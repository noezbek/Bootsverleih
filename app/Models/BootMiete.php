<?php

namespace App\Models;

use App\Filters\DbFilter;
use DateTime;
use PDO;

class BootMiete extends DatabaseEntry
{
    private Boot|int $boot;
    private Bestellung|int $bestellung;
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

    public function getCalculatedDays() : int
    {
        $start = new DateTime($this->getStartdatum());
        $end   = new DateTime($this->getEnddatum());
        return $start->diff($end)->days;
    }

    public function getCalculatedSollPreis() : float
    {
        $days = $this->getCalculatedDays();
        return $this>$this->getPreisProTag() * $days;
    }

    public function getBestellung(): Bestellung|int
    {
        return $this->bestellung;
    }

    public function getBoot(): Boot|int
    {
        return $this->boot;
    }

    public function getStartdatum(): string
    {
        return $this->startdatum;
    }

    public function getEnddatum(): string
    {
        return $this->enddatum;
    }

    public function getPreisProTag(): float
    {
        return $this->preisProTag;
    }

    protected static function getInsertStmnt() : string
    {
        $table = self::getTable();
        return "INSERT INTO $table (bestellung_ID, boot_ID, startdatum, enddatum, preis_pro_tag, userID) VALUES (:bestellung_ID, :boot_ID, :startdatum, :enddatum, :preis_pro_tag, :Benutzer)";
    }

    protected static function getUpdateStmnt() : string
    {
        $table = self::getTable();
        return "UPDATE $table SET bestellung_ID=:bestellung_ID, boot_ID=:boot_ID, startdatum =:startdatum, enddatum =:enddatum, preis_pro_tag =:preis_pro_tag, userID=:Benutzer, active=:Active WHERE ID = :ID";
    }


    public function saveEntry(PDO $db): void
    {
        $sqlString = empty($this->id) ? self::getInsertStmnt(): self::getUpdateStmnt();
        $stmt = $db->prepare($sqlString);
        $stmt->bindValue(":bestellung_ID", $this->getBestellung());
        $stmt->bindValue(":boot_ID", $this->getBoot());
        $stmt->bindValue(":startdatum", $this->getStartdatum());
        $stmt->bindValue(":enddatum", $this->getEnddatum());
        $stmt->bindValue(":preis_pro_tag", $this->getPreisProTag());
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
