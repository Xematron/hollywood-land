<?php

namespace HollywoodLand\Models;

use HollywoodLand\Config\Database;

class InscripcioDebat
{
    public static function comptar(int $debatId): int
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COUNT(*) FROM InscripcioDebat WHERE debat_id = :debat_id'
        );
        $stmt->execute(['debat_id' => $debatId]);

        return (int) $stmt->fetchColumn();
    }

    public static function jaInscrit(int $debatId, int $usuariId): bool
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT 1 FROM InscripcioDebat WHERE debat_id = :debat_id AND usuari_id = :usuari_id'
        );
        $stmt->execute(['debat_id' => $debatId, 'usuari_id' => $usuariId]);

        return (bool) $stmt->fetchColumn();
    }

    public static function inscriure(int $debatId, int $usuariId): void
    {
        $stmt = Database::getConnection()->prepare(
            'INSERT INTO InscripcioDebat (debat_id, usuari_id) VALUES (:debat_id, :usuari_id)'
        );
        $stmt->execute(['debat_id' => $debatId, 'usuari_id' => $usuariId]);
    }

    public static function cancelar(int $debatId, int $usuariId): void
    {
        $stmt = Database::getConnection()->prepare(
            'DELETE FROM InscripcioDebat WHERE debat_id = :debat_id AND usuari_id = :usuari_id'
        );
        $stmt->execute(['debat_id' => $debatId, 'usuari_id' => $usuariId]);
    }

    public static function findByUsuari(int $usuariId): array
    {
        $stmt = Database::getConnection()->prepare(
            "SELECT i.id, d.id AS debat_id, d.titol, d.durada_min, d.aforament,
                    s.data, s.hora AS hora_sessio, p.titol AS pelicula_titol, p.durada_min AS pelicula_durada
             FROM InscripcioDebat i
             JOIN Debat d ON d.id = i.debat_id
             JOIN Sessio s ON s.id = d.sessio_id
             JOIN Pelicula p ON p.id = s.pelicula_id
             WHERE i.usuari_id = :usuari_id
             ORDER BY s.data DESC, s.hora DESC"
        );
        $stmt->execute(['usuari_id' => $usuariId]);

        return $stmt->fetchAll();
    }
}
