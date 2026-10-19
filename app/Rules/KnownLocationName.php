<?php

namespace App\Rules;

use App\Services\LocationNameMatcher;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Fails when a typed port / country is close to a name that already exists in the system
 * (e.g. "jabelali" while "Jebel Ali" exists): the user has to select the existing name.
 * Exact names and genuinely new names pass.
 */
class KnownLocationName implements ValidationRule
{
    private const LABELS = [
        'port_of_loading' => 'Port of Loading',
        'port_of_discharge' => 'Port of Discharge',
        'final_destination' => 'Final Destination',
    ];

    public function __construct(private string $type)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            return;
        }

        $result = app(LocationNameMatcher::class)->match($this->type, $value);
        if ($result['status'] !== 'select') {
            return;
        }

        $label = self::LABELS[$attribute] ?? str_replace('_', ' ', $attribute);
        $fail(sprintf(
            '%s "%s" is already in the system as: %s. Please select the existing name.',
            $label,
            trim($value),
            implode(', ', $result['suggestions'])
        ));
    }
}
