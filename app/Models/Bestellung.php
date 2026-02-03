<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Filters\DbFilter;
use PDO;

class Bestellung extends DatabaseEntry
{
    private Kunde|int $kunde;
    private OrderStatus|int $bestellstatus;
    private array $reservierteLiegeplaetze = [];
    private array $gemieteteBoote = [];

    public function __construct(
        Kunde|int $kunde,
        OrderStatus|int $bestellstatus,
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

    protected static function getInsertStmnt() : string
    {
        $table = self::getTable();
        return "INSERT INTO $table (kunde_ID, bestellstatus, userID) VALUES (:kunde_ID, :bestellstatus, :Benutzer)";
    }

    protected static function getUpdateStmnt() : string
    {
        $table = self::getTable();
        return "UPDATE $table SET kunde_ID=:kunde_ID, bestellstatus=:bestellstatus, userID=:Benutzer, active=:Active WHERE ID = :ID";
    }


    public function saveEntry(PDO $db): void
    {
        $sqlString = empty($this->id) ? self::getInsertStmnt(): self::getUpdateStmnt();
        $stmt = $db->prepare($sqlString);
        $stmt->bindValue(":kunde_ID", $this->getKunde());
        $stmt->bindValue(":bestellstatus", $this->getBestellstatus());
        $this->saveData($stmt, $db);
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
            'reservierteLiegeplaetze' => $this->reservierteLiegeplaetze,
            'gemieteteBoote' => $this->gemieteteBoote,
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

    public function getReservierteLiegeplaetze(): array
    {
        return $this->reservierteLiegeplaetze;
    }

    public function getGemieteteBoote(): array
    {
        return $this->gemieteteBoote;
    }

    public function setReservierteLiegeplaetze(array $reservierteLiegeplaetze): void
    {
        $this->reservierteLiegeplaetze = $reservierteLiegeplaetze;
    }

    public function setGemieteteBoote(array $gemieteteBoote): void
    {
        $this->gemieteteBoote = $gemieteteBoote;
    }
}
