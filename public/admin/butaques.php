<?php

require __DIR__ . '/../../src/bootstrap.php';

use HollywoodLand\Core\Auth;
use HollywoodLand\Models\Butaca;
use HollywoodLand\Models\Sala;

Auth::requireRole('treballador', 'admin');

$salaId = (int) ($_GET['sala_id'] ?? 0);
$sala = Sala::findById($salaId);

if ($sala === null) {
    http_response_code(404);
    exit('Sala no trobada.');
}

$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accio'] ?? '') === 'actualitzar') {
    $operatives = $_POST['operativa'] ?? [];

    foreach (Butaca::perSala($salaId) as $butaca) {
        $marcada = in_array((string) $butaca['id'], $operatives, true);
        Butaca::setOperativa((int) $butaca['id'], $marcada);
    }

    $success = 'Butaques actualitzades correctament.';
}

$butaques = Butaca::perSala($salaId);

$files = [];
foreach ($butaques as $butaca) {
    $files[$butaca['fila']][] = $butaca;
}
ksort($files);
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<?php $pageTitle = 'Butaques de ' . $sala['nom'] . ' — Hollywood Land'; require __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container flex-grow-1">
    <h1 class="mb-4">Butaques — <?= htmlspecialchars($sala['nom']) ?></h1>
    <a href="sales.php" class="btn btn-outline-dark btn-sm mb-3">Tornar a gestió de sales</a>

    <?php if ($success !== null): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <p>Desmarca les butaques temporalment fora de servei (obres, desperfectes...). No afecta les entrades ja venudes.</p>

    <form method="post">
        <?php foreach ($files as $fila => $butaquesFila): ?>
            <p>
                <strong>Fila <?= htmlspecialchars($fila) ?>:</strong>
                <?php foreach ($butaquesFila as $butaca): ?>
                    <label class="form-check form-check-inline">
                        <input
                            type="checkbox"
                            name="operativa[]"
                            value="<?= (int) $butaca['id'] ?>"
                            class="form-check-input"
                            <?= $butaca['operativa'] ? 'checked' : '' ?>
                        >
                        <span class="form-check-label"><?= (int) $butaca['numero'] ?></span>
                    </label>
                <?php endforeach; ?>
            </p>
        <?php endforeach; ?>

        <button type="submit" name="accio" value="actualitzar" class="btn btn-dark">Desar</button>
    </form>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
