<?php
/**
 * Authentication Controller
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../classes/NotificationService.php';

$action = $_GET['action'] ?? '';
$pdo = DB::getConnection();

// 1. Password Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'login') {
    if (!validate_csrf()) {
        set_flash('danger', 'নিরাপত্তা টোকেন সঠিক নয়।');
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }

    $identifier = sanitize($_POST['identifier'] ?? ''); // Email or Phone
    $password = $_POST['password'] ?? '';

    if (empty($identifier) || empty($password)) {
        set_flash('danger', 'অনুগ্রহ করে ইমেইল/ফোন ও পাসওয়ার্ড প্রদান করুন।');
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
    $stmt->execute([$identifier, $identifier]);
    $user = $stmt->fetch();

    if ($user && !empty($user['password_hash']) && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        set_flash('success', "স্বাগতম, {$user['name']}! সফলভাবে লগইন সম্পন্ন হয়েছে।");
        
        if ($user['role'] === 'admin' || in_array(strtolower($user['email'] ?? ''), array_map('strtolower', FIXED_ADMINS))) {
            header('Location: ' . BASE_URL . '/admin/dashboard.php');
        } else {
            header('Location: ' . BASE_URL . '/donor_dashboard.php');
        }
        exit;
    } else {
        set_flash('danger', 'ইমেইল/ফোন অথবা পাসওয়ার্ড ভুল হয়েছে।');
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}

// 2. Request OTP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'send_otp') {
    $identifier = sanitize($_POST['identifier'] ?? '');

    if (empty($identifier)) {
        echo json_encode(['status' => false, 'message' => 'ইমেইল বা ফোন প্রদান করুন']);
        exit;
    }

    // Generate 6 digit OTP
    $code = str_pad((string)random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    // Insert OTP
    $ins = $pdo->prepare("INSERT INTO otp_codes (identifier, code, expires_at) VALUES (?, ?, ?)");
    $ins->execute([$identifier, $code, $expiresAt]);

    // Send OTP via email if email
    if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
        $notifier = new NotificationService();
        $notifier->sendOTP($identifier, $code);
    }

    set_flash('info', "আপনার ভেরিফিকেশন কোড পাঠানো হয়েছে (টেস্টিং ওটিপি: {$code})");
    header('Location: ' . BASE_URL . '/login.php?otp_sent=1&identifier=' . urlencode($identifier));
    exit;
}

// 3. Verify OTP & Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'verify_otp') {
    $identifier = sanitize($_POST['identifier'] ?? '');
    $code = trim($_POST['otp'] ?? '');

    $stmt = $pdo->prepare("
        SELECT * FROM otp_codes 
        WHERE identifier = ? AND code = ? AND is_used = 0 AND expires_at >= NOW() 
        ORDER BY id DESC LIMIT 1
    ");
    $stmt->execute([$identifier, $code]);
    $otpRecord = $stmt->fetch();

    if ($otpRecord) {
        // Mark OTP as used
        $pdo->prepare("UPDATE otp_codes SET is_used = 1 WHERE id = ?")->execute([$otpRecord['id']]);

        // Find or create user
        $userStmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
        $userStmt->execute([$identifier, $identifier]);
        $user = $userStmt->fetch();

        if (!$user) {
            $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);
            $email = $isEmail ? $identifier : null;
            $phone = !$isEmail ? $identifier : '01700000000';
            $ins = $pdo->prepare("INSERT INTO users (name, email, phone, role) VALUES ('ইউজার', ?, ?, 'user')");
            $ins->execute([$email, $phone]);
            $userId = (int)$pdo->lastInsertId();
        } else {
            $userId = $user['id'];
        }

        $_SESSION['user_id'] = $userId;
        set_flash('success', 'ওটিপি ভেরিফিকেশন সফল হয়েছে!');
        header('Location: ' . BASE_URL . '/donor_dashboard.php');
        exit;
    } else {
        set_flash('danger', 'ভুল অথবা মেয়াদোত্তীর্ণ ওটিপি (OTP)।');
        header('Location: ' . BASE_URL . '/login.php?otp_sent=1&identifier=' . urlencode($identifier));
        exit;
    }
}

// 4. Volunteer Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'volunteer_register') {
    if (!validate_csrf()) {
        set_flash('danger', 'নিরাপত্তা টোকেন সঠিক নয়।');
        header('Location: ' . BASE_URL . '/volunteer_register.php');
        exit;
    }

    $name = sanitize($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $phone = sanitize($_POST['phone'] ?? '');
    $userType = sanitize($_POST['user_type'] ?? 'general');
    $isMariner = ($userType === 'mariner') ? 1 : 0;
    $cdcSid = $isMariner ? sanitize($_POST['cdc_sid_no'] ?? '') : null;
    $rank = $isMariner ? sanitize($_POST['mariner_rank'] ?? '') : null;
    $interest = isset($_POST['interest']) ? implode(', ', array_map('sanitize', $_POST['interest'])) : '';
    $message = sanitize($_POST['message'] ?? '');

    try {
        $ins = $pdo->prepare("
            INSERT INTO volunteers (name, email, phone, is_mariner, cdc_sid_no, rank_designation, interest_area, message)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $ins->execute([$name, $email, $phone, $isMariner, $cdcSid, $rank, $interest, $message]);

        set_flash('success', 'ধন্যবাদ! BMMC ভলান্টিয়ার হিসেবে আপনার আবেদন সফলভাবে গৃহীত হয়েছে। আমাদের সমন্বয়ক টিম আপনার সাথে দ্রুত যোগাযোগ করবে।');
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    } catch (Exception $e) {
        set_flash('danger', 'আবেদন সংরক্ষণে ত্রুটি: ' . $e->getMessage());
        header('Location: ' . BASE_URL . '/volunteer_register.php');
        exit;
    }
}

header('Location: ' . BASE_URL . '/index.php');
exit;
