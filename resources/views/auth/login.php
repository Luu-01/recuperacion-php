<h2 class="mb-4">Iniciar sesión</h2>

<form action="login.php" method="POST" class="mt-3">
    <?= csrf() ?>

    <!-- Credenciales -->
    <?php include __DIR__ . '/partials/credentials.php'; ?>

    <button type="submit" class="btn btn-primary">
        Entrar
    </button>
</form>

<p class="mt-3">
    ¿No tienes cuenta?
    <a href="register.php">Regístrate</a>
</p>
