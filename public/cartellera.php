<?php

require __DIR__ . '/../src/bootstrap.php';

use HollywoodLand\Models\Sessio;

$sessions = Sessio::cartellera();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<?php $pageTitle = 'Cartellera — Hollywood Land'; require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/partials/navbar.php'; ?>

<div class="container flex-grow-1">
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
                    <th></th>
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
                    <td><a class="btn btn-sm btn-dark" href="entrades.php?sessio_id=<?= (int) $s['id'] ?>">Comprar</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
