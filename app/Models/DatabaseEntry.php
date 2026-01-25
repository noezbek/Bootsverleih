<?php

namespace App\Models;
use App\Filters\DbFilter;
use PDO;

abstract class DatabaseEntry
{
    protected int|null $id;
    protected bool $active;
    protected string|null $created_at;
    protected string|null $updated_at;
    protected User|int|null $user;

    public function __construct( ?int $id = null,
                                 ?bool $active = true,
                                 string|null $updated_at = null,
                                 string|null $created_at = null,
                                 User|int|null $user = null,)
    {
        $this->id = $id;
        $this->active = $active;
        $this->updated_at = $updated_at;
        $this->created_at = $created_at;
        $this->user = $user;
    }

    public function saveData(\PDOStatement $stmt, \PDO $pdoHandler): void
    {
        $user_ID = null;
        if (!empty($this->getUser()) && $this->getUser() instanceof DatabaseEntry) {
            $user_ID = $this->getUser()->getID();
        } else if (!empty($this->getUser())) {
            $user_ID = $this->getUser();
        }

        $stmt->bindValue(":Benutzer", $user_ID ?? null);

        try {
            if ($this->id === null || intval($this->id) === 0) {
                $stmt->execute();
                $this->id = $pdoHandler->lastInsertId();
            } else {
                $stmt->bindValue(":ID", $this->getID(), \PDO::PARAM_INT);
                $stmt->bindValue(":Active", $this->active ? 1 : 0);
//                $stmt->bindValue(':Updated', $this->updated_at ? (new DateTime())->format('Y-m-d H:i:s') : null, $this->updated_at ? PDO::PARAM_STR : PDO::PARAM_NULL);
                $stmt->execute();
            }
        } catch (\PDOException $e) {
            // Dump params in a buffer
            ob_start();
            $stmt->debugDumpParams();
            $paramsDump = ob_get_clean();

            $msg = sprintf(
                "SQL execution failed in %s:%d\nMessage: %s\nQuery: %s\nParams:\n%s",
                __FILE__,
                __LINE__,
                $e->getMessage(),
                $stmt->queryString,
                $paramsDump
            );

            throw new \RuntimeException($msg, 0, $e);
        }
    }

    public static function saveRelations(
        PDO    $db,
        string $table,
        string $parentColumn,
        string $relColumn,
        int    $parentID,
        array  $relIDs
    ): void
    {
        $deleteSql = "DELETE FROM `$table` WHERE `$parentColumn` = :parentID";
        $deleteStmt = $db->prepare($deleteSql);
        $deleteStmt->execute([
            ':parentID' => $parentID
        ]);

        if (!empty($relIDs)) {
            $insertSql = "
                INSERT INTO `$table` (`$parentColumn`, `$relColumn`)
                VALUES (:parentID, :relID)
            ";
            $insertStmt = $db->prepare($insertSql);

            foreach ($relIDs as $relID) {
                $insertStmt->execute([
                    ':parentID' => $parentID,
                    ':relID' => $relID
                ]);
            }
        }
    }

    public static function deactivateByID(PDO $db, int $id): void
    {
        $table = static::getTable();
        $stmt = $db->prepare(" UPDATE `$table`
    SET active = 0
    WHERE `ID` = :id");
        $stmt->execute([':id' => $id]);
    }

    public static function deleteByID(PDO $db, int $id): void
    {
        $table = static::getTable();
        $stmt = $db->prepare("DELETE FROM $table WHERE ID = :id");
        $stmt->execute([':id' => $id]);
    }

    public function deleteEntry(PDO $db): void
    {
        if ($this->id === null) return;
        static::deleteByID($db, $this->id);
    }

    public function setID($id): void
    {
        $this->id = $id;
    }

    public function getID(): int
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

    public function getCreated_at(): string
    {
        return $this->created_at;
    }

    public function setCreated_at(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getUpdated_at(): string
    {
        return $this->updated_at;
    }

    public function setUpdated_at(string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public function getUser(): User|int|null
    {
        return $this->user;
    }

    public function setUser(User|int|null $user): void
    {
        $this->user = $user;
    }

    abstract protected static function getInsertStmnt() : string;
    abstract protected static function getUpdateStmnt() : string;
    abstract static public function getTable(): string;
    abstract public function saveEntry(PDO $db): void;
    abstract public static function findByIdEntry(PDO $db, int $id): self|null;
    abstract public static function findAllEntries(PDO $db, ?DbFilter $filter = null): array;
    abstract public function toArray(): array;
}
