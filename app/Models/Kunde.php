<?php

namespace App\Models;

use App\Filters\DbFilter;
use PDO;

class Kunde extends Person
{

    private array $bestellungen = [];
    private array $zahlungen = [];
    private array $vertraege = [];


    public function __construct(
        string $vorname,
        string $nachname,
        string $email,
        string $geburtsdatum,
        string $telefon,
        string|null $strasse,
        int|null $plz,
        string|null $stadt,
        ?int $id = null,
        ?bool $active = true,
        string|null $updated_at = null,
        string|null $created = null,
        User|int|null $user = null,
    ) {
        parent::__construct($vorname, $nachname, $email, $geburtsdatum, $telefon, $strasse, $plz, $stadt, $id, $active, $updated_at, $created, $user);
    }

    public static function getTable(): string
    {
        return 'kunde';
    }

    public function saveEntry(PDO $db): void
    {
        $sqlString = empty($this->id) ? self::getInsertStmnt(): self::getUpdateStmnt();
        $stmt = $db->prepare($sqlString);
        $this->savePerson($stmt, $db);
    }

    protected static function getInsertStmnt() : string
    {
        $table = self::getTable();
        return "INSERT INTO $table (vorname, nachname, email, geburtsdatum, telefon, strasse, plz, stadt, userID)
                 VALUES (:vorname, :nachname, :email, :geburtsdatum, :telefon, :strasse, :plz, :stadt, :Benutzer)";
    }

    protected static function getUpdateStmnt() : string
    {
        $table = self::getTable();
        return "UPDATE $table
                 SET vorname=:vorname, nachname=:nachname, email=:email, geburtsdatum=:geburtsdatum, telefon=:telefon, strasse=:strasse, plz=:plz, stadt=:stadt, userID=:Benutzer, active=:Active
                 WHERE ID = :ID";
    }

    public static function findByIdEntry(PDO $db, int $id): self|null
    {
        $table = self::getTable();

        $stmt = $db->prepare("SELECT
    k.*,
    GROUP_CONCAT(DISTINCT b.ID) AS bestellungen,
    GROUP_CONCAT(DISTINCT v.ID) AS vertraege,
    GROUP_CONCAT(DISTINCT z.ID) AS zahlungen
FROM $table k
LEFT JOIN bestellungen b ON b.kunde_ID = k.ID
LEFT JOIN vertraege v    ON v.bestellung_ID = b.ID
LEFT JOIN zahlungen z    ON z.vertrag_ID = v.ID
WHERE k.ID = :id
GROUP BY k.ID
LIMIT 1;
");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        $kunde = new Kunde(
            $row['vorname'],
            $row['nachname'],
            $row['email'],
            $row['geburtsdatum'],
            $row['telefon'],
            $row['strasse'],
            $row['plz'],
            $row['stadt'],
            $row['ID'],
            (bool)$row['active'],
        );

        return $kunde;
    }


    public static function findAllEntries(PDO $db, ?DbFilter $filter = null): array
    {
        $table = self::getTable();

        $filter ??= new DbFilter();
        $c = $filter->compile();

        $sql = "
    SELECT
        k.*,
        GROUP_CONCAT(DISTINCT b.ID) AS bestellungen,
        GROUP_CONCAT(DISTINCT v.ID) AS vertraege,
        GROUP_CONCAT(DISTINCT z.ID) AS zahlungen
    FROM $table k
    LEFT JOIN bestellungen b ON b.kunde_ID = k.ID
    LEFT JOIN vertraege v    ON v.bestellung_ID = b.ID
    LEFT JOIN zahlungen z    ON z.vertrag_ID = v.ID
"
            . $c['whereSql']
            . " GROUP BY k.ID "
            . $c['orderSql']
            . $c['limitSql'];


        $stmt = $db->prepare($sql);

        $kundenById = [];   // id => Kunde

        $stmt->execute($c['params']);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $kunde = new Kunde(
                $row['vorname'],
                $row['nachname'],
                $row['email'],
                $row['geburtsdatum'],
                $row['telefon'],
                $row['strasse'],
                (int)$row['plz'],
                $row['stadt'],
                (int)$row['ID'],
                (bool)$row['active'],
            );

            $bestellungen = $row['bestellungen'] ? array_map('intval', explode(',', $row['bestellungen'])) : [];
            $zahlungen = $row['zahlungen'] ? array_map('intval', explode(',', $row['zahlungen'])) : [];
            $vertraege = $row['vertraege'] ? array_map('intval', explode(',', $row['vertraege'])) : [];

            $kunde->setBestellungen($bestellungen);
            $kunde->setZahlungen($zahlungen);
            $kunde->setVertraege($vertraege);

            $id = $kunde->getID();
            $kundenById[$id] = $kunde;
        }

        return $kundenById ?? [];
    }


    private static function selectBestellRel(PDO $db, array $kundeIDs): array
    {
        $filter = new DbFilter();
        $filter->whereIn('kunde_ID', $kundeIDs);

        $bestellungen = Bestellung::findAllEntries($db, $filter);

        $byKunde = [];      // kundeID => [bestellungen]

        foreach ($bestellungen as $b) {
            $kid = $b->getKunde();

            $byKunde[$kid][] = $b;
        }

        return $byKunde;
    }

    public function toArray(): array
    {
        return [
            ...self::toPersonArray(),
            'bestellungen' => $this->getBestellungen(),
            'zahlungen' => $this->getZahlungen(),
            'vertraege' => $this->getVertraege(),
        ];
    }

    public function getBestellungen(): array
    {
        return $this->bestellungen;
    }

    public function setBestellungen(array $bestellungen): void
    {
        $this->bestellungen = $bestellungen;
    }

    public function getVertraege(): array
    {
        return $this->vertraege;
    }

    public function setVertraege(array $vertraege): void
    {
        $this->vertraege = $vertraege;
    }

    public function getZahlungen(): array
    {
        return $this->zahlungen;
    }

    public function setZahlungen(array $zahlungen): void
    {
        $this->zahlungen = $zahlungen;
    }
}