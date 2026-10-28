<?php

namespace HollywoodLand\Models;

use HollywoodLand\Config\Database;

class Debat
{
    public static function propers(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT d.id, d.titol, d.descripcio, d.aforament, d.durada_min,
                    s.data, s.hora AS hora_sessio, p.titol AS pelicula_titol, p.durada_min AS pelicula_durada,
                    sa.nom AS sala_nom
             FROM Debat d
             JOIN Sessio s ON s.id = d.sessio_id
             JOIN Pelicula p ON p.id = s.pelicula_id
             JOIN Sala sa ON sa.id = s.sala_id
             WHERE s.data >= CURDATE()
             ORDER BY s.data, s.hora'
        );

        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT d.id, d.titol, d.descripcio, d.aforament, d.durada_min,
                    s.data, s.hora AS hora_sessio, p.titol AS pelicula_titol, p.durada_min AS pelicula_durada,
                    sa.nom AS sala_nom
             FROM Debat d
             JOIN Sessio s ON s.id = d.sessio_id
             JOIN Pelicula p ON p.id = s.pelicula_id
             JOIN Sala sa ON sa.id = s.sala_id
             WHERE d.id = :id'
        );
        $stmt->execute(['id' => $id]);
        $debat = $stmt->fetch();

        return $debat ?: null;
    }
}
