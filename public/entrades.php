<?php

require __DIR__ . '/../src/bootstrap.php';

use HollywoodLand\Core\Auth;
use HollywoodLand\Models\Butaca;
use HollywoodLand\Models\Entrada;
use HollywoodLand\Models\Sessio;

Auth::requireLogin();

$sessioId = (int) ($_GET['sessio_id'] ?? 0);
$sessio = Sessio::findDetallById($sessioId);

if ($sessio === null) {
    http_response_code(404);
    exit('Sessió no trobada.');
}

$sessioInici = DateTime::createFromFormat('Y-m-d H:i:s', $sessio['data'] . ' ' . $sessio['hora']);
$haPassat = $sessioInici < new DateTime();

$butaques = Butaca::perSala($sessio['sala_id']);
$ocupades = Entrada::ocupades($sessioId);

$files = [];
foreach ($butaques as $butaca) {
    $files[$butaca['fila']][] = $butaca;
}
ksort($files);
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<?php $pageTitle = 'Comprar entrades — Hollywood Land'; require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/partials/navbar.php'; ?>

<div class="container flex-grow-1">
    <h1 class="mb-1"><?= htmlspecialchars($sessio['titol']) ?></h1>
    <p class="text-muted">
        <?= htmlspecialchars($sessio['data']) ?> — <?= substr($sessio['hora'], 0, 5) ?>
        · <?= htmlspecialchars($sessio['sala_nom']) ?>
        · <?= number_format((float) $sessio['preu'], 2) ?> €/entrada
    </p>

    <?php if ($haPassat): ?>
        <div class="alert alert-warning">Aquesta sessió ja ha passat, no es poden comprar entrades.</div>
    <?php else: ?>
        <div class="mb-3 d-flex gap-3">
            <span><span class="seat-sample seat-lliure"></span> Lliure</span>
            <span><span class="seat-sample seat-ocupada"></span> Ocupada</span>
            <span><span class="seat-sample seat-fora"></span> Fora de servei</span>
        </div>

        <form method="post" action="entrades_confirmar.php" id="form-entrades">
            <input type="hidden" name="sessio_id" value="<?= $sessio['id'] ?>">

            <div class="mb-4">
                <?php foreach ($files as $fila => $butaquesFila): ?>
                    <div class="d-flex align-items-center mb-2">
                        <strong class="me-3" style="width: 1.5rem;"><?= htmlspecialchars($fila) ?></strong>
                        <div class="d-flex gap-2 flex-wrap">
                            <?php foreach ($butaquesFila as $butaca): ?>
                                <?php
                                $id = (int) $butaca['id'];
                                $foraServei = !$butaca['operativa'];
                                $ocupada = in_array($id, $ocupades, true);
                                $disabled = $foraServei || $ocupada;
                                $classe = $foraServei ? 'seat-fora' : ($ocupada ? 'seat-ocupada' : 'seat-lliure');
                                ?>
                                <label class="seat <?= $classe ?>">
                                    <input
                                        type="checkbox"
                                        name="butaca_id[]"
                                        value="<?= $id ?>"
                                        data-preu="<?= (float) $sessio['preu'] ?>"
                                        class="seat-checkbox"
                                        <?= $disabled ? 'disabled' : '' ?>
                                    >
                                    <?= (int) $butaca['numero'] ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="d-flex align-items-center gap-3">
                <p class="mb-0">Butaques seleccionades: <strong id="num-seleccionades">0</strong></p>
                <p class="mb-0">Total: <strong id="total-preu">0.00 €</strong></p>
                <button type="submit" class="btn btn-dark" id="btn-comprar" disabled>Confirmar compra</button>
            </div>
        </form>

        <script>
        (function () {
            var checkboxes = document.querySelectorAll('.seat-checkbox');
            var numEl = document.getElementById('num-seleccionades');
            var totalEl = document.getElementById('total-preu');
            var btn = document.getElementById('btn-comprar');

            function actualitza() {
                var seleccionades = document.querySelectorAll('.seat-checkbox:checked');
                var total = 0;
                seleccionades.forEach(function (cb) {
                    total += parseFloat(cb.dataset.preu);
                });
                numEl.textContent = seleccionades.length;
                totalEl.textContent = total.toFixed(2) + ' €';
                btn.disabled = seleccionades.length === 0;
            }

            checkboxes.forEach(function (cb) {
                cb.addEventListener('change', actualitza);
            });
        })();
        </script>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
