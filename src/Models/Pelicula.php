<?php

namespace HollywoodLand\Models;

use HollywoodLand\Config\Database;

class Pelicula
{
    public static function all(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT * FROM Pelicula ORDER BY titol'
        );

        return $stmt->fetchAll();
    }
}