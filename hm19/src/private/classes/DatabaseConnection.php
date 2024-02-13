<?php

namespace Classes;

use PDO;

class DatabaseConnection extends Singleton
{
    private PDO $pdo;

    protected function __construct()
    {
        $this->pdo = new PDO(getenv("DB_DSN"), getenv("DB_ROOT_USER"), getenv("DB_ROOT_PASS"));
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getConnection(): PDO
    {
        $instance = static::getInstance();
        return $instance->pdo;
    }
}