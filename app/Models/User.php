<?php

namespace App\Models;

use DBConnection;

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
        ?int $id = null
    ) {
        parent::__construct($id);
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

    public function saveEntry(): void
    {
        $db = DBConnection::getConnection();
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

    public function deleteEntry(): void
    {
        if ($this->id === null) return;

        $db = DBConnection::getConnection();
        $table = self::getTable();
        $stmt = $db->prepare("DELETE FROM $table WHERE ID = :id");
        $stmt->execute([':id' => $this->id]);
    }

    public static function findByIdEntry(int $id): ?static
    {
        $db = DBConnection::getConnection();
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

    public static function findByUsername(string $username): ?User
    {
        $db = DBConnection::getConnection();
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

    public static function findAllEntries(): array
    {
        $db = DBConnection::getConnection();
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
}