<?php

require __DIR__ . '/../src/bootstrap.php';

use HollywoodLand\Core\Auth;
use HollywoodLand\Models\Entrada;

Auth::requireLogin();

$entrades = Entrada::findByUsuari((int) Auth::user()['id']);
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<?php $pageTitle = 'Les meves entrades — Hollywood Land'; require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/partials/navbar.php'; ?>

<div class="container flex-grow-1">
    <h1 class="mb-4">Les meves entrades</h1>

    <?php if (empty($entrades)): ?>
        <p>Encara no has comprat cap entrada. <a href="cartellera.php">Consulta la cartellera</a>.</p>
    <?php else: ?>
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Pel·lícula</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Sala</th>
                    <th>Butaca</th>
                    <th>Preu</th>
                    <th>Comprada</th>
                    <th>Estat</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entrades as $e): ?>
                <tr>
                    <td><?= htmlspecialchars($e['titol']) ?></td>
                    <td><?= htmlspecialchars($e['data']) ?></td>
                    <td><?= substr($e['hora'], 0, 5) ?></td>
                    <td><?= htmlspecialchars($e['sala_nom']) ?></td>
                    <td><?= htmlspecialchars($e['fila'] . $e['numero']) ?></td>
                    <td><?= number_format((float) $e['preu_pagat'], 2) ?> €</td>
                    <td><?= htmlspecialchars($e['data_compra']) ?></td>
                    <td>
                        <span class="badge <?= $e['estat'] === 'pagada' ? 'text-bg-success' : 'text-bg-secondary' ?>">
                            <?= htmlspecialchars($e['estat']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
