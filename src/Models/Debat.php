<?php

namespace HollywoodLand\Models;

use HollywoodLand\Config\Database;
use PDOException;

class Debat
{
    public static function all(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT d.id, d.sessio_id, d.titol, d.descripcio, d.aforament, d.durada_min,
                    s.data, s.hora AS hora_sessio, p.titol AS pelicula_titol
             FROM Debat d
             JOIN Sessio s ON s.id = d.sessio_id
             JOIN Pelicula p ON p.id = s.pelicula_id
             ORDER BY s.data DESC, s.hora DESC'
        );

        return $stmt->fetchAll();
    }

    public static function create(int $sessioId, ?string $titol, ?string $descripcio, ?int $aforament, int $duradaMin): int
    {
        $stmt = Database::getConnection()->prepare(
            'INSERT INTO Debat (sessio_id, titol, descripcio, aforament, durada_min)
             VALUES (:sessio_id, :titol, :descripcio, :aforament, :durada_min)'
        );
        $stmt->execute([
            'sessio_id' => $sessioId,
            'titol' => $titol,
            'descripcio' => $descripcio,
            'aforament' => $aforament,
            'durada_min' => $duradaMin,
        ]);

        return (int) Database::getConnection()->lastInsertId();
    }

    public static function update(int $id, int $sessioId, ?string $titol, ?string $descripcio, ?int $aforament, int $duradaMin): void
    {
        $stmt = Database::getConnection()->prepare(
            'UPDATE Debat
             SET sessio_id = :sessio_id, titol = :titol, descripcio = :descripcio,
                 aforament = :aforament, durada_min = :durada_min
             WHERE id = :id'
        );
        $stmt->execute([
            'sessio_id' => $sessioId,
            'titol' => $titol,
            'descripcio' => $descripcio,
            'aforament' => $aforament,
            'durada_min' => $duradaMin,
            'id' => $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        try {
            $stmt = Database::getConnection()->prepare('DELETE FROM Debat WHERE id = :id');
            $stmt->execute(['id' => $id]);

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

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
