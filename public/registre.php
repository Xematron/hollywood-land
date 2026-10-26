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
<?php $pageTitle = 'Registre — Hollywood Land'; require __DIR__ . '/partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/partials/navbar.php'; ?>

<div class="container flex-grow-1" style="max-width: 420px;">
    <h1 class="mb-4">Crear compte</h1>

    <?php if ($error !== null): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="registre.php">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contrasenya</label>
            <input type="password" id="password" name="password" class="form-control" required minlength="8">
        </div>
        <div class="mb-3">
            <label for="password_confirm" class="form-label">Repeteix la contrasenya</label>
            <input type="password" id="password_confirm" name="password_confirm" class="form-control" required minlength="8">
        </div>
        <button type="submit" class="btn btn-dark">Registrar-me</button>
    </form>

    <p class="mt-3"><a href="login.php">Ja tinc compte</a></p>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
