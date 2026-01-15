<?php

namespace App\Models;
use PDO;

abstract class DatabaseEntry
{
    protected ?int $id;
    protected bool $active;

    public function __construct(?int $id = null, ?bool $active = true)
    {
        $this->id = $id;
        $this->active = $active;
    }

    public function getID(): ?int
    {
        return $this->id;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    abstract static public function getTable(): string;

    abstract public function saveEntry(PDO $db): void;
    abstract public function deleteEntry(PDO $db): void;

    abstract public static function findByIdEntry(PDO $db, int $id): ?static;
    abstract public static function findAllEntries(PDO $db): array;
    abstract public function toArray(): array;
}
