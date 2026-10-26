<header>
    <nav class="navbar navbar-dark">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="/index.php">Hollywood Land</a>
            <div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="text-light me-3">Hola <?= htmlspecialchars($_SESSION['nom']) ?></span>
                    <?php if ($_SESSION['rol'] === 'admin'): ?>
                        <a class="btn btn-sm btn-outline-light text-gold-light me-2" href="/admin/index.php">Zona Admin</a>
                    <?php endif; ?>
                    <?php if (in_array($_SESSION['rol'], ['taquiller', 'admin'], true)): ?>
                        <a class="btn btn-sm btn-outline-light text-gold-light me-2" href="/treballador/index.php">Zona Treballador</a>
                    <?php endif; ?>
                    <a class="btn btn-sm btn-outline-light text-gold-light" href="/logout.php">Tancar sessió</a>
                <?php else: ?>
                    <a class="btn btn-sm btn-outline-light text-gold-light" href="/login.php">Iniciar sessió</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <nav class="navbar-dark navbar-links py-1 mb-4">
        <div class="container my-2">
            <a class="links text-chestnut" href="/cartellera.php">Cartellera</a>
        </div>
    </nav>
</header>
