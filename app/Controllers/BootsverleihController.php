<?php

namespace App\Controllers;

use App\Enums\Availability;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentRhythm;
use App\Enums\PaymentStatus;
use App\Models\Bestellung;
use App\Models\Boot;
use App\Models\BootMiete;
use App\Models\DBConnection;
use App\Models\Feature;
use App\Models\Vertrag;
use App\Models\Zahlung;
use DateTime;
use Exception;

class BootsverleihController extends BaseController
{
    public function index()
    {
        echo "<h1>BootsverleihController funktioniert!</h1>";
    }
    public function loadBoote(): \CodeIgniter\HTTP\ResponseInterface
    {
        $db = DBConnection::getConnection();

        $bootInstances = Boot::findAllEntries($db);

        $boote = [];

        $today = (new \DateTime())->format('Y-m-d');

        foreach ($bootInstances as $id => $boot) {

            $boot->setBooked(
                $boot->isBooked($db, $today, $today)
            );

            $boote[$id] = $boot->toArray();
        }

        return $this->response->setJSON($boote);
    }

    public function loadFeatures(): \CodeIgniter\HTTP\ResponseInterface
    {
        $db = DBConnection::getConnection();

        $featureInstances = Feature::findAllEntries($db);

        $features = [];

        foreach ($featureInstances as $id => $feature) {
            $features[$id] = $feature->toArray();
        }

        return $this->response->setJSON($features);
    }

    public function saveBoot(): \CodeIgniter\HTTP\ResponseInterface
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

        return $this->response->setJSON($kunde->toArray());
    }

    public function saveBootMiete(): \CodeIgniter\HTTP\ResponseInterface
    {
        $kunde_ID = $_SESSION['kunde_id'];

        if (!$kunde_ID) {
            throw new Exception('Keine KundenID');
        }

        $db = DBConnection::getConnection();
        $data = json_decode($_POST['data'], 1);

        try {
            $db->beginTransaction();

            $boot = Boot::findByIdEntry($db, $data['bootID']);

            $verfuegbar = $boot->getVerfuegbarkeit();

            if (Availability::VERFUEGBAR->value !== $verfuegbar) {
                throw new Exception('Boot ist aktuell nicht verfügbar');
            }

            $isBooked = $boot->isBooked($db, $data['startDate'], $data['endDate']);

            if ($isBooked) {
                throw new Exception('Boot ist aktuell besetzt und kann nicht gebucht werden');
            }

            $bestellung = new Bestellung($kunde_ID,
//            OrderStatus::IN_BEARBEITUNG->value, //ToDo: eigtl in bearbeitung aber mail senden geht nciht
                OrderStatus::BESTAETIGT->value,
            );

            $bestellung->saveEntry($db);

            $bestellID = $bestellung->getID();

            $miete = new BootMiete($boot->getID(), $bestellID, $data['startDate'], $data['endDate'], $data['preisProTag']);
            $miete->saveEntry($db);

            //ToDo: normalerweise wird das über mailbestätigung gemacht aber mail geht nicht deswegen direkt dummy zahlen
            $vertrag = new Vertrag($bestellID, PaymentRhythm::EINMALIG->value, PaymentMethod::UEBERWEISUNG->value);
            $vertrag->saveEntry($db);

            $zahlung = new Zahlung($vertrag->getID(), PaymentStatus::BEZAHLT, $miete->getCalculatedSollPreis(), Zahlung::calculateFaelligAm());
            $zahlung->saveEntry($db);

            $db->commit();

            $payload  = [
                'miete' => $miete->toArray(),
                'vertrag' => $vertrag->toArray(),
                'zahlung' => $zahlung->toArray(),
                'bestellung' => $bestellung->toArray(),
            ];

            return $this->response->setJSON($payload);
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e; // oder eigene Fehlermeldung
        }


    }

    public function deleteBoot(): \CodeIgniter\HTTP\RedirectResponse
    {
        $db = DBConnection::getConnection();
        $id = ($_POST['id'] === '' ? null : (int) $_POST['id']);

        Boot::deleteByID($db, $id);

        return redirect()->back()->with('saved', 1);
    }
}