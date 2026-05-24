<h1 class="mb-4">Ficha de producto</h1>

<div class="card">
    <div class="card-body">

        <p class="mb-2">
            <strong>Nombre: </strong>
            <?= e($producto->nombre) ?>
        </p>

        <p class="mb-2">
            <strong>Precio: </strong>
            <?= e($producto->precio) ?> €
        </p>

        <p class="mb-2">
            <strong>Stock: </strong>
            <?= e($producto->stock) ?> unidades
        </p>

        <p class="mb-2">
            <strong>Categoría: </strong>
            <?= e($producto->categoria->nombre_categoria) ?>
        </p>

        <a href="<?= url('/productos/index.php') ?>" class="btn btn-secondary mt-3">Volver</a>

    </div>
</div>
