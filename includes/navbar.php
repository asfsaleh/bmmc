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
                <small class="text-info" style="font-size: 0.70rem; letter-spacing: 0.3px;"><?= __('nav_brand_sub', 'Bangladesh Merchant Mariners') ?></small>
            </div>
        </a>

        <!-- Mobile Header Action Area (Day/Night switch & Hamburger toggler) -->
        <div class="d-flex align-items-center gap-2 d-lg-none">
            <button class="theme-toggle-btn js-theme-toggle" type="button" aria-label="Toggle Theme" title="দিন/রাত মোড পরিবর্তন করুন">
                <i class="bi bi-sun-fill theme-toggle-icon"></i>
            </button>
            <button class="navbar-toggler border-0 shadow-none p-2 rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.2) !important;">
                <i class="bi bi-list fs-2"></i>
            </button>
        </div>

        <!-- Navbar Links & Mobile Collapsible Drawer -->
        <div class="collapse navbar-collapse mobile-nav-collapse" id="navbarMain">
            <!-- Mobile Language Switcher (Prominently placed at top of Hamburger Menu) -->
            <div class="d-lg-none d-flex align-items-center justify-content-between p-2 mb-3 mt-2 rounded-3 bmmc-mobile-lang-bar" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.12);">
                <span class="small text-secondary ps-1 fw-semibold d-flex align-items-center gap-1">
                    <i class="bi bi-translate text-info"></i> <?= __('lang_switch_title', 'Language') ?>
                </span>
                <div class="btn-group btn-group-sm rounded-pill p-1" style="background: rgba(0, 0, 0, 0.35); border: 1px solid rgba(255, 255, 255, 0.1);">
                    <a href="<?= lang_url('en') ?>" class="btn btn-sm rounded-pill px-3 py-1 <?= is_english() ? 'fw-bold shadow-sm' : 'text-light' ?>" style="<?= is_english() ? 'background: #00d2ff; color: #061426; font-weight: 700;' : 'color: rgba(255,255,255,0.7);' ?>">
                        English
                    </a>
                    <a href="<?= lang_url('bn') ?>" class="btn btn-sm rounded-pill px-3 py-1 <?= !is_english() ? 'fw-bold shadow-sm' : 'text-light' ?>" style="<?= !is_english() ? 'background: #00d2ff; color: #061426; font-weight: 700;' : 'color: rgba(255,255,255,0.7);' ?>">
                        বাংলা
                    </a>
                </div>
            </div>

            <!-- Centered Nav Links: Home, Our Services, Blood Portal -->
            <ul class="navbar-nav mx-lg-auto mb-2 mb-lg-0 pt-3 pt-lg-0 align-items-lg-center gap-1 gap-lg-2">
                <!-- Home -->
                <li class="nav-item">
                    <a class="nav-link text-white py-2 px-3 rounded-pill nav-link-mobile nav-pill-btn" href="<?= BASE_URL ?>/index.php">
                        <i class="bi bi-house-door me-1 text-info"></i> <?= __('nav_home', 'Home') ?>
                    </a>
                </li>

                <!-- Desktop "Our Services" Mega Dropdown (Visible on Desktop: d-none d-lg-block) -->
                <li class="nav-item dropdown dropdown-mega d-none d-lg-block">
                    <a class="nav-link dropdown-toggle text-white py-2 px-3 rounded-pill nav-link-mobile nav-pill-btn" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                        <i class="bi bi-grid-fill me-1 text-info"></i> <?= __('nav_our_services', 'Our Services') ?>
                    </a>

                    <!-- Desktop Mega Menu -->
                    <div class="dropdown-menu dropdown-mega-menu border-0 shadow-lg" id="desktopServicesMenu" aria-labelledby="servicesDropdown">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 mega-header-border">
                            <div>
                                <h6 class="mega-header-title fw-bold mb-0 d-flex align-items-center gap-2">
                                    <i class="bi bi-compass text-info fs-5"></i>
                                    <span><?= __('nav_mega_title') ?></span>
                                </h6>
                                <small class="mega-header-subtitle" style="font-size: 0.78rem;"><?= __('nav_mega_subtitle') ?></small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge mega-status-badge px-3 py-1 rounded-pill small">
                                    <i class="bi bi-check-circle-fill me-1"></i> <?= __('nav_mega_status') ?>
                                </span>
                                <button type="button" class="btn mega-close-btn btn-sm rounded-circle p-0 border-0" id="closeMegaMenuBtn" title="<?= __('btn_close', 'Close') ?>">
                                    <i class="bi bi-x-lg" style="font-size: 0.85rem;"></i>
                                </button>
                            </div>
                        </div>

                        <!-- 5 Pillars Grid -->
                        <div class="mega-pillars-grid">
                            <?php foreach ($pillars as $pId => $pillar): 
                                $pServices = get_services_by_pillar($pId);
                            ?>
                            <div class="mega-pillar-col">
                                <div class="mega-pillar-title" title="<?= htmlspecialchars($pillar['title']) ?>">
                                    <i class="bi <?= htmlspecialchars($pillar['icon']) ?>" style="color: <?= htmlspecialchars($pillar['color']) ?>;"></i>
                                    <span><?= htmlspecialchars($pillar['title']) ?></span>
                                </div>
                                <div class="mega-service-list">
                                    <?php foreach ($pServices as $s): ?>
                                    <a href="<?= BASE_URL ?>/<?= htmlspecialchars($s['link']) ?>" class="mega-service-link" title="<?= htmlspecialchars($s['title']) ?> — <?= htmlspecialchars($s['title_en']) ?>">
                                        <div class="mega-service-icon" style="color: <?= htmlspecialchars($s['color']) ?>;">
                                            <i class="bi <?= htmlspecialchars($s['icon']) ?>"></i>
                                        </div>
                                        <div class="flex-grow-1" style="min-width: 0;">
                                            <div class="d-flex align-items-center justify-content-between gap-1">
                                                <span class="mega-service-title"><?= htmlspecialchars($s['title']) ?></span>
                                                <?php if ($s['status'] === 'live'): ?>
                                                    <span class="badge-live-pulse flex-shrink-0">LIVE</span>
                                                <?php else: ?>
                                                    <span class="badge-soon flex-shrink-0">Soon</span>
                                                <?php endif; ?>
                                            </div>
                                            <small class="mega-service-sub text-truncate"><?= htmlspecialchars($s['title_en']) ?></small>
                                        </div>
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-3 pt-3 mega-footer-border d-flex justify-content-between align-items-center">
                            <div class="small mega-footer-hint">
                                <i class="bi bi-info-circle me-1 text-info"></i> <?= __('nav_mega_footer_hint') ?>
                            </div>
                            <a href="<?= BASE_URL ?>/index.php#services-section" class="btn mega-footer-btn btn-sm rounded-pill px-3 py-1 close-mega-on-click">
                                <?= __('nav_view_all_services') ?> <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </li>

                <!-- Mobile Multi-Level Services Accordion (Visible on Mobile only: d-lg-none) -->
                <li class="nav-item d-lg-none w-100">
                    <a class="nav-link text-white py-2 px-3 rounded-3 nav-pill-btn d-flex align-items-center justify-content-between" data-bs-toggle="collapse" href="#mobileServicesCollapse" role="button" aria-expanded="false" aria-controls="mobileServicesCollapse" id="mobileServicesToggle" style="background: rgba(255, 255, 255, 0.04);">
                        <span><i class="bi bi-grid-fill me-2 text-info"></i><?= __('nav_our_services') ?></span>
                        <i class="bi bi-chevron-down toggle-icon transition-transform"></i>
                    </a>

                    <!-- Level 1 Submenu: সেবার ধরন গুলো (5 Pillars) -->
                    <div class="collapse mt-2 ps-1" id="mobileServicesCollapse">
                        <div class="accordion accordion-flush mobile-pillars-accordion my-1" id="mobilePillarsAcc">
                            <?php foreach ($pillars as $pId => $pillar): 
                                $pServices = get_services_by_pillar($pId);
                            ?>
                            <div class="accordion-item mb-2 rounded-3 overflow-hidden" style="background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08);">
                                <h2 class="accordion-header" id="heading_mob_<?= $pId ?>">
                                    <button class="accordion-button collapsed py-2 px-3 text-white d-flex align-items-center gap-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_mob_<?= $pId ?>" aria-expanded="false" aria-controls="collapse_mob_<?= $pId ?>" style="background: rgba(255, 255, 255, 0.03); font-size: 0.88rem; font-weight: 600;">
                                        <i class="bi <?= htmlspecialchars($pillar['icon']) ?>" style="color: <?= htmlspecialchars($pillar['color']) ?>; font-size: 1rem;"></i>
                                        <span class="flex-grow-1 text-truncate"><?= htmlspecialchars($pillar['title']) ?></span>
                                        <span class="badge bg-secondary bg-opacity-50 text-white-50 rounded-pill px-2 py-0" style="font-size: 0.65rem;"><?= count($pServices) ?> <?= is_english() ? 'Services' : 'টি' ?></span>
                                    </button>
                                </h2>
                                <!-- Level 2 Submenu: সেবা গুলো (Services list under this pillar) -->
                                <div id="collapse_mob_<?= $pId ?>" class="accordion-collapse collapse" data-bs-parent="#mobilePillarsAcc">
                                    <div class="accordion-body p-2 pt-1">
                                        <?php foreach ($pServices as $s): ?>
                                        <a href="<?= BASE_URL ?>/<?= htmlspecialchars($s['link']) ?>" class="d-flex align-items-center justify-content-between p-2 text-decoration-none rounded-2 mb-1 mobile-service-item" style="background: rgba(0, 0, 0, 0.25); border-left: 3px solid <?= htmlspecialchars($s['color']) ?>;">
                                            <div class="d-flex align-items-center gap-2 overflow-hidden">
                                                <div style="width: 26px; height: 26px; border-radius: 6px; background: rgba(255, 255, 255, 0.08); display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: <?= htmlspecialchars($s['color']) ?>;">
                                                    <i class="bi <?= htmlspecialchars($s['icon']) ?>" style="font-size: 0.85rem;"></i>
                                                </div>
                                                <div class="text-truncate">
                                                    <div class="text-white small fw-semibold text-truncate"><?= htmlspecialchars($s['title']) ?></div>
                                                    <div class="text-secondary text-truncate" style="font-size: 0.68rem;"><?= htmlspecialchars($s['title_en']) ?></div>
                                                </div>
                                            </div>
                                            <?php if ($s['status'] === 'live'): ?>
                                                <span class="badge-live-pulse flex-shrink-0 ms-2"><?= __('nav_live_badge') ?></span>
                                            <?php else: ?>
                                                <span class="badge-soon flex-shrink-0 ms-2"><?= __('nav_soon_badge') ?></span>
                                            <?php endif; ?>
                                        </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="p-2 border-top border-secondary border-opacity-25 mt-2 text-center">
                            <a href="<?= BASE_URL ?>/index.php#services-section" class="btn btn-outline-info btn-sm rounded-pill w-100 py-1" style="font-size: 0.82rem;">
                                <i class="bi bi-grid-fill me-1"></i> <?= __('nav_all_services_list') ?>
                            </a>
                        </div>
                    </div>
                </li>

                <!-- Dedicated Blood Portal Direct Button -->
                <li class="nav-item">
                    <a class="nav-link text-white py-2 px-3 rounded-pill nav-link-mobile nav-pill-btn d-flex align-items-center gap-1" href="<?= BASE_URL ?>/blood.php">
                        <i class="bi bi-droplet-fill text-danger"></i>
                        <span><?= __('nav_blood_portal') ?></span>
                        <span class="badge-live-pulse ms-1"><?= __('nav_live_badge') ?></span>
                    </a>
                </li>

                <!-- Admin Dashboard Link -->
                <?php if ($isAdmin): ?>
                <li class="nav-item">
                    <a class="nav-link text-warning fw-bold py-2 px-3 rounded-pill nav-link-mobile nav-pill-btn" href="<?= BASE_URL ?>/admin/dashboard.php">
                        <i class="bi bi-shield-lock-fill me-1"></i> <?= __('nav_admin') ?>
                    </a>
                </li>
                <?php endif; ?>
            </ul>

            <!-- Mobile & Desktop Action Area (Right Side) -->
            <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 mt-3 mt-lg-0 pt-3 pt-lg-0 border-top border-lg-0 border-secondary border-opacity-25 flex-shrink-0">
                
                <!-- Day/Night Switch for Desktop -->
                <button class="theme-toggle-btn js-theme-toggle d-none d-lg-inline-flex me-1" type="button" aria-label="Toggle Theme" title="দিন/রাত মোড পরিবর্তন করুন (GMT+6 স্বয়ংক্রিয়)">
                    <i class="bi bi-sun-fill theme-toggle-icon"></i>
                </button>

                <!-- User Login / Profile Dropdown -->
                <?php if ($currentUser): ?>
                    <div class="dropdown nav-action-btn flex-shrink-0">
                        <button class="btn btn-outline-info dropdown-toggle rounded-pill px-3 py-2 py-lg-1 btn-sm w-100 text-center text-nowrap" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($currentUser['name']) ?>
                            <?php if ($currentUser['user_type'] === 'mariner'): ?>
                                <span class="badge bg-primary ms-1"><?= is_english() ? 'Mariner' : 'মেরিনার' ?></span>
                            <?php endif; ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark glass-card border-secondary w-100 mt-2">
                            <li><a class="dropdown-item text-white py-2" href="<?= BASE_URL ?>/donor_dashboard.php"><i class="bi bi-speedometer2 me-2 text-info"></i><?= __('nav_donor_dashboard') ?></a></li>
                            <?php if ($isAdmin): ?>
                            <li><a class="dropdown-item text-warning py-2" href="<?= BASE_URL ?>/admin/dashboard.php"><i class="bi bi-shield-lock me-2"></i><?= __('nav_admin_dashboard') ?></a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider border-secondary"></li>
                            <li><a class="dropdown-item text-danger py-2" href="<?= BASE_URL ?>/logout.php"><i class="bi bi-box-arrow-right me-2"></i><?= __('nav_logout') ?></a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login.php" class="btn btn-outline-light btn-sm rounded-pill px-3 py-2 py-lg-1 text-center text-nowrap flex-shrink-0 nav-action-btn">
                        <i class="bi bi-box-arrow-in-right me-1"></i> <?= __('nav_login') ?>
                    </a>
                <?php endif; ?>

                <!-- ==============================================================
                     HIGHLIGHTED FAR-RIGHT BUTTON: "BECOME A BMMC VOLUNTEER"
                =============================================================== -->
                <a href="<?= BASE_URL ?>/volunteer_register.php" class="btn-volunteer-highlight text-nowrap flex-shrink-0 nav-action-btn" title="Become a BMMC Volunteer">
                    <i class="bi bi-person-heart"></i>
                    <span>Become a BMMC Volunteer</span>
                </a>

            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var servicesDropdown = document.getElementById('servicesDropdown');
    var desktopMenu = document.getElementById('desktopServicesMenu');
    var closeBtn = document.getElementById('closeMegaMenuBtn');

    function closeDesktopMega() {
        if (!servicesDropdown || !desktopMenu) return;
        desktopMenu.classList.remove('show');
        servicesDropdown.classList.remove('show');
        servicesDropdown.setAttribute('aria-expanded', 'false');
        if (window.bootstrap) {
            var bsDropdown = bootstrap.Dropdown.getInstance(servicesDropdown);
            if (bsDropdown) {
                bsDropdown.hide();
            }
        }
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            closeDesktopMega();
        });
    }

    // Close when clicking hash links like #services-section
    var closeLinks = document.querySelectorAll('.close-mega-on-click');
    closeLinks.forEach(function (link) {
        link.addEventListener('click', function () {
            closeDesktopMega();
        });
    });

    // Close when clicking outside on desktop
    document.addEventListener('click', function (e) {
        if (desktopMenu && desktopMenu.classList.contains('show')) {
            if (!desktopMenu.contains(e.target) && !servicesDropdown.contains(e.target)) {
                closeDesktopMega();
            }
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && desktopMenu && desktopMenu.classList.contains('show')) {
            closeDesktopMega();
        }
    });

    // ============================================================
    // BMMC Universal Day / Night Theme Controller (GMT+6 Automatic)
    // ============================================================
    function getBstHour() {
        var now = new Date();
        var utc = now.getTime() + (now.getTimezoneOffset() * 60000);
        return new Date(utc + (3600000 * 6)).getHours();
    }

    function getBstAutoTheme() {
        var hour = getBstHour();
        // Day theme: 06:00 to 17:59 BST
        return (hour >= 6 && hour < 18) ? 'light' : 'dark';
    }

    function applyTheme(theme, isManual) {
        document.documentElement.setAttribute('data-theme', theme);
        document.documentElement.setAttribute('data-bs-theme', theme);

        var metaColor = document.getElementById('metaThemeColor');
        if (metaColor) {
            metaColor.setAttribute('content', theme === 'light' ? '#f0f7ff' : '#061426');
        }

        var toggleBtns = document.querySelectorAll('.js-theme-toggle');
        toggleBtns.forEach(function(btn) {
            var icon = btn.querySelector('.theme-toggle-icon');
            if (icon) {
                if (theme === 'light') {
                    icon.className = 'bi bi-moon-stars-fill theme-toggle-icon';
                    btn.setAttribute('title', 'রাত মোডে পরিবর্তন করুন (বর্তমানে: দিন)');
                    btn.setAttribute('aria-label', 'Switch to Night mode');
                } else {
                    icon.className = 'bi bi-sun-fill theme-toggle-icon';
                    btn.setAttribute('title', 'দিন মোডে পরিবর্তন করুন (বর্তমানে: রাত)');
                    btn.setAttribute('aria-label', 'Switch to Day mode');
                }
            }
        });

        if (isManual) {
            localStorage.setItem('bmmc_theme_preference', theme);
        }
    }

    window.toggleBmmcTheme = function() {
        var current = document.documentElement.getAttribute('data-theme') || 'dark';
        var next = (current === 'light') ? 'dark' : 'light';
        applyTheme(next, true);
    };

    // Attach click listeners to all theme toggle buttons
    var toggleBtns = document.querySelectorAll('.js-theme-toggle');
    toggleBtns.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            window.toggleBmmcTheme();
        });
    });

    // Apply current state to buttons
    var savedPref = localStorage.getItem('bmmc_theme_preference');
    var activeTheme = (savedPref === 'light' || savedPref === 'dark') ? savedPref : getBstAutoTheme();
    applyTheme(activeTheme, false);

    // Auto-update if no manual preference is saved and BST crosses day/night boundary
    setInterval(function() {
        var pref = localStorage.getItem('bmmc_theme_preference');
        if (!pref || pref === 'auto') {
            var autoTheme = getBstAutoTheme();
            var current = document.documentElement.getAttribute('data-theme');
            if (autoTheme !== current) {
                applyTheme(autoTheme, false);
            }
        }
    }, 60000);
});
</script>
