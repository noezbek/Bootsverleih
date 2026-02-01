<?php

namespace App\Models;

use App\Filters\DbFilter;
use PDO;

class Liegeplatz extends DatabaseEntry
{
    private string $beschreibung;
    private ?string $bezeichnung;
    private ?float $preisProTag;
    private ?int $kapazitaet;

    private float $posX;
    private float $posY;
    private float $posW;
    private float $posH;

    public function __construct(
        string $beschreibung,
        ?string $bezeichnung = null,
        ?float $preisProTag = null,
        ?int $kapazitaet = null,
        float $posX = 0.0,
        float $posY = 0.0,
        float $posW = 0.0,
        float $posH = 0.0,
        ?int $id = null,
        ?bool $active = true,
        ?string $updated_at = null,
        ?string $created_at = null,
        User|int|null $user = null,
    ) {
        parent::__construct($id, $active, $updated_at, $created_at, $user);

        $this->beschreibung = $beschreibung;
        $this->bezeichnung = $bezeichnung;
        $this->preisProTag = $preisProTag;
        $this->kapazitaet = $kapazitaet;
        $this->posX = $posX;
        $this->posY = $posY;
        $this->posW = $posW;
        $this->posH = $posH;
    }

    public static function getTable(): string
    {
        return 'liegeplaetze';
    }


    public function saveEntry(PDO $db): void
    {
        $sql = $this->getID() ? self::getInsertStmnt() : self::getUpdateStmnt();

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':beschreibung', $this->beschreibung);
        $stmt->bindValue(':bezeichnung', $this->bezeichnung);
        $stmt->bindValue(':preis', $this->preisProTag);
        $stmt->bindValue(':kapazitaet', $this->kapazitaet);
        $stmt->bindValue(':pos_x', $this->posX);
        $stmt->bindValue(':pos_y', $this->posY);
        $stmt->bindValue(':pos_w', $this->posW);
        $stmt->bindValue(':pos_h', $this->posH);

        $this->saveData($stmt, $db);
    }

    protected static function getInsertStmnt(): string
    {
        $table = self::getTable();

        return "
            INSERT INTO $table
            (beschreibung, bezeichnung, preis_pro_tag, kapazitaet, pos_x, pos_y, pos_w, pos_h, userID)
            VALUES
            (:beschreibung, :bezeichnung, :preis, :kapazitaet, :pos_x, :pos_y, :pos_w, :pos_h, :Benutzer)
        ";
    }

    protected static function getUpdateStmnt(): string
    {
        $table = self::getTable();

        return "
            UPDATE $table SET
                beschreibung = :beschreibung,
                bezeichnung  = :bezeichnung,
                preis_pro_tag = :preis,
                kapazitaet = :kapazitaet,
                pos_x = :pos_x,
                pos_y = :pos_y,
                pos_w = :pos_w,
                pos_h = :pos_h,
                userID = :Benutzer,
                active = :Active
            WHERE ID = :ID
        ";
    }


    public static function findByIdEntry(PDO $db, int $id): ?self
    {
        $table = self::getTable();

        $stmt = $db->prepare("SELECT * FROM $table WHERE ID = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return self::fromRow($row);
    }

    public static function findAllEntries(PDO $db, ?DbFilter $filter = null): array
    {
        $table = self::getTable();
        $filter ??= new DbFilter();

        $c = $filter->compile();
        $sql = "SELECT * FROM $table" . $c['whereSql'] . $c['orderSql'] . $c['limitSql'];

        $stmt = $db->prepare($sql);
        $stmt->execute($c['params']);

        $map = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $obj = self::fromRow($row);
            $map[$obj->getID()] = $obj;
        }

        return $map;
    }

    private static function fromRow(array $row): self
    {
        return new self(
            $row['beschreibung'],
            $row['bezeichnung'],
            $row['preis_pro_tag'] !== null ? (float)$row['preis_pro_tag'] : null,
            $row['kapazitaet'] !== null ? (int)$row['kapazitaet'] : null,
            (float)$row['pos_x'],
            (float)$row['pos_y'],
            (float)$row['pos_w'],
            (float)$row['pos_h'],
            (int)$row['ID'],
            (bool)$row['active'],
            $row['updated_at'],
            $row['created_at'],
            $row['userID'],
        );
    }

    public function toArray(): array
    {
        return [
            'ID' => $this->id,
            'name' => $this->beschreibung,
            'bezeichnung' => $this->bezeichnung,
            'preisProTag' => $this->preisProTag,
            'kapazitaet' => $this->kapazitaet,
            'pos' => [
                'x' => $this->posX,
                'y' => $this->posY,
                'w' => $this->posW,
                'h' => $this->posH,
            ],
            'active' => $this->active,
        ];
    }

    public function getBeschreibung(): string
    {
        return $this->beschreibung;
    }

    public function setBeschreibung(string $beschreibung): void
    {
        $this->beschreibung = $beschreibung;
    }

    public function getBezeichnung(): ?string
    {
        return $this->bezeichnung;
    }

    public function setBezeichnung(?string $bezeichnung): void
    {
        $this->bezeichnung = $bezeichnung;
    }

    public function getPreisProTag(): ?float
    {
        return $this->preisProTag;
    }

    public function setPreisProTag(?float $preisProTag): void
    {
        $this->preisProTag = $preisProTag;
    }

    public function getKapazitaet(): ?int
    {
        return $this->kapazitaet;
    }

    public function setKapazitaet(?int $kapazitaet): void
    {
        $this->kapazitaet = $kapazitaet;
    }

    public function getPosX(): float
    {
        return $this->posX;
    }

    public function setPosX(float $posX): void
    {
        $this->posX = $posX;
    }

    public function getPosY(): float
    {
        return $this->posY;
    }

    public function setPosY(float $posY): void
    {
        $this->posY = $posY;
    }

    public function getPosW(): float
    {
        return $this->posW;
    }

    public function setPosW(float $posW): void
    {
        $this->posW = $posW;
    }

    public function getPosH(): float
    {
        return $this->posH;
    }

    public function setPosH(float $posH): void
    {
        $this->posH = $posH;
    }
}
