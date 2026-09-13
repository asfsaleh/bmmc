<?php
$pageTitle = 'রক্তদানের সম্মতি — BMMC';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/classes/NotificationService.php';

$pdo = DB::getConnection();
$token = trim($_GET['token'] ?? '');
$action = strtolower(trim($_GET['action'] ?? ''));

// Validate token
$stmt = $pdo->prepare("
    SELECT dr.*, 
           d.id AS donor_id, d.blood_group, d.district AS donor_district, d.area AS donor_area, d.whatsapp,
           u.name, u.email, u.phone, u.user_type, u.mariner_rank,
           br.patient_name, br.hospital, br.district AS req_district, br.area AS req_area, 
           br.urgency, br.needed_by, br.contact_name, br.contact_phone, br.contact_email, br.bags_needed, br.id AS req_id
    FROM donor_responses dr
    JOIN donors d ON dr.donor_id = d.id
    JOIN users u ON d.user_id = u.id
    JOIN blood_requests br ON dr.request_id = br.id
    WHERE dr.response_token = ?
");
$stmt->execute([$token]);
$response = $stmt->fetch();

require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <?php if (!$response): ?>
                <div class="glass-card p-5 text-center">
                    <i class="bi bi-exclamation-octagon-fill text-danger fs-1 mb-3 d-block"></i>
                    <h4 class="text-white fw-bold">অনুরোধের লিংকটি সঠিক নয় বা মেয়াদোত্তীর্ণ</h4>
                    <p class="text-secondary small">লিংকটি হয়তো ইতিমধ্যে ব্যবহৃত হয়েছে অথবা পরিবর্তিত হয়েছে।</p>
                    <a href="<?= BASE_URL ?>/index.php" class="btn btn-ocean rounded-pill px-4 mt-3">হোমপেজে ফিরে যান</a>
                </div>

            <?php else: 
                $donorData = [
                    'id' => $response['donor_id'],
                    'name' => $response['name'],
                    'email' => $response['email'],
                    'phone' => $response['phone'],
                    'whatsapp' => $response['whatsapp'],
                    'blood_group' => $response['blood_group'],
                    'district' => $response['donor_district'],
                    'area' => $response['donor_area'],
                    'user_type' => $response['user_type'],
                    'mariner_rank' => $response['mariner_rank']
                ];

                $requestData = [
                    'patient_name' => $response['patient_name'],
                    'hospital' => $response['hospital'],
                    'district' => $response['req_district'],
                    'area' => $response['req_area'],
                    'contact_name' => $response['contact_name'],
                    'contact_phone' => $response['contact_phone'],
                    'contact_email' => $response['contact_email'],
                ];

                // Process Action if clicked
                if ($action === 'agree' && $response['status'] !== 'agreed'):
                    $upd = $pdo->prepare("UPDATE donor_responses SET status = 'agreed', responded_at = NOW() WHERE id = ?");
                    $upd->execute([$response['id']]);

                    // Update blood request status
                    $pdo->prepare("UPDATE blood_requests SET status = 'matched' WHERE id = ?")->execute([$response['req_id']]);

                    // Send mutual contact info emails
                    $notifier = new NotificationService();
                    $notifier->sendMutualContactSharing($donorData, $requestData);
                    $response['status'] = 'agreed';
                elseif ($action === 'decline' && $response['status'] === 'notified'):
                    $upd = $pdo->prepare("UPDATE donor_responses SET status = 'declined', responded_at = NOW() WHERE id = ?");
                    $upd->execute([$response['id']]);
                    $response['status'] = 'declined';
                endif;
            ?>

                <?php if ($response['status'] === 'agreed'): ?>
                    <!-- Success Agreement Card -->
                    <div class="glass-card p-4 p-md-5 text-center border-success border-opacity-50">
                        <div style="width: 70px; height: 70px; margin: 0 auto 20px; background: rgba(16, 185, 129, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #10b981;">
                            <i class="bi bi-heart-fill text-danger fs-2"></i>
                        </div>
                        <h3 class="fw-bold text-white mb-2">মানবতার মহান ব্রত! আপনাকে আন্তরিক ধন্যবাদ</h3>
                        <p class="text-secondary small mb-4">
                            সম্মানিত রক্তদাতা <strong><?= htmlspecialchars($response['name']) ?></strong>, আপনার সম্মতির প্রেক্ষিতে রোগীর প্রতিনিধির কাছে আপনার ফোন নম্বর প্রেরণ করা হয়েছে। আপনি সরাসরি নিচের নম্বরে যোগাযোগ করতে পারেন:
                        </p>

                        <div class="p-3 rounded-3 text-start mb-4" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1);">
                            <h6 class="text-info fw-bold mb-3"><i class="bi bi-hospital me-1"></i>রোগীর ও হাসপাতালের বিস্তারিত:</h6>
                            <table class="table table-dark table-borderless small mb-0">
                                <tr><td class="text-secondary" style="width: 35%;">রোগীর নাম:</td><td class="fw-bold"><?= htmlspecialchars($response['patient_name']) ?></td></tr>
                                <tr><td class="text-secondary">হাসপাতাল:</td><td><?= htmlspecialchars($response['hospital']) ?></td></tr>
                                <tr><td class="text-secondary">স্থান:</td><td><?= htmlspecialchars($response['req_area']) ?>, <?= htmlspecialchars($response['req_district']) ?></td></tr>
                                <tr><td class="text-secondary">প্রয়োজনীয় রক্ত:</td><td class="text-danger fw-bold"><?= htmlspecialchars($response['blood_group']) ?> (<?= to_bangla_number($response['bags_needed']) ?> ব্যাগ)</td></tr>
                                <tr><td class="text-secondary">যোগাযোগকারী:</td><td><?= htmlspecialchars($response['contact_name']) ?></td></tr>
                            </table>
                        </div>

                        <a href="tel:<?= htmlspecialchars($response['contact_phone']) ?>" class="btn btn-success btn-lg rounded-pill px-5 py-3 fw-bold mb-3">
                            <i class="bi bi-telephone-fill me-2"></i> রোগীর স্বজনকে কল করুন: <?= htmlspecialchars($response['contact_phone']) ?>
                        </a>
                        
                        <p class="text-muted small">
                            রক্তদান সম্পন্ন হলে আপনার ড্যাশবোর্ডে রক্তদান নিশ্চিত করুন যাতে আপনার ৪ মাসের বিশ্রাম বিরতি চালু হতে পারে।
                        </p>
                    </div>

                <?php elseif ($response['status'] === 'declined'): ?>
                    <!-- Declined Card -->
                    <div class="glass-card p-5 text-center">
                        <i class="bi bi-emoji-smile text-secondary fs-1 mb-3 d-block"></i>
                        <h4 class="text-white fw-bold">আপনার অপারগতা গৃহীত হয়েছে</h4>
                        <p class="text-secondary small">
                            কোনো অসুবিধা নেই। শারীরিক সুস্থতা ও পারিপার্শ্বিক অবস্থা সবার আগে। অন্য কোনো সময় অন্য কারো বিপদে আপনার পাশে থাকার অপেক্ষায় রইলাম।
                        </p>
                        <a href="<?= BASE_URL ?>/index.php" class="btn btn-outline-light rounded-pill px-4 mt-3">হোমপেজে যান</a>
                    </div>

                <?php else: ?>
                    <!-- Pending Decision Card -->
                    <div class="glass-blood-card p-4 p-md-5 text-center">
                        <div class="blood-pill mx-auto mb-3"><?= htmlspecialchars($response['blood_group']) ?></div>
                        <h3 class="fw-bold text-white mb-2">জরুরি রক্তদানের অনুরোধ</h3>
                        <p class="text-secondary small mb-4">
                            রোগী <strong><?= htmlspecialchars($response['patient_name']) ?></strong>-এর জন্য জরুরি ভিত্তিতে <strong><?= htmlspecialchars($response['blood_group']) ?></strong> রক্ত প্রয়োজন।
                        </p>

                        <div class="p-3 rounded-3 text-start mb-4" style="background: rgba(0,0,0,0.3);">
                            <p class="mb-1 small text-light"><strong>হাসপাতাল:</strong> <?= htmlspecialchars($response['hospital']) ?></p>
                            <p class="mb-1 small text-light"><strong>স্থান:</strong> <?= htmlspecialchars($response['req_area']) ?>, <?= htmlspecialchars($response['req_district']) ?></p>
                            <p class="mb-0 small text-light"><strong>জরুরিতা:</strong> <?= strtoupper($response['urgency']) ?></p>
                        </div>

                        <div class="d-flex justify-content-center gap-3">
                            <a href="<?= BASE_URL ?>/respond.php?token=<?= $token ?>&action=agree" class="btn btn-blood btn-lg rounded-pill px-4 py-2 fw-bold">
                                ✅ হ্যাঁ, আমি রক্ত দিতে রাজি
                            </a>
                            <a href="<?= BASE_URL ?>/respond.php?token=<?= $token ?>&action=decline" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                                ❌ এখন সম্ভব নয়
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
