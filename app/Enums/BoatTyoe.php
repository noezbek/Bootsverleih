<?php

namespace App\Enums;

enum BoatTyoe: int
{
    case SEGELBOOT = 1;
    case MOTORBOOT = 2;
    case KAJAK = 3;
    case KANU = 4;
    case SUP = 5;

    public static function list(): array
    {
        return [
            1 => 'Segelboot',
            2 => 'Motorboot',
            3 => 'Kajak',
            4 => 'Kanu',
            5 => 'SUP'
        ];
    }
}
