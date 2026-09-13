<?php
/**
 * Donation Completion and Resting Period Controller
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$action = $_GET['action'] ?? '';
$pdo = DB::getConnection();

// 1. Donor records own donation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'complete_donor_self') {
    require_login();
    if (!validate_csrf()) {
        set_flash('danger', 'নিরাপত্তা টোকেন সঠিক নয়।');
        header('Location: ' . BASE_URL . '/donor_dashboard.php');
        exit;
    }

    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT id FROM donors WHERE user_id = ?");
    $stmt->execute([$userId]);
    $donor = $stmt->fetch();

    if (!$donor) {
        set_flash('danger', 'রক্তদাতা প্রোফাইল পাওয়া যায়নি।');
        header('Location: ' . BASE_URL . '/donor_dashboard.php');
        exit;
    }

    $donorId = $donor['id'];
    $donationDate = !empty($_POST['donation_date']) ? $_POST['donation_date'] : date('Y-m-d');
    $hospital = sanitize($_POST['hospital'] ?? '');
    $notes = sanitize($_POST['notes'] ?? '');

    // Calculate resting until (4 months = 120 days)
    $restingUntil = date('Y-m-d', strtotime("+120 days", strtotime($donationDate)));

    try {
        $pdo->beginTransaction();

        // Insert into donation history
        $insHist = $pdo->prepare("
            INSERT INTO donation_history (donor_id, donation_date, hospital, notes, resting_until)
            VALUES (?, ?, ?, ?, ?)
        ");
        $insHist->execute([$donorId, $donationDate, $hospital, $notes, $restingUntil]);

        // Update donor table
        $updDonor = $pdo->prepare("
            UPDATE donors 
            SET is_available = 0, 
                last_donation_date = ?, 
                next_available_date = ?, 
                total_donations = total_donations + 1
            WHERE id = ?
        ");
        $updDonor->execute([$donationDate, $restingUntil, $donorId]);

        $pdo->commit();

        set_flash('success', 'রক্তদানের তথ্য সফলভাবে সংরক্ষিত হয়েছে। আপনার শারীরিক সুস্থতা রক্ষার্থে পরবর্তী ১২০ দিন (৪ মাস) বিশ্রাম সময়কাল কার্যকর থাকবে।');
        header('Location: ' . BASE_URL . '/donor_dashboard.php');
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        error_log("Donation record error: " . $e->getMessage());
        set_flash('danger', 'রেকর্ড সংরক্ষণে ত্রুটি: ' . $e->getMessage());
        header('Location: ' . BASE_URL . '/donor_dashboard.php');
        exit;
    }
}

// 2. Mark a Blood Request as fulfilled
if ($action === 'mark_fulfilled') {
    require_login();
    $requestId = (int)($_GET['id'] ?? 0);

    $upd = $pdo->prepare("UPDATE blood_requests SET status = 'fulfilled' WHERE id = ?");
    $upd->execute([$requestId]);

    set_flash('success', 'আবেদনটি সফলভাবে সম্পন্ন (Fulfilled) হিসেবে চিহ্নিত করা হয়েছে।');
    header('Location: ' . BASE_URL . '/blood_request_detail.php?id=' . $requestId);
    exit;
}

header('Location: ' . BASE_URL . '/index.php');
exit;
