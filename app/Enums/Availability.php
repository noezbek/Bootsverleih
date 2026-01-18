<?php

namespace App\Enums;

enum Availability: int
{
    case VERFUEGBAR        = 1;
    case RESERVIERT        = 2;
    case VERMIETET         = 3;
    case NICHT_VERFUEGBAR  = 4;
    case WARTUNG           = 5;
    case AUSSER_BETRIEB    = 6;

    public static function label(?int $id): ?string
    {
        return match ($id) {
            1 => 'Verfügbar',
            2 => 'Reserviert',
            3 => 'Vermietet',
            4 => 'Nicht verfügbar',
            5 => 'In Wartung',
            6 => 'Außer Betrieb',
            default => null,
        };
    }
}
