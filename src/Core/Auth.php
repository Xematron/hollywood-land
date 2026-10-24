<?php

namespace HollywoodLand\Core;

class Auth
{
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: /login.php');
            exit;
        }
    }

    public static function requireRole(string ...$rols): void
    {
        self::requireLogin();

        if (!in_array($_SESSION['rol'], $rols, true)) {
            http_response_code(403);
            exit('Accés denegat: no tens permisos per veure aquesta pàgina.');
        }
    }

    public static function user(): ?array
    {
        if (!self::isLoggedIn()) {
            return null;
        }

        return [
            'id' => $_SESSION['user_id'],
            'nom' => $_SESSION['nom'],
            'rol' => $_SESSION['rol'],
        ];
    }
}
