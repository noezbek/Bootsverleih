<?php

namespace App\Models;

use CodeIgniter\Model;

abstract class Person extends DatabaseEntry
{
    protected string $vorname;
    protected string $nachname;
    protected string $email;
    protected string $geburtsdatum;
    protected int $telefon;
    protected string $strasse;
    protected int $plz;
    protected string $stadt;

    public function __construct(
        string $vorname,
        string $nachname,
        string $email,
        string $geburtsdatum,
        int $telefon,
        string $strasse,
        int $plz,
        string $stadt,
        ?bool $active = true,
        ?int $id = null
    ) {
        parent::__construct($id, $active);
        $this->vorname = $vorname;
        $this->nachname = $nachname;
        $this->email = $email;
        $this->geburtsdatum = $geburtsdatum;
        $this->telefon = $telefon;
        $this->strasse = $strasse;
        $this->plz = $plz;
        $this->stadt = $stadt;
    }

    protected function getPersonBindArray(): array
    {
        return [
            ':v'  => $this->vorname,
            ':n'  => $this->nachname,
            ':e'  => $this->email,
            ':g'  => $this->geburtsdatum,
            ':t'  => $this->telefon,
            ':s'  => $this->strasse,
            ':p'  => $this->plz,
            ':st' => $this->stadt,
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

    public function getTelefon(): int { return $this->telefon; }
    public function setTelefon(int $telefon): void { $this->telefon = $telefon; }

    public function getStrasse(): string { return $this->strasse; }
    public function setStrasse(string $strasse): void { $this->strasse = $strasse; }

    public function getPlz(): int { return $this->plz; }
    public function setPlz(int $plz): void { $this->plz = $plz; }

    public function getStadt(): string { return $this->stadt; }
    public function setStadt(string $stadt): void { $this->stadt = $stadt; }
}