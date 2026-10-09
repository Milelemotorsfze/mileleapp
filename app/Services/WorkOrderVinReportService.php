<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * VIN report: one row per VIN linked to a Work Order.
 *
 * Data flow: VIN (w_o_vehicles) -> Work Order -> Sales Order (-> quotation incoterm)
 *            VIN -> Vehicle / Stock record -> Purchase Order (preferred destination) / GRN
 *
 * The rows are built from a few simple primary-key queries, combined in PHP and cached for a
 * short time, so paging / sorting / filtering never re-runs heavy SQL.
 */
class WorkOrderVinReportService
{
    /** Report columns in display order => export heading. */
    public const COLUMNS = [
        'vin' => 'Chassis / VIN Number',
        'wo_number' => 'WO Number',
        'so_number' => 'SO Number',
        'pol' => 'POL (Port of Loading)',
        'pod' => 'POD (Port of Discharge)',
        'actual_destination' => 'Actual Destination',
        'preferred_destination' => 'Preferred Destination',
        'sales_person' => 'Salesperson Name',
        'wo_status' => 'WO Status',
        'delivery_status' => 'Delivery Status',
        'brand' => 'Brand',
        'model_line' => 'Model Line',
        'variant' => 'Variant',
        'model_year' => 'Model Year',
        'engine' => 'Engine Number',
        'exterior_colour' => 'Exterior Color',
        'interior_colour' => 'Interior Color',
        'incoterm' => 'Incoterms',
        'grn_date' => 'GRN Date',
        'wo_date' => 'WO Date',
        'destination_match' => 'Destination Match Status',
    ];

    /** Multi-select filters: request key => report column. */
    public const LIST_FILTERS = [
        'pol' => 'pol',
        'pod' => 'pod',
        'actual_destination' => 'actual_destination',
        'destination_match' => 'destination_match',
        'sales_person' => 'sales_person',
        'wo_status' => 'wo_status',
        'delivery_status' => 'delivery_status',
        'brand' => 'brand',
        'model_line' => 'model_line',
        'variant' => 'variant',
        'model_year' => 'model_year',
        'incoterm' => 'incoterm',
    ];

    public const MATCHING = 'Matching';
    public const NOT_MATCHING = 'Not Matching';
    public const NOT_AVAILABLE = 'Not Available';

    private const CACHE_KEY = 'work_order_vin_report_rows';
    private const CACHE_SECONDS = 300;

    /**
     * All report rows (every user), cached.
     *
     * @return array{built_at: string, rows: array<int, array>}
     */
    public function dataset(bool $fresh = false): array
    {
        if ($fresh) {
            Cache::forget(self::CACHE_KEY);
        }

        return Cache::remember(self::CACHE_KEY, self::CACHE_SECONDS, fn () => [
            'built_at' => now()->toDateTimeString(),
            'rows' => $this->buildRows(),
        ]);
    }

    /**
     * Rows visible to the user: all rows, or only work orders they created / are the salesperson of.
     */
    public function scopeRows(array $rows, ?int $limitedToUserId): array
    {
        if (!$limitedToUserId) {
            return $rows;
        }

        return array_values(array_filter($rows, fn ($row) =>
            (int) $row['created_by'] === $limitedToUserId || (int) $row['sales_person_id'] === $limitedToUserId
        ));
    }

    public function applyFilters(array $rows, array $filters): array
    {
        $search = is_string($filters['vin_search'] ?? null) ? trim($filters['vin_search']) : '';

        $dateRanges = [];
        foreach (['wo_date', 'grn_date'] as $dateColumn) {
            $from = $this->validDate($filters[$dateColumn . '_from'] ?? null);
            $to = $this->validDate($filters[$dateColumn . '_to'] ?? null);
            if ($from || $to) {
                $dateRanges[$dateColumn] = [$from, $to];
            }
        }

        $lists = [];
        foreach (self::LIST_FILTERS as $key => $column) {
            // Only plain string values are accepted (ignores nested arrays from crafted requests).
            $values = array_filter(
                (array) ($filters[$key] ?? []),
                fn ($v) => is_scalar($v) && trim((string) $v) !== ''
            );
            if (!empty($values)) {
                $lists[$column] = array_flip(array_map('strval', $values));
            }
        }

        if ($search === '' && empty($dateRanges) && empty($lists)) {
            return $rows;
        }

        return array_values(array_filter($rows, function ($row) use ($search, $dateRanges, $lists) {
            if ($search !== ''
                && stripos((string) $row['vin'], $search) === false
                && stripos((string) $row['wo_number'], $search) === false
                && stripos((string) $row['so_number'], $search) === false) {
                return false;
            }

            foreach ($dateRanges as $column => [$from, $to]) {
                $date = $row[$column] ? substr($row[$column], 0, 10) : null;
                if ($date === null || ($from && $date < $from) || ($to && $date > $to)) {
                    return false;
                }
            }

            foreach ($lists as $column => $allowed) {
                if ($row[$column] === null || !isset($allowed[(string) $row[$column]])) {
                    return false;
                }
            }

            return true;
        }));
    }

    /**
     * Sort by a report column (unknown columns fall back to WO date). Blank values always go last.
     */
    public function sortRows(array $rows, string $column, string $direction): array
    {
        if (!array_key_exists($column, self::COLUMNS)) {
            $column = 'wo_date';
        }
        $descending = strtolower($direction) === 'desc';

        usort($rows, function ($a, $b) use ($column, $descending) {
            $x = (string) ($a[$column] ?? '');
            $y = (string) ($b[$column] ?? '');
            if ($x === '' || $y === '') {
                return ($x === '') <=> ($y === '');
            }
            $result = strnatcasecmp($x, $y);
            if ($result === 0) {
                $result = $a['wo_vehicle_id'] <=> $b['wo_vehicle_id'];
            }
            return $descending ? -$result : $result;
        });

        return $rows;
    }

    /**
     * Distinct values for each multi-select filter, taken from the given rows.
     */
    public function filterOptions(array $rows): array
    {
        $options = [];
        foreach (self::LIST_FILTERS as $key => $column) {
            $values = [];
            foreach ($rows as $row) {
                $value = $row[$column];
                if ($value !== null && trim((string) $value) !== '') {
                    $values[(string) $value] = true;
                }
            }
            $values = array_keys($values);
            natcasesort($values);
            $options[$key] = array_values($values);
        }
        $options['destination_match'] = [self::MATCHING, self::NOT_MATCHING, self::NOT_AVAILABLE];

        return $options;
    }

    /**
     * Row as sent to the browser / export: report columns plus link and badge fields (no access fields).
     */
    public function publicRow(array $row): array
    {
        unset($row['created_by'], $row['sales_person_id']);
        return $row;
    }

    private function validDate($value): ?string
    {
        if (!is_string($value) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return null;
        }
        [$y, $m, $d] = array_map('intval', explode('-', $value));
        return checkdate($m, $d, $y) ? $value : null;
    }

    private function buildRows(): array
    {
        // 1. VIN + WO + stock record details: primary-key joins only.
        $base = DB::table('w_o_vehicles as wv')
            ->join('work_orders as wo', 'wo.id', '=', 'wv.work_order_id')
            ->leftJoin('vehicles as v', 'v.id', '=', 'wv.vehicle_id')
            ->leftJoin('varaints as var', 'var.id', '=', 'v.varaints_id')
            ->leftJoin('master_model_lines as ml', 'ml.id', '=', 'var.master_model_lines_id')
            ->leftJoin('brands as ml_brand', 'ml_brand.id', '=', 'ml.brand_id')
            ->leftJoin('brands as var_brand', 'var_brand.id', '=', 'var.brands_id')
            ->leftJoin('color_codes as ext', 'ext.id', '=', 'v.ex_colour')
            ->leftJoin('color_codes as intc', 'intc.id', '=', 'v.int_colour')
            ->leftJoin('purchasing_order as po', 'po.id', '=', 'v.purchasing_order_id')
            ->leftJoin('countries as po_fd', 'po_fd.id', '=', 'po.fd')
            ->leftJoin('grn as g', 'g.id', '=', 'v.grn_id')
            ->leftJoin('users as wo_sp', 'wo_sp.id', '=', 'wo.sales_person_id')
            ->whereNull('wv.deleted_at')
            ->whereNull('wo.deleted_at')
            ->selectRaw("
                wv.id as wo_vehicle_id,
                wo.id as work_order_id,
                wo.created_by,
                wo.sales_person_id,
                wv.vin,
                wo.wo_number,
                wo.so_number,
                NULLIF(TRIM(wo.port_of_loading), '') as pol,
                NULLIF(TRIM(wo.port_of_discharge), '') as pod,
                NULLIF(TRIM(wo.final_destination), '') as actual_destination,
                COALESCE(NULLIF(TRIM(po_fd.name), ''), NULLIF(TRIM(wv.preferred_destination), '')) as preferred_destination,
                wo_sp.name as wo_sales_person,
                COALESCE(ml_brand.brand_name, var_brand.brand_name, NULLIF(wv.brand, '')) as brand,
                ml.model_line,
                COALESCE(var.name, NULLIF(wv.variant, '')) as variant,
                COALESCE(NULLIF(var.my, ''), NULLIF(wv.model_year, '')) as model_year,
                COALESCE(NULLIF(v.engine, ''), NULLIF(wv.engine, '')) as engine,
                COALESCE(ext.name, NULLIF(wv.exterior_colour, '')) as exterior_colour,
                COALESCE(intc.name, NULLIF(wv.interior_colour, '')) as interior_colour,
                COALESCE(g.date, v.netsuit_grn_date) as grn_date,
                wo.date as wo_date
            ")
            ->get();

        // 2. Latest WO status per work order.
        $woStatus = [];
        foreach (DB::table('wo_status')->select('id', 'wo_id', 'status', 'status_changed_at')->get() as $s) {
            $current = $woStatus[$s->wo_id] ?? null;
            if (!$current || [(string) $s->status_changed_at, $s->id] > [(string) $current->status_changed_at, $current->id]) {
                $woStatus[$s->wo_id] = $s;
            }
        }

        // 3. Latest delivery status per WO vehicle.
        $deliveryStatus = [];
        foreach (DB::table('wo_veh_del_status')->select('id', 'w_o_vehicle_id', 'status', 'created_at')->get() as $d) {
            $current = $deliveryStatus[$d->w_o_vehicle_id] ?? null;
            if (!$current || [(string) $d->created_at, $d->id] > [(string) $current->created_at, $current->id]) {
                $deliveryStatus[$d->w_o_vehicle_id] = $d;
            }
        }

        // 4. One SO per SO number (SO numbers are not unique) and one quotation detail per quotation.
        $soByNumber = [];
        foreach (DB::table('so')->whereNull('deleted_at')->whereNotNull('so_number')
            ->select('id', 'so_number', 'quotation_id', 'sales_person_id')->get() as $so) {
            $key = strtolower(trim($so->so_number));
            if (!isset($soByNumber[$key]) || $so->id > $soByNumber[$key]->id) {
                $soByNumber[$key] = $so;
            }
        }

        $incotermByQuotation = [];
        foreach (DB::table('quotation_details')->whereNull('deleted_at')
            ->select('id', 'quotation_id', 'incoterm')->orderBy('id')->get() as $qd) {
            $incotermByQuotation[$qd->quotation_id] = $qd->incoterm; // highest id wins
        }

        $soSalesPersonIds = array_filter(array_unique(array_map(fn ($so) => $so->sales_person_id, $soByNumber)));
        $userNames = empty($soSalesPersonIds) ? [] : DB::table('users')->whereIn('id', $soSalesPersonIds)->pluck('name', 'id')->all();

        // 5. Number of non-cancelled WOs each VIN is linked to (more than one = duplicate link).
        $activeWosByVin = [];
        foreach ($base as $r) {
            if ($r->vin === null || $r->vin === '') {
                continue;
            }
            $status = $woStatus[$r->work_order_id]->status ?? '';
            if ($status !== 'Cancelled') {
                $activeWosByVin[$r->vin][$r->work_order_id] = true;
            }
        }

        $rows = [];
        foreach ($base as $r) {
            $so = $r->so_number !== null ? ($soByNumber[strtolower(trim($r->so_number))] ?? null) : null;
            $incoterm = $so && $so->quotation_id ? trim((string) ($incotermByQuotation[$so->quotation_id] ?? '')) : '';

            $rows[] = [
                'wo_vehicle_id' => (int) $r->wo_vehicle_id,
                'work_order_id' => (int) $r->work_order_id,
                'created_by' => $r->created_by,
                'sales_person_id' => $r->sales_person_id,
                'vin' => $r->vin,
                'wo_number' => $r->wo_number,
                'so_number' => $r->so_number,
                'pol' => $r->pol,
                'pod' => $r->pod,
                'actual_destination' => $r->actual_destination,
                'preferred_destination' => $r->preferred_destination,
                'sales_person' => $r->wo_sales_person ?: ($so ? ($userNames[$so->sales_person_id] ?? null) : null),
                'wo_status' => $woStatus[$r->work_order_id]->status ?? 'Active',
                'delivery_status' => $deliveryStatus[$r->wo_vehicle_id]->status ?? 'On Hold',
                'brand' => $r->brand,
                'model_line' => $r->model_line,
                'variant' => $r->variant,
                'model_year' => $r->model_year !== null ? (string) $r->model_year : null,
                'engine' => $r->engine,
                'exterior_colour' => $r->exterior_colour,
                'interior_colour' => $r->interior_colour,
                'incoterm' => $incoterm !== '' ? $incoterm : null,
                'grn_date' => $r->grn_date ? substr($r->grn_date, 0, 10) : null,
                'wo_date' => $r->wo_date ? substr($r->wo_date, 0, 10) : null,
                'destination_match' => $this->destinationMatch($r->actual_destination, $r->preferred_destination),
                'active_wo_count' => isset($activeWosByVin[$r->vin]) ? count($activeWosByVin[$r->vin]) : 0,
            ];
        }

        return $rows;
    }

    /**
     * Compares WO destination with PO destination, ignoring case and extra spaces.
     */
    private function destinationMatch(?string $actual, ?string $preferred): string
    {
        $normalize = fn (?string $value) => mb_strtolower(trim(preg_replace('/[\s\x{00A0}]+/u', ' ', (string) $value)));
        $actual = $normalize($actual);
        $preferred = $normalize($preferred);

        if ($actual === '' || $preferred === '') {
            return self::NOT_AVAILABLE;
        }

        return $actual === $preferred ? self::MATCHING : self::NOT_MATCHING;
    }
}
