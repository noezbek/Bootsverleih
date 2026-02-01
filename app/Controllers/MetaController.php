<?php

namespace App\Controllers;

use App\Enums\Availability;
use App\Enums\BoatTyoe;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentRhythm;
use App\Enums\PaymentStatus;

class MetaController extends BaseController
{
    public function index()
    {
        echo "<h1>MetaController funktioniert!</h1>";
    }

    public function loadEnums()
    {
        $list = [
            'verfuegbarkeiten' => Availability::list(),
            'bootTypen' => BoatTyoe::list(),
            'bestellStatus' => OrderStatus::list(),
            'zahlStatus' => PaymentStatus::list(),
            'zahlMethoden' => PaymentMethod::list(),
            'zahlRhythmus' => PaymentRhythm::list(),
        ];
        return $this->response->setJSON($list);
    }
}