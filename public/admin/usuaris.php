<?php

require __DIR__ . '/../../src/bootstrap.php';

use HollywoodLand\Core\Auth;
use HollywoodLand\Models\Usuari;

Auth::requireRole('admin');

$usuariActual = Auth::user();
$error = null;
$success = null;
$rolsValids = ['admin', 'treballador', 'client'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accio = $_POST['accio'] ?? '';

    if ($accio === 'crear') {
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $rol = $_POST['rol'] ?? 'client';

        if ($nom === '' || $email === '' || $password === '') {
            $error = 'Cal omplir tots els camps per crear un usuari.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "L'email no és vàlid.";
        } elseif (!in_array($rol, $rolsValids, true)) {
            $error = 'Rol no vàlid.';
        } elseif (Usuari::findByEmail($email) !== null) {
            $error = 'Ja existeix un usuari amb aquest email.';
        } else {
            Usuari::create($nom, $email, $password, $rol);
            $success = 'Usuari creat correctament.';
        }
    } elseif ($accio === 'actualitzar') {
        $id = (int) ($_POST['id'] ?? 0);
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $rol = $_POST['rol'] ?? 'client';

        if ($nom === '' || $email === '') {
            $error = 'Cal omplir nom i email.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "L'email no és vàlid.";
        } elseif (!in_array($rol, $rolsValids, true)) {
            $error = 'Rol no vàlid.';
        } elseif ($id === (int) $usuariActual['id'] && $rol !== $usuariActual['rol']) {
            $error = 'No pots canviar el teu propi rol.';
        } else {
            Usuari::update($id, $nom, $email, $rol);
            $success = 'Usuari actualitzat correctament.';
        }
    } elseif ($accio === 'eliminar') {
        $id = (int) ($_POST['id'] ?? 0);

        if ($id === (int) $usuariActual['id']) {
            $error = 'No et pots eliminar a tu mateix.';
        } else {
            Usuari::delete($id);
            $success = 'Usuari eliminat correctament.';
        }
    }
}

$usuaris = Usuari::all();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
<?php $pageTitle = "Gestió d'usuaris — Hollywood Land"; require __DIR__ . '/../partials/head.php'; ?>
</head>
<body>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container flex-grow-1">
    <h1 class="mb-4">Gestió d'usuaris</h1>
    <a href="index.php" class="btn btn-outline-dark btn-sm mb-3">Tornar al panell admin</a>

    <?php if ($error !== null): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success !== null): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <h2 class="mt-4">Usuaris existents</h2>
    <?php foreach ($usuaris as $usuari): ?>
        <div class="d-flex flex-wrap gap-2 align-items-center mb-2 pb-2 border-bottom">
        <form method="post" class="d-flex flex-wrap gap-2 align-items-center">
            <input type="hidden" name="id" value="<?= (int) $usuari['id'] ?>">
            <input type="text" name="nom" value="<?= htmlspecialchars($usuari['nom']) ?>" class="form-control form-control-sm w-auto" required>
            <input type="email" name="email" value="<?= htmlspecialchars($usuari['email']) ?>" class="form-control form-control-sm w-auto" required>
            <select name="rol" class="form-select form-select-sm w-auto">
                <?php foreach ($rolsValids as $rol): ?>
                    <option value="<?= $rol ?>" <?= $rol === $usuari['rol'] ? 'selected' : '' ?>><?= $rol ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="accio" value="actualitzar" class="btn btn-sm btn-dark">Desar</button>
        </form>
        <form method="post" class="js-confirm" data-message="Eliminar aquest usuari?">
            <input type="hidden" name="id" value="<?= (int) $usuari['id'] ?>">
            <input type="hidden" name="accio" value="eliminar">
            <button type="submit" class="btn btn-sm btn-outline-dark">Eliminar</button>
        </form>
        </div>
    <?php endforeach; ?>

    <h2 class="mt-5">Crear usuari nou</h2>
    <form method="post" style="max-width: 420px;">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" id="nom" name="nom" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contrasenya</label>
            <input type="password" id="password" name="password" class="form-control" required minlength="8">
        </div>
        <div class="mb-3">
            <label for="rol" class="form-label">Rol</label>
            <select id="rol" name="rol" class="form-select">
                <?php foreach ($rolsValids as $rol): ?>
                    <option value="<?= $rol ?>"><?= $rol ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" name="accio" value="crear" class="btn btn-dark">Crear</button>
    </form>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
<?php require __DIR__ . '/../partials/confirm_modal.php'; ?>
</body>
</html>
