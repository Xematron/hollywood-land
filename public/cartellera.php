<?php

require __DIR__ . '/../src/bootstrap.php';

use HollywoodLand\Core\Auth;
use HollywoodLand\Models\Activitat;
use HollywoodLand\Models\Debat;
use HollywoodLand\Models\InscripcioActivitat;
use HollywoodLand\Models\InscripcioDebat;
use HollywoodLand\Models\Sessio;

$missatges = [];
$missatgesActivitat = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['debat_id'])) {
    Auth::requireLogin();

    $debatId = (int) $_POST['debat_id'];
    $accio = $_POST['accio'] ?? '';
    $usuariId = (int) Auth::user()['id'];

    if ($accio === 'inscriure') {
        $debatInfo = Debat::findById($debatId);
        $inscrits = InscripcioDebat::comptar($debatId);

        if ($debatInfo !== null && $debatInfo['aforament'] !== null && $inscrits >= (int) $debatInfo['aforament']) {
            $missatges[$debatId] = ['tipus' => 'danger', 'text' => 'Aforament complet.'];
        } else {
            try {
                InscripcioDebat::inscriure($debatId, $usuariId);
                $missatges[$debatId] = ['tipus' => 'success', 'text' => 'Inscrit correctament.'];
            } catch (PDOException $e) {
                $missatges[$debatId] = ['tipus' => 'warning', 'text' => 'Ja hi estaves inscrit.'];
            }
        }
    } elseif ($accio === 'cancelar') {
        InscripcioDebat::cancelar($debatId, $usuariId);
        $missatges[$debatId] = ['tipus' => 'success', 'text' => 'Inscripció cancel·lada.'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['activitat_id'])) {
    Auth::requireLogin();

    $activitatId = (int) $_POST['activitat_id'];
    $accio = $_POST['accio'] ?? '';
    $usuariId = (int) Auth::user()['id'];

    if ($accio === 'inscriure') {
        $activitatInfo = Activitat::findById($activitatId);
        $inscrits = InscripcioActivitat::comptar($activitatId);

        if ($activitatInfo !== null && $activitatInfo['aforament'] !== null && $inscrits >= (int) $activitatInfo['aforament']) {
            $missatgesActivitat[$activitatId] = ['tipus' => 'danger', 'text' => 'Aforament complet.'];
        } else {
            try {
                InscripcioActivitat::inscriure($activitatId, $usuariId);
                $missatgesActivitat[$activitatId] = ['tipus' => 'success', 'text' => 'Inscrit correctament.'];
            } catch (PDOException $e) {
                $missatgesActivitat[$activitatId] = ['tipus' => 'warning', 'text' => 'Ja hi estaves inscrit.'];
            }
        }
    } elseif ($accio === 'cancelar') {
        InscripcioActivitat::cancelar($activitatId, $usuariId);
        $missatgesActivitat[$activitatId] = ['tipus' => 'success', 'text' => 'Inscripció cancel·lada.'];
    }
}

$usuari = Auth::user();
$inscripcionsUsuari = [];
$inscripcionsActivitatUsuari = [];
if ($usuari !== null) {
    foreach (InscripcioDebat::findByUsuari((int) $usuari['id']) as $i) {
        $inscripcionsUsuari[(int) $i['debat_id']] = true;
    }
    foreach (InscripcioActivitat::findByUsuari((int) $usuari['id']) as $i) {
        $inscripcionsActivitatUsuari[(int) $i['activitat_id']] = true;
    }
}

$items = [];

foreach (Sessio::cartellera() as $s) {
    $items[] = [
        'tipus' => 'sessio',
        'datahora' => $s['data'] . ' ' . $s['hora'],
        'data' => $s['data'],
        'hora' => substr($s['hora'], 0, 5),
        'titol' => $s['titol'] . ' (' . (int) $s['durada_min'] . ' min)',
        'sala_nom' => $s['sala_nom'],
        'info' => number_format((float) $s['preu'], 2) . ' €'
            . ($s['idioma_subtitols'] ? ' · ' . $s['idioma_subtitols'] : ''),
        'sessio_id' => $s['id'],
    ];
}

foreach (Debat::propers() as $d) {
    $debatId = (int) $d['id'];
    $horaInici = DateTime::createFromFormat('H:i:s', $d['hora_sessio']);
    $horaInici->modify('+' . (int) $d['pelicula_durada'] . ' minutes');
    $inscrits = InscripcioDebat::comptar($debatId);

    $items[] = [
        'tipus' => 'debat',
        'datahora' => $d['data'] . ' ' . $horaInici->format('H:i:s'),
        'data' => $d['data'],
        'hora' => $horaInici->format('H:i'),
        'titol' => 'Cine-fòrum: ' . ($d['titol'] ?? $d['pelicula_titol']),
        'sala_nom' => $d['sala_nom'],
        'info' => $d['aforament'] !== null ? $inscrits . '/' . (int) $d['aforament'] . ' places' : $inscrits . ' inscrits',
        'debat_id' => $debatId,
        'aforament' => $d['aforament'],
        'inscrits' => $inscrits,
    ];
}

foreach (Activitat::properes() as $a) {
    $activitatId = (int) $a['id'];
    $inscrits = InscripcioActivitat::comptar($activitatId);

    $items[] = [
        'tipus' => 'activitat',
        'datahora' => $a['data'] . ' ' . $a['hora'],
        'data' => $a['data'],
        'hora' => substr($a['hora'], 0, 5),
        'titol' => ucfirst($a['tipus']) . ': ' . $a['titol'] . ' (' . (int) $a['durada_min'] . ' min)',
        'sala_nom' => $a['sala_nom'],
        'info' => $a['aforament'] !== null ? $inscrits . '/' . (int) $a['aforament'] . ' places' : '',
        'activitat_id' => $activitatId,
        'aforament' => $a['aforament'],
        'inscrits' => $inscrits,
    ];
}

usort($items, fn ($a, $b) => $a['datahora'] <=> $b['datahora']);
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<?php $pageTitle = 'Cartellera — Hollywood Land'; require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/partials/navbar.php'; ?>

<div class="container flex-grow-1">
    <h1 class="mb-4">Cartellera</h1>

    <div class="mb-3 d-flex gap-3">
        <span><span class="legend-sample legend-sessio"></span> Sessió</span>
        <span><span class="legend-sample legend-debat"></span> Cine-fòrum</span>
        <span><span class="legend-sample legend-activitat"></span> Altres activitats</span>
    </div>

    <?php if (empty($items)): ?>
        <p>No hi ha res programat properament.</p>
    <?php else: ?>
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Tipus</th>
                    <th>Títol</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Sala</th>
                    <th>Info</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr class="row-<?= $item['tipus'] ?>">
                    <td>
                        <?php if ($item['tipus'] === 'sessio'): ?>
                            <span class="badge text-bg-dark">Sessió</span>
                        <?php elseif ($item['tipus'] === 'debat'): ?>
                            <span class="badge text-bg-warning text-dark">Cine-fòrum</span>
                        <?php else: ?>
                            <span class="badge text-bg-secondary">Activitat</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($item['titol']) ?></td>
                    <td><?= htmlspecialchars($item['data']) ?></td>
                    <td><?= htmlspecialchars($item['hora']) ?></td>
                    <td><?= htmlspecialchars($item['sala_nom'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($item['info']) ?></td>
                    <td>
                        <?php if ($item['tipus'] === 'sessio'): ?>
                            <a class="btn btn-sm btn-dark" href="entrades.php?sessio_id=<?= (int) $item['sessio_id'] ?>">Comprar</a>
                        <?php elseif ($item['tipus'] === 'debat'): ?>
                            <?php $debatId = $item['debat_id']; ?>
                            <?php if (isset($missatges[$debatId])): ?>
                                <div class="small text-<?= $missatges[$debatId]['tipus'] === 'success' ? 'success' : ($missatges[$debatId]['tipus'] === 'warning' ? 'warning' : 'danger') ?>">
                                    <?= htmlspecialchars($missatges[$debatId]['text']) ?>
                                </div>
                            <?php endif; ?>

                            <?php $jaInscrit = isset($inscripcionsUsuari[$debatId]); ?>
                            <?php $ple = $item['aforament'] !== null && $item['inscrits'] >= (int) $item['aforament'] && !$jaInscrit; ?>

                            <?php if ($usuari === null): ?>
                                <a class="btn btn-sm btn-dark" href="login.php?redirect=<?= urlencode('/cartellera.php') ?>">Inicia sessió</a>
                            <?php elseif ($jaInscrit): ?>
                                <form method="post" class="d-inline js-confirm" data-message="Vols cancel·lar la inscripció?">
                                    <input type="hidden" name="debat_id" value="<?= $debatId ?>">
                                    <button type="submit" name="accio" value="cancelar" class="btn btn-sm btn-outline-dark">Cancel·la</button>
                                </form>
                            <?php elseif ($ple): ?>
                                <span class="badge text-bg-secondary">Ple</span>
                            <?php else: ?>
                                <form method="post" class="d-inline js-confirm" data-message="Vols inscriure't a aquest cine-fòrum?">
                                    <input type="hidden" name="debat_id" value="<?= $debatId ?>">
                                    <button type="submit" name="accio" value="inscriure" class="btn btn-sm btn-dark">Inscriu-m'hi</button>
                                </form>
                            <?php endif; ?>
                        <?php elseif ($item['tipus'] === 'activitat'): ?>
                            <?php $activitatId = $item['activitat_id']; ?>
                            <?php if (isset($missatgesActivitat[$activitatId])): ?>
                                <div class="small text-<?= $missatgesActivitat[$activitatId]['tipus'] === 'success' ? 'success' : ($missatgesActivitat[$activitatId]['tipus'] === 'warning' ? 'warning' : 'danger') ?>">
                                    <?= htmlspecialchars($missatgesActivitat[$activitatId]['text']) ?>
                                </div>
                            <?php endif; ?>

                            <?php $jaInscritActivitat = isset($inscripcionsActivitatUsuari[$activitatId]); ?>
                            <?php $pleActivitat = $item['aforament'] !== null && $item['inscrits'] >= (int) $item['aforament'] && !$jaInscritActivitat; ?>

                            <?php if ($usuari === null): ?>
                                <a class="btn btn-sm btn-dark" href="login.php?redirect=<?= urlencode('/cartellera.php') ?>">Inicia sessió</a>
                            <?php elseif ($jaInscritActivitat): ?>
                                <form method="post" class="d-inline js-confirm" data-message="Vols cancel·lar la inscripció?">
                                    <input type="hidden" name="activitat_id" value="<?= $activitatId ?>">
                                    <button type="submit" name="accio" value="cancelar" class="btn btn-sm btn-outline-dark">Cancel·la</button>
                                </form>
                            <?php elseif ($pleActivitat): ?>
                                <span class="badge text-bg-secondary">Ple</span>
                            <?php else: ?>
                                <form method="post" class="d-inline js-confirm" data-message="Vols inscriure't a aquesta activitat?">
                                    <input type="hidden" name="activitat_id" value="<?= $activitatId ?>">
                                    <button type="submit" name="accio" value="inscriure" class="btn btn-sm btn-dark">Inscriu-m'hi</button>
                                </form>
                            <?php endif; ?>
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
