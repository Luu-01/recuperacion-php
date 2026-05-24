<!-- Correo -->
    <div class="mb-3">
        <label for="correo" class="form-label">Correo electrónico</label>

        <input 
            type="text" 
            id="correo"
            name="correo" 
            class="form-control <?= errors()->has('correo') ? 'is-invalid' : '' ?>"
            value="<?= e(old('correo')) ?>"
        >

        <?php if (errors()->has('correo')): ?>
            <?php foreach (errors()->get('correo') as $msg): ?>
                <div class="invalid-feedback"><?= e($msg) ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Clave -->
    <div class="mb-3">
        <label for="clave" class="form-label">Clave secreta</label>

        <input 
            type="password" 
            id="clave"
            name="clave" 
            class="form-control <?= errors()->has('clave') ? 'is-invalid' : '' ?>"
        >

        <?php if (errors()->has('clave')): ?>
            <?php foreach (errors()->get('clave') as $msg): ?>
                <div class="invalid-feedback"><?= e($msg) ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>