<?php

declare(strict_types=1);

namespace App\Core;

require_once __DIR__ . '/../../config/config.php';

use PDO;

class DB
{
    private static ?PDO $pdo = null;

    public static function connection(): PDO
    {
        if (self::$pdo === null) {
            $db_host = DB_HOST;
            $db_name = DB_NAME;
            $db_user = DB_USER;
            $db_pass = DB_PASS;

            $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";

            $pdo = new PDO($dsn, $db_user, $db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo = $pdo;
        }

        return self::$pdo;
    }

    public static function select(string $sql, array $params = [], string $class = 'stdClass'): array
    {
        if(defined('DEBUG') && DEBUG === true){
            d($sql);
            d($params);
            d($class);
        }
        $stmt = self::connection()->prepare($sql);
        $stmt->execute($params);
        
        $objects = [];

        if ($class === 'stdClass') {
            while ($obj = $stmt->fetchObject()) {
                $objects[] = $obj;
            }
        } else {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $objects[] = $class::hydrate($row);
            }
        }

        return $objects;
    }

    public static function insert(string $sql, array $params = []): int
    {
        $stmt = self::connection()->prepare($sql);
        $stmt->execute($params);
        return (int)self::connection()->lastInsertId();
    }

    public static function update(string $sql, array $params = []): int
    {
        $stmt = self::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public static function delete(string $sql, array $params = []): int
    {
        return self::update($sql, $params);
    }
}
