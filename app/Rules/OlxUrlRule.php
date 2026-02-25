<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OlxUrlRule implements ValidationRule
{
    /**
     * Validates that the given value is a proper URL on the olx.ua domain, including mobile subdomains
     * and that its path contains /obyavlenie/
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $fail('The :attribute must be a valid URL.');
            return;
        }

        $host = parse_url($value, PHP_URL_HOST);

        if ($host === false || !str_contains($host, 'olx.ua')) {
            $fail('The :attribute must be an olx.ua URL.');
            return;
        }

        $path = parse_url($value, PHP_URL_PATH);
        if ($path === false || !str_contains($path, '/obyavlenie/')) {
            $fail('The :attribute must be a valid OLX tracking URL.');
        }
    }
}
