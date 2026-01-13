<?php

namespace App\Models;

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

    abstract public function saveEntry(): void;
    abstract public function deleteEntry(): void;

    abstract public static function findByIdEntry(int $id): ?static;
    abstract public static function findAllEntries(): array;
}
