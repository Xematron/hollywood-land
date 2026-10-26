<?php

require __DIR__ . '/../src/bootstrap.php';

use HollywoodLand\Models\Usuari;

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $usuari = Usuari::verifyCredentials($email, $password);

    if ($usuari !== null) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $usuari['id'];
        $_SESSION['nom'] = $usuari['nom'];
        $_SESSION['rol'] = $usuari['rol'];

        header('Location: index.php');
        exit;
    }

    $error = 'Email o contrasenya incorrectes.';
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<?php $pageTitle = 'Iniciar sessió — Hollywood Land'; require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/partials/navbar.php'; ?>

<div class="container flex-grow-1" style="max-width: 420px;">
    <h1 class="mb-4">Iniciar sessió</h1>

    <?php if ($error !== null): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="login.php">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contrasenya</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-dark">Entrar</button>
    </form>

    <p class="mt-3"><a href="registre.php">Crear compte nou</a></p>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
