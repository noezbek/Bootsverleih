<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Filters\DbFilter;
use PDO;

class Zahlung extends DatabaseEntry
{
    private Vertrag|int $vertrag;
    private PaymentStatus|int $zahlungsstatus;
    private float $betrag;
    private ?string $bezahlt_am;
    private ?string $faellig_am;

    public function __construct(
        Vertrag|int $vertrag,
        PaymentStatus|int $zahlungsstatus,
        float $betrag,
        ?string $bezahlt_am,
        ?string $faellig_am,
        ?int $id = null,
        bool $active = true,
        ?string $updated_at = null,
        ?string $created_at = null,
        User|int|null $user = null,
    ) {
        parent::__construct($id, $active, $updated_at, $created_at, $user);
        $this->vertrag = $vertrag;
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
        $sql = $this->id === null
            ? self::getInsertStmnt()
            : self::getUpdateStmnt();

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':vertrag_ID', $this->getVertrag(), PDO::PARAM_INT);
        $stmt->bindValue(':zahlungsstatus', $this->getZahlungsstatus(), PDO::PARAM_INT);
        $stmt->bindValue(':betrag', $this->betrag);
        $stmt->bindValue(':bezahlt_am', $this->bezahlt_am);
        $stmt->bindValue(':faellig_am', $this->faellig_am);

        $this->saveData($stmt, $db);
    }

    protected static function getInsertStmnt(): string
    {
        $table = self::getTable();
        return "
            INSERT INTO $table
                (vertrag_ID, zahlungsstatus, betrag, bezahlt_am, faellig_am, userID)
            VALUES
                (:vertrag_ID, :zahlungsstatus, :betrag, :bezahlt_am, :faellig_am, :Benutzer)
        ";
    }

    protected static function getUpdateStmnt(): string
    {
        $table = self::getTable();
        return "
            UPDATE $table SET
                vertrag_ID      = :vertrag_ID,
                zahlungsstatus  = :zahlungsstatus,
                betrag          = :betrag,
                bezahlt_am      = :bezahlt_am,
                faellig_am      = :faellig_am,
                userID          = :Benutzer,
                active          = :Active
            WHERE ID = :ID
        ";
    }

    public static function findByIdEntry(PDO $db, int $id): ?self
    {
        $table = self::getTable();
        $stmt = $db->prepare("SELECT * FROM $table WHERE ID = :id");
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return self::mapRow($row);
    }

    public static function findAllEntries(PDO $db, ?DbFilter $filter = null): array
    {
        $filter ??= new DbFilter();
        $c = $filter->compile();

        $table = self::getTable();
        $sql = "SELECT * FROM $table{$c['whereSql']}{$c['orderSql']}{$c['limitSql']}";

        $stmt = $db->prepare($sql);
        $stmt->execute($c['params']);

        $result = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $obj = self::mapRow($row);
            $result[$obj->id] = $obj;
        }

        return $result;
    }

    private static function mapRow(array $row): self
    {
        return new self(
            (int)$row['vertrag_ID'],
            PaymentStatus::from((int)$row['zahlungsstatus']),
            (float)$row['betrag'],
            $row['bezahlt_am'],
            $row['faellig_am'],
            (int)$row['ID'],
            (bool)$row['active'],
            $row['updated_at'],
            $row['created_at'],
            $row['userID']
        );
    }


    public function getVertrag(): Vertrag|int
    {
        return $this->vertrag;
    }

    public function getZahlungsstatus(): PaymentStatus|int
    {
        return $this->zahlungsstatus;
    }

    public function toArray(): array
    {
        return [
            'ID' => $this->id,
            'vertrag_ID' => $this->getVertrag(),
            'zahlungsstatus' => $this->getZahlungsstatus(),
            'betrag' => $this->betrag,
            'bezahlt_am' => $this->bezahlt_am,
            'faellig_am' => $this->faellig_am,
            'active' => $this->active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
