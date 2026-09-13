<?php
$pageTitle = 'অ্যাডমিন ড্যাশবোর্ড — BMMC';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();
$pdo = DB::getConnection();

// Core Counts
$totalDonors = $pdo->query("SELECT COUNT(*) FROM donors")->fetchColumn() ?: 0;
$marinerDonors = $pdo->query("SELECT COUNT(*) FROM donors d JOIN users u ON d.user_id = u.id WHERE u.user_type = 'mariner'")->fetchColumn() ?: 0;
$restingDonors = $pdo->query("SELECT COUNT(*) FROM donors WHERE is_available = 0 AND next_available_date > CURDATE()")->fetchColumn() ?: 0;
$activeDonors = $pdo->query("SELECT COUNT(*) FROM donors WHERE is_available = 1")->fetchColumn() ?: 0;

$totalRequests = $pdo->query("SELECT COUNT(*) FROM blood_requests")->fetchColumn() ?: 0;
$openRequests = $pdo->query("SELECT COUNT(*) FROM blood_requests WHERE status IN ('open', 'matched')")->fetchColumn() ?: 0;
$fulfilledRequests = $pdo->query("SELECT COUNT(*) FROM blood_requests WHERE status = 'fulfilled'")->fetchColumn() ?: 0;
$totalVolunteers = $pdo->query("SELECT COUNT(*) FROM volunteers")->fetchColumn() ?: 0;

// Recent Requests
$recentReqStmt = $pdo->query("
    SELECT * FROM blood_requests 
    ORDER BY created_at DESC 
    LIMIT 6
");
$recentRequests = $recentReqStmt->fetchAll();

// Recent Email / Notification Logs
$emailLogStmt = $pdo->query("
    SELECT * FROM email_logs 
    ORDER BY created_at DESC 
    LIMIT 6
");
$recentLogs = $emailLogStmt->fetchAll();
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-warning text-dark px-2 py-1"><i class="bi bi-shield-lock-fill me-1"></i>কমিউনিটি অ্যাডমিন</span>
                <h3 class="fw-bold text-white mb-0">কেন্দ্রীয় প্রশাসনিক ড্যাশবোর্ড</h3>
            </div>
            <p class="text-secondary small mb-0">BMMC রক্তদান ও কমিউনিটি কার্যক্রম নিয়ন্ত্রণ প্যানেল</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>/admin/donors.php" class="btn btn-outline-light btn-sm rounded-pill">
                <i class="bi bi-people me-1"></i> সকল ডোনার তালিকা
            </a>
            <a href="<?= BASE_URL ?>/admin/requests.php" class="btn btn-outline-danger btn-sm rounded-pill">
                <i class="bi bi-droplet me-1"></i> সকল রক্তের আবেদন
            </a>
        </div>
    </div>

    <!-- 8 Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="glass-card p-3 text-center">
                <span class="text-secondary small">মোট ডোনার</span>
                <h3 class="text-white fw-bold mb-0"><?= to_bangla_number($totalDonors) ?></h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="glass-card p-3 text-center border-info border-opacity-30">
                <span class="text-info small"><i class="bi bi-compass me-1"></i>মেরিনার্স ডোনার</span>
                <h3 class="text-info fw-bold mb-0"><?= to_bangla_number($marinerDonors) ?></h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="glass-card p-3 text-center border-success border-opacity-30">
                <span class="text-success small">প্রস্তুত (Available)</span>
                <h3 class="text-success fw-bold mb-0"><?= to_bangla_number($activeDonors) ?></h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="glass-card p-3 text-center border-warning border-opacity-30">
                <span class="text-warning small">বিশ্রামে (Resting)</span>
                <h3 class="text-warning fw-bold mb-0"><?= to_bangla_number($restingDonors) ?></h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="glass-card p-3 text-center border-danger border-opacity-30">
                <span class="text-danger small">সক্রিয় রক্তের আবেদন</span>
                <h3 class="text-danger fw-bold mb-0"><?= to_bangla_number($openRequests) ?></h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="glass-card p-3 text-center">
                <span class="text-secondary small">মোট আবেদন</span>
                <h3 class="text-white fw-bold mb-0"><?= to_bangla_number($totalRequests) ?></h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="glass-card p-3 text-center border-success border-opacity-30">
                <span class="text-success small">সফল রক্তদান</span>
                <h3 class="text-success fw-bold mb-0"><?= to_bangla_number($fulfilledRequests) ?></h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="glass-card p-3 text-center">
                <span class="text-secondary small">ভলান্টিয়ার আবেদন</span>
                <h3 class="text-white fw-bold mb-0"><?= to_bangla_number($totalVolunteers) ?></h3>
            </div>
        </div>
    </div>

    <!-- Active Requests Grid -->
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="glass-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-white mb-0"><i class="bi bi-activity text-danger me-2"></i>সাম্প্রতিক রক্তের আবেদনসমূহ</h5>
                    <a href="<?= BASE_URL ?>/admin/requests.php" class="text-info small text-decoration-none">সবগুলো দেখুন →</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle small mb-0">
                        <thead>
                            <tr class="border-bottom border-secondary text-secondary">
                                <th>রোগী</th>
                                <th>গ্রুপ</th>
                                <th>হাসপাতাল / এলাকা</th>
                                <th>জরুরিতা</th>
                                <th>অবস্থা</th>
                                <th>অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentRequests as $req): ?>
                                <tr class="border-bottom border-secondary border-opacity-10">
                                    <td class="fw-bold text-white"><?= htmlspecialchars($req['patient_name']) ?></td>
                                    <td><span class="badge bg-danger"><?= htmlspecialchars($req['blood_group']) ?></span></td>
                                    <td class="text-secondary"><?= htmlspecialchars($req['hospital']) ?>, <?= htmlspecialchars($req['district']) ?></td>
                                    <td><?= get_urgency_badge($req['urgency']) ?></td>
                                    <td><?= get_status_badge($req['status']) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/blood_request_detail.php?id=<?= $req['id'] ?>" class="btn btn-outline-info btn-sm py-0 px-2" title="বিস্তারিত">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Notification / Email Activity Logs -->
        <div class="col-lg-5">
            <div class="glass-card p-4 h-100">
                <h5 class="fw-bold text-white mb-3"><i class="bi bi-envelope-paper text-info me-2"></i>সাম্প্রতিক নোটিফিকেশন লগ</h5>
                
                <?php if (empty($recentLogs)): ?>
                    <p class="text-secondary small text-center py-4">কোনো নোটিফিকেশন লগ পাওয়া যায়নি।</p>
                <?php else: ?>
                    <div class="list-group list-group-flush bg-transparent">
                        <?php foreach ($recentLogs as $log): ?>
                            <div class="list-group-item bg-transparent text-light border-secondary border-opacity-15 px-0 py-2">
                                <div class="d-flex justify-content-between align-items-start">
                                    <strong class="text-info small"><?= htmlspecialchars($log['recipient_email']) ?></strong>
                                    <span class="badge bg-secondary" style="font-size: 0.7rem;"><?= htmlspecialchars($log['status']) ?></span>
                                </div>
                                <div class="text-secondary" style="font-size: 0.75rem;"><?= htmlspecialchars($log['subject']) ?></div>
                                <small class="text-muted" style="font-size: 0.7rem;"><?= $log['created_at'] ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
