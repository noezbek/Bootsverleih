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

        return $this->response->setJSON([
            'zahlungen' => $zahlungen
        ]);
    }

    public function saveZahlung(): array
    {
        $db = DBConnection::getConnection();

        $data = json_decode($_POST['data'], 1);

        $id = ($data['id'] === '' ? null : (int) $data['id']);

        $zahlung = new Zahlung(
            $data['laenge'],
            $data['breite'],
            $data['tiefgang'],
            $data['beschreibung'],
            $data['kapazitaet'],
            $data['zahlungenstyp'],
            $data['preis_pro_tag'],
            $data['kaution'],
            $data['verfuegbarkeit'],
            $id,
            (bool) $data['active']
        );
        $zahlung->saveEntry($db);

        return $zahlung->toArray();
    }

    public function deleteZahlung(): \CodeIgniter\HTTP\RedirectResponse
    {
        $db = DBConnection::getConnection();
        $id = ($_POST['id'] === '' ? null : (int) $_POST['id']);

        Zahlung::deleteByID($db, $id);

        return redirect()->back()->with('saved', 1);
    }


}