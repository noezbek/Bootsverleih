<?php

namespace App\Models;

use App\Filters\DbFilter;
use PDO;

class Feature extends DatabaseEntry
{

    private string|null $bezeichnung;
    private string|null $beschreibung;

    public function __construct(
        string|null $bezeichnung,
        string|null $beschreibung,
        ?int $id = null,
        ?bool $active = true,
        string|null $updated_at = null,
        string|null $created = null,
        User|int|null $user = null,
    ) {
        parent::__construct( $id, $active, $updated_at, $created, $user);
        $this->bezeichnung = $bezeichnung;
        $this->beschreibung = $beschreibung;
    }

    protected static function getInsertStmnt() : string
    {
        $table = self::getTable();
        return "INSERT INTO $table (bezeichnung, beschreibung, userID)
                 VALUES (:bezeichnung, :beschreibung, :Benutzer)";
    }

    protected static function getUpdateStmnt() : string
    {
        $table = self::getTable();
        return "UPDATE $table
                 SET bezeichnung=:bezeichnung, beschreibung=:beschreibung, userID=:Benutzer, active=:Active
                 WHERE ID = :ID";
    }

    static public function getTable(): string
    {
        return 'features';
    }

    public function saveEntry(PDO $db): void
    {
        $sqlString = empty($this->id) ? self::getInsertStmnt(): self::getUpdateStmnt();
        $stmt = $db->prepare($sqlString);
        $stmt->bindValue(":bezeichnung", $this->getBezeichnung());
        $stmt->bindValue(":beschreibung", $this->getBeschreibung());
        $this->saveData($stmt, $db);
    }

    public static function findByIdEntry(PDO $db, int $id): self|null
    {
        $table = self::getTable();

        $stmt = $db->prepare("SELECT * FROM $table WHERE ID = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return null;

        return new Feature(
            $row['bezeichnung'],
            $row['beschreibung'],
            $row['ID'],
            (bool)$row['active'],
            $row['updated_at'],
            $row['created_at'],
            $row['userID'],
        );
    }

    public static function findAllEntries(PDO $db, ?DbFilter $filter = null): array
    {
        $table = self::getTable();

        $stmt = $db->query("SELECT * FROM $table");

        $featuresById = [];   // id => Boot

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $feature = new Feature(
                $row['bezeichnung'],
                $row['beschreibung'],
                (int)$row['ID'],
                (bool)$row['active'],
                $row['updated_at'],
                $row['created_at'],
                $row['userID'],
            );

            $id = $feature->getID();
            $featuresById[$id] = $feature;
        }

        return $featuresById;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'bezeichnung' => $this->bezeichnung,
            'beschreibung' => $this->beschreibung,
            'active' => $this->active,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }

    public function getBezeichnung(): string|null
    {
        return $this->bezeichnung;
    }

    public function setBezeichnung(string|null $bezeichnung): void
    {
        $this->bezeichnung = $bezeichnung;
    }

    public function getBeschreibung(): string|null
    {
        return $this->beschreibung;
    }

    public function setBeschreibung(string|null $beschreibung): void
    {
        $this->beschreibung = $beschreibung;
    }

}