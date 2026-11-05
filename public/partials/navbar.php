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
                    <?php if ($_SESSION['rol'] === 'treballador'): ?>
                        <a class="btn btn-sm btn-outline-light text-gold-light me-2" href="/treballador/index.php">Zona Treballador</a>
                    <?php endif; ?>
                    <a class="btn btn-sm btn-outline-light text-gold-light" href="/logout.php">Tancar sessió</a>
                <?php else: ?>
                    <a class="btn btn-sm btn-outline-light text-gold-light me-2" href="/login.php">Iniciar sessió</a>
                    <a class="btn btn-sm btn-outline-light text-gold-light" href="/registre.php">Registra't</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <nav class="navbar navbar-dark navbar-links py-1 mb-4">
        <div class="container my-2 d-flex justify-content-start gap-4">
            <a class="links text-chestnut" href="/cartellera.php">Comprar entrades</a>
            <?php if (isset($_SESSION['user_id']) && $_SESSION['rol'] === 'client'): ?>
                <a class="links text-chestnut" href="/les-meves-entrades.php">Les meves entrades</a>
            <?php endif; ?>
            <a class="links text-chestnut" href="/restaurant.php">Restaurant</a>
        </div>
    </nav>
</header>
