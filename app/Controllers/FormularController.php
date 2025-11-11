<?php

namespace App\Controllers;

class FormularController extends BaseController
{

    public function index()
    {
        echo "<h1>Formularcontroller funktioniert!</h1>";
    }


    public function getformularzeigen(): string
    {
        return view('registierungsFormular');

    }

    public function postauswertung(): string
    {
        $data = [
            "nachname" => $_POST["lastName"],
            "vorname" => $_POST["firstName"],
        ];
        return view('auswertungsFormular', $data);
    }
}