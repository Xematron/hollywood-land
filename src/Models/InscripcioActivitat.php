<?php

namespace HollywoodLand\Models;

use HollywoodLand\Config\Database;

class InscripcioActivitat
{
    public static function comptar(int $activitatId): int
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COUNT(*) FROM InscripcioActivitat WHERE activitat_id = :activitat_id'
        );
        $stmt->execute(['activitat_id' => $activitatId]);

        return (int) $stmt->fetchColumn();
    }

    public static function jaInscrit(int $activitatId, int $usuariId): bool
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT 1 FROM InscripcioActivitat WHERE activitat_id = :activitat_id AND usuari_id = :usuari_id'
        );
        $stmt->execute(['activitat_id' => $activitatId, 'usuari_id' => $usuariId]);

        return (bool) $stmt->fetchColumn();
    }

    public static function inscriure(int $activitatId, int $usuariId): void
    {
        $stmt = Database::getConnection()->prepare(
            'INSERT INTO InscripcioActivitat (activitat_id, usuari_id) VALUES (:activitat_id, :usuari_id)'
        );
        $stmt->execute(['activitat_id' => $activitatId, 'usuari_id' => $usuariId]);
    }

    public static function cancelar(int $activitatId, int $usuariId): void
    {
        $stmt = Database::getConnection()->prepare(
            'DELETE FROM InscripcioActivitat WHERE activitat_id = :activitat_id AND usuari_id = :usuari_id'
        );
        $stmt->execute(['activitat_id' => $activitatId, 'usuari_id' => $usuariId]);
    }

    public static function findByUsuari(int $usuariId): array
    {
        $stmt = Database::getConnection()->prepare(
            "SELECT i.id, a.id AS activitat_id, a.tipus, a.titol, a.data, a.hora, a.durada_min, a.aforament
             FROM InscripcioActivitat i
             JOIN Activitat a ON a.id = i.activitat_id
             WHERE i.usuari_id = :usuari_id
             ORDER BY a.data DESC, a.hora DESC"
        );
        $stmt->execute(['usuari_id' => $usuariId]);

        return $stmt->fetchAll();
    }
}
