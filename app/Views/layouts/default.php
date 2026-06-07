<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $__view->e($title ?? 'Starter') ?></title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <?php $__view->partial('partials/meta') ?>
    <link rel="stylesheet" href="/assets/css/open-props.min.css">
    <link rel="stylesheet" href="/assets/css/normalize.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="/assets/js/htmx.min.js" defer></script>
    <script src="/assets/js/alpine.min.js" defer></script>
</head>
<body>
    <?php $__view->partial('partials/header') ?>

    <main class="container">
        <?= $content ?>
    </main>

    <?php $__view->partial('partials/footer') ?>
</body>
</html>
