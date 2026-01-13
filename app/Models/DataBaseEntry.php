<?php

namespace App\Models;

abstract class DatabaseEntry
{
    protected ?int $id;

    public function __construct(?int $id = null)
    {
        $this->id = $id;
    }

    public function getID(): ?int
    {
        return $this->id;
    }

    abstract static public function getTable() : string;

    abstract public function saveEntry(): void;
    abstract public function deleteEntry(): void;

    abstract public static function findByIdEntry(int $id): ?static;
    abstract public static function findAllEntries(): array;
}
