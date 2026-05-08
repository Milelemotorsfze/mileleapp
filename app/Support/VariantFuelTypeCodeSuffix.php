<?php

namespace App\Support;

/**
 * Maps fuel_type to the suffix used in generated variant names.
 *
 * Final mapping (value → suffix):
 * - Petrol → P
 * - Diesel → D
 * - Petrol Hybrid → PH
 * - Diesel Hybrid → DH
 * - Petrol Mild Hybrid → PMHE
 * - Diesel Mild Hybrid → DMHE
 * - PHEV → PHEV
 * - EV → EV
 *
 * Diesel PHEV is intentionally not used for now (commented in dropdowns and not mapped here).
 */
final class VariantFuelTypeCodeSuffix
{
    public static function toSuffix(?string $fuelType): string
    {
        $t = trim((string) $fuelType);
        if ($t === '') {
            return 'EV';
        }

        $lower = mb_strtolower($t, 'UTF-8');
        $normalizedSpaces = preg_replace('/\s+/', ' ', $lower);

        // Exact values shown in dropdowns + existing short tokens.
        static $exact = [
            'petrol' => 'P',
            'diesel' => 'D',
            'petrol hybrid' => 'PH',
            'diesel hybrid' => 'DH',
            'petrol mild hybrid' => 'PMHE',
            'diesel mild hybrid' => 'DMHE',
            'phev' => 'PHEV',
            'ev' => 'EV',
        ];

        if (isset($exact[$normalizedSpaces])) {
            return $exact[$normalizedSpaces];
        }

        return 'EV';
    }
}
