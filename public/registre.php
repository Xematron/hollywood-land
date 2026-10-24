<?php

require __DIR__ . '/../src/bootstrap.php';

use HollywoodLand\Models\Usuari;

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    if ($nom === '' || $email === '' || $password === '') {
        $error = 'Cal omplir tots els camps.';
    } elseif (strlen($password) < 8) {
        $error = 'La contrasenya ha de tenir almenys 8 caràcters.';
    } elseif ($password !== $passwordConfirm) {
        $error = 'Les contrasenyes no coincideixen.';
    } elseif (Usuari::findByEmail($email) !== null) {
        $error = 'Ja existeix un usuari amb aquest email.';
    } else {
        Usuari::create($nom, $email, $password, 'client');

        header('Location: login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Registre — Hollywood Land</title>
</head>
<body>
    <h1>Crear compte</h1>

    <?php if ($error !== null): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="registre.php">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

        <label for="password">Contrasenya</label>
        <input type="password" id="password" name="password" required minlength="8">

        <label for="password_confirm">Repeteix la contrasenya</label>
        <input type="password" id="password_confirm" name="password_confirm" required minlength="8">

        <button type="submit">Registrar-me</button>
    </form>

    <p><a href="login.php">Ja tinc compte</a></p>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
