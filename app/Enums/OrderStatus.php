<?php

namespace App\Enums;

enum OrderStatus: int
{
    case BESTAETIGT      = 1;
    case ABGESCHLOSSEN   = 2;
    case STORNIERT       = 3;
    case IN_BEARBEITUNG  = 4;

    public static function label(?int $id): ?string
    {
        return match ($id) {
            1 => 'Bestätigt',
            2 => 'Abgeschlossen',
            3 => 'Storniert',
            4 => 'In Bearbeitung',
            default => null,
        };
    }
}
