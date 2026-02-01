<?php

namespace App\Controllers;

use App\Models\DBConnection;
use App\Models\Liegeplatz;

class LiegeplatzeController extends BaseController
{
    public function index()
    {
        echo "<h1>LiegeplatzeController funktioniert!</h1>";
    }
    public function loadLiegeplaetze(): \CodeIgniter\HTTP\ResponseInterface
    {
        $db = DBConnection::getConnection();
        $liegeplaetzInstances = Liegeplatz::findAllEntries($db);

        $liegeplaetze = [];

        foreach ($liegeplaetzInstances as $id => $liegeplaetz) {
            $liegeplaetze[$id] = $liegeplaetz->toArray();
        }


        return $this->response->setJSON([
            'liegeplaetze' => $liegeplaetze
        ]);
    }
}