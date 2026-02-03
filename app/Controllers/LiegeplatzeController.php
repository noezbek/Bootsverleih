<?php

namespace App\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentRhythm;
use App\Enums\PaymentStatus;
use App\Enums\ReservationStatus;
use App\Models\Bestellung;
use App\Models\DBConnection;
use App\Models\ReservationEmail;
use App\Models\Kunde;
use App\Models\Liegeplatz;
use App\Models\LiegeplatzReservierung;
use App\Models\Vertrag;
use App\Models\Zahlung;
use DateTime;
use DateTimeZone;
use Exception;

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


        return $this->response->setJSON($liegeplaetze);
    }

    /**
     * @throws RandomException
     */
    public function saveReservierung(): \CodeIgniter\HTTP\ResponseInterface
    {
        $kundenID = $_SESSION['kunde_id'];

        if (!$kundenID) {
            throw new Exception('Keine KundenID');
        }

        $db = DBConnection::getConnection();

        try {
            $db->beginTransaction();

            $kundenInstance = Kunde::findByIdEntry($db, $kundenID);

            if (!$kundenInstance) {
                throw new Exception('Kein Kunde gefunden');
            }

            $data = json_decode($_POST['data'], 1);

            $bestellung = new Bestellung($kundenID, OrderStatus::IN_BEARBEITUNG->value);
            $bestellung->saveEntry($db);
            $bestellID = $bestellung->getID();

            $expiresAt = LiegeplatzReservierung::calculateExpireDate();
            $token = bin2hex(random_bytes(32));
            $confirmUrl = LiegeplatzReservierung::buildConfirmUrl($token);
            $reservierung = new LiegeplatzReservierung($data['liegeplatz'], $data['boot'], $bestellID, $data['startdatum'], $data['enddatum'], $data['preisProTag'],
//            ReservationStatus::ANGEFRAGT->value,//Todo normalerweise per mail aber geht gerade nihct
                ReservationStatus::RESERVIERT->value,
                $expiresAt, $token);

            $reservierung->saveEntry($db);

            //Todo normalerweise per mail aber geht gerade nihct
//        ReservationEmail::sendConfirmation(
//            $kundenInstance,
//            $confirmUrl,
//            $expiresAt
//        );

            //Todo normalerweise per mail aber geht gerade nihct
            $vertrag = new Vertrag($bestellID, PaymentRhythm::EINMALIG->value, PaymentMethod::UEBERWEISUNG->value);
            $vertrag->saveEntry($db);

            $zahlung = new Zahlung($vertrag->getID(), PaymentStatus::BEZAHLT, $reservierung->getCalculatedSollPreis(), Zahlung::calculateFaelligAm());
            $zahlung->saveEntry($db);

            $db->commit();

            $payload  = [
                'reservierung' => $reservierung->toArray(),
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

    public function confirm(string $token)
    {
        $db = DBConnection::getConnection();

        $reservierung = LiegeplatzReservierung::findByToken($db, $token);

        if (!$reservierung) {
            return $this->response->setStatusCode(404)
                ->setBody('Ungültiger oder abgelaufener Link.');
        }

        if (
            $reservierung->getStatus() === 1 &&
            new \DateTime() > new \DateTime($reservierung->getExpiresAt())
        ) {
            return $this->response->setBody(
                'Die Reservierung ist leider abgelaufen.'
            );
        }

        if ($reservierung->getStatus() !== 1) {
            return $this->response->setBody(
                'Diese Reservierung wurde bereits bestätigt.'
            );
        }

        return view('zahlung_auswahl', [
            'token' => $token
        ]);
    }

    public function confirmPost()
    {
        $token = $_POST['token'] ?? null;
        $zahlungsart = $_POST['zahlungsart'] ?? null;

        if (!$token || !$zahlungsart) {
            throw new \RuntimeException('Ungültige Bestätigungsdaten');
        }

        $db = DBConnection::getConnection();

        $reservierung = LiegeplatzReservierung::findByToken($db, $token);

        if (!$reservierung) {
            throw new \RuntimeException('Reservierung nicht gefunden oder abgelaufen');
        }

        // Sicherheit: nur ANGEFRAGT darf bestätigt werden
        if ($reservierung->getStatus() !== 1) {
            throw new \RuntimeException('Reservierung wurde bereits bestätigt');
        }

        // Ablauf prüfen
        if (new \DateTime() > new \DateTime($reservierung->getExpiresAt())) {
            throw new \RuntimeException('Reservierung ist abgelaufen');
        }

        // Zahlungsart in Bestellung speichern
        $bestellung = Bestellung::findByIdEntry(
            $db,
            $reservierung->getBestellung()
        );

        if (!$bestellung) {
            throw new \RuntimeException('Bestellung nicht gefunden');
        }

        $vertrag = new Vertrag($bestellung->getID(), PaymentRhythm::EINMALIG->value, $zahlungsart);
        $vertrag->saveEntry($db);

        $dummybetrag = 1.00;//später errechnen
        $faelligAm = date('Y-m-d H:i:s');//später errechnen

        $zahlung = new Zahlung($vertrag->getID(), PaymentStatus::AUSSTEHEND->value, $dummybetrag, $faelligAm);
        $zahlung->saveEntry($db);

        $bestellung->setBestellstatus(OrderStatus::BESTAETIGT);
        $bestellung->saveEntry($db);

        // JETZT verbindlich bestätigen
        $reservierung->setStatus(2); // RESERVIERT
        $reservierung->saveEntry($db);

        return $this->response->setBody(
            'Vielen Dank! Ihre Reservierung ist nun verbindlich.'
        );
    }


}