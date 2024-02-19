<?php

namespace Classes;

use Interfaces\IRepository;
use PDO;

abstract class AbstractRepository implements IRepository
{
    public static function insert(array $params): false|string
    {
        $db = DatabaseConnection::getConnection();
        $queryBuilder = new MysqlQueryBuilder();

        $fields = array_keys($params);

        $query = $queryBuilder
            ->insert(static::$table, $fields)
            ->getSQL();

        $stmt = $db->prepare($query);
        $stmt->execute(array_values($params));
        return $db->lastInsertId();
    }

    public static function select(array $params, array $fields, int $offset, int $limit): false|array
    {
        $db = DatabaseConnection::getConnection();
        $queryBuilder = new MysqlQueryBuilder();

        $searchFields = array_keys($params);
        $placeholders = $searchFields ? implode(' = ? AND ', $searchFields) : null;

        $query = $queryBuilder
            ->select(static::$table, $fields)
            ->where($placeholders)
            ->limit($offset, $limit)
            ->getSQL();

        $stmt = $db->prepare($query);
        $stmt->execute(array_values($params));

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return match (true) {
            count($result) > 1 => $result,
            count($result) === 1 => $result[0],
            default => false
        };
    }

    public static function update(array $params, array $fields): bool
    {
        $db = DatabaseConnection::getConnection();
        $queryBuilder = new MysqlQueryBuilder();

        $updateFields = array_keys($fields);
        $updatePlaceholders = implode(' = ?, ', $updateFields) . ' = ?';

        $searchFields = array_keys($params);
        $searchPlaceholders = implode(' = ? AND ', $searchFields);

        $params = array_merge(array_values($fields), array_values($params));

        $query = $queryBuilder
            ->update(static::$table, $updatePlaceholders)
            ->where($searchPlaceholders)
            ->getSQL();

        $stmt = $db->prepare($query);
        $stmt->execute(array_values($params));
        return $stmt->rowCount() > 0;
    }

    public static function delete(array $params): bool
    {
        $db = DatabaseConnection::getConnection();
        $queryBuilder = new MysqlQueryBuilder();

        $searchFields = array_keys($params);
        $placeholders = implode(' = ? AND ', $searchFields);

        $query = $queryBuilder
            ->delete(static::$table)
            ->where($placeholders)
            ->getSQL();

        $stmt = $db->prepare($query);
        $stmt->execute(array_values($params));
        return $stmt->rowCount() > 0;
    }

    public static function count(array $params = []): false|array
    {
        $db = DatabaseConnection::getConnection();
        $queryBuilder = new MysqlQueryBuilder();

        $searchFields = array_keys($params);
        $placeholders = $searchFields ? implode(' = ? AND ', $searchFields) : null;

        $query = $queryBuilder
            ->select(static::$table, ['COUNT(*) as count'])
            ->where($placeholders)
            ->getSQL();

        $stmt = $db->prepare($query);
        $stmt->execute(array_values($params));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function query(array $params, string $sql): false|array
    {
        $db = DatabaseConnection::getConnection();

        $sth = $db->prepare($sql);
        $result = $sth->execute($params);

        if (false === $result) {
            return false;
        }

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

}