<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ISBN implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach ((array)$value as $isbn) {
            $formattedIsbn = preg_replace('/[^0-9A-Z]/', '', strtoupper($isbn));
            if (!preg_match('/^[0-9A-Z]{10}(?>\d{3})?$/', $formattedIsbn)) {
                $fail("Each :attribute must be a valid 10 or 13 digit ISBN. Failed at ({$isbn})");
            }
        }
    }
}
