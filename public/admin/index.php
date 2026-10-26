<?php

require __DIR__ . '/../../src/bootstrap.php';

use HollywoodLand\Core\Auth;

Auth::requireRole('admin');

$usuari = Auth::user();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<?php $pageTitle = 'Panell admin — Hollywood Land'; require __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container flex-grow-1">
    <h1 class="mb-4">Panell d'administració</h1>
    <p>Benvingut, <?= htmlspecialchars($usuari['nom']) ?> (rol: <?= htmlspecialchars($usuari['rol']) ?>).</p>
    <p><a href="usuaris.php">Gestió d'usuaris</a></p>
    <p><a href="sales.php">Gestió de sales</a></p>
    <p><a href="pelicules.php">Gestió de pel·lícules</a></p>
    <p><a href="sessions.php">Gestió de sessions</a></p>
    <p><a href="../index.php">Tornar a l'inici</a></p>
</div>
</body>
</html>
