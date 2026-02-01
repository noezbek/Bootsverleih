<?php

namespace App\Enums;

enum PaymentRhythm: int
{
    case EINMALIG  = 1;
    case MONATLICH = 2;
    case JAEHRLICH = 3;

    public static function list(): array
    {
        return [
            1 => 'Einmalig',
            2 => 'Monatlich',
            3 => 'Jährlich'
        ];
    }
}
