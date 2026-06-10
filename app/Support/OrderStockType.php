<?php

namespace App\Support;

use Illuminate\Validation\Rule;

class OrderStockType
{
    public const BACK_TO_BACK = 'Back-to-Back';

    public const STOCKS = 'Stocks';

    public const CROSS_TRADE = 'Cross Trade';

    public static function options(): array
    {
        return [
            self::BACK_TO_BACK,
            self::STOCKS,
            self::CROSS_TRADE,
        ];
    }

    public static function normalize(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $legacyMap = [
            'Back to Back' => self::BACK_TO_BACK,
            'Stocks' => self::STOCKS,
            'Cross state' => self::CROSS_TRADE,
            'Cross-state' => self::CROSS_TRADE,
        ];

        return $legacyMap[$value] ?? $value;
    }

    /**
     * @return array<int, mixed>
     */
    public static function validationRules(): array
    {
        return ['required', Rule::in(self::options())];
    }
}
