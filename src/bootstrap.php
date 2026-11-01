<?php

// En Docker local, les variables DB_* les injecta docker-compose.yml.
// En hosting compartit (sense Docker) no existeixen; env.local.php
// (no versionat) les defineix amb putenv() si el fitxer hi és present.
if (is_file(__DIR__ . '/Config/env.local.php')) {
    require __DIR__ . '/Config/env.local.php';
}

spl_autoload_register(function (string $class): void {
    $prefix = 'HollywoodLand\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $path = __DIR__ . '/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($path)) {
        require $path;
    }
});

session_start();
