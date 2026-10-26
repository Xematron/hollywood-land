<?php

require __DIR__ . '/../../src/bootstrap.php';

use HollywoodLand\Core\Auth;

Auth::requireRole('taquiller', 'admin');

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
    <p>Benvingut, <?= htmlspecialchars($usuari['nom']) ?> (rol: <?= htmlspecialchars($usuari['rol']) ?>).</p>
    <p>Pendent de desenvolupar: venda d'entrades al taulell.</p>
    <p><a href="../index.php">Tornar a l'inici</a></p>
</div>
</body>
</html>
