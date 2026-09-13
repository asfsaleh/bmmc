<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';

$currentUser = current_user();
$isAdmin = is_admin();
?>
<nav class="navbar navbar-expand-lg navbar-dark glass-nav sticky-top py-3">
    <div class="container">
        <!-- Logo / Brand -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= BASE_URL ?>/index.php">
            <div style="width: 44px; height: 44px; background: linear-gradient(135deg, #00d2ff, #0284c7); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(0, 210, 255, 0.4);">
                <i class="bi bi-compass fs-4 text-white"></i>
            </div>
            <div>
                <span class="fw-bold fs-5 text-white d-block" style="line-height: 1.2;">BMMC</span>
                <small class="text-info" style="font-size: 0.72rem; letter-spacing: 0.5px;">বাংলাদেশ মার্চেন্ট মেরিনার্স</small>
            </div>
        </a>

        <!-- Mobile toggle button -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= BASE_URL ?>/index.php">
                        <i class="bi bi-house-door me-1 text-info"></i> হোম
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= BASE_URL ?>/blood_request.php">
                        <i class="bi bi-droplet-half me-1 text-danger"></i> রক্তের আবেদন
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= BASE_URL ?>/donor_register.php">
                        <i class="bi bi-heart-pulse me-1 text-danger"></i> ডোনার রেজিস্ট্রেশন
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= BASE_URL ?>/volunteer_register.php">
                        <i class="bi bi-people me-1 text-warning"></i> ভলান্টিয়ার টিম
                    </a>
                </li>
                <?php if ($isAdmin): ?>
                <li class="nav-item">
                    <a class="nav-link text-warning fw-bold" href="<?= BASE_URL ?>/admin/dashboard.php">
                        <i class="bi bi-shield-lock-fill me-1"></i> অ্যাডমিন প্যানেল
                    </a>
                </li>
                <?php endif; ?>
            </ul>

            <!-- Right side / Auth buttons -->
            <div class="d-flex align-items-center gap-2">
                <a href="<?= BASE_URL ?>/blood_request.php" class="btn btn-blood btn-sm me-2 emergency-pulse">
                    <i class="bi bi-exclamation-octagon-fill me-1"></i> জরুরি রক্ত চাই
                </a>

                <?php if ($currentUser): ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-info dropdown-toggle rounded-pill px-3 py-1 btn-sm" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($currentUser['name']) ?>
                            <?php if ($currentUser['user_type'] === 'mariner'): ?>
                                <span class="badge bg-primary ms-1">মেরিনার</span>
                            <?php endif; ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark glass-card border-secondary">
                            <li><a class="dropdown-item text-white" href="<?= BASE_URL ?>/donor_dashboard.php"><i class="bi bi-speedometer2 me-2 text-info"></i>ড্যাশবোর্ড</a></li>
                            <?php if ($isAdmin): ?>
                            <li><a class="dropdown-item text-warning" href="<?= BASE_URL ?>/admin/dashboard.php"><i class="bi bi-shield-lock me-2"></i>অ্যাডমিন ড্যাশবোর্ড</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider border-secondary"></li>
                            <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/logout.php"><i class="bi bi-box-arrow-right me-2"></i>লগআউট</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login.php" class="btn btn-outline-light btn-sm rounded-pill px-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i> লগইন
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
