<?php

namespace App\Models;

use App\Filters\DbFilter;
use PDO;

class Bestellung extends DatabaseEntry
{
    private int $kunde;
    private int $bestellstatus;

    public function __construct(
        int $kunde,
        int $bestellstatus,
        ?int $id = null,
        bool $active = true,
        ?string $updated_at = null,
        ?string $created_at = null,
        ?int $userID = null
    ) {
        parent::__construct($id, $active, $updated_at, $created_at, $userID);
        $this->kunde = $kunde;
        $this->bestellstatus = $bestellstatus;
    }

    public static function getTable(): string
    {
        return 'bestellungen';
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
                (int)$row['kunde_ID'],
                (int)$row['bestellstatus'],
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

    public function getKundeId(): int
    {
        return $this->kunde;
    }

    public function getBestellstatus(): int
    {
        return $this->bestellstatus;
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
            'kunde' => $this->kunde,
            'bestellstatus' => $this->bestellstatus,
            'active' => $this->active,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
