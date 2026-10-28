<?php

namespace HollywoodLand\Models;

use HollywoodLand\Config\Database;

class Activitat
{
    public static function properes(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT a.id, a.tipus, a.titol, a.descripcio, a.data, a.hora, a.durada_min, a.aforament,
                    sa.nom AS sala_nom
             FROM Activitat a
             LEFT JOIN Sala sa ON sa.id = a.sala_id
             WHERE a.data >= CURDATE()
             ORDER BY a.data, a.hora'
        );

        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM Activitat WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);
        $activitat = $stmt->fetch();

        return $activitat ?: null;
    }
}
