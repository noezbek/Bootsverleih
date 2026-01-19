<?php
namespace App\Enums;

enum PaymentStatus: int
{
    case AUSSTEHEND        = 1;
    case BEZAHLT           = 2;
    case TEILWEISE_BEZAHLT = 3;
    case FEHLGESCHLAGEN    = 4;
    case STORNIERT         = 5;
    case RUECKERSTATTET    = 6;

    public static function list(): array
    {
        return [
            1 => 'Ausstehend',
            2 => 'Bezahlt',
            3 => 'Teilweise bezahlt',
            4 => 'Fehlgeschlagen',
            5 => 'Storniert',
            6 => 'Rückerstattet'
        ];
    }
}
