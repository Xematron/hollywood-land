<?php

namespace HollywoodLand\Models;

use HollywoodLand\Config\Database;
use PDOException;

class Sessio
{
    public static function cartellera(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT s.id, s.data, s.hora, s.preu, s.idioma_subtitols,
                    p.id AS pelicula_id, p.titol, p.durada_min, p.classificacio,
                    sa.nom AS sala_nom
             FROM Sessio s
             JOIN Pelicula p ON p.id = s.pelicula_id
             JOIN Sala sa ON sa.id = s.sala_id
             WHERE s.data >= CURDATE()
             ORDER BY s.data, s.hora'
        );

        return $stmt->fetchAll();
    }

    public static function all(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT s.id, s.data, s.hora, s.preu, s.idioma_subtitols,
                    p.id AS pelicula_id, p.titol,
                    sa.id AS sala_id, sa.nom AS sala_nom
             FROM Sessio s
             JOIN Pelicula p ON p.id = s.pelicula_id
             JOIN Sala sa ON sa.id = s.sala_id
             ORDER BY s.data DESC, s.hora DESC'
        );

        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM Sessio WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);
        $sessio = $stmt->fetch();

        return $sessio ?: null;
    }

    public static function create(
        int $peliculaId,
        int $salaId,
        string $data,
        string $hora,
        float $preu,
        ?string $idiomaSubtitols,
        ?int $cicleId = null
    ): int {
        $stmt = Database::getConnection()->prepare(
            'INSERT INTO Sessio (pelicula_id, sala_id, cicle_id, data, hora, preu, idioma_subtitols)
             VALUES (:pelicula_id, :sala_id, :cicle_id, :data, :hora, :preu, :idioma_subtitols)'
        );
        $stmt->execute([
            'pelicula_id' => $peliculaId,
            'sala_id' => $salaId,
            'cicle_id' => $cicleId,
            'data' => $data,
            'hora' => $hora,
            'preu' => $preu,
            'idioma_subtitols' => $idiomaSubtitols,
        ]);

        return (int) Database::getConnection()->lastInsertId();
    }

    public static function update(
        int $id,
        int $peliculaId,
        int $salaId,
        string $data,
        string $hora,
        float $preu,
        ?string $idiomaSubtitols
    ): void {
        $stmt = Database::getConnection()->prepare(
            'UPDATE Sessio
             SET pelicula_id = :pelicula_id, sala_id = :sala_id, data = :data,
                 hora = :hora, preu = :preu, idioma_subtitols = :idioma_subtitols
             WHERE id = :id'
        );
        $stmt->execute([
            'pelicula_id' => $peliculaId,
            'sala_id' => $salaId,
            'data' => $data,
            'hora' => $hora,
            'preu' => $preu,
            'idioma_subtitols' => $idiomaSubtitols,
            'id' => $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        try {
            $stmt = Database::getConnection()->prepare('DELETE FROM Sessio WHERE id = :id');
            $stmt->execute(['id' => $id]);

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}