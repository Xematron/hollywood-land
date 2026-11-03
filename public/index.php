<?php

require __DIR__ . '/../src/bootstrap.php';

use HollywoodLand\Core\Auth;
use HollywoodLand\Models\Activitat;
use HollywoodLand\Models\Debat;
use HollywoodLand\Models\InscripcioActivitat;
use HollywoodLand\Models\InscripcioDebat;
use HollywoodLand\Models\Sessio;

$missatgesActivitat = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['activitat_id'])) {
    Auth::requireLogin();

    $activitatId = (int) $_POST['activitat_id'];
    $accio = $_POST['accio'] ?? '';
    $usuariId = (int) Auth::user()['id'];

    if ($accio === 'inscriure') {
        $quantitat = max(1, (int) ($_POST['quantitat'] ?? 1));
        $activitatInfo = Activitat::findById($activitatId);
        $inscrits = InscripcioActivitat::comptar($activitatId);

        if ($activitatInfo !== null && $activitatInfo['aforament'] !== null && $inscrits + $quantitat > (int) $activitatInfo['aforament']) {
            $missatgesActivitat[$activitatId] = ['tipus' => 'danger', 'text' => 'No queden prou places per a aquesta quantitat.'];
        } else {
            try {
                InscripcioActivitat::inscriure($activitatId, $usuariId, $quantitat);
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

$missatgesDebat = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['debat_id'])) {
    Auth::requireLogin();

    $debatId = (int) $_POST['debat_id'];
    $accio = $_POST['accio'] ?? '';
    $usuariId = (int) Auth::user()['id'];

    if ($accio === 'inscriure') {
        $quantitat = max(1, (int) ($_POST['quantitat'] ?? 1));
        $debatInfo = Debat::findById($debatId);
        $inscrits = InscripcioDebat::comptar($debatId);

        if ($debatInfo !== null && $debatInfo['aforament'] !== null && $inscrits + $quantitat > (int) $debatInfo['aforament']) {
            $missatgesDebat[$debatId] = ['tipus' => 'danger', 'text' => 'No queden prou places per a aquesta quantitat.'];
        } else {
            try {
                InscripcioDebat::inscriure($debatId, $usuariId, $quantitat);
                $missatgesDebat[$debatId] = ['tipus' => 'success', 'text' => 'Inscrit correctament.'];
            } catch (PDOException $e) {
                $missatgesDebat[$debatId] = ['tipus' => 'warning', 'text' => 'Ja hi estaves inscrit.'];
            }
        }
    } elseif ($accio === 'cancelar') {
        InscripcioDebat::cancelar($debatId, $usuariId);
        $missatgesDebat[$debatId] = ['tipus' => 'success', 'text' => 'Inscripció cancel·lada.'];
    }
}

$usuari = Auth::user();

const MESOS_CA = [
    1 => 'gener', 2 => 'febrer', 3 => 'març', 4 => 'abril',
    5 => 'maig', 6 => 'juny', 7 => 'juliol', 8 => 'agost',
    9 => 'setembre', 10 => 'octubre', 11 => 'novembre', 12 => 'desembre',
];

const DIES_CA = [
    1 => 'Dilluns', 2 => 'Dimarts', 3 => 'Dimecres', 4 => 'Dijous',
    5 => 'Divendres', 6 => 'Dissabte', 7 => 'Diumenge',
];

function mesAmbPreposicio(int $mes): string
{
    $nom = MESOS_CA[$mes];
    $prep = in_array($nom[0], ['a', 'e', 'i', 'o', 'u'], true) ? "d'" : 'de ';

    return $prep . $nom;
}

function etiquetaDia(string $data): string
{
    $d = new DateTime($data);
    $diaSetmana = DIES_CA[(int) $d->format('N')];
    $dia = (int) $d->format('j');
    $mes = (int) $d->format('n');

    return "{$diaSetmana}, {$dia} " . mesAmbPreposicio($mes);
}

function sumaMinuts(string $hora, int $minuts): string
{
    $t = DateTime::createFromFormat('H:i:s', $hora);
    $t->modify("+{$minuts} minutes");

    return $t->format('H:i');
}

function afegirAlDia(array &$dies, string $data, array $item): void
{
    if (!isset($dies[$data])) {
        $dies[$data] = [
            'etiqueta' => etiquetaDia($data),
            'items' => [],
        ];
    }

    $dies[$data]['items'][] = $item;
}

$dies = [];

foreach (Sessio::cartellera() as $s) {
    afegirAlDia($dies, $s['data'], [
        'tipus' => 'sessio',
        'sessio_id' => $s['id'],
        'hora' => substr($s['hora'], 0, 5),
        'titol' => $s['titol'],
        'detall' => (int) $s['durada_min'] . ' min' . ($s['classificacio'] ? ' · ' . $s['classificacio'] : ''),
        'sala' => $s['sala_nom'],
        'sinopsi' => $s['sinopsi'],
        'poster' => $s['titol'],
    ]);
}

foreach (Debat::propers() as $d) {
    $titol = 'Cine-fòrum: ' . ($d['titol'] ?? $d['pelicula_titol']);
    $debatId = (int) $d['id'];
    afegirAlDia($dies, $d['data'], [
        'tipus' => 'debat',
        'id' => $debatId,
        'hora' => sumaMinuts($d['hora_sessio'], (int) $d['pelicula_durada']),
        'titol' => $titol,
        'detall' => $d['durada_min'] . ' min',
        'sala' => $d['sala_nom'],
        'sinopsi' => $d['descripcio'],
        'poster' => $titol,
        'aforament' => $d['aforament'],
        'inscrits' => InscripcioDebat::comptar($debatId),
    ]);
}

foreach (Activitat::properes() as $a) {
    $titol = ucfirst($a['tipus']) . ': ' . $a['titol'];
    $activitatId = (int) $a['id'];
    afegirAlDia($dies, $a['data'], [
        'tipus' => 'activitat',
        'activitat_id' => $activitatId,
        'hora' => substr($a['hora'], 0, 5),
        'titol' => $titol,
        'detall' => $a['durada_min'] . ' min',
        'sala' => $a['sala_nom'],
        'sinopsi' => $a['descripcio'],
        'poster' => $titol,
        'aforament' => $a['aforament'],
        'inscrits' => InscripcioActivitat::comptar($activitatId),
    ]);
}

ksort($dies);
foreach ($dies as &$dia) {
    usort($dia['items'], fn ($a, $b) => $a['hora'] <=> $b['hora']);
}
unset($dia);
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<?php $pageTitle = 'Hollywood Land'; require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/partials/navbar.php'; ?>

<div class="container flex-grow-1">
    <?php if (empty($dies)): ?>
        <p>No hi ha sessions properes.</p>
    <?php else: ?>
        <?php foreach ($dies as $dia): ?>
            <h2 class="mt-5 mb-3 border-bottom border-[#2a1a1a] pb-2"><?= htmlspecialchars($dia['etiqueta']) ?></h2>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                <?php foreach ($dia['items'] as $item): ?>
                <?php $esSessio = $item['tipus'] === 'sessio'; ?>
                <?php $esDebat = $item['tipus'] === 'debat'; ?>
                <?php $esActivitat = $item['tipus'] === 'activitat'; ?>
                <div class="col">
                    <div class="card h-100 shadow-sm<?= $esSessio ? '' : ' border-warning' ?>">
                        <img src="https://placehold.co/300x450?text=<?= urlencode($item['poster']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['titol']) ?>">
                        <div class="card-body">
                            <span class="badge <?= $esSessio ? 'text-bg-dark' : 'text-bg-warning text-dark' ?> mb-2"><?= htmlspecialchars($item['hora']) ?>h</span>
                            <h5 class="card-title"><?= htmlspecialchars($item['titol']) ?></h5>
                            <p class="card-text text-muted mb-0"><?= htmlspecialchars($item['detall']) ?></p>
                            <?php if (!empty($item['sala'])): ?>
                                <p class="card-text text-muted mb-0"><?= htmlspecialchars($item['sala']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($item['sinopsi'])): ?>
                                <p class="card-text small mt-2"><?= htmlspecialchars($item['sinopsi']) ?></p>
                            <?php endif; ?>
                        </div>
                        <?php if ($esSessio): ?>
                            <div class="card-footer bg-white border-0">
                                <a href="entrades.php?sessio_id=<?= (int) $item['sessio_id'] ?>" class="btn btn-sm btn-dark w-100">Comprar entrades</a>
                            </div>
                        <?php elseif ($esDebat): ?>
                            <?php
                                $debatId = $item['id'];
                                $jaInscritDebat = $usuari !== null && InscripcioDebat::jaInscrit($debatId, (int) $usuari['id']);
                                $pleDebat = $item['aforament'] !== null && $item['inscrits'] >= (int) $item['aforament'] && !$jaInscritDebat;
                            ?>
                            <div class="card-footer bg-white border-0">
                                <?php if (isset($missatgesDebat[$debatId])): ?>
                                    <div class="small text-<?= $missatgesDebat[$debatId]['tipus'] === 'success' ? 'success' : ($missatgesDebat[$debatId]['tipus'] === 'warning' ? 'warning' : 'danger') ?> mb-1">
                                        <?= htmlspecialchars($missatgesDebat[$debatId]['text']) ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($usuari === null): ?>
                                    <a href="login.php?redirect=<?= urlencode('/index.php') ?>" class="btn btn-sm btn-dark w-100">Inscriure't al debat</a>
                                <?php elseif ($jaInscritDebat): ?>
                                    <form method="post" class="js-confirm" data-message="Vols cancel·lar la inscripció?">
                                        <input type="hidden" name="debat_id" value="<?= $debatId ?>">
                                        <input type="hidden" name="accio" value="cancelar">
                                        <button type="submit" class="btn btn-sm btn-outline-dark w-100">Cancel·la inscripció</button>
                                    </form>
                                <?php elseif ($pleDebat): ?>
                                    <span class="badge text-bg-secondary">Aforament complet</span>
                                <?php else: ?>
                                    <?php $maxQuantitatDebat = $item['aforament'] !== null ? max(1, min(10, (int) $item['aforament'] - (int) $item['inscrits'])) : 10; ?>
                                    <form method="post" class="js-confirm" data-message="Vols inscriure't a aquest debat?" data-quantitat-max="<?= $maxQuantitatDebat ?>">
                                        <input type="hidden" name="debat_id" value="<?= $debatId ?>">
                                        <input type="hidden" name="accio" value="inscriure">
                                        <button type="submit" class="btn btn-sm btn-dark w-100">Inscriure't al debat</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php elseif ($esActivitat): ?>
                            <?php
                                $activitatId = $item['activitat_id'];
                                $jaInscritActivitat = $usuari !== null && InscripcioActivitat::jaInscrit($activitatId, (int) $usuari['id']);
                                $pleActivitat = $item['aforament'] !== null && $item['inscrits'] >= (int) $item['aforament'] && !$jaInscritActivitat;
                            ?>
                            <div class="card-footer bg-white border-0">
                                <?php if (isset($missatgesActivitat[$activitatId])): ?>
                                    <div class="small text-<?= $missatgesActivitat[$activitatId]['tipus'] === 'success' ? 'success' : ($missatgesActivitat[$activitatId]['tipus'] === 'warning' ? 'warning' : 'danger') ?> mb-1">
                                        <?= htmlspecialchars($missatgesActivitat[$activitatId]['text']) ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($usuari === null): ?>
                                    <a href="login.php?redirect=<?= urlencode('/index.php') ?>" class="btn btn-sm btn-dark w-100">Inscriure't a l'activitat</a>
                                <?php elseif ($jaInscritActivitat): ?>
                                    <form method="post" class="js-confirm" data-message="Vols cancel·lar la inscripció?">
                                        <input type="hidden" name="activitat_id" value="<?= $activitatId ?>">
                                        <input type="hidden" name="accio" value="cancelar">
                                        <button type="submit" class="btn btn-sm btn-outline-dark w-100">Cancel·la inscripció</button>
                                    </form>
                                <?php elseif ($pleActivitat): ?>
                                    <span class="badge text-bg-secondary">Aforament complet</span>
                                <?php else: ?>
                                    <?php $maxQuantitatActivitat = $item['aforament'] !== null ? max(1, min(10, (int) $item['aforament'] - (int) $item['inscrits'])) : 10; ?>
                                    <form method="post" class="js-confirm" data-message="Vols inscriure't a aquesta activitat?" data-quantitat-max="<?= $maxQuantitatActivitat ?>">
                                        <input type="hidden" name="activitat_id" value="<?= $activitatId ?>">
                                        <input type="hidden" name="accio" value="inscriure">
                                        <button type="submit" class="btn btn-sm btn-dark w-100">Inscriu-t'hi</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
<?php require __DIR__ . '/partials/confirm_modal.php'; ?>
</body>
</html>
