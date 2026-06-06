<?php $__view->layout('layouts/default', ['title' => $title]) ?>

<h1>Contact</h1>

<?php if ($success): ?>
    <div class="alert alert--success"><?= $__view->e($success) ?></div>
<?php endif ?>

<form method="post" action="/contact" class="form">
    <?= $csrf->field() ?>

    <div class="form-group <?= isset($errors['name']) ? 'has-error' : '' ?>">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?= $__view->e($old['name'] ?? '') ?>" required>
        <?php if (isset($errors['name'])): ?>
            <span class="error"><?= $__view->e($errors['name']) ?></span>
        <?php endif ?>
    </div>

    <div class="form-group <?= isset($errors['email']) ? 'has-error' : '' ?>">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= $__view->e($old['email'] ?? '') ?>" required>
        <?php if (isset($errors['email'])): ?>
            <span class="error"><?= $__view->e($errors['email']) ?></span>
        <?php endif ?>
    </div>

    <div class="form-group <?= isset($errors['message']) ? 'has-error' : '' ?>">
        <label for="message">Message</label>
        <textarea id="message" name="message" rows="5" required><?= $__view->e($old['message'] ?? '') ?></textarea>
        <?php if (isset($errors['message'])): ?>
            <span class="error"><?= $__view->e($errors['message']) ?></span>
        <?php endif ?>
    </div>

    <button type="submit" class="btn">Send Message</button>
</form>
