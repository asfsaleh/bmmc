<?php
$pageTitle = 'ভলান্টিয়ার তালিকা — BMMC Admin';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();
$pdo = DB::getConnection();

$stmt = $pdo->query("SELECT * FROM volunteers ORDER BY created_at DESC");
$volunteers = $stmt->fetchAll();
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-white mb-1"><i class="bi bi-people-fill text-warning me-2"></i>নিবন্ধিত ভলান্টিয়ার আবেদনসমূহ</h3>
            <p class="text-secondary small mb-0">কমিউনিটি সেবায় আগ্রহীদের পূর্ণাঙ্গ তালিকা: <?= to_bangla_number(count($volunteers)) ?> জন</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/dashboard.php" class="btn btn-outline-light btn-sm rounded-pill">
            ← ড্যাশবোর্ডে ফিরে যান
        </a>
    </div>

    <div class="glass-card p-4">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle small mb-0">
                <thead>
                    <tr class="border-bottom border-secondary text-secondary">
                        <th>নাম ও মোবাইল</th>
                        <th>ইমেইল</th>
                        <th>পরিচয় ও পদবী</th>
                        <th>CDC / SID</th>
                        <th>কাজের ক্ষেত্র</th>
                        <th>বার্তা / অভিজ্ঞতা</th>
                        <th>আবেদনের তারিখ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($volunteers)): ?>
                        <tr><td colspan="7" class="text-center py-4 text-secondary">কোনো ভলান্টিয়ার আবেদন জমা পড়েনি।</td></tr>
                    <?php else: ?>
                        <?php foreach ($volunteers as $v): ?>
                            <tr class="border-bottom border-secondary border-opacity-15">
                                <td>
                                    <strong class="text-white"><?= htmlspecialchars($v['name']) ?></strong>
                                    <a href="tel:<?= htmlspecialchars($v['phone']) ?>" class="text-info d-block text-decoration-none"><?= htmlspecialchars($v['phone']) ?></a>
                                </td>
                                <td><?= htmlspecialchars($v['email']) ?></td>
                                <td>
                                    <?php if ($v['is_mariner']): ?>
                                        <span class="mariner-badge" style="font-size: 0.72rem;">
                                            <i class="bi bi-compass"></i> <?= htmlspecialchars($v['rank_designation'] ?? 'Seafarer') ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">সাধারণ ভলান্টিয়ার</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= !empty($v['cdc_sid_no']) ? '<span class="text-warning fw-semibold">' . htmlspecialchars($v['cdc_sid_no']) . '</span>' : '<span class="text-muted">—</span>' ?>
                                </td>
                                <td><span class="text-light"><?= htmlspecialchars($v['interest_area']) ?></span></td>
                                <td class="text-secondary" style="max-width: 250px;"><?= nl2br(htmlspecialchars($v['message'] ?? '')) ?></td>
                                <td><?= format_date_bn($v['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
