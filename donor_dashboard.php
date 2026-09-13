<?php
$pageTitle = 'ডোনার ড্যাশবোর্ড — BMMC';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/functions.php';

require_login();
$user = current_user();
$pdo = DB::getConnection();

// Fetch donor record
$stmt = $pdo->prepare("SELECT * FROM donors WHERE user_id = ?");
$stmt->execute([$user['id']]);
$donor = $stmt->fetch();

if (!$donor) {
    set_flash('info', 'রক্তদাতা ড্যাশবোর্ড দেখার আগে অনুগ্রহ করে রক্তদাতা তথ্য ফর্মটি পূরণ করুন।');
    header('Location: ' . BASE_URL . '/donor_register.php');
    exit;
}

// Refresh resting status automatically if date passed
refresh_donor_resting_status($donor['id']);
// Reload donor data
$stmt->execute([$user['id']]);
$donor = $stmt->fetch();

// Calculate resting days remaining
$isResting = ($donor['is_available'] == 0 && !empty($donor['next_available_date']) && strtotime($donor['next_available_date']) > time());
$daysRemaining = 0;
if ($isResting) {
    $diff = strtotime($donor['next_available_date']) - time();
    $daysRemaining = max(1, ceil($diff / (60 * 60 * 24)));
}

// Fetch donation history
$histStmt = $pdo->prepare("SELECT * FROM donation_history WHERE donor_id = ? ORDER BY donation_date DESC");
$histStmt->execute([$donor['id']]);
$history = $histStmt->fetchAll();

// Fetch requests where donor was notified
$reqStmt = $pdo->prepare("
    SELECT dr.status AS response_status, dr.response_token, dr.responded_at, br.*
    FROM donor_responses dr
    JOIN blood_requests br ON dr.request_id = br.id
    WHERE dr.donor_id = ?
    ORDER BY dr.created_at DESC
    LIMIT 10
");
$reqStmt->execute([$donor['id']]);
$notifiedRequests = $reqStmt->fetchAll();
?>

<div class="container py-5">
    <!-- Top Welcome Banner -->
    <div class="glass-card p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="blood-pill" style="width: 64px; height: 64px; font-size: 1.5rem;">
                    <?= htmlspecialchars($donor['blood_group']) ?>
                </div>
                <div>
                    <h3 class="fw-bold text-white mb-1 d-flex align-items-center gap-2">
                        <?= htmlspecialchars($user['name']) ?>
                        <?php if ($user['user_type'] === 'mariner'): ?>
                            <span class="mariner-badge">
                                <i class="bi bi-compass"></i> মেরিনার (<?= htmlspecialchars($user['mariner_rank'] ?? 'Seafarer') ?>)
                            </span>
                        <?php endif; ?>
                    </h3>
                    <p class="text-secondary small mb-0">
                        <i class="bi bi-geo-alt me-1 text-info"></i><?= htmlspecialchars($donor['area']) ?>, <?= htmlspecialchars($donor['district']) ?> &nbsp;|&nbsp; 
                        <i class="bi bi-telephone me-1 text-info"></i><?= htmlspecialchars($user['phone']) ?>
                        <?php if (!empty($user['cdc_sid_no'])): ?>
                            &nbsp;|&nbsp; <span class="text-info fw-semibold">CDC/SID:</span> <?= htmlspecialchars($user['cdc_sid_no']) ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <!-- Total Donations Counter -->
            <div class="text-md-end">
                <span class="d-block text-secondary small">মোট রক্তদান</span>
                <span class="fs-2 fw-bold text-info"><?= to_bangla_number($donor['total_donations']) ?> বার</span>
            </div>
        </div>
    </div>

    <!-- Status Banner: Resting Period vs Available -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <?php if ($isResting): ?>
                <div class="resting-banner glass-card h-100 p-4">
                    <div class="d-flex align-items-start gap-3">
                        <i class="bi bi-hourglass-split fs-1 text-warning"></i>
                        <div>
                            <h5 class="fw-bold text-warning mb-2">বিশ্রাম ও সুস্থতা সময়কাল চলছে (Resting Period)</h5>
                            <p class="small text-light mb-2">
                                একজন রক্তদাতার শারীরিক সুস্থতা রক্ষার্থে সফল রক্তদানের পর ৪ মাস (১২০ দিন) একটি বাধ্যতামূলক বিশ্রাম বিরতি থাকে। এই সময়ে সিস্টেম থেকে আপনার কাছে কোনো নতুন রক্তের অনুরোধ পাঠানো হবে না।
                            </p>
                            <div class="badge bg-warning text-dark fs-6 px-3 py-2 mt-1">
                                <i class="bi bi-calendar-check me-1"></i> আর মাত্র <?= to_bangla_number($daysRemaining) ?> দিন বাকি (সম্ভাব্য তারিখ: <?= format_date_bn($donor['next_available_date']) ?>)
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="available-banner glass-card h-100 p-4">
                    <div class="d-flex align-items-start gap-3">
                        <i class="bi bi-check-circle-fill fs-1 text-success"></i>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold text-success mb-2">আপনি রক্তদানের জন্য সম্পূর্ণ প্রস্তুত (Ready for Donation)</h5>
                            <p class="small text-light mb-3">
                                আপনার এলাকার কাছাকাছি কোনো মুমূর্ষু রোগীর আপনার ব্লাড গ্রুপের প্রয়োজন হলে স্বয়ংক্রিয়ভাবে আপনার ইমেইলে নোটিফিকেশন পৌঁছে যাবে।
                            </p>
                            <div class="d-flex gap-2 align-items-center">
                                <span class="badge bg-success px-3 py-2">স্ট্যাটাস: সক্রিয় (Active)</span>
                                <a href="<?= BASE_URL ?>/controllers/donor_controller.php?action=toggle_availability" class="btn btn-outline-light btn-sm rounded-pill">
                                    সাময়িকভাবে নিষ্ক্রিয় করুন
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Log a Donation Modal Button -->
        <div class="col-lg-4">
            <div class="glass-card p-4 h-100 text-center d-flex flex-column justify-content-center">
                <i class="bi bi-droplet-half fs-1 text-danger mb-2"></i>
                <h6 class="fw-bold text-white mb-2">সম্প্রতি কি রক্তদান করেছেন?</h6>
                <p class="text-secondary small mb-3">রক্তদানের তথ্য আপডেট করুন যাতে ৪ মাসের বিশ্রাম সময়কাল শুরু হতে পারে।</p>
                <button type="button" class="btn btn-blood btn-sm rounded-pill py-2" data-bs-toggle="modal" data-bs-target="#logDonationModal">
                    <i class="bi bi-plus-circle me-1"></i> রক্তদান সম্পন্ন রেকর্ড করুন
                </button>
            </div>
        </div>
    </div>

    <!-- Notifications & Responses Section -->
    <div class="row g-4">
        <!-- Recent Blood Requests Notified to Donor -->
        <div class="col-lg-7">
            <div class="glass-card p-4 h-100">
                <h5 class="fw-bold text-white mb-3"><i class="bi bi-bell-fill text-info me-2"></i>আপনার কাছে আসা রক্তের অনুরোধসমূহ</h5>
                
                <?php if (empty($notifiedRequests)): ?>
                    <p class="text-secondary small text-center py-4">আপাতত আপনার কাছে কোনো পেন্ডিং অনুরোধ আসেনি।</p>
                <?php else: ?>
                    <div class="list-group list-group-flush bg-transparent">
                        <?php foreach ($notifiedRequests as $r): ?>
                            <div class="list-group-item bg-transparent text-light border-secondary border-opacity-25 px-0 py-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <span class="badge bg-danger me-2"><?= htmlspecialchars($r['blood_group']) ?></span>
                                        <strong><?= htmlspecialchars($r['patient_name']) ?></strong>
                                        <small class="text-secondary d-block"><i class="bi bi-hospital me-1"></i><?= htmlspecialchars($r['hospital']) ?>, <?= htmlspecialchars($r['area']) ?></small>
                                    </div>
                                    <?php if ($r['response_status'] === 'agreed'): ?>
                                        <span class="badge bg-success"><i class="bi bi-check-lg me-1"></i>সম্মতি দিয়েছেন</span>
                                    <?php elseif ($r['response_status'] === 'declined'): ?>
                                        <span class="badge bg-secondary">অপারগ</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">অপেক্ষমান</span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="text-muted"><?= format_date_bn($r['created_at']) ?></small>
                                    <?php if ($r['response_status'] === 'notified'): ?>
                                        <div class="d-flex gap-2">
                                            <a href="<?= BASE_URL ?>/respond.php?token=<?= $r['response_token'] ?>&action=agree" class="btn btn-sm btn-success rounded-pill px-3">
                                                ✅ রাজি
                                            </a>
                                            <a href="<?= BASE_URL ?>/respond.php?token=<?= $r['response_token'] ?>&action=decline" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                                ❌ অপারগ
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Donation History Table -->
        <div class="col-lg-5">
            <div class="glass-card p-4 h-100">
                <h5 class="fw-bold text-white mb-3"><i class="bi bi-clock-history text-info me-2"></i>রক্তদানের পূর্ববর্তী ইতিহাস</h5>
                
                <?php if (empty($history)): ?>
                    <p class="text-secondary small text-center py-4">এখনো কোনো পূর্ববর্তী রক্তদানের রেকর্ড নেই।</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-dark table-borderless small mb-0">
                            <thead>
                                <tr class="border-bottom border-secondary text-secondary">
                                    <th>তারিখ</th>
                                    <th>হাসপাতাল / স্থান</th>
                                    <th>বিশ্রাম শেষ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($history as $h): ?>
                                    <tr class="border-bottom border-secondary border-opacity-10">
                                        <td><?= format_date_bn($h['donation_date']) ?></td>
                                        <td><?= htmlspecialchars($h['hospital'] ?: 'উল্লেখ নেই') ?></td>
                                        <td class="text-warning"><?= format_date_bn($h['resting_until']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Log a Donation Completed -->
<div class="modal fade" id="logDonationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-secondary">
            <div class="modal-header border-secondary border-opacity-25">
                <h5 class="modal-title text-white"><i class="bi bi-droplet-fill text-danger me-2"></i>রক্তদান সম্পন্ন রেকর্ড</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= BASE_URL ?>/controllers/donation_controller.php?action=complete_donor_self" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body text-light">
                    <p class="small text-secondary">
                        রক্তদান সম্পন্ন রেকর্ড করলে আপনার প্রোফাইলে মোট রক্তদানের সংখ্যা ১ বৃদ্ধি পাবে এবং আগামী ১২০ দিনের (৪ মাস) জন্য বিশ্রাম বিরতি কার্যকর হবে।
                    </p>
                    <div class="mb-3">
                        <label class="form-label">রক্তদানের তারিখ <span class="text-danger">*</span></label>
                        <input type="date" class="form-control glass-input" name="donation_date" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">হাসপাতালের নাম ও স্থান</label>
                        <input type="text" class="form-control glass-input" name="hospital" placeholder="যেমন: চট্টগ্রাম মেডিকেল কলেজ হাসপাতাল">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">মন্তব্য / নোট (ঐচ্ছিক)</label>
                        <textarea class="form-control glass-input" name="notes" rows="2" placeholder="রোগীর নাম বা কোনো নোট"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-secondary border-opacity-25">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-blood btn-sm rounded-pill px-4">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
