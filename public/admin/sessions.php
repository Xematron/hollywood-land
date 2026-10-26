<?php

require __DIR__ . '/../../src/bootstrap.php';

use HollywoodLand\Core\Auth;
use HollywoodLand\Models\Pelicula;
use HollywoodLand\Models\Sala;
use HollywoodLand\Models\Sessio;

Auth::requireRole('admin');

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accio = $_POST['accio'] ?? '';

    if ($accio === 'crear' || $accio === 'actualitzar') {
        $id = (int) ($_POST['id'] ?? 0);
        $peliculaId = (int) ($_POST['pelicula_id'] ?? 0);
        $salaId = (int) ($_POST['sala_id'] ?? 0);
        $data = trim($_POST['data'] ?? '');
        $hora = trim($_POST['hora'] ?? '');
        $preu = (float) ($_POST['preu'] ?? 0);
        $idiomaSubtitols = trim($_POST['idioma_subtitols'] ?? '');
        $idiomaSubtitols = $idiomaSubtitols === '' ? null : $idiomaSubtitols;

        $original = $accio === 'actualitzar' ? Sessio::findById($id) : null;

        if ($accio === 'actualitzar' && $original !== null && $original['data'] < date('Y-m-d')) {
            $error = 'No es pot editar una sessió que ja ha passat.';
        } elseif ($peliculaId <= 0 || $salaId <= 0 || $data === '' || $hora === '' || $preu <= 0) {
            $error = 'Cal omplir pel·lícula, sala, data, hora i un preu superior a 0.';
        } elseif (!in_array(substr($hora, 3, 2), ['00', '15', '30', '45'], true)) {
            $error = "L'hora ha de ser en intervals de 15 minuts (00, 15, 30, 45).";
        } elseif ($data < date('Y-m-d')) {
            $error = 'No es pot programar una sessió amb data passada.';
        } elseif ($accio === 'crear') {
            Sessio::create($peliculaId, $salaId, $data, $hora, $preu, $idiomaSubtitols);
            $success = 'Sessió creada correctament.';
        } else {
            Sessio::update($id, $peliculaId, $salaId, $data, $hora, $preu, $idiomaSubtitols);
            $success = 'Sessió actualitzada correctament.';
        }
    } elseif ($accio === 'eliminar') {
        $id = (int) ($_POST['id'] ?? 0);

        if (Sessio::delete($id)) {
            $success = 'Sessió eliminada correctament.';
        } else {
            $error = 'No es pot eliminar: la sessió té entrades o debats associats.';
        }
    }
}

$sessions = Sessio::all();
$pelicules = Pelicula::all();
$sales = Sala::all();

$horesDisponibles = [];
for ($h = 0; $h < 24; $h++) {
    foreach (['00', '15', '30', '45'] as $m) {
        $horesDisponibles[] = sprintf('%02d:%s', $h, $m);
    }
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<?php $pageTitle = 'Gestió de sessions — Hollywood Land'; require __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container flex-grow-1">
    <h1 class="mb-4">Gestió de sessions</h1>
    <p><a href="index.php">Tornar al panell admin</a></p>

    <?php if ($error !== null): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if ($success !== null): ?>
        <p style="color:green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <h2>Sessions existents</h2>
    <?php foreach ($sessions as $sessio): ?>
        <?php $esPassada = $sessio['data'] < date('Y-m-d'); ?>
        <?php if ($esPassada): ?>
            <form method="post" style="display:flex; flex-wrap:wrap; gap:0.5rem; align-items:center; margin-bottom:0.75rem; border-bottom:1px solid #ccc; padding-bottom:0.5rem;">
                <input type="hidden" name="id" value="<?= (int) $sessio['id'] ?>">
                <span>
                    <?= htmlspecialchars($sessio['data']) ?> <?= htmlspecialchars(substr($sessio['hora'], 0, 5)) ?>h
                    — <?= htmlspecialchars($sessio['titol']) ?> (<?= htmlspecialchars($sessio['sala_nom']) ?>)
                    — <em>sessió passada, no editable</em>
                </span>
                <button type="submit" name="accio" value="eliminar" onclick="return confirm('Eliminar aquesta sessió?');">Eliminar</button>
            </form>
        <?php else: ?>
        <form method="post" style="display:flex; flex-wrap:wrap; gap:0.5rem; align-items:center; margin-bottom:0.75rem; border-bottom:1px solid #ccc; padding-bottom:0.5rem;">
            <input type="hidden" name="id" value="<?= (int) $sessio['id'] ?>">

            <label>Pel·lícula
                <select name="pelicula_id" required>
                    <?php foreach ($pelicules as $pelicula): ?>
                        <option value="<?= (int) $pelicula['id'] ?>" <?= (int) $pelicula['id'] === (int) $sessio['pelicula_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($pelicula['titol']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Sala
                <select name="sala_id" required>
                    <?php foreach ($sales as $sala): ?>
                        <option value="<?= (int) $sala['id'] ?>" <?= (int) $sala['id'] === (int) $sessio['sala_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($sala['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Data
                <input type="date" name="data" value="<?= htmlspecialchars($sessio['data']) ?>" required>
            </label>
            <label>Hora
                <select name="hora" required>
                    <?php foreach ($horesDisponibles as $h): ?>
                        <option value="<?= $h ?>" <?= $h === substr($sessio['hora'], 0, 5) ? 'selected' : '' ?>><?= $h ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Preu (€)
                <input type="number" name="preu" value="<?= htmlspecialchars($sessio['preu']) ?>" step="0.01" min="0.01" required>
            </label>
            <label>Subtítols
                <input type="text" name="idioma_subtitols" value="<?= htmlspecialchars($sessio['idioma_subtitols'] ?? '') ?>">
            </label>

            <button type="submit" name="accio" value="actualitzar">Desar</button>
            <button type="submit" name="accio" value="eliminar" onclick="return confirm('Eliminar aquesta sessió?');">Eliminar</button>
        </form>
        <?php endif; ?>
    <?php endforeach; ?>

    <h2>Crear sessió nova</h2>
    <form method="post">
        <label for="pelicula_id">Pel·lícula</label>
        <select id="pelicula_id" name="pelicula_id" required>
            <?php foreach ($pelicules as $pelicula): ?>
                <option value="<?= (int) $pelicula['id'] ?>"><?= htmlspecialchars($pelicula['titol']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="sala_id">Sala</label>
        <select id="sala_id" name="sala_id" required>
            <?php foreach ($sales as $sala): ?>
                <option value="<?= (int) $sala['id'] ?>"><?= htmlspecialchars($sala['nom']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="data">Data</label>
        <input type="date" id="data" name="data" min="<?= date('Y-m-d') ?>" required>

        <label for="hora">Hora</label>
        <select id="hora" name="hora" required>
            <?php foreach ($horesDisponibles as $h): ?>
                <option value="<?= $h ?>"><?= $h ?></option>
            <?php endforeach; ?>
        </select>

        <label for="preu">Preu (€)</label>
        <input type="number" id="preu" name="preu" step="0.01" min="0.01" required>

        <label for="idioma_subtitols">Subtítols</label>
        <input type="text" id="idioma_subtitols" name="idioma_subtitols">

        <button type="submit" name="accio" value="crear">Crear</button>
    </form>
</div>
</body>
</html>
