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
    <p class="mb-4">Benvingut, <?= htmlspecialchars($usuari['nom']) ?> (rol: <?= htmlspecialchars($usuari['rol']) ?>).</p>

    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="usuaris.php" class="btn btn-dark">Gestió d'usuaris</a>
        <a href="sales.php" class="btn btn-dark">Gestió de sales</a>
        <a href="pelicules.php" class="btn btn-dark">Gestió de pel·lícules</a>
        <a href="sessions.php" class="btn btn-dark">Gestió de sessions</a>
        <a href="informes.php" class="btn btn-dark">Informes i estadístiques</a>
    </div>

    <a href="../index.php" class="btn btn-outline-dark btn-sm">Tornar a l'inici</a>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
