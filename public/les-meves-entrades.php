<?php

require __DIR__ . '/../src/bootstrap.php';

use HollywoodLand\Core\Auth;
use HollywoodLand\Models\Entrada;
use HollywoodLand\Models\InscripcioActivitat;
use HollywoodLand\Models\InscripcioDebat;

Auth::requireLogin();

$entrades = Entrada::findByUsuari((int) Auth::user()['id']);
$inscripcions = InscripcioDebat::findByUsuari((int) Auth::user()['id']);
$inscripcionsActivitats = InscripcioActivitat::findByUsuari((int) Auth::user()['id']);

$activitats = [];

foreach ($inscripcions as $i) {
    $horaInici = DateTime::createFromFormat('H:i:s', $i['hora_sessio']);
    $horaInici->modify('+' . (int) $i['pelicula_durada'] . ' minutes');
    $activitats[] = [
        'tipus' => 'Cine-fòrum',
        'titol' => $i['titol'] ?? $i['pelicula_titol'],
        'data' => $i['data'],
        'hora' => $horaInici->format('H:i'),
        'durada' => (int) $i['durada_min'],
        'es_debat' => true,
        'debat_id' => (int) $i['debat_id'],
    ];
}

foreach ($inscripcionsActivitats as $a) {
    $activitats[] = [
        'tipus' => ucfirst($a['tipus']),
        'titol' => $a['titol'],
        'data' => $a['data'],
        'hora' => substr($a['hora'], 0, 5),
        'durada' => (int) $a['durada_min'],
        'es_debat' => false,
        'activitat_id' => (int) $a['activitat_id'],
    ];
}

usort($activitats, fn ($a, $b) => $a['data'] . $a['hora'] <=> $b['data'] . $b['hora']);
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<?php $pageTitle = 'Les meves entrades — Hollywood Land'; require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/partials/navbar.php'; ?>

<div class="container flex-grow-1">
    <h1 class="mb-4">Les meves entrades</h1>

    <h2 class="mb-3">Pel·lícules</h2>

    <?php if (empty($entrades)): ?>
        <p>Encara no has comprat cap entrada. <a href="cartellera.php">Consulta la cartellera</a>.</p>
    <?php else: ?>
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Pel·lícula</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Sala</th>
                    <th>Butaca</th>
                    <th>Preu</th>
                    <th>Comprada</th>
                    <th>Estat</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entrades as $e): ?>
                <tr>
                    <td><?= htmlspecialchars($e['titol']) ?></td>
                    <td><?= htmlspecialchars($e['data']) ?></td>
                    <td><?= substr($e['hora'], 0, 5) ?></td>
                    <td><?= htmlspecialchars($e['sala_nom']) ?></td>
                    <td><?= htmlspecialchars($e['fila'] . $e['numero']) ?></td>
                    <td><?= number_format((float) $e['preu_pagat'], 2) ?> €</td>
                    <td><?= htmlspecialchars($e['data_compra']) ?></td>
                    <td>
                        <span class="badge <?= $e['estat'] === 'pagada' ? 'text-bg-success' : 'text-bg-secondary' ?>">
                            <?= htmlspecialchars($e['estat']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <h2 class="mt-5 mb-3">Activitats</h2>

    <?php if (empty($activitats)): ?>
        <p>Encara no t'has inscrit a cap cine-fòrum ni activitat.</p>
    <?php else: ?>
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Tipus</th>
                    <th>Títol</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Durada</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activitats as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['tipus']) ?></td>
                    <td><?= htmlspecialchars($a['titol']) ?></td>
                    <td><?= htmlspecialchars($a['data']) ?></td>
                    <td><?= htmlspecialchars($a['hora']) ?></td>
                    <td><?= $a['durada'] ?> min</td>
                    <td>
                        <?php if ($a['es_debat']): ?>
                            <a href="debat.php?id=<?= $a['debat_id'] ?>" class="btn btn-sm btn-outline-dark">Gestionar</a>
                        <?php else: ?>
                            <form method="post" action="cartellera.php" class="d-inline js-confirm" data-message="Vols cancel·lar la inscripció?">
                                <input type="hidden" name="activitat_id" value="<?= $a['activitat_id'] ?>">
                                <input type="hidden" name="accio" value="cancelar">
                                <button type="submit" class="btn btn-sm btn-outline-dark">Cancel·la</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
<?php require __DIR__ . '/partials/confirm_modal.php'; ?>
</body>
</html>
