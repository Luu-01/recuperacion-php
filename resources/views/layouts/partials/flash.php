<?php if ($message = session()->getFlash('error')): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<?php if ($message = session()->getFlash('success')): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>