<?php
$pageTitle = 'ভলান্টিয়ার আবেদন — BMMC';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/districts.php';
require_once __DIR__ . '/includes/functions.php';

$marinerRanks = getMarinerRanks();
$currentUser = current_user();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <div class="glass-card p-4 p-md-5 position-relative">
                <!-- Header -->
                <div class="text-center mb-4">
                    <div style="width: 60px; height: 60px; margin: 0 auto 16px; background: rgba(0, 210, 255, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #00d2ff;">
                        <i class="bi bi-people-fill text-info fs-2"></i>
                    </div>
                    <h3 class="fw-bold text-white mb-2">BMMC ভলান্টিয়ার টিমে যুক্ত হোন</h3>
                    <p class="text-secondary small">
                        মেরিন কমিউনিটির বিভিন্ন কল্যানমূলক অরাজনৈতিক ও অলাভজনক কাজের জন্য নিঃস্বার্থ স্বেচ্ছাসেবী আহ্বান।
                    </p>
                    <div class="alert alert-dark bg-opacity-50 border-secondary py-2 small text-warning">
                        <i class="bi bi-exclamation-circle me-1"></i> দুনিয়াবি কোনো বিনিময় বা বেতনাদি নেই — পুরোটাই শতভাগ স্বেচ্ছাসেবা!
                    </div>
                </div>

                <form action="<?= BASE_URL ?>/controllers/auth_controller.php?action=volunteer_register" method="POST">
                    <?= csrf_field() ?>

                    <!-- Identity -->
                    <div class="p-3 mb-4 rounded-3 border border-info border-opacity-30" style="background: rgba(0, 210, 255, 0.06);">
                        <label class="form-label d-block text-info fw-bold mb-2">
                            <i class="bi bi-person-badge me-1"></i> আপনার পরিচয় <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="user_type" id="v_type_mariner" value="mariner" checked>
                                <label class="form-check-label text-white fw-semibold" for="v_type_mariner">
                                    ⚓ মেরিনার (Merchant Mariner)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="user_type" id="v_type_general" value="general">
                                <label class="form-check-label text-white fw-semibold" for="v_type_general">
                                    👤 সাধারণ স্বেচ্ছাসেবী (General Volunteer)
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Mariner Specifics -->
                    <div id="mariner-fields" class="p-3 mb-4 rounded-3 border border-primary border-opacity-30" style="background: rgba(30, 58, 138, 0.2);">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="cdc_sid_no" class="form-label text-light">CDC / SID নম্বর <span class="text-danger">*</span></label>
                                <input type="text" class="form-control glass-input" id="cdc_sid_no" name="cdc_sid_no" placeholder="যেমন: C/O/12345 বা SID No.">
                            </div>
                            <div class="col-md-6">
                                <label for="mariner_rank" class="form-label text-light">পদবী / র‍্যাংক</label>
                                <select class="form-select glass-input" id="mariner_rank" name="mariner_rank">
                                    <option value="">-- নির্বাচন করুন --</option>
                                    <?php foreach ($marinerRanks as $title => $val): ?>
                                        <option value="<?= htmlspecialchars($val) ?>"><?= htmlspecialchars($title) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Details -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">পূর্ণ নাম <span class="text-danger">*</span></label>
                            <input type="text" class="form-control glass-input" id="name" name="name" value="<?= htmlspecialchars($currentUser['name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">মোবাইল নম্বর <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control glass-input" id="phone" name="phone" value="<?= htmlspecialchars($currentUser['phone'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">ইমেইল ঠিকানা <span class="text-danger">*</span></label>
                        <input type="email" class="form-control glass-input" id="email" name="email" value="<?= htmlspecialchars($currentUser['email'] ?? '') ?>" required>
                    </div>

                    <!-- Preferred Areas of Volunteer Work -->
                    <div class="mb-3">
                        <label class="form-label">কোন কোন ক্ষেত্রে যুক্ত থাকতে চান?</label>
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="interest[]" value="Blood Donation Coordination" id="int_1" checked>
                                    <label class="form-check-label text-light small" for="int_1">রক্তদান কার্যক্রম সমন্বয়</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="interest[]" value="Mariner Welfare Support" id="int_2" checked>
                                    <label class="form-check-label text-light small" for="int_2">মেরিনার কল্যাণ ও উদ্ধার সহায়তা</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="interest[]" value="Port & Hospital Liaison" id="int_3">
                                    <label class="form-check-label text-light small" for="int_3">হাসপাতাল ও পোর্ট যোগাযোগ</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="interest[]" value="IT & Media Management" id="int_4">
                                    <label class="form-check-label text-light small" for="int_4">আইটি ও সোশ্যাল মিডিয়া</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message / Statement -->
                    <div class="mb-4">
                        <label for="message" class="form-label">আপনার অভিজ্ঞতা বা কিছু বার্তা (ঐচ্ছিক)</label>
                        <textarea class="form-control glass-input" id="message" name="message" rows="3" placeholder="অতীতে কোনো স্বেচ্ছাসেবার অভিজ্ঞতা থাকলে লিখুন..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-ocean btn-lg w-100 rounded-pill py-3 fw-bold">
                        <i class="bi bi-check-circle-fill me-2"></i> ভলান্টিয়ার আবেদন জমা দিন
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
