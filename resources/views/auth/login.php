<h2 class="mb-4">Iniciar sesión</h2>

<form action="<?= url('/login') ?>" method="POST" class="mt-3">
    <?= csrf() ?>

    <!-- Credenciales -->
    <?php include __DIR__ . '/partials/credentials.php'; ?>

    <button type="submit" class="btn btn-primary">
        Entrar
    </button>
</form>

<p class="mt-3">
    ¿No tienes cuenta?
    <a href="<?= url('/register') ?>">Regístrate</a>
</p>
