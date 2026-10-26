<?php

require __DIR__ . '/../../src/bootstrap.php';

use HollywoodLand\Core\Auth;
use HollywoodLand\Models\Sala;

Auth::requireRole('admin');

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
    <meta charset="UTF-8">
    <title>Gestió de sales — Hollywood Land</title>
</head>
<body>
    <h1>Gestió de sales</h1>
    <p><a href="index.php">Tornar al panell admin</a></p>

    <?php if ($error !== null): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if ($success !== null): ?>
        <p style="color:green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <h2>Sales</h2>
    <?php foreach ($sales as $sala): ?>
        <form method="post" style="display:flex; gap:0.5rem; align-items:center; margin-bottom:0.5rem;">
            <input type="hidden" name="id" value="<?= (int) $sala['id'] ?>">
            <input type="text" name="nom" value="<?= htmlspecialchars($sala['nom']) ?>" required>
            <span>
                Capacitat: <?= (int) $sala['capacitat'] ?>
                <?php if ((int) $sala['fora_servei'] > 0): ?>
                    (<?= (int) $sala['fora_servei'] ?> fora de servei)
                <?php endif; ?>
            </span>
            <button type="submit" name="accio" value="actualitzar">Desar</button>
            <a href="butaques.php?sala_id=<?= (int) $sala['id'] ?>">Gestionar butaques</a>
        </form>
    <?php endforeach; ?>
</body>
</html>
