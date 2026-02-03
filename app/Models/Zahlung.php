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
    private ?string $faelligAm;
    private ?string $bezahltAm;

    public function __construct(
        int $vertrag,
        PaymentStatus|int $zahlungsstatus,
        float $betrag,
        ?string $faelligAm,
        ?string $bezahltAm = null,
        ?int $id = null,
        bool $active = true,
        ?string $updated_at = null,
        ?string $created_at = null,
        ?int $userID = null
    ) {
        parent::__construct($id, $active, $updated_at, $created_at, $userID);
        $this->vertrag = $vertrag;
        $this->zahlungsstatus = $zahlungsstatus;
        $this->betrag = $betrag;
        $this->faelligAm = $faelligAm;
        $this->bezahltAm = $bezahltAm;
    }

    public static function getTable(): string
    {
        return 'zahlungen';
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
                (int)$row['vertrag_ID'],
                (int)$row['zahlungsstatus'],
                (float)$row['betrag'],
                $row['faellig_am'],
                $row['bezahlt_am'],
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

    public function getVertrag(): Vertrag|int
    {
        return $this->vertrag;
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
            'vertrag' => $this->vertrag,
            'zahlungsstatus' => $this->zahlungsstatus,
            'betrag' => $this->betrag,
            'faelligAm' => $this->faelligAm,
            'bezahltAm' => $this->bezahltAm,
            'active' => $this->active,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
