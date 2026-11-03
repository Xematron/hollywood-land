<?php

require __DIR__ . '/../src/bootstrap.php';

use HollywoodLand\Core\Auth;
use HollywoodLand\Models\Debat;
use HollywoodLand\Models\InscripcioDebat;

Auth::requireLogin();

$debatId = (int) ($_GET['id'] ?? 0);
$debat = Debat::findById($debatId);

if ($debat === null) {
    http_response_code(404);
    $missatge404 = 'Cine-fòrum no trobat.';
    require __DIR__ . '/partials/error404.php';
    exit;
}

$usuariId = (int) Auth::user()['id'];
$missatge = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accio = $_POST['accio'] ?? '';

    if ($accio === 'inscriure') {
        $quantitat = max(1, (int) ($_POST['quantitat'] ?? 1));
        $inscrits = InscripcioDebat::comptar($debatId);

        if ($debat['aforament'] !== null && $inscrits + $quantitat > (int) $debat['aforament']) {
            $missatge = ['tipus' => 'danger', 'text' => 'No queden prou places per a aquesta quantitat.'];
        } else {
            try {
                InscripcioDebat::inscriure($debatId, $usuariId, $quantitat);
                $missatge = ['tipus' => 'success', 'text' => 'Inscripció confirmada.'];
            } catch (PDOException $e) {
                $missatge = ['tipus' => 'warning', 'text' => 'Ja estaves inscrit.'];
            }
        }
    } elseif ($accio === 'cancelar') {
        InscripcioDebat::cancelar($debatId, $usuariId);
        $missatge = ['tipus' => 'success', 'text' => 'Inscripció cancel·lada.'];
    }
}

$inscrits = InscripcioDebat::comptar($debatId);
$jaInscrit = InscripcioDebat::jaInscrit($debatId, $usuariId);
$quantitatPropia = $jaInscrit ? InscripcioDebat::quantitatInscrita($debatId, $usuariId) : 0;
$ple = $debat['aforament'] !== null && $inscrits >= (int) $debat['aforament'] && !$jaInscrit;
$maxQuantitat = $debat['aforament'] !== null ? max(1, min(10, (int) $debat['aforament'] - $inscrits)) : 10;

$horaInici = DateTime::createFromFormat('H:i:s', $debat['hora_sessio']);
$horaInici->modify('+' . (int) $debat['pelicula_durada'] . ' minutes');
$debatDateTime = DateTime::createFromFormat('Y-m-d H:i', $debat['data'] . ' ' . $horaInici->format('H:i'));
$haPassat = $debatDateTime < new DateTime();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<?php $pageTitle = 'Cine-fòrum — Hollywood Land'; require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/partials/navbar.php'; ?>

<div class="container flex-grow-1" style="max-width: 600px;">
    <h1 class="mb-1"><?= htmlspecialchars('Cine-fòrum: ' . ($debat['titol'] ?? $debat['pelicula_titol'])) ?></h1>
    <p class="text-muted">
        <?= htmlspecialchars($debat['data']) ?> — <?= $horaInici->format('H:i') ?>
        · <?= (int) $debat['durada_min'] ?> min
        <?php if ($debat['aforament'] !== null): ?>
            · <?= $inscrits ?>/<?= (int) $debat['aforament'] ?> places
        <?php endif; ?>
    </p>

    <?php if ($debat['descripcio']): ?>
        <p><?= nl2br(htmlspecialchars($debat['descripcio'])) ?></p>
    <?php endif; ?>

    <?php if ($missatge !== null): ?>
        <div class="alert alert-<?= $missatge['tipus'] ?>"><?= htmlspecialchars($missatge['text']) ?></div>
    <?php endif; ?>

    <?php if ($haPassat): ?>
        <div class="alert alert-warning">Aquest cine-fòrum ja ha passat.</div>
    <?php elseif ($jaInscrit): ?>
        <p>Inscrit amb <strong><?= $quantitatPropia ?></strong> <?= $quantitatPropia === 1 ? 'persona' : 'persones' ?>.</p>
        <form method="post" class="js-confirm" data-message="Vols cancel·lar la inscripció?">
            <input type="hidden" name="accio" value="cancelar">
            <button type="submit" class="btn btn-outline-dark">Cancel·la la inscripció</button>
        </form>
    <?php elseif ($ple): ?>
        <div class="alert alert-secondary">Aforament complet.</div>
    <?php else: ?>
        <form method="post" class="js-confirm" data-message="Vols inscriure't a aquest cine-fòrum?" data-quantitat-max="<?= $maxQuantitat ?>">
            <input type="hidden" name="accio" value="inscriure">
            <button type="submit" class="btn btn-dark">Inscriu-m'hi</button>
        </form>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
<?php require __DIR__ . '/partials/confirm_modal.php'; ?>
</body>
</html>
