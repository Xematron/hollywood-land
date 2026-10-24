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
    <meta charset="UTF-8">
    <title>Iniciar sessió — Hollywood Land</title>
</head>
<body>
    <h1>Iniciar sessió</h1>

    <?php if ($error !== null): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="login.php">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Contrasenya</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Entrar</button>
    </form>
</body>
</html>
