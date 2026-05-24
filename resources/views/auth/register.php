<h2 class="mb-4">Registro de nuevo usuario</h2>

<form action="register.php" method="POST" class="mt-3">
    <?= csrf() ?>
    
    <!-- Nombre -->
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>

        <input 
            type="text"
            id="nombre"
            name="nombre"
            class="form-control <?= errors()->has('nombre') ? 'is-invalid' : '' ?>"
            value="<?= e(old('nombre')) ?>"
        >

        <?php if (errors()->has('nombre')): ?>
            <?php foreach (errors()->get('nombre') as $msg): ?>
                <div class="invalid-feedback"><?= e($msg) ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Credenciales -->
    <?php include __DIR__ . '/partials/credentials.php'; ?>

    <button type="submit" class="btn btn-success">
        Registrarse
    </button>
</form>
