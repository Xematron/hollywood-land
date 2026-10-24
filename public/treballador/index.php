<?php

require __DIR__ . '/../../src/bootstrap.php';

use HollywoodLand\Core\Auth;

Auth::requireRole('taquiller', 'admin');

$usuari = Auth::user();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Zona Treballador — Hollywood Land</title>
</head>
<body>
    <h1>Zona Treballador</h1>
    <p>Benvingut, <?= htmlspecialchars($usuari['nom']) ?> (rol: <?= htmlspecialchars($usuari['rol']) ?>).</p>
    <p>Pendent de desenvolupar: venda d'entrades al taulell.</p>
    <p><a href="../index.php">Tornar a l'inici</a></p>
</body>
</html>
