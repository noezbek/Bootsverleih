<?php

namespace App\Models;

use DBConnection;

class Mitarbeiter extends Person
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
        ?int $id = null
    ) {
        parent::__construct($vorname, $nachname, $email, $geburtsdatum, $telefon, $strasse, $plz, $stadt, $id);
    }

    public static function getTable(): string
    {
        return 'mitarbeiter';
    }

    public function saveEntry(): void
    {
        $db = DBConnection::getConnection();
        $table = self::getTable();

        if ($this->id === null) {
            $stmt = $db->prepare(
                "INSERT INTO $table (vorname, nachname, email, geburtsdatum, telefon, strasse, plz, stadt)
                 VALUES (:v, :n, :e, :g, :t, :s, :p, :st)"
            );
            $stmt->execute($this->getPersonBindArray());
            $this->id = (int)$db->lastInsertId();
        } else {
            $stmt = $db->prepare(
                "UPDATE $table
                 SET vorname=:v, nachname=:n, email=:e, geburtsdatum=:g, telefon=:t, strasse=:s, plz=:p, stadt=:st
                 WHERE ID = :id"
            );
            $data = $this->getPersonBindArray();
            $data[':id'] = $this->id;
            $stmt->execute($data);
        }
    }

    public function deleteEntry(): void
    {
        if ($this->id === null) return;

        $db = DBConnection::getConnection();
        $table = self::getTable();
        $stmt = $db->prepare("DELETE FROM $table WHERE ID = :id");
        $stmt->execute([':id' => $this->id]);
    }

    public static function findByIdEntry(int $id): ?static
    {
        $db = DBConnection::getConnection();
        $table = self::getTable();

        $stmt = $db->prepare("SELECT * FROM $table WHERE ID = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row ? new Mitarbeiter(
            $row['vorname'],
            $row['nachname'],
            $row['email'],
            $row['geburtsdatum'],
            (int)$row['telefon'],
            $row['strasse'],
            (int)$row['plz'],
            $row['stadt'],
            (int)$row['ID']
        ) : null;
    }

    public static function findAllEntries(): array
    {
        $db = DBConnection::getConnection();
        $table = self::getTable();

        $stmt = $db->query("SELECT * FROM $table");

        $list = [];
        while ($row = $stmt->fetch()) {
            $list[] = new Mitarbeiter(
                $row['vorname'],
                $row['nachname'],
                $row['email'],
                $row['geburtsdatum'],
                (int)$row['telefon'],
                $row['strasse'],
                (int)$row['plz'],
                $row['stadt'],
                (int)$row['ID']
            );
        }
        return $list;
    }
}