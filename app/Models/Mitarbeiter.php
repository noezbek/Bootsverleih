<?php

namespace App\Models;

use PDO;

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
        return 'mitarbeiter';
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

    public static function findByIdEntry(PDO $db, int $id): ?static
    {
        $table = self::getTable();

        $stmt = $db->prepare("SELECT * FROM $table WHERE ID = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row ? new Mitarbeiter(
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

    public static function findAllEntries(PDO $db): array
    {
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

    public function toArray(): array
    {
        return [
            'ID'       => $this->id,
            'active' => $this->active,
            'vorname' => $this->vorname,
            'nachname' => $this->nachname,
            'email' => $this->email,
            'geburtsdatum' => $this->geburtsdatum,
            'telefon' => $this->telefon,
            'strasse' => $this->strasse,
            'plz' => $this->plz,
            'stadt' => $this->stadt
        ];
    }
}