<?php

namespace App\Controllers;

use App\Models\Boot;
use App\Models\DBConnection;
use App\Models\Feature;

class BootsverleihController extends BaseController
{
    public function index()
    {
        echo "<h1>BootsverleihController funktioniert!</h1>";
    }
    public function loadBoote(): \CodeIgniter\HTTP\ResponseInterface
    {
        $db = DBConnection::getConnection();
        // Alle Kunden aus der DB holen
        $bootInstances = Boot::findAllEntries($db);
        $featureInstances = Feature::findAllEntries($db);

        $boote = [];
        $features = [];

        foreach ($bootInstances as $id => $boot) {
            $boote[$id] = $boot->toArray();
        }
        foreach ($featureInstances as $id => $feature) {
            $features[$id] = $feature->toArray();
        }

        return $this->response->setJSON([
            'boote' => $boote,
            'features' => $features,
        ]);
    }

    public function saveBoot(): array
    {
        $db = DBConnection::getConnection();

        $data = json_decode($_POST['data'], 1);

        $id = ($data['id'] === '' ? null : (int) $data['id']);

        $kunde = new Boot(
            $data['laenge'],
            $data['breite'],
            $data['tiefgang'],
            $data['beschreibung'],
            $data['kapazitaet'],
            $data['bootstyp'],
            $data['preis_pro_tag'],
            $data['kaution'],
            $data['verfuegbarkeit'],
            $id,
            (bool) $data['active']
        );
        $kunde->saveEntry($db);

        return $kunde->toArray();
    }

    public function deleteBoot(): \CodeIgniter\HTTP\RedirectResponse
    {
        $db = DBConnection::getConnection();
        $id = ($_POST['id'] === '' ? null : (int) $_POST['id']);

        Boot::deleteByID($db, $id);

        return redirect()->back()->with('saved', 1);
    }


}