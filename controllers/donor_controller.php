<?php
/**
 * Donor Controller
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../classes/NotificationService.php';

$action = $_GET['action'] ?? '';
$pdo = DB::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'register') {
    if (!validate_csrf()) {
        set_flash('danger', 'নিরাপত্তা ত্রুটি (CSRF Token Invalid)। পুনরায় চেষ্টা করুন।');
        header('Location: ' . BASE_URL . '/donor_register.php');
        exit;
    }

    $name = sanitize($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $phone = sanitize($_POST['phone'] ?? '');
    $whatsapp = sanitize($_POST['whatsapp'] ?? '');
    $bloodGroup = sanitize($_POST['blood_group'] ?? '');
    $district = sanitize($_POST['district'] ?? '');
    $area = sanitize($_POST['area'] ?? '');
    $userType = sanitize($_POST['user_type'] ?? 'general');
    $cdcSid = ($userType === 'mariner') ? sanitize($_POST['cdc_sid_no'] ?? '') : null;
    $marinerRank = ($userType === 'mariner') ? sanitize($_POST['mariner_rank'] ?? '') : null;
    $lastDonationDate = !empty($_POST['last_donation_date']) ? $_POST['last_donation_date'] : null;
    $password = $_POST['password'] ?? '';

    // Validation
    if (empty($name) || empty($phone) || empty($email) || empty($bloodGroup) || empty($district) || empty($area)) {
        set_flash('danger', 'অনুগ্রহ করে সকল প্রয়োজনীয় তথ্য সঠিকভাবে পূরণ করুন।');
        header('Location: ' . BASE_URL . '/donor_register.php');
        exit;
    }

    if ($userType === 'mariner' && empty($cdcSid)) {
        set_flash('danger', 'মেরিনারদের ক্ষেত্রে সিডিসি / এসআইডি নম্বর প্রদান বাধ্যতামূলক।');
        header('Location: ' . BASE_URL . '/donor_register.php');
        exit;
    }

    try {
        $pdo->beginTransaction();

        // 1. Resolve or Create User
        $userId = null;
        if (is_logged_in()) {
            $userId = $_SESSION['user_id'];
            // Update user profile info
            $updUser = $pdo->prepare("
                UPDATE users 
                SET name = ?, email = ?, phone = ?, user_type = ?, cdc_sid_no = ?, mariner_rank = ?, role = 'donor'
                WHERE id = ?
            ");
            $updUser->execute([$name, $email, $phone, $userType, $cdcSid, $marinerRank, $userId]);
        } else {
            // Check if phone or email already registered
            $chk = $pdo->prepare("SELECT id FROM users WHERE phone = ? OR email = ?");
            $chk->execute([$phone, $email]);
            $existing = $chk->fetch();

            if ($existing) {
                $userId = $existing['id'];
                $updUser = $pdo->prepare("
                    UPDATE users 
                    SET name = ?, user_type = ?, cdc_sid_no = ?, mariner_rank = ?, role = 'donor'
                    WHERE id = ?
                ");
                $updUser->execute([$name, $userType, $cdcSid, $marinerRank, $userId]);
            } else {
                $passwordHash = !empty($password) ? password_hash($password, PASSWORD_BCRYPT) : null;
                $insUser = $pdo->prepare("
                    INSERT INTO users (name, email, phone, password_hash, role, user_type, cdc_sid_no, mariner_rank)
                    VALUES (?, ?, ?, ?, 'donor', ?, ?, ?)
                ");
                $insUser->execute([$name, $email, $phone, $passwordHash, $userType, $cdcSid, $marinerRank]);
                $userId = (int)$pdo->lastInsertId();
            }

            // Log the user in
            $_SESSION['user_id'] = $userId;
        }

        // 2. Calculate Availability & Resting Period
        $isAvailable = 1;
        $nextAvailableDate = null;
        if ($lastDonationDate) {
            $lastTime = strtotime($lastDonationDate);
            $restingUntilTime = strtotime("+" . RESTING_PERIOD_DAYS . " days", $lastTime);
            $nextAvailableDate = date('Y-m-d', $restingUntilTime);
            if ($restingUntilTime > time()) {
                $isAvailable = 0; // Donor is currently resting
            }
        }

        // 3. Upsert Donor Profile
        $chkDonor = $pdo->prepare("SELECT id FROM donors WHERE user_id = ?");
        $chkDonor->execute([$userId]);
        $existingDonor = $chkDonor->fetch();

        if ($existingDonor) {
            $updDonor = $pdo->prepare("
                UPDATE donors 
                SET blood_group = ?, district = ?, area = ?, whatsapp = ?, is_available = ?, last_donation_date = ?, next_available_date = ?
                WHERE id = ?
            ");
            $updDonor->execute([$bloodGroup, $district, $area, $whatsapp, $isAvailable, $lastDonationDate, $nextAvailableDate, $existingDonor['id']]);
        } else {
            $insDonor = $pdo->prepare("
                INSERT INTO donors (user_id, blood_group, district, area, whatsapp, is_available, last_donation_date, next_available_date)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $insDonor->execute([$userId, $bloodGroup, $district, $area, $whatsapp, $isAvailable, $lastDonationDate, $nextAvailableDate]);
        }

        $pdo->commit();

        set_flash('success', 'অভিনন্দন! রক্তদাতা হিসেবে আপনার নিবন্ধন সফলভাবে সম্পন্ন হয়েছে।');
        header('Location: ' . BASE_URL . '/donor_dashboard.php');
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Donor registration error: " . $e->getMessage());
        set_flash('danger', 'রেজিস্ট্রেশন প্রক্রিয়ায় সমস্যা দেখা দিয়েছে: ' . $e->getMessage());
        header('Location: ' . BASE_URL . '/donor_register.php');
        exit;
    }
}

// Toggle Availability
if ($action === 'toggle_availability' && is_logged_in()) {
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT id, is_available, next_available_date FROM donors WHERE user_id = ?");
    $stmt->execute([$userId]);
    $donor = $stmt->fetch();

    if ($donor) {
        $newStatus = $donor['is_available'] ? 0 : 1;
        // If manually enabling, clear next_available_date if it was in future
        $upd = $pdo->prepare("UPDATE donors SET is_available = ? WHERE id = ?");
        $upd->execute([$newStatus, $donor['id']]);
        set_flash('success', 'আপনার রক্তদানের স্ট্যাটাস পরিবর্তিত হয়েছে।');
    }
    header('Location: ' . BASE_URL . '/donor_dashboard.php');
    exit;
}

header('Location: ' . BASE_URL . '/index.php');
exit;
