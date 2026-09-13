<?php
$pageTitle = 'লগইন — BMMC';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/functions.php';

$otpSent = isset($_GET['otp_sent']);
$identifier = sanitize($_GET['identifier'] ?? '');
?>

<div class="container py-4 py-lg-5">
    <!-- Header Title -->
    <div class="text-center mb-4">
        <h2 class="fw-bold text-white mb-1">
            <i class="bi bi-shield-lock-fill text-info me-2"></i>BMMC পোর্টাল লগইন
        </h2>
        <p class="text-secondary small">বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি ব্লাড ও মেম্বার পোর্টাল</p>
    </div>

    <div class="row g-4 justify-content-center">
        <!-- 1. Standard Login Form (Left Column) -->
        <div class="col-lg-5 col-md-10">
            <div class="glass-card p-4 p-md-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-secondary border-opacity-25">
                        <div style="width: 44px; height: 44px; background: rgba(0, 210, 255, 0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 1px solid #00d2ff;" class="me-3 flex-shrink-0">
                            <i class="bi bi-person-lock text-info fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-white mb-0 fs-5">ব্যবহারকারী লগইন</h4>
                            <small class="text-secondary">ইমেইল / মোবাইল ও পাসওয়ার্ড</small>
                        </div>
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
                                <a href="<?= BASE_URL ?>/login.php" class="text-secondary small text-decoration-none">← পাসওয়ার্ড দিয়ে লগইন করুন</a>
                            </div>
                        </form>
                    <?php else: ?>
                        <!-- Standard Password Login Form -->
                        <form action="<?= BASE_URL ?>/controllers/auth_controller.php?action=login" method="POST">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label class="form-label text-light">ইমেইল অথবা মোবাইল নম্বর <span class="text-danger">*</span></label>
                                <input type="text" class="form-control glass-input" id="loginIdentifier" name="identifier" placeholder="ইমেইল বা ০১XXXXXXXXX" required>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label text-light">পাসওয়ার্ড <span class="text-danger">*</span></label>
                                </div>
                                <input type="password" class="form-control glass-input" id="loginPassword" name="password" placeholder="আপনার পাসওয়ার্ড" required>
                            </div>

                            <button type="submit" class="btn btn-ocean w-100 rounded-pill py-2 mb-3 fw-bold">
                                <i class="bi bi-box-arrow-in-right me-1"></i> লগইন করুন
                            </button>
                        </form>

                        <div class="position-relative my-3 text-center">
                            <hr class="border-secondary border-opacity-50">
                            <span class="position-absolute top-50 start-50 translate-middle px-3 text-secondary small" style="background: #0b2545;">অথবা ওটিপি (OTP)</span>
                        </div>

                        <!-- OTP Request Form -->
                        <form action="<?= BASE_URL ?>/controllers/auth_controller.php?action=send_otp" method="POST">
                            <?= csrf_field() ?>
                            <div class="mb-2">
                                <label class="form-label text-secondary small">পাসওয়ার্ড মনে নেই? ওটিপি দিয়ে তাৎক্ষণিক লগইন করুন</label>
                                <div class="input-group">
                                    <input type="text" class="form-control glass-input" name="identifier" placeholder="ইমেইল বা মোবাইল নম্বর" required>
                                    <button type="submit" class="btn btn-outline-info">কোড পাঠান</button>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>

                <div class="text-center mt-3 pt-3 border-top border-secondary border-opacity-25">
                    <span class="text-secondary small">নতুন রক্তদাতা?</span>
                    <a href="<?= BASE_URL ?>/donor_register.php" class="text-info small fw-bold text-decoration-none ms-1">
                        এখানে নিবন্ধন করুন →
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. One-Click Test Login Panel (Right Column) -->
        <div class="col-lg-7 col-md-10">
            <div class="glass-card p-4 p-md-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom border-secondary border-opacity-25">
                    <div class="d-flex align-items-center">
                        <div style="width: 44px; height: 44px; background: rgba(255, 193, 7, 0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255, 193, 7, 0.5);" class="me-3 flex-shrink-0">
                            <i class="bi bi-lightning-charge-fill text-warning fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-white mb-0 fs-5">১-ক্লিক টেস্ট লগইন</h4>
                            <small class="text-secondary">সকল ভূমিকা (Roles) পরীক্ষা করার বিশেষ সুবিধা</small>
                        </div>
                    </div>
                    <span class="badge bg-warning text-dark px-2 py-1 small fw-bold">
                        <i class="bi bi-cpu me-1"></i>টেস্ট প্যানেল
                    </span>
                </div>

                <p class="text-light small mb-3">
                    নিচের যেকোনো ভূমিকায় এক ক্লিকে সরাসরি প্রবেশ করতে পারেন অথবা <span class="badge bg-secondary">অটো-ফিল</span> বাটন চেপে বামপাশের ফর্মে মান পূরণ করে পরীক্ষা করতে পারেন:
                </p>

                <div class="d-flex flex-column gap-3">
                    <!-- Role 1: Community Admin -->
                    <div class="test-role-card role-admin">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="role-avatar" style="background: rgba(239, 35, 60, 0.2); border: 1px solid rgba(239, 35, 60, 0.5);">
                                    <i class="bi bi-shield-shaded text-danger fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-white mb-0">ক্যাপ্টেন শফিকুর রহমান</h6>
                                    <small class="text-secondary">মাস্টার মেরিনার / ক্যাপ্টেন &bull; সিডিসি: C/O/08452</small>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-danger"><i class="bi bi-shield-lock me-1"></i>অ্যাডমিন</span>
                                <span class="badge bg-primary">মেরিনার</span>
                            </div>
                        </div>
                        <p class="text-secondary small mb-2">
                            সম্পূর্ণ অ্যাডমিন ড্যাশবোর্ড, রক্তদাতা ও রক্তের অনুরোধ পর্যবেক্ষণ, ভলান্টিয়ার তালিকা নিয়ন্ত্রণ।
                        </p>
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-2 border-top border-secondary border-opacity-25">
                            <code class="text-info small">admin@bmmc.org | admin123</code>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-1 small" onclick="fillLoginForm('admin@bmmc.org', 'admin123')">
                                    <i class="bi bi-input-cursor me-1"></i>অটো-ফিল
                                </button>
                                <form action="<?= BASE_URL ?>/controllers/auth_controller.php?action=test_login" method="POST" class="d-inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="role" value="admin">
                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 py-1 fw-bold shadow-sm">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>১-ক্লিকে অ্যাডমিন প্রবেশ ➔
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Role 2: Active Mariner Donor -->
                    <div class="test-role-card role-mariner-active">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="role-avatar" style="background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.5);">
                                    <i class="bi bi-droplet-fill text-danger fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-white mb-0">চিফ ইঞ্জিনিয়ার মাহফুজুর আলম</h6>
                                    <small class="text-secondary">চিফ ইঞ্জিনিয়ার &bull; সিডিসি: C/E/04112 &bull; চট্টগ্রাম</small>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>রক্তদানে প্রস্তুত</span>
                                <span class="badge bg-danger">A+ (পজিটিভ)</span>
                                <span class="badge bg-primary">মেরিনার</span>
                            </div>
                        </div>
                        <p class="text-secondary small mb-2">
                            অ্যাক্টিভ মেরিনার রক্তদাতা ড্যাশবোর্ড, জরুরি অনুরোধে সাড়া প্রদান, রক্তদানের ইতিহাস ও প্রোফাইল।
                        </p>
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-2 border-top border-secondary border-opacity-25">
                            <code class="text-info small">mahfuz.marine@gmail.com | donor123</code>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-1 small" onclick="fillLoginForm('mahfuz.marine@gmail.com', 'donor123')">
                                    <i class="bi bi-input-cursor me-1"></i>অটো-ফিল
                                </button>
                                <form action="<?= BASE_URL ?>/controllers/auth_controller.php?action=test_login" method="POST" class="d-inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="role" value="mariner_active">
                                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 py-1 fw-bold shadow-sm">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>১-ক্লিকে ডোনার প্রবেশ ➔
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Role 3: Resting Mariner Donor (Countdown Active) -->
                    <div class="test-role-card role-mariner-resting">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="role-avatar" style="background: rgba(245, 158, 11, 0.2); border: 1px solid rgba(245, 158, 11, 0.5);">
                                    <i class="bi bi-hourglass-split text-warning fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-white mb-0">ইঞ্জিনিয়ার রাশেদুল ইসলাম</h6>
                                    <small class="text-secondary">থার্ড ইঞ্জিনিয়ার &bull; সিডিসি: 3/E/12840 &bull; চট্টগ্রাম</small>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-warning text-dark"><i class="bi bi-clock-history me-1"></i>১২০ দিন কাউন্টডাউন</span>
                                <span class="badge bg-danger">O+ (পজিটিভ)</span>
                                <span class="badge bg-primary">মেরিনার</span>
                            </div>
                        </div>
                        <p class="text-secondary small mb-2">
                            রক্তদানের পর ৪ মাস (১২০ দিন) সুরক্ষা লক সক্রিয়। অবশিষ্ট বিশ্রাম দিন লাইভ দেখার টেস্ট প্রোফাইল।
                        </p>
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-2 border-top border-secondary border-opacity-25">
                            <code class="text-info small">rashed.marine@gmail.com | donor123</code>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-1 small" onclick="fillLoginForm('rashed.marine@gmail.com', 'donor123')">
                                    <i class="bi bi-input-cursor me-1"></i>অটো-ফিল
                                </button>
                                <form action="<?= BASE_URL ?>/controllers/auth_controller.php?action=test_login" method="POST" class="d-inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="role" value="mariner_resting">
                                    <button type="submit" class="btn btn-warning text-dark btn-sm rounded-pill px-3 py-1 fw-bold shadow-sm">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>১-ক্লিকে বিশ্রামরত প্রবেশ ➔
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Role 4: General Citizen Donor -->
                    <div class="test-role-card role-general">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="role-avatar" style="background: rgba(0, 210, 255, 0.2); border: 1px solid rgba(0, 210, 255, 0.5);">
                                    <i class="bi bi-heart-pulse-fill text-info fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-white mb-0">নাজমুল হুদা</h6>
                                    <small class="text-secondary">সাধারণ নাগরিক &bull; খুলনা &bull; ৩ বার রক্তদান</small>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>রক্তদানে প্রস্তুত</span>
                                <span class="badge bg-danger">O- (নেগেটিভ)</span>
                                <span class="badge bg-secondary">সাধারণ নাগরিক</span>
                            </div>
                        </div>
                        <p class="text-secondary small mb-2">
                            নন-মেরিনার সাধারণ রক্তদাতা প্রোফাইল ও ডোনেশন হিস্ট্রি ট্র্যাকিং।
                        </p>
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-2 border-top border-secondary border-opacity-25">
                            <code class="text-info small">najmul.huda@gmail.com | donor123</code>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-1 small" onclick="fillLoginForm('najmul.huda@gmail.com', 'donor123')">
                                    <i class="bi bi-input-cursor me-1"></i>অটো-ফিল
                                </button>
                                <form action="<?= BASE_URL ?>/controllers/auth_controller.php?action=test_login" method="POST" class="d-inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="role" value="general_donor">
                                    <button type="submit" class="btn btn-outline-info btn-sm rounded-pill px-3 py-1 fw-bold shadow-sm">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>১-ক্লিকে সাধারণ ডোনার প্রবেশ ➔
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fillLoginForm(identifier, password) {
    const identEl = document.getElementById('loginIdentifier');
    const passEl = document.getElementById('loginPassword');
    if (identEl && passEl) {
        identEl.value = identifier;
        passEl.value = password;

        // Visual feedback highlight
        identEl.style.borderColor = '#00d2ff';
        identEl.style.boxShadow = '0 0 15px rgba(0, 210, 255, 0.5)';
        passEl.style.borderColor = '#00d2ff';
        passEl.style.boxShadow = '0 0 15px rgba(0, 210, 255, 0.5)';

        identEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        identEl.focus();

        setTimeout(() => {
            identEl.style.borderColor = '';
            identEl.style.boxShadow = '';
            passEl.style.borderColor = '';
            passEl.style.boxShadow = '';
        }, 1600);
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

