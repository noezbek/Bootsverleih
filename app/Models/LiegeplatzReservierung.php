<?php

namespace App\Models;

use App\Filters\DbFilter;
use PDO;

class LiegeplatzReservierung extends DatabaseEntry
{
    private Liegeplatz|int $liegeplatz;
    private int $boot;
    private int $bestellung;
    private string $startdatum;
    private string $enddatum;
    private float $preisProTag;
    private int $status;
    private string $expires_at;
    private string|null $confirmed_at;
    private string $confirm_token;

    public function __construct(
        int $liegeplatz,
        int $boot,
        int $bestellung,
        string $startdatum,
        string $enddatum,
        float $preisProTag,
        int $status,
        string $expires_at,
        string $confirm_token,
        ?int $id = null,
        bool $active = true,
        ?string $updated_at = null,
        ?string $created_at = null,
        ?int $userID = null
    ) {
        parent::__construct($id, $active, $updated_at, $created_at, $userID);
        $this->liegeplatz = $liegeplatz;
        $this->boot = $boot;
        $this->bestellung = $bestellung;
        $this->startdatum = $startdatum;
        $this->enddatum = $enddatum;
        $this->preisProTag = $preisProTag;
        $this->status = $status;
        $this->expires_at = $expires_at;
        $this->confirm_token = $confirm_token;
    }

    public static function getTable(): string
    {
        return 'liegeplatz_reservierungen';
    }

    public static function findAllEntries(PDO $db, ?DbFilter $filter = null): array
    {
//        $filter ??= new DbFilter();
//        $c = $filter->compile();

        $sql = "
    SELECT *
    FROM " . self::getTable() . "
    WHERE active = 1
      AND (
            status = 2
            OR (status = 1 AND expires_at > NOW())
      )
      " ;
//            . $c['whereSql'];

        $stmt = $db->prepare($sql);
        $stmt->execute(
//            $c['params']
        );

        $map = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new self(
                (int)$row['liegeplatz_ID'],
                (int)$row['boot_ID'],
                (int)$row['bestellung_ID'],
                $row['startdatum'],
                $row['enddatum'],
                (float)$row['preis_pro_tag'],
                $row['status'],
                $row['expires_at'],
                $row['confirm_token'],
                (int)$row['ID'],
                (bool)$row['active'],
                $row['updated_at'],
                $row['created_at'],
                $row['userID'],
            );
            $obj->setConfirmAt($row['confirmed_at'] ?? null);

            $map[$obj->getID()] = $obj;
        }

        return $map;
    }

    public function getBestellungId(): int
    {
        return $this->bestellung;
    }

    protected static function getInsertStmnt(): string
    {
        $table = self::getTable();

        return "
            INSERT INTO $table
            (bestellung_ID, liegeplatz_ID, boot_ID, startdatum, enddatum, preis_pro_tag, status, expires_at, confirmed_at, confirm_token, userID)
            VALUES
            (:bestellung_ID, :liegeplatz_ID, :boot_ID, :startdatum, :enddatum, :preis_pro_tag, :status, :expires_at, :confirmed_at, :confirm_token, :Benutzer)
        ";
    }

    protected static function getUpdateStmnt(): string
    {
        $table = self::getTable();

        return "
            UPDATE $table SET
                bestellung_ID = :bestellung_ID,
                liegeplatz_ID  = :liegeplatz_ID,
                boot_ID = :boot_ID,
                startdatum = :startdatum,
                enddatum = :enddatum,
                preis_pro_tag = :preis_pro_tag,
                status = :status,
                expires_at = :expires_at,
                confirmed_at = :confirmed_at,
                confirm_token = :confirm_token,
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
        $stmt->bindValue(':liegeplatz_ID', $this->liegeplatz);
        $stmt->bindValue(':boot_ID', $this->boot);
        $stmt->bindValue(':startdatum', $this->startdatum);
        $stmt->bindValue(':enddatum', $this->enddatum);
        $stmt->bindValue(':preis_pro_tag', $this->preisProTag);
        $stmt->bindValue(':status', $this->status);
        $stmt->bindValue(':expires_at', $this->expires_at);
        $stmt->bindValue(':confirmed_at', $this->confirmed_at ?? null);
        $stmt->bindValue(':confirm_token', $this->confirm_token);
        $this->saveData($stmt, $db);
    }

    public static function findByIdEntry(PDO $db, int $id): self|null
    {
        // TODO: Implement findByIdEntry() method.
        return null;
    }

    public static function findByToken(PDO $db, string $token): ?self
    {
        $sql = "
        SELECT *
        FROM " . self::getTable() . "
        WHERE confirm_token = :token
          AND active = 1
          AND (
                status = 2
                OR (status = 1 AND expires_at > NOW())
          )
        LIMIT 1
    ";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            'token' => $token,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $res = new self(
            (int)$row['liegeplatz_ID'],
            (int)$row['boot_ID'],
            (int)$row['bestellung_ID'],
            $row['startdatum'],
            $row['enddatum'],
            (float)$row['preis_pro_tag'],
            (int)$row['ID'],
            (bool)$row['active'],
            $row['updated_at'],
            $row['created_at'],
            $row['userID'],
        );

        $res->setConfirmAt($row['confirmed_at']);
        $res->setExpiresAt($row['expires_at']);
        $res->setConfirmToken($row['confirm_token']);
        $res->setStatus($row['status']);

        return $res;
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'bestellung' => $this->bestellung,
            'liegeplatz' => $this->liegeplatz,
            'boot' => $this->boot,
            'startdatum' => $this->startdatum,
            'enddatum' => $this->enddatum,
            'preisProTag' => $this->preisProTag,
            'active' => $this->active,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }

    public function setLiegeplatz(Liegeplatz|int $liegeplatz) : void
    {
        $this->liegeplatz = $liegeplatz;
    }

    public function getLiegeplatz() : Liegeplatz|int
    {
        return $this->liegeplatz;
    }

    public function setConfirmToken(string $confirm_token) : void
    {
        $this->confirm_token = $confirm_token;
    }

    public function getConfirmToken() : string
    {
        return $this->confirm_token;
    }

    public function setExpiresAt(string $expires_at) :void
    {
        $this->expires_at = $expires_at;
    }

    public function getExpiresAt() :string
    {
        return $this->expires_at;
    }

    public function setConfirmAt(string|null $confirmed_at) :void
    {
        $this->confirmed_at = $confirmed_at;
    }

    public function getConfirmAt() :string|null
    {
        return $this->confirmed_at;
    }

    public function setStatus(int $status) : void
    {
        $this->status = $status;
    }

    public function getStatus() : int
    {
        return $this->status;
    }

    public function setBestellung(Bestellung|int $bestellung) : void
    {
        $this->bestellung = $bestellung;
    }

    public function getBestellung() : Bestellung|int
    {
        return $this->bestellung;
    }
}
