<?php

namespace App\Controllers;

use App\Models\Kundendaten;

class FormularController extends BaseController
{

    private $kundendaten;

    public function __construct()
    {
        $this->kundendaten = new Kundendaten();
    }

    public function index()
    {
        echo "<h1>Formularcontroller funktioniert!</h1>";
    }


    public function getformularzeigen(): string
    {
                return view('homepage');
        
        // return view('registierungsFormular');

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