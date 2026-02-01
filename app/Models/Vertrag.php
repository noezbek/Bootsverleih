<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\PaymentRhythm;
use App\Enums\PaymentMethod;
use App\Filters\DbFilter;
use PDO;

class Vertrag extends DatabaseEntry
{
    private Bestellung|int $bestellung;
    private PaymentRhythm|int $zahlungsrhythmus;
    private PaymentMethod|int $zahlungsmethode;
    private string $vertragsbeginn;
    private ?string $gekuendigt_am;
    private array $zahlungen;

    public function __construct(
        Bestellung|int    $bestellung,
        PaymentRhythm|int $zahlungsrhythmus,
        PaymentMethod|int $zahlungsmethode,
        string            $vertragsbeginn,
        ?string           $gekuendigt_am = null,
        ?int              $id = null,
        bool              $active = true,
        ?string           $updated_at = null,
        ?string           $created_at = null,
        User|int|null     $user = null,
    ) {
        parent::__construct($id, $active, $updated_at, $created_at, $user);
        $this->bestellung = $bestellung;
        $this->zahlungsrhythmus = $zahlungsrhythmus;
        $this->zahlungsmethode = $zahlungsmethode;
        $this->vertragsbeginn = $vertragsbeginn;
        $this->gekuendigt_am = $gekuendigt_am;
    }

    public static function getTable(): string
    {
        return 'vertraege';
    }

    public function saveEntry(PDO $db): void
    {
        $sql = $this->id === null
            ? self::getInsertStmnt()
            : self::getUpdateStmnt();

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':bestellung_ID', $this->getBestellung(), PDO::PARAM_INT);
        $stmt->bindValue(':zahlungsrhythmus', $this->getZahlungsrhythmus(), PDO::PARAM_INT);
        $stmt->bindValue(':zahlungsmethode', $this->getZahlungsmethode(), PDO::PARAM_INT);
        $stmt->bindValue(':vertragsbeginn', $this->vertragsbeginn);
        $stmt->bindValue(':gekuendigt_am', $this->gekuendigt_am);

        $this->saveData($stmt, $db);
    }

    protected static function getInsertStmnt(): string
    {
        $table = self::getTable();
        return "
            INSERT INTO $table
                (bestellung_ID, zahlungsrhythmus, zahlungsmethode, vertragsbeginn, gekuendigt_am, userID)
            VALUES
                (:bestellung_ID, :zahlungsrhythmus, :zahlungsmethode, :vertragsbeginn, :gekuendigt_am, :Benutzer)
        ";
    }

    protected static function getUpdateStmnt(): string
    {
        $table = self::getTable();
        return "
            UPDATE $table SET
                bestellung_ID   = :bestellung_ID,
                zahlungsrhythmus= :zahlungsrhythmus,
                zahlungsmethode = :zahlungsmethode,
                vertragsbeginn  = :vertragsbeginn,
                gekuendigt_am   = :gekuendigt_am,
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
        $table = self::getTable();

        $filter ??= new DbFilter();
        $c = $filter->compile();

        $sql = "SELECT * FROM $table"
            . $c['whereSql']
            . $c['orderSql']
            . $c['limitSql'];

        $stmt = $db->prepare($sql);

        $vertragsById = [];   // id => Boot
        $vertragIDs = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $vertrag = new Vertrag(
                $row['bestellung_ID'],
                $row['zahlungsrhythmus'],
                $row['zahlungsmethode'],
                $row['vertragsbeginn'],
                $row['gekuendigt_am'],
                (int)$row['ID'],
                (bool)$row['active'],
                $row['updated_at'],
                $row['created_at'],
                $row['userID'],
            );

            $id = $vertrag->getID();
            $vertragsById[$id] = $vertrag;
            $vertragIDs[] = $id;
        }

        if (!$vertragIDs) return [];

        $zahlungenByVertrag = [];

        $zahlungen = Zahlung::findAllEntries(
            $db,
            (new DbFilter())->whereIn('vertrag_ID', $vertragIDs)
        );

        foreach ($zahlungen as $zahlung) {
            $vid = $zahlung->getVertragId();
            $zahlungenByVertrag[$vid][] = $zahlung;
        }

        foreach ($vertragsById as $vid => $vertrag) {
            $vertrag->setZahlungen($zahlungenByVertrag[$vid] ?? []);
        }

        return $vertragsById;
    }

    private static function mapRow(array $row): self
    {
        return new self(
            (int)$row['bestellung_ID'],
            PaymentRhythm::from((int)$row['zahlungsrhythmus']),
            PaymentMethod::from((int)$row['zahlungsmethode']),
            $row['vertragsbeginn'],
            $row['gekuendigt_am'],
            (int)$row['ID'],
            (bool)$row['active'],
            $row['updated_at'],
            $row['created_at'],
            $row['userID']
        );
    }

    public function kuendigen(?string $datum = null): void
    {
        $this->active = false;
        $this->gekuendigt_am = $datum ?? date('Y-m-d');
    }


    public function createErsteZahlung(PDO $db, float $betrag): Zahlung
    {
        // Fälligkeitsdatum = Vertragsbeginn
        $faelligAm = $this->vertragsbeginn;

        $zahlung = new Zahlung(
            $this->getID() ?? throw new \LogicException('Vertrag muss gespeichert sein'),
            PaymentStatus::AUSSTEHEND,
            $betrag,
            null,              // bezahlt_am
            $faelligAm,
            null,              // Zahlung-ID
            true,
            null,
            null,
            $this->user
        );

        $zahlung->saveEntry($db);

        return $zahlung;
    }

    public function getBestellung(): Bestellung|int
    {
        return $this->bestellung;
    }

    public function getZahlungsrhythmus(): PaymentRhythm|int
    {
        return $this->zahlungsrhythmus;
    }

    public function getZahlungsmethode(): PaymentMethod|int
    {
        return $this->zahlungsmethode;
    }

    public function setZahlungen(array $zahlungen): void
    {
        $this->zahlungen = $zahlungen;
    }

    public function toArray(): array
    {
        return [
            'ID' => $this->id,
            'bestellung_ID' => $this->getBestellung(),
            'zahlungsrhythmus' => $this->getZahlungsrhythmus(),
            'zahlungsmethode' => $this->getZahlungsmethode(),
            'vertragsbeginn' => $this->vertragsbeginn,
            'gekuendigt_am' => $this->gekuendigt_am,
            'active' => $this->active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
