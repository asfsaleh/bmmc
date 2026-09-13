<?php
$pageTitle = 'রক্তদাতা ব্যবস্থাপনা — BMMC Admin';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/districts.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();
$pdo = DB::getConnection();

// Filters
$bg = sanitize($_GET['blood_group'] ?? '');
$district = sanitize($_GET['district'] ?? '');
$userType = sanitize($_GET['user_type'] ?? '');
$status = sanitize($_GET['status'] ?? '');
$search = sanitize($_GET['q'] ?? '');

$sql = "
    SELECT d.*, u.name, u.email, u.phone, u.user_type, u.cdc_sid_no, u.mariner_rank
    FROM donors d
    JOIN users u ON d.user_id = u.id
    WHERE 1=1
";
$params = [];

if (!empty($bg)) {
    $sql .= " AND d.blood_group = ?";
    $params[] = $bg;
}
if (!empty($district)) {
    $sql .= " AND d.district = ?";
    $params[] = $district;
}
if (!empty($userType)) {
    $sql .= " AND u.user_type = ?";
    $params[] = $userType;
}
if ($status === 'available') {
    $sql .= " AND d.is_available = 1";
} elseif ($status === 'resting') {
    $sql .= " AND d.is_available = 0";
}
if (!empty($search)) {
    $sql .= " AND (u.name LIKE ? OR u.phone LIKE ? OR u.cdc_sid_no LIKE ? OR d.area LIKE ?)";
    $params = array_merge($params, ["%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"]);
}

$sql .= " ORDER BY d.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$donors = $stmt->fetchAll();

$districtsByDivision = getBangladeshDistricts();
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-white mb-1"><i class="bi bi-people-fill text-info me-2"></i>রক্তদাতা তালিকা ও ব্যবস্থাপনা</h3>
            <p class="text-secondary small mb-0">মোট প্রাপ্ত রক্তদাতা: <?= to_bangla_number(count($donors)) ?> জন</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/dashboard.php" class="btn btn-outline-light btn-sm rounded-pill">
            ← ড্যাশবোর্ডে ফিরে যান
        </a>
    </div>

    <!-- Filters Bar -->
    <div class="glass-card p-3 mb-4">
        <form method="GET" action="<?= BASE_URL ?>/admin/donors.php" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-secondary">নাম / ফোন / CDC নম্বর</label>
                <input type="text" class="form-control form-control-sm glass-input" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="অনুসন্ধান করুন...">
            </div>
            <div class="col-md-2">
                <label class="form-label small text-secondary">ব্লাড গ্রুপ</label>
                <select class="form-select form-select-sm glass-input" name="blood_group">
                    <option value="">সব গ্রুপ</option>
                    <?php foreach (['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $g): ?>
                        <option value="<?= $g ?>" <?= $bg === $g ? 'selected' : '' ?>><?= $g ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-secondary">পরিচয়</label>
                <select class="form-select form-select-sm glass-input" name="user_type">
                    <option value="">সকল</option>
                    <option value="mariner" <?= $userType === 'mariner' ? 'selected' : '' ?>>মেরিনার (CDC)</option>
                    <option value="general" <?= $userType === 'general' ? 'selected' : '' ?>>সাধারণ নাগরিক</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-secondary">স্ট্যাটাস</label>
                <select class="form-select form-select-sm glass-input" name="status">
                    <option value="">সকল স্ট্যাটাস</option>
                    <option value="available" <?= $status === 'available' ? 'selected' : '' ?>>প্রস্তুত (Available)</option>
                    <option value="resting" <?= $status === 'resting' ? 'selected' : '' ?>>বিশ্রামে (Resting)</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-ocean btn-sm w-100"><i class="bi bi-funnel me-1"></i>ফিল্টার</button>
                <a href="<?= BASE_URL ?>/admin/donors.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>

    <!-- Donors Table -->
    <div class="glass-card p-4">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle small mb-0">
                <thead>
                    <tr class="border-bottom border-secondary text-secondary">
                        <th>গ্রুপ</th>
                        <th>রক্তদাতার নাম</th>
                        <th>পরিচয় / পদবী</th>
                        <th>মোবাইল / WhatsApp</th>
                        <th>জেলা ও এলাকা</th>
                        <th>স্ট্যাটাস</th>
                        <th>সর্বশেষ রক্তদান</th>
                        <th>মোট দান</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($donors)): ?>
                        <tr><td colspan="8" class="text-center py-4 text-secondary">কোনো রক্তদাতা পাওয়া যায়নি।</td></tr>
                    <?php else: ?>
                        <?php foreach ($donors as $d): ?>
                            <tr class="border-bottom border-secondary border-opacity-15">
                                <td><span class="badge bg-danger fs-6"><?= htmlspecialchars($d['blood_group']) ?></span></td>
                                <td>
                                    <strong class="text-white"><?= htmlspecialchars($d['name']) ?></strong>
                                    <small class="text-muted d-block"><?= htmlspecialchars($d['email']) ?></small>
                                </td>
                                <td>
                                    <?php if ($d['user_type'] === 'mariner'): ?>
                                        <span class="mariner-badge" style="font-size: 0.72rem;">
                                            <i class="bi bi-compass"></i> <?= htmlspecialchars($d['mariner_rank'] ?? 'Seafarer') ?>
                                        </span>
                                        <?php if (!empty($d['cdc_sid_no'])): ?>
                                            <small class="text-info d-block mt-1">CDC: <?= htmlspecialchars($d['cdc_sid_no']) ?></small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">সাধারণ নাগরিক</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="tel:<?= htmlspecialchars($d['phone']) ?>" class="text-info fw-semibold text-decoration-none"><?= htmlspecialchars($d['phone']) ?></a>
                                    <?php if (!empty($d['whatsapp'])): ?>
                                        <small class="text-success d-block"><i class="bi bi-whatsapp"></i> <?= htmlspecialchars($d['whatsapp']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-secondary"><?= htmlspecialchars($d['area']) ?>, <?= htmlspecialchars($d['district']) ?></td>
                                <td>
                                    <?php if ($d['is_available'] == 1): ?>
                                        <span class="badge bg-success">প্রস্তুত</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark" title="বিশ্রাম শেষ: <?= $d['next_available_date'] ?>">বিশ্রামে (Resting)</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= format_date_bn($d['last_donation_date']) ?></td>
                                <td class="text-center fw-bold text-info"><?= to_bangla_number($d['total_donations']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
