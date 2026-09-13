<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';

$currentUser = current_user();
$isAdmin = is_admin();
?>
<nav class="navbar navbar-expand-lg navbar-dark glass-nav sticky-top py-2 py-lg-3">
    <div class="container">
        <!-- Logo / Brand -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= BASE_URL ?>/index.php">
            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #00d2ff, #0284c7); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(0, 210, 255, 0.4);">
                <i class="bi bi-compass fs-4 text-white"></i>
            </div>
            <div>
                <span class="fw-bold fs-5 text-white d-block" style="line-height: 1.1;">BMMC</span>
                <small class="text-info" style="font-size: 0.70rem; letter-spacing: 0.3px;">বাংলাদেশ মার্চেন্ট মেরিনার্স</small>
            </div>
        </a>

        <!-- Custom Mobile Toggle Button -->
        <button class="navbar-toggler border-0 shadow-none p-2 rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.2) !important;">
            <i class="bi bi-list fs-3 text-white"></i>
        </button>

        <!-- Navbar Links & Mobile Collapsible Drawer -->
        <div class="collapse navbar-collapse mobile-nav-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3 pt-3 pt-lg-0">
                <li class="nav-item">
                    <a class="nav-link text-white py-2 px-3 rounded-2 nav-link-mobile" href="<?= BASE_URL ?>/index.php">
                        <i class="bi bi-house-door me-2 text-info"></i> হোম
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white py-2 px-3 rounded-2 nav-link-mobile" href="<?= BASE_URL ?>/blood_request.php">
                        <i class="bi bi-droplet-half me-2 text-danger"></i> রক্তের আবেদন
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white py-2 px-3 rounded-2 nav-link-mobile" href="<?= BASE_URL ?>/donor_register.php">
                        <i class="bi bi-heart-pulse me-2 text-danger"></i> ডোনার রেজিস্ট্রেশন
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white py-2 px-3 rounded-2 nav-link-mobile" href="<?= BASE_URL ?>/volunteer_register.php">
                        <i class="bi bi-people me-2 text-warning"></i> ভলান্টিয়ার টিম
                    </a>
                </li>
                <?php if ($isAdmin): ?>
                <li class="nav-item">
                    <a class="nav-link text-warning fw-bold py-2 px-3 rounded-2 nav-link-mobile" href="<?= BASE_URL ?>/admin/dashboard.php">
                        <i class="bi bi-shield-lock-fill me-2"></i> অ্যাডমিন প্যানেল
                    </a>
                </li>
                <?php endif; ?>
            </ul>

            <!-- Mobile & Desktop Action Buttons -->
            <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 mt-3 mt-lg-0 pt-3 pt-lg-0 border-top border-lg-0 border-secondary border-opacity-25">
                <a href="<?= BASE_URL ?>/blood_request.php" class="btn btn-blood btn-sm text-center emergency-pulse w-100 w-lg-auto py-2 px-3">
                    <i class="bi bi-exclamation-octagon-fill me-1"></i> জরুরি রক্ত চাই
                </a>

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
                    <a href="<?= BASE_URL ?>/login.php" class="btn btn-outline-light btn-sm rounded-pill px-4 py-2 py-lg-1 text-center w-100 w-lg-auto">
                        <i class="bi bi-box-arrow-in-right me-1"></i> লগইন
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
