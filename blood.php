<?php
/**
 * Bangladesh Merchant Mariners Community (BMMC)
 * Dedicated Blood Donation Network Portal
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = __('blood_page_title');
$ogTitle = __('blood_page_title');
$ogDescription = __('blood_hero_desc');

require_once __DIR__ . '/includes/header.php';

$pdo = DB::getConnection();

// Fetch statistics
$totalDonors = $pdo->query("SELECT COUNT(*) FROM donors")->fetchColumn() ?: 0;
$marinerDonors = $pdo->query("SELECT COUNT(*) FROM donors d JOIN users u ON d.user_id = u.id WHERE u.user_type = 'mariner'")->fetchColumn() ?: 0;
$fulfilledDonations = $pdo->query("SELECT COUNT(*) FROM donation_history")->fetchColumn() ?: 0;
$openRequestsCount = $pdo->query("SELECT COUNT(*) FROM blood_requests WHERE status IN ('open', 'matched')")->fetchColumn() ?: 0;

// Fetch latest urgent blood requests
$reqStmt = $pdo->query("
    SELECT * FROM blood_requests 
    WHERE status IN ('open', 'matched')
    ORDER BY 
        CASE urgency WHEN 'emergency' THEN 1 WHEN 'urgent' THEN 2 ELSE 3 END,
        created_at DESC
    LIMIT 6
");
$recentRequests = $reqStmt->fetchAll();
?>

<!-- Blood Portal Hero Banner -->
<section class="hero-banner text-center position-relative py-5">
    <div class="hero-glow glow-red"></div>
    <div class="hero-glow glow-blue"></div>

    <div class="container position-relative" style="z-index: 2;">
        <!-- Navigation Back to Main BMMC Hub -->
        <div class="mb-4">
            <a href="<?= BASE_URL ?>/index.php" class="btn btn-outline-info btn-sm rounded-pill px-3 py-1">
                <i class="bi bi-arrow-left me-1"></i> <?= __('blood_back_hub') ?>
            </a>
        </div>

        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill border border-danger border-opacity-30 bg-danger bg-opacity-10 text-danger">
            <i class="bi bi-droplet-fill"></i>
            <small class="fw-bold"><?= __('blood_hero_pill') ?></small>
        </div>

        <h1 class="display-4 fw-bold text-white mb-3" style="line-height: 1.25;">
            <?= __('blood_hero_title_lead') ?> <span style="background: linear-gradient(135deg, #ef233c, #f87171); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"><?= __('blood_hero_title_highlight') ?></span> <?= __('blood_hero_title_end') ?>
        </h1>
        
        <p class="lead text-secondary mx-auto mb-4" style="max-width: 840px;">
            <?= __('blood_hero_desc') ?>
        </p>

        <!-- Blood Action Triggers -->
        <div class="d-flex flex-wrap justify-content-center gap-3 mb-5">
            <a href="<?= BASE_URL ?>/blood_request.php" class="btn btn-blood btn-lg rounded-pill px-4 py-3 emergency-pulse shadow-lg">
                <i class="bi bi-droplet-fill me-2 fs-5"></i> <?= __('blood_btn_emergency_req') ?>
            </a>
            <a href="<?= BASE_URL ?>/donor_register.php" class="btn btn-ocean btn-lg rounded-pill px-4 py-3 shadow-lg">
                <i class="bi bi-heart-pulse-fill me-2 fs-5 text-warning"></i> <?= __('blood_btn_donor_reg') ?>
            </a>
            <a href="<?= BASE_URL ?>/volunteer_register.php" class="btn btn-outline-light btn-lg rounded-pill px-4 py-3">
                <i class="bi bi-people-fill me-2 fs-5 text-info"></i> <?= __('blood_btn_volunteer_join') ?>
            </a>
        </div>

        <!-- Live Statistics Counter Grid -->
        <div class="row g-4 mt-3">
            <div class="col-6 col-lg-3">
                <div class="glass-card p-4 text-center">
                    <div class="stat-counter mb-1" data-target="<?= max(1, (int)$totalDonors) ?>">
                        <?= to_bangla_number($totalDonors) ?>
                    </div>
                    <span class="text-secondary small fw-medium"><?= __('blood_stat_registered') ?></span>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="glass-card p-4 text-center border-info border-opacity-25">
                    <div class="stat-counter text-info mb-1" data-target="<?= max(1, (int)$marinerDonors) ?>">
                        <?= to_bangla_number($marinerDonors) ?>
                    </div>
                    <span class="text-info small fw-medium"><i class="bi bi-compass me-1"></i><?= __('blood_stat_mariners') ?></span>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="glass-card p-4 text-center">
                    <div class="stat-counter text-success mb-1" data-target="<?= max(1, (int)$fulfilledDonations) ?>">
                        <?= to_bangla_number($fulfilledDonations) ?>
                    </div>
                    <span class="text-secondary small fw-medium"><?= __('blood_stat_fulfilled') ?></span>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="glass-card p-4 text-center border-danger border-opacity-25">
                    <div class="stat-counter text-danger mb-1" data-target="<?= max(1, (int)$openRequestsCount) ?>">
                        <?= to_bangla_number($openRequestsCount) ?>
                    </div>
                    <span class="text-danger small fw-medium"><?= __('blood_stat_ongoing') ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Active Blood Requests Section -->
<section class="py-5">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h3 class="fw-bold text-white mb-1"><i class="bi bi-broadcast text-danger me-2"></i><?= __('blood_active_requests_title') ?></h3>
                <p class="text-secondary small mb-0"><?= __('blood_active_requests_sub') ?></p>
            </div>
            <a href="<?= BASE_URL ?>/blood_request.php" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-2">
                <i class="bi bi-plus-circle me-1"></i> <?= __('blood_btn_new_request') ?>
            </a>
        </div>

        <?php if (empty($recentRequests)): ?>
            <div class="glass-card p-5 text-center">
                <i class="bi bi-check-circle-fill text-success fs-1 mb-3 d-block"></i>
                <h5 class="text-white"><?= __('blood_no_pending') ?></h5>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($recentRequests as $req): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="glass-blood-card p-4 h-100 position-relative d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="blood-pill"><?= htmlspecialchars($req['blood_group']) ?></div>
                                        <div>
                                            <h5 class="text-white fw-bold mb-0" style="font-size: 1.05rem;"><?= htmlspecialchars($req['patient_name']) ?></h5>
                                            <small class="text-secondary"><i class="bi bi-hospital me-1"></i><?= htmlspecialchars($req['hospital']) ?></small>
                                        </div>
                                    </div>
                                    <?= get_urgency_badge($req['urgency']) ?>
                                </div>

                                <div class="row g-2 small text-light mb-3">
                                    <div class="col-6">
                                        <span class="text-secondary"><?= __('blood_location_label') ?></span> <?= htmlspecialchars($req['area']) ?>, <?= htmlspecialchars($req['district']) ?>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-secondary"><?= __('blood_bags_label') ?></span> <?= to_bangla_number($req['bags_needed']) ?> <?= __('blood_bags_suffix') ?>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-secondary"><?= __('blood_date_label') ?></span> <?= format_date_bn($req['needed_by'] ?: $req['created_at']) ?>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-secondary"><?= __('blood_status_label') ?></span> <?= get_status_badge($req['status']) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top border-secondary border-opacity-25 mt-2">
                                <span class="small text-info fw-semibold">
                                    <i class="bi bi-telephone-fill me-1"></i><?= htmlspecialchars($req['contact_phone']) ?>
                                </span>
                                <a href="<?= BASE_URL ?>/blood_request_detail.php?id=<?= $req['id'] ?>" class="btn btn-outline-light btn-sm rounded-pill px-3">
                                    <?= __('blood_btn_details') ?> <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- How the Smart Blood Automation Works -->
<section class="py-5" style="background: rgba(0, 0, 0, 0.2);">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-feature-badge">
                <i class="bi bi-gear-wide-connected"></i>
                <span><?= __('blood_how_it_works_badge') ?></span>
            </div>
            <h3 class="fw-bold text-white mb-2"><?= __('blood_how_it_works_title') ?></h3>
            <p class="text-secondary"><?= __('blood_how_it_works_sub') ?></p>
        </div>

        <div class="row g-4">
            <!-- Step 1 -->
            <div class="col-md-6 col-lg-3">
                <div class="glass-card p-4 h-100 text-center">
                    <div style="width: 56px; height: 56px; margin: 0 auto 16px; background: rgba(0, 210, 255, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid var(--ocean-cyan);">
                        <span class="fw-bold fs-4 text-info"><?= is_english() ? '1' : '১' ?></span>
                    </div>
                    <h5 class="text-white fw-bold mb-2"><?= __('blood_step1_title') ?></h5>
                    <p class="text-secondary small mb-0">
                        <?= __('blood_step1_desc') ?>
                    </p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="col-md-6 col-lg-3">
                <div class="glass-card p-4 h-100 text-center">
                    <div style="width: 56px; height: 56px; margin: 0 auto 16px; background: rgba(239, 35, 60, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid var(--blood-crimson);">
                        <span class="fw-bold fs-4 text-danger"><?= is_english() ? '2' : '২' ?></span>
                    </div>
                    <h5 class="text-white fw-bold mb-2"><?= __('blood_step2_title') ?></h5>
                    <p class="text-secondary small mb-0">
                        <?= __('blood_step2_desc') ?>
                    </p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="col-md-6 col-lg-3">
                <div class="glass-card p-4 h-100 text-center">
                    <div style="width: 56px; height: 56px; margin: 0 auto 16px; background: rgba(245, 158, 11, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid #f59e0b;">
                        <span class="fw-bold fs-4 text-warning"><?= is_english() ? '3' : '৩' ?></span>
                    </div>
                    <h5 class="text-white fw-bold mb-2"><?= __('blood_step3_title') ?></h5>
                    <p class="text-secondary small mb-0">
                        <?= __('blood_step3_desc') ?>
                    </p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="col-md-6 col-lg-3">
                <div class="glass-card p-4 h-100 text-center">
                    <div style="width: 56px; height: 56px; margin: 0 auto 16px; background: rgba(16, 185, 129, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid #10b981;">
                        <span class="fw-bold fs-4 text-success"><?= is_english() ? '4' : '৪' ?></span>
                    </div>
                    <h5 class="text-white fw-bold mb-2"><?= __('blood_step4_title') ?></h5>
                    <p class="text-secondary small mb-0">
                        <?= __('blood_step4_desc') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Volunteer & Community Callout -->
<section class="py-5">
    <div class="container">
        <div class="glass-card p-4 p-md-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill bg-primary bg-opacity-20 text-info border border-info border-opacity-25">
                        <i class="bi bi-compass"></i>
                        <small class="fw-bold"><?= __('blood_callout_badge') ?></small>
                    </div>
                    <h2 class="fw-bold text-white mb-3"><?= __('blood_callout_title') ?></h2>
                    <p class="text-light" style="line-height: 1.8;">
                        <?= __('blood_callout_desc') ?>
                    </p>
                    <div class="alert alert-dark bg-opacity-50 border-secondary mb-0 py-2">
                        <i class="bi bi-quote fs-4 text-info me-2"></i>
                        <span class="text-white-50 fst-italic"><?= __('blood_callout_quote') ?></span>
                    </div>
                </div>
                <div class="col-lg-4 text-center">
                    <div class="p-4 rounded-4 border border-secondary border-opacity-25" style="background: rgba(0,0,0,0.25);">
                        <i class="bi bi-person-heart text-info fs-1 mb-3 d-block"></i>
                        <h5 class="text-white fw-bold mb-2"><?= __('blood_callout_box_title') ?></h5>
                        <p class="text-secondary small mb-4"><?= __('blood_callout_box_sub') ?></p>
                        <a href="<?= BASE_URL ?>/volunteer_register.php" class="btn btn-ocean w-100 py-2 rounded-pill fw-semibold">
                            <i class="bi bi-pencil-square me-2"></i> <?= __('blood_btn_fill_volunteer') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
