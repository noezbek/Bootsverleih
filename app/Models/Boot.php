<?php

namespace App\Models;

use DBConnection;

class Boot extends DatabaseEntry
{
    private string $yachtTyp;
    private ?float $laenge;
    private ?float $breite;
    private ?float $tiefgang;

    public function __construct(
        string $yachtTyp,
        ?float $laenge = null,
        ?float $breite = null,
        ?float $tiefgang = null,
        ?int $id = null
    ) {
        parent::__construct($id);
        $this->yachtTyp = $yachtTyp;
        $this->laenge = $laenge;
        $this->breite = $breite;
        $this->tiefgang = $tiefgang;
    }

    public static function getTable(): string
    {
        return 'boote';
    }

    public function saveEntry(): void
    {
        $db = DBConnection::getConnection();
        $table = self::getTable();

        if ($this->id === null) {
            $stmt = $db->prepare(
                "INSERT INTO $table (yacht_typ, laenge, breite, tiefgang)
                 VALUES (:y, :l, :b, :t)"
            );
            $stmt->execute([
                ':y' => $this->yachtTyp,
                ':l' => $this->laenge,
                ':b' => $this->breite,
                ':t' => $this->tiefgang,
            ]);
            $this->id = (int)$db->lastInsertId();
        } else {
            $stmt = $db->prepare(
                "UPDATE $table
                 SET yacht_typ = :y, laenge = :l, breite = :b, tiefgang = :t
                 WHERE ID = :id"
            );
            $stmt->execute([
                ':y'  => $this->yachtTyp,
                ':l'  => $this->laenge,
                ':b'  => $this->breite,
                ':t'  => $this->tiefgang,
                ':id' => $this->id,
            ]);
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

        return $row ? new Boot(
            $row['yacht_typ'],
            $row['laenge'] !== null ? (float)$row['laenge'] : null,
            $row['breite'] !== null ? (float)$row['breite'] : null,
            $row['tiefgang'] !== null ? (float)$row['tiefgang'] : null,
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
            $list[] = new Boot(
                $row['yacht_typ'],
                $row['laenge'] !== null ? (float)$row['laenge'] : null,
                $row['breite'] !== null ? (float)$row['breite'] : null,
                $row['tiefgang'] !== null ? (float)$row['tiefgang'] : null,
                (int)$row['ID']
            );
        }

        return $list;
    }
}