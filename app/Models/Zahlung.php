<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Filters\DbFilter;
use PDO;

class Zahlung extends DatabaseEntry
{
    private Bestellung|int $bestellung;
    private PaymentStatus|int $zahlungsstatus;
    private float $betrag;
    private string|null $bezahlt_am;
    private string|null $faellig_am;
    public function __construct(
        Bestellung|int $bestellung,
        OrderStatus|int $zahlungsstatus,
        float $betrag,
        string|null $bezahlt_am,
        string|null $faellig_am,
        ?int $id = null,
        ?bool $active = true,
        string|null $updated_at = null,
        string|null $created = null,
        User|int|null $user = null,
    ) {
        parent::__construct($id, $active, $updated_at, $created, $user);
        $this->bestellung = $bestellung;
        $this->zahlungsstatus = $zahlungsstatus;
        $this->betrag = $betrag;
        $this->bezahlt_am = $bezahlt_am;
        $this->faellig_am = $faellig_am;
    }

    public static function getTable(): string
    {
        return 'zahlungen';
    }

    public function saveEntry(PDO $db): void
    {
        $sqlString = empty($this->id) ? self::getInsertStmnt(): self::getUpdateStmnt();
        $stmt = $db->prepare($sqlString);
        $stmt->bindValue(":bestellung_ID", $this->getBestellung(), PDO::PARAM_INT);
        $stmt->bindValue(":zahlungsstatus", $this->getZahlungsstatus(), PDO::PARAM_INT);
        $stmt->bindValue(":betrag", $this->getBetrag());
        $stmt->bindValue(":bezahlt_am", $this->getBezahlt_am());
        $stmt->bindValue(":faellig_am", $this->getFaellig_am());
        $this->saveData($stmt, $db);
    }

    protected static function getInsertStmnt() : string
    {
        $table = self::getTable();
        return "INSERT INTO $table (bestellung_ID, zahlungsstatus, betrag, bezahlt_am, faellig_am, userID)
                 VALUES (:bestellung_ID, :zahlungsstatus, :betrag, :bezahlt_am, :faellig_am, :Benutzer)";
    }

    protected static function getUpdateStmnt() : string
    {
        $table = self::getTable();
        return "UPDATE $table
                 SET bestellung_ID=:bestellung_ID, zahlungsstatus=:zahlungsstatus, betrag=:betrag, bezahlt_am=:bezahlt_am, faellig_am=:faellig_am, userID=:Benutzer, active=:Active
                 WHERE ID = :ID";
    }

    public static function findByIdEntry(PDO $db, int $id): array|null
    {
        $table = self::getTable();

        $stmt = $db->prepare("SELECT * FROM $table WHERE ID = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return null;

        $bestellung = new Zahlung(
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
            $bestellung = new Zahlung(
                (int)$row['bestellung_ID'],
                $row['zahlungsstatus'],
                $row['betrag'],
                $row['bezahlt_am'],
                $row['faellig_am'],
                (bool)$row['active'],
                $row['updated_at'],
                $row['created_at'],
                $row['userID'],
            );
            $map[$id]= $bestellung->toArray();
        }

        return $map;
    }

    public function toArray(): array
    {
        return [
            'ID' => $this->id,
            'bestellung' => $this->bestellung,
            'zahlungsstatus' => $this->zahlungsstatus,
            'betrag' => $this->betrag,
            'bezahlt_am' => $this->bezahlt_am,
            'faellig_am' => $this->faellig_am,
            'active' => $this->active,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }

    public function getBestellung(): Bestellung|int
    {
        return $this->bestellung;
    }

    public function setBestellung(Bestellung|int $bestellung): void
    {
        $this->bestellung = $bestellung;
    }

    public function getZahlungsstatus(): PaymentStatus|int
    {
        return $this->zahlungsstatus;
    }

    public function setZahlungsstatus(PaymentStatus|int $zahlungsstatus): void
    {
        $this->zahlungsstatus = $zahlungsstatus;
    }

    public function getBetrag(): float
    {
        return $this->betrag;
    }

    public function setBetrag(float $betrag): void
    {
        $this->betrag = $betrag;
    }

    public function getBezahlt_am(): string
    {
        return $this->bezahlt_am;
    }

    public function setBezahlt_am(string $bezahlt_am): void
    {
        $this->bezahlt_am = $bezahlt_am;
    }

    public function getFaellig_am(): string
    {
        return $this->faellig_am;
    }

    public function setFaellig_am(string $faellig_am): void
    {
        $this->faellig_am = $faellig_am;
    }
}