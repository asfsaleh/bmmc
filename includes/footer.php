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
                        <h5 class="fw-bold text-white mb-0"><?= __('site_short_name', 'BMMC') ?></h5>
                        <small class="text-info" style="font-size: 0.72rem;"><?= __('nav_brand_sub', 'Bangladesh Merchant Mariners') ?></small>
                    </div>
                </div>
                <p class="text-secondary small" style="line-height: 1.7;">
                    <?= __('footer_about_desc') ?>
                </p>
                <div class="d-flex gap-3 mt-3">
                    <a href="https://facebook.com" target="_blank" class="text-info fs-5" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="https://wa.me/8801711000000" target="_blank" class="text-success fs-5" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                    <a href="mailto:welfare@bmmc.org" class="text-warning fs-5" aria-label="Email"><i class="bi bi-envelope"></i></a>
                    <a href="<?= BASE_URL ?>/volunteer_register.php" class="text-info fs-5" title="<?= __('footer_link_vol_reg') ?>"><i class="bi bi-person-heart"></i></a>
                </div>
            </div>

            <!-- Col 2: Service Wings -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3 border-bottom border-secondary pb-2"><?= __('footer_wings_title') ?></h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?= BASE_URL ?>/blood.php" class="text-light text-decoration-none"><i class="bi bi-droplet-fill me-1 text-danger"></i><?= __('footer_link_blood') ?></a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/service.php?slug=emergency-team" class="text-secondary text-decoration-none"><i class="bi bi-shield-exclamation me-1 text-danger"></i><?= __('footer_link_emergency') ?></a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/service.php?slug=legal-support" class="text-secondary text-decoration-none"><i class="bi bi-hammer me-1 text-warning"></i><?= __('footer_link_legal') ?></a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/service.php?slug=cadet-programme" class="text-secondary text-decoration-none"><i class="bi bi-mortarboard me-1 text-info"></i><?= __('footer_link_cadet') ?></a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/service.php?slug=seatime-calculator" class="text-secondary text-decoration-none"><i class="bi bi-calculator me-1 text-warning"></i><?= __('footer_link_seatime') ?></a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/index.php#services-section" class="text-info text-decoration-none fw-semibold"><i class="bi bi-arrow-right-circle me-1"></i><?= __('footer_link_all_services') ?></a></li>
                </ul>
            </div>

            <!-- Col 3: Seafarers Pledge & Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h6 class="text-white fw-bold mb-3 border-bottom border-secondary pb-2"><?= __('footer_quick_links_title') ?></h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?= BASE_URL ?>/blood.php" class="text-secondary text-decoration-none"><i class="bi bi-chevron-right me-1 text-danger"></i><?= __('nav_blood_portal') ?></a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/blood_request.php" class="text-secondary text-decoration-none"><i class="bi bi-chevron-right me-1 text-danger"></i><?= __('footer_link_blood_req') ?></a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/donor_register.php" class="text-secondary text-decoration-none"><i class="bi bi-chevron-right me-1 text-warning"></i><?= __('footer_link_donor_reg') ?></a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/volunteer_register.php" class="text-info fw-semibold text-decoration-none"><i class="bi bi-chevron-right me-1"></i><?= __('footer_link_vol_reg') ?></a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>/login.php" class="text-secondary text-decoration-none"><i class="bi bi-chevron-right me-1 text-info"></i><?= __('footer_link_login') ?></a></li>
                </ul>
            </div>

            <!-- Col 4: Contact & Volunteer -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3 border-bottom border-secondary pb-2"><?= __('footer_contact_title') ?></h6>
                <p class="text-secondary small mb-1"><i class="bi bi-geo-alt me-2 text-info"></i><?= __('footer_contact_loc') ?></p>
                <p class="text-secondary small mb-1"><i class="bi bi-globe me-2 text-info"></i><?= __('footer_contact_web') ?></p>
                <p class="text-secondary small mb-3"><i class="bi bi-envelope me-2 text-info"></i><?= __('footer_contact_email') ?></p>
                
                <a href="<?= BASE_URL ?>/volunteer_register.php" class="btn btn-volunteer-highlight btn-sm w-100 py-2">
                    <i class="bi bi-person-heart me-1"></i> <?= __('nav_volunteer_btn', 'Become a BMMC Volunteer') ?>
                </a>
            </div>
        </div>

        <hr class="border-secondary my-4 opacity-25">

        <!-- Bottom Row: Copyright + PC Language Switcher Toggle + Disclaimer -->
        <div class="row align-items-center small text-secondary">
            <div class="col-lg-4 text-center text-lg-start mb-2 mb-lg-0">
                © <?= date('Y') ?> <?= __('site_name') ?>. <?= __('footer_rights_reserved') ?>
            </div>
            
            <!-- Desktop / PC Footer Language Switcher (Prominent & Accessible) -->
            <div class="col-lg-4 text-center mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center gap-2 p-1 px-3 rounded-pill bmmc-footer-lang-switcher" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.15);">
                    <span class="small text-secondary fw-semibold">
                        <i class="bi bi-globe2 text-info me-1"></i><?= __('footer_lang_label') ?>
                    </span>
                    <div class="btn-group btn-group-sm rounded-pill p-1" style="background: rgba(0, 0, 0, 0.35); border: 1px solid rgba(255, 255, 255, 0.1);">
                        <a href="<?= lang_url('en') ?>" class="btn btn-sm rounded-pill px-3 py-1 <?= is_english() ? 'fw-bold shadow-sm' : 'text-light' ?>" style="<?= is_english() ? 'background: #00d2ff; color: #061426; font-weight: 700;' : 'color: rgba(255,255,255,0.7);' ?>" title="Switch to English">
                            English
                        </a>
                        <a href="<?= lang_url('bn') ?>" class="btn btn-sm rounded-pill px-3 py-1 <?= !is_english() ? 'fw-bold shadow-sm' : 'text-light' ?>" style="<?= !is_english() ? 'background: #00d2ff; color: #061426; font-weight: 700;' : 'color: rgba(255,255,255,0.7);' ?>" title="বাংলা ভাষায় দেখুন">
                            বাংলা
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 text-center text-lg-end mt-2 mt-lg-0">
                <span class="text-muted"><?= __('footer_disclaimer') ?></span>
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
