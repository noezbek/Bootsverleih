<?php

namespace App\Controllers;

use App\Models\Kunde;

class FormularController extends BaseController
{

    public function __construct()
    {
        //
    }

    public function index()
    {
        echo "<h1>Formularcontroller funktioniert!</h1>";
    }


    public function getformularzeigen(): string
    {
        return view('registierungsFormular');
    }

    public function gettestkunde(): string
    {
        return view('kunde_test');
    }

    public function posttestkunde(): string
    {

        try {
            // Testkunde erzeugen
            $kunde = new Kunde(
                "Test",
                "Kunde",
                "testkunde@example.de",
                "2000-01-01",
                491234567,
                "Teststraße 1",
                12345,
                "Teststadt"
            );

            // Speichern
            $kunde->saveEntry();

            // Erfolgs-View laden
            return view('kunde_test_auswertung', [
                'message' => '✅ Testkunde gespeichert! ID: ' . $kunde->getID()
            ]);

        } catch (\Throwable $e) {
            // Fehler-View laden
            return view('kunde_test_auswertung', [
                'message' => '❌ Fehler: ' . $e->getMessage()
            ]);
        }

        return view('kunde_test_auswertung');
    }

    public function postauswertung(): string
    {
        $this->kundendaten->speichern($_POST["firstName"], $_POST["lastName"]);

        $data = [
            "nachname" => $_POST["lastName"],
            "vorname" => $_POST["firstName"],
        ];
        return view('auswertungsFormular', $data);
    }


}