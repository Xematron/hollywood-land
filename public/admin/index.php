<?php

require __DIR__ . '/../../src/bootstrap.php';

use HollywoodLand\Core\Auth;

Auth::requireRole('admin');

$usuari = Auth::user();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Panell admin — Hollywood Land</title>
</head>
<body>
    <h1>Panell d'administració</h1>
    <p>Benvingut, <?= htmlspecialchars($usuari['nom']) ?> (rol: <?= htmlspecialchars($usuari['rol']) ?>).</p>
    <p><a href="usuaris.php">Gestió d'usuaris</a></p>
    <p><a href="sales.php">Gestió de sales</a></p>
    <p><a href="pelicules.php">Gestió de pel·lícules</a></p>
    <p><a href="sessions.php">Gestió de sessions</a></p>
    <p><a href="../index.php">Tornar a l'inici</a></p>
</body>
</html>
