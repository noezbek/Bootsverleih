<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentRhythm;
use App\Filters\DbFilter;
use PDO;

class Vertrag extends DatabaseEntry
{
    private int $bestellung;
    private PaymentRhythm|int $zahlungsrhythmus;
    private PaymentMethod|int $zahlungsmethode;
    private ?string $vertragsbeginn;
    private ?string $gekuendigtAm;

    public function __construct(
        int $bestellung,
        PaymentRhythm|int $zahlungsrhythmus,
        PaymentMethod|int $zahlungsmethode,
        ?string $vertragsbeginn = null,
        ?string $gekuendigtAm= null,
        ?int $id = null,
        bool $active = true,
        ?string $updated_at = null,
        ?string $created_at = null,
        ?int $userID = null
    ) {
        parent::__construct($id, $active, $updated_at, $created_at, $userID);
        $this->bestellung = $bestellung;
        $this->zahlungsrhythmus = $zahlungsrhythmus;
        $this->zahlungsmethode = $zahlungsmethode;
        $this->vertragsbeginn = $vertragsbeginn;
        $this->gekuendigtAm = $gekuendigtAm;
    }

    public static function getTable(): string
    {
        return 'vertraege';
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
                (int)$row['bestellung_ID'],
                (int)$row['zahlungsrhythmus'],
                (int)$row['zahlungsmethode'],
                $row['vertragsbeginn'],
                $row['gekuendigt_am'],
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

    public function getBestellung(): int
    {
        return $this->bestellung;
    }

    protected static function getInsertStmnt(): string
    {
        $table = self::getTable();

        return "
            INSERT INTO $table
            (bestellung_ID, vertragsbeginn, zahlungsrhythmus, zahlungsmethode, gekuendigt_am, userID)
            VALUES
            (:bestellung_ID, NOW(), :zahlungsrhythmus, :zahlungsmethode, :gekuendigt_am, :Benutzer)
        ";
    }

    protected static function getUpdateStmnt(): string
    {
        $table = self::getTable();

        return "
            UPDATE $table SET
                bestellung_ID = :bestellung_ID,
                zahlungsrhythmus = :zahlungsrhythmus,
                zahlungsmethode = :zahlungsmethode,
                gekuendigt_am = :gekuendigt_am,
                userID = :Benutzer,
                active = :Active
            WHERE ID = :ID
        ";
    }

    public function saveEntry(PDO $db): void
    {
        $sql = $this->getID() ? self::getUpdateStmnt() : self::getInsertStmnt();

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':bestellung_ID', $this->bestellung);
        $stmt->bindValue(':zahlungsrhythmus', $this->zahlungsrhythmus);
        $stmt->bindValue(':zahlungsmethode', $this->zahlungsmethode);
        $stmt->bindValue(':gekuendigt_am', $this->gekuendigtAm ?? null);

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
            'zahlungsrhythmus' => $this->zahlungsrhythmus,
            'zahlungsmethode' => $this->zahlungsmethode,
            'vertragsbeginn' => $this->vertragsbeginn,
            'gekuendigtAm' => $this->gekuendigtAm,
            'active' => $this->active,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
