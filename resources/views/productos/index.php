<h1 class="mb-4">Listado de productos</h1>

<!-- Filtros -->
<form method="get" action="<?= url('/productos') ?>" class="row g-3 mb-4">

    <div class="col-md-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" value="<?= e($nombre) ?>" class="form-control">
    </div>

    <div class="col-md-3">
        <label class="form-label">Categoría</label>
        <select name="categoria_id" class="form-select">
            <option value="">-- Todas --</option>
            <?php foreach ($categorias as $categoria): ?>
                <option
                    value="<?= e($categoria->id_categoria) ?>"
                    <?= ($categoria->id_categoria == $categoria_id) ? 'selected' : '' ?>>
                    <?= e($categoria->nombre_categoria) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-6 d-flex align-items-end">
        <button type="submit" class="btn btn-primary me-2">Filtrar</button>
        <a href="<?= url('/productos') ?>" class="btn btn-secondary">Limpiar</a>
    </div>

</form>

<p>
    <a href="<?= url('/productos/create') ?>" class="btn btn-success">Crear producto</a>
</p>

<div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio (€)</th>
                <th>Stock (ud)</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td class="text-end"><?= e($producto->id) ?></td>
                    <td><?= e($producto->nombre) ?></td>
                    <td class="text-end"><?= e($producto->precio) ?></td>
                    <td class="text-end"><?= e($producto->stock) ?></td>
                    <td><?= e($producto->categoria->nombre_categoria) ?></td>

                    <td>
                        <a href="<?= url('/productos/' . $producto->id) ?>" class="btn btn-sm btn-info text-white">Ver</a>
                        <a href="<?= url('/productos/' . $producto->id . '/edit') ?>" class="btn btn-sm btn-warning">Editar</a>

                        <form action="<?= url('/productos/' . $producto->id) ?>" method="post" class="d-inline">
                            <?= csrf() ?>
                            <input type="hidden" name="_method" value="DELETE">
                            <button
                                type="submit"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Eliminar este producto?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="mt-4">
    <?= $productos->links(); ?>
</div>
