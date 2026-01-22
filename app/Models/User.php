<?php

namespace App\Models;

use App\Filters\DbFilter;
use PDO;

class User extends DatabaseEntry
{
    private string $username;
    private string $passwordHash;
    private ?int $kunde;
    private ?int $mitarbeiter;

    public function __construct(
        string $username,
        string $passwordHash,
        ?int $kunde = null,
        ?int $mitarbeiter = null,
        ?int $id = null,
        ?bool $active = true,
        string|null $updated_at = null,
        string|null $created_at = null,
        User|int|null $user = null,
    ) {
        parent::__construct($id, $active, $updated_at, $created_at, $user);
        $this->username = $username;
        $this->passwordHash = $passwordHash;
        $this->kunde = $kunde;
        $this->mitarbeiter = $mitarbeiter;
    }

    public static function getTable(): string
    {
        return 'users';
    }

    // Hilfsfunktion zum Erstellen mit Klartext-Passwort
    public static function createWithPlainPassword(
        string $username,
        string $plainPassword,
        ?int $kunde = null,
        ?int $mitarbeiter = null
    ): User {
        $hash = password_hash($plainPassword, PASSWORD_DEFAULT);
        return new User($username, $hash, $kunde, $mitarbeiter);
    }

    public function verifyPassword(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->passwordHash);
    }

    public function saveEntry(PDO $db): void
    {
        $table = self::getTable();

        if ($this->id === null) {
            $stmt = $db->prepare(
                "INSERT INTO $table (username, password_hash, kunde, mitarbeiter)
                 VALUES (:u, :p, :k, :m)"
            );
            $stmt->execute([
                ':u' => $this->username,
                ':p' => $this->passwordHash,
                ':k' => $this->kunde,
                ':m' => $this->mitarbeiter
            ]);
            $this->id = (int)$db->lastInsertId();
        } else {
            $stmt = $db->prepare(
                "UPDATE $table
                 SET username = :u, password_hash = :p, kunde = :k, mitarbeiter = :m
                 WHERE ID = :id"
            );
            $stmt->execute([
                ':u'  => $this->username,
                ':p'  => $this->passwordHash,
                ':k'  => $this->kunde,
                ':m'  => $this->mitarbeiter,
                ':id' => $this->id
            ]);
        }
    }

    public function deleteEntry(PDO $db): void
    {
        if ($this->id === null) return;

        $table = self::getTable();
        $stmt = $db->prepare("DELETE FROM $table WHERE ID = :id");
        $stmt->execute([':id' => $this->id]);
    }

    public static function findByIdEntry(PDO $db, int $id): ?static
    {
        $table = self::getTable();

        $stmt = $db->prepare("SELECT * FROM $table WHERE ID = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row ? new User(
            $row['username'],
            $row['password_hash'],
            $row['kunde'] !== null ? (int)$row['kunde'] : null,
            $row['mitarbeiter'] !== null ? (int)$row['mitarbeiter'] : null,
            (int)$row['ID']
        ) : null;
    }

    public static function findByUsername(PDO $db, string $username): ?User
    {
        $table = self::getTable();

        $stmt = $db->prepare("SELECT * FROM $table WHERE username = :u");
        $stmt->execute([':u' => $username]);
        $row = $stmt->fetch();

        return $row ? new User(
            $row['username'],
            $row['password_hash'],
            $row['kunde'] !== null ? (int)$row['kunde'] : null,
            $row['mitarbeiter'] !== null ? (int)$row['mitarbeiter'] : null,
            (int)$row['ID']
        ) : null;
    }

    public static function findAllEntries(PDO $db, ?DbFilter $filter = null): array
    {
        $table = self::getTable();
        $stmt = $db->query("SELECT * FROM $table");

        $list = [];
        while ($row = $stmt->fetch()) {
            $list[] = new User(
                $row['username'],
                $row['password_hash'],
                $row['kunde'] !== null ? (int)$row['kunde'] : null,
                $row['mitarbeiter'] !== null ? (int)$row['mitarbeiter'] : null,
                (int)$row['ID']
            );
        }

        return $list;
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

    public function toArray(): array
    {
        return [
            'id' => $this->id
        ];
    }
}