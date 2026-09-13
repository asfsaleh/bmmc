<?php
$pageTitle = 'রক্তের জরুরি আবেদন — BMMC';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/districts.php';
require_once __DIR__ . '/includes/functions.php';

$districtsByDivision = getBangladeshDistricts();
$currentUser = current_user();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <div class="glass-blood-card p-4 p-md-5 position-relative">
                <!-- Header -->
                <div class="text-center mb-4">
                    <div class="blood-pill mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.6rem; background: linear-gradient(135deg, #ef233c, #800f2f);">
                        <i class="bi bi-broadcast"></i>
                    </div>
                    <h3 class="fw-bold text-white mb-2">জরুরি রক্তের জন্য আবেদন করুন</h3>
                    <p class="text-secondary small">
                        সঠিক তথ্য প্রদান করুন। আবেদন জমা হওয়া মাত্রই স্বয়ংক্রিয়ভাবে নিকটবর্তী রক্তদাতাদের কাছে বিজ্ঞপ্তি পৌঁছে যাবে।
                    </p>
                </div>

                <form action="<?= BASE_URL ?>/controllers/request_controller.php?action=create" method="POST" id="bloodReqForm">
                    <?= csrf_field() ?>

                    <!-- Patient Details -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="patient_name" class="form-label">রোগীর নাম <span class="text-danger">*</span></label>
                            <input type="text" class="form-control glass-input" id="patient_name" name="patient_name" placeholder="রোগীর পূর্ণ নাম" required>
                        </div>
                        <div class="col-md-6">
                            <label for="blood_group" class="form-label">প্রয়োজনীয় ব্লাড গ্রুপ <span class="text-danger">*</span></label>
                            <select class="form-select glass-input fw-bold text-danger" id="blood_group" name="blood_group" required>
                                <option value="">-- ব্লাড গ্রুপ নির্বাচন করুন --</option>
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

                    <!-- Bags Needed & Urgency -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="bags_needed" class="form-label">কত ব্যাগ রক্ত প্রয়োজন? <span class="text-danger">*</span></label>
                            <input type="number" class="form-control glass-input" id="bags_needed" name="bags_needed" value="1" min="1" max="10" required>
                        </div>
                        <div class="col-md-6">
                            <label for="needed_by" class="form-label">কখন / কোন তারিখে প্রয়োজন? <span class="text-danger">*</span></label>
                            <input type="date" class="form-control glass-input" id="needed_by" name="needed_by" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>

                    <!-- Urgency Selection -->
                    <div class="p-3 mb-4 rounded-3 border border-danger border-opacity-30" style="background: rgba(239, 35, 60, 0.1);">
                        <label class="form-label d-block text-white fw-bold mb-2">
                            <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i> জরুরিতার মাত্রা (Urgency Level) <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex flex-column flex-sm-row flex-wrap gap-2 gap-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="urgency" id="urg_emergency" value="emergency" checked>
                                <label class="form-check-label text-danger fw-bold" for="urg_emergency">
                                    🔴 অতি জরুরি (Emergency - আজই প্রয়োজন)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="urgency" id="urg_urgent" value="urgent">
                                <label class="form-check-label text-warning fw-semibold" for="urg_urgent">
                                    🟡 জরুরি (আগামী ২৪-৪৮ ঘণ্টায়)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="urgency" id="urg_normal" value="normal">
                                <label class="form-check-label text-info" for="urg_normal">
                                    🟢 সাধারণ (অপারেশনের পূর্ব প্রস্তুতি)
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Hospital & Location -->
                    <div class="mb-3">
                        <label for="hospital" class="form-label">হাসপাতালের নাম ও সম্পূর্ণ ঠিকানা <span class="text-danger">*</span></label>
                        <input type="text" class="form-control glass-input" id="hospital" name="hospital" placeholder="যেমন: ন্যাশনাল হার্ট ফাউন্ডেশন / চমেক হাসপাতাল, ওয়ার্ড নং ৫" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="district" class="form-label">জেলা <span class="text-danger">*</span></label>
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
                            <label for="area" class="form-label">এলাকা / থানা / নিকটবর্তী ল্যান্ডমার্ক <span class="text-danger">*</span></label>
                            <input type="text" class="form-control glass-input" id="area" name="area" placeholder="যেমন: পাঁচলাইশ / ধানমন্ডি / চকবাজার" required>
                        </div>
                    </div>

                    <!-- Contact Details -->
                    <div class="p-3 mb-4 rounded-3 border border-secondary border-opacity-30" style="background: rgba(255, 255, 255, 0.05);">
                        <h6 class="text-info fw-bold mb-3"><i class="bi bi-person-lines-fill me-1"></i>যোগাযোগকারীর তথ্য (রক্তদাতারা এই নম্বরে কল করবেন)</h6>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="contact_name" class="form-label">যোগাযোগকারীর নাম <span class="text-danger">*</span></label>
                                <input type="text" class="form-control glass-input" id="contact_name" name="contact_name" value="<?= htmlspecialchars($currentUser['name'] ?? '') ?>" placeholder="আপনার নাম" required>
                            </div>
                            <div class="col-md-6">
                                <label for="contact_phone" class="form-label">সচল মোবাইল নম্বর <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control glass-input" id="contact_phone" name="contact_phone" value="<?= htmlspecialchars($currentUser['phone'] ?? '') ?>" placeholder="০১৭XXXXXXXX" required>
                            </div>
                            <div class="col-md-12">
                                <label for="contact_email" class="form-label">ইমেইল ঠিকানা <span class="text-danger">*</span></label>
                                <input type="email" class="form-control glass-input" id="contact_email" name="contact_email" value="<?= htmlspecialchars($currentUser['email'] ?? '') ?>" placeholder="রক্তদাতা রাজি হলে তার নম্বর এই ইমেইলে যাবে" required>
                                <small class="text-muted" style="font-size: 0.75rem;">ডোনার রাজি হওয়ামাত্রই রক্তদাতার নাম ও মোবাইল নম্বর আপনার এই ইমেইলে পাঠিয়ে দেওয়া হবে।</small>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="mb-4">
                        <label for="notes" class="form-label">রোগীর সমস্যা বা অতিরিক্ত নির্দেশনা (ঐচ্ছিক)</label>
                        <textarea class="form-control glass-input" id="notes" name="notes" rows="2" placeholder="যেমন: থ্যালাসেমিয়া রোগী / সিজারিয়ান অপারেশন / প্লাটিলেট প্রয়োজন"></textarea>
                    </div>

                    <button type="submit" class="btn btn-blood btn-lg w-100 rounded-pill py-3 fw-bold emergency-pulse">
                        <i class="bi bi-send-fill me-2"></i> রক্তের আবেদন জমা দিন ও ডোনার খুঁজুন
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
