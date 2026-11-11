<?php

namespace App\Http\Controllers;

use App\Models\UserActivities;
use App\Services\VariantPriceCsvImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Variant Price page: download the current prices as CSV, upload an edited CSV, preview what
 * it will change, then confirm or cancel.
 *
 * The uploaded file is kept in storage/app/variant-price-imports until it is confirmed or
 * cancelled (or for a day). Preview and confirm both validate it against the current prices,
 * so a confirm applies exactly what is valid at that moment.
 */
class VariantPriceImportController extends Controller
{
    private const DIRECTORY = 'variant-price-imports';

    /** Same permission as the Variant Price menu entry. */
    private const PERMISSION = 'vehicle-selling-price';

    public function __construct(private VariantPriceCsvImportService $importer)
    {
    }

    public function export(): StreamedResponse
    {
        $this->authorizeAccess();
        $rows = $this->importer->exportRows();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\u{FEFF}"); // so Excel opens it as UTF-8
            fputcsv($out, array_values(VariantPriceCsvImportService::EXPORT_COLUMNS));
            foreach ($rows as $row) {
                fputcsv($out, array_map(fn ($field) => $row[$field] ?? '', array_keys(VariantPriceCsvImportService::EXPORT_COLUMNS)));
            }
            fclose($out);
        }, 'variant-prices-' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function upload(Request $request): RedirectResponse
    {
        $this->authorizeAccess();
        $request->validate(
            ['file' => 'required|file|mimes:csv,txt|max:5120'],
            ['file.mimes' => 'Please upload a .csv file.', 'file.max' => 'The file may not be larger than 5 MB.']
        );

        $this->deleteStaleUploads();

        $token = (string) Str::uuid();
        $request->file('file')->storeAs(self::DIRECTORY, $this->fileName($token));

        try {
            $this->importer->analyse($this->path($token));
        } catch (InvalidArgumentException $e) {
            Storage::delete(self::DIRECTORY . '/' . $this->fileName($token));

            return redirect()->route('variantprices.allvariantprice')->withErrors(['file' => $e->getMessage()]);
        }

        return redirect()->route('variantprices.import.preview', $token);
    }

    public function preview(string $token)
    {
        $this->authorizeAccess();
        $path = $this->existingPath($token);

        return view('variant-prices.import-preview', [
            'token' => $token,
            'result' => $this->importer->analyse($path),
        ]);
    }

    public function confirm(string $token): RedirectResponse
    {
        $this->authorizeAccess();
        $path = $this->existingPath($token);

        try {
            $result = $this->importer->analyse($path);
        } catch (InvalidArgumentException $e) {
            return redirect()->route('variantprices.allvariantprice')->withErrors(['file' => $e->getMessage()]);
        }

        $user = Auth::user();
        $applied = $this->importer->apply($result['updates'], $user->id, $user->selectedRole);
        Storage::delete(self::DIRECTORY . '/' . $this->fileName($token));

        $activity = new UserActivities();
        $activity->activity = "Variant Price CSV Upload: {$applied['rows']} rows, {$applied['vehicles']} vehicles updated";
        $activity->users_id = $user->id;
        $activity->save();

        Log::info('Variant price CSV upload', [
            'user_id' => $user->id,
            'rows_in_file' => $result['total_rows'],
            'rows_updated' => $applied['rows'],
            'vehicles_updated' => $applied['vehicles'],
            'rows_unchanged' => count($result['unchanged']),
            'rows_with_errors' => count($result['errors']),
        ]);

        $message = number_format($applied['rows']) . ' row(s) updated (' . number_format($applied['vehicles']) . ' vehicles).';
        if ($result['errors']) {
            $message .= ' ' . number_format(count($result['errors'])) . ' row(s) with errors were skipped.';
        }

        return redirect()->route('variantprices.allvariantprice')->with('success', $message);
    }

    public function cancel(string $token): RedirectResponse
    {
        $this->authorizeAccess();
        Storage::delete(self::DIRECTORY . '/' . $this->fileName($this->validToken($token)));

        return redirect()->route('variantprices.allvariantprice');
    }

    private function authorizeAccess(): void
    {
        abort_unless(Auth::user()?->hasPermissionForSelectedRole(self::PERMISSION), 403);
    }

    /** One file per upload; the user id in the name keeps each user to their own uploads. */
    private function fileName(string $token): string
    {
        return Auth::id() . '_' . $token . '.csv';
    }

    private function path(string $token): string
    {
        return Storage::path(self::DIRECTORY . '/' . $this->fileName($token));
    }

    private function existingPath(string $token): string
    {
        $path = $this->path($this->validToken($token));
        abort_unless(is_file($path), 404, 'This upload has expired or was already applied. Please upload the file again.');

        return $path;
    }

    private function validToken(string $token): string
    {
        abort_unless(Str::isUuid($token), 404);

        return $token;
    }

    /** Uploads that were never confirmed or cancelled. */
    private function deleteStaleUploads(): void
    {
        $cutoff = now()->subDay()->getTimestamp();
        foreach (Storage::files(self::DIRECTORY) as $file) {
            if (Storage::lastModified($file) < $cutoff) {
                Storage::delete($file);
            }
        }
    }
}
