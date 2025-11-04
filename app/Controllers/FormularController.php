<?php

namespace App\Controllers;

class FormularController extends BaseController
{

    public function index()
    {
        echo "<h1>Formularcontroller funktioniert!</h1>";
    }


    public function getformularzeigen(): string{
        // die();
       return view('auswertungsFormular');

    }

    // publ
    // }ic function postAuswertung() {
    //     echo "<p style='color:red;font-size:50px'>". $_POST['lastName'] . "</P>";
    // }
}