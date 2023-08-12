<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AboveEighteen implements Rule
{
    public function passes($attribute, $value)
    {
        $eighteenYearsAgo = now()->subYears(18);
        return strtotime($value) <= $eighteenYearsAgo->timestamp;
    }

    public function message()
    {
        return 'The :attribute must be a date above or equal to 18 years ago.';
    }
}
