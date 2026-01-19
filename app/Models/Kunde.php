<?php

namespace App\Models;

use App\Filters\DbFilter;
use PDO;

class Kunde extends Person
{
    public function __construct(
        string $vorname,
        string $nachname,
        string $email,
        string $geburtsdatum,
        int $telefon,
        string $strasse,
        int $plz,
        string $stadt,
        ?int $id = null,
        ?bool $active = true,
        string|null $updated_at = null,
        string|null $created = null,
        User|int|null $user = null,
    ) {
        parent::__construct($vorname, $nachname, $email, $geburtsdatum, $telefon, $strasse, $plz, $stadt, $id, $active, $updated_at, $created, $user);
    }

    public static function getTable(): string
    {
        return 'kunde';
    }

    public function saveEntry(PDO $db): void
    {
        $sqlString = empty($this->id) ? self::getInsertStmnt(): self::getUpdateStmnt();
        $stmt = $db->prepare($sqlString);
        $this->savePerson($stmt, $db);
    }

    protected static function getInsertStmnt() : string
    {
        $table = self::getTable();
        return "INSERT INTO $table (vorname, nachname, email, geburtsdatum, telefon, strasse, plz, stadt, userID)
                 VALUES (:vorname, :nachname, :email, :geburtsdatum, :telefon, :strasse, :plz, :stadt, :Benutzer)";
    }

    protected static function getUpdateStmnt() : string
    {
        $table = self::getTable();
        return "UPDATE $table
                 SET vorname=:vorname, nachname=:nachname, email=:email, geburtsdatum=:geburtsdatum, telefon=:telefon, strasse=:strasse, plz=:plz, stadt=:stadt, userID=:Benutzer, active=:Active
                 WHERE ID = :ID";
    }

    public static function findByIdEntry(PDO $db, int $id): array|null
    {
        $table = self::getTable();

        $stmt = $db->prepare("SELECT * FROM $table WHERE ID = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        $kunde = new Kunde(
            $row['vorname'],
            $row['nachname'],
            $row['email'],
            $row['geburtsdatum'],
            $row['telefon'],
            $row['strasse'],
            $row['plz'],
            $row['stadt'],
            $row['ID'],
            (bool)$row['active'],
        );

        return $kunde->toArray();
    }


    public static function findAllEntries(PDO $db, ?DbFilter $filter = null): array
    {
        $table = self::getTable();

        $stmt = $db->query("SELECT * FROM $table");
        $res = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $kunde = new Kunde(
                $row['vorname'],
                $row['nachname'],
                $row['email'],
                $row['geburtsdatum'],
                $row['telefon'],
                $row['strasse'],
                (int)$row['plz'],
                $row['stadt'],
                $row['ID'],
                (bool)$row['active'],
            );

            $id = $kunde->getID(); // falls vorhanden, sonst (int)$row['ID']
            $res[$id] = $kunde->toArray();
        }

        return $res;
    }

    public static function attachBestellungen(PDO $db, array &$kunden, ?DbFilter $extraFilter = null): void
    {
        if (!$kunden) return;

        $kundeIDs = array_map('intval', array_keys($kunden));

        $filter = $extraFilter ?? new DbFilter();
        $filter->whereIn('kunde_ID', $kundeIDs);

        // Bestellungen keyed by Bestellung-ID
        $bestellungenByBestellId = Bestellung::findAllEntries($db, $filter);

        // Gruppieren nach kunde_ID
        $bestellungenByKundeId = [];
        foreach ($bestellungenByBestellId as $b) {
            $kid = $b['kunde_ID'];
            $bestellungenByKundeId[$kid][] = $b;
        }

        // Attach
        foreach ($kunden as $kid => &$kunde) {
            $kunde['bestellungen'] = $bestellungenByKundeId[(int)$kid] ?? [];
        }
        unset($kunde);
    }

    public static function attachZahlungen(PDO $db, array &$kunden, ?DbFilter $extraFilter = null): void
    {
            xdebug_break();
        if (!$kunden) return;

        // 1) bestellID -> kundeID map bauen + alle bestellIDs sammeln
        $bestellToKunde = [];
        $bestellIDs = [];

        foreach ($kunden as $kundeId => $kunde) {
            foreach (($kunde['bestellungen'] ?? []) as $bestellung) {
                if (!isset($bestellung['ID'])) continue;

                $bid = (int)$bestellung['ID'];
                $bestellIDs[] = $bid;
                $bestellToKunde[$bid] = (int)$kundeId;
            }
        }

        $bestellIDs = array_values(array_unique($bestellIDs));

        // Default: jeder Kunde hat erstmal leere Zahlungen
        foreach ($kunden as $kid => &$kunde) {
            $kunde['zahlungen'] = [];
        }
        unset($kunde);

        if (!$bestellIDs) return;

        // 2) Zahlungen batch laden (findAllEntries bleibt keyed nach Zahlung-ID!)
        $filter = $extraFilter ?? new DbFilter();
        $filter->whereIn('bestellung_ID', $bestellIDs);

        $zahlungenByZahlungId = Zahlung::findAllEntries($db, $filter);

        // 3) Zahlungen nach Kunde gruppieren (über bestell_ID -> kundeID)
        foreach ($zahlungenByZahlungId as $zahlung) {
            $bid = (int)($zahlung['bestellung'] ?? 0);
            if ($bid <= 0) continue;

            $kid = $bestellToKunde[$bid] ?? null;
            if ($kid === null) continue;

            $kunden[$kid]['zahlungen'][] = $zahlung;
        }
    }

    public function toArray(): array
    {
        return self::toPersonArray();
    }
}