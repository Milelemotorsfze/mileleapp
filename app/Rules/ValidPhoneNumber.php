<?php

namespace App\Rules;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;
use Illuminate\Contracts\Validation\Rule;
use Closure;

class ValidPhoneNumber implements Rule
{
    protected $countryCode;
    public function __construct($countryCode = 'US')
    {
        $this->countryCode = $countryCode;
    }

    public function passes($attribute, $value)
    {
        if (empty($value)) {
            return true;
        }

        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $numberProto = $phoneUtil->parse($value, $this->countryCode);
            return $phoneUtil->isValidNumber($numberProto);
        } catch (NumberParseException $e) {
            return false;
        }
    }

    public function message()
    {
        return 'The :attribute field contains an invalid phone number.';
    }
}