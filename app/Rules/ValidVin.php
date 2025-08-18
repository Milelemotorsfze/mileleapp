<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidVin implements Rule
{
    public function passes($attribute, $value)
    {
        return is_string($value) && preg_match('/^[A-Za-z0-9]+$/', $value);
    }

    public function message()
    {
        return 'The :attribute may only contain letters and numbers, with no spaces or special characters.';
    }
} 