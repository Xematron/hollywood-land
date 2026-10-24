<?php

require __DIR__ . '/../src/bootstrap.php';

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
    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Sessió iniciada com <?= htmlspecialchars($_SESSION['nom']) ?> (<?= htmlspecialchars($_SESSION['rol']) ?>)</p>
        <p><a href="logout.php">Tancar sessió</a></p>
    <?php else: ?>
        <p><a href="login.php">Iniciar sessió</a></p>
    <?php endif; ?>
</body>
</html>
