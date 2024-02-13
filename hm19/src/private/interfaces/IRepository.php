<?php

namespace Interfaces;

interface IRepository
{
    public static function insert(array $params): false|string;

    public static function select(array $params, array $fields, int $offset, int $limit): false|array;

    public static function update(array $params, array $fields): bool;

    public static function delete(array $params): bool;

    public static function count(array $params): false|array;

    public static function query(array $params, string $sql): false|array;
}