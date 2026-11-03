<?php

require __DIR__ . '/../src/bootstrap.php';

use HollywoodLand\Core\Auth;
use HollywoodLand\Models\Butaca;
use HollywoodLand\Models\Entrada;
use HollywoodLand\Models\Sessio;

Auth::requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /cartellera.php');
    exit;
}

$sessioId = (int) ($_POST['sessio_id'] ?? 0);
$sessio = Sessio::findDetallById($sessioId);

if ($sessio === null) {
    http_response_code(404);
    $missatge404 = 'Sessió no trobada.';
    require __DIR__ . '/partials/error404.php';
    exit;
}

$butacaIdsSeleccionades = array_map('intval', $_POST['butaca_id'] ?? []);
$ocupades = Entrada::ocupades($sessioId);

$butaquesDisponibles = [];
foreach (Butaca::perSala($sessio['sala_id']) as $butaca) {
    if ($butaca['operativa']) {
        $butaquesDisponibles[(int) $butaca['id']] = $butaca;
    }
}

$seleccionadesValides = [];
$jaNoDisponibles = [];

foreach ($butacaIdsSeleccionades as $butacaId) {
    if (!isset($butaquesDisponibles[$butacaId]) || in_array($butacaId, $ocupades, true)) {
        $jaNoDisponibles[] = $butacaId;
        continue;
    }

    $seleccionadesValides[] = $butaquesDisponibles[$butacaId];
}

$total = count($seleccionadesValides) * (float) $sessio['preu'];
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<?php $pageTitle = 'Confirma la compra — Hollywood Land'; require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/partials/navbar.php'; ?>

<div class="container flex-grow-1">
    <h1 class="mb-4">Confirma la compra</h1>

    <p>
        <?= htmlspecialchars($sessio['titol']) ?> — <?= htmlspecialchars($sessio['data']) ?>
        <?= substr($sessio['hora'], 0, 5) ?> · <?= htmlspecialchars($sessio['sala_nom']) ?>
    </p>

    <?php if (!empty($jaNoDisponibles)): ?>
        <div class="alert alert-warning">
            Algunes butaques seleccionades ja no estan disponibles i s'han tret de la selecció.
        </div>
    <?php endif; ?>

    <?php if (empty($seleccionadesValides)): ?>
        <div class="alert alert-danger">Cap de les butaques seleccionades ja és disponible.</div>
        <a class="btn btn-dark" href="entrades.php?sessio_id=<?= $sessioId ?>">Tornar a triar butaques</a>
    <?php else: ?>
        <ul>
            <?php foreach ($seleccionadesValides as $b): ?>
                <li>Fila <?= htmlspecialchars($b['fila']) ?>, butaca <?= (int) $b['numero'] ?></li>
            <?php endforeach; ?>
        </ul>

        <p>
            Butaques: <strong><?= count($seleccionadesValides) ?></strong>
            · Total a pagar: <strong><?= number_format($total, 2) ?> €</strong>
        </p>

        <form method="post" action="entrades_confirmar.php" class="d-inline">
            <input type="hidden" name="sessio_id" value="<?= $sessioId ?>">
            <?php foreach ($seleccionadesValides as $b): ?>
                <input type="hidden" name="butaca_id[]" value="<?= (int) $b['id'] ?>">
            <?php endforeach; ?>
            <button type="submit" class="btn btn-dark">Confirmar i pagar</button>
        </form>
        <a class="btn btn-outline-dark" href="entrades.php?sessio_id=<?= $sessioId ?>">Tornar a triar butaques</a>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
