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
    private Zahlung|int|null $zahlung;

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

    public static function findByIdEntry(PDO $db, int $id): self|null
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

        $bestellID = $bestellung->getID();

        $rel= self::selectRelItemIDs($db, [$bestellID])[$bestellID];

        if (isset($result[$bestellID])) {
            $bestellung->setBoote($rel['boot']);
            $bestellung->setLiegeplatze($rel['liegeplatz']);
        }

        $zahlungMap = self::selectZahlungsRel($db, [$bestellID]);
        $bestellung->setZahlung($zahlungMap[$bestellID] ?? null);

        return $bestellung;
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
        $stmt->execute($c['params']);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /** @var Bestellung[] $map */
        $map = [];
        $ids = [];

        foreach ($rows as $row) {
            $id = (int)$row['ID'];
            $ids[] = $id;

            $map[$id] = new Bestellung(
                (int)$row['kunde_ID'],
                $row['bestellstatus'],
                $id,
                (bool)$row['active'],
                $row['updated_at'],
                $row['created_at'],
                $row['userID'],
            );
        }

        if ($ids) {
            $rels = self::selectRelItemIDs($db, $ids);

            foreach ($rels as $bestellID => $rel) {
                if (isset($map[$bestellID])) {
                    $map[$bestellID]->setBoote($rel['boot']);
                    $map[$bestellID]->setLiegeplatze($rel['liegeplatz']);
                }
            }

            $zahlungen = self::selectZahlungsRel($db, $ids);
            foreach ($zahlungen as $bestellID => $zahlung) {
                if (isset($map[$bestellID])) {
                    $map[$bestellID]->setZahlung($zahlung);
                }
            }
        }

        return $map;
    }

    public static function selectZahlungsRel(
        PDO $db,
        array $bestellIDs,
        ?DbFilter $extraFilter = null
    ): array {
        if (!$bestellIDs) return [];

        $filter = $extraFilter ?? new DbFilter();
        $filter->whereIn('bestellung_ID', $bestellIDs);

        $zahlungen = Zahlung::findAllEntries($db, $filter);

        $res = []; // bestellID => Zahlung

        foreach ($zahlungen as $zahlung) {
            $bid = $zahlung->getBestellungID();
            if ($bid <= 0) continue;

            // falls DB kaputt ist und mehrere Zahlungen existieren:
            if (isset($res[$bid])) {
                // defensive Entscheidung
                // z. B. letzte gewinnt oder erste gewinnt
                // oder Exception werfen
                throw new \Exception("Mehrere Zahlungen für Bestellung {$bid} gefunden");
            }

            $res[$bid] = $zahlung;
        }

        return $res;
    }


    private static function selectRelItemIDs(PDO $db, array $bestellIDs): array
    {
        $bestellIDs = array_values(array_unique(array_map('intval', $bestellIDs)));
        if (!$bestellIDs) return [];

        $ph = implode(',', array_fill(0, count($bestellIDs), '?'));

        $sql = "
        SELECT bestellung_ID, boot_ID, NULL AS liegeplatz_ID
        FROM bestellung_boot
        WHERE bestellung_ID IN ($ph)

        UNION ALL

        SELECT bestellung_ID, NULL AS boot_ID, liegeplatz_ID
        FROM bestellung_liegeplatz
        WHERE bestellung_ID IN ($ph)
    ";

        $stmt = $db->prepare($sql);
        $stmt->execute([...$bestellIDs, ...$bestellIDs]);

        $map = [];
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $bid = (int)$r['bestellung_ID'];

            if (!isset($map[$bid])) {
                $map[$bid] = [
                    'boote' => [],
                    'liegeplaetze' => []
                ];
            }

            if ($r['boot_ID'] !== null) {
                $map[$bid]['boote'][] = (int)$r['boot_ID'];
            }
            if ($r['liegeplatz_ID'] !== null) {
                $map[$bid]['liegeplaetze'][] = (int)$r['liegeplatz_ID'];
            }
        }

        // unique & reindex
        foreach ($map as &$entry) {
            $entry['boote'] = array_values(array_unique($entry['boote']));
            $entry['liegeplaetze'] = array_values(array_unique($entry['liegeplaetze']));
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

    public function getZahlung(): Zahlung|int|null
    {
        return $this->zahlung;
    }

    public function setZahlung(Zahlung|int|null $zahlung): void
    {
        $this->zahlung = $zahlung;
    }
}