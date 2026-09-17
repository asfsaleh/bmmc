<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/services_data.php';

$footerPillars = get_bmmc_pillars();
?>
<footer class="py-5 mt-5 border-top border-secondary border-opacity-25" style="background: rgba(4, 14, 28, 0.95);">
    <div class="container">
        <div class="row g-4">
            <!-- Col 1: About BMMC -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width: 38px; height: 38px; background: linear-gradient(135deg, #00d2ff, #0284c7); border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 14px rgba(0, 210, 255, 0.4);">
                        <i class="bi bi-compass fs-4 text-white"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-white mb-0">BMMC</h5>
                        <small class="text-info" style="font-size: 0.72rem;">বাংলাদেশ মার্চেন্ট মেরিনার্স</small>
                    </div>
                </div>
                <p class="text-secondary small" style="line-height: 1.7;">
                    বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি (BMMC) — বিশ্বজুড়ে সমুদ্রগামী মেরিনার ও দেশের সাধারণ নাগরিকদের ঐক্যবদ্ধ প্ল্যাটফর্ম। জরুরি উদ্ধার, আইনি সহায়তা, ক্যাডেট মেন্টরশিপ, স্মার্ট টুলস ও রক্তদান নেটওয়ার্ক সহ ২১টি সেবায় নিবেদিত।
                </p>
                <div class="d-flex gap-3 mt-3">
                    <a href="https://facebook.com" target="_blank" class="text-info fs-5"><i class="bi bi-facebook"></i></a>
                    <a href="https://wa.me/8801711000000" target="_blank" class="text-success fs-5"><i class="bi bi-whatsapp"></i></a>
                    <a href="mailto:welfare@bmmc.org" class="text-warning fs-5"><i class="bi bi-envelope"></i></a>
                    <a href="<?= BASE_URL ?>/volunteer_register.php" class="text-info fs-5" title="ভলান্টিয়ার আবেদন"><i class="bi bi-person-heart"></i></a>
                </div>
            </div>

            <!-- Col 2: Service Wings -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3 border-bottom border-secondary pb-2">আমাদের সেবা স্তম্ভ</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?= BASE_URL ?>/blood.php" class="text-light text-decoration-none"><i class="bi bi-droplet-fill me-1 text-danger"></i>রক্তদান নেটওয়ার্ক (Live)</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/service.php?slug=emergency-team" class="text-secondary text-decoration-none"><i class="bi bi-shield-exclamation me-1 text-danger"></i>ইমার্জেন্সি রেসপন্স টিম</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/service.php?slug=legal-support" class="text-secondary text-decoration-none"><i class="bi bi-balance-scale me-1 text-warning"></i>আইনি সহায়তা ও ITF</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/service.php?slug=cadet-programme" class="text-secondary text-decoration-none"><i class="bi bi-mortarboard me-1 text-info"></i>ফ্রেশ ক্যাডেট ট্রেইনিং</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/service.php?slug=seatime-calculator" class="text-secondary text-decoration-none"><i class="bi bi-calculator me-1 text-warning"></i>সি-টাইম ক্যালকুলেটর</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/index.php#services-section" class="text-info text-decoration-none fw-semibold"><i class="bi bi-arrow-right-circle me-1"></i>সমস্ত ২১টি সেবাসমূহ →</a></li>
                </ul>
            </div>

            <!-- Col 3: Seafarers Pledge & Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h6 class="text-white fw-bold mb-3 border-bottom border-secondary pb-2">জরুরি পোর্টাল</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?= BASE_URL ?>/blood.php" class="text-secondary text-decoration-none"><i class="bi bi-chevron-right me-1 text-danger"></i>রক্তদান পোর্টাল</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/blood_request.php" class="text-secondary text-decoration-none"><i class="bi bi-chevron-right me-1 text-danger"></i>রক্তের আবেদন</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/donor_register.php" class="text-secondary text-decoration-none"><i class="bi bi-chevron-right me-1 text-warning"></i>ডোনার রেজিস্ট্রেশন</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/volunteer_register.php" class="text-info fw-semibold text-decoration-none"><i class="bi bi-chevron-right me-1"></i>ভলান্টিয়ার আবেদন</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/login.php" class="text-secondary text-decoration-none"><i class="bi bi-chevron-right me-1 text-info"></i>লগইন একাউন্ট</a></li>
                </ul>
            </div>

            <!-- Col 4: Contact & Volunteer -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3 border-bottom border-secondary pb-2">যোগাযোগ ও কেন্দ্র</h6>
                <p class="text-secondary small mb-1"><i class="bi bi-geo-alt me-2 text-info"></i>চট্টগ্রাম পোর্ট ও ঢাকা, বাংলাদেশ</p>
                <p class="text-secondary small mb-1"><i class="bi bi-globe me-2 text-info"></i>bmmc.skillsetup.org</p>
                <p class="text-secondary small mb-3"><i class="bi bi-envelope me-2 text-info"></i>welfare@bmmc.org</p>
                
                <a href="<?= BASE_URL ?>/volunteer_register.php" class="btn btn-volunteer-highlight btn-sm w-100 py-2">
                    <i class="bi bi-person-heart me-1"></i> Become a BMMC Volunteer
                </a>
            </div>
        </div>

        <hr class="border-secondary my-4 opacity-25">

        <div class="row align-items-center small text-secondary">
            <div class="col-md-6 text-center text-md-start">
                © <?= date('Y') ?> বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি (BMMC). সর্বস্বত্ব সংরক্ষিত।
            </div>
            <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                <span class="text-muted">১০০% অলাভজনক, অরাজনৈতিক ও স্বেচ্ছাসেবী মেরিটাইম নেটওয়ার্ক</span>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Main Custom JS -->
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
