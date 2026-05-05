<?php

namespace App\Support;

/**
 * Maps fuel_type to the suffix used in generated variant names.
 *
 * Final mapping (value → suffix):
 * - Petrol → P
 * - Diesel → D
 * - PH (P HEV Petrol Hybrid Electric Vehicle) → PH
 * - Diesel Hybrid → DH
 * - Petrol MHEV / M HEV → PMHE
 * - Diesel MHEV → DMHE
 * - PHEV / P HEV → PHEV
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
            'ph' => 'PH',
            'diesel hybrid' => 'DH',
            'petrol mhev' => 'PMHE',
            'm hev' => 'PMHE',
            'diesel mhev' => 'DMHE',
            'p hev' => 'PH',
            'phev' => 'PHEV',
            'mhev' => 'PMHE',
            'ev' => 'EV',
        ];

        if (isset($exact[$normalizedSpaces])) {
            return $exact[$normalizedSpaces];
        }

        return 'EV';
    }
}
