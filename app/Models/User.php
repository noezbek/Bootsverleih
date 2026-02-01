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

    public function verifyPassword(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->passwordHash);
    }

    public function saveEntry(PDO $db): void
    {
        $table = self::getTable();

        if ($this->id === null) {
            $stmt = $db->prepare(self::getInsertStmnt());
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
                 SET username = :u, password_hash = :p, kunde_ID = :k, mitarbeiter_ID = :m
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
            $row['kunde_ID'] !== null ? (int)$row['kunde_ID'] : null,
            $row['mitarbeiter_ID'] !== null ? (int)$row['mitarbeiter_ID'] : null,
            (int)$row['ID'],
            (bool)$row['active'],
            $row['updated_at'],
            $row['created_at'],
        ) : null;
    }

    public static function findForAuth(PDO $pdo, string $username): ?User
    {
        $stmt = $pdo->prepare(
            'SELECT ID, username, password_hash, active
         FROM users
         WHERE username = :username
         LIMIT 1'
        );

        $stmt->execute(['username' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (! $row) {
            return null;
        }

        return new User(
            $row['username'],
            $row['password_hash'],
            null,
            null,
            (int) $row['ID'],
            (bool) $row['active']
        );
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
        return "INSERT INTO $table (username, password_hash, kunde_ID, mitarbeiter_ID) VALUES (:u, :p, :k, :m)";
    }

    protected static function getUpdateStmnt() : string
    {
        $table = self::getTable();
        return "UPDATE $table SET username = :u, password_hash = :p, kunde_ID = :k, mitarbeiter_ID = :m, active = :Active  WHERE ID = :ID";
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id
        ];
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    public function getKunde(): Kunde|int|null
    {
        return $this->kunde;
    }

    public function setKunde(Kunde|int|null $kunde): void
    {
        $this->kunde = $kunde;
    }

    public function getMitarbeiter(): Mitarbeiter|int|null
    {
        return $this->mitarbeiter;
    }

    public function setMitarbeiter(Mitarbeiter|int|null $mitarbeiter): void
    {
        $this->mitarbeiter = $mitarbeiter;
    }
}