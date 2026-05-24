<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Tienda' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEwYlCbvRrpJQlmq2HTS3dfzKCQ5Ww69NmD9RmoYOkKPzYcAgFEAgcf" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
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
