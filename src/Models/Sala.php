<?php

namespace HollywoodLand\Models;

use HollywoodLand\Config\Database;

class Sala
{
    public static function all(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT sa.id, sa.nom,
                    COUNT(b.id) AS capacitat,
                    SUM(CASE WHEN b.operativa = 0 THEN 1 ELSE 0 END) AS fora_servei
             FROM Sala sa
             LEFT JOIN Butaca b ON b.sala_id = sa.id
             GROUP BY sa.id, sa.nom
             ORDER BY sa.nom'
        );

        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM Sala WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);
        $sala = $stmt->fetch();

        return $sala ?: null;
    }

    public static function updateNom(int $id, string $nom): void
    {
        $stmt = Database::getConnection()->prepare(
            'UPDATE Sala SET nom = :nom WHERE id = :id'
        );
        $stmt->execute([
            'nom' => $nom,
            'id' => $id,
        ]);
    }
}
