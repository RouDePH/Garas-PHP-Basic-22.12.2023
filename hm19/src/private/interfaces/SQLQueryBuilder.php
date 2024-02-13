<?php

namespace Interfaces;

interface SQLQueryBuilder
{
    public function insert(string $table, array $fields): SQLQueryBuilder;

    public function select(string $table, array $fields): SQLQueryBuilder;

    public function where(string $field, string $value, string $operator = '='): SQLQueryBuilder;

    public function limit(int $start, int $offset): SQLQueryBuilder;

    public function getSQL(): string;
}