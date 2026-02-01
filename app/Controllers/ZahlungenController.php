<?php

namespace App\Controllers;

use App\Filters\DbFilter;
use App\Models\Bestellung;
use App\Models\DBConnection;

class ZahlungenController extends BaseController
{
    public function index()
    {
        echo "<h1>ZahlungenController funktioniert!</h1>";
    }
    public function loadZahlungen(): \CodeIgniter\HTTP\ResponseInterface
    {
        $db = DBConnection::getConnection();

        $kundeId = 1;

        $bestellungen = Bestellung::findAllEntries(
            $db,
            (new DbFilter())->where('kunde_ID', '=', $kundeId)
        );

        return $this->response->setJSON($bestellungen);
    }
}