<?php

namespace Database;

class UserRepository extends AbstractRepository
{
    protected static string $table = "users";

    public static function select(
        array $params,
        array $fields = ["id", "full_name", "email", "active", "role"],
        int   $offset = 0,
        int   $limit = 100
    ): false|array
    {
        return parent::select($params, $fields, $offset, $limit);
    }

    public static function count(
        array $params = ["active" => 1]
    ): false|array
    {
        return parent::count($params);
    }
}