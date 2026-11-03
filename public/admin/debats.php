<?php

require __DIR__ . '/../../src/bootstrap.php';

use HollywoodLand\Core\Auth;
use HollywoodLand\Models\Debat;
use HollywoodLand\Models\Sessio;

Auth::requireRole('treballador', 'admin');

$urlPanell = Auth::user()['rol'] === 'admin' ? 'index.php' : '../treballador/index.php';

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accio = $_POST['accio'] ?? '';

    if ($accio === 'crear' || $accio === 'actualitzar') {
        $id = (int) ($_POST['id'] ?? 0);
        $sessioId = (int) ($_POST['sessio_id'] ?? 0);
        $titol = trim($_POST['titol'] ?? '');
        $titol = $titol === '' ? null : $titol;
        $descripcio = trim($_POST['descripcio'] ?? '');
        $descripcio = $descripcio === '' ? null : $descripcio;
        $aforament = trim($_POST['aforament'] ?? '');
        $aforament = $aforament === '' ? null : (int) $aforament;
        $duradaMin = (int) ($_POST['durada_min'] ?? 0);

        if ($sessioId <= 0 || $duradaMin <= 0) {
            $error = 'Cal triar una sessió i indicar una durada superior a 0.';
        } elseif (Sessio::findById($sessioId) === null) {
            $error = 'La sessió seleccionada no existeix.';
        } elseif ($accio === 'crear') {
            Debat::create($sessioId, $titol, $descripcio, $aforament, $duradaMin);
            $success = 'Cine-fòrum creat correctament.';
        } else {
            Debat::update($id, $sessioId, $titol, $descripcio, $aforament, $duradaMin);
            $success = 'Cine-fòrum actualitzat correctament.';
        }
    } elseif ($accio === 'eliminar') {
        $id = (int) ($_POST['id'] ?? 0);

        if (Debat::delete($id)) {
            $success = 'Cine-fòrum eliminat correctament.';
        } else {
            $error = 'No es pot eliminar: el cine-fòrum té inscripcions associades.';
        }
    }
}

$debats = Debat::all();
$sessions = Sessio::all();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<?php $pageTitle = 'Gestió de cine-fòrums — Hollywood Land'; require __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container flex-grow-1">
    <h1 class="mb-4">Gestió de cine-fòrums</h1>
    <a href="<?= $urlPanell ?>" class="btn btn-outline-dark btn-sm mb-3">Tornar al panell</a>

    <?php if ($error !== null): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success !== null): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <h2 class="mt-4">Cine-fòrums existents</h2>
    <?php foreach ($debats as $debat): ?>
        <div class="d-flex flex-wrap gap-2 align-items-end mb-3 pb-3 border-bottom">
        <form method="post" class="d-flex flex-wrap gap-2 align-items-end">
            <input type="hidden" name="id" value="<?= (int) $debat['id'] ?>">

            <div>
                <label class="form-label small mb-0">Sessió</label>
                <select name="sessio_id" class="form-select form-select-sm" required>
                    <?php foreach ($sessions as $sessio): ?>
                        <option value="<?= (int) $sessio['id'] ?>" <?= (int) $sessio['id'] === (int) $debat['sessio_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($sessio['data']) ?> <?= htmlspecialchars(substr($sessio['hora'], 0, 5)) ?>h
                            — <?= htmlspecialchars($sessio['titol']) ?> (<?= htmlspecialchars($sessio['sala_nom']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="form-label small mb-0">Títol (opcional)</label>
                <input type="text" name="titol" value="<?= htmlspecialchars($debat['titol'] ?? '') ?>" class="form-control form-control-sm">
            </div>
            <div>
                <label class="form-label small mb-0">Durada (min)</label>
                <input type="number" name="durada_min" value="<?= (int) $debat['durada_min'] ?>" min="1" class="form-control form-control-sm" style="width: 6rem;" required>
            </div>
            <div>
                <label class="form-label small mb-0">Aforament (opcional)</label>
                <input type="number" name="aforament" value="<?= htmlspecialchars((string) ($debat['aforament'] ?? '')) ?>" min="1" class="form-control form-control-sm" style="width: 6rem;">
            </div>
            <div class="flex-grow-1" style="min-width: 220px;">
                <label class="form-label small mb-0">Descripció</label>
                <input type="text" name="descripcio" value="<?= htmlspecialchars($debat['descripcio'] ?? '') ?>" class="form-control form-control-sm">
            </div>

            <button type="submit" name="accio" value="actualitzar" class="btn btn-sm btn-dark">Desar</button>
        </form>
        <form method="post" class="js-confirm" data-message="Eliminar aquest cine-fòrum?">
            <input type="hidden" name="id" value="<?= (int) $debat['id'] ?>">
            <input type="hidden" name="accio" value="eliminar">
            <button type="submit" class="btn btn-sm btn-outline-dark">Eliminar</button>
        </form>
        </div>
    <?php endforeach; ?>
    <?php if (empty($debats)): ?>
        <p class="text-muted">Encara no hi ha cap cine-fòrum creat.</p>
    <?php endif; ?>

    <h2 class="mt-5">Crear cine-fòrum nou</h2>
    <form method="post" class="d-flex flex-wrap gap-2 align-items-end">
        <div>
            <label for="sessio_id" class="form-label small mb-0">Sessió</label>
            <select id="sessio_id" name="sessio_id" class="form-select form-select-sm" required>
                <option value="">— Tria una sessió —</option>
                <?php foreach ($sessions as $sessio): ?>
                    <option value="<?= (int) $sessio['id'] ?>">
                        <?= htmlspecialchars($sessio['data']) ?> <?= htmlspecialchars(substr($sessio['hora'], 0, 5)) ?>h
                        — <?= htmlspecialchars($sessio['titol']) ?> (<?= htmlspecialchars($sessio['sala_nom']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="titol" class="form-label small mb-0">Títol (opcional)</label>
            <input type="text" id="titol" name="titol" class="form-control form-control-sm">
        </div>
        <div>
            <label for="durada_min" class="form-label small mb-0">Durada (min)</label>
            <input type="number" id="durada_min" name="durada_min" min="1" value="60" class="form-control form-control-sm" style="width: 6rem;" required>
        </div>
        <div>
            <label for="aforament" class="form-label small mb-0">Aforament (opcional)</label>
            <input type="number" id="aforament" name="aforament" min="1" class="form-control form-control-sm" style="width: 6rem;">
        </div>
        <div class="flex-grow-1" style="min-width: 220px;">
            <label for="descripcio" class="form-label small mb-0">Descripció</label>
            <input type="text" id="descripcio" name="descripcio" class="form-control form-control-sm">
        </div>

        <button type="submit" name="accio" value="crear" class="btn btn-dark">Crear</button>
    </form>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
<?php require __DIR__ . '/../partials/confirm_modal.php'; ?>
</body>
</html>
