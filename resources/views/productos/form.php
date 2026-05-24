<form method="POST" action="<?= $action ?>" class="mt-4">
    <?= csrf() ?>
    <input type="hidden" name="id" value="<?= e($producto->id ?? '') ?>">

    <!-- Nombre -->
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>

        <input
            type="text"
            id="nombre"
            name="nombre"
            class="form-control <?= errors()->has('nombre') ? 'is-invalid' : '' ?>"
            value="<?= e(old('nombre', $producto->nombre ?? '')) ?>">

        <!-- Todos los errores -->
        <?php foreach (errors()->get('nombre') as $msg): ?>
            <div class="invalid-feedback"><?= e($msg) ?></div>
        <?php endforeach; ?>

    </div>

    <!-- Precio -->
    <div class="mb-3">
        <label for="precio" class="form-label">Precio (€)</label>

        <input
            type="text"
            id="precio"
            name="precio"
            step="0.01"
            class="form-control <?= errors()->has('precio') ? 'is-invalid' : '' ?>"
            value="<?= e(old('precio', $producto->precio ?? '')) ?>">


        <!-- Todos los errores -->
        <?php foreach (errors()->get('precio') as $msg): ?>
            <div class="invalid-feedback"><?= e($msg) ?></div>
        <?php endforeach; ?>
    </div>

    <!-- Stock -->
    <div class="mb-3">
        <label for="stock" class="form-label">Stock (ud)</label>

        <input
            type="text"
            id="stock"
            name="stock"
            class="form-control <?= errors()->has('stock') ? 'is-invalid' : '' ?>"
            value="<?= e(old('stock', $producto->stock ?? '')) ?>">

        <?php if (errors()->has('stock')): ?>
            <!-- Solo el primer error -->
            <div class="invalid-feedback"><?= e(error('stock')) ?></div>
        <?php endif; ?>
    </div>

    <!-- Categoría -->
    <div class="mb-3">
        <label for="categoria_id" class="form-label">Categoría</label>

        <select
            id="categoria_id"
            name="categoria_id"
            class="form-select <?= errors()->has('categoria_id') ? 'is-invalid' : '' ?>">
            <?php foreach ($categorias as $categoria): ?>
                <option
                    value="<?= e($categoria->id_categoria) ?>"
                    <?= old('categoria_id', $producto->categoria_id ?? '') == $categoria->id_categoria ? 'selected' : '' ?>>
                    <?= e($categoria->nombre_categoria) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Todos los errores -->
        <?php foreach (errors()->get('categoria_id') as $msg): ?>
            <div class="invalid-feedback"><?= e($msg) ?></div>
        <?php endforeach; ?>

    </div>

    <!-- Acción -->
    <div class="mt-3">
        <button type="submit" class="btn btn-primary">
            Guardar producto
        </button>
    </div>
    
</form>