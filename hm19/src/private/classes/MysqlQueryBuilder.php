<?php

namespace Classes;

use Exception;
use Interfaces\{SQLQueryBuilder};
use stdClass;

class MysqlQueryBuilder implements SQLQueryBuilder
{
    protected stdClass $query;

    protected function reset(): void
    {
        $this->query = new stdClass();
    }

    public function insert(string $table, array $fields): SQLQueryBuilder
    {
        $this->reset();
        $fieldsCount = count($fields);
        $this->query->base = "INSERT INTO " . $table . " (" . implode(", ", $fields) . ") VALUES (" . implode(", ", array_fill(0, $fieldsCount, "?")) . ")";
        $this->query->type = 'insert';

        return $this;
    }

    public function select(string $table, array $fields): SQLQueryBuilder
    {
        $this->reset();
        $this->query->base = "SELECT " . implode(", ", $fields) . " FROM " . $table;
        $this->query->type = 'select';

        return $this;
    }

    /**
     * @throws Exception
     */
    public function where(string $field, string $value = null, string $operator = '='): SQLQueryBuilder
    {
        if (!in_array($this->query->type, ['select', 'update', 'delete'])) {
            throw new Exception("WHERE can only be added to SELECT, UPDATE OR DELETE");
        }
        $this->query->where[] = $value ? "$field $operator '$value'" : "$field $operator ?";

        return $this;
    }


    /**
     * @throws Exception
     */
    public function limit(int $start, int $offset): SQLQueryBuilder
    {
        if ($this->query->type !== 'select') {
            throw new Exception("LIMIT can only be added to SELECT");
        }
        $this->query->limit = " LIMIT " . $start . ", " . $offset;

        return $this;
    }

    public function getSQL(): string
    {
        $query = $this->query;
        $sql = $query->base;
        if (!empty($query->where)) {
            $sql .= " WHERE " . implode(' AND ', $query->where);
        }
        if (isset($query->limit)) {
            $sql .= $query->limit;
        }
        $sql .= ";";
        return $sql;
    }
}