<?php

namespace App\Models;

use App\Enums\Availability;
use App\Enums\BoatTyoe;
use App\Filters\DbFilter;
use PDO;

class Boot extends DatabaseEntry
{
    private float $laenge;
    private float $breite;
    private float $tiefgang;
    private string $beschreibung;
    private int $kapazitaet;
    private BoatTyoe|int $bootstyp;
    private float $preis_pro_tag;
    private float $kaution;
    private Availability|int $verfuegbarkeit;
    private array $features;

    public function __construct(
        float $laenge,
        float $breite,
        float $tiefgang,
        string $beschreibung,
        int $kapazitaet,
        BoatTyoe|int $bootstyp,
        float $preis_pro_tag,
        float $kaution,
        Availability|int $verfuegbarkeit,
        ?int $id = null,
        ?bool $active = true,
        string|null $updated_at = null,
        string|null $created = null,
        User|int|null $user = null,
    ) {
        parent::__construct($id, $active, $updated_at, $created, $user);
        $this->laenge = $laenge;
        $this->breite = $breite;
        $this->tiefgang = $tiefgang;
        $this->beschreibung = $beschreibung;
        $this->kapazitaet = $kapazitaet;
        $this->bootstyp = $bootstyp;
        $this->preis_pro_tag = $preis_pro_tag;
        $this->kaution = $kaution;
        $this->verfuegbarkeit = $verfuegbarkeit;
    }

    public static function getTable(): string
    {
        return 'boote';
    }

    public function saveEntry(PDO $db): void
    {
        $sqlString = empty($this->id) ? self::getInsertStmnt(): self::getUpdateStmnt();
        $stmt = $db->prepare($sqlString);
        $stmt->bindValue(":laenge", $this->getLaenge());
        $stmt->bindValue(":breite", $this->getBreite());
        $stmt->bindValue(":tiefgang", $this->getTiefgang());
        $stmt->bindValue(":beschreibung", $this->getBeschreibung());
        $stmt->bindValue(":kapazitaet", $this->getKapazitaet());
        $stmt->bindValue(":bootstyp", $this->getBootstyp());
        $stmt->bindValue(":verfuegbarkeit", $this->getVerfuegbarkeit());
        $stmt->bindValue(":preis_pro_tag", $this->getPreis_pro_tag());
        $stmt->bindValue(":kaution", $this->getKaution());
        $this->saveData($stmt, $db);
        $this->saveBootFeatureRelations($db);
    }

    public function saveBootFeatureRelations(PDO $db) : void
    {
        //ToDo: to implement
    }

    protected static function getInsertStmnt() : string
    {
        $table = self::getTable();
        return "INSERT INTO $table (laenge, breite, tiefgang, beschreibung, kapazitaet, bootstyp, verfuegbarkeit, preis_pro_tag, userID)
                 VALUES (:laenge, :breite, :tiefgang, :beschreibung, :kapazitaet, :bootstyp, :verfuegbarkeit, :preis_pro_tag, :Benutzer)";
    }

    protected static function getUpdateStmnt() : string
    {
        $table = self::getTable();
        return "UPDATE $table
                 SET laenge=:laenge, breite=:breite, tiefgang=:tiefgang, beschreibung=:beschreibung, kapazitaet=:kapazitaet, bootstyp=:bootstyp, verfuegbarkeit=:verfuegbarkeit, preis_pro_tag=:preis_pro_tag, userID=:Benutzer, active=:Active
                 WHERE ID = :ID";
    }

    public static function findByIdEntry(PDO $db, int $id): array|null
    {
        $table = self::getTable();

        $stmt = $db->prepare("SELECT * FROM $table WHERE ID = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return null;

        $boot = new Boot(
            $row['laenge'],
            $row['breite'],
            $row['tiefgang'],
            $row['beschreibung'],
            $row['kapazitaet'],
            $row['bootstyp'],
            $row['preis_pro_tag'],
            $row['kaution'],
            $row['verfuegbarkeit'],
            $row['ID'],
            (bool)$row['active'],
            $row['updated_at'],
            $row['created_at'],
            $row['userID'],
        );

        $features = self::selectFeatureIDs($db, [$boot->getID()]);

        $boot->setFeatures($features[$boot->getID()] ?? []);

        return $boot->toArray();
    }

    private static function selectFeatureIDs(PDO $db, array $bootIDs): array
    {
        $bootIDs = array_values(array_unique(array_map('intval', $bootIDs)));
        if (!$bootIDs) return [];

        $placeholders = implode(',', array_fill(0, count($bootIDs), '?'));
        $stmt = $db->prepare(
            "SELECT boot_ID, feature_ID
         FROM boot_hat_feature
         WHERE boot_ID IN ($placeholders)"
        );
        $stmt->execute($bootIDs);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $map = [];
        foreach ($rows as $r) {
            $bid = (int)$r['boot_ID'];
            $fid = (int)$r['feature_ID'];
            $map[$bid][] = $fid;
        }

        foreach ($map as $bid => $fids) {
            $map[$bid] = array_values(array_unique($fids));
        }

        return $map;
    }


    public static function findAllEntries(PDO $db, ?DbFilter $filter = null): array
    {
        $table = self::getTable();

        $stmt = $db->query("SELECT * FROM $table");

        $bootsById = [];   // id => Boot
        $bootIDs = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $boot = new Boot(
                $row['laenge'],
                $row['breite'],
                $row['tiefgang'],
                $row['beschreibung'],
                $row['kapazitaet'],
                $row['bootstyp'],
                $row['preis_pro_tag'],
                $row['kaution'],
                $row['verfuegbarkeit'],
                (int)$row['ID'],
                (bool)$row['active'],
                $row['updated_at'],
                $row['created_at'],
                $row['userID'],
            );

            $id = $boot->getID();
            $bootsById[$id] = $boot;
            $bootIDs[] = $id;
        }

        if (!$bootIDs) return [];

        $featureMap = self::selectFeatureIDs($db, $bootIDs);

        $res = [];
        foreach ($bootsById as $id => $boot) {
            $boot->setFeatures($featureMap[$id] ?? []);
            $res[$id] = $boot->toArray();
        }

        return $res;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'length' => $this->laenge,
            'width' => $this->breite,
            'depth' => $this->tiefgang,
            'name' => $this->beschreibung,
            'capacity' => $this->kapazitaet,
            'type' => $this->bootstyp,
            'pricePerDay' => $this->preis_pro_tag,
            'deposit' => $this->kaution,
            'availability' => $this->verfuegbarkeit,
            'features' => $this->features,
            'active' => $this->active,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }

    public function getLaenge(): float
    {
        return $this->laenge;
    }

    public function setLaenge(float $laenge): void
    {
        $this->laenge = $laenge;
    }

    public function getBreite(): float
    {
        return $this->breite;
    }

    public function setBreite(float $breite): void
    {
        $this->breite = $breite;
    }

    public function getTiefgang(): float
    {
        return $this->tiefgang;
    }

    public function setTiefgang(float $tiefgang): void
    {
        $this->tiefgang = $tiefgang;
    }

    public function getBeschreibung(): string
    {
        return $this->beschreibung;
    }

    public function setBeschreibung(string $beschreibung): void
    {
        $this->beschreibung = $beschreibung;
    }

    public function getKapazitaet(): int
    {
        return $this->kapazitaet;
    }

    public function setKapazitaet(int $kapazitaet): void
    {
        $this->kapazitaet = $kapazitaet;
    }

    public function getBootstyp(): BoatTyoe|int
    {
        return $this->bootstyp;
    }

    public function setBootstyp(BoatTyoe|int $bootstyp): void
    {
        $this->bootstyp = $bootstyp;
    }

    public function getVerfuegbarkeit(): Availability|int
    {
        return $this->verfuegbarkeit;
    }

    public function setVerfuegbarkeit(Availability|int $verfuegbarkeit): void
    {
        $this->verfuegbarkeit = $verfuegbarkeit;
    }

    public function getPreis_pro_tag(): float
    {
        return $this->preis_pro_tag;
    }

    public function setPreis_pro_tag(float $preis_pro_tag): void
    {
        $this->preis_pro_tag = $preis_pro_tag;
    }

    public function getKaution(): float
    {
        return $this->kaution;
    }

    public function setKaution(float $kaution): void
    {
        $this->kaution = $kaution;
    }

    public function getFeatures(): array
    {
        return $this->features;
    }

    public function setFeatures(array $features): void
    {
        $this->features = $features;
    }
}