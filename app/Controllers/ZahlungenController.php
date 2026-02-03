<?php

namespace App\Controllers;

use App\Filters\DbFilter;
use App\Models\Bestellung;
use App\Models\BootMiete;
use App\Models\DBConnection;
use App\Models\LiegeplatzReservierung;
use App\Models\Vertrag;
use App\Models\Zahlung;
use Exception;
use PDO;

class ZahlungenController extends BaseController
{
    public function index()
    {
        echo "<h1>ZahlungenController funktioniert!</h1>";
    }

    public function loadZahlungenByKunde(): \CodeIgniter\HTTP\ResponseInterface
    {

        $kundeId = $_SESSION['kunde_id'];

        if (!$kundeId) {
            throw new Exception('Keine KundenID');
        }

        $db = DBConnection::getConnection();

        $filter = new DbFilter();
        $filter->where('active', '=', 1);

        $zahlungenInstances = Zahlung::findByKunde(
            $db,
            $kundeId
        );

        $zahlungen = [];

        foreach ($zahlungenInstances as $id => $zahlung) {
            $zahlungen[$id] = $zahlung->toArray();
        }

        return $this->response->setJSON($zahlungen);
    }

    public static function loadItemsForOrders(PDO $db, array $bestellIDs): array
    {
        if (empty($bestellIDs)) {
            return [];
        }

        // Ergebnis vorbereiten
        $result = [];
        foreach ($bestellIDs as $bid) {
            $result[(int)$bid] = [
                'bootmieten' => [],
                'liegeplatzReservierungen' => [],
            ];
        }

        $mietFilter = new DbFilter();
        $mietFilter->where('active', '=', 1);
        $mietFilter->whereIn('bestellung_ID',  $bestellIDs);

        $bootmieten = BootMiete::findAllEntries($db, $mietFilter);

        foreach ($bootmieten as $bm) {
            $bid = $bm->getBestellung();
            if (isset($result[$bid])) {
                $result[$bid]['bootmieten'][] = $bm->toArray();
            }
        }

        $resFilter = new DbFilter();
        $resFilter->where('active', '=', 1);
        $resFilter->whereIn('bestellung_ID', $bestellIDs);

        $liegeplatzReservierungen = LiegeplatzReservierung::findAllEntries($db, $resFilter);

        foreach ($liegeplatzReservierungen as $lr) {
            $bid = $lr->getBestellung();
            if (isset($result[$bid])) {
                $result[$bid]['liegeplatzReservierungen'][] = $lr->toArray();
            }
        }

        return $result;
    }


    public function loadZahlungen(): \CodeIgniter\HTTP\ResponseInterface
    {
        $db = DBConnection::getConnection();

        $filter = new DbFilter();
        $filter->where('active', '=', 1);

        $zahlungenInstances = Zahlung::findAllEntries(
            $db,
            $filter
        );

        $zahlungen = [];

        foreach ($zahlungenInstances as $id => $zahlung) {
            $zahlungen[$id] = $zahlung->toArray();
        }

        return $this->response->setJSON($zahlungen);
    }

    public function loadBestellungen(): \CodeIgniter\HTTP\ResponseInterface
    {
        $db = DBConnection::getConnection();

        $filter = new DbFilter();
        $filter->where('active', '=', 1);

        $bestellungenInstances = Bestellung::findAllEntries(
            $db,
            $filter
        );

        $bestellungen = [];

        $items = self::loadItemsForOrders($db, array_keys($bestellungenInstances));

        foreach ($bestellungenInstances as $id => $bestellung) {
            $item = $items[$id];
            $bestellung->setReservierteLiegeplaetze($item['liegeplatzReservierungen']);
            $bestellung->setGemieteteBoote($item['bootmieten']);
            $bestellungen[$id] = $bestellung->toArray();
        }

        return $this->response->setJSON($bestellungen);
    }

    public function loadVertaege(): \CodeIgniter\HTTP\ResponseInterface
    {
        $db = DBConnection::getConnection();

        $filter = new DbFilter();
        $filter->where('active', '=', 1);

        $vertraegeInstances = Vertrag::findAllEntries(
            $db,
            $filter
        );

        $vertraege = [];

        foreach ($vertraegeInstances as $id => $vertraeg) {
            $vertraege[$id] = $vertraeg->toArray();
        }

        return $this->response->setJSON($vertraege);
    }

}