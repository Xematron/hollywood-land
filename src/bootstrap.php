<?php

// En Docker local, les variables DB_* les injecta docker-compose.yml.
// En hosting compartit (sense Docker) no existeixen; env.local.php
// (no versionat) les defineix amb putenv() si el fitxer hi és present.
if (is_file(__DIR__ . '/Config/env.local.php')) {
    require __DIR__ . '/Config/env.local.php';
}

// El hosting té el rellotge en una altra zona horària; fixem la del cinema
// perquè date()/DateTime (i les comprovacions de "data passada") siguin correctes.
date_default_timezone_set('Europe/Madrid');

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
