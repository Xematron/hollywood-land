<?php

require __DIR__ . '/../../src/bootstrap.php';

use HollywoodLand\Core\Auth;
use HollywoodLand\Models\Pelicula;
use HollywoodLand\Models\Sala;
use HollywoodLand\Models\Sessio;

Auth::requireRole('treballador', 'admin');

$urlPanell = Auth::user()['rol'] === 'admin' ? 'index.php' : '../treballador/index.php';

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
    <a href="<?= $urlPanell ?>" class="btn btn-outline-dark btn-sm mb-3">Tornar al panell</a>

    <?php if ($error !== null): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success !== null): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <h2 class="mt-4">Sessions existents</h2>
    <?php foreach ($sessions as $sessio): ?>
        <?php $esPassada = $sessio['data'] < date('Y-m-d'); ?>
        <?php if ($esPassada): ?>
            <form method="post" class="d-flex flex-wrap gap-2 align-items-center mb-2 pb-2 border-bottom">
                <input type="hidden" name="id" value="<?= (int) $sessio['id'] ?>">
                <span>
                    <?= htmlspecialchars($sessio['data']) ?> <?= htmlspecialchars(substr($sessio['hora'], 0, 5)) ?>h
                    — <?= htmlspecialchars($sessio['titol']) ?> (<?= htmlspecialchars($sessio['sala_nom']) ?>)
                    — <em>sessió passada, no editable</em>
                </span>
                <button type="submit" name="accio" value="eliminar" class="btn btn-sm btn-outline-dark" onclick="return confirm('Eliminar aquesta sessió?');">Eliminar</button>
            </form>
        <?php else: ?>
        <form method="post" class="d-flex flex-wrap gap-2 align-items-end mb-3 pb-3 border-bottom">
            <input type="hidden" name="id" value="<?= (int) $sessio['id'] ?>">

            <div>
                <label class="form-label small mb-0">Pel·lícula</label>
                <select name="pelicula_id" class="form-select form-select-sm" required>
                    <?php foreach ($pelicules as $pelicula): ?>
                        <option value="<?= (int) $pelicula['id'] ?>" <?= (int) $pelicula['id'] === (int) $sessio['pelicula_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($pelicula['titol']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="form-label small mb-0">Sala</label>
                <select name="sala_id" class="form-select form-select-sm" required>
                    <?php foreach ($sales as $sala): ?>
                        <option value="<?= (int) $sala['id'] ?>" <?= (int) $sala['id'] === (int) $sessio['sala_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($sala['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="form-label small mb-0">Data</label>
                <input type="date" name="data" value="<?= htmlspecialchars($sessio['data']) ?>" class="form-control form-control-sm" required>
            </div>
            <div>
                <label class="form-label small mb-0">Hora</label>
                <select name="hora" class="form-select form-select-sm" required>
                    <?php foreach ($horesDisponibles as $h): ?>
                        <option value="<?= $h ?>" <?= $h === substr($sessio['hora'], 0, 5) ? 'selected' : '' ?>><?= $h ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="form-label small mb-0">Preu (€)</label>
                <input type="number" name="preu" value="<?= htmlspecialchars($sessio['preu']) ?>" step="0.01" min="0.01" class="form-control form-control-sm" style="width: 6rem;" required>
            </div>
            <div>
                <label class="form-label small mb-0">Subtítols</label>
                <input type="text" name="idioma_subtitols" value="<?= htmlspecialchars($sessio['idioma_subtitols'] ?? '') ?>" class="form-control form-control-sm">
            </div>

            <button type="submit" name="accio" value="actualitzar" class="btn btn-sm btn-dark">Desar</button>
            <button type="submit" name="accio" value="eliminar" class="btn btn-sm btn-outline-dark" onclick="return confirm('Eliminar aquesta sessió?');">Eliminar</button>
        </form>
        <?php endif; ?>
    <?php endforeach; ?>

    <h2 class="mt-5">Crear sessió nova</h2>
    <form method="post" class="d-flex flex-wrap gap-2 align-items-end">
        <div>
            <label for="pelicula_id" class="form-label small mb-0">Pel·lícula</label>
            <select id="pelicula_id" name="pelicula_id" class="form-select form-select-sm" required>
                <?php foreach ($pelicules as $pelicula): ?>
                    <option value="<?= (int) $pelicula['id'] ?>"><?= htmlspecialchars($pelicula['titol']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="sala_id" class="form-label small mb-0">Sala</label>
            <select id="sala_id" name="sala_id" class="form-select form-select-sm" required>
                <?php foreach ($sales as $sala): ?>
                    <option value="<?= (int) $sala['id'] ?>"><?= htmlspecialchars($sala['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="data" class="form-label small mb-0">Data</label>
            <input type="date" id="data" name="data" min="<?= date('Y-m-d') ?>" class="form-control form-control-sm" required>
        </div>
        <div>
            <label for="hora" class="form-label small mb-0">Hora</label>
            <select id="hora" name="hora" class="form-select form-select-sm" required>
                <?php foreach ($horesDisponibles as $h): ?>
                    <option value="<?= $h ?>"><?= $h ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="preu" class="form-label small mb-0">Preu (€)</label>
            <input type="number" id="preu" name="preu" step="0.01" min="0.01" class="form-control form-control-sm" style="width: 6rem;" required>
        </div>
        <div>
            <label for="idioma_subtitols" class="form-label small mb-0">Subtítols</label>
            <input type="text" id="idioma_subtitols" name="idioma_subtitols" class="form-control form-control-sm">
        </div>

        <button type="submit" name="accio" value="crear" class="btn btn-dark">Crear</button>
    </form>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
