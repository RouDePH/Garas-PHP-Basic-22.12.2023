<?php

namespace Models;

use Classes\{DatabaseConnection, MysqlQueryBuilder};
use PDO;

class UserRepository
{
    public static function create(array $params): false|string
    {
        $db = DatabaseConnection::getConnection();
        $queryBuilder = new MysqlQueryBuilder();

        $fields = array_keys($params);

        $query = $queryBuilder
            ->insert("users", $fields)
            ->getSQL();

        $stmt = $db->prepare($query);
        $stmt->execute(array_values($params));
        return $db->lastInsertId();
    }

    public static function getById(int $id): mixed
    {
        $db = DatabaseConnection::getConnection();
        $queryBuilder = new MysqlQueryBuilder();

        $query = $queryBuilder
            ->select("users", ["id", "full_name", "email", "active", "role"])
            ->where("id")
            ->getSQL();

        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function update(int $id, array $params): bool
    {
        $db = DatabaseConnection::getConnection();
        $queryBuilder = new MysqlQueryBuilder();

        $fields = array_keys($params);
        $placeholders = implode(' = ?, ', $fields) . ' = ?';
        $params[] = $id;

        $query = $queryBuilder
            ->update("users", $placeholders)
            ->where("id")
            ->getSQL();

        $stmt = $db->prepare($query);
        $stmt->execute(array_values($params));
        return $stmt->rowCount() > 0;
    }

    public static function delete(int $id): bool
    {
        $db = DatabaseConnection::getConnection();
        $queryBuilder = new MysqlQueryBuilder();

        $query = $queryBuilder
            ->delete("users")
            ->where("id")
            ->getSQL();

        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    public static function getAll(int $offset, int $limit): false|array
    {
        $db = DatabaseConnection::getConnection();
        $queryBuilder = new MysqlQueryBuilder();

        $query = $queryBuilder
            ->select("users", ['id', "full_name", "email", "active", "role"])
            ->limit($offset, $limit)
            ->getSQL();

        $stmt = $db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function count(): false|array
    {
        $db = DatabaseConnection::getConnection();
        $queryBuilder = new MysqlQueryBuilder();

        $query = $queryBuilder
            ->select("users", ['COUNT(*) as count'])
            ->getSQL();

        $stmt = $db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
    }

    public static function query(string $sql, array $params = []): ?array
    {
        $db = DatabaseConnection::getConnection();

        $sth = $db->prepare($sql);
        $result = $sth->execute($params);

        if (false === $result) {
            return null;
        }

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
}