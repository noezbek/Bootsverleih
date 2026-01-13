<?php

namespace App\Models;

use App\Models\DBConnection;

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
        ?bool $kunde = true,
        ?int $id = null
    ) {
        parent::__construct($vorname, $nachname, $email, $geburtsdatum, $telefon, $strasse, $plz, $stadt, $kunde, $id);
    }

    public static function getTable(): string
    {
        return 'kunde';
    }

    public function saveEntry(): void
    {
        $db = DBConnection::getConnection();
        $table = self::getTable();

        if ($this->id === null) {
            $stmt = $db->prepare(
                "INSERT INTO $table (vorname, nachname, email, geburtsdatum, telefon, strasse, plz, stadt, active)
                 VALUES (:v, :n, :e, :g, :t, :s, :p, :st, 1)"
            );
            $stmt->execute($this->getPersonBindArray());
            $this->id = (int) $db->lastInsertId();
        } else {
            $stmt = $db->prepare(
                "UPDATE $table
                 SET vorname=:v, nachname=:n, email=:e, geburtsdatum=:g, telefon=:t, strasse=:s, plz=:p, stadt=:st, active=:active
                 WHERE ID = :id"
            );
            $data = [
                ...$this->getPersonBindArray(),
                'active' => $this->getActive()
            ];
            $data[':id'] = $this->id;
            $stmt->execute($data);
        }
    }

    public function deleteEntry(): void
    {
        if ($this->id === null)
            return;

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

        return $row ? new Kunde(
            $row['vorname'],
            $row['nachname'],
            $row['email'],
            $row['geburtsdatum'],
            (int) $row['telefon'],
            $row['strasse'],
            (int) $row['plz'],
            $row['stadt'],
            (bool) $row['active'] ?? true,
            (int) $row['ID']
        ) : null;
    }

    public static function findAllEntries(): array
    {
        $db = DBConnection::getConnection();
        $table = self::getTable();

        $stmt = $db->query("SELECT * FROM $table");

        $list = [];

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

            // ID als Key verwenden
            $id = (int) $row['ID'];

            // Optional: Feldnamen vereinheitlichen
            $list[$id] = [
                'ID' => $id,
                'active' => (bool)$row['active'],
                'vorname' => $row['vorname'],
                'nachname' => $row['nachname'],
                'email' => $row['email'],
                'geburtsdatum' => $row['geburtsdatum'],
                'telefon' => $row['telefon'],
                'strasse' => $row['strasse'],
                'plz' => $row['plz'],
                'stadt' => $row['stadt']
            ];
        }

        return $list;
    }
}