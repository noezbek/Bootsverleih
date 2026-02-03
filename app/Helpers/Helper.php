<?php

namespace App\Helpers;

use DateTime;

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

    public static function toDateTime(string|DateTime $v): DateTime
    {
        return $v instanceof DateTime ? $v : new DateTime($v);
    }

    public static function calculatedDays(string|DateTime $start, string|DateTime $end): int
    {
        $start = Helper::toDateTime($start);
        $end   = Helper::toDateTime($end);

        if (!$start || !$end) {
            return 0;
        }

        // gleiche Tage = 1 Tag (Miete / Reservierung)
        return (int)$start->diff($end)->format('%a') + 1;
    }
}