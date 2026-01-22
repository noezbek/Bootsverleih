<?php

namespace App\Models;

use App\Filters\DbFilter;
use PDO;

class Liegeplatz extends DatabaseEntry
{
    private string $beschreibung;
    public function __construct(
        string $beschreibung,
        ?int $id = null,
        ?bool $active = true,
        string|null $updated_at = null,
        string|null $created = null,
        User|int|null $user = null,
    ) {
        parent::__construct($id, $active, $updated_at, $created, $user);
        $this->beschreibung = $beschreibung;
    }

    public static function getTable(): string
    {
        return 'liegeplaetze';
    }

    public function saveEntry(PDO $db): void
    {
        $sqlString = empty($this->id) ? self::getInsertStmnt(): self::getUpdateStmnt();
        $stmt = $db->prepare($sqlString);
        $stmt->bindValue(":beschreibung", $this->getID());
        $this->saveData($stmt, $db);
    }

    protected static function getInsertStmnt() : string
    {
        $table = self::getTable();
        return "INSERT INTO $table (beschreibung, userID)
                 VALUES (:beschreibung,, :Benutzer)";
    }

    protected static function getUpdateStmnt() : string
    {
        $table = self::getTable();
        return "UPDATE $table
                 SET beschreibung=:beschreibung, userID=:Benutzer, active=:Active
                 WHERE ID = :ID";
    }

    public static function findByIdEntry(PDO $db, int $id): self|null
    {
        $table = self::getTable();

        $stmt = $db->prepare("SELECT * FROM $table WHERE ID = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return null;

        return new Liegeplatz(
            (int)$row['beschreibung'],
            $id,
            (bool)$row['active'],
            $row['updated_at'],
            $row['created_at'],
            $row['userID'],
        );
    }

    public static function findAllEntries(PDO $db, ?DbFilter $filter = null): array
    {
        $table = self::getTable();

        $filter ??= new DbFilter();
        $c = $filter->compile();

        $sql = "SELECT * FROM $table" . $c['whereSql'] . $c['orderSql'] . $c['limitSql'];

        $stmt = $db->prepare($sql);
        $stmt->execute($c['params']);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $map = [];
        foreach ($rows as $row) {
            $id = (int)$row['ID'];
            $liegeplatz = new Liegeplatz(
                (int)$row['beschreibung'],
                $id,
                (bool)$row['active'],
                $row['updated_at'],
                $row['created_at'],
                $row['userID'],
            );
            $map[$id] = $liegeplatz;
        }

        return $map;
    }


    public function toArray(): array
    {
        return [
            'ID' => $this->id,
            'beschreibung' => $this->beschreibung
        ];
    }

    public function getBeschreibung(): string
    {
        return $this->beschreibung;
    }

    public function setBeschreibung(string $beschreibung): void
    {
        $this->beschreibung = $beschreibung;
    }
}