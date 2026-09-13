<?php
/**
 * Blood Request Controller
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../classes/MatchingService.php';

$action = $_GET['action'] ?? '';
$pdo = DB::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create') {
    if (!validate_csrf()) {
        set_flash('danger', 'নিরাপত্তা ত্রুটি (CSRF Token Invalid)।');
        header('Location: ' . BASE_URL . '/blood_request.php');
        exit;
    }

    $patientName  = sanitize($_POST['patient_name'] ?? '');
    $bloodGroup   = sanitize($_POST['blood_group'] ?? '');
    $bagsNeeded   = max(1, (int)($_POST['bags_needed'] ?? 1));
    $hospital     = sanitize($_POST['hospital'] ?? '');
    $district     = sanitize($_POST['district'] ?? '');
    $area         = sanitize($_POST['area'] ?? '');
    $urgency      = sanitize($_POST['urgency'] ?? 'urgent');
    $neededBy     = !empty($_POST['needed_by']) ? $_POST['needed_by'] : null;
    $contactName  = sanitize($_POST['contact_name'] ?? '');
    $contactPhone = sanitize($_POST['contact_phone'] ?? '');
    $contactEmail = filter_var($_POST['contact_email'] ?? '', FILTER_VALIDATE_EMAIL);
    $notes        = sanitize($_POST['notes'] ?? '');
    $requesterId  = is_logged_in() ? $_SESSION['user_id'] : null;

    if (empty($patientName) || empty($bloodGroup) || empty($hospital) || empty($district) || empty($area) || empty($contactPhone)) {
        set_flash('danger', 'অনুগ্রহ করে সকল প্রয়োজনীয় তথ্য সঠিকভাবে পূরণ করুন।');
        header('Location: ' . BASE_URL . '/blood_request.php');
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO blood_requests (
                requester_id, patient_name, blood_group, bags_needed, hospital, 
                district, area, urgency, status, needed_by, contact_name, 
                contact_phone, contact_email, notes
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'open', ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $requesterId, $patientName, $bloodGroup, $bagsNeeded, $hospital,
            $district, $area, $urgency, $neededBy, $contactName,
            $contactPhone, $contactEmail, $notes
        ]);
        $requestId = (int)$pdo->lastInsertId();

        // Trigger Automated Matching & Notification
        $matchingService = new MatchingService();
        $matchResult = $matchingService->matchAndNotify($requestId);

        $notified = $matchResult['notified_count'] ?? 0;
        set_flash('success', "আপনার রক্তের আবেদনটি সফলভাবে জমা হয়েছে। এলাকা ও গ্রুপের সাথে মিল থাকায় {$notified} জন রক্তদাতার কাছে স্বয়ংক্রিয় বিজ্ঞপ্তি পাঠানো হয়েছে।");
        header('Location: ' . BASE_URL . '/blood_request_detail.php?id=' . $requestId);
        exit;

    } catch (Exception $e) {
        error_log("Blood request error: " . $e->getMessage());
        set_flash('danger', 'আবেদন সংরক্ষণে ত্রুটি: ' . $e->getMessage());
        header('Location: ' . BASE_URL . '/blood_request.php');
        exit;
    }
}

// Cancel Request
if ($action === 'cancel' && is_logged_in()) {
    $requestId = (int)($_GET['id'] ?? 0);
    $upd = $pdo->prepare("UPDATE blood_requests SET status = 'cancelled' WHERE id = ?");
    $upd->execute([$requestId]);
    set_flash('info', 'আবেদনটি বাতিল করা হয়েছে।');
    header('Location: ' . BASE_URL . '/blood_request_detail.php?id=' . $requestId);
    exit;
}

header('Location: ' . BASE_URL . '/index.php');
exit;
