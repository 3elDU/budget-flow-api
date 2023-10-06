<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;

class MoneyAmount implements ValidationRule
{
    /**
     * Checks if the specified number is a valid amount of money for transaction
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_float($value) && !is_integer($value)) {
            $fail(":attribute must be a number");
        }

        if ($value === 0 || $value === 0.0) {
            $fail(":attribute must not be zero");
        }

        // Test a value with regex, that is has no more than 2 digits after comma
        if (!preg_match("/^\-?[0-9]+\.?[0-9]{0,2}$/", strval($value))) {
            $fail(":attribute must be a valid amount");
        }
    }
}
