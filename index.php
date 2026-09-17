<?php
/**
 * Bangladesh Merchant Mariners Community (BMMC)
 * Universal Organization Homepage — Multi-Service Maritime Portal
 */
require_once __DIR__ . '/config/config.php';

$pageTitle = is_english() 
    ? 'Bangladesh Merchant Mariners Community (BMMC) — Universal Maritime Platform' 
    : 'বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি (BMMC) — সার্বজনীন মেরিটাইম সেবা প্ল্যাটফর্ম';
$ogTitle = is_english() 
    ? 'BMMC — Bangladesh Merchant Mariners Community (21 Specialized Services)' 
    : 'BMMC — বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি (২১টি সার্বজনীন সেবা)';
$ogDescription = is_english() 
    ? 'Safeguarding seafarers\' rights, professional dignity, emergency rescue, cadet mentorship, and life-saving blood donation across 21 specialized BMMC wings.' 
    : 'সমুদ্রে নাবিকদের অধিকার রক্ষা, পেশাগত মর্যাদা, জরুরি উদ্ধার, ক্যাডেট মেন্টরশিপ ও রক্তদান নেটওয়ার্ক সহ BMMC-এর ২১টি বিশেষায়িত সেবামূলক উদ্যোগ।';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/services_data.php';

$pdo = DB::getConnection();

// Fetch live statistics
$totalDonors = 0;
$marinerDonors = 0;
$fulfilledDonations = 0;
$openRequestsCount = 0;

try {
    $totalDonors = $pdo->query("SELECT COUNT(*) FROM donors")->fetchColumn() ?: 0;
    $marinerDonors = $pdo->query("SELECT COUNT(*) FROM donors d JOIN users u ON d.user_id = u.id WHERE u.user_type = 'mariner'")->fetchColumn() ?: 0;
    $fulfilledDonations = $pdo->query("SELECT COUNT(*) FROM donation_history")->fetchColumn() ?: 0;
    $openRequestsCount = $pdo->query("SELECT COUNT(*) FROM blood_requests WHERE status IN ('open', 'matched')")->fetchColumn() ?: 0;
} catch (Exception $e) {}

// Fetch 3 active blood requests for the flagship spotlight
$recentRequests = [];
try {
    $reqStmt = $pdo->query("
        SELECT * FROM blood_requests 
        WHERE status IN ('open', 'matched')
        ORDER BY 
            CASE urgency WHEN 'emergency' THEN 1 WHEN 'urgent' THEN 2 ELSE 3 END,
            created_at DESC
        LIMIT 3
    ");
    $recentRequests = $reqStmt->fetchAll();
} catch (Exception $e) {}

$pillars = get_bmmc_pillars();
$services = get_bmmc_services();
?>

<!-- =============================================================
     HERO BANNER: UNIVERSAL MARITIME ORGANIZATION
============================================================= -->
<section class="hero-banner text-center position-relative py-5">
    <div class="hero-glow glow-blue"></div>
    <div class="hero-glow glow-red"></div>

    <div class="container position-relative" style="z-index: 2;">
        
        <!-- Organization Pill Badge -->
        <div class="section-feature-badge">
            <i class="bi bi-compass-fill"></i>
            <span><?= __('hero_pill_badge') ?></span>
        </div>

        <!-- Main Headline -->
        <h1 class="display-4 fw-bold text-white mb-3" style="line-height: 1.25;">
            <?= __('hero_title_lead') ?> <span style="background: linear-gradient(135deg, #00d2ff, #38bdf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"><?= __('hero_title_highlight') ?></span><?= is_english() ? '' : ' জাতীয় প্ল্যাটফর্ম' ?>
        </h1>
        
        <!-- Subtitle -->
        <p class="lead text-light opacity-90 mx-auto mb-4" style="max-width: 860px; line-height: 1.7; font-size: 1.15rem;">
            <?= __('hero_subtitle') ?>
        </p>

        <!-- Primary Call to Action Buttons -->
        <div class="d-flex flex-wrap justify-content-center gap-3 mb-5">
            <a href="#services-section" class="btn btn-ocean btn-lg rounded-pill px-4 py-3 shadow-lg fw-bold">
                <i class="bi bi-grid-3x3-gap-fill me-2"></i> <?= __('hero_btn_services') ?>
            </a>
            <a href="<?= BASE_URL ?>/blood.php" class="btn btn-blood btn-lg rounded-pill px-4 py-3 emergency-pulse shadow-lg fw-bold">
                <i class="bi bi-droplet-fill me-2 fs-5"></i> <?= __('hero_btn_blood') ?>
            </a>
            <a href="<?= BASE_URL ?>/volunteer_register.php" class="btn btn-volunteer-highlight btn-lg rounded-pill px-4 py-3">
                <i class="bi bi-person-heart fs-5"></i> <?= __('hero_btn_volunteer') ?>
            </a>
        </div>

        <!-- Key Metrics Bar -->
        <div class="row g-3 justify-content-center">
            <div class="col-6 col-md-3">
                <div class="glass-card p-3 p-md-4 text-center h-100 border-info border-opacity-25">
                    <div class="display-6 fw-bold text-info mb-1"><?= __('count_services') ?></div>
                    <span class="text-secondary small fw-medium"><?= __('hero_stat_services') ?></span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="glass-card p-3 p-md-4 text-center h-100 border-danger border-opacity-25">
                    <div class="display-6 fw-bold text-danger mb-1"><?= to_bangla_number($totalDonors ?: 100) ?>+</div>
                    <span class="text-secondary small fw-medium"><?= __('hero_stat_donors') ?></span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="glass-card p-3 p-md-4 text-center h-100 border-warning border-opacity-25">
                    <div class="display-6 fw-bold text-warning mb-1"><?= __('count_support') ?></div>
                    <span class="text-secondary small fw-medium"><?= __('hero_stat_support') ?></span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="glass-card p-3 p-md-4 text-center h-100 border-success border-opacity-25">
                    <div class="display-6 fw-bold text-success mb-1"><?= __('count_nonprofit') ?></div>
                    <span class="text-secondary small fw-medium"><?= __('hero_stat_nonprofit') ?></span>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- =============================================================
     FLAGSHIP SPOTLIGHT: ACTIVE BLOOD DONATION NETWORK
============================================================= -->
<section class="py-5" style="background: linear-gradient(180deg, rgba(6, 20, 38, 0.5) 0%, rgba(128, 15, 47, 0.15) 50%, rgba(6, 20, 38, 0.8) 100%);">
    <div class="container">
        <div class="glass-blood-card p-4 p-md-5 rounded-4 position-relative overflow-hidden">
            <!-- Ambient Glow -->
            <div class="position-absolute" style="top: -60px; right: -60px; width: 220px; height: 220px; background: rgba(239, 35, 60, 0.25); filter: blur(50px); border-radius: 50%;"></div>

            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill bg-danger bg-opacity-20 border border-danger border-opacity-40 text-white small fw-bold">
                        <span class="pulse-dot" style="background: #ef233c;"></span>
                        <span><?= __('blood_spotlight_pill') ?></span>
                    </div>

                    <h2 class="display-6 fw-bold text-white mb-3">
                        <i class="bi bi-droplet-fill text-danger me-2"></i> <?= __('blood_spotlight_title') ?>
                    </h2>
                    <p class="text-light opacity-90 mb-4" style="line-height: 1.7; font-size: 1.05rem;">
                        <?= __('blood_spotlight_desc') ?>
                    </p>

                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="badge bg-dark bg-opacity-60 border border-secondary text-light px-3 py-2 rounded-pill small">
                            <i class="bi bi-shield-check text-success me-1"></i> <?= __('blood_badge_resting') ?>
                        </span>
                        <span class="badge bg-dark bg-opacity-60 border border-secondary text-light px-3 py-2 rounded-pill small">
                            <i class="bi bi-lightning-charge-fill text-warning me-1"></i> <?= __('blood_badge_instant') ?>
                        </span>
                        <span class="badge bg-dark bg-opacity-60 border border-secondary text-light px-3 py-2 rounded-pill small">
                            <i class="bi bi-telephone-fill text-info me-1"></i> <?= __('blood_badge_hotline') ?>
                        </span>
                    </div>

                    <!-- Direct Action Buttons -->
                    <div class="d-flex flex-wrap gap-3">
                        <a href="<?= BASE_URL ?>/blood.php" class="btn btn-blood btn-lg rounded-pill px-4 py-2 fw-bold shadow-lg">
                            <i class="bi bi-arrow-right-circle-fill me-2"></i> <?= __('blood_btn_portal') ?>
                        </a>
                        <a href="<?= BASE_URL ?>/blood_request.php" class="btn btn-outline-light btn-lg rounded-pill px-4 py-2 fw-semibold">
                            <i class="bi bi-plus-circle me-1"></i> <?= __('blood_btn_request') ?>
                        </a>
                        <a href="<?= BASE_URL ?>/donor_register.php" class="btn btn-outline-warning btn-lg rounded-pill px-4 py-2 fw-semibold">
                            <i class="bi bi-heart-fill me-1"></i> <?= __('blood_btn_register') ?>
                        </a>
                    </div>
                </div>

                <!-- Recent Blood Appeals Feed Preview -->
                <div class="col-lg-5">
                    <div class="p-4 rounded-3 border border-secondary border-opacity-30" style="background: rgba(6, 18, 36, 0.75);">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-white fw-bold mb-0"><i class="bi bi-broadcast text-danger me-2"></i><?= __('blood_recent_appeals_title') ?></h6>
                            <a href="<?= BASE_URL ?>/blood.php" class="small text-info text-decoration-none"><?= __('blood_view_all') ?> <i class="bi bi-arrow-right"></i></a>
                        </div>

                        <?php if (empty($recentRequests)): ?>
                            <div class="text-center py-4">
                                <i class="bi bi-check-circle-fill text-success fs-2 mb-2 d-block"></i>
                                <span class="text-light small"><?= __('blood_no_pending') ?></span>
                            </div>
                        <?php else: ?>
                            <div class="d-flex flex-column gap-2">
                                <?php foreach ($recentRequests as $req): ?>
                                <div class="p-2 rounded-3 border border-secondary border-opacity-25 d-flex align-items-center justify-content-between" style="background: rgba(255, 255, 255, 0.02);">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="blood-pill py-1 px-2" style="font-size: 0.85rem; min-width: 42px;"><?= htmlspecialchars($req['blood_group']) ?></span>
                                        <div>
                                            <div class="text-white fw-semibold small text-truncate" style="max-width: 150px;"><?= htmlspecialchars($req['patient_name']) ?></div>
                                            <small class="text-secondary" style="font-size: 0.72rem;"><?= htmlspecialchars($req['area']) ?>, <?= htmlspecialchars($req['district']) ?></small>
                                        </div>
                                    </div>
                                    <a href="<?= BASE_URL ?>/blood_request_detail.php?id=<?= $req['id'] ?>" class="btn btn-outline-danger btn-sm rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                        <?= __('blood_help_btn') ?> <i class="bi bi-chevron-right"></i>
                                    </a>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =============================================================
     INTERACTIVE SERVICES SHOWCASE: ALL 21 SERVICES
============================================================= -->
<section id="services-section" class="py-5">
    <div class="container">
        
        <!-- Section Header -->
        <div class="text-center mb-4">
            <div class="section-feature-badge">
                <i class="bi bi-grid-fill"></i>
                <span><?= __('services_hub_badge') ?></span>
            </div>
            <h2 class="display-6 fw-bold text-white mb-2"><?= __('services_section_title') ?></h2>
            <p class="text-secondary mx-auto" style="max-width: 760px;">
                <?= __('services_section_subtitle') ?>
            </p>
        </div>

        <!-- Filter Tabs -->
        <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
            <button class="service-filter-btn active" onclick="filterServices('all', this)">
                <i class="bi bi-collection-fill"></i> <?= __('filter_all') ?>
            </button>
            <button class="service-filter-btn" onclick="filterServices('emergency', this)">
                <i class="bi bi-shield-exclamation text-danger"></i> <?= __('filter_emergency') ?>
            </button>
            <button class="service-filter-btn" onclick="filterServices('career', this)">
                <i class="bi bi-mortarboard-fill text-info"></i> <?= __('filter_career') ?>
            </button>
            <button class="service-filter-btn" onclick="filterServices('tools', this)">
                <i class="bi bi-cpu-fill text-warning"></i> <?= __('filter_tools') ?>
            </button>
            <button class="service-filter-btn" onclick="filterServices('health', this)">
                <i class="bi bi-hospital-fill text-success"></i> <?= __('filter_health') ?>
            </button>
            <button class="service-filter-btn" onclick="filterServices('community', this)">
                <i class="bi bi-globe2 text-primary"></i> <?= __('filter_community') ?>
            </button>
        </div>

        <!-- Mobile Horizontal Scroll Hint (Visible only on mobile) -->
        <div class="d-flex d-md-none justify-content-between align-items-center mb-3 px-1 text-secondary small">
            <span class="d-inline-flex align-items-center gap-1 text-info fw-semibold">
                <i class="bi bi-arrow-left-right"></i> <?= __('services_swipe_hint') ?>
            </span>
            <span class="badge rounded-pill service-counter-badge" id="serviceFilterCountBadge">
                <?= count($services) ?> <?= __('services_counter_suffix') ?>
            </span>
        </div>

        <!-- Services 21 Cards Grid (Horizontal scroll on mobile, responsive grid on desktop) -->
        <div class="row g-4" id="servicesGrid">
            <?php foreach ($services as $s): 
                $isLive = ($s['status'] === 'live');
            ?>
            <div class="col-md-6 col-lg-4 service-item" data-pillar="<?= htmlspecialchars($s['pillar']) ?>">
                <div class="service-showcase-card <?= $isLive ? 'card-live' : '' ?>">
                    <div>
                        <!-- Header Icon & Status Badge -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="service-card-icon" style="background: rgba(<?= $isLive ? '239, 35, 60, 0.15' : '0, 210, 255, 0.12' ?>); border: 1px solid <?= htmlspecialchars($s['color']) ?>; color: <?= htmlspecialchars($s['color']) ?>;">
                                <i class="bi <?= htmlspecialchars($s['icon']) ?>"></i>
                            </div>
                            <?php if ($isLive): ?>
                                <span class="badge-live-pulse"><?= __('service_status_live') ?></span>
                            <?php else: ?>
                                <span class="badge-soon"><?= __('service_status_soon') ?></span>
                            <?php endif; ?>
                        </div>

                        <!-- Pillar Tag -->
                        <small class="text-info text-uppercase fw-bold d-block mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                            <?= htmlspecialchars($pillars[$s['pillar']]['title'] ?? '') ?>
                        </small>

                        <!-- Title -->
                        <h5 class="text-white fw-bold mb-1" style="line-height: 1.3;">
                            <?= htmlspecialchars($s['title']) ?>
                        </h5>
                        <?php if (!is_english() && !empty($s['title_en'])): ?>
                        <small class="text-secondary d-block mb-3" style="font-size: 0.78rem;">
                            <?= htmlspecialchars($s['title_en']) ?>
                        </small>
                        <?php else: ?>
                        <div class="mb-3"></div>
                        <?php endif; ?>

                        <!-- Summary -->
                        <p class="text-light opacity-80 small mb-4" style="line-height: 1.6;">
                            <?= htmlspecialchars($s['summary']) ?>
                        </p>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-3 border-top border-secondary border-opacity-25">
                        <a href="<?= BASE_URL ?>/<?= htmlspecialchars($s['link']) ?>" class="btn <?= $isLive ? 'btn-blood' : 'btn-outline-info' ?> btn-sm rounded-pill w-100 py-2 fw-semibold">
                            <?= htmlspecialchars($s['button_text']) ?> <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- =============================================================
     BECOME A BMMC VOLUNTEER CTA SECTION
============================================================= -->
<section class="py-5" style="background: linear-gradient(135deg, rgba(8, 20, 38, 0.8) 0%, rgba(2, 132, 199, 0.15) 100%);">
    <div class="container">
        <div class="glass-card p-4 p-md-5 rounded-4 border-info border-opacity-30 position-relative">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="section-feature-badge">
                        <i class="bi bi-person-heart"></i>
                        <span><?= __('volunteer_cta_badge') ?></span>
                    </div>

                    <h2 class="display-6 fw-bold text-white mb-3">
                        <?= __('volunteer_cta_title') ?>
                    </h2>
                    <p class="text-light opacity-90 mb-4" style="line-height: 1.8;">
                        <?= __('volunteer_cta_desc') ?>
                    </p>

                    <div class="alert alert-dark bg-opacity-50 border-secondary mb-0 py-2 small text-warning">
                        <i class="bi bi-info-circle-fill me-1"></i> 
                        <?= __('volunteer_cta_alert') ?>
                    </div>
                </div>

                <div class="col-lg-4 text-center">
                    <div class="p-4 rounded-4 border border-secondary border-opacity-25" style="background: rgba(6, 18, 36, 0.85);">
                        <i class="bi bi-people-fill text-info fs-1 mb-3 d-block"></i>
                        <h5 class="text-white fw-bold mb-2"><?= __('volunteer_cta_box_title') ?></h5>
                        <p class="text-secondary small mb-4"><?= __('volunteer_cta_box_sub') ?></p>
                        <a href="<?= BASE_URL ?>/volunteer_register.php" class="btn btn-volunteer-highlight w-100 py-3 rounded-pill fs-6">
                            <i class="bi bi-pencil-square me-2"></i> <?= __('volunteer_cta_btn') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =============================================================
     TRANSPARENCY & CORE COMMUNITY COMMITMENT
============================================================= -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="glass-card p-4 h-100 text-center">
                    <div style="width: 54px; height: 54px; margin: 0 auto 16px; background: rgba(16, 185, 129, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid #10b981;">
                        <i class="bi bi-shield-check text-success fs-3"></i>
                    </div>
                    <h5 class="text-white fw-bold mb-2"><?= __('transparency_title_1') ?></h5>
                    <p class="text-secondary small mb-0">
                        <?= __('transparency_desc_1') ?>
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-4 h-100 text-center">
                    <div style="width: 54px; height: 54px; margin: 0 auto 16px; background: rgba(0, 210, 255, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid #00d2ff;">
                        <i class="bi bi-patch-check-fill text-info fs-3"></i>
                    </div>
                    <h5 class="text-white fw-bold mb-2"><?= __('transparency_title_2') ?></h5>
                    <p class="text-secondary small mb-0">
                        <?= __('transparency_desc_2') ?>
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-4 h-100 text-center">
                    <div style="width: 54px; height: 54px; margin: 0 auto 16px; background: rgba(245, 158, 11, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid #f59e0b;">
                        <i class="bi bi-heart-fill text-warning fs-3"></i>
                    </div>
                    <h5 class="text-white fw-bold mb-2"><?= __('transparency_title_3') ?></h5>
                    <p class="text-secondary small mb-0">
                        <?= __('transparency_desc_3') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filter Script for 21 Services -->
<script>
function filterServices(pillarId, btn) {
    // Update active button state
    document.querySelectorAll('.service-filter-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');

    // Filter cards
    let visibleCount = 0;
    const items = document.querySelectorAll('.service-item');
    items.forEach(item => {
        const itemPillar = item.getAttribute('data-pillar');
        if (pillarId === 'all' || itemPillar === pillarId) {
            item.style.display = '';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    // Update count badge if present
    const countBadge = document.getElementById('serviceFilterCountBadge');
    if (countBadge) {
        const suffix = <?= json_encode(__('services_counter_suffix')) ?>;
        countBadge.textContent = visibleCount + ' ' + suffix;
    }

    // Smoothly rewind horizontal container to the beginning
    const grid = document.getElementById('servicesGrid');
    if (grid) {
        grid.scrollTo({ left: 0, behavior: 'smooth' });
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
