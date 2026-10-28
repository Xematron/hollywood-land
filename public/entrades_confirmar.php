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
    exit('Sessió no trobada.');
}

$sessioInici = DateTime::createFromFormat('Y-m-d H:i:s', $sessio['data'] . ' ' . $sessio['hora']);

if ($sessioInici < new DateTime()) {
    http_response_code(400);
    exit('Aquesta sessió ja ha passat, no es poden comprar entrades.');
}

$butacaIdsSeleccionades = array_map('intval', $_POST['butaca_id'] ?? []);
$usuariId = (int) Auth::user()['id'];

$butaquesValides = [];
foreach (Butaca::perSala($sessio['sala_id']) as $butaca) {
    if ($butaca['operativa']) {
        $butaquesValides[(int) $butaca['id']] = $butaca;
    }
}

$comprades = [];
$fallides = [];

foreach ($butacaIdsSeleccionades as $butacaId) {
    if (!isset($butaquesValides[$butacaId])) {
        $fallides[] = ['numero' => null, 'motiu' => 'no disponible'];
        continue;
    }

    $butaca = $butaquesValides[$butacaId];

    try {
        Entrada::create($sessioId, $usuariId, $butacaId, (float) $sessio['preu']);
        $comprades[] = $butaca;
    } catch (PDOException $e) {
        $fallides[] = ['numero' => $butaca['fila'] . $butaca['numero'], 'motiu' => 'ja venuda'];
    }
}

$totalPagat = count($comprades) * (float) $sessio['preu'];
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<?php $pageTitle = 'Compra confirmada — Hollywood Land'; require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/partials/navbar.php'; ?>

<div class="container flex-grow-1">
    <h1 class="mb-4">Resum de la compra</h1>

    <?php if (empty($comprades) && empty($fallides)): ?>
        <div class="alert alert-warning">No has seleccionat cap butaca.</div>
        <a class="btn btn-dark" href="entrades.php?sessio_id=<?= $sessioId ?>">Tornar a triar butaques</a>
    <?php else: ?>
        <p>
            <?= htmlspecialchars($sessio['titol']) ?> — <?= htmlspecialchars($sessio['data']) ?>
            <?= substr($sessio['hora'], 0, 5) ?> · <?= htmlspecialchars($sessio['sala_nom']) ?>
        </p>

        <?php if (!empty($comprades)): ?>
            <div class="alert alert-success">
                Compra confirmada: butaques
                <?php foreach ($comprades as $b): ?>
                    <strong><?= htmlspecialchars($b['fila'] . $b['numero']) ?></strong><?= $b !== end($comprades) ? ',' : '' ?>
                <?php endforeach; ?>
                — Total pagat: <strong><?= number_format($totalPagat, 2) ?> €</strong>
            </div>
        <?php endif; ?>

        <?php if (!empty($fallides)): ?>
            <div class="alert alert-danger">
                Algunes butaques no s'han pogut reservar:
                <?php foreach ($fallides as $f): ?>
                    <?= $f['numero'] !== null ? htmlspecialchars($f['numero']) . ' (' . $f['motiu'] . ')' : $f['motiu'] ?>;
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <a class="btn btn-dark" href="les-meves-entrades.php">Les meves entrades</a>
        <a class="btn btn-outline-dark" href="cartellera.php">Tornar a la cartellera</a>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
