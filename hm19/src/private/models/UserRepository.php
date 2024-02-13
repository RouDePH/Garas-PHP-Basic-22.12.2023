<?php

namespace Models;

use Classes\{DatabaseConnection};
use PDO;

class UserRepository
{
    public static function create($full_name, $email, $password, $active = false, $role = 'user'): false|string
    {
        $db = DatabaseConnection::getConnection();
        $stmt = $db->prepare("INSERT INTO users (full_name, email, password, active, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$full_name, $email, $password, $active, $role]);
        return $db->lastInsertId();
    }

    public static function getById($id)
    {
        $db = DatabaseConnection::getConnection();
        $stmt = $db->prepare("SELECT `id`,`full_name`,`email`,`active`,`role` FROM `users` WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($id, $full_name, $email, $password, $active, $role): bool
    {
        $db = DatabaseConnection::getConnection();
        $stmt = $db->prepare("UPDATE users SET full_name = ?, email = ?, password = ?, active = ?, role = ? WHERE id = ?");
        return $stmt->execute([$full_name, $email, $password, $active, $role, $id]);
    }

    public static function delete($id): bool
    {
        $db = DatabaseConnection::getConnection();
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function getAll(): false|array
    {
        $db = DatabaseConnection::getConnection();
        $stmt = $db->query("SELECT `id`,`full_name`,`email`,`active`,`role` FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function query(string $sql, array $params = []): ?array
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