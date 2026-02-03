<?php

namespace App\Controllers;

use App\Filters\DbFilter;
use App\Models\Bestellung;
use App\Models\BootMiete;
use App\Models\DBConnection;
use App\Models\LiegeplatzReservierung;
use App\Models\Vertrag;
use App\Models\Zahlung;
use RuntimeException;

class ZahlungenController extends BaseController
{
    public function index()
    {
        echo "<h1>ZahlungenController funktioniert!</h1>";
    }
    public function loadZahlungen(): \CodeIgniter\HTTP\ResponseInterface
    {
        $db = DBConnection::getConnection();
        $kundeId = 7; // TODO: aus Session/Auth

        /* =========================
         * 1. Bestellungen
         * ========================= */
        $bestellungen = Bestellung::findAllEntries(
            $db,
            (new DbFilter())->where('kunde_ID', '=', $kundeId)
        );

        if (!$bestellungen) {
            return $this->response->setJSON([]);
        }

        $bestellungIds = array_keys($bestellungen);

        /* =========================
         * 2. Items
         * ========================= */
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

        /* =========================
         * 3. Verträge
         * ========================= */
        $vertraege = Vertrag::findAllEntries(
            $db,
            (new DbFilter())->whereIn('bestellung_ID', $bestellungIds)
        );

        $vertragByBestellung = [];
        $vertragIds = [];

        foreach ($vertraege as $v) {
            $vertragByBestellung[$v->getBestellungId()] = $v;
            $vertragIds[] = $v->getID();
        }

        /* =========================
         * 4. Zahlungen
         * ========================= */
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

        /* =========================
         * 5. Response bauen
         * ========================= */
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

}