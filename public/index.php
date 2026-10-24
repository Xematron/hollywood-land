<?php

spl_autoload_register(function (string $class): void {
    $prefix = 'HollywoodLand\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $path = __DIR__ . '/../src/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($path)) {
        require $path;
    }
});

use HollywoodLand\Config\Database;

try {
    Database::getConnection();
    $status = 'Connexió a la BD OK';
} catch (\PDOException $e) {
    $status = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Hollywood Land</title>
</head>
<body>
    <h1>Hollywood Land</h1>
    <p><?= htmlspecialchars($status) ?></p>
</body>
</html>
