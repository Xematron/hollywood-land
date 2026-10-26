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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

    <p><a href="registre.php">Crear compte nou</a></p>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
