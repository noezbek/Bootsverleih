<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Filters\DbFilter;
use DateTime;
use PDO;

class Zahlung extends DatabaseEntry
{
    private Vertrag|int $vertrag;
    private PaymentStatus|int $zahlungsstatus;
    private float $betrag;
    private ?string $faelligAm;
    private string|null $bezahltAm;

    public function __construct(
        int               $vertrag,
        PaymentStatus|int $zahlungsstatus,
        float             $betrag,
        ?string           $faelligAm,
        ?string           $bezahltAm = null,
        ?int              $id = null,
        bool              $active = true,
        ?string           $updated_at = null,
        ?string           $created_at = null,
        ?int              $userID = null
    )
    {
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

    public static function calculateFaelligAm(): string
    {
        $faelligAm = new DateTime();        // heute
        $faelligAm->modify('+30 days');
        return $faelligAm->format('Y-m-d');
    }

    public function getVertrag(): Vertrag|int
    {
        return $this->vertrag;
    }

    public function getZahlungsstatus(): PaymentStatus|int
    {
        return $this->zahlungsstatus;
    }

    public function getBetrag(): float
    {
        return $this->betrag;
    }

    public function getBezahltAm(): string|null
    {
        return $this->bezahltAm;
    }

    public function getFaelligAm(): string
    {
        return $this->faelligAm;
    }

    protected static function getInsertStmnt() : string
    {
        $table = self::getTable();
        return "INSERT INTO $table (vertrag_ID, zahlungsstatus, betrag, bezahlt_am, faellig_am, userID)
                 VALUES (:vertrag_ID, :zahlungsstatus, :betrag, :bezahlt_am, :faellig_am, :Benutzer)";
    }

    protected static function getUpdateStmnt() : string
    {
        $table = self::getTable();
        return "UPDATE $table
                 SET vertrag_ID=:vertrag_ID, zahlungsstatus=:zahlungsstatus, betrag=:betrag, bezahlt_am=:bezahlt_am, faellig_am=:faellig_am, userID=:Benutzer, active=:Active
                 WHERE ID = :ID";
    }

    public function saveEntry(PDO $db): void
    {
        $sqlString = empty($this->id) ? self::getInsertStmnt(): self::getUpdateStmnt();
        $stmt = $db->prepare($sqlString);
        $stmt->bindValue(":vertrag_ID", $this->getVertrag());
        $stmt->bindValue(":zahlungsstatus", $this->getZahlungsstatus());
        $stmt->bindValue(":betrag", $this->getBetrag());
        $stmt->bindValue(":bezahlt_am", $this->getBezahltAm());
        $stmt->bindValue(":faellig_am", $this->getFaelligAm());
        $this->saveData($stmt, $db);
    }

    public static function findByIdEntry(PDO $db, int $id): self|null
    {
        $table = self::getTable();

        $stmt = $db->prepare("SELECT * FROM $table WHERE ID = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return null;

        return new Zahlung(
            $row['vertrag_ID'],
            $row['zahlungsstatus'],
            $row['betrag'],
            $row['bezahlt_am'],
            $row['faellig_am'],
            $row['ID'],
            (bool)$row['active'],
            $row['updated_at'],
            $row['created_at'],
            $row['userID'],
        );
    }

    public static function findByKunde(PDO $db, int $kunde): array
    {
        $stmt = $db->prepare("SELECT
      z.*,
    z.ID            AS zahlung_ID,
    v.ID            AS vertrag_ID,
    b.ID            AS bestellung_ID,
    k.ID            AS kunde_ID
FROM zahlungen z
JOIN vertraege v        ON z.vertrag_ID = v.ID
JOIN bestellungen b   ON v.bestellung_ID = b.ID
JOIN kunde k          ON b.kunde_ID = k.ID
WHERE k.ID = :kundeId
  AND z.active = 1;
");
        $stmt->execute([':kundeId' => $kunde]);

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
