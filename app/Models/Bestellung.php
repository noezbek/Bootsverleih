<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Filters\DbFilter;
use PDO;

class Bestellung extends DatabaseEntry
{
    private Kunde|int $kunde;
    private OrderStatus|int $bestellstatus;
    private array $boote;
    private array $liegeplatze;

    public function __construct(
        Kunde|int       $kunde,
        OrderStatus|int $bestellstatus,
        ?int            $id = null,
        ?bool           $active = true,
        string|null     $updated_at = null,
        string|null     $created = null,
        User|int|null   $user = null,
    )
    {
        parent::__construct($id, $active, $updated_at, $created, $user);
        $this->kunde = $kunde;
        $this->bestellstatus = $bestellstatus;
    }

    public static function getTable(): string
    {
        return 'bestellungen';
    }

    public static function getBootRelTable(): string
    {
        return 'bestellung_boot';
    }

    public static function getLiegeplatzRelTable(): string
    {
        return 'bestellung_liegeplatz';
    }

    public function saveEntry(PDO $db): void
    {
        $sqlString = empty($this->id) ? self::getInsertStmnt() : self::getUpdateStmnt();
        $stmt = $db->prepare($sqlString);
        $stmt->bindValue(":kunde_ID", $this->getID(), PDO::PARAM_INT);
        $stmt->bindValue(":bestellstatus", $this->getID(), PDO::PARAM_INT);
        $this->saveData($stmt, $db);
        $this->saveBestellBoote($db);
        $this->saveBestellLiegeplaetze($db);
    }

    public function saveBestellBoote(PDO $db) : void
    {
        self::saveRelations($db, self::getBootRelTable(), 'bestellung_ID', 'boot_ID', $this->getID(), $this->getBoote());
    }

    public function saveBestellLiegeplaetze(PDO $db) : void
    {
        self::saveRelations($db, self::getBootRelTable(), 'bestellung_ID', 'liegeplatz_ID', $this->getID(), $this->getLiegeplatze());
    }

    protected static function getInsertStmnt(): string
    {
        $table = self::getTable();
        return "INSERT INTO $table (kunde_ID, bestellstatus, userID)
                 VALUES (:kunde_ID, :bestellstatus, :Benutzer)";
    }

    protected static function getUpdateStmnt(): string
    {
        $table = self::getTable();
        return "UPDATE $table
                 SET kunde_ID=:kunde_ID, bestellstatus=:bestellstatus, userID=:Benutzer, active=:Active
                 WHERE ID = :ID";
    }

    public static function findByIdEntry(PDO $db, int $id): array|null
    {
        $table = self::getTable();

        $stmt = $db->prepare("SELECT * FROM $table WHERE ID = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return null;

        $bestellung = new Bestellung(
            $row['kunde_ID'],
            $row['bestellstatus'],
            $row['ID'],
            (bool)$row['active'],
            $row['updated_at'],
            $row['created_at'],
            $row['userID'],
        );

        return $bestellung->toArray();
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
            $bestellung = new Bestellung(
                (int)$row['kunde_ID'],
                $row['bestellstatus'],
                $id,
                (bool)$row['active'],
                $row['updated_at'],
                $row['created_at'],
                $row['userID'],
            );
            $map[$id] = $bestellung->toArray();
        }

        return $map;
    }

    private static function attachBoote(PDO $db, array $bestellungen, ?DbFilter $extraFilter = null): void
    {
//        if (!$bestellungen) return;
//
//        $orderIDs = array_map('intval', array_keys($bestellungen));
//
//        $filter = $extraFilter ?? new DbFilter();
//        $filter->whereIn('kunde_ID', $bestellungen);
//
//        // Bestellungen keyed by Bestellung-ID
//        $bestellungenByBestellId = Bestellung::findAllEntries($db, $filter);
//
//        // Gruppieren nach kunde_ID
//        $bestellungenByKundeId = [];
//        foreach ($bestellungenByBestellId as $b) {
//            $kid = $b['kunde_ID'];
//            $bestellungenByKundeId[$kid][] = $b;
//        }
//
//        // Attach
//        foreach ($bestellungen as $kid => &$kunde) {
//            $kunde['bestellungen'] = $bestellungenByKundeId[(int)$kid] ?? [];
//        }
//        unset($kunde);
    }


    public function toArray(): array
    {
        return [
            'ID' => $this->id,
            'kunde_ID' => $this->kunde,
            'bestellstatus' => $this->bestellstatus,
            'active' => $this->active,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }

    public function getKunde(): Kunde|int
    {
        return $this->kunde;
    }

    public function setKunde(Kunde|int $kunde): void
    {
        $this->kunde = $kunde;
    }

    public function getBestellstatus(): OrderStatus|int
    {
        return $this->bestellstatus;
    }

    public function setBestellstatus(OrderStatus|int $bestellstatus): void
    {
        $this->bestellstatus = $bestellstatus;
    }

    public function getBoote(): array
    {
        return $this->boote;
    }

    public function setBoote(array $boote): void
    {
        $this->boote = $boote;
    }

    public function getLiegeplatze(): array
    {
        return $this->liegeplatze;
    }

    public function setLiegeplatze(array $liegeplatze): void
    {
        $this->liegeplatze = $liegeplatze;
    }
}