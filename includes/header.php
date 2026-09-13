<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';

$pageTitle = $pageTitle ?? SITE_NAME;
$flash = get_flash();

// OpenGraph & Meta Configuration for Facebook, WhatsApp, Messenger
$ogTitle = $ogTitle ?? ($pageTitle . ' — ' . SITE_NAME_EN);
$ogDescription = $ogDescription ?? 'বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি (BMMC) — মেরিনার ও সাধারণ নাগরিকদের জন্য জরুরি রক্তদান ও স্বেচ্ছাসেবী সহায়তা পোর্টাল। ৪ মাসের বিশ্রাম বিরতি সুরক্ষা ও স্বয়ংক্রিয় ম্যাচিং।';
$ogImage = $ogImage ?? (rtrim(BASE_URL, '/') . '/assets/images/og-image.jpg');
$currentUrl = $currentUrl ?? ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? "https" : "http") . "://" . ($_SERVER['HTTP_HOST'] ?? 'bmmc.skillsetup.org') . ($_SERVER['REQUEST_URI'] ?? ''));
?>
<!DOCTYPE html>
<html lang="bn" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — <?= SITE_NAME_EN ?></title>

    <!-- Primary SEO Meta Tags -->
    <meta name="title" content="<?= htmlspecialchars($ogTitle) ?>">
    <meta name="description" content="<?= htmlspecialchars($ogDescription) ?>">
    <meta name="author" content="Bangladesh Merchant Mariners Community (BMMC)">
    <meta name="theme-color" content="#061426">

    <!-- Open Graph / Facebook / WhatsApp / Messenger -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="BMMC — Bangladesh Merchant Mariners Community">
    <meta property="og:title" content="<?= htmlspecialchars($ogTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($ogDescription) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($ogImage) ?>">
    <meta property="og:image:secure_url" content="<?= htmlspecialchars($ogImage) ?>">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="BMMC Blood & Seafarers Welfare Network">
    <meta property="og:url" content="<?= htmlspecialchars($currentUrl) ?>">
    <meta property="og:locale" content="bn_BD">

    <!-- Twitter / X Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@BMMC_Mariners">
    <meta name="twitter:title" content="<?= htmlspecialchars($ogTitle) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($ogDescription) ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($ogImage) ?>">

    <!-- Favicon & Touch Icons -->
    <link rel="icon" type="image/jpeg" href="<?= BASE_URL ?>/assets/images/og-image.jpg">
    <link rel="apple-touch-icon" href="<?= BASE_URL ?>/assets/images/og-image.jpg">
    
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
