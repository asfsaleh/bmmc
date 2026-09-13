<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';

$pageTitle = $pageTitle ?? SITE_NAME;
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — <?= SITE_NAME_EN ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom Glassmorphism Stylesheet -->
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/navbar.php'; ?>

<!-- Flash Message Notification -->
<?php if ($flash): ?>
<div class="container mt-3">
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show glass-card border-0 d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle-fill fs-4 me-2"></i>
        <div><?= htmlspecialchars($flash['message']) ?></div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
<?php endif; ?>
