<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Tienda' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= url('/css/app.css') ?>">
</head>
<body>

    <?php require __DIR__ . '/partials/header.php'; ?>

    <main class="container py-4">
        <?php include __DIR__ . '/partials/flash.php'; ?>
        <?= $content ?? '' ?>
    </main>

    <?php require __DIR__ . '/partials/footer.php'; ?>

</body>
</html>
