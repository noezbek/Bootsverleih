<?php

namespace App\Enums;

enum UserType: int
{
    case KUNDE        = 1;
    case MITARBEITER        = 2;

    public static function list(): array
    {
        return [
            1 => 'Kunde',
            2 => 'Mitarbeiter'
        ];
    }
}