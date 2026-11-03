<?php

require __DIR__ . '/../../src/bootstrap.php';

use HollywoodLand\Core\Auth;
use HollywoodLand\Models\Activitat;
use HollywoodLand\Models\Sala;

Auth::requireRole('treballador', 'admin');

$urlPanell = Auth::user()['rol'] === 'admin' ? 'index.php' : '../treballador/index.php';

$error = null;
$success = null;
$tipusValids = ['taller', 'xerrada', 'podcast', 'altres'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accio = $_POST['accio'] ?? '';

    if ($accio === 'crear' || $accio === 'actualitzar') {
        $id = (int) ($_POST['id'] ?? 0);
        $tipus = $_POST['tipus'] ?? '';
        $titol = trim($_POST['titol'] ?? '');
        $descripcio = trim($_POST['descripcio'] ?? '');
        $descripcio = $descripcio === '' ? null : $descripcio;
        $data = trim($_POST['data'] ?? '');
        $hora = trim($_POST['hora'] ?? '');
        $duradaMin = (int) ($_POST['durada_min'] ?? 0);
        $aforament = trim($_POST['aforament'] ?? '');
        $aforament = $aforament === '' ? null : (int) $aforament;
        $salaId = trim($_POST['sala_id'] ?? '');
        $salaId = $salaId === '' ? null : (int) $salaId;

        if (!in_array($tipus, $tipusValids, true)) {
            $error = 'Tipus no vàlid.';
        } elseif ($titol === '' || $data === '' || $hora === '' || $duradaMin <= 0) {
            $error = 'Cal omplir títol, data, hora i una durada superior a 0.';
        } elseif (!in_array(substr($hora, 3, 2), ['00', '15', '30', '45'], true)) {
            $error = "L'hora ha de ser en intervals de 15 minuts (00, 15, 30, 45).";
        } elseif ($accio === 'crear') {
            Activitat::create($tipus, $titol, $descripcio, $data, $hora, $duradaMin, $aforament, $salaId);
            $success = 'Activitat creada correctament.';
        } else {
            Activitat::update($id, $tipus, $titol, $descripcio, $data, $hora, $duradaMin, $aforament, $salaId);
            $success = 'Activitat actualitzada correctament.';
        }
    } elseif ($accio === 'eliminar') {
        $id = (int) ($_POST['id'] ?? 0);

        if (Activitat::delete($id)) {
            $success = 'Activitat eliminada correctament.';
        } else {
            $error = 'No es pot eliminar: l\'activitat té inscripcions associades.';
        }
    }
}

$activitats = Activitat::all();
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
<?php $pageTitle = "Gestió d'activitats — Hollywood Land"; require __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container flex-grow-1">
    <h1 class="mb-4">Gestió d'activitats</h1>
    <a href="<?= $urlPanell ?>" class="btn btn-outline-dark btn-sm mb-3">Tornar al panell</a>

    <?php if ($error !== null): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success !== null): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <h2 class="mt-4">Activitats existents</h2>
    <?php foreach ($activitats as $activitat): ?>
        <div class="d-flex flex-wrap gap-2 align-items-end mb-3 pb-3 border-bottom">
        <form method="post" class="d-flex flex-wrap gap-2 align-items-end">
            <input type="hidden" name="id" value="<?= (int) $activitat['id'] ?>">

            <div>
                <label class="form-label small mb-0">Tipus</label>
                <select name="tipus" class="form-select form-select-sm">
                    <?php foreach ($tipusValids as $tipus): ?>
                        <option value="<?= $tipus ?>" <?= $tipus === $activitat['tipus'] ? 'selected' : '' ?>><?= ucfirst($tipus) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="form-label small mb-0">Títol</label>
                <input type="text" name="titol" value="<?= htmlspecialchars($activitat['titol']) ?>" class="form-control form-control-sm" required>
            </div>
            <div>
                <label class="form-label small mb-0">Data</label>
                <input type="date" name="data" value="<?= htmlspecialchars($activitat['data']) ?>" class="form-control form-control-sm" required>
            </div>
            <div>
                <label class="form-label small mb-0">Hora</label>
                <select name="hora" class="form-select form-select-sm" required>
                    <?php foreach ($horesDisponibles as $h): ?>
                        <option value="<?= $h ?>" <?= $h === substr($activitat['hora'], 0, 5) ? 'selected' : '' ?>><?= $h ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="form-label small mb-0">Durada (min)</label>
                <input type="number" name="durada_min" value="<?= (int) $activitat['durada_min'] ?>" min="1" class="form-control form-control-sm" style="width: 6rem;" required>
            </div>
            <div>
                <label class="form-label small mb-0">Aforament (opcional)</label>
                <input type="number" name="aforament" value="<?= htmlspecialchars((string) ($activitat['aforament'] ?? '')) ?>" min="1" class="form-control form-control-sm" style="width: 6rem;">
            </div>
            <div>
                <label class="form-label small mb-0">Sala (opcional)</label>
                <select name="sala_id" class="form-select form-select-sm">
                    <option value="">— Cap —</option>
                    <?php foreach ($sales as $sala): ?>
                        <option value="<?= (int) $sala['id'] ?>" <?= (int) $sala['id'] === (int) ($activitat['sala_id'] ?? 0) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($sala['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex-grow-1" style="min-width: 220px;">
                <label class="form-label small mb-0">Descripció</label>
                <input type="text" name="descripcio" value="<?= htmlspecialchars($activitat['descripcio'] ?? '') ?>" class="form-control form-control-sm">
            </div>

            <button type="submit" name="accio" value="actualitzar" class="btn btn-sm btn-dark">Desar</button>
        </form>
        <form method="post" class="js-confirm" data-message="Eliminar aquesta activitat?">
            <input type="hidden" name="id" value="<?= (int) $activitat['id'] ?>">
            <input type="hidden" name="accio" value="eliminar">
            <button type="submit" class="btn btn-sm btn-outline-dark">Eliminar</button>
        </form>
        </div>
    <?php endforeach; ?>
    <?php if (empty($activitats)): ?>
        <p class="text-muted">Encara no hi ha cap activitat creada.</p>
    <?php endif; ?>

    <h2 class="mt-5">Crear activitat nova</h2>
    <form method="post" class="d-flex flex-wrap gap-2 align-items-end">
        <div>
            <label for="tipus" class="form-label small mb-0">Tipus</label>
            <select id="tipus" name="tipus" class="form-select form-select-sm">
                <?php foreach ($tipusValids as $tipus): ?>
                    <option value="<?= $tipus ?>"><?= ucfirst($tipus) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="titol" class="form-label small mb-0">Títol</label>
            <input type="text" id="titol" name="titol" class="form-control form-control-sm" required>
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
            <label for="durada_min" class="form-label small mb-0">Durada (min)</label>
            <input type="number" id="durada_min" name="durada_min" min="1" class="form-control form-control-sm" style="width: 6rem;" required>
        </div>
        <div>
            <label for="aforament" class="form-label small mb-0">Aforament (opcional)</label>
            <input type="number" id="aforament" name="aforament" min="1" class="form-control form-control-sm" style="width: 6rem;">
        </div>
        <div>
            <label for="sala_id" class="form-label small mb-0">Sala (opcional)</label>
            <select id="sala_id" name="sala_id" class="form-select form-select-sm">
                <option value="">— Cap —</option>
                <?php foreach ($sales as $sala): ?>
                    <option value="<?= (int) $sala['id'] ?>"><?= htmlspecialchars($sala['nom']) ?></option>
                <?php endforeach; ?>
            </select>
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
