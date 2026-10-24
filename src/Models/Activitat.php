<?php

namespace HollywoodLand\Models;

use HollywoodLand\Config\Database;

class Activitat
{
    public static function properes(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT id, tipus, titol, descripcio, data, hora, durada_min
             FROM Activitat
             WHERE data >= CURDATE()
             ORDER BY data, hora'
        );

        return $stmt->fetchAll();
    }
}
