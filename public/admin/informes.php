<?php

require __DIR__ . '/../../src/bootstrap.php';

use HollywoodLand\Core\Auth;
use HollywoodLand\Models\Entrada;

Auth::requireRole('admin');

$dataInici = trim($_GET['inici'] ?? '');
$dataFi = trim($_GET['fi'] ?? '');

// Dates fixes (no "mes en curs"): projecte fictici amb dades de demo
// que no creixen, cal que l'informe surti ple independentment de quan
// es faci la presentació/entrevista.
if ($dataInici === '') {
    $dataInici = '2026-10-01';
}
if ($dataFi === '') {
    $dataFi = '2026-11-30';
}

$error = null;
if ($dataInici > $dataFi) {
    $error = 'La data d\'inici no pot ser posterior a la data de fi.';
    $taquillera = [];
    $ocupacio = [];
} else {
    $taquillera = Entrada::taquilleraPerPeriode($dataInici, $dataFi);
    $ocupacio = Entrada::ocupacioPerSessio($dataInici, $dataFi);
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<?php $pageTitle = 'Informes i estadístiques — Hollywood Land'; require __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container flex-grow-1">
    <h1 class="mb-4">Informes i estadístiques</h1>
    <p><a href="index.php">Tornar al panell admin</a></p>

    <?php if ($error !== null): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="get" class="row row-cols-auto g-2 align-items-end mb-4">
        <div class="col">
            <label for="inici" class="form-label">Des de</label>
            <input type="date" id="inici" name="inici" class="form-control" value="<?= htmlspecialchars($dataInici) ?>">
        </div>
        <div class="col">
            <label for="fi" class="form-label">Fins a</label>
            <input type="date" id="fi" name="fi" class="form-control" value="<?= htmlspecialchars($dataFi) ?>">
        </div>
        <div class="col">
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
    </form>

    <h2>Pel·lícules més taquilleres</h2>
    <?php if (empty($taquillera)): ?>
        <p>No hi ha entrades venudes en aquest període.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Pel·lícula</th>
                    <th>Entrades venudes</th>
                    <th>Ingressos</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($taquillera as $fila): ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['titol']) ?></td>
                        <td><?= (int) $fila['entrades_venudes'] ?></td>
                        <td><?= number_format((float) $fila['ingressos'], 2) ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <h2 class="mt-5">Ocupació per sessió</h2>
    <?php if (empty($ocupacio)): ?>
        <p>No hi ha sessions en aquest període.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Pel·lícula</th>
                    <th>Sala</th>
                    <th>Ocupació</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ocupacio as $fila): ?>
                    <?php
                        $capacitat = (int) $fila['capacitat'];
                        $venudes = (int) $fila['venudes'];
                        $percentatge = $capacitat > 0 ? round($venudes / $capacitat * 100) : 0;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['data']) ?></td>
                        <td><?= htmlspecialchars(substr($fila['hora'], 0, 5)) ?>h</td>
                        <td><?= htmlspecialchars($fila['titol']) ?></td>
                        <td><?= htmlspecialchars($fila['sala_nom']) ?></td>
                        <td><?= $venudes ?> / <?= $capacitat ?> (<?= $percentatge ?>%)</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
