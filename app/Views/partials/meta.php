<?php
$metaTitle = $__view->e($title ?? 'Starter');
$metaDescription = $__view->e($description ?? '');
$metaUrl = $canonical ?? '';
$metaImage = $ogImage ?? '';
?>
<?php if ($metaDescription): ?>
<meta name="description" content="<?= $metaDescription ?>">
<?php endif ?>
<meta property="og:title" content="<?= $metaTitle ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="<?= $metaUrl ?: $__view->url() ?>">
<?php if ($metaDescription): ?>
<meta property="og:description" content="<?= $metaDescription ?>">
<?php endif ?>
<?php if ($metaImage): ?>
<meta property="og:image" content="<?= $__view->e($metaImage) ?>">
<?php endif ?>
<meta name="twitter:card" content="<?= $metaImage ? 'summary_large_image' : 'summary' ?>">
<?php if ($metaUrl): ?>
<link rel="canonical" href="<?= $__view->e($metaUrl) ?>">
<?php endif ?>
