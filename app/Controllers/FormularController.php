<?php

namespace App\Controllers;

class FormularController extends BaseController
{ 
    public function __construct() {

    }

    public function getFormularZeigen($nummer=NULL): string{
        return view["registrierungsFormular"];
    }

    public function postAuswertung() {
        echo "<p style='color:red;font-size:50px'>". $_POST['lastName'] . "</P>";
    }
}