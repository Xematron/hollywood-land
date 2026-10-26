<?php

require __DIR__ . '/../../src/bootstrap.php';

use HollywoodLand\Core\Auth;
use HollywoodLand\Models\Pelicula;

Auth::requireRole('admin');

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
    <p><a href="index.php">Tornar al panell admin</a></p>

    <?php if ($error !== null): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if ($success !== null): ?>
        <p style="color:green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <h2>Pel·lícules existents</h2>
    <?php foreach ($pelicules as $pelicula): ?>
        <form method="post" style="display:flex; flex-wrap:wrap; gap:0.5rem; align-items:center; margin-bottom:0.75rem; border-bottom:1px solid #ccc; padding-bottom:0.5rem;">
            <input type="hidden" name="id" value="<?= (int) $pelicula['id'] ?>">

            <label>Títol
                <input type="text" name="titol" value="<?= htmlspecialchars($pelicula['titol']) ?>" required>
            </label>
            <label>Durada (min)
                <input type="number" name="durada_min" value="<?= (int) $pelicula['durada_min'] ?>" min="1" required>
            </label>
            <label>Idioma original
                <input type="text" name="idioma_original" value="<?= htmlspecialchars($pelicula['idioma_original']) ?>" required>
            </label>
            <label>Classificació
                <input type="text" name="classificacio" value="<?= htmlspecialchars($pelicula['classificacio'] ?? '') ?>">
            </label>
            <label>Cartell (URL)
                <input type="text" name="cartell_img" value="<?= htmlspecialchars($pelicula['cartell_img'] ?? '') ?>">
            </label>
            <label>Sinopsi
                <textarea name="sinopsi" rows="2" cols="30"><?= htmlspecialchars($pelicula['sinopsi'] ?? '') ?></textarea>
            </label>

            <button type="submit" name="accio" value="actualitzar">Desar</button>
            <button type="submit" name="accio" value="eliminar" onclick="return confirm('Eliminar aquesta pel·lícula?');">Eliminar</button>
        </form>
    <?php endforeach; ?>

    <h2>Crear pel·lícula nova</h2>
    <form method="post">
        <label for="titol">Títol</label>
        <input type="text" id="titol" name="titol" required>

        <label for="durada_min">Durada (min)</label>
        <input type="number" id="durada_min" name="durada_min" min="1" required>

        <label for="idioma_original">Idioma original</label>
        <input type="text" id="idioma_original" name="idioma_original" required>

        <label for="classificacio">Classificació</label>
        <input type="text" id="classificacio" name="classificacio">

        <label for="cartell_img">Cartell (URL)</label>
        <input type="text" id="cartell_img" name="cartell_img">

        <label for="sinopsi">Sinopsi</label>
        <textarea id="sinopsi" name="sinopsi" rows="3" cols="40"></textarea>

        <button type="submit" name="accio" value="crear">Crear</button>
    </form>
</div>
</body>
</html>
