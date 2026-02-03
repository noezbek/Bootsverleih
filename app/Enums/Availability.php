<?php

namespace App\Enums;

enum Availability: int
{
    case VERFUEGBAR        = 1;
    case NICHT_VERFUEGBAR  = 2;
    case WARTUNG           = 3;
    case AUSSER_BETRIEB    = 4;

    public static function list(): array
    {
        return [
            1 => 'Verfügbar',
            2 => 'Nicht verfügbar',
            3 => 'In Wartung',
            4 => 'Außer Betrieb'
        ];
    }
}
