<?php

namespace HollywoodLand\Models;

use HollywoodLand\Config\Database;
use PDO;

class Usuari
{
    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM Usuari WHERE email = :email'
        );
        $stmt->execute(['email' => $email]);
        $usuari = $stmt->fetch();

        return $usuari ?: null;
    }

    public static function findById(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM Usuari WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);
        $usuari = $stmt->fetch();

        return $usuari ?: null;
    }

    public static function create(string $nom, string $email, string $password, string $rol = 'client'): int
    {
        $stmt = Database::getConnection()->prepare(
            'INSERT INTO Usuari (nom, email, password_hash, rol) VALUES (:nom, :email, :password_hash, :rol)'
        );
        $stmt->execute([
            'nom' => $nom,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'rol' => $rol,
        ]);

        return (int) Database::getConnection()->lastInsertId();
    }

    public static function verifyCredentials(string $email, string $password): ?array
    {
        $usuari = self::findByEmail($email);

        if ($usuari === null || !password_verify($password, $usuari['password_hash'])) {
            return null;
        }

        return $usuari;
    }
}
