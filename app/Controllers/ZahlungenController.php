<?php

namespace App\Controllers;

use App\Models\Zahlung;
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
        // Alle zahlungn aus der DB holen
        $zahlungenInstances = Zahlung::findAllEntries($db);

        $zahlungen = [];

        foreach ($zahlungenInstances as $id => $z) {
            $zahlungen[$id] = $z->toArray();
        }

        return $this->response->setJSON($zahlungen);
    }
}