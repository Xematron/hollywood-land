<?php

require __DIR__ . '/../../src/bootstrap.php';

use HollywoodLand\Core\Auth;
use HollywoodLand\Models\Pelicula;

Auth::requireRole('treballador', 'admin');

$urlPanell = Auth::user()['rol'] === 'admin' ? 'index.php' : '../treballador/index.php';

$error = null;
$success = null;

function buit2null(string $valor): ?string
{
    return $valor === '' ? null : $valor;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accio = $_POST['accio'] ?? '';

    if ($accio === 'crear' || $accio === 'actualitzar') {
        $titol = trim($_POST['titol'] ?? '');
        $duradaMin = (int) ($_POST['durada_min'] ?? 0);
        $idiomaOriginal = trim($_POST['idioma_original'] ?? '');
        $sinopsi = buit2null(trim($_POST['sinopsi'] ?? ''));
        $cartellImg = buit2null(trim($_POST['cartell_img'] ?? ''));
        $classificacio = buit2null(trim($_POST['classificacio'] ?? ''));

        if ($titol === '' || $duradaMin <= 0 || $idiomaOriginal === '') {
            $error = 'Cal omplir títol, durada (> 0) i idioma original.';
        } elseif ($accio === 'crear') {
            Pelicula::create($titol, $duradaMin, $idiomaOriginal, $sinopsi, $cartellImg, $classificacio);
            $success = 'Pel·lícula creada correctament.';
        } else {
            $id = (int) ($_POST['id'] ?? 0);
            Pelicula::update($id, $titol, $duradaMin, $idiomaOriginal, $sinopsi, $cartellImg, $classificacio);
            $success = 'Pel·lícula actualitzada correctament.';
        }
    } elseif ($accio === 'eliminar') {
        $id = (int) ($_POST['id'] ?? 0);

        if (Pelicula::delete($id)) {
            $success = 'Pel·lícula eliminada correctament.';
        } else {
            $error = 'No es pot eliminar: la pel·lícula té sessions programades.';
        }
    }
}

$pelicules = Pelicula::all();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<?php $pageTitle = 'Gestió de pel·lícules — Hollywood Land'; require __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container flex-grow-1">
    <h1 class="mb-4">Gestió de pel·lícules</h1>
    <a href="<?= $urlPanell ?>" class="btn btn-outline-dark btn-sm mb-3">Tornar al panell</a>

    <?php if ($error !== null): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success !== null): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <h2 class="mt-4">Pel·lícules existents</h2>
    <?php foreach ($pelicules as $pelicula): ?>
        <form method="post" class="d-flex flex-wrap gap-2 align-items-end mb-3 pb-3 border-bottom">
            <input type="hidden" name="id" value="<?= (int) $pelicula['id'] ?>">

            <div>
                <label class="form-label small mb-0">Títol</label>
                <input type="text" name="titol" value="<?= htmlspecialchars($pelicula['titol']) ?>" class="form-control form-control-sm" required>
            </div>
            <div>
                <label class="form-label small mb-0">Durada (min)</label>
                <input type="number" name="durada_min" value="<?= (int) $pelicula['durada_min'] ?>" min="1" class="form-control form-control-sm" style="width: 6rem;" required>
            </div>
            <div>
                <label class="form-label small mb-0">Idioma original</label>
                <input type="text" name="idioma_original" value="<?= htmlspecialchars($pelicula['idioma_original']) ?>" class="form-control form-control-sm" required>
            </div>
            <div>
                <label class="form-label small mb-0">Classificació</label>
                <input type="text" name="classificacio" value="<?= htmlspecialchars($pelicula['classificacio'] ?? '') ?>" class="form-control form-control-sm" style="width: 6rem;">
            </div>
            <div>
                <label class="form-label small mb-0">Cartell (URL)</label>
                <input type="text" name="cartell_img" value="<?= htmlspecialchars($pelicula['cartell_img'] ?? '') ?>" class="form-control form-control-sm">
            </div>
            <div>
                <label class="form-label small mb-0">Sinopsi</label>
                <textarea name="sinopsi" rows="1" class="form-control form-control-sm"><?= htmlspecialchars($pelicula['sinopsi'] ?? '') ?></textarea>
            </div>

            <button type="submit" name="accio" value="actualitzar" class="btn btn-sm btn-dark">Desar</button>
            <button type="submit" name="accio" value="eliminar" class="btn btn-sm btn-outline-dark" onclick="return confirm('Eliminar aquesta pel·lícula?');">Eliminar</button>
        </form>
    <?php endforeach; ?>

    <h2 class="mt-5">Crear pel·lícula nova</h2>
    <form method="post" style="max-width: 480px;">
        <div class="mb-3">
            <label for="titol" class="form-label">Títol</label>
            <input type="text" id="titol" name="titol" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="durada_min" class="form-label">Durada (min)</label>
            <input type="number" id="durada_min" name="durada_min" min="1" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="idioma_original" class="form-label">Idioma original</label>
            <input type="text" id="idioma_original" name="idioma_original" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="classificacio" class="form-label">Classificació</label>
            <input type="text" id="classificacio" name="classificacio" class="form-control">
        </div>
        <div class="mb-3">
            <label for="cartell_img" class="form-label">Cartell (URL)</label>
            <input type="text" id="cartell_img" name="cartell_img" class="form-control">
        </div>
        <div class="mb-3">
            <label for="sinopsi" class="form-label">Sinopsi</label>
            <textarea id="sinopsi" name="sinopsi" rows="3" class="form-control"></textarea>
        </div>
        <button type="submit" name="accio" value="crear" class="btn btn-dark">Crear</button>
    </form>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
