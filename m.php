<?php
/**
 * BMMC Maintenance Mode Controller & Switcher
 * URL: /m or /m.php
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

$maintenanceFile = __DIR__ . '/config/maintenance.json';

// Default config
$defaultConfig = [
    'enabled' => true,
    'title' => 'সাইটের যান্ত্রিক রক্ষণাবেক্ষণ চলছে — BMMC',
    'headline' => 'সাইটের সিস্টেম আপগ্রেড ও যান্ত্রিক রক্ষণাবেক্ষণ চলছে',
    'message' => 'সম্মানিত মেরিনার ও ভিজিটরবৃন্দ, বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি (BMMC) পোর্টালটির ইঞ্জিন ও ডাটাবেস সিস্টেম আপগ্রেডের কাজ চলছে। উন্নত সেবা ও নির্ভরযোগ্য রক্তদান নেটওয়ার্ক নিশ্চিত করতে সাইটটি সাময়িকভাবে রক্ষণাবেক্ষণ মোডে রয়েছে।',
    'estimated_time' => 'খুব শীঘ্রই আমরা পুনরায় লাইভে আসছি',
    'contact_phone' => '+8801711000000',
    'contact_email' => 'admin@bmmc.org',
    'updated_at' => date('Y-m-d H:i:s')
];

// Load current configuration
$config = $defaultConfig;
if (file_exists($maintenanceFile)) {
    $loaded = json_decode(file_get_contents($maintenanceFile), true);
    if (is_array($loaded)) {
        $config = array_merge($defaultConfig, $loaded);
    }
}

$action = $_GET['action'] ?? '';
$isCurrentlyEnabled = !empty($config['enabled']);

// Handle Toggle Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'toggle') {
    if (!validate_csrf()) {
        set_flash('danger', 'নিরাপত্তা টোকেন সঠিক নয়। আবার চেষ্টা করুন।');
        header('Location: ' . BASE_URL . '/m.php');
        exit;
    }

    $newState = !$isCurrentlyEnabled;
    $config['enabled'] = $newState;
    $config['updated_at'] = date('Y-m-d H:i:s');

    file_put_contents($maintenanceFile, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    if ($newState) {
        set_flash('warning', '⚠️ <strong>মেইনটেন্যান্স মোড সক্রিয় করা হয়েছে!</strong> এখন সাধারণ সকল ভিজিটরের জন্য গিয়ার অ্যানিমেশন ও স্পার্ক সহ মেইনটেন্যান্স পেজ প্রদর্শিত হবে।');
    } else {
        set_flash('success', '🚀 <strong>মেইনটেন্যান্স মোড বন্ধ করা হয়েছে!</strong> ওয়েবসাইটটি এখন সবার জন্য স্বাভাবিকভাবে লাইভ রয়েছে।');
    }

    header('Location: ' . BASE_URL . '/m.php');
    exit;
}

// Handle Update Content Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update_content') {
    if (!validate_csrf()) {
        set_flash('danger', 'নিরাপত্তা টোকেন সঠিক নয়।');
        header('Location: ' . BASE_URL . '/m.php');
        exit;
    }

    $config['headline'] = sanitize($_POST['headline'] ?? $config['headline']);
    $config['message'] = sanitize($_POST['message'] ?? $config['message']);
    $config['estimated_time'] = sanitize($_POST['estimated_time'] ?? $config['estimated_time']);
    $config['contact_phone'] = sanitize($_POST['contact_phone'] ?? $config['contact_phone']);
    $config['updated_at'] = date('Y-m-d H:i:s');

    file_put_contents($maintenanceFile, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    set_flash('success', 'মেইনটেন্যান্স নোটিশের তথ্য সফলভাবে আপডেট করা হয়েছে।');
    header('Location: ' . BASE_URL . '/m.php');
    exit;
}

// Handle Admin Bypass Toggle (Allows admin to view the live site even if maintenance is on)
if ($action === 'toggle_bypass') {
    $_SESSION['maintenance_bypass'] = empty($_SESSION['maintenance_bypass']);
    if ($_SESSION['maintenance_bypass']) {
        set_flash('info', '🔓 <strong>বাইপাস মোড সক্রিয়:</strong> আপনার ব্রাউজারে সাইটটি সাধারণ অবস্থায় প্রদর্শিত হবে।');
    } else {
        set_flash('info', '🔒 <strong>বাইপাস মোড নিষ্ক্রিয়:</strong> এখন আপনিও মেইনটেন্যান্স পেজটি দেখতে পাবেন।');
    }
    header('Location: ' . BASE_URL . '/m.php');
    exit;
}

$hasBypass = !empty($_SESSION['maintenance_bypass']);
$pageTitle = 'মেইনটেন্যান্স কন্ট্রোল প্যানেল — BMMC';
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    
    <!-- Google Fonts & Bootstrap 5 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&family=Orbitron:wght@600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background-color: #061426;
            color: #f8fafc;
            min-height: 100vh;
            background-image: 
                radial-gradient(at 0% 0%, rgba(19, 64, 116, 0.5) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(217, 4, 41, 0.2) 0px, transparent 50%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .glass-panel {
            background: rgba(11, 37, 69, 0.75);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5);
        }

        .status-badge {
            font-family: 'Orbitron', sans-serif;
            letter-spacing: 1px;
            font-size: 0.9rem;
            padding: 8px 18px;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .status-live {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid #10b981;
            color: #34d399;
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.3);
        }

        .status-maintenance {
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid #f59e0b;
            color: #fbbf24;
            box-shadow: 0 0 20px rgba(245, 158, 11, 0.4);
        }

        .btn-toggle-main {
            font-size: 1.15rem;
            font-weight: 700;
            padding: 16px 28px;
            border-radius: 40px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            cursor: pointer;
        }

        .btn-turn-off {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            box-shadow: 0 6px 25px rgba(16, 185, 129, 0.45);
        }
        .btn-turn-off:hover {
            background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.6);
            color: #fff;
        }

        .btn-turn-on {
            background: linear-gradient(135deg, #ef233c 0%, #b91c1c 100%);
            color: #ffffff;
            box-shadow: 0 6px 25px rgba(239, 35, 60, 0.45);
        }
        .btn-turn-on:hover {
            background: linear-gradient(135deg, #ff3b53 0%, #dc2626 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(239, 35, 60, 0.6);
            color: #fff;
        }

        .form-control, .form-control:focus {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            color: #fff !important;
            border-radius: 10px;
        }

        .gear-spin {
            animation: spin 8s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <nav class="navbar navbar-dark py-3 border-bottom border-secondary border-opacity-25" style="background: rgba(6, 20, 38, 0.85); backdrop-filter: blur(10px);">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= BASE_URL ?>/index.php">
                <i class="bi bi-compass text-info fs-3"></i>
                <span class="fw-bold">BMMC SYSTEM CONTROLLER</span>
            </a>
            <div class="d-flex align-items-center gap-2">
                <a href="<?= BASE_URL ?>/maintenance.php" target="_blank" class="btn btn-outline-warning btn-sm rounded-pill px-3">
                    <i class="bi bi-eye me-1"></i> মেইনটেন্যান্স প্রিভিউ
                </a>
                <a href="<?= BASE_URL ?>/index.php" class="btn btn-outline-light btn-sm rounded-pill px-3">
                    <i class="bi bi-globe me-1"></i> সাইটে যান
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container py-5 flex-grow-1">

        <!-- Flash Message -->
        <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show glass-panel border-0 text-white mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill fs-4 me-2 text-info"></i>
                <div><?= $flash['message'] ?></div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Status Card & Master Switch -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8">
                <div class="glass-panel p-4 p-md-5 text-center">

                    <div class="mb-3">
                        <?php if ($isCurrentlyEnabled): ?>
                            <span class="status-badge status-maintenance">
                                <i class="bi bi-gear-wide-connected gear-spin fs-5"></i>
                                MAINTENANCE MODE ACTIVE
                            </span>
                        <?php else: ?>
                            <span class="status-badge status-live">
                                <i class="bi bi-check-circle-fill fs-5"></i>
                                WEBSITE IS LIVE & PUBLIC
                            </span>
                        <?php endif; ?>
                    </div>

                    <h2 class="fw-bold text-white mb-2">
                        <?php if ($isCurrentlyEnabled): ?>
                            সাইটটি বর্তমানে <span class="text-warning">রক্ষণাবেক্ষণ (Maintenance)</span> মোডে আছে
                        <?php else: ?>
                            সাইটটি বর্তমানে <span class="text-success">অনলাইন (Live)</span> আছে
                        <?php endif; ?>
                    </h2>

                    <p class="text-secondary mb-4 mx-auto" style="max-width: 600px;">
                        <?php if ($isCurrentlyEnabled): ?>
                            যেকোনো সাধারণ ভিজিটর সাইটে ঢুকলে মেকানিক্যাল ইঞ্জিনিয়ারিংয়ের গিয়ার রোটেশন, স্পার্কিং এবং ভাইব্রেশন ইফেক্ট সহ <strong>"Site Under Maintenance"</strong> পেজ দেখতে পাবে।
                        <?php else: ?>
                            সকল পেজ এবং রক্তদান সেবা সাধারণ ব্যবহারকারীদের জন্য উন্মুক্ত রয়েছে।
                        <?php endif; ?>
                    </p>

                    <!-- The Master 1-Click Toggle Button -->
                    <form action="<?= BASE_URL ?>/m.php?action=toggle" method="POST">
                        <?= csrf_field() ?>
                        <?php if ($isCurrentlyEnabled): ?>
                            <button type="submit" class="btn-toggle-main btn-turn-off w-100 shadow-lg" onclick="return confirm('আপনি কি নিশ্চিত যে মেইনটেন্যান্স মোড বন্ধ করে সাইট লাইভ করতে চান?');">
                                <i class="bi bi-power fs-3"></i>
                                <span>মেইনটেন্যান্স বন্ধ করুন ও সাইট লাইভ করুন ➔</span>
                            </button>
                        <?php else: ?>
                            <button type="submit" class="btn-toggle-main btn-turn-on w-100 shadow-lg" onclick="return confirm('সতর্কতা: মেইনটেন্যান্স মোড চালু করলে সাধারণ ভিজিটররা সাইট ব্রাউজ করতে পারবে না। চালু করবেন?');">
                                <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                                <span>মেইনটেন্যান্স মোড চালু করুন (Turn ON) ➔</span>
                            </button>
                        <?php endif; ?>
                    </form>

                    <!-- Quick Bypass & Controls -->
                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 mt-4 pt-3 border-top border-secondary border-opacity-25">
                        <span class="text-secondary small">
                            <i class="bi bi-clock-history me-1"></i> সর্বশেষ আপডেট: <?= htmlspecialchars($config['updated_at'] ?? 'N/A') ?>
                        </span>
                        
                        <a href="<?= BASE_URL ?>/m.php?action=toggle_bypass" class="btn btn-sm btn-outline-info rounded-pill px-3">
                            <i class="bi <?= $hasBypass ? 'bi-unlock-fill' : 'bi-lock-fill' ?> me-1"></i>
                            <?= $hasBypass ? 'বাইপাস বন্ধ করুন (View Maintenance)' : 'অ্যাডমিন বাইপাস সক্রিয় করুন (Browse Live)' ?>
                        </a>

                        <a href="<?= BASE_URL ?>/maintenance.php" target="_blank" class="btn btn-sm btn-outline-light rounded-pill px-3">
                            <i class="bi bi-box-arrow-up-right me-1"></i> মেইনটেন্যান্স পেজ দেখুন
                        </a>
                    </div>

                </div>
            </div>
        </div>

        <!-- Custom Content Settings (Accordion or Collapsible) -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="glass-panel p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-sliders text-info fs-4"></i>
                            <h5 class="fw-bold text-white mb-0">মেইনটেন্যান্স নোটিশ কাস্টমাইজেশন</h5>
                        </div>
                    </div>

                    <form action="<?= BASE_URL ?>/m.php?action=update_content" method="POST">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label text-light small fw-semibold">শিরোনাম (Headline)</label>
                            <input type="text" class="form-control" name="headline" value="<?= htmlspecialchars($config['headline']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-light small fw-semibold">বিস্তারিত বার্তা (Message for Visitors)</label>
                            <textarea class="form-control" name="message" rows="3" required><?= htmlspecialchars($config['message']) ?></textarea>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-light small fw-semibold">আনুমানিক সময় (Estimated Time)</label>
                                <input type="text" class="form-control" name="estimated_time" value="<?= htmlspecialchars($config['estimated_time']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-light small fw-semibold">জরুরি হটলাইন নম্বর</label>
                                <input type="text" class="form-control" name="contact_phone" value="<?= htmlspecialchars($config['contact_phone']) ?>">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-outline-info rounded-pill px-4">
                            <i class="bi bi-check-circle me-1"></i> তথ্য সংরক্ষণ করুন
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer class="py-3 text-center text-secondary small border-top border-secondary border-opacity-25">
        <p class="mb-0">BMMC Maintenance Controller &bull; Designed for Bangladesh Merchant Mariners Community</p>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
