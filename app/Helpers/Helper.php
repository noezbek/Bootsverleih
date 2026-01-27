<?php

namespace App\Helpers;

class Helper
{
    public static function isAnyEmpty(mixed ...$values): bool
    {
        foreach ($values as $value) {
            if ($value === null) return true;
            if (is_string($value) && trim($value) === '') return true;
            if (is_array($value) && empty($value)) return true;
        }
        return false;
    }

}