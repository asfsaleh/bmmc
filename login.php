<?php
$pageTitle = 'লগইন — BMMC';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/functions.php';

$otpSent = isset($_GET['otp_sent']);
$identifier = sanitize($_GET['identifier'] ?? '');
?>

<div class="container py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-12" style="max-width: 460px;">
            <div class="glass-card p-4 p-sm-5 shadow-lg border border-secondary border-opacity-25" style="border-radius: 20px;">
                
                <!-- Top Tabs (Pill Switcher like Screenshot) -->
                <div class="d-flex p-1 mb-4 rounded-pill mx-auto" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15);">
                    <a href="<?= BASE_URL ?>/login.php" class="btn btn-sm w-50 rounded-pill fw-bold text-decoration-none shadow-sm" style="background: #ffffff; color: #0b2545;">
                        🔑 লগইন
                    </a>
                    <a href="<?= BASE_URL ?>/donor_register.php" class="btn btn-sm w-50 rounded-pill text-white text-decoration-none" style="opacity: 0.85;">
                        ✨ রেজিস্ট্রেশন
                    </a>
                </div>

                <!-- Heading & Subtitle -->
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-white mb-2" style="letter-spacing: -0.5px;">স্বাগতম আবার!</h3>
                    <p class="text-secondary small mb-0">আপনার ইমেইল ও পাসওয়ার্ড দিয়ে একাউন্টে প্রবেশ করুন।</p>
                </div>

                <!-- Demo Account Quick Fill Box (Exact Match to Screenshot) -->
                <div class="p-3 mb-4 rounded-3" style="background: rgba(255, 255, 255, 0.05); border: 1px dashed rgba(255, 255, 255, 0.22); border-radius: 12px;">
                    <div class="small text-secondary mb-2 d-flex align-items-center">
                        <i class="bi bi-lightning-charge-fill text-warning me-1"></i> ডেমো একাউন্ট কুইক লগইন (ক্লিক করুন):
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="demo-role-btn" onclick="fillCredentials('admin@bmmc.org', 'admin123')">
                            <span>🛡️</span> Admin
                        </button>
                        <button type="button" class="demo-role-btn" onclick="fillCredentials('mahfuz.marine@gmail.com', 'donor123')">
                            <span>⚓</span> মেরিনার ডোনার
                        </button>
                        <button type="button" class="demo-role-btn" onclick="fillCredentials('rashed.marine@gmail.com', 'donor123')">
                            <span>⏳</span> বিশ্রামে আছেন
                        </button>
                        <button type="button" class="demo-role-btn" onclick="fillCredentials('najmul.huda@gmail.com', 'donor123')">
                            <span>👥</span> সাধারণ ডোনার
                        </button>
                    </div>
                </div>

                <?php if ($otpSent): ?>
                    <!-- OTP Verification Form -->
                    <form action="<?= BASE_URL ?>/controllers/auth_controller.php?action=verify_otp" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="identifier" value="<?= htmlspecialchars($identifier) ?>">
                        
                        <div class="mb-3">
                            <label class="form-label text-light small fw-semibold">ওটিপি (OTP) কোড দিন <span class="text-danger">*</span></label>
                            <input type="text" class="form-control glass-input text-center fs-4 letter-spacing" name="otp" placeholder="XXXXXX" maxlength="6" required autofocus>
                            <small class="text-secondary d-block mt-1">প্রদত্ত নম্বর/ইমেইল: <?= htmlspecialchars($identifier) ?></small>
                        </div>

                        <button type="submit" class="btn btn-ocean w-100 rounded-pill py-2 mb-3">
                            <i class="bi bi-shield-check me-1"></i> ওটিপি যাচাই করে লগইন
                        </button>

                        <div class="text-center">
                            <a href="<?= BASE_URL ?>/login.php" class="text-secondary small text-decoration-none">← পাসওয়ার্ড দিয়ে লগইন করুন</a>
                        </div>
                    </form>
                <?php else: ?>
                    <!-- Standard Password Login Form -->
                    <form action="<?= BASE_URL ?>/controllers/auth_controller.php?action=login" method="POST">
                        <?= csrf_field() ?>

                        <!-- Email Address Input -->
                        <div class="mb-3">
                            <label class="form-label text-light small fw-semibold">ইমেইল ঠিকানা</label>
                            <input type="text" class="form-control glass-input" id="loginIdentifier" name="identifier" placeholder="name@example.com" required value="<?= htmlspecialchars($identifier) ?>">
                        </div>

                        <!-- Password Input with Forgot Password link -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label text-light small fw-semibold mb-0">পাসওয়ার্ড</label>
                                <a href="#" onclick="toggleOtpMode(); return false;" class="text-secondary small text-decoration-none" style="font-size: 0.8rem;">পাসওয়ার্ড ভুলে গেছেন?</a>
                            </div>
                            <input type="password" class="form-control glass-input" id="loginPassword" name="password" placeholder="••••••••" required>
                        </div>

                        <!-- Remember Me Checkbox -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="rememberMe" name="remember" checked>
                            <label class="form-check-label text-secondary small" for="rememberMe">
                                আমাকে মনে রাখুন
                            </label>
                        </div>

                        <!-- Big Blue Login Button -->
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm" style="background: #2563eb; border-color: #2563eb; border-radius: 12px; font-size: 1rem;">
                            লগইন
                        </button>
                    </form>

                    <!-- Hidden OTP Request Section (Reveals if "Forgot Password" is clicked) -->
                    <div id="otpSection" class="mt-4 pt-3 border-top border-secondary border-opacity-25" style="display: none;">
                        <form action="<?= BASE_URL ?>/controllers/auth_controller.php?action=send_otp" method="POST">
                            <?= csrf_field() ?>
                            <label class="form-label text-secondary small">পাসওয়ার্ড মনে নেই? ওটিপি দিয়ে তাৎক্ষণিক লগইন করুন</label>
                            <div class="input-group">
                                <input type="text" class="form-control glass-input" name="identifier" placeholder="ইমেইল বা মোবাইল নম্বর" required>
                                <button type="submit" class="btn btn-outline-info">কোড পাঠান</button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<script>
function fillCredentials(email, password) {
    const emailField = document.getElementById('loginIdentifier');
    const passField = document.getElementById('loginPassword');
    if (emailField && passField) {
        emailField.value = email;
        passField.value = password;

        // Visual feedback highlight
        emailField.style.borderColor = '#00d2ff';
        emailField.style.boxShadow = '0 0 14px rgba(0, 210, 255, 0.4)';
        passField.style.borderColor = '#00d2ff';
        passField.style.boxShadow = '0 0 14px rgba(0, 210, 255, 0.4)';

        setTimeout(() => {
            emailField.style.borderColor = '';
            emailField.style.boxShadow = '';
            passField.style.borderColor = '';
            passField.style.boxShadow = '';
        }, 1000);
    }
}

function toggleOtpMode() {
    const sec = document.getElementById('otpSection');
    if (sec) {
        sec.style.display = (sec.style.display === 'none' || sec.style.display === '') ? 'block' : 'none';
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>


