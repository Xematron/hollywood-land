<?php

namespace HollywoodLand\Config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            // putenv() està deshabilitat en hosting compartit (InfinityFree);
            // env.local.php hi defineix constants HL_DB_* en lloc de cridar putenv().
            $host = defined('HL_DB_HOST') ? HL_DB_HOST : getenv('DB_HOST');
            $db = defined('HL_DB_NAME') ? HL_DB_NAME : getenv('DB_NAME');
            $user = defined('HL_DB_USER') ? HL_DB_USER : getenv('DB_USER');
            $password = defined('HL_DB_PASSWORD') ? HL_DB_PASSWORD : getenv('DB_PASSWORD');

            try {
                self::$instance = new PDO(
                    "mysql:host=$host;dbname=$db;charset=utf8mb4",
                    $user,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                throw new PDOException('Error de connexió a la BD: ' . $e->getMessage(), (int) $e->getCode());
            }
        }

        return self::$instance;
    }
}
