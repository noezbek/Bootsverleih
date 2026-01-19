<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Filters\DbFilter;
use PDO;

class Bestellung extends DatabaseEntry
{
    private Kunde|int $kunde;
    private OrderStatus|int $bestellstatus;
    public function __construct(
        Kunde|int $kunde,
        OrderStatus|int $bestellstatus,
        ?int $id = null,
        ?bool $active = true,
        string|null $updated_at = null,
        string|null $created = null,
        User|int|null $user = null,
    ) {
        parent::__construct($id, $active, $updated_at, $created, $user);
        $this->kunde = $kunde;
        $this->bestellstatus = $bestellstatus;
    }

    public static function getTable(): string
    {
        return 'bestellungen';
    }

    public function saveEntry(PDO $db): void
    {
        $sqlString = empty($this->id) ? self::getInsertStmnt(): self::getUpdateStmnt();
        $stmt = $db->prepare($sqlString);
        $stmt->bindValue(":kunde_ID", $this->getID(), PDO::PARAM_INT);
        $stmt->bindValue(":bestellstatus", $this->getID(), PDO::PARAM_INT);
        $this->saveData($stmt, $db);
    }

    protected static function getInsertStmnt() : string
    {
        $table = self::getTable();
        return "INSERT INTO $table (kunde_ID, bestellstatus, userID)
                 VALUES (:kunde_ID, :bestellstatus, :Benutzer)";
    }

    protected static function getUpdateStmnt() : string
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
}