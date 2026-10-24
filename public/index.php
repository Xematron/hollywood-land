<?php

require __DIR__ . '/../src/bootstrap.php';

use HollywoodLand\Models\Activitat;
use HollywoodLand\Models\Debat;
use HollywoodLand\Models\Sessio;

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
        'hora' => substr($s['hora'], 0, 5),
        'titol' => $s['titol'],
        'detall' => (int) $s['durada_min'] . ' min' . ($s['classificacio'] ? ' · ' . $s['classificacio'] : ''),
        'poster' => $s['titol'],
    ]);
}

foreach (Debat::propers() as $d) {
    $titol = 'Cine-fòrum: ' . ($d['titol'] ?? $d['pelicula_titol']);
    afegirAlDia($dies, $d['data'], [
        'tipus' => 'debat',
        'hora' => sumaMinuts($d['hora_sessio'], (int) $d['pelicula_durada']),
        'titol' => $titol,
        'detall' => $d['durada_min'] . ' min' . ($d['aforament'] ? ' · aforament ' . $d['aforament'] : ''),
        'poster' => $titol,
    ]);
}

foreach (Activitat::properes() as $a) {
    $titol = ucfirst($a['tipus']) . ': ' . $a['titol'];
    afegirAlDia($dies, $a['data'], [
        'tipus' => 'activitat',
        'hora' => substr($a['hora'], 0, 5),
        'titol' => $titol,
        'detall' => $a['durada_min'] . ' min',
        'poster' => $titol,
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
    <meta charset="UTF-8">
    <title>Hollywood Land</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand">Hollywood Land</span>
        <div>
            <a class="btn btn-sm btn-outline-light me-2" href="cartellera.php">Cartellera</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="text-light me-3"><?= htmlspecialchars($_SESSION['nom']) ?> (<?= htmlspecialchars($_SESSION['rol']) ?>)</span>
                <?php if ($_SESSION['rol'] === 'admin'): ?>
                    <a class="btn btn-sm btn-outline-light me-2" href="admin/index.php">Zona Admin</a>
                <?php endif; ?>
                <?php if (in_array($_SESSION['rol'], ['taquiller', 'admin'], true)): ?>
                    <a class="btn btn-sm btn-outline-light me-2" href="treballador/index.php">Zona Treballador</a>
                <?php endif; ?>
                <a class="btn btn-sm btn-outline-light" href="logout.php">Tancar sessió</a>
            <?php else: ?>
                <a class="btn btn-sm btn-outline-light" href="login.php">Iniciar sessió</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container">
    <h1 class="mb-4">Cartellera</h1>
    <?php if (empty($dies)): ?>
        <p>No hi ha sessions properes.</p>
    <?php else: ?>
        <?php foreach ($dies as $dia): ?>
            <h2 class="mt-5 mb-3 border-bottom pb-2"><?= htmlspecialchars($dia['etiqueta']) ?></h2>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                <?php foreach ($dia['items'] as $item): ?>
                <?php $esSessio = $item['tipus'] === 'sessio'; ?>
                <div class="col">
                    <div class="card h-100 shadow-sm<?= $esSessio ? '' : ' border-warning' ?>">
                        <img src="https://placehold.co/300x450?text=<?= urlencode($item['poster']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['titol']) ?>">
                        <div class="card-body">
                            <span class="badge <?= $esSessio ? 'text-bg-dark' : 'text-bg-warning text-dark' ?> mb-2"><?= htmlspecialchars($item['hora']) ?>h</span>
                            <h5 class="card-title"><?= htmlspecialchars($item['titol']) ?></h5>
                            <p class="card-text text-muted mb-0"><?= htmlspecialchars($item['detall']) ?></p>
                        </div>
                        <?php if ($esSessio): ?>
                            <div class="card-footer bg-white border-0">
                                <a href="cartellera.php" class="btn btn-sm btn-dark w-100">Veure sessions</a>
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
</body>
</html>
