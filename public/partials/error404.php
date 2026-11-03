<?php
// Inclòs des d'un entry point amb: http_response_code(404); require __DIR__ . '/partials/error404.php'; exit;
// Cal definir $missatge404 abans d'incloure'l.
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<?php $pageTitle = 'Pàgina no trobada — Hollywood Land'; require __DIR__ . '/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/navbar.php'; ?>

<div class="container flex-grow-1 text-center py-5">
    <h1 class="mb-3">404</h1>
    <p class="lead"><?= htmlspecialchars($missatge404 ?? 'Pàgina no trobada.') ?></p>
    <a href="/index.php" class="btn btn-dark mt-3">Tornar a l'inici</a>
</div>
<?php require __DIR__ . '/footer.php'; ?>
</body>
</html>
