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
use RuntimeException;

class ZahlungenController extends BaseController
{
    public function index()
    {
        echo "<h1>ZahlungenController funktioniert!</h1>";
    }
    public function loadZahlungsVerwaltung(): \CodeIgniter\HTTP\ResponseInterface
    {
        $kundeId = $_SESSION['kunde_id'];

        if (!$kundeId) {
            throw new Exception('Keine KundenID');
        }

        $db = DBConnection::getConnection();

        $bestellungen = Bestellung::findAllEntries(
            $db,
            (new DbFilter())->where('kunde_ID', '=', $kundeId)
        );

        if (!$bestellungen) {
            return $this->response->setJSON([]);
        }

        $bestellungIds = array_keys($bestellungen);


        $bootMieten = BootMiete::findAllEntries(
            $db,
            (new DbFilter())->whereIn('bestellung_ID', $bestellungIds)
        );

        $liegeplaetze = LiegeplatzReservierung::findAllEntries(
            $db,
            (new DbFilter())->whereIn('bestellung_ID', $bestellungIds)
        );

        $bootByBestellung = [];
        foreach ($bootMieten as $bm) {
            $bootByBestellung[$bm->getBestellungId()] = $bm;
        }

        $liegeplatzByBestellung = [];
        foreach ($liegeplaetze as $lp) {
            $liegeplatzByBestellung[$lp->getBestellungId()] = $lp;
        }

        $vertraege = Vertrag::findAllEntries(
            $db,
            (new DbFilter())->whereIn('bestellung_ID', $bestellungIds)
        );

        $vertragByBestellung = [];
        $vertragIds = [];

        foreach ($vertraege as $v) {
            $vertragByBestellung[$v->getBestellung()] = $v;
            $vertragIds[] = $v->getID();
        }

        $zahlungen = $vertragIds
            ? Zahlung::findAllEntries(
                $db,
                (new DbFilter())->whereIn('vertrag_ID', $vertragIds)
            )
            : [];

        $zahlungenByVertrag = [];
        foreach ($zahlungen as $z) {
            $zahlungenByVertrag[$z->getVertrag()][] = $z;
        }

        $result = [];

        foreach ($bestellungen as $bid => $bestellung) {

            $vertrag = $vertragByBestellung[$bid] ?? null;
            $item = $bootByBestellung[$bid] ?? $liegeplatzByBestellung[$bid] ?? null;

            $zahlungsArray = $vertrag ? ($zahlungenByVertrag[$vertrag->getID()] ?? []) : [];

            $itemType = null;

            if ($item instanceof BootMiete) {
                $itemType = 'boot';
            } elseif ($item instanceof LiegeplatzReservierung) {
                $itemType = 'liegeplatz';
            }

            if (!$itemType) {
                throw new RuntimeException('Kein Item zur Bestellung gefunden');
            }

            $res = [
                'bestellung' => $bestellung->toArray(),
                'item'       => $item ? $item->toArray() : null,
                'itemType'  => $itemType,
                'vertrag'    => $vertrag ? $vertrag->toArray() : null,
                'zahlungen'  => array_map(
                    fn (Zahlung $z) => $z->toArray(),
                    $zahlungsArray
                ),
            ];

            $result[] = $res;
        }

        return $this->response->setJSON($result);
    }

    public function loadZahlungem(): \CodeIgniter\HTTP\ResponseInterface
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

        foreach ($bestellungenInstances as $id => $bestellungen) {
            $bestellungen[$id] = $bestellungen->toArray();
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