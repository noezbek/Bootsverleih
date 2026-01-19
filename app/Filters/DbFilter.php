<?php

namespace App\Filters;

use InvalidArgumentException;

final class DbFilter
{
    /** @var array<int, array{sql:string, params:array}> */
    private array $conditions = [];

    /** @var array<int, array{col:string, dir:string}> */
    private array $orderBy = [];

    private ?int $limit = null;
    private ?int $offset = null;

    public function where(string $column, string $op, mixed $value): self
    {
        $op = strtoupper(trim($op));
        $allowed = ['=', '!=', '<>', '>', '>=', '<', '<=', 'LIKE'];
        if (!in_array($op, $allowed, true)) {
            throw new InvalidArgumentException("Operator nicht erlaubt: $op");
        }

        // NULL special-case
        if ($value === null) {
            if ($op === '=' ) {
                $this->conditions[] = ['sql' => "$column IS NULL", 'params' => []];
                return $this;
            }
            if ($op === '!=' || $op === '<>') {
                $this->conditions[] = ['sql' => "$column IS NOT NULL", 'params' => []];
                return $this;
            }
        }

        $this->conditions[] = ['sql' => "$column $op ?", 'params' => [$value]];
        return $this;
    }

    public function whereIn(string $column, array $values): self
    {
        $values = array_values($values);
        if (!$values) {
            // leerer IN-Filter soll "nichts finden"
            $this->conditions[] = ['sql' => "1=0", 'params' => []];
            return $this;
        }

        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $this->conditions[] = ['sql' => "$column IN ($placeholders)", 'params' => $values];
        return $this;
    }

    public function whereBetween(string $column, mixed $from, mixed $to): self
    {
        $this->conditions[] = ['sql' => "$column BETWEEN ? AND ?", 'params' => [$from, $to]];
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $dir = strtoupper(trim($direction));
        if (!in_array($dir, ['ASC', 'DESC'], true)) {
            throw new InvalidArgumentException("ORDER BY direction nicht erlaubt: $direction");
        }
        $this->orderBy[] = ['col' => $column, 'dir' => $dir];
        return $this;
    }

    public function limit(int $limit, ?int $offset = null): self
    {
        if ($limit < 1) throw new InvalidArgumentException("limit muss >= 1 sein");
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }

    /** @return array{whereSql:string, params:array, orderSql:string, limitSql:string} */
    public function compile(): array
    {
        $params = [];
        $whereSql = '';

        if ($this->conditions) {
            $parts = [];
            foreach ($this->conditions as $c) {
                $parts[] = '(' . $c['sql'] . ')';
                foreach ($c['params'] as $p) $params[] = $p;
            }
            $whereSql = ' WHERE ' . implode(' AND ', $parts);
        }

        $orderSql = '';
        if ($this->orderBy) {
            $orderParts = array_map(fn($o) => $o['col'] . ' ' . $o['dir'], $this->orderBy);
            $orderSql = ' ORDER BY ' . implode(', ', $orderParts);
        }

        $limitSql = '';
        if ($this->limit !== null) {
            $limitSql = ' LIMIT ' . (int)$this->limit;
            if ($this->offset !== null) $limitSql .= ' OFFSET ' . (int)$this->offset;
        }

        return compact('whereSql', 'params', 'orderSql', 'limitSql');
    }
}
