<?php
$pageTitle = 'লগইন — BMMC';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/functions.php';

$otpSent = isset($_GET['otp_sent']);
$identifier = sanitize($_GET['identifier'] ?? '');
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="glass-card p-4 p-md-5">
                <div class="text-center mb-4">
                    <div style="width: 54px; height: 54px; margin: 0 auto 12px; background: rgba(0, 210, 255, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid #00d2ff;">
                        <i class="bi bi-person-lock text-info fs-3"></i>
                    </div>
                    <h3 class="fw-bold text-white mb-1">লগইন করুন</h3>
                    <p class="text-secondary small">BMMC ব্লাড ডোনার ও মেম্বার পোর্টাল</p>
                </div>

                <?php if ($otpSent): ?>
                    <!-- OTP Verification Form -->
                    <form action="<?= BASE_URL ?>/controllers/auth_controller.php?action=verify_otp" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="identifier" value="<?= htmlspecialchars($identifier) ?>">
                        
                        <div class="mb-3">
                            <label class="form-label text-light">ওটিপি (OTP) কোড দিন <span class="text-danger">*</span></label>
                            <input type="text" class="form-control glass-input text-center fs-4 letter-spacing" name="otp" placeholder="XXXXXX" maxlength="6" required autofocus>
                            <small class="text-secondary d-block mt-1">প্রদত্ত নম্বর/ইমেইল: <?= htmlspecialchars($identifier) ?></small>
                        </div>

                        <button type="submit" class="btn btn-ocean w-100 rounded-pill py-2 mb-3">
                            <i class="bi bi-shield-check me-1"></i> ওটিপি যাচাই করে লগইন
                        </button>

                        <div class="text-center">
                            <a href="<?= BASE_URL ?>/login.php" class="text-secondary small text-decoration-none">← অন্যভাবে লগইন করুন</a>
                        </div>
                    </form>
                <?php else: ?>
                    <!-- Standard Password Login Form -->
                    <form action="<?= BASE_URL ?>/controllers/auth_controller.php?action=login" method="POST">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label text-light">ইমেইল অথবা মোবাইল নম্বর <span class="text-danger">*</span></label>
                            <input type="text" class="form-control glass-input" name="identifier" placeholder="ইমেইল বা ০১XXXXXXXXX" required>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label class="form-label text-light">পাসওয়ার্ড <span class="text-danger">*</span></label>
                            </div>
                            <input type="password" class="form-control glass-input" name="password" placeholder="আপনার পাসওয়ার্ড" required>
                        </div>

                        <button type="submit" class="btn btn-ocean w-100 rounded-pill py-2 mb-3 fw-bold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> লগইন করুন
                        </button>
                    </form>

                    <div class="position-relative my-4 text-center">
                        <hr class="border-secondary border-opacity-50">
                        <span class="position-absolute top-50 start-50 translate-middle px-3 text-secondary small" style="background: #0b2545;">অথবা</span>
                    </div>

                    <!-- OTP Request Form -->
                    <form action="<?= BASE_URL ?>/controllers/auth_controller.php?action=send_otp" method="POST">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label text-secondary small">পাসওয়ার্ড মনে নেই? ওটিপি (OTP) দিয়ে লগইন করুন</label>
                            <div class="input-group">
                                <input type="text" class="form-control glass-input" name="identifier" placeholder="ইমেইল বা মোবাইল নম্বর" required>
                                <button type="submit" class="btn btn-outline-info">কোড পাঠান</button>
                            </div>
                        </div>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top border-secondary border-opacity-25">
                        <span class="text-secondary small">নতুন রক্তদাতা?</span>
                        <a href="<?= BASE_URL ?>/donor_register.php" class="text-info small fw-bold text-decoration-none ms-1">
                            এখানে নিবন্ধন করুন →
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
