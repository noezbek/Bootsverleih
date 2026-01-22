<?php

namespace App\Models;

use App\Filters\DbFilter;
use PDO;

class Kunde extends Person
{

    private array $bestellungen;

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

    public static function findByIdEntry(PDO $db, int $id): self|null
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

        return $kunde;
    }


    public static function findAllEntries(PDO $db, ?DbFilter $filter = null): array
    {
        $table = self::getTable();

        $stmt = $db->query("SELECT * FROM $table");

        $kundenById = [];   // id => Kunde
        $kundeIDs = [];

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
                (int)$row['ID'],
                (bool)$row['active'],
            );

            $id = $kunde->getID();
            $kundenById[$id] = $kunde;
            $kundeIDs[] = $id;
        }

        if (!$kundeIDs) return [];

        /**Bestellungen batch laden */
        // kundeID => [Bestellung, ...]
        $bestellungenByKunde = self::selectBestellRel($db, $kundeIDs);

        /**alles direkt setzen */
        $res = [];
        foreach ($kundenById as $kid => $kunde) {
            $bestellungen = $bestellungenByKunde[$kid] ?? [];

            $kunde->setBestellungen($bestellungen);
            $res[$kid] = $kunde;
        }

        return $res;
    }


    private static function selectBestellRel(PDO $db, array $kundeIDs): array
    {
        $filter = new DbFilter();
        $filter->whereIn('kunde_ID', $kundeIDs);

        $bestellungen = Bestellung::findAllEntries($db, $filter);

        $byKunde = [];      // kundeID => [bestellungen]

        foreach ($bestellungen as $b) {
            $kid = $b->getKunde();

            $byKunde[$kid][] = $b;
        }

        return $byKunde;
    }

    public function toArray(): array
    {
        $bestellungen = [];

        foreach ($this->bestellungen as $bestellung) {
            $bestellungen[$bestellung->getID()] = $bestellung->toArray();
        }

        return [
            ...self::toPersonArray(),
            'bestellungen' => $bestellungen,
        ];
    }

    public function getBestellungen(): array
    {
        return $this->bestellungen;
    }

    public function setBestellungen(array $bestellungen): void
    {
        $this->bestellungen = $bestellungen;
    }
}