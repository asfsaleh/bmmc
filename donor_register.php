<?php
$pageTitle = 'রক্তদাতা নিবন্ধন — BMMC';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/districts.php';
require_once __DIR__ . '/includes/functions.php';

$districtsByDivision = getBangladeshDistricts();
$marinerRanks = getMarinerRanks();
$currentUser = current_user();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <div class="glass-card p-4 p-md-5 position-relative">
                <!-- Header Icon & Title -->
                <div class="text-center mb-4">
                    <div class="blood-pill mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="bi bi-droplet-fill"></i>
                    </div>
                    <h3 class="fw-bold text-white mb-2">রক্তদাতা হিসেবে নিবন্ধন করুন</h3>
                    <p class="text-secondary small">
                        আপনার এক ব্যাগ রক্ত বাঁচাতে পারে একটি মুমূর্ষু প্রাণ। দেশ-বিদেশের যেকোনো স্থানে জরুরি প্রয়োজনে পাশে দাঁড়ান।
                    </p>
                </div>

                <form action="<?= BASE_URL ?>/controllers/donor_controller.php?action=register" method="POST" id="donorRegForm">
                    <?= csrf_field() ?>

                    <!-- User Type Selection: Mariner vs General Citizen (Requested by User) -->
                    <div class="p-3 mb-4 rounded-3 border border-info border-opacity-30" style="background: rgba(0, 210, 255, 0.06);">
                        <label class="form-label d-block text-info fw-bold mb-2">
                            <i class="bi bi-person-badge me-1"></i> আপনার পরিচয় নির্বাচন করুন <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex flex-column flex-sm-row gap-3 gap-sm-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="user_type" id="type_mariner" value="mariner" checked>
                                <label class="form-check-label text-white fw-semibold" for="type_mariner">
                                    ⚓ মেরিনার (Merchant Mariner)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="user_type" id="type_general" value="general">
                                <label class="form-check-label text-white fw-semibold" for="type_general">
                                    👤 সাধারণ নাগরিক (General Citizen)
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Mariner Specific Fields (Shown only if Mariner is selected) -->
                    <div id="mariner-fields" class="p-3 mb-4 rounded-3 border border-primary border-opacity-30" style="background: rgba(30, 58, 138, 0.2);">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="cdc_sid_no" class="form-label text-light">
                                    <i class="bi bi-card-heading me-1 text-info"></i> CDC / SID নম্বর <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control glass-input" id="cdc_sid_no" name="cdc_sid_no" placeholder="যেমন: C/O/12345 বা SID No." required>
                                <small class="text-muted" style="font-size: 0.75rem;">আপনার সিডিসি বা এসআইডি নম্বর প্রদান করুন</small>
                            </div>
                            <div class="col-md-6">
                                <label for="mariner_rank" class="form-label text-light">
                                    <i class="bi bi-award me-1 text-info"></i> পদবী / র‍্যাংক (Rank)
                                </label>
                                <select class="form-select glass-input" id="mariner_rank" name="mariner_rank">
                                    <option value="">-- নির্বাচন করুন --</option>
                                    <?php foreach ($marinerRanks as $title => $val): ?>
                                        <option value="<?= htmlspecialchars($val) ?>"><?= htmlspecialchars($title) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">পূর্ণ নাম <span class="text-danger">*</span></label>
                            <input type="text" class="form-control glass-input" id="name" name="name" value="<?= htmlspecialchars($currentUser['name'] ?? '') ?>" placeholder="আপনার পূর্ণ নাম" required>
                        </div>
                        <div class="col-md-6">
                            <label for="blood_group" class="form-label">ব্লাড গ্রুপ <span class="text-danger">*</span></label>
                            <select class="form-select glass-input fw-bold text-danger" id="blood_group" name="blood_group" required>
                                <option value="">-- ব্লাড গ্রুপ বাছুন --</option>
                                <option value="A+">A+ (এ পজিটিভ)</option>
                                <option value="A-">A- (এ নেগেটিভ)</option>
                                <option value="B+">B+ (বি পজিটিভ)</option>
                                <option value="B-">B- (বি নেগেটিভ)</option>
                                <option value="O+">O+ (ও পজিটিভ)</option>
                                <option value="O-">O- (ও নেগেটিভ)</option>
                                <option value="AB+">AB+ (এবি পজিটিভ)</option>
                                <option value="AB-">AB- (এবি নেগেটিভ)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Contact Details -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">মোবাইল নম্বর <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control glass-input" id="phone" name="phone" value="<?= htmlspecialchars($currentUser['phone'] ?? '') ?>" placeholder="০১৭XXXXXXXX" required>
                            <small class="text-muted" style="font-size: 0.75rem;">জরুরি প্রয়োজনে এই নম্বরে যোগাযোগ করা হবে</small>
                        </div>
                        <div class="col-md-6">
                            <label for="whatsapp" class="form-label">হোয়াটসঅ্যাপ নম্বর</label>
                            <input type="tel" class="form-control glass-input" id="whatsapp" name="whatsapp" placeholder="হোয়াটসঅ্যাপ চালু নম্বর">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">ইমেইল ঠিকানা <span class="text-danger">*</span></label>
                        <input type="email" class="form-control glass-input" id="email" name="email" value="<?= htmlspecialchars($currentUser['email'] ?? '') ?>" placeholder="example@email.com" required>
                        <small class="text-muted" style="font-size: 0.75rem;">রক্তের অনুরোধের তাৎক্ষণিক নোটিফিকেশন এই ইমেইলে যাবে</small>
                    </div>

                    <!-- Location / District -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="district" class="form-label">বর্তমান জেলা <span class="text-danger">*</span></label>
                            <select class="form-select glass-input" id="district" name="district" required>
                                <option value="">-- জেলা নির্বাচন করুন --</option>
                                <?php foreach ($districtsByDivision as $div => $districts): ?>
                                    <optgroup label="<?= htmlspecialchars($div) ?>">
                                        <?php foreach ($districts as $key => $name): ?>
                                            <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($name) ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="area" class="form-label">এলাকা / থানা / উপজেলা <span class="text-danger">*</span></label>
                            <input type="text" class="form-control glass-input" id="area" name="area" placeholder="যেমন: জিইসি মোড় / আগ্রাবাদ / ধানমন্ডি" required>
                        </div>
                    </div>

                    <!-- Last Donation Date -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="last_donation_date" class="form-label">সর্বশেষ রক্তদানের তারিখ (যদি ইতিপূর্বে দিয়ে থাকেন)</label>
                            <input type="date" class="form-control glass-input" id="last_donation_date" name="last_donation_date">
                            <small class="text-muted" style="font-size: 0.75rem;">৪ মাসের মধ্যে দিলে সেই অনুযায়ী বিশ্রাম সময় গণনা হবে</small>
                        </div>
                        <?php if (!$currentUser): ?>
                        <div class="col-md-6">
                            <label for="password" class="form-label">পাসওয়ার্ড নির্ধারণ করুন <span class="text-danger">*</span></label>
                            <input type="password" class="form-control glass-input" id="password" name="password" placeholder="কমপক্ষে ৬ অক্ষরের পাসওয়ার্ড" required minlength="6">
                            <small class="text-muted" style="font-size: 0.75rem;">পরবর্তীতে ড্যাশবোর্ডে লগইন করতে লাগবে</small>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Disclaimer & Submit -->
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="terms" required checked>
                        <label class="form-check-label text-secondary small" for="terms">
                            আমি স্বেচ্ছায় রক্তদানে সম্মত হচ্ছি এবং নিশ্চিত করছি যে আমি সম্পূর্ণ সুস্থ ও রক্তদানের উপযুক্ত।
                        </label>
                    </div>

                    <button type="submit" class="btn btn-blood btn-lg w-100 rounded-pill py-3 fw-bold">
                        <i class="bi bi-heart-fill me-2"></i> রক্তদাতা হিসেবে নিশ্চিত করুন
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
