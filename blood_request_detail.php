<?php
$pageTitle = 'আবেদনের বিস্তারিত ও অগ্রগতি — BMMC';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = DB::getConnection();
$requestId = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM blood_requests WHERE id = ?");
$stmt->execute([$requestId]);
$request = $stmt->fetch();

if (!$request) {
    set_flash('danger', 'অনুরোধটি খুঁজে পাওয়া যায়নি।');
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

// Fetch responses & agreed donors
$respStmt = $pdo->prepare("
    SELECT dr.status AS response_status, dr.responded_at, d.blood_group, d.district, d.area, 
           u.name, u.phone, u.user_type, u.mariner_rank, u.cdc_sid_no
    FROM donor_responses dr
    JOIN donors d ON dr.donor_id = d.id
    JOIN users u ON d.user_id = u.id
    WHERE dr.request_id = ?
    ORDER BY dr.responded_at DESC
");
$respStmt->execute([$requestId]);
$responses = $respStmt->fetchAll();

$agreedDonors = array_filter($responses, fn($r) => $r['response_status'] === 'agreed');
$totalNotified = count($responses);
$totalAgreed = count($agreedDonors);

$currentUser = current_user();
$canManage = is_admin() || ($currentUser && $currentUser['id'] == $request['requester_id']);
?>

<div class="container py-5">
    <div class="row justify-content-center g-4">
        <div class="col-lg-8">
            <!-- Main Request Card -->
            <div class="glass-blood-card p-4 p-md-5 mb-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="blood-pill" style="width: 64px; height: 64px; font-size: 1.6rem;">
                            <?= htmlspecialchars($request['blood_group']) ?>
                        </div>
                        <div>
                            <h3 class="fw-bold text-white mb-1"><?= htmlspecialchars($request['patient_name']) ?></h3>
                            <span class="text-secondary small"><i class="bi bi-clock me-1"></i>আবেদনের সময়: <?= format_date_bn($request['created_at']) ?></span>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-2">
                        <?= get_urgency_badge($request['urgency']) ?>
                        <?= get_status_badge($request['status']) ?>
                    </div>
                </div>

                <div class="row g-3 p-3 rounded-3 mb-4" style="background: rgba(0,0,0,0.25);">
                    <div class="col-sm-6">
                        <span class="text-secondary small d-block">হাসপাতালের নাম:</span>
                        <strong class="text-light"><?= htmlspecialchars($request['hospital']) ?></strong>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-secondary small d-block">স্থান ও জেলা:</span>
                        <strong class="text-light"><?= htmlspecialchars($request['area']) ?>, <?= htmlspecialchars($request['district']) ?></strong>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-secondary small d-block">প্রয়োজনীয় রক্ত:</span>
                        <strong class="text-danger fs-5"><?= to_bangla_number($request['bags_needed']) ?> ব্যাগ</strong>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-secondary small d-block">প্রয়োজনের সম্ভাব্য তারিখ:</span>
                        <strong class="text-warning"><?= format_date_bn($request['needed_by']) ?></strong>
                    </div>
                    <?php if (!empty($request['notes'])): ?>
                    <div class="col-12 pt-2 border-top border-secondary border-opacity-25">
                        <span class="text-secondary small d-block">রোগীর বিস্তারিত/নোট:</span>
                        <span class="text-light"><?= nl2br(htmlspecialchars($request['notes'])) ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Contact Box -->
                <div class="p-3 rounded-3 border border-info border-opacity-30 mb-4" style="background: rgba(0, 210, 255, 0.05);">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <span class="text-secondary small d-block">জরুরি যোগাযোগের ব্যক্তি:</span>
                            <strong class="text-white fs-5"><?= htmlspecialchars($request['contact_name']) ?></strong>
                        </div>
                        <a href="tel:<?= htmlspecialchars($request['contact_phone']) ?>" class="btn btn-ocean rounded-pill px-4">
                            <i class="bi bi-telephone-fill me-2"></i><?= htmlspecialchars($request['contact_phone']) ?>
                        </a>
                    </div>
                </div>

                <!-- Social Share / WhatsApp Sharing Button -->
                <?php
                $shareText = urlencode("🚨 জরুরি রক্তের আবেদন!\nগ্রুপ: {$request['blood_group']}\nরোগী: {$request['patient_name']}\nহাসপাতাল: {$request['hospital']}, {$request['district']}\nযোগাযোগ: {$request['contact_phone']}\nবিস্তারিত: " . BASE_URL . "/blood_request_detail.php?id=" . $request['id']);
                ?>
                <div class="d-flex gap-2">
                    <a href="https://api.whatsapp.com/send?text=<?= $shareText ?>" target="_blank" class="btn btn-success btn-sm rounded-pill px-3">
                        <i class="bi bi-whatsapp me-1"></i> হোয়াটসঅ্যাপে শেয়ার করুন
                    </a>
                    <?php if ($canManage && $request['status'] !== 'fulfilled'): ?>
                        <a href="<?= BASE_URL ?>/controllers/donation_controller.php?action=mark_fulfilled&id=<?= $request['id'] ?>" class="btn btn-outline-success btn-sm rounded-pill px-3 ms-auto" onclick="return confirm('রক্তদান কি সফলভাবে সম্পন্ন হয়েছে?');">
                            <i class="bi bi-check-circle me-1"></i> সম্পন্ন (Fulfilled) হিসেবে চিহ্নিত করুন
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Matching & Agreed Donors Section -->
            <div class="glass-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-white mb-0">
                        <i class="bi bi-people-fill text-info me-2"></i>স্বয়ংক্রিয় ডোনার অনুসন্ধান ফলাফল
                    </h5>
                    <div>
                        <span class="badge bg-primary me-1">বিজ্ঞপ্তি পাঠানো: <?= to_bangla_number($totalNotified) ?> জন</span>
                        <span class="badge bg-success">রাজি হয়েছেন: <?= to_bangla_number($totalAgreed) ?> জন</span>
                    </div>
                </div>

                <?php if (empty($agreedDonors)): ?>
                    <div class="text-center py-4 text-secondary">
                        <i class="bi bi-hourglass-top fs-2 text-warning d-block mb-2"></i>
                        <p class="mb-1">এখনো কোনো রক্তদাতা সম্মতি নিশ্চিত করেননি। উপযুক্ত রক্তদাতাদের ইমেইলে বিজ্ঞপ্তি পাঠানো রয়েছে।</p>
                        <small class="text-muted">ডোনার সম্মতি দিলে তাৎক্ষণিকভাবে এখানে তার নম্বর ও বিবরণ দৃশ্যমান হবে।</small>
                    </div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($agreedDonors as $donor): ?>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 border border-success border-opacity-50" style="background: rgba(16, 185, 129, 0.1);">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold text-white mb-0"><?= htmlspecialchars($donor['name']) ?></h6>
                                        <span class="badge bg-danger"><?= htmlspecialchars($donor['blood_group']) ?></span>
                                    </div>
                                    <?php if ($donor['user_type'] === 'mariner'): ?>
                                        <div class="mariner-badge mb-2" style="font-size: 0.75rem;">
                                            <i class="bi bi-compass"></i> মেরিনার (<?= htmlspecialchars($donor['mariner_rank'] ?? 'Seafarer') ?>)
                                        </div>
                                    <?php endif; ?>
                                    <p class="small text-secondary mb-2">
                                        <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($donor['area']) ?>, <?= htmlspecialchars($donor['district']) ?>
                                    </p>
                                    <a href="tel:<?= htmlspecialchars($donor['phone']) ?>" class="btn btn-sm btn-success rounded-pill w-100 fw-bold">
                                        <i class="bi bi-telephone-fill me-1"></i> কল করুন: <?= htmlspecialchars($donor['phone']) ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
