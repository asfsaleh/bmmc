<?php
/**
 * Bangladesh Merchant Mariners Community (BMMC)
 * Universal Organization Homepage — Multi-Service Maritime Portal
 */
$pageTitle = 'বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি (BMMC) — সার্বজনীন মেরিটাইম সেবা প্ল্যাটফর্ম';
$ogTitle = 'BMMC — বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি (২১টি সার্বজনীন সেবা)';
$ogDescription = 'সমুদ্রে নাবিকদের অধিকার রক্ষা, পেশাগত মর্যাদা, জরুরি উদ্ধার, ক্যাডেট মেন্টরশিপ ও রক্তদান নেটওয়ার্ক সহ BMMC-এর ২১টি বিশেষায়িত সেবামূলক উদ্যোগ।';

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
        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill border border-info border-opacity-30 bg-info bg-opacity-10 text-info">
            <i class="bi bi-compass-fill"></i>
            <small class="fw-bold">বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি (BMMC) • বিশ্বজুড়ে বাংলাদেশি নাবিকদের ঐক্য</small>
        </div>

        <!-- Main Headline -->
        <h1 class="display-4 fw-bold text-white mb-3" style="line-height: 1.25;">
            সমুদ্রে নাবিকদের অধিকার রক্ষা, <span style="background: linear-gradient(135deg, #00d2ff, #38bdf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">মর্যাদা ও মানবকল্যাণের</span> জাতীয় প্ল্যাটফর্ম
        </h1>
        
        <!-- Subtitle -->
        <p class="lead text-light opacity-90 mx-auto mb-4" style="max-width: 860px; line-height: 1.7; font-size: 1.15rem;">
            বাংলাদেশি মার্চেন্ট মেরিনার এবং সাধারণ নাগরিকদের সম্মিলিত অরাজনৈতিক ও অলাভজনক উদ্যোগ। জরুরি উদ্ধার, আইনি পরামর্শ, ফ্রেশ ক্যাডেট প্রশিক্ষণ, ডিজিটাল স্মার্ট টুলস এবং জীবন রক্ষাকারী রক্তদান নেটওয়ার্ক সহ ২১টি সেবার সমন্বিত প্ল্যাটফর্ম।
        </p>

        <!-- Primary Call to Action Buttons -->
        <div class="d-flex flex-wrap justify-content-center gap-3 mb-5">
            <a href="#services-section" class="btn btn-ocean btn-lg rounded-pill px-4 py-3 shadow-lg fw-bold">
                <i class="bi bi-grid-3x3-gap-fill me-2"></i> আমাদের ২১টি সেবাসমূহ দেখুন
            </a>
            <a href="<?= BASE_URL ?>/blood.php" class="btn btn-blood btn-lg rounded-pill px-4 py-3 emergency-pulse shadow-lg fw-bold">
                <i class="bi bi-droplet-fill me-2 fs-5"></i> জরুরি রক্তদান পোর্টাল (Live)
            </a>
            <a href="<?= BASE_URL ?>/volunteer_register.php" class="btn btn-volunteer-highlight btn-lg rounded-pill px-4 py-3">
                <i class="bi bi-person-heart fs-5"></i> Become a BMMC Volunteer
            </a>
        </div>

        <!-- Key Metrics Bar -->
        <div class="row g-3 justify-content-center">
            <div class="col-6 col-md-3">
                <div class="glass-card p-3 p-md-4 text-center h-100 border-info border-opacity-25">
                    <div class="display-6 fw-bold text-info mb-1">২১টি</div>
                    <span class="text-secondary small fw-medium">বিশেষায়িত মেরিটাইম সেবা</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="glass-card p-3 p-md-4 text-center h-100 border-danger border-opacity-25">
                    <div class="display-6 fw-bold text-danger mb-1"><?= to_bangla_number($totalDonors ?: 100) ?>+</div>
                    <span class="text-secondary small fw-medium">নিবন্ধিত রক্তদাতা ও নাবিক</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="glass-card p-3 p-md-4 text-center h-100 border-warning border-opacity-25">
                    <div class="display-6 fw-bold text-warning mb-1">২৪/৭</div>
                    <span class="text-secondary small fw-medium">জরুরি সাপোর্ট ও হেল্পলাইন</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="glass-card p-3 p-md-4 text-center h-100 border-success border-opacity-25">
                    <div class="display-6 fw-bold text-success mb-1">১০০%</div>
                    <span class="text-secondary small fw-medium">অরাজনৈতিক ও অলাভজনক</span>
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
                        <span>সক্রিয় সেবা উইং (Active Live Service)</span>
                    </div>

                    <h2 class="display-6 fw-bold text-white mb-3">
                        <i class="bi bi-droplet-fill text-danger me-2"></i> BMMC রক্তদান নেটওয়ার্ক
                    </h2>
                    <p class="text-light opacity-90 mb-4" style="line-height: 1.7; font-size: 1.05rem;">
                        আমাদের প্রথম সম্পূর্ণ কার্যকর মানবকল্যাণমূলক সেবা। দেশজুড়ে চিকিৎসাধীন সাধারণ রোগী ও সাগরে কর্মরত মেরিনার পরিবারের রক্তের প্রয়োজনে সম্পূর্ণ নিঃস্বার্থ ও মধ্যস্থতাকারীবিহীন জরুরি রক্ত মেলানোর স্বয়ংক্রিয় প্ল্যাটফর্ম।
                    </p>

                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="badge bg-dark bg-opacity-60 border border-secondary text-light px-3 py-2 rounded-pill small">
                            <i class="bi bi-shield-check text-success me-1"></i> ৪ মাসের রেস্টিং পিরিয়ড সুরক্ষা
                        </span>
                        <span class="badge bg-dark bg-opacity-60 border border-secondary text-light px-3 py-2 rounded-pill small">
                            <i class="bi bi-lightning-charge-fill text-warning me-1"></i> তাৎক্ষণিক এলাকাভিত্তিক ম্যাচিং
                        </span>
                        <span class="badge bg-dark bg-opacity-60 border border-secondary text-light px-3 py-2 rounded-pill small">
                            <i class="bi bi-telephone-fill text-info me-1"></i> ২৪/৭ হটলাইন সহায়তা
                        </span>
                    </div>

                    <!-- Direct Action Buttons -->
                    <div class="d-flex flex-wrap gap-3">
                        <a href="<?= BASE_URL ?>/blood.php" class="btn btn-blood btn-lg rounded-pill px-4 py-2 fw-bold shadow-lg">
                            <i class="bi bi-arrow-right-circle-fill me-2"></i> রক্তদান পোর্টালে যান
                        </a>
                        <a href="<?= BASE_URL ?>/blood_request.php" class="btn btn-outline-light btn-lg rounded-pill px-4 py-2 fw-semibold">
                            <i class="bi bi-plus-circle me-1"></i> রক্তের আবেদন
                        </a>
                        <a href="<?= BASE_URL ?>/donor_register.php" class="btn btn-outline-warning btn-lg rounded-pill px-4 py-2 fw-semibold">
                            <i class="bi bi-heart-fill me-1"></i> ডোনার রেজিস্ট্রেশন
                        </a>
                    </div>
                </div>

                <!-- Recent Blood Appeals Feed Preview -->
                <div class="col-lg-5">
                    <div class="p-4 rounded-3 border border-secondary border-opacity-30" style="background: rgba(6, 18, 36, 0.75);">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-white fw-bold mb-0"><i class="bi bi-broadcast text-danger me-2"></i>জরুরি রক্তের আবেদনসমূহ</h6>
                            <a href="<?= BASE_URL ?>/blood.php" class="small text-info text-decoration-none">সকল দেখুন <i class="bi bi-arrow-right"></i></a>
                        </div>

                        <?php if (empty($recentRequests)): ?>
                            <div class="text-center py-4">
                                <i class="bi bi-check-circle-fill text-success fs-2 mb-2 d-block"></i>
                                <span class="text-light small">বর্তমানে কোনো জরুরি রক্তের আবেদন পেন্ডিং নেই!</span>
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
                                        সাহায্য <i class="bi bi-chevron-right"></i>
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
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill bg-info bg-opacity-10 border border-info border-opacity-25 text-info small fw-bold">
                <i class="bi bi-grid-fill"></i>
                <span>BMMC মাল্টি-ডিসিপ্লিনারি মেরিটাইম সেবা হাব</span>
            </div>
            <h2 class="display-6 fw-bold text-white mb-2">আমাদের ২১টি সার্বজনীন সেবাসমূহ</h2>
            <p class="text-secondary mx-auto" style="max-width: 760px;">
                নাবিকদের ক্যারিয়ার শুরু থেকে শুরু করে সমুদ্রে নিরাপত্তা, পারিবারিক কল্যাণ, আইনি অধিকার ও ডিজিটাল ইউটিলিটি—প্রতিটি উইং সম্পূর্ণ পেশাদারিত্বের সাথে ডিজাইন করা হয়েছে।
            </p>
        </div>

        <!-- Filter Tabs -->
        <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
            <button class="service-filter-btn active" onclick="filterServices('all', this)">
                <i class="bi bi-collection-fill"></i> সকল সেবা (২১টি)
            </button>
            <button class="service-filter-btn" onclick="filterServices('emergency', this)">
                <i class="bi bi-shield-exclamation text-danger"></i> জরুরি সাড়া ও কল্যাণ (৫টি)
            </button>
            <button class="service-filter-btn" onclick="filterServices('career', this)">
                <i class="bi bi-mortarboard-fill text-info"></i> ক্যারিয়ার ও ক্যাডেট (৪টি)
            </button>
            <button class="service-filter-btn" onclick="filterServices('tools', this)">
                <i class="bi bi-cpu-fill text-warning"></i> স্মার্ট টুলস ও ইউটিলিটি (৬টি)
            </button>
            <button class="service-filter-btn" onclick="filterServices('health', this)">
                <i class="bi bi-hospital-fill text-success"></i> স্বাস্থ্য ও ডিরেক্টরি (১টি)
            </button>
            <button class="service-filter-btn" onclick="filterServices('community', this)">
                <i class="bi bi-globe2 text-primary"></i> কমিউনিটি ও স্বচ্ছতা (৫টি)
            </button>
        </div>

        <!-- Mobile Horizontal Scroll Hint (Visible only on mobile) -->
        <div class="d-flex d-md-none justify-content-between align-items-center mb-3 px-1 text-secondary small">
            <span class="d-inline-flex align-items-center gap-1 text-info fw-semibold">
                <i class="bi bi-arrow-left-right"></i> ডানে-বামে সোয়াইপ করুন
            </span>
            <span class="badge bg-dark bg-opacity-75 border border-secondary border-opacity-40 text-secondary rounded-pill px-2 py-1" id="serviceFilterCountBadge">
                ২১টি সেবা
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
                                <span class="badge-live-pulse">সক্রিয় (LIVE)</span>
                            <?php else: ?>
                                <span class="badge-soon">নির্মাণাধীন</span>
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
                        <small class="text-secondary d-block mb-3" style="font-size: 0.78rem;">
                            <?= htmlspecialchars($s['title_en']) ?>
                        </small>

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
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill bg-info bg-opacity-15 border border-info border-opacity-30 text-info small fw-bold">
                        <i class="bi bi-person-heart"></i>
                        <span>বিএমএমসি সেচ্ছাসেবী নেটওয়ার্ক</span>
                    </div>

                    <h2 class="display-6 fw-bold text-white mb-3">
                        আপনার মেধা, সময় ও সহমর্মিতা দিয়ে শামিল হোন
                    </h2>
                    <p class="text-light opacity-90 mb-4" style="line-height: 1.8;">
                        BMMC কোনো বাণিজ্যিক প্রতিষ্ঠান নয়—এটি মেরিনার ও সাধারণ নাগরিকদের একটি নিঃস্বার্থ যৌথ মানবকল্যাণ আন্দোলন। সাগরে বিপন্ন নাবিকদের উদ্ধার, ক্যাডেটদের প্রশিক্ষণ, রক্তদান সমন্বয় বা আইনি সহমর্মিতা—যেকোনো উইংয়ে আপনি ভলান্টিয়ার হতে পারেন।
                    </p>

                    <div class="alert alert-dark bg-opacity-50 border-secondary mb-0 py-2 small text-warning">
                        <i class="bi bi-info-circle-fill me-1"></i> 
                        ভলান্টিয়ার ফর্মে <strong>'Agree to donate blood'</strong> চেকলিস্ট সিলেক্ট করলে একই সাথে ব্লাড ডোনার নেটওয়ার্কেও নিবন্ধিত হবেন!
                    </div>
                </div>

                <div class="col-lg-4 text-center">
                    <div class="p-4 rounded-4 border border-secondary border-opacity-25" style="background: rgba(6, 18, 36, 0.85);">
                        <i class="bi bi-people-fill text-info fs-1 mb-3 d-block"></i>
                        <h5 class="text-white fw-bold mb-2">ভলান্টিয়ার আবেদন</h5>
                        <p class="text-secondary small mb-4">চট্টগ্রাম, ঢাকা, খুলনা বা সমুদ্রের যেকোনো অবস্থান থেকেই যুক্ত হতে পারেন।</p>
                        <a href="<?= BASE_URL ?>/volunteer_register.php" class="btn btn-volunteer-highlight w-100 py-3 rounded-pill fs-6">
                            <i class="bi bi-pencil-square me-2"></i> Become a BMMC Volunteer
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
                    <h5 class="text-white fw-bold mb-2">১০০% আর্থিক স্বচ্ছতা</h5>
                    <p class="text-secondary small mb-0">
                        প্রতিটি টাকা অনুদান ও খরচের হিসাব ওয়েবসাইটে উন্মুক্ত লাইভ লেজারের মাধ্যমে সবার পরীক্ষার জন্য প্রদর্শিত হবে।
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-4 h-100 text-center">
                    <div style="width: 54px; height: 54px; margin: 0 auto 16px; background: rgba(0, 210, 255, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid #00d2ff;">
                        <i class="bi bi-patch-check-fill text-info fs-3"></i>
                    </div>
                    <h5 class="text-white fw-bold mb-2">ভেরিফায়েড নাবিক সম্প্রদায়</h5>
                    <p class="text-secondary small mb-0">
                        সরকারি সিডিসি ও পরিচয়পত্র যাচাইয়ের মাধ্যমে নকল পরিচয় ও ভুয়া এজেন্সির প্রতারণা নির্মূলে অঙ্গীকারবদ্ধ।
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-4 h-100 text-center">
                    <div style="width: 54px; height: 54px; margin: 0 auto 16px; background: rgba(245, 158, 11, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid #f59e0b;">
                        <i class="bi bi-heart-fill text-warning fs-3"></i>
                    </div>
                    <h5 class="text-white fw-bold mb-2">নিঃস্বার্থ মানবসেবা</h5>
                    <p class="text-secondary small mb-0">
                        কোনো রাজনৈতিক সংশ্লিষ্টতা নেই। আমাদের একমাত্র লক্ষ্য বিশ্বজুড়ে মেরিনার এবং দেশের সাধারণ মানুষের পাশে দাঁড়ানো।
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
        countBadge.textContent = visibleCount + 'টি সেবা';
    }

    // Smoothly rewind horizontal container to the beginning
    const grid = document.getElementById('servicesGrid');
    if (grid) {
        grid.scrollTo({ left: 0, behavior: 'smooth' });
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
