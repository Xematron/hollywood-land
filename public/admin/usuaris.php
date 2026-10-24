<?php

require __DIR__ . '/../../src/bootstrap.php';

use HollywoodLand\Core\Auth;
use HollywoodLand\Models\Usuari;

Auth::requireRole('admin');

$usuariActual = Auth::user();
$error = null;
$success = null;
$rolsValids = ['admin', 'taquiller', 'client'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accio = $_POST['accio'] ?? '';

    if ($accio === 'crear') {
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $rol = $_POST['rol'] ?? 'client';

        if ($nom === '' || $email === '' || $password === '') {
            $error = 'Cal omplir tots els camps per crear un usuari.';
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

        if (!in_array($rol, $rolsValids, true)) {
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
    <meta charset="UTF-8">
    <title>Gestió d'usuaris — Hollywood Land</title>
</head>
<body>
    <h1>Gestió d'usuaris</h1>
    <p><a href="index.php">Tornar al panell admin</a></p>

    <?php if ($error !== null): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if ($success !== null): ?>
        <p style="color:green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <h2>Usuaris existents</h2>
    <?php foreach ($usuaris as $usuari): ?>
        <form method="post" style="display:flex; gap:0.5rem; align-items:center; margin-bottom:0.5rem;">
            <input type="hidden" name="id" value="<?= (int) $usuari['id'] ?>">
            <input type="text" name="nom" value="<?= htmlspecialchars($usuari['nom']) ?>" required>
            <input type="email" name="email" value="<?= htmlspecialchars($usuari['email']) ?>" required>
            <select name="rol">
                <?php foreach ($rolsValids as $rol): ?>
                    <option value="<?= $rol ?>" <?= $rol === $usuari['rol'] ? 'selected' : '' ?>><?= $rol ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="accio" value="actualitzar">Desar</button>
            <button type="submit" name="accio" value="eliminar" onclick="return confirm('Eliminar aquest usuari?');">Eliminar</button>
        </form>
    <?php endforeach; ?>

    <h2>Crear usuari nou</h2>
    <form method="post">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Contrasenya</label>
        <input type="password" id="password" name="password" required minlength="8">

        <label for="rol">Rol</label>
        <select id="rol" name="rol">
            <?php foreach ($rolsValids as $rol): ?>
                <option value="<?= $rol ?>"><?= $rol ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" name="accio" value="crear">Crear</button>
    </form>
</body>
</html>
