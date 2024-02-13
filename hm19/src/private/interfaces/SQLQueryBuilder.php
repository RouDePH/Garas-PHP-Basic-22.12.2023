<?php

namespace Interfaces;

interface SQLQueryBuilder
{
    public function insert(string $table, array $fields): SQLQueryBuilder;

    public function select(string $table, array $fields): SQLQueryBuilder;

    public function update(string $table, string $placeholders): SQLQueryBuilder;

    public function delete(string $table): SQLQueryBuilder;

    public function where(string $field, string $value, string $operator = '='): SQLQueryBuilder;

    public function limit(int $start, int $offset): SQLQueryBuilder;

    public function getSQL(): string;
}