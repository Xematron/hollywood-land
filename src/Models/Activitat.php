<?php

namespace HollywoodLand\Models;

use HollywoodLand\Config\Database;
use PDOException;

class Activitat
{
    public static function all(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT a.id, a.tipus, a.titol, a.descripcio, a.data, a.hora, a.durada_min, a.aforament, a.sala_id,
                    sa.nom AS sala_nom
             FROM Activitat a
             LEFT JOIN Sala sa ON sa.id = a.sala_id
             ORDER BY a.data DESC, a.hora DESC'
        );

        return $stmt->fetchAll();
    }

    public static function create(
        string $tipus,
        string $titol,
        ?string $descripcio,
        string $data,
        string $hora,
        int $duradaMin,
        ?int $aforament,
        ?int $salaId
    ): int {
        $stmt = Database::getConnection()->prepare(
            'INSERT INTO Activitat (tipus, titol, descripcio, data, hora, durada_min, aforament, sala_id)
             VALUES (:tipus, :titol, :descripcio, :data, :hora, :durada_min, :aforament, :sala_id)'
        );
        $stmt->execute([
            'tipus' => $tipus,
            'titol' => $titol,
            'descripcio' => $descripcio,
            'data' => $data,
            'hora' => $hora,
            'durada_min' => $duradaMin,
            'aforament' => $aforament,
            'sala_id' => $salaId,
        ]);

        return (int) Database::getConnection()->lastInsertId();
    }

    public static function update(
        int $id,
        string $tipus,
        string $titol,
        ?string $descripcio,
        string $data,
        string $hora,
        int $duradaMin,
        ?int $aforament,
        ?int $salaId
    ): void {
        $stmt = Database::getConnection()->prepare(
            'UPDATE Activitat
             SET tipus = :tipus, titol = :titol, descripcio = :descripcio, data = :data, hora = :hora,
                 durada_min = :durada_min, aforament = :aforament, sala_id = :sala_id
             WHERE id = :id'
        );
        $stmt->execute([
            'tipus' => $tipus,
            'titol' => $titol,
            'descripcio' => $descripcio,
            'data' => $data,
            'hora' => $hora,
            'durada_min' => $duradaMin,
            'aforament' => $aforament,
            'sala_id' => $salaId,
            'id' => $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        try {
            $stmt = Database::getConnection()->prepare('DELETE FROM Activitat WHERE id = :id');
            $stmt->execute(['id' => $id]);

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

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
