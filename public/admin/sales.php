<?php

require __DIR__ . '/../../src/bootstrap.php';

use HollywoodLand\Core\Auth;
use HollywoodLand\Models\Sala;

Auth::requireRole('treballador', 'admin');

$urlPanell = Auth::user()['rol'] === 'admin' ? 'index.php' : '../treballador/index.php';

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accio = $_POST['accio'] ?? '';

    if ($accio === 'actualitzar') {
        $id = (int) ($_POST['id'] ?? 0);
        $nom = trim($_POST['nom'] ?? '');

        if ($nom === '') {
            $error = 'El nom no pot estar buit.';
        } else {
            Sala::updateNom($id, $nom);
            $success = 'Sala actualitzada correctament.';
        }
    }
}

$sales = Sala::all();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<?php $pageTitle = 'Gestió de sales — Hollywood Land'; require __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container flex-grow-1">
    <h1 class="mb-4">Gestió de sales</h1>
    <a href="<?= $urlPanell ?>" class="btn btn-outline-dark btn-sm mb-3">Tornar al panell</a>

    <?php if ($error !== null): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success !== null): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <h2 class="mt-4">Sales</h2>
    <?php foreach ($sales as $sala): ?>
        <form method="post" class="d-flex flex-wrap gap-2 align-items-center mb-2 pb-2 border-bottom">
            <input type="hidden" name="id" value="<?= (int) $sala['id'] ?>">
            <input type="text" name="nom" value="<?= htmlspecialchars($sala['nom']) ?>" class="form-control form-control-sm w-auto" required>
            <span>
                Capacitat: <?= (int) $sala['capacitat'] ?>
                <?php if ((int) $sala['fora_servei'] > 0): ?>
                    (<?= (int) $sala['fora_servei'] ?> fora de servei)
                <?php endif; ?>
            </span>
            <button type="submit" name="accio" value="actualitzar" class="btn btn-sm btn-dark">Desar</button>
            <a href="butaques.php?sala_id=<?= (int) $sala['id'] ?>" class="btn btn-sm btn-outline-dark">Gestionar butaques</a>
        </form>
    <?php endforeach; ?>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
