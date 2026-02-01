<?php

namespace App\Models;

use App\Filters\DbFilter;
use PDO;

class LiegeplatzReservierung extends DatabaseEntry
{
    private Bestellung|int $bestellung;
    private Liegeplatz|int $liegeplatz;
    private Boot|int $boot;
    private string $startdatum;
    private string $enddatum;
    private float $preisProTag;

    public function __construct(
        Bestellung|int $bestellung,
        Liegeplatz|int $liegeplatz,
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
        $this->liegeplatz = $liegeplatz;
        $this->boot = $boot;
        $this->startdatum = $startdatum;
        $this->enddatum = $enddatum;
        $this->preisProTag = $preisProTag;
    }

    public static function getTable(): string
    {
        return 'liegeplatz_reservierungen';
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

        /** @var LiegeplatzReservierung[] $liegeplatzReservierung */
        $liegeplatzReservierung = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $bootMiete = new LiegeplatzReservierung(
                (int)$row['bestellung_ID'],
                (int)$row['liegeplatz_ID'],
                $row['startdatum'],
                $row['enddatum'],
                (float)$row['preis_pro_tag'],
                (int)$row['ID'],
                (bool)$row['active'],
                $row['updated_at'],
                $row['created_at'],
                $row['userID'],
            );

            $liegeplatzReservierung[$bootMiete->getID()] = $bootMiete;
        }

        return $liegeplatzReservierung;
    }

    public function toArray(): array
    {
        // TODO: Implement toArray() method.
        return [];
    }

    public function getBestellung() : Bestellung|int
    {
        return $this->bestellung;
    }
}
