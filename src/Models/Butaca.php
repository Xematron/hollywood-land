<?php

namespace HollywoodLand\Models;

use HollywoodLand\Config\Database;

class Butaca
{
    public static function perSala(int $salaId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM Butaca WHERE sala_id = :sala_id ORDER BY fila, numero'
        );
        $stmt->execute(['sala_id' => $salaId]);

        return $stmt->fetchAll();
    }

    public static function setOperativa(int $id, bool $operativa): void
    {
        $stmt = Database::getConnection()->prepare(
            'UPDATE Butaca SET operativa = :operativa WHERE id = :id'
        );
        $stmt->execute([
            'operativa' => $operativa ? 1 : 0,
            'id' => $id,
        ]);
    }
}
