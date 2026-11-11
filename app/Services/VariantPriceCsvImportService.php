<?php

namespace App\Services;

use App\Models\Vehicles;
use App\Models\Vehicleslog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use SplFileObject;

/**
 * Bulk update of variant prices from a CSV file (Variant Price page, "Upload as CSV").
 *
 * A row is identified by its six key columns (brand, model line, variant, model year, interior
 * and exterior colour names), compared case-insensitively with surrounding/double spaces
 * ignored. A key can stand for more than one variant/colour id combination (colour names are
 * not unique); every vehicle under it is updated, exactly as the page shows them as one row.
 *
 * Only Minimum Commission, Price and Bottom Price are written. A blank cell leaves that field
 * as it is. Keys that do not exist are reported as errors, never created.
 *
 * analyse() never writes; apply() writes the updates analyse() returned, in one transaction.
 */
class VariantPriceCsvImportService
{
    /** Upper limit of rows per file (the page lists a few thousand combinations). */
    public const MAX_ROWS = 10000;

    /** vehicles.price and vehicles.bottom_price are decimal(10,2). */
    public const MAX_AMOUNT = 99999999.99;

    /** Key columns, in file order: field => header. */
    public const KEY_COLUMNS = [
        'brand' => 'Brand',
        'model_line' => 'Model Line',
        'variant' => 'Variant',
        'model_year' => 'Model Year',
        'interior_colour' => 'Interior Colour',
        'exterior_colour' => 'Exterior Colour',
    ];

    /** Fields the upload writes, in file order: vehicles column => header. */
    public const UPDATABLE_COLUMNS = [
        'minimum_commission' => 'Minimum Commission',
        'price' => 'Price',
        'bottom_price' => 'Bottom Price',
    ];

    /** Fields that only take whole numbers (the page's own input accepts digits only). */
    public const WHOLE_NUMBER_COLUMNS = ['minimum_commission'];

    /** Columns of the downloaded file. */
    public const EXPORT_COLUMNS = self::KEY_COLUMNS + self::UPDATABLE_COLUMNS;

    /** Accepted spellings of each header, after normalizeHeader(). */
    private const HEADER_ALIASES = [
        'brand' => ['brand', 'brand name'],
        'model_line' => ['model line', 'modelline', 'model'],
        'variant' => ['variant', 'variant name'],
        'model_year' => ['model year', 'my', 'year'],
        'interior_colour' => ['interior colour', 'int colour', 'interior'],
        'exterior_colour' => ['exterior colour', 'ext colour', 'ex colour', 'exterior'],
        'minimum_commission' => ['minimum commission', 'min commission'],
        'price' => ['price', 'selling price'],
        'bottom_price' => ['bottom price'],
    ];

    public const LOG_STATUS = 'Update Vehicles Selling Price / GP';

    /**
     * Reads and validates a CSV file against the current vehicles. Nothing is written.
     *
     * @return array{
     *     total_rows: int,
     *     updates: list<array{line: int, key: array<string, string>, combinations: list<array{0: int, 1: ?string, 2: ?string}>, vehicle_count: int, values: array<string, float>, changes: array<string, array{from: string, to: string}>}>,
     *     unchanged: list<array{line: int, key: array<string, string>, reason: string}>,
     *     errors: list<array{line: int, key: array<string, string>, messages: list<string>}>,
     * }
     *
     * @throws InvalidArgumentException when the file itself cannot be used (no header row,
     *                                  missing columns, no data rows, too many rows).
     */
    public function analyse(string $path): array
    {
        $rows = $this->readRows($path);
        $existing = $this->existingCombinations();

        // Lines per key, to reject every row of a key that appears more than once.
        $linesByKey = [];
        foreach ($rows as $line => $row) {
            $linesByKey[$this->keyOf($row)][] = $line;
        }

        $result = ['total_rows' => count($rows), 'updates' => [], 'unchanged' => [], 'errors' => []];

        foreach ($rows as $line => $row) {
            $key = $this->keyOf($row);
            $display = array_intersect_key($row, self::KEY_COLUMNS);
            $messages = [];

            $duplicateLines = array_values(array_diff($linesByKey[$key], [$line]));
            if ($duplicateLines) {
                $messages[] = 'Duplicate row: the same Brand / Model Line / Variant / Model Year / colours are also on row '
                    . implode(', ', $duplicateLines) . '.';
            }

            $values = [];
            foreach (self::UPDATABLE_COLUMNS as $field => $header) {
                $parsed = $this->parseAmount($row[$field] ?? '', in_array($field, self::WHOLE_NUMBER_COLUMNS, true));
                if (is_string($parsed)) {
                    $messages[] = "{$header} {$parsed}";
                } elseif ($parsed !== null) {
                    $values[$field] = $parsed;
                }
            }

            $combination = $existing['combinations'][$key] ?? null;
            if ($combination === null) {
                $messages[] = isset($existing['variants'][$this->variantKeyOf($row)])
                    ? 'This variant has no vehicles with this Interior Colour and Exterior Colour.'
                    : 'No vehicles found for this Brand / Model Line / Variant / Model Year.';
            } elseif (!$messages) {
                $messages = $this->priceOrderErrors($values, $combination);
            }

            if ($messages) {
                $result['errors'][] = ['line' => $line, 'key' => $display, 'messages' => $messages];
                continue;
            }

            if (!$values) {
                $result['unchanged'][] = ['line' => $line, 'key' => $display, 'reason' => 'Minimum Commission, Price and Bottom Price are blank.'];
                continue;
            }

            $changes = [];
            foreach ($values as $field => $value) {
                if (!$this->alreadyEquals($combination[$field], $combination['vehicle_count'], $value)) {
                    $changes[$field] = ['from' => $this->describeCurrent($combination[$field]), 'to' => self::formatAmount($value)];
                }
            }

            if (!$changes) {
                $result['unchanged'][] = ['line' => $line, 'key' => $display, 'reason' => 'Already has these values.'];
                continue;
            }

            $result['updates'][] = [
                'line' => $line,
                'key' => $display,
                'combinations' => $combination['combinations'],
                'vehicle_count' => $combination['vehicle_count'],
                'values' => array_intersect_key($values, $changes),
                'changes' => $changes,
            ];
        }

        return $result;
    }

    /**
     * Writes the updates from analyse() and logs every changed value in vehicles_log.
     *
     * Vehicles are read once, changed ones are updated with one UPDATE per (field, new value)
     * and the log rows are inserted in batches, all in one transaction. Only vehicles whose
     * value actually changes are touched.
     *
     * @return array{rows: int, vehicles: int, values: int}
     */
    public function apply(array $updates, int $userId, ?string $role): array
    {
        if (!$updates) {
            return ['rows' => 0, 'vehicles' => 0, 'values' => 0];
        }

        $newValues = []; // combination id => [field => value]
        foreach ($updates as $update) {
            foreach ($update['combinations'] as $combination) {
                $newValues[self::combinationId(...$combination)] = $update['values'];
            }
        }
        $variantIds = array_values(array_unique(array_map(fn ($c) => $c[0], array_merge(...array_column($updates, 'combinations')))));

        $now = Carbon::now();

        return DB::transaction(function () use ($updates, $variantIds, $newValues, $now, $userId, $role) {
            $idsByValue = []; // "field|value" => [vehicle ids]
            $logRows = [];
            $changedVehicles = [];

            $vehicles = Vehicles::query()
                ->toBase()
                ->select('id', 'varaints_id', 'int_colour', 'ex_colour', ...array_keys(self::UPDATABLE_COLUMNS))
                ->whereIn('varaints_id', $variantIds)
                ->lockForUpdate()
                ->get();

            foreach ($vehicles as $vehicle) {
                $values = $newValues[self::combinationId($vehicle->varaints_id, $vehicle->int_colour, $vehicle->ex_colour)] ?? null;
                if ($values === null) {
                    continue;
                }
                foreach ($values as $field => $value) {
                    if ($vehicle->$field !== null && $vehicle->$field !== '' && self::cents($vehicle->$field) === self::cents($value)) {
                        continue;
                    }
                    // Prices are decimal(10,2); minimum_commission is text holding a whole number.
                    $newValue = number_format($value, in_array($field, self::WHOLE_NUMBER_COLUMNS, true) ? 0 : 2, '.', '');
                    $idsByValue[$field . '|' . $newValue][] = $vehicle->id;
                    $changedVehicles[$vehicle->id] = true;
                    $logRows[] = [
                        'time' => $now->toTimeString(),
                        'date' => $now->toDateString(),
                        'status' => self::LOG_STATUS,
                        'vehicles_id' => $vehicle->id,
                        'field' => $field,
                        'old_value' => $vehicle->$field,
                        'new_value' => $newValue,
                        'category' => 'Sales',
                        'created_by' => $userId,
                        'role' => $role,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            foreach ($idsByValue as $fieldAndValue => $ids) {
                [$field, $value] = explode('|', $fieldAndValue, 2);
                foreach (array_chunk($ids, 500) as $chunk) {
                    Vehicles::whereIn('id', $chunk)->update([$field => $value, 'updated_at' => $now]);
                }
            }
            foreach (array_chunk($logRows, 500) as $chunk) {
                Vehicleslog::insert($chunk);
            }

            return ['rows' => count($updates), 'vehicles' => count($changedVehicles), 'values' => count($logRows)];
        });
    }

    /**
     * Current values, one row per key, for the "Download CSV" template. A value is left blank
     * when the key's vehicles do not all have the same one (blank = no change on upload).
     *
     * @return list<array<string, string>> rows keyed like EXPORT_COLUMNS
     */
    public function exportRows(): array
    {
        $rows = [];
        $sortKeys = [];
        foreach ($this->existingCombinations()['combinations'] as $combination) {
            $sortKeys[] = implode("\x1f", $combination['names']);
            $row = $combination['names'];
            foreach (array_keys(self::UPDATABLE_COLUMNS) as $field) {
                $current = $combination[$field];
                $uniform = $current['min'] !== null && $current['min'] === $current['max'] && $current['count'] === $combination['vehicle_count'];
                $row[$field] = $uniform ? self::plainAmount((float) $current['min']) : '';
            }
            $rows[] = $row;
        }

        uasort($sortKeys, 'strnatcasecmp');

        return array_map(fn ($index) => $rows[$index], array_keys($sortKeys));
    }

    /**
     * Parses one amount cell. Returns null for a blank cell (no change), the amount, or an error
     * message (string) to be prefixed with the column name.
     */
    public function parseAmount(string $raw, bool $wholeNumber = false): float|string|null
    {
        $value = str_replace([' ', "\u{00A0}"], '', trim($raw));
        if ($value === '') {
            return null;
        }
        if (preg_match('/^-\s*\d/', $value) || str_starts_with($value, '(')) {
            return 'cannot be negative.';
        }
        // Commas only as thousands separators: 150,000 or (Indian grouping) 1,50,000. Anything
        // else, such as a decimal comma (150000,50), is rejected rather than misread.
        if (!preg_match('/^(\d+|\d{1,3}(,\d{3})+|\d{1,2}(,\d{2})*,\d{3})(\.\d+)?$/', $value)) {
            return 'must be a number ("' . trim($raw) . '").';
        }
        $value = str_replace(',', '', $value);
        if (preg_match('/\.\d{3,}$/', $value)) {
            return 'can have at most 2 decimal places.';
        }
        $amount = (float) $value;
        if ($wholeNumber && fmod($amount, 1.0) != 0.0) {
            return 'must be a whole number.';
        }
        if ($amount <= 0) {
            return 'must be greater than 0.';
        }
        if ($amount > self::MAX_AMOUNT) {
            return 'is too large (maximum ' . number_format(self::MAX_AMOUNT, 2) . ').';
        }

        return $amount;
    }

    /** Lower-cased, trimmed, inner whitespace collapsed: how key cells are compared. */
    public static function normalize(?string $value): string
    {
        return mb_strtolower(trim(preg_replace('/\s+/u', ' ', (string) $value)));
    }

    /** "150,000" / "150,000.50". */
    public static function formatAmount(float $amount): string
    {
        return number_format($amount, fmod($amount, 1.0) == 0.0 ? 0 : 2, '.', ',');
    }

    /**
     * Reads the CSV into rows keyed by spreadsheet row number (header = row 1), each with the
     * known fields as trimmed strings. Fully blank rows are skipped.
     *
     * @return array<int, array<string, string>>
     */
    public function readRows(string $path): array
    {
        $file = new SplFileObject($path, 'r');
        $file->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY);
        $file->setCsvControl($this->detectDelimiter($path), '"', '');

        $columns = null; // field => column index
        $rows = [];
        foreach ($file as $index => $cells) {
            if (!is_array($cells) || $cells === [null]) {
                continue;
            }
            $cells = array_map(fn ($cell) => $this->toUtf8(trim((string) $cell)), $cells);

            if ($columns === null) {
                $columns = $this->mapHeader($cells);
                continue;
            }
            if (implode('', $cells) === '') {
                continue;
            }
            if (count($rows) >= self::MAX_ROWS) {
                throw new InvalidArgumentException('The file has more than ' . number_format(self::MAX_ROWS) . ' rows. Please split it into smaller files.');
            }

            $row = [];
            foreach ($columns as $field => $position) {
                $row[$field] = $cells[$position] ?? '';
            }
            $rows[$index + 1] = $row;
        }

        if ($columns === null) {
            throw new InvalidArgumentException('The file is empty.');
        }
        if (!$rows) {
            throw new InvalidArgumentException('The file has a header row but no data rows.');
        }

        return $rows;
    }

    /**
     * Every key that exists among non-deleted vehicles of existing variants (the rows the
     * Variant Price page lists), with its id combinations and current prices; plus the set of
     * variant keys, to explain a key that does not match.
     *
     * One grouped query over vehicles, names joined onto the grouped rows afterwards.
     */
    private function existingCombinations(): array
    {
        // 0 (and, for the text minimum_commission column, '') counts as "no value", as on the page.
        $values = [
            'price' => 'NULLIF(price, 0)',
            'bottom_price' => 'NULLIF(bottom_price, 0)',
            'minimum_commission' => "NULLIF(CAST(NULLIF(TRIM(minimum_commission), '') AS DECIMAL(14, 2)), 0)",
        ];

        $grouped = Vehicles::query()
            ->toBase()
            ->select('varaints_id', 'int_colour', 'ex_colour')
            ->selectRaw('COUNT(*) AS vehicle_count')
            ->whereIn('varaints_id', DB::table('varaints')->select('id'))
            ->groupBy('varaints_id', 'int_colour', 'ex_colour');
        foreach ($values as $field => $expression) {
            $grouped->selectRaw("MIN({$expression}) AS {$field}_min, MAX({$expression}) AS {$field}_max, COUNT({$expression}) AS {$field}_count");
        }

        $rows = DB::query()
            ->fromSub($grouped, 'v')
            ->select('v.*', 'brands.brand_name', 'master_model_lines.model_line', 'varaints.name AS variant', 'varaints.my AS model_year')
            ->addSelect('int_color.name AS interior_colour', 'ex_color.name AS exterior_colour')
            ->leftJoin('varaints', 'v.varaints_id', '=', 'varaints.id')
            ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
            ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
            ->leftJoin('color_codes as int_color', 'v.int_colour', '=', 'int_color.id')
            ->leftJoin('color_codes as ex_color', 'v.ex_colour', '=', 'ex_color.id')
            ->get();

        $combinations = [];
        $variants = [];
        foreach ($rows as $row) {
            $names = [
                'brand' => (string) $row->brand_name,
                'model_line' => (string) $row->model_line,
                'variant' => (string) $row->variant,
                'model_year' => (string) $row->model_year,
                'interior_colour' => (string) $row->interior_colour,
                'exterior_colour' => (string) $row->exterior_colour,
            ];
            $key = $this->keyOf($names);
            $variants[$this->variantKeyOf($names)] = true;

            $entry = $combinations[$key] ?? [
                'names' => $names,
                'combinations' => [],
                'vehicle_count' => 0,
            ] + array_fill_keys(array_keys($values), ['min' => null, 'max' => null, 'count' => 0]);
            $entry['combinations'][] = [(int) $row->varaints_id, self::colourId($row->int_colour), self::colourId($row->ex_colour)];
            $entry['vehicle_count'] += (int) $row->vehicle_count;
            foreach (array_keys($values) as $field) {
                $entry[$field] = self::mergeRange($entry[$field], $row->{$field . '_min'}, $row->{$field . '_max'}, (int) $row->{$field . '_count'});
            }
            $combinations[$key] = $entry;
        }

        return ['combinations' => $combinations, 'variants' => $variants];
    }

    /** Bottom Price must be <= Price, using the current value of whichever field is blank. */
    private function priceOrderErrors(array $values, array $combination): array
    {
        if (!isset($values['price']) && !isset($values['bottom_price'])) {
            return [];
        }

        $price = $values['price'] ?? ($combination['price']['min'] !== null ? (float) $combination['price']['min'] : null);
        $bottom = $values['bottom_price'] ?? ($combination['bottom_price']['max'] !== null ? (float) $combination['bottom_price']['max'] : null);

        if ($bottom === null) {
            return [];
        }
        if ($price === null) {
            return ['Bottom Price cannot be set: there is no Price for this combination. Add a Price on the same row.'];
        }
        if (self::cents($bottom) <= self::cents($price)) {
            return [];
        }

        $priceLabel = isset($values['price']) ? 'Price' : 'the current Price';
        $bottomLabel = isset($values['bottom_price']) ? 'Bottom Price' : 'The current Bottom Price';

        return ["{$bottomLabel} (" . self::formatAmount($bottom) . ") must be less than or equal to {$priceLabel} (" . self::formatAmount($price) . ').'];
    }

    /** True when every vehicle of the key already has exactly this value. */
    private function alreadyEquals(array $current, int $vehicleCount, float $value): bool
    {
        return $current['count'] === $vehicleCount
            && $current['min'] !== null
            && self::cents($current['min']) === self::cents($value)
            && self::cents($current['max']) === self::cents($value);
    }

    private function describeCurrent(array $current): string
    {
        if ($current['min'] === null) {
            return '—';
        }
        $min = self::formatAmount((float) $current['min']);
        $max = self::formatAmount((float) $current['max']);

        return $min === $max ? $min : "{$min} – {$max}";
    }

    /** @return array<string, int> field => column index */
    private function mapHeader(array $cells): array
    {
        $lookup = [];
        foreach (self::HEADER_ALIASES as $field => $aliases) {
            foreach ($aliases as $alias) {
                $lookup[$alias] = $field;
            }
        }

        $columns = [];
        foreach ($cells as $position => $cell) {
            $field = $lookup[$this->normalizeHeader($cell)] ?? null;
            if ($field !== null && !isset($columns[$field])) {
                $columns[$field] = $position;
            }
        }

        $missing = array_values(array_diff_key(self::KEY_COLUMNS, $columns));
        if ($missing) {
            throw new InvalidArgumentException('Missing column(s): ' . implode(', ', $missing)
                . '. The first row must have the headers: ' . implode(', ', self::EXPORT_COLUMNS) . '.');
        }
        if (!array_intersect_key(self::UPDATABLE_COLUMNS, $columns)) {
            throw new InvalidArgumentException('The file needs at least one of the columns: ' . implode(', ', self::UPDATABLE_COLUMNS) . '.');
        }

        return $columns;
    }

    private function normalizeHeader(string $cell): string
    {
        $cell = str_replace("\u{FEFF}", '', $cell);
        $cell = self::normalize(str_replace(['_', '-'], ' ', $cell));

        return preg_replace('/\bcolor\b/', 'colour', $cell);
    }

    /** Comma, semicolon (Excel in some locales) or tab, whichever the header row uses most. */
    private function detectDelimiter(string $path): string
    {
        $handle = fopen($path, 'r');
        $firstLine = (string) fgets($handle);
        fclose($handle);

        $counts = [',' => substr_count($firstLine, ','), ';' => substr_count($firstLine, ';'), "\t" => substr_count($firstLine, "\t")];
        arsort($counts);

        return reset($counts) > 0 ? array_key_first($counts) : ',';
    }

    /** Excel's plain "CSV" format saves Windows-1252, not UTF-8. */
    private function toUtf8(string $value): string
    {
        return mb_check_encoding($value, 'UTF-8') ? $value : mb_convert_encoding($value, 'UTF-8', 'Windows-1252');
    }

    private function keyOf(array $row): string
    {
        return implode("\x1f", array_map(fn ($field) => self::normalize($row[$field] ?? ''), array_keys(self::KEY_COLUMNS)));
    }

    private function variantKeyOf(array $row): string
    {
        return implode("\x1f", array_map(fn ($field) => self::normalize($row[$field] ?? ''), ['brand', 'model_line', 'variant', 'model_year']));
    }

    private static function mergeRange(array $range, $min, $max, int $count): array
    {
        if ($min !== null && ($range['min'] === null || (float) $min < (float) $range['min'])) {
            $range['min'] = $min;
        }
        if ($max !== null && ($range['max'] === null || (float) $range['max'] < (float) $max)) {
            $range['max'] = $max;
        }
        $range['count'] += $count;

        return $range;
    }

    /** Colour ids are stored as strings, possibly null or empty; kept exactly as stored. */
    private static function colourId($value): ?string
    {
        return $value === null ? null : (string) $value;
    }

    private static function combinationId($variantId, $intColour, $exColour): string
    {
        return json_encode([(int) $variantId, self::colourId($intColour), self::colourId($exColour)]);
    }

    private static function cents($amount): int
    {
        return (int) round(((float) $amount) * 100);
    }

    /** "150000" / "150000.5" for the downloadable file (no thousands separators). */
    private static function plainAmount(float $amount): string
    {
        return rtrim(rtrim(number_format($amount, 2, '.', ''), '0'), '.');
    }
}
