<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';
?>
<footer class="py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <!-- Col 1: About BMMC -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width: 36px; height: 36px; background: #00d2ff; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-compass fs-5 text-dark"></i>
                    </div>
                    <h5 class="fw-bold text-white mb-0">BMMC</h5>
                </div>
                <p class="text-secondary small">
                    বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি — দেশ ও বিদেশের সমুদ্রগামী মেরিনারদের নিয়ে গঠিত একটি অরাজনৈতিক, অলাভজনক এবং সম্পূর্ণ স্বেচ্ছাসেবী প্ল্যাটফর্ম।
                </p>
                <div class="d-flex gap-3 mt-3">
                    <a href="#" class="text-info fs-5"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-info fs-5"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="text-info fs-5"><i class="bi bi-whatsapp"></i></a>
                    <a href="#" class="text-info fs-5"><i class="bi bi-envelope"></i></a>
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h6 class="text-white fw-bold mb-3 border-bottom border-secondary pb-2">জরুরি লিংক</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?= BASE_URL ?>/blood_request.php" class="text-secondary text-decoration-none hover-info"><i class="bi bi-chevron-right me-1 text-danger"></i>রক্তের আবেদন</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/donor_register.php" class="text-secondary text-decoration-none hover-info"><i class="bi bi-chevron-right me-1 text-danger"></i>ডোনার রেজিস্ট্রেশন</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/volunteer_register.php" class="text-secondary text-decoration-none hover-info"><i class="bi bi-chevron-right me-1 text-info"></i>ভলান্টিয়ার আবেদন</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/login.php" class="text-secondary text-decoration-none hover-info"><i class="bi bi-chevron-right me-1 text-info"></i>লগইন পোর্টাল</a></li>
                </ul>
            </div>

            <!-- Col 3: Seafarers Pledge -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3 border-bottom border-secondary pb-2">আমাদের অঙ্গীকার</h6>
                <p class="text-secondary small">
                    "দুনিয়াবি কোনো বিনিময় বা বেতনাদি নেই — পুরোটাই শতভাগ স্বেচ্ছাসেবা। দেশ ও জাতির প্রতিটি সংকটে মেরিনার ভাইয়েরা মানুষের পাশে থাকবে।"
                </p>
                <div class="badge bg-dark border border-secondary text-light p-2">
                    <i class="bi bi-shield-check text-success me-1"></i> বিশ্বস্ত মেরিন নেটওয়ার্ক
                </div>
            </div>

            <!-- Col 4: Contact -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3 border-bottom border-secondary pb-2">যোগাযোগ</h6>
                <p class="text-secondary small mb-1"><i class="bi bi-geo-alt me-2 text-info"></i>চট্টগ্রাম ও ঢাকা, বাংলাদেশ</p>
                <p class="text-secondary small mb-1"><i class="bi bi-globe me-2 text-info"></i>bmmc.skillsetup.org</p>
                <p class="text-secondary small mb-1"><i class="bi bi-envelope me-2 text-info"></i>welfare@bmmc.org</p>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <div class="row align-items-center small text-secondary">
            <div class="col-md-6 text-center text-md-start">
                © <?= date('Y') ?> বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি (BMMC). সর্বস্বত্ব সংরক্ষিত।
            </div>
            <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                <span class="text-muted">শতভাগ অলাভজনক ও অবাণিজ্যিক মেরিনার্স প্ল্যাটফর্ম</span>
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
