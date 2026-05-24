<?php if ($paginator->hasPages()): ?>
    <nav aria-label="Paginación" class="mt-3">
        <ul class="pagination justify-content-center">

            <!-- Botón Anterior -->
            <?php if (!$paginator->onFirstPage()): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $paginator->previousPageUrl() ?>">Anterior</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <span class="page-link">Anterior</span>
                </li>
            <?php endif; ?>

            <!-- Texto de página actual -->
            <li class="page-item disabled">
                <span class="page-link">
                    Página <?= $paginator->currentPage() ?> de <?= $paginator->lastPage() ?>
                </span>
            </li>

            <!-- Botón Siguiente -->
            <?php if ($paginator->hasMorePages()): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $paginator->nextPageUrl() ?>">Siguiente</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <span class="page-link">Siguiente</span>
                </li>
            <?php endif; ?>

        </ul>
    </nav>
<?php endif; ?>
