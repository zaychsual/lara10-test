<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ArrayAtLeastOneRequired implements Rule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    public function passes($attribute, $value)
    {
        foreach ($value as $arrayElement) {
            if (null !== $arrayElement) {
                return true;
            }
        }

        return false;
    }

    public function message()
    {
        return 'at least one input.';
    }
}
