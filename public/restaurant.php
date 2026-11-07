<?php

require __DIR__ . '/../src/bootstrap.php';
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<?php $pageTitle = 'Restaurant - Hollywood Land'; require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/partials/navbar.php'; ?>

<div class="container flex-grow-1">
    <h1 class="mt-4">Restaurant</h1>
    <p class="lead"><strong>El nostre diner temàtic dels anys 50.</strong></p>

    <p>
        Abans o després de la sessió, para't a la nostra cafeteria ambientada en l'època
        daurada de Hollywood: neons, estrelles de la fama i la banda sonora dels clàssics
        que projectem a les sales. Un espai pensat per allargar la conversa del cine-fòrum
        amb un cafè, un batut o un menú complet abans de tornar a casa.
    </p>

    <p class="text-muted">
        Nota: per respecte a les sales de projecció, no es permet entrar-hi amb menjar ni beguda.
    </p>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
