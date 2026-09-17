<?php
/**
 * Bangladesh Merchant Mariners Community (BMMC)
 * Dynamic Service Showcase & Under-Development Engine
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/services_data.php';

$slug = trim($_GET['slug'] ?? '');

// If blood network is accessed, redirect cleanly to blood portal
if ($slug === 'blood-network') {
    header('Location: ' . BASE_URL . '/blood.php');
    exit;
}

$service = get_service_by_slug($slug);
$pillars = get_bmmc_pillars();

if (!$service) {
    $pageTitle = __('service_not_found_title') . ' — ' . __('site_short_name');
    require_once __DIR__ . '/includes/header.php';
    ?>
    <div class="container py-5 text-center">
        <div class="glass-card p-5 my-5 mx-auto" style="max-width: 600px;">
            <i class="bi bi-exclamation-octagon text-warning fs-1 mb-3 d-block"></i>
            <h3 class="text-white fw-bold mb-2"><?= __('service_not_found_title') ?></h3>
            <p class="text-secondary mb-4"><?= __('service_not_found_desc') ?></p>
            <a href="<?= BASE_URL ?>/index.php#services-section" class="btn btn-ocean rounded-pill px-4 py-2">
                <i class="bi bi-grid-fill me-2"></i> <?= __('service_btn_back_list') ?>
            </a>
        </div>
    </div>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$currentPillar = $pillars[$service['pillar']] ?? null;
$pageTitle = $service['title'] . ' — ' . __('nav_our_services') . ' | ' . __('site_short_name');
$ogTitle = $service['title'] . ' (' . $service['title_en'] . ') — ' . __('site_short_name');
$ogDescription = $service['summary'];

require_once __DIR__ . '/includes/header.php';

// Fetch related services under the same pillar (excluding current)
$relatedServices = array_filter(get_services_by_pillar($service['pillar']), function($s) use ($slug) {
    return $s['slug'] !== $slug;
});
?>

<!-- Service Detail Hero Section -->
<section class="py-5 position-relative">
    <div class="hero-glow glow-blue"></div>
    <div class="container position-relative" style="z-index: 2;">
        
        <!-- Breadcrumbs & Back Navigation -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/index.php" class="text-info text-decoration-none"><i class="bi bi-house me-1"></i><?= __('breadcrumb_home') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/index.php#services-section" class="text-secondary text-decoration-none"><?= __('breadcrumb_services') ?></a></li>
                    <?php if ($currentPillar): ?>
                    <li class="breadcrumb-item text-secondary"><?= htmlspecialchars($currentPillar['title']) ?></li>
                    <?php endif; ?>
                    <li class="breadcrumb-item active text-white" aria-current="page"><?= htmlspecialchars($service['title']) ?></li>
                </ol>
            </nav>
            <a href="<?= BASE_URL ?>/index.php#services-section" class="btn btn-outline-light btn-sm rounded-pill px-3 py-1">
                <i class="bi bi-arrow-left me-1"></i> <?= __('service_btn_back_list') ?>
            </a>
        </div>

        <!-- Service Main Header Card -->
        <div class="glass-card p-4 p-md-5 mb-4 position-relative overflow-hidden">
            <!-- Background Decorative Watermark Icon -->
            <i class="bi <?= htmlspecialchars($service['icon']) ?> position-absolute text-info opacity-10" style="font-size: 16rem; right: -30px; bottom: -50px; pointer-events: none;"></i>

            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <!-- Pillar Badge -->
                    <div class="section-feature-badge">
                        <i class="bi <?= htmlspecialchars($currentPillar['icon'] ?? 'bi-compass') ?>"></i>
                        <span><?= htmlspecialchars($currentPillar['title'] ?? 'BMMC Service') ?></span>
                    </div>

                    <h1 class="display-5 fw-bold text-white mb-2">
                        <?= htmlspecialchars($service['title']) ?>
                    </h1>
                    <?php if (!is_english() && !empty($service['title_en'])): ?>
                    <h5 class="text-info opacity-75 fw-normal mb-3" style="letter-spacing: 0.5px;">
                        <?= htmlspecialchars($service['title_en']) ?>
                    </h5>
                    <?php endif; ?>
                    <p class="lead text-light mb-4" style="line-height: 1.7; font-size: 1.15rem;">
                        <?= htmlspecialchars($service['summary']) ?>
                    </p>

                    <!-- Status Indicator Pill -->
                    <div class="d-inline-flex align-items-center gap-3 px-3 py-2 rounded-3 border border-warning border-opacity-30 bg-warning bg-opacity-10 text-warning">
                        <div class="spinner-grow spinner-grow-sm text-warning" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="small fw-semibold">
                            <?= __('blood_status_label') ?> <span class="text-white"><?= htmlspecialchars($service['badge']) ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 text-center">
                    <!-- Interactive Visual Badge -->
                    <div class="p-4 rounded-4 border border-info border-opacity-25" style="background: rgba(6, 18, 36, 0.7); backdrop-filter: blur(12px);">
                        <div style="width: 80px; height: 80px; margin: 0 auto 16px; background: rgba(0, 210, 255, 0.15); border-radius: 20px; display: flex; align-items: center; justify-content: center; border: 2px solid #00d2ff; box-shadow: 0 0 25px rgba(0, 210, 255, 0.3);">
                            <i class="bi <?= htmlspecialchars($service['icon']) ?> fs-1 text-info"></i>
                        </div>
                        <h6 class="text-white fw-bold mb-1"><?= __('service_wing_title') ?></h6>
                        <small class="text-secondary d-block mb-3"><?= __('service_wing_sub') ?></small>
                        
                        <a href="<?= BASE_URL ?>/volunteer_register.php?wing=<?= urlencode($service['title']) ?>" class="btn btn-ocean w-100 py-2 rounded-pill fw-semibold shadow-sm">
                            <i class="bi bi-people-fill me-2"></i> <?= __('service_btn_volunteer_wing') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Official "Under Development / Coming Soon" Notice Box -->
        <div class="glass-card p-4 p-md-5 mb-5 border-warning border-opacity-30" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.08) 0%, rgba(6, 18, 36, 0.85) 100%);">
            <div class="row align-items-center g-4">
                <div class="col-md-2 text-center">
                    <div style="width: 70px; height: 70px; margin: 0 auto; background: rgba(245, 158, 11, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #f59e0b; box-shadow: 0 0 20px rgba(245, 158, 11, 0.35);">
                        <i class="bi bi-cone-striped fs-1 text-warning"></i>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="d-inline-block px-3 py-1 mb-2 rounded-pill bg-warning bg-opacity-20 text-warning border border-warning border-opacity-40 small fw-bold">
                        <i class="bi bi-tools me-1"></i> <?= __('service_under_dev_pill') ?>
                    </div>
                    <h4 class="text-white fw-bold mb-2"><?= __('service_under_dev_title') ?></h4>
                    <p class="text-light opacity-75 mb-0" style="line-height: 1.6;">
                        <?= __('service_under_dev_desc') ?>
                    </p>
                </div>
                <div class="col-md-3 text-center text-md-end">
                    <button type="button" class="btn btn-outline-warning btn-lg rounded-pill px-4 py-2 w-100" onclick="alert(<?= json_encode(__('service_notified_alert')) ?>)">
                        <i class="bi bi-bell-fill me-2"></i> <?= __('service_btn_get_notified') ?>
                    </button>
                </div>
            </div>
        </div>

        <!-- Deep Dive: Problem vs Solution Grid -->
        <div class="row g-4 mb-5">
            <!-- Problem Context -->
            <div class="col-lg-6">
                <div class="glass-card p-4 p-md-5 h-100 border-danger border-opacity-25" style="background: rgba(239, 35, 60, 0.04);">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="width: 44px; height: 44px; background: rgba(239, 35, 60, 0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(239, 35, 60, 0.4);">
                            <i class="bi bi-exclamation-circle-fill text-danger fs-4"></i>
                        </div>
                        <h4 class="text-white fw-bold mb-0"><?= __('service_problem_heading') ?></h4>
                    </div>
                    <p class="text-light" style="line-height: 1.8;">
                        <?= htmlspecialchars($service['problem'] ?? '') ?>
                    </p>
                </div>
            </div>

            <!-- BMMC Solution -->
            <div class="col-lg-6">
                <div class="glass-card p-4 p-md-5 h-100 border-success border-opacity-25" style="background: rgba(16, 185, 129, 0.04);">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="width: 44px; height: 44px; background: rgba(16, 185, 129, 0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(16, 185, 129, 0.4);">
                            <i class="bi bi-shield-fill-check text-success fs-4"></i>
                        </div>
                        <h4 class="text-white fw-bold mb-0"><?= __('service_solution_heading') ?></h4>
                    </div>
                    <p class="text-light" style="line-height: 1.8;">
                        <?= htmlspecialchars($service['solution'] ?? $service['description']) ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Planned Core Features / Roadmap -->
        <?php if (!empty($service['features'])): ?>
        <div class="glass-card p-4 p-md-5 mb-5">
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-2 rounded-pill bg-primary bg-opacity-20 text-info border border-info border-opacity-30 small fw-bold">
                    <i class="bi bi-stars"></i>
                    <span><?= __('service_roadmap_pill') ?></span>
                </div>
                <h3 class="text-white fw-bold"><?= __('service_features_heading') ?></h3>
                <p class="text-secondary small"><?= __('service_features_sub') ?></p>
            </div>

            <div class="row g-3">
                <?php foreach ($service['features'] as $index => $feature): ?>
                <div class="col-md-6">
                    <div class="p-3 rounded-3 border border-secondary border-opacity-25 h-100 d-flex align-items-start gap-3" style="background: rgba(255, 255, 255, 0.02);">
                        <div style="width: 32px; height: 32px; flex-shrink: 0; background: rgba(0, 210, 255, 0.15); border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 1px solid #00d2ff;">
                            <i class="bi bi-check2 text-info fw-bold"></i>
                        </div>
                        <div>
                            <span class="text-light fw-medium" style="line-height: 1.5;"><?= htmlspecialchars($feature) ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Related Pillar Services -->
        <?php if (!empty($relatedServices)): ?>
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-white fw-bold mb-0">
                    <i class="bi <?= htmlspecialchars($currentPillar['icon'] ?? 'bi-grid') ?> text-info me-2"></i>
                    <?= __('service_related_heading') ?> <?= htmlspecialchars($currentPillar['title'] ?? '') ?>
                </h4>
            </div>

            <div class="row g-3">
                <?php foreach (array_slice($relatedServices, 0, 3) as $rel): ?>
                <div class="col-md-4">
                    <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between border-secondary border-opacity-25">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="bi <?= htmlspecialchars($rel['icon']) ?> fs-4 text-info"></i>
                                <h6 class="text-white fw-bold mb-0"><?= htmlspecialchars($rel['title']) ?></h6>
                            </div>
                            <p class="text-secondary small mb-3" style="line-height: 1.5;">
                                <?= htmlspecialchars($rel['summary']) ?>
                            </p>
                        </div>
                        <div>
                            <a href="<?= BASE_URL ?>/service.php?slug=<?= $rel['slug'] ?>" class="btn btn-outline-info btn-sm rounded-pill w-100">
                                <?= __('service_btn_view_detail') ?> <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Global Blood Portal Referral Banner -->
        <div class="glass-blood-card p-4 rounded-4 text-center">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                <div class="text-md-start">
                    <h5 class="text-white fw-bold mb-1"><i class="bi bi-droplet-fill text-danger me-2"></i><?= __('service_urgent_blood_heading') ?></h5>
                    <p class="text-secondary small mb-0"><?= __('service_urgent_blood_sub') ?></p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= BASE_URL ?>/blood_request.php" class="btn btn-blood btn-sm rounded-pill px-4 py-2 emergency-pulse">
                        <i class="bi bi-plus-circle me-1"></i> <?= __('blood_btn_request') ?>
                    </a>
                    <a href="<?= BASE_URL ?>/blood.php" class="btn btn-outline-light btn-sm rounded-pill px-3 py-2">
                        <?= __('nav_blood_portal') ?> <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
