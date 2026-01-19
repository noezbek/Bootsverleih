<?php

namespace App\Enums;

enum OrderStatus: int
{
    case BESTAETIGT      = 1;
    case ABGESCHLOSSEN   = 2;
    case STORNIERT       = 3;
    case IN_BEARBEITUNG  = 4;

    public static function list(): array
    {
        return [
            1 => 'Bestätigt',
            2 => 'Abgeschlossen',
            3 => 'Storniert',
            4 => 'In Bearbeitung'
        ];
    }
}
