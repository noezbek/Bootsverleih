<?php

namespace App\Controllers;

use App\Models\DBConnection;
use App\Models\Kunde;

class KundenverwaltungController extends BaseController
{

    public function index()
    {
        echo "<h1>KundenverwaltungController funktioniert!</h1>";
    }

    public function loadKundenverwaltung(): \CodeIgniter\HTTP\ResponseInterface
    {
        $db = DBConnection::getConnection();
        // Alle Kunden aus der DB holen
        $kunden = Kunde::findAllEntries($db);

        return $this->response->setJSON([
            'kunden' => $kunden
        ]);
    }

    public function saveKundenverwaltung(): array
    {
        $db = DBConnection::getConnection();

        $data = json_decode($_POST['data'], 1);

        $id = ($data['id'] === '' ? null : (int) $data['id']);
        $geburtsdatum = ($data['geburtsdatum'] === '' ? null : $data['geburtsdatum']);

        $kunde = new Kunde(
            $data['vorname'],
            $data['nachname'],
            $data['email'],
            $geburtsdatum,
            (int) $data['telefon'],
            $data['strasse'],
            (int) $data['plz'],
            $data['stadt'],
            (bool) $data['active'],
            $id
        );
        $kunde->saveEntry($db);

        return $kunde->toArray();
    }

    public function deleteKundenverwaltung(): \CodeIgniter\HTTP\RedirectResponse
    {
        $db = DBConnection::getConnection();
        $id = ($_POST['id'] === '' ? null : (int) $_POST['id']);

        Kunde::deleteByID($db, $id);

        return redirect()->back()->with('saved', 1);
    }
}