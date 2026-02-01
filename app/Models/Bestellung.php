<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Filters\DbFilter;
use PDO;

class Bestellung extends DatabaseEntry
{
    private Kunde|int $kunde;
    private OrderStatus|int $bestellstatus;

    private BootMiete|null $bootMiete = null;
    private LiegeplatzReservierung|null $liegeplatzReservierung = null;
    private Vertrag|int|null $vertrag = null;

    public function __construct(
        Kunde|int $kunde,
        OrderStatus|int $bestellstatus,
        ?int $id = null,
        ?bool $active = true,
        ?string $updated_at = null,
        ?string $created_at = null,
        User|int|null $user = null
    ) {
        parent::__construct($id, $active, $updated_at, $created_at, $user);
        $this->kunde = $kunde;
        $this->bestellstatus = $bestellstatus;
    }

    public static function getTable(): string
    {
        return 'bestellungen';
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

    public static function findAllEntries(PDO $db, ?DbFilter $filter = null): array
    {
        $table = self::getTable();

        $filter ??= new DbFilter();
        $c = $filter->compile();

        $sql = "SELECT * FROM $table" . $c['whereSql'];
        $stmt = $db->prepare($sql);
        $stmt->execute($c['params']);

        /** @var Bestellung[] $bestellungen */
        $bestellungen = [];
        $bestellungIDs = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $bestellung = new Bestellung(
                (int)$row['kunde_ID'],
                (int)$row['bestellstatus'],
                (int)$row['ID'],
                (bool)$row['active'],
                $row['updated_at'],
                $row['created_at'],
                $row['userID'],
            );

            $id = $bestellung->getID();
            $bestellungen[$id] = $bestellung;
            $bestellungIDs[] = $id;
        }

        if (!$bestellungIDs) {
            return [];
        }

        $bootMieten = BootMiete::findAllEntries(
            $db,
            (new DbFilter())->whereIn('bestellung_ID', $bestellungIDs)
        );

        foreach ($bootMieten as $bootMiete) {
            $bid = $bootMiete->getBestellung();
            if (isset($bestellungen[$bid])) {
                $bestellungen[$bid]->setBootMiete($bootMiete);
            }
        }

        $liegeplaetze = LiegeplatzReservierung::findAllEntries(
            $db,
            (new DbFilter())->whereIn('bestellung_ID', $bestellungIDs)
        );

        foreach ($liegeplaetze as $lp) {
            $bid = $lp->getBestellung();
            if (isset($bestellungen[$bid])) {
                $bestellungen[$bid]->setLiegeplatzReservierung($lp);
            }
        }

        $vertraege = Vertrag::findAllEntries(
            $db,
            (new DbFilter())->whereIn('bestellung_ID', $bestellungIDs)
        );

        foreach ($vertraege as $vertrag) {
            $bid = $vertrag->getBestellung();
            if (isset($bestellungen[$bid])) {
                $bestellungen[$bid]->setVertrag($vertrag);
            }
        }

        return $bestellungen;
    }


    public function toArray(): array
    {
        // TODO: Implement toArray() method.
        return [];
    }

    public function setVertrag(Vertrag|int|null $vertrag) : void
    {
        $this->vertrag = $vertrag;
    }

    public function setBootMiete(BootMiete|null $bootMiete) : void
    {
        $this->bootMiete = $bootMiete;
    }

    public function setLiegeplatzReservierung(LiegeplatzReservierung|null $liegeplatzReservierung) : void
    {
        $this->liegeplatzReservierung = $liegeplatzReservierung;
    }

    public function getKunde(): Kunde|int
    {
        return $this->kunde;
    }
}
