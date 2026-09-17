<?php
/**
 * Bangladesh Merchant Mariners Community (BMMC)
 * Volunteer Registration with Integrated Blood Donation Opt-in
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/districts.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/services_data.php';

$pageTitle = __('vol_page_title');
$ogTitle = __('vol_page_title');
$ogDescription = __('vol_hero_subtitle');

require_once __DIR__ . '/includes/header.php';

$marinerRanks = getMarinerRanks();
$currentUser = current_user();
$bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
$preselectedWing = $_GET['wing'] ?? '';

$portCities = is_english() ? [
    'Chattogram Port & Patenga Area',
    'Chattogram City & Agrabad',
    'Dhaka Division & Metros',
    'Mongla Port & Khulna',
    'Payra Port & Barishal',
    'Sylhet Division',
    'Rajshahi & North Bengal',
    'Stationed Abroad / At Sea on Board',
    'Other Locations / Districts'
] : [
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
                        <i class="bi bi-arrow-left me-1"></i> <?= __('breadcrumb_home') ?>
                    </a>
                </div>

                <!-- Header -->
                <div class="text-center mb-4">
                    <div style="width: 70px; height: 70px; margin: 0 auto 16px; background: linear-gradient(135deg, rgba(0, 210, 255, 0.2), rgba(2, 132, 199, 0.3)); border-radius: 20px; display: flex; align-items: center; justify-content: center; border: 2px solid #00d2ff; box-shadow: 0 0 25px rgba(0, 210, 255, 0.35);">
                        <i class="bi bi-people-fill text-info fs-1"></i>
                    </div>
                    <h2 class="fw-bold text-white mb-2"><?= __('vol_hero_title') ?></h2>
                    <p class="text-secondary small mb-3" style="max-width: 600px; margin: 0 auto;">
                        <?= __('vol_hero_subtitle') ?>
                    </p>
                    <div class="service-dev-badge mb-3">
                        <i class="bi bi-heart-fill"></i>
                        <span><?= __('vol_badge_selfless') ?></span>
                    </div>
                </div>

                <form action="<?= BASE_URL ?>/controllers/auth_controller.php?action=volunteer_register" method="POST" id="volunteerForm">
                    <?= csrf_field() ?>

                    <!-- Identity Section -->
                    <div class="p-4 mb-4 rounded-3 border border-info border-opacity-30" style="background: rgba(0, 210, 255, 0.05);">
                        <label class="form-label d-block text-info fw-bold mb-3">
                            <i class="bi bi-person-badge me-2"></i> <?= __('vol_sec1_title') ?> <span class="text-danger">*</span>
                        </label>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="form-check p-3 rounded-3 border border-secondary border-opacity-30 h-100" style="background: rgba(255, 255, 255, 0.02);">
                                    <input class="form-check-input ms-0 me-2" type="radio" name="user_type" id="v_type_mariner" value="mariner" checked onchange="toggleMarinerFields(true)">
                                    <label class="form-check-label text-white fw-semibold" for="v_type_mariner">
                                        ⚓ <?= __('vol_type_mariner') ?>
                                        <small class="text-secondary d-block mt-1"><?= __('vol_type_mariner_sub') ?></small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check p-3 rounded-3 border border-secondary border-opacity-30 h-100" style="background: rgba(255, 255, 255, 0.02);">
                                    <input class="form-check-input ms-0 me-2" type="radio" name="user_type" id="v_type_general" value="general" onchange="toggleMarinerFields(false)">
                                    <label class="form-check-label text-white fw-semibold" for="v_type_general">
                                        👥 <?= __('vol_type_general') ?>
                                        <small class="text-secondary d-block mt-1"><?= __('vol_type_general_sub') ?></small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mariner Specifics (CDC / Rank) -->
                    <div id="mariner-fields" class="p-4 mb-4 rounded-3 border border-primary border-opacity-30" style="background: rgba(30, 58, 138, 0.25);">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="cdc_sid_no" class="form-label text-light"><?= __('vol_cdc_sid_label') ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control glass-input" id="cdc_sid_no" name="cdc_sid_no" placeholder="<?= __('vol_cdc_sid_placeholder') ?>" value="<?= htmlspecialchars($currentUser['cdc_sid'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="mariner_rank" class="form-label text-light"><?= __('vol_rank_label') ?></label>
                                <select class="form-select glass-input" id="mariner_rank" name="mariner_rank">
                                    <option value=""><?= __('vol_rank_select') ?></option>
                                    <?php foreach ($marinerRanks as $title => $val): ?>
                                        <option value="<?= htmlspecialchars($val) ?>"><?= htmlspecialchars(is_english() ? $val : $title) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Personal & Contact Information -->
                    <div class="p-4 mb-4 rounded-3 border border-secondary border-opacity-25" style="background: rgba(255, 255, 255, 0.02);">
                        <label class="form-label d-block text-white fw-bold mb-3">
                            <i class="bi bi-card-heading me-2 text-info"></i> <?= __('vol_sec2_title') ?>
                        </label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label text-light"><?= __('vol_name_label') ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control glass-input" id="name" name="name" value="<?= htmlspecialchars($currentUser['name'] ?? '') ?>" placeholder="<?= __('vol_name_placeholder') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label text-light"><?= __('vol_phone_label') ?> <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control glass-input" id="phone" name="phone" value="<?= htmlspecialchars($currentUser['phone'] ?? '') ?>" placeholder="<?= __('vol_phone_placeholder') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label text-light"><?= __('vol_email_label') ?> <span class="text-danger">*</span></label>
                                <input type="email" class="form-control glass-input" id="email" name="email" value="<?= htmlspecialchars($currentUser['email'] ?? '') ?>" placeholder="<?= __('vol_email_placeholder') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="port_city" class="form-label text-light"><?= __('vol_location_label') ?> <span class="text-danger">*</span></label>
                                <select class="form-select glass-input" id="port_city" name="port_city" required>
                                    <option value=""><?= __('vol_location_select') ?></option>
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
                            <i class="bi bi-grid-3x3-gap-fill me-2 text-info"></i> <?= __('vol_sec3_title') ?>
                        </label>
                        <small class="text-secondary d-block mb-3"><?= __('vol_sec3_sub') ?></small>
                        
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="interest[]" value="Blood Donation Coordination" id="w_blood" checked>
                                    <label class="form-check-label text-light small" for="w_blood">
                                        <?= __('vol_wing_blood') ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="interest[]" value="Emergency Response Team" id="w_emergency" <?= ($preselectedWing === 'ইমার্জেন্সি রেসপন্স টিম' ? 'checked' : '') ?>>
                                    <label class="form-check-label text-light small" for="w_emergency">
                                        <?= __('vol_wing_emergency') ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="interest[]" value="Legal Support & ITF Info" id="w_legal" <?= ($preselectedWing === 'আইনি সহায়তা সেল' ? 'checked' : '') ?>>
                                    <label class="form-check-label text-light small" for="w_legal">
                                        <?= __('vol_wing_legal') ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="interest[]" value="Fresh Cadet Training" id="w_cadet" <?= ($preselectedWing === 'ফ্রেশ ক্যাডেট ট্রেইনিং প্রোগ্রাম' ? 'checked' : '') ?>>
                                    <label class="form-check-label text-light small" for="w_cadet">
                                        <?= __('vol_wing_cadet') ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="interest[]" value="Manning Agency Review & Anti-Fraud" id="w_agency" <?= ($preselectedWing === 'ম্যানিং এজেন্সি ও ট্রেনিং রিভিউ' ? 'checked' : '') ?>>
                                    <label class="form-check-label text-light small" for="w_agency">
                                        <?= __('vol_wing_agency') ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="interest[]" value="IT, Web & Digital Tools" id="w_it" <?= ($preselectedWing === 'ডিজিটাল লগবুক ও ডকুমেন্ট ট্র্যাকার' ? 'checked' : '') ?>>
                                    <label class="form-check-label text-light small" for="w_it">
                                        <?= __('vol_wing_it') ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="interest[]" value="Medical & Test Centre Liaison" id="w_medical" <?= ($preselectedWing === 'মেডিকেল ও টেস্ট সেন্টার ডিরেক্টরি' ? 'checked' : '') ?>>
                                    <label class="form-check-label text-light small" for="w_medical">
                                        <?= __('vol_wing_medical') ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="interest[]" value="Welfare & Loss of Life Fund" id="w_fund" <?= ($preselectedWing === 'জীবনহানি ও পেনশন সহায়তা তহবিল' ? 'checked' : '') ?>>
                                    <label class="form-check-label text-light small" for="w_fund">
                                        <?= __('vol_wing_welfare') ?>
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
                                    <?= __('vol_blood_optin_label') ?>
                                </label>
                                <p class="text-light opacity-75 small mb-3">
                                    <?= __('vol_blood_optin_desc') ?>
                                </p>

                                <!-- Collapsible Blood Group & Donation Fields -->
                                <div id="blood-donor-fields" class="p-3 rounded-3 border border-danger border-opacity-25" style="background: rgba(0, 0, 0, 0.3);">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="blood_group" class="form-label text-white fw-semibold small">
                                                <?= __('vol_blood_group_label') ?> <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select glass-input border-danger border-opacity-50" id="blood_group" name="blood_group">
                                                <option value=""><?= __('vol_blood_group_select') ?></option>
                                                <?php foreach ($bloodGroups as $bg): ?>
                                                    <option value="<?= $bg ?>"><?= $bg ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="last_donation_date" class="form-label text-white fw-semibold small">
                                                <?= __('vol_last_donation_label') ?>
                                            </label>
                                            <input type="date" class="form-control glass-input" id="last_donation_date" name="last_donation_date" max="<?= date('Y-m-d') ?>">
                                        </div>
                                    </div>
                                    <div class="mt-2 text-info small">
                                        <i class="bi bi-shield-check me-1"></i> <?= __('vol_blood_resting_note') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Note / Message -->
                    <div class="mb-4">
                        <label for="message" class="form-label text-light"><?= __('vol_note_label') ?></label>
                        <textarea class="form-control glass-input" id="message" name="message" rows="3" placeholder="<?= __('vol_note_placeholder') ?>"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-ocean btn-lg w-100 rounded-pill py-3 fw-bold shadow-lg">
                        <i class="bi bi-check-circle-fill me-2"></i> <?= __('vol_btn_submit') ?>
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
