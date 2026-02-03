<?php

namespace App\Enums;

enum ReservationStatus: int
{
    case ANGEFRAGT        = 1;
    case RESERVIERT           = 2;

    public static function list(): array
    {
        return [
            1 => 'Angefragt',
            2 => 'Reserviert'
        ];
    }
}