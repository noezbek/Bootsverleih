<?php

namespace App\Models;

abstract class Person extends DatabaseEntry
{
    protected string $vorname;
    protected string $nachname;
    protected string $email;
    protected string $geburtsdatum;
    protected string $telefon;
    protected string $strasse;
    protected int $plz;
    protected string $stadt;

    public function __construct(
        string $vorname,
        string $nachname,
        string $email,
        string $geburtsdatum,
        string $telefon,
        string $strasse,
        int $plz,
        string $stadt,
        ?int $id = null,
        ?bool $active = true,
        string|null $updated_at = null,
        string|null $created_at = null,
        User|int|null $user = null,
    ) {
        parent::__construct($id, $active, $updated_at, $created_at, $user);
        $this->vorname = $vorname;
        $this->nachname = $nachname;
        $this->email = $email;
        $this->geburtsdatum = $geburtsdatum;
        $this->telefon = $telefon;
        $this->strasse = $strasse;
        $this->plz = $plz;
        $this->stadt = $stadt;
    }

    protected function savePerson(\PDOStatement $stmt, \PDO $pdoHandler): void
    {
        $stmt->bindValue(":vorname", $this->getVorname());
        $stmt->bindValue(":nachname", $this->getNachname());
        $stmt->bindValue(":email", $this->getEmail());
        $stmt->bindValue(":geburtsdatum", $this->getGeburtsdatum());
        $stmt->bindValue(":telefon", $this->getTelefon());
        $stmt->bindValue(":strasse", $this->getStrasse());
        $stmt->bindValue(":plz", $this->getPlz());
        $stmt->bindValue(":stadt", $this->getStadt());
        $this->saveData($stmt, $pdoHandler);
    }

    protected function toPersonArray(): array
    {
        return [
            'id'       => $this->id,
            'active' => $this->active,
            'vorname' => $this->vorname,
            'nachname' => $this->nachname,
            'email' => $this->email,
            'geburtsdatum' => $this->geburtsdatum,
            'telefon' => $this->telefon,
            'strasse' => $this->strasse,
            'plz' => $this->plz,
            'stadt' => $this->stadt
        ];
    }

    public function getVorname(): string { return $this->vorname; }
    public function setVorname(string $vorname): void { $this->vorname = $vorname; }

    public function getNachname(): string { return $this->nachname; }
    public function setNachname(string $nachname): void { $this->nachname = $nachname; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function getGeburtsdatum(): string { return $this->geburtsdatum; }
    public function setGeburtsdatum(string $geburtsdatum): void { $this->geburtsdatum = $geburtsdatum; }

    public function getTelefon(): string { return $this->telefon; }
    public function setTelefon(string $telefon): void { $this->telefon = $telefon; }

    public function getStrasse(): string { return $this->strasse; }
    public function setStrasse(string $strasse): void { $this->strasse = $strasse; }

    public function getPlz(): int { return $this->plz; }
    public function setPlz(int $plz): void { $this->plz = $plz; }

    public function getStadt(): string { return $this->stadt; }
    public function setStadt(string $stadt): void { $this->stadt = $stadt; }

    public function getFullName(): string { return $this->vorname . ' ' . $this->nachname; }
}