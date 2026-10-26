<?php

namespace HollywoodLand\Models;

use HollywoodLand\Config\Database;
use PDOException;

class Pelicula
{
    public static function all(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT * FROM Pelicula ORDER BY titol'
        );

        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM Pelicula WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);
        $pelicula = $stmt->fetch();

        return $pelicula ?: null;
    }

    public static function create(
        string $titol,
        int $duradaMin,
        string $idiomaOriginal,
        ?string $sinopsi,
        ?string $cartellImg,
        ?string $classificacio
    ): int {
        $stmt = Database::getConnection()->prepare(
            'INSERT INTO Pelicula (titol, durada_min, idioma_original, sinopsi, cartell_img, classificacio)
             VALUES (:titol, :durada_min, :idioma_original, :sinopsi, :cartell_img, :classificacio)'
        );
        $stmt->execute([
            'titol' => $titol,
            'durada_min' => $duradaMin,
            'idioma_original' => $idiomaOriginal,
            'sinopsi' => $sinopsi,
            'cartell_img' => $cartellImg,
            'classificacio' => $classificacio,
        ]);

        return (int) Database::getConnection()->lastInsertId();
    }

    public static function update(
        int $id,
        string $titol,
        int $duradaMin,
        string $idiomaOriginal,
        ?string $sinopsi,
        ?string $cartellImg,
        ?string $classificacio
    ): void {
        $stmt = Database::getConnection()->prepare(
            'UPDATE Pelicula
             SET titol = :titol, durada_min = :durada_min, idioma_original = :idioma_original,
                 sinopsi = :sinopsi, cartell_img = :cartell_img, classificacio = :classificacio
             WHERE id = :id'
        );
        $stmt->execute([
            'titol' => $titol,
            'durada_min' => $duradaMin,
            'idioma_original' => $idiomaOriginal,
            'sinopsi' => $sinopsi,
            'cartell_img' => $cartellImg,
            'classificacio' => $classificacio,
            'id' => $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        try {
            $stmt = Database::getConnection()->prepare('DELETE FROM Pelicula WHERE id = :id');
            $stmt->execute(['id' => $id]);

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}