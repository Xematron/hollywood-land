<?php

require __DIR__ . '/../../src/bootstrap.php';

use HollywoodLand\Core\Auth;

Auth::requireRole('treballador', 'admin');

$usuari = Auth::user();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<?php $pageTitle = 'Zona Treballador — Hollywood Land'; require __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container flex-grow-1">
    <h1 class="mb-4">Zona Treballador</h1>
    <p class="mb-4">Benvingut, <?= htmlspecialchars($usuari['nom']) ?> (rol: <?= htmlspecialchars($usuari['rol']) ?>).</p>

    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="../admin/sales.php" class="btn btn-dark">Gestió de sales</a>
        <a href="../admin/pelicules.php" class="btn btn-dark">Gestió de pel·lícules</a>
        <a href="../admin/sessions.php" class="btn btn-dark">Gestió de sessions</a>
    </div>

    <a href="../index.php" class="btn btn-outline-dark btn-sm">Tornar a l'inici</a>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
