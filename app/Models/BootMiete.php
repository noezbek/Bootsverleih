<?php

namespace App\Models;

use App\Filters\DbFilter;
use PDO;

class BootMiete extends DatabaseEntry
{
    private Bestellung|int $bestellung;
    private Boot|int $boot;
    private string $startdatum;
    private string $enddatum;
    private float $preisProTag;

    public function __construct(
        Bestellung|int $bestellung,
        Boot|int $boot,
        string $startdatum,
        string $enddatum,
        float $preisProTag,
        ?int $id = null,
        ?bool $active = true,
        ?string $updated_at = null,
        ?string $created_at = null,
        User|int|null $user = null
    ) {
        parent::__construct($id, $active, $updated_at, $created_at, $user);
        $this->bestellung = $bestellung;
        $this->boot = $boot;
        $this->startdatum = $startdatum;
        $this->enddatum = $enddatum;
        $this->preisProTag = $preisProTag;
    }

    public static function getTable(): string
    {
        return 'boot_mieten';
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

    public static function findAllEntries(PDO $db, ?DbFilter $filter = null): array
    {
        $table = self::getTable();

        $filter ??= new DbFilter();
        $c = $filter->compile();

        $sql = "SELECT * FROM $table" . $c['whereSql'];
        $stmt = $db->prepare($sql);
        $stmt->execute($c['params']);

        /** @var BootMiete[] $bootMieten */
        $bootMieten = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $bootMiete = new BootMiete(
                (int)$row['bestellung_ID'],
                (int)$row['boot_ID'],
                $row['startdatum'],
                $row['enddatum'],
                (float)$row['preis_pro_tag'],
                (int)$row['ID'],
                (bool)$row['active'],
                $row['updated_at'],
                $row['created_at'],
                $row['userID'],
            );

            $bootMieten[$bootMiete->getID()] = $bootMiete;
        }

        return $bootMieten;
    }

    public function getBestellung() : Bestellung|int
    {
        return $this->bestellung;
    }

    public function toArray(): array
    {
        // TODO: Implement toArray() method.
        return [];
    }
}
