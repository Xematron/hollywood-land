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

    <h1 class="mt-5 mb-4">Els meus cine-fòrums</h1>

    <?php if (empty($inscripcions)): ?>
        <p>Encara no t'has inscrit a cap cine-fòrum.</p>
    <?php else: ?>
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Cine-fòrum</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Durada</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inscripcions as $i): ?>
                <?php
                $horaInici = DateTime::createFromFormat('H:i:s', $i['hora_sessio']);
                $horaInici->modify('+' . (int) $i['pelicula_durada'] . ' minutes');
                ?>
                <tr>
                    <td><?= htmlspecialchars($i['titol'] ?? $i['pelicula_titol']) ?></td>
                    <td><?= htmlspecialchars($i['data']) ?></td>
                    <td><?= $horaInici->format('H:i') ?></td>
                    <td><?= (int) $i['durada_min'] ?> min</td>
                    <td><a href="debat.php?id=<?= (int) $i['debat_id'] ?>" class="btn btn-sm btn-outline-dark">Gestionar</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <h1 class="mt-5 mb-4">Les meves activitats</h1>

    <?php if (empty($inscripcionsActivitats)): ?>
        <p>Encara no t'has inscrit a cap activitat.</p>
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
                <?php foreach ($inscripcionsActivitats as $a): ?>
                <tr>
                    <td><?= htmlspecialchars(ucfirst($a['tipus'])) ?></td>
                    <td><?= htmlspecialchars($a['titol']) ?></td>
                    <td><?= htmlspecialchars($a['data']) ?></td>
                    <td><?= substr($a['hora'], 0, 5) ?></td>
                    <td><?= (int) $a['durada_min'] ?> min</td>
                    <td>
                        <form method="post" action="cartellera.php" class="d-inline js-confirm" data-message="Vols cancel·lar la inscripció?">
                            <input type="hidden" name="activitat_id" value="<?= (int) $a['activitat_id'] ?>">
                            <button type="submit" name="accio" value="cancelar" class="btn btn-sm btn-outline-dark">Cancel·la</button>
                        </form>
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
