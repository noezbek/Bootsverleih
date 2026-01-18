<?php

namespace App\Models;

use PDO;

class Mitarbeiter extends Person
{
    public function __construct(
        string        $vorname,
        string        $nachname,
        string        $email,
        string        $geburtsdatum,
        int           $telefon,
        string        $strasse,
        int           $plz,
        string        $stadt,
        ?int          $id = null,
        ?bool         $active = true,
        string|null   $updated_at = null,
        string|null   $created = null,
        User|int|null $user = null,
    )
    {
        parent::__construct($vorname, $nachname, $email, $geburtsdatum, $telefon, $strasse, $plz, $stadt, $id, $active, $updated_at, $created, $user);
    }

    public static function getTable(): string
    {
        return 'mitarbeiter';
    }

    public function saveEntry(PDO $db): void
    {
        $sqlString = empty($this->id) ? self::getInsertStmnt() : self::getUpdateStmnt();
        $stmt = $db->prepare($sqlString);
        $this->savePerson($stmt, $db);
    }

    protected static function getInsertStmnt(): string
    {
        $table = self::getTable();
        return "INSERT INTO $table (vorname, nachname, email, geburtsdatum, telefon, strasse, plz, stadt, userID)
                 VALUES (:vorname, :nachname, :email, :geburtsdatum, :telefon, :strasse, :plz, :stadt, :Benutzer)";
    }

    protected static function getUpdateStmnt(): string
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

        $mitarbeiter = new Mitarbeiter(
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

        return $mitarbeiter->toArray();
    }


    public static function findAllEntries(PDO $db): array
    {
        $table = self::getTable();

        $stmt = $db->query("SELECT * FROM $table");
        $res = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $mitarbeiter = new Mitarbeiter(
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

            $id = $mitarbeiter->getID(); // falls vorhanden, sonst (int)$row['ID']
            $res[$id] = $mitarbeiter->toArray();
        }

        return $res;
    }

    public function toArray(): array
    {
        return self::toPersonArray();
    }
}