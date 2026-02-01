<?php

namespace App\Models;

use App\Filters\DbFilter;
use PDO;

class Vertrag extends DatabaseEntry
{
    private int $bestellung;
    private int $zahlungsrhythmus;
    private int $zahlungsmethode;
    private string $vertragsbeginn;
    private ?string $gekuendigtAm;

    public function __construct(
        int $bestellung,
        int $zahlungsrhythmus,
        int $zahlungsmethode,
        string $vertragsbeginn,
        ?string $gekuendigtAm,
        ?int $id = null,
        bool $active = true,
        ?string $updated_at = null,
        ?string $created_at = null,
        ?int $userID = null
    ) {
        parent::__construct($id, $active, $updated_at, $created_at, $userID);
        $this->bestellung = $bestellung;
        $this->zahlungsrhythmus = $zahlungsrhythmus;
        $this->zahlungsmethode = $zahlungsmethode;
        $this->vertragsbeginn = $vertragsbeginn;
        $this->gekuendigtAm = $gekuendigtAm;
    }

    public static function getTable(): string
    {
        return 'vertraege';
    }

    public static function findAllEntries(PDO $db, ?DbFilter $filter = null): array
    {
        $filter ??= new DbFilter();
        $c = $filter->compile();

        $sql = "SELECT * FROM " . self::getTable() . $c['whereSql'];
        $stmt = $db->prepare($sql);
        $stmt->execute($c['params']);

        $map = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new self(
                (int)$row['bestellung_ID'],
                (int)$row['zahlungsrhythmus'],
                (int)$row['zahlungsmethode'],
                $row['vertragsbeginn'],
                $row['gekuendigt_am'],
                (int)$row['ID'],
                (bool)$row['active'],
                $row['updated_at'],
                $row['created_at'],
                $row['userID'],
            );

            $map[$obj->getID()] = $obj;
        }

        return $map;
    }

    public function getBestellungId(): int
    {
        return $this->bestellung;
    }

    protected static function getInsertStmnt(): string
    {
        // TODO: Implement getInsertStmnt() method.
        return '';
    }

    protected static function getUpdateStmnt(): string
    {
        // TODO: Implement getUpdateStmnt() method.
        return '';
    }

    public function saveEntry(PDO $db): void
    {
        // TODO: Implement saveEntry() method.
    }

    public static function findByIdEntry(PDO $db, int $id): self|null
    {
        // TODO: Implement findByIdEntry() method.
        return null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'bestellung' => $this->bestellung,
            'zahlungsrhythmus' => $this->zahlungsrhythmus,
            'zahlungsmethode' => $this->zahlungsmethode,
            'vertragsbeginn' => $this->vertragsbeginn,
            'gekuendigtAm' => $this->gekuendigtAm,
            'active' => $this->active,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
