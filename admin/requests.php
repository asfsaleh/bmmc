<?php
$pageTitle = 'রক্তের আবেদন ব্যবস্থাপনা — BMMC Admin';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../classes/MatchingService.php';

require_admin();
$pdo = DB::getConnection();

// Handle manual re-matching action
if (isset($_GET['action']) && $_GET['action'] === 'rematch' && isset($_GET['id'])) {
    $reqId = (int)$_GET['id'];
    $matchingService = new MatchingService();
    $res = $matchingService->matchAndNotify($reqId);
    $cnt = $res['notified_count'] ?? 0;
    set_flash('success', "পুনরায় অনুসন্ধান সম্পন্ন। নতুন আরও {$cnt} জন উপযুক্ত রক্তদাতার নিকট বিজ্ঞপ্তি পাঠানো হয়েছে।");
    header('Location: ' . BASE_URL . '/admin/requests.php');
    exit;
}

// Filters
$status = sanitize($_GET['status'] ?? '');
$urgency = sanitize($_GET['urgency'] ?? '');
$bg = sanitize($_GET['blood_group'] ?? '');

$sql = "SELECT * FROM blood_requests WHERE 1=1";
$params = [];

if (!empty($status)) {
    $sql .= " AND status = ?";
    $params[] = $status;
}
if (!empty($urgency)) {
    $sql .= " AND urgency = ?";
    $params[] = $urgency;
}
if (!empty($bg)) {
    $sql .= " AND blood_group = ?";
    $params[] = $bg;
}

$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$requests = $stmt->fetchAll();
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-white mb-1"><i class="bi bi-droplet-half text-danger me-2"></i>রক্তের আবেদনসমূহ</h3>
            <p class="text-secondary small mb-0">মোট আবেদন: <?= to_bangla_number(count($requests)) ?> টি</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>/blood_request.php" class="btn btn-blood btn-sm rounded-pill px-3">
                <i class="bi bi-plus-lg me-1"></i> নতুন আবেদন তৈরি
            </a>
            <a href="<?= BASE_URL ?>/admin/dashboard.php" class="btn btn-outline-light btn-sm rounded-pill">
                ← ড্যাশবোর্ড
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card p-3 mb-4">
        <form method="GET" action="<?= BASE_URL ?>/admin/requests.php" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-secondary">অবস্থা (Status)</label>
                <select class="form-select form-select-sm glass-input" name="status">
                    <option value="">সকল অবস্থা</option>
                    <option value="open" <?= $status === 'open' ? 'selected' : '' ?>>চলমান (Open)</option>
                    <option value="matched" <?= $status === 'matched' ? 'selected' : '' ?>>ম্যাচড (Matched)</option>
                    <option value="fulfilled" <?= $status === 'fulfilled' ? 'selected' : '' ?>>সম্পন্ন (Fulfilled)</option>
                    <option value="cancelled" <?= $status === 'cancelled' ? 'selected' : '' ?>>বাতিল (Cancelled)</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-secondary">জরুরিতার মাত্রা</label>
                <select class="form-select form-select-sm glass-input" name="urgency">
                    <option value="">সকল মাত্রা</option>
                    <option value="emergency" <?= $urgency === 'emergency' ? 'selected' : '' ?>>অতি জরুরি (Emergency)</option>
                    <option value="urgent" <?= $urgency === 'urgent' ? 'selected' : '' ?>>জরুরি (Urgent)</option>
                    <option value="normal" <?= $urgency === 'normal' ? 'selected' : '' ?>>সাধারণ (Normal)</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-secondary">ব্লাড গ্রুপ</label>
                <select class="form-select form-select-sm glass-input" name="blood_group">
                    <option value="">সব গ্রুপ</option>
                    <?php foreach (['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $g): ?>
                        <option value="<?= $g ?>" <?= $bg === $g ? 'selected' : '' ?>><?= $g ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-ocean btn-sm w-100"><i class="bi bi-funnel me-1"></i>ফিল্টার</button>
                <a href="<?= BASE_URL ?>/admin/requests.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>

    <!-- Requests Table -->
    <div class="glass-card p-4">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle small mb-0">
                <thead>
                    <tr class="border-bottom border-secondary text-secondary">
                        <th>গ্রুপ</th>
                        <th>রোগীর নাম</th>
                        <th>হাসপাতাল ও এলাকা</th>
                        <th>প্রয়োজন</th>
                        <th>জরুরিতা</th>
                        <th>যোগাযোগ</th>
                        <th>অবস্থা</th>
                        <th>তারিখ</th>
                        <th>অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($requests)): ?>
                        <tr><td colspan="9" class="text-center py-4 text-secondary">কোনো রক্তের আবেদন পাওয়া যায়নি।</td></tr>
                    <?php else: ?>
                        <?php foreach ($requests as $r): ?>
                            <tr class="border-bottom border-secondary border-opacity-15">
                                <td><span class="badge bg-danger fs-6"><?= htmlspecialchars($r['blood_group']) ?></span></td>
                                <td><strong class="text-white"><?= htmlspecialchars($r['patient_name']) ?></strong></td>
                                <td class="text-secondary"><?= htmlspecialchars($r['hospital']) ?>, <?= htmlspecialchars($r['area']) ?></td>
                                <td><?= to_bangla_number($r['bags_needed']) ?> ব্যাগ</td>
                                <td><?= get_urgency_badge($r['urgency']) ?></td>
                                <td>
                                    <a href="tel:<?= htmlspecialchars($r['contact_phone']) ?>" class="text-info text-decoration-none">
                                        <?= htmlspecialchars($r['contact_phone']) ?>
                                    </a>
                                </td>
                                <td><?= get_status_badge($r['status']) ?></td>
                                <td><?= format_date_bn($r['created_at']) ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= BASE_URL ?>/blood_request_detail.php?id=<?= $r['id'] ?>" class="btn btn-outline-info" title="বিস্তারিত">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <?php if ($r['status'] !== 'fulfilled'): ?>
                                            <a href="<?= BASE_URL ?>/admin/requests.php?action=rematch&id=<?= $r['id'] ?>" class="btn btn-outline-warning" title="পুনরায় ডোনারদের নোটিফাই করুন">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
