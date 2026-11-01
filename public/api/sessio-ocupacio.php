<?php

require __DIR__ . '/../../src/bootstrap.php';

use HollywoodLand\Models\Butaca;
use HollywoodLand\Models\Entrada;
use HollywoodLand\Models\Sessio;

header('Content-Type: application/json; charset=utf-8');

$sessioId = (int) ($_GET['sessio_id'] ?? 0);
$sessio = Sessio::findById($sessioId);

if ($sessio === null) {
    http_response_code(404);
    echo json_encode(['error' => 'Sessió no trobada.']);
    exit;
}

$butaques = Butaca::perSala((int) $sessio['sala_id']);
$capacitat = count(array_filter($butaques, fn ($b) => (bool) $b['operativa']));
$ocupades = count(Entrada::ocupades($sessioId));

echo json_encode([
    'sessio_id' => $sessioId,
    'capacitat' => $capacitat,
    'ocupades' => $ocupades,
    'lliures' => $capacitat - $ocupades,
]);
