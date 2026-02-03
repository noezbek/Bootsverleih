<?php

namespace App\Models;

use App\Filters\DbFilter;
use PDO;

class BootMiete extends DatabaseEntry
{
    private int $boot;
    private int $bestellung;
    private string $startdatum;
    private string $enddatum;
    private float $preisProTag;

    public function __construct(
        int $boot,
        int $bestellung,
        string $startdatum,
        string $enddatum,
        float $preisProTag,
        ?int $id = null,
        bool $active = true,
        ?string $updated_at = null,
        ?string $created_at = null,
        ?int $userID = null
    ) {
        parent::__construct($id, $active, $updated_at, $created_at, $userID);
        $this->boot = $boot;
        $this->bestellung = $bestellung;
        $this->startdatum = $startdatum;
        $this->enddatum = $enddatum;
        $this->preisProTag = $preisProTag;
    }

    public static function getTable(): string
    {
        return 'boot_mieten';
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
                (int)$row['boot_ID'],
                (int)$row['bestellung_ID'],
                $row['startdatum'],
                $row['enddatum'],
                (float)$row['preis_pro_tag'],
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
            'boot' => $this->boot,
            'startdatum' => $this->startdatum,
            'enddatum' => $this->enddatum,
            'preisProTag' => $this->preisProTag,
            'active' => $this->active,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
