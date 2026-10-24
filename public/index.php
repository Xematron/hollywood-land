<?php

require __DIR__ . '/../src/bootstrap.php';

use HollywoodLand\Models\Sessio;

$sessions = Sessio::cartellera();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Hollywood Land</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand">Hollywood Land</span>
        <div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="text-light me-3"><?= htmlspecialchars($_SESSION['nom']) ?> (<?= htmlspecialchars($_SESSION['rol']) ?>)</span>
                <?php if ($_SESSION['rol'] === 'admin'): ?>
                    <a class="btn btn-sm btn-outline-light me-2" href="admin/index.php">Zona Admin</a>
                <?php endif; ?>
                <?php if (in_array($_SESSION['rol'], ['taquiller', 'admin'], true)): ?>
                    <a class="btn btn-sm btn-outline-light me-2" href="treballador/index.php">Zona Treballador</a>
                <?php endif; ?>
                <a class="btn btn-sm btn-outline-light" href="logout.php">Tancar sessió</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container">
    <h1 class="mb-4">Cartellera</h1>
    <?php if (empty($sessions)): ?>
        <p>No hi ha sessions properes.</p>
    <?php else: ?>
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Pel·lícula</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Sala</th>
                    <th>Preu</th>
                    <th>Subtítols</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sessions as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['titol']) ?> <small class="text-muted">(<?= (int) $s['durada_min'] ?> min)</small></td>
                    <td><?= htmlspecialchars($s['data']) ?></td>
                    <td><?= substr($s['hora'], 0, 5) ?></td>
                    <td><?= htmlspecialchars($s['sala_nom']) ?></td>
                    <td><?= number_format((float) $s['preu'], 2) ?> €</td>
                    <td><?= htmlspecialchars($s['idioma_subtitols'] ?? '-') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>