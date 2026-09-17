<?php
/**
 * Bangladesh Merchant Mariners Community (BMMC)
 * Volunteer Registration with Integrated Blood Donation Opt-in
 */
$pageTitle = 'BMMC ভলান্টিয়ার আবেদন ও যোগ দিন';
$ogTitle = 'Become a BMMC Volunteer — মানবতার সেবায় যোগ দিন';
$ogDescription = 'বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি (BMMC)-এর বিভিন্ন সেবা উইংয়ে ভলান্টিয়ার হিসেবে যোগ দিন এবং রক্তদান নেটওয়ার্কের অংশ হোন।';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/districts.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/services_data.php';

$marinerRanks = getMarinerRanks();
$currentUser = current_user();
$bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
$preselectedWing = $_GET['wing'] ?? '';

$portCities = [
    'চট্টগ্রাম বন্দর ও পতেঙ্গা এলাকা (Chattogram Port)',
    'চট্টগ্রাম মহানগর ও আগ্রাবাদ (Chattogram City)',
    'ঢাকা বিভাগ (Dhaka Division)',
    'মোংলা বন্দর ও খুলনা (Mongla Port & Khulna)',
    'পায়রা বন্দর ও বরিশাল (Payra Port & Barishal)',
    'সিলেট বিভাগ (Sylhet Division)',
    'রাজশাহী ও উত্তরবঙ্গ (Rajshahi & North Bengal)',
    'বিদেশে কর্মরত / সমুদ্রে জাহাজে (Abroad / At Sea)',
    'অন্যান্য জেলা (Other Locations)'
];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
            <div class="glass-card p-4 p-md-5 position-relative">
                
                <!-- Back Navigation -->
                <div class="mb-3">
                    <a href="<?= BASE_URL ?>/index.php" class="btn btn-outline-light btn-sm rounded-pill px-3 py-1">
                        <i class="bi bi-arrow-left me-1"></i> হোমে ফিরুন
                    </a>
                </div>

                <!-- Header -->
                <div class="text-center mb-4">
                    <div style="width: 70px; height: 70px; margin: 0 auto 16px; background: linear-gradient(135deg, rgba(0, 210, 255, 0.2), rgba(2, 132, 199, 0.3)); border-radius: 20px; display: flex; align-items: center; justify-content: center; border: 2px solid #00d2ff; box-shadow: 0 0 25px rgba(0, 210, 255, 0.35);">
                        <i class="bi bi-people-fill text-info fs-1"></i>
                    </div>
                    <h2 class="fw-bold text-white mb-2">BMMC ভলান্টিয়ার টিমে যুক্ত হোন</h2>
                    <p class="text-secondary small mb-3" style="max-width: 600px; margin: 0 auto;">
                        মেরিন কমিউনিটির অধিকার রক্ষা, জরুরি উদ্ধার, ক্যাডেটদের ক্যারিয়ার গাইডেন্স ও দেশব্যাপী রক্তদানের যৌথ নেটওয়ার্ক।
                    </p>
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-warning bg-opacity-10 border border-warning border-opacity-30 text-warning small">
                        <i class="bi bi-heart-fill"></i>
                        <span>দুনিয়াবি কোনো বিনিময় বা বেতনাদি নেই — পুরোটাই শতভাগ স্বেচ্ছাসেবা!</span>
                    </div>
                </div>

                <form action="<?= BASE_URL ?>/controllers/auth_controller.php?action=volunteer_register" method="POST" id="volunteerForm">
                    <?= csrf_field() ?>

                    <!-- Identity Section -->
                    <div class="p-4 mb-4 rounded-3 border border-info border-opacity-30" style="background: rgba(0, 210, 255, 0.05);">
                        <label class="form-label d-block text-info fw-bold mb-3">
                            <i class="bi bi-person-badge me-2"></i> ১. আপনার প্রাথমিক পরিচয় <span class="text-danger">*</span>
                        </label>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="form-check p-3 rounded-3 border border-secondary border-opacity-30 h-100" style="background: rgba(255, 255, 255, 0.02);">
                                    <input class="form-check-input ms-0 me-2" type="radio" name="user_type" id="v_type_mariner" value="mariner" checked onchange="toggleMarinerFields(true)">
                                    <label class="form-check-label text-white fw-semibold" for="v_type_mariner">
                                        ⚓ মার্চেন্ট মেরিনার (Merchant Mariner)
                                        <small class="text-secondary d-block mt-1">সিডিসি বা এসআইডি ধারী নাবিক</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check p-3 rounded-3 border border-secondary border-opacity-30 h-100" style="background: rgba(255, 255, 255, 0.02);">
                                    <input class="form-check-input ms-0 me-2" type="radio" name="user_type" id="v_type_general" value="general" onchange="toggleMarinerFields(false)">
                                    <label class="form-check-label text-white fw-semibold" for="v_type_general">
                                        👥 সাধারণ নাগরিক / শুভানুধ্যায়ী
                                        <small class="text-secondary d-block mt-1">মেরিন ও মানবসেবায় আগ্রহী ভলান্টিয়ার</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mariner Specifics (CDC / Rank) -->
                    <div id="mariner-fields" class="p-4 mb-4 rounded-3 border border-primary border-opacity-30" style="background: rgba(30, 58, 138, 0.25);">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="cdc_sid_no" class="form-label text-light">CDC / SID নম্বর <span class="text-danger">*</span></label>
                                <input type="text" class="form-control glass-input" id="cdc_sid_no" name="cdc_sid_no" placeholder="যেমন: C/O/12345 বা SID No." value="<?= htmlspecialchars($currentUser['cdc_sid'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="mariner_rank" class="form-label text-light">র‍্যাংক / পদবী</label>
                                <select class="form-select glass-input" id="mariner_rank" name="mariner_rank">
                                    <option value="">-- পদবী নির্বাচন করুন --</option>
                                    <?php foreach ($marinerRanks as $title => $val): ?>
                                        <option value="<?= htmlspecialchars($val) ?>"><?= htmlspecialchars($title) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Personal & Contact Information -->
                    <div class="p-4 mb-4 rounded-3 border border-secondary border-opacity-25" style="background: rgba(255, 255, 255, 0.02);">
                        <label class="form-label d-block text-white fw-bold mb-3">
                            <i class="bi bi-card-heading me-2 text-info"></i> ২. যোগাযোগের তথ্য
                        </label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label text-light">পূর্ণ নাম <span class="text-danger">*</span></label>
                                <input type="text" class="form-control glass-input" id="name" name="name" value="<?= htmlspecialchars($currentUser['name'] ?? '') ?>" placeholder="আপনার পূর্ণ নাম লিখুন" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label text-light">সচল মোবাইল নম্বর <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control glass-input" id="phone" name="phone" value="<?= htmlspecialchars($currentUser['phone'] ?? '') ?>" placeholder="017XXXXXXXX" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label text-light">ইমেইল ঠিকানা <span class="text-danger">*</span></label>
                                <input type="email" class="form-control glass-input" id="email" name="email" value="<?= htmlspecialchars($currentUser['email'] ?? '') ?>" placeholder="name@example.com" required>
                            </div>
                            <div class="col-md-6">
                                <label for="port_city" class="form-label text-light">আপনার অবস্থান / বন্দর এলাকা <span class="text-danger">*</span></label>
                                <select class="form-select glass-input" id="port_city" name="port_city" required>
                                    <option value="">-- অবস্থান নির্বাচন করুন --</option>
                                    <?php foreach ($portCities as $pCity): ?>
                                        <option value="<?= htmlspecialchars($pCity) ?>"><?= htmlspecialchars($pCity) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Preferred Service Wings -->
                    <div class="p-4 mb-4 rounded-3 border border-secondary border-opacity-25" style="background: rgba(255, 255, 255, 0.02);">
                        <label class="form-label d-block text-white fw-bold mb-2">
                            <i class="bi bi-grid-3x3-gap-fill me-2 text-info"></i> ৩. কোন কোন সেবায় ভূমিকা রাখতে চান?
                        </label>
                        <small class="text-secondary d-block mb-3">একাধিক ক্ষেত্রে টিক দিতে পারেন:</small>
                        
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="interest[]" value="Blood Donation Coordination" id="w_blood" checked>
                                    <label class="form-check-label text-light small" for="w_blood">
                                        🩸 রক্তদান কার্যক্রম সমন্বয় (Blood Coordination)
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="interest[]" value="Emergency Response Team" id="w_emergency" <?= ($preselectedWing === 'ইমার্জেন্সি রেসপন্স টিম' ? 'checked' : '') ?>>
                                    <label class="form-check-label text-light small" for="w_emergency">
                                        🆘 জরুরি সাড়া ও উদ্ধার টিম (Emergency Response)
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="interest[]" value="Legal Support & ITF Info" id="w_legal" <?= ($preselectedWing === 'আইনি সহায়তা সেল' ? 'checked' : '') ?>>
                                    <label class="form-check-label text-light small" for="w_legal">
                                        ⚖️ আইনি সহায়তা ও চুক্তিপত্র যাচাই (Legal Cell)
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="interest[]" value="Fresh Cadet Training" id="w_cadet" <?= ($preselectedWing === 'ফ্রেশ ক্যাডেট ট্রেইনিং প্রোগ্রাম' ? 'checked' : '') ?>>
                                    <label class="form-check-label text-light small" for="w_cadet">
                                        🎓 ফ্রেশ ক্যাডেট মেন্টরিং ও ট্রেইনিং গাইড
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="interest[]" value="Manning Agency Review & Anti-Fraud" id="w_agency" <?= ($preselectedWing === 'ম্যানিং এজেন্সি ও ট্রেনিং রিভিউ' ? 'checked' : '') ?>>
                                    <label class="form-check-label text-light small" for="w_agency">
                                        🏢 এজেন্সি রিভিউ ও স্ক্যাম প্রতিরোধ (Anti-Fraud)
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="interest[]" value="IT, Web & Digital Tools" id="w_it" <?= ($preselectedWing === 'ডিজিটাল লগবুক ও ডকুমেন্ট ট্র্যাকার' ? 'checked' : '') ?>>
                                    <label class="form-check-label text-light small" for="w_it">
                                        💻 আইটি, ওয়েব ও ডিজিটাল ইউটিলিটি ডেভেলপমেন্ট
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="interest[]" value="Medical & Test Centre Liaison" id="w_medical" <?= ($preselectedWing === 'মেডিকেল ও টেস্ট সেন্টার ডিরেক্টরি' ? 'checked' : '') ?>>
                                    <label class="form-check-label text-light small" for="w_medical">
                                        🏥 মেডিকেল ও টেস্ট সেন্টার যোগাযোগ
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="interest[]" value="Welfare & Loss of Life Fund" id="w_fund" <?= ($preselectedWing === 'জীবনহানি ও পেনশন সহায়তা তহবিল' ? 'checked' : '') ?>>
                                    <label class="form-check-label text-light small" for="w_fund">
                                        🕊️ মৃত সহকর্মীর পরিবার ও ওয়েলফেয়ার সহায়তা
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==============================================================
                         SPECIAL BLOOD DONATION OPT-IN CHECKLIST (USER REQUIREMENT)
                    =============================================================== -->
                    <div class="p-4 mb-4 rounded-3 border border-danger border-opacity-40" style="background: linear-gradient(135deg, rgba(239, 35, 60, 0.08) 0%, rgba(6, 18, 36, 0.8) 100%);">
                        <div class="d-flex align-items-start gap-3">
                            <div class="form-check form-switch pt-1">
                                <input class="form-check-input fs-4" type="checkbox" role="switch" id="agree_blood_donation" name="agree_blood_donation" value="1" checked onchange="toggleBloodFields(this.checked)">
                            </div>
                            <div class="flex-grow-1">
                                <label class="form-check-label text-white fw-bold fs-6 d-block mb-1" for="agree_blood_donation">
                                    ❤️ Agree to Donate Blood (রক্তদানে সম্মতি)
                                </label>
                                <p class="text-light opacity-75 small mb-3">
                                    আপনি কি রক্তদানে আগ্রহী? এই অপশনটি চালু রাখলে আপনার অ্যাকাউন্টটি স্বয়ংক্রিয়ভাবে <strong>BMMC ব্লাড ডোনার নেটওয়ার্কেও</strong> নিবন্ধিত হয়ে যাবে। আপনার এলাকায় কোনো মুমূর্ষু রোগীর জরুরি রক্তের প্রয়োজন হলে আপনি নোটিফিকেশন পাবেন।
                                </p>

                                <!-- Collapsible Blood Group & Donation Fields -->
                                <div id="blood-donor-fields" class="p-3 rounded-3 border border-danger border-opacity-25" style="background: rgba(0, 0, 0, 0.3);">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="blood_group" class="form-label text-white fw-semibold small">
                                                আপনার রক্তের গ্রুপ <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select glass-input border-danger border-opacity-50" id="blood_group" name="blood_group">
                                                <option value="">-- রক্তের গ্রুপ নির্বাচন করুন --</option>
                                                <?php foreach ($bloodGroups as $bg): ?>
                                                    <option value="<?= $bg ?>"><?= $bg ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="last_donation_date" class="form-label text-white fw-semibold small">
                                                সর্বশেষ রক্তদানের আনুমানিক তারিখ (যদি থাকে)
                                            </label>
                                            <input type="date" class="form-control glass-input" id="last_donation_date" name="last_donation_date" max="<?= date('Y-m-d') ?>">
                                        </div>
                                    </div>
                                    <div class="mt-2 text-info small">
                                        <i class="bi bi-shield-check me-1"></i> BMMC চিকিৎসাবিজ্ঞানসম্মত ৪ মাসের রেস্টিং প্রোটোকল মেনে চলে। রক্তদানের পর ৪ মাস আপনার কাছে কোনো নতুন কল যাবে না।
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Note / Message -->
                    <div class="mb-4">
                        <label for="message" class="form-label text-light">আপনার কোনো বিশেষ দক্ষতা বা পূর্ব অভিজ্ঞতা (ঐচ্ছিক)</label>
                        <textarea class="form-control glass-input" id="message" name="message" rows="3" placeholder="অতীতে কোনো সমাজসেবা, চিকিৎসাসেবা বা মেরিটাইম ওয়েলফেয়ারে কাজের অভিজ্ঞতা থাকলে সংক্ষেপে লিখুন..."></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-ocean btn-lg w-100 rounded-pill py-3 fw-bold shadow-lg">
                        <i class="bi bi-check-circle-fill me-2"></i> BMMC ভলান্টিয়ার আবেদন জমা দিন
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleMarinerFields(isMariner) {
    const fields = document.getElementById('mariner-fields');
    const cdcInput = document.getElementById('cdc_sid_no');
    if (fields) {
        fields.style.display = isMariner ? 'block' : 'none';
        if (cdcInput) {
            cdcInput.required = isMariner;
        }
    }
}

function toggleBloodFields(isAgreed) {
    const fields = document.getElementById('blood-donor-fields');
    const bgSelect = document.getElementById('blood_group');
    if (fields) {
        fields.style.display = isAgreed ? 'block' : 'none';
        if (bgSelect) {
            bgSelect.required = isAgreed;
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    const isMariner = document.getElementById('v_type_mariner')?.checked ?? true;
    toggleMarinerFields(isMariner);

    const isBloodAgreed = document.getElementById('agree_blood_donation')?.checked ?? true;
    toggleBloodFields(isBloodAgreed);
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
