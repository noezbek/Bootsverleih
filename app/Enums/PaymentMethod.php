<?php

namespace App\Enums;

enum PaymentMethod: int
{
    case UEBERWEISUNG = 1;
    case LASTSCHRIFT  = 2;
    case BAR          = 3;

    public static function list(): array
    {
        return [
            1 => 'Überweisung',
            2 => 'Lastschrift',
            3 => 'Bar'
        ];
    }
}
