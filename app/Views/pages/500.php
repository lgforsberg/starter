<?php $__view->layout('layouts/default', ['title' => $title]) ?>

<div class="error-page">
    <h1>500</h1>
    <p>Something went wrong on our end.</p>
    <?php if (isset($exception)): ?>
        <details class="debug-details">
            <summary>Debug info</summary>
            <pre><?= $__view->e($exception->getMessage()) ?>

<?= $__view->e($exception->getTraceAsString()) ?></pre>
        </details>
    <?php endif ?>
    <a href="/" class="btn">Go home</a>
</div>
