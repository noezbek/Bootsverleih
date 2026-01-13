<?php

namespace App\Controllers;

use App\Models\Kunde;

class KundenverwaltungController extends BaseController
{

    public function index()
    {
        echo "<h1>KundenverwaltungController funktioniert!</h1>";
    }


    public function getopenkundenverwaltung(): string
    {
        // Alle Kunden aus der DB holen
        $kunden = Kunde::findAllEntries();

        // Daten an View übergeben
        return view('kundenverwaltung', [
            'kunden' => $kunden
        ]);
    }
}