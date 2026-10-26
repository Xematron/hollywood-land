<?php

require __DIR__ . '/../../src/bootstrap.php';

use HollywoodLand\Core\Auth;
use HollywoodLand\Models\Butaca;
use HollywoodLand\Models\Sala;

Auth::requireRole('admin');

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
    <p><a href="sales.php">Tornar a gestió de sales</a></p>

    <?php if ($success !== null): ?>
        <p style="color:green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <p>Desmarca les butaques temporalment fora de servei (obres, desperfectes...). No afecta les entrades ja venudes.</p>

    <form method="post">
        <?php foreach ($files as $fila => $butaquesFila): ?>
            <p>
                <strong>Fila <?= htmlspecialchars($fila) ?>:</strong>
                <?php foreach ($butaquesFila as $butaca): ?>
                    <label style="margin-right:0.75rem;">
                        <input
                            type="checkbox"
                            name="operativa[]"
                            value="<?= (int) $butaca['id'] ?>"
                            <?= $butaca['operativa'] ? 'checked' : '' ?>
                        >
                        <?= (int) $butaca['numero'] ?>
                    </label>
                <?php endforeach; ?>
            </p>
        <?php endforeach; ?>

        <button type="submit" name="accio" value="actualitzar">Desar</button>
    </form>
</div>
</body>
</html>
