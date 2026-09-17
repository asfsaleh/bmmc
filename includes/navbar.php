<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/services_data.php';

$currentUser = current_user();
$isAdmin = is_admin();
$pillars = get_bmmc_pillars();
$services = get_bmmc_services();
?>
<nav class="navbar navbar-expand-lg navbar-dark glass-nav sticky-top py-2 py-lg-3">
    <div class="container position-relative">
        <!-- Logo / Brand -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= BASE_URL ?>/index.php">
            <div style="width: 42px; height: 42px; background: linear-gradient(135deg, #00d2ff, #0284c7); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 16px rgba(0, 210, 255, 0.45);">
                <i class="bi bi-compass fs-4 text-white"></i>
            </div>
            <div>
                <span class="fw-bold fs-5 text-white d-block" style="line-height: 1.1; letter-spacing: 0.5px;">BMMC</span>
                <small class="text-info" style="font-size: 0.70rem; letter-spacing: 0.3px;">বাংলাদেশ মার্চেন্ট মেরিনার্স</small>
            </div>
        </a>

        <!-- Custom Mobile Toggle Button -->
        <button class="navbar-toggler border-0 shadow-none p-2 rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.2) !important;">
            <i class="bi bi-list fs-2 text-white"></i>
        </button>

        <!-- Navbar Links & Mobile Collapsible Drawer -->
        <div class="collapse navbar-collapse mobile-nav-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-2 pt-3 pt-lg-0 align-items-lg-center">
                <!-- Home -->
                <li class="nav-item">
                    <a class="nav-link text-white py-2 px-3 rounded-2 nav-link-mobile" href="<?= BASE_URL ?>/index.php">
                        <i class="bi bi-house-door me-1 text-info"></i> হোম
                    </a>
                </li>

                <!-- Our Services (Mega Dropdown on Desktop, Collapsible on Mobile) -->
                <li class="nav-item dropdown dropdown-mega">
                    <a class="nav-link dropdown-toggle text-white py-2 px-3 rounded-2 nav-link-mobile" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-grid-fill me-1 text-info"></i> আমাদের সেবাসমূহ
                    </a>

                    <!-- Desktop Mega Menu -->
                    <div class="dropdown-menu dropdown-mega-menu d-none d-lg-block border-0 shadow-lg" aria-labelledby="servicesDropdown">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-secondary border-opacity-25">
                            <div>
                                <h6 class="text-white fw-bold mb-0"><i class="bi bi-compass text-info me-2"></i>BMMC সার্বজনীন মেরিটাইম সেবা উইং</h6>
                                <small class="text-secondary">সমগ্র বিশ্বের বাংলাদেশি নাবিক ও পরিবারের কল্যাণ ও অধিকার সুরক্ষায় নিবেদিত ২১টি সেবা</small>
                            </div>
                            <div>
                                <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-3 py-1 rounded-pill small">
                                    <i class="bi bi-check-circle-fill me-1"></i> ১টি সেবা সক্রিয় (Live) • ২০টি নির্মাণাধীন
                                </span>
                            </div>
                        </div>

                        <div class="row g-3">
                            <?php foreach ($pillars as $pId => $pillar): 
                                $pServices = get_services_by_pillar($pId);
                            ?>
                            <div class="col-lg">
                                <div class="mega-pillar-title">
                                    <i class="bi <?= htmlspecialchars($pillar['icon']) ?>" style="color: <?= htmlspecialchars($pillar['color']) ?>;"></i>
                                    <span><?= htmlspecialchars($pillar['title']) ?></span>
                                </div>
                                <div class="mega-service-list">
                                    <?php foreach ($pServices as $s): ?>
                                    <a href="<?= BASE_URL ?>/<?= htmlspecialchars($s['link']) ?>" class="mega-service-link">
                                        <div class="mega-service-icon" style="color: <?= htmlspecialchars($s['color']) ?>;">
                                            <i class="bi <?= htmlspecialchars($s['icon']) ?>"></i>
                                        </div>
                                        <div class="flex-grow-1" style="min-width: 0;">
                                            <div class="d-flex align-items-center justify-content-between gap-1">
                                                <span class="fw-semibold text-truncate small text-light"><?= htmlspecialchars($s['title']) ?></span>
                                                <?php if ($s['status'] === 'live'): ?>
                                                    <span class="badge-live-pulse">LIVE</span>
                                                <?php endif; ?>
                                            </div>
                                            <small class="text-secondary d-block text-truncate" style="font-size: 0.72rem;"><?= htmlspecialchars($s['title_en']) ?></small>
                                        </div>
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-3 pt-3 border-top border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                            <div class="small text-secondary">
                                <i class="bi bi-info-circle me-1 text-info"></i> প্রতিটি সেবার বিস্তারিত ও রোডম্যাপ দেখতে যেকোনো সেবায় ক্লিক করুন।
                            </div>
                            <a href="<?= BASE_URL ?>/index.php#services-section" class="btn btn-outline-info btn-sm rounded-pill px-3 py-1">
                                সমস্ত সেবার গ্রিড ভিউ <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Mobile Accordion Menu inside Dropdown -->
                    <div class="dropdown-menu dropdown-menu-dark d-lg-none glass-card border-secondary w-100 p-2 mt-2">
                        <div class="mobile-services-accordion accordion accordion-flush" id="mobileServicesAcc">
                            <?php foreach ($pillars as $pId => $pillar): 
                                $pServices = get_services_by_pillar($pId);
                            ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading_<?= $pId ?>">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?= $pId ?>">
                                        <i class="bi <?= htmlspecialchars($pillar['icon']) ?> me-2" style="color: <?= htmlspecialchars($pillar['color']) ?>;"></i>
                                        <?= htmlspecialchars($pillar['title']) ?>
                                    </button>
                                </h2>
                                <div id="collapse_<?= $pId ?>" class="accordion-collapse collapse" data-bs-parent="#mobileServicesAcc">
                                    <div class="accordion-body">
                                        <?php foreach ($pServices as $s): ?>
                                        <a href="<?= BASE_URL ?>/<?= htmlspecialchars($s['link']) ?>" class="d-flex align-items-center justify-content-between py-2 px-2 text-light text-decoration-none rounded hover-bg">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi <?= htmlspecialchars($s['icon']) ?>" style="color: <?= htmlspecialchars($s['color']) ?>;"></i>
                                                <span class="small"><?= htmlspecialchars($s['title']) ?></span>
                                            </div>
                                            <?php if ($s['status'] === 'live'): ?>
                                                <span class="badge-live-pulse">LIVE</span>
                                            <?php else: ?>
                                                <span class="badge-soon">Soon</span>
                                            <?php endif; ?>
                                        </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="p-2 border-top border-secondary border-opacity-25 mt-2 text-center">
                            <a href="<?= BASE_URL ?>/index.php#services-section" class="btn btn-outline-info btn-sm rounded-pill w-100">
                                <i class="bi bi-grid-fill me-1"></i> সমস্ত সেবাসমূহ দেখুন
                            </a>
                        </div>
                    </div>
                </li>

                <!-- Dedicated Blood Portal Direct Button -->
                <li class="nav-item">
                    <a class="nav-link text-white py-2 px-3 rounded-2 nav-link-mobile d-flex align-items-center gap-1" href="<?= BASE_URL ?>/blood.php">
                        <i class="bi bi-droplet-fill text-danger"></i>
                        <span>রক্তদান পোর্টাল</span>
                        <span class="badge-live-pulse ms-1">LIVE</span>
                    </a>
                </li>

                <!-- Admin Dashboard Link -->
                <?php if ($isAdmin): ?>
                <li class="nav-item">
                    <a class="nav-link text-warning fw-bold py-2 px-3 rounded-2 nav-link-mobile" href="<?= BASE_URL ?>/admin/dashboard.php">
                        <i class="bi bi-shield-lock-fill me-1"></i> অ্যাডমিন
                    </a>
                </li>
                <?php endif; ?>
            </ul>

            <!-- Mobile & Desktop Action Area -->
            <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 mt-3 mt-lg-0 pt-3 pt-lg-0 border-top border-lg-0 border-secondary border-opacity-25">
                
                <!-- User Login / Profile Dropdown -->
                <?php if ($currentUser): ?>
                    <div class="dropdown w-100 w-lg-auto">
                        <button class="btn btn-outline-info dropdown-toggle rounded-pill px-3 py-2 py-lg-1 btn-sm w-100 text-center" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($currentUser['name']) ?>
                            <?php if ($currentUser['user_type'] === 'mariner'): ?>
                                <span class="badge bg-primary ms-1">মেরিনার</span>
                            <?php endif; ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark glass-card border-secondary w-100 mt-2">
                            <li><a class="dropdown-item text-white py-2" href="<?= BASE_URL ?>/donor_dashboard.php"><i class="bi bi-speedometer2 me-2 text-info"></i>ড্যাশবোর্ড</a></li>
                            <?php if ($isAdmin): ?>
                            <li><a class="dropdown-item text-warning py-2" href="<?= BASE_URL ?>/admin/dashboard.php"><i class="bi bi-shield-lock me-2"></i>অ্যাডমিন ড্যাশবোর্ড</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider border-secondary"></li>
                            <li><a class="dropdown-item text-danger py-2" href="<?= BASE_URL ?>/logout.php"><i class="bi bi-box-arrow-right me-2"></i>লগআউট</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login.php" class="btn btn-outline-light btn-sm rounded-pill px-3 py-2 py-lg-1 text-center w-100 w-lg-auto">
                        <i class="bi bi-box-arrow-in-right me-1"></i> লগইন
                    </a>
                <?php endif; ?>

                <!-- ==============================================================
                     HIGHLIGHTED FAR-RIGHT BUTTON: "BECOME A BMMC VOLUNTEER"
                =============================================================== -->
                <a href="<?= BASE_URL ?>/volunteer_register.php" class="btn-volunteer-highlight w-100 w-lg-auto text-center" title="বিএমএমসি ভলান্টিয়ার টিমে যুক্ত হোন">
                    <i class="bi bi-person-heart fs-5"></i>
                    <span>Become a BMMC Volunteer</span>
                </a>

            </div>
        </div>
    </div>
</nav>
