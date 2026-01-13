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

    public function getopenkundenverwaltung(): string
    {
        $db = DBConnection::getConnection();
        // Alle Kunden aus der DB holen
        $kunden = Kunde::findAllEntries($db);

        // Daten an View übergeben
        return view('kundenverwaltung', [
            'kunden' => $kunden
        ]);
    }

    public function postsavekundenverwaltung(): \CodeIgniter\HTTP\RedirectResponse
    {
        $db = DBConnection::getConnection();

        $id = ($_POST['id'] === '' ? null : (int) $_POST['id']);
        $geburtsdatum = ($_POST['geburtsdatum'] === '' ? null : $_POST['geburtsdatum']);

        $kunde = new Kunde(
            $_POST['vorname'],
            $_POST['nachname'],
            $_POST['email'],
            $geburtsdatum,
            (int) $_POST['telefon'],
            $_POST['strasse'],
            (int) $_POST['plz'],
            $_POST['stadt'],
            (bool) $_POST['active'],
            $id
        );
        $kunde->saveEntry($db);

        return redirect()->back()->with('saved', 1);
    }

    public function postdeletekundenverwaltung(): \CodeIgniter\HTTP\RedirectResponse
    {
        $db = DBConnection::getConnection();
        $id = ($_POST['id'] === '' ? null : (int) $_POST['id']);

        Kunde::deleteByID($db, $id);

        return redirect()->back()->with('saved', 1);
    }
}