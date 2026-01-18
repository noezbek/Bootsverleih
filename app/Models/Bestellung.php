<?php

namespace App\Models;

use App\Enums\OrderStatus;
use PDO;

class Bestellung extends DatabaseEntry
{
    private Kunde|int $kunde;
    private OrderStatus|int $bestellstatus;
    public function __construct(
        Kunde|int $kunde,
        OrderStatus|int $bestellstatus,
        ?int $id = null,
        ?bool $active = true,
        string|null $updated_at = null,
        string|null $created = null,
        User|int|null $user = null,
    ) {
        parent::__construct($id, $active, $updated_at, $created, $user);
        $this->kunde = $kunde;
        $this->bestellstatus = $bestellstatus;
    }

    public static function getTable(): string
    {
        return 'bestellungen';
    }

    public function saveEntry(PDO $db): void
    {
        $sqlString = empty($this->id) ? self::getInsertStmnt(): self::getUpdateStmnt();
        $stmt = $db->prepare($sqlString);
        $stmt->bindValue(":kunde_ID", $this->getID(), PDO::PARAM_INT);
        $stmt->bindValue(":bestellstatus", $this->getID(), PDO::PARAM_INT);
        $this->saveData($stmt, $db);
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
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return null;

        $bestellung = new Bestellung(
            $row['kunde_ID'],
            $row['bestellstatus'],
            $row['ID'],
            (bool)$row['active'],
            $row['updated_at'],
            $row['created_at'],
            $row['userID'],
        );

        return $bestellung->toArray();
    }

    public static function findAllEntries(PDO $db): array
    {
        $table = self::getTable();

        $stmt = $db->query("SELECT * FROM $table");

        $bestellungIDs = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $bestellung = new Bestellung(
                $row['kunde_ID'],
                $row['bestellstatus'],
                $row['ID'],
                (bool)$row['active'],
                $row['updated_at'],
                $row['created_at'],
                $row['userID'],
            );

            $id = $bestellung->getID();
            $bestellungIDs[$id] = $bestellung;
        }

        if (!$bestellungIDs) return [];


        $res = [];
        foreach ($bestellungIDs as $id => $bestellung) {
            $res[$id] = $bestellung->toArray();
        }

        return $res;
    }

    public function toArray(): array
    {
        return [
            'ID' => $this->id,
            'kunde_ID' => $this->kunde,
            'bestellstatus' => $this->bestellstatus,
            'active' => $this->active,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }

    public function getKunde(): Kunde|int
    {
        return $this->kunde;
    }

    public function setKunde(Kunde|int $kunde): void
    {
        $this->kunde = $kunde;
    }

    public function getBestellstatus(): OrderStatus|int
    {
        return $this->bestellstatus;
    }

    public function setBestellstatus(OrderStatus|int $bestellstatus): void
    {
        $this->bestellstatus = $bestellstatus;
    }
}