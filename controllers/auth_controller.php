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

// 0. Test 1-Click Login for Demo & Testing (All Roles)
if ($action === 'test_login') {
    $roleKey = sanitize($_POST['role'] ?? $_GET['role'] ?? '');

    $testProfiles = [
        'admin' => [
            'email' => 'admin@bmmc.org',
            'phone' => '01711000000',
            'name' => 'ক্যাপ্টেন শফিকুর রহমান (অ্যাডমিন)',
            'role' => 'admin',
            'user_type' => 'mariner',
            'cdc_sid_no' => 'C/O/08452',
            'mariner_rank' => 'Master Mariner / Captain',
            'blood_group' => 'O+',
            'district' => 'Chattogram',
            'area' => 'আগ্রাবাদ সি/এ',
            'is_available' => 1,
            'title' => 'কমিউনিটি অ্যাডমিন (ক্যাপ্টেন শফিকুর রহমান)',
            'redirect' => '/admin/dashboard.php'
        ],
        'mariner_active' => [
            'email' => 'mahfuz.marine@gmail.com',
            'phone' => '01812345678',
            'name' => 'চিফ ইঞ্জিনিয়ার মাহফুজুর আলম',
            'role' => 'donor',
            'user_type' => 'mariner',
            'cdc_sid_no' => 'C/E/04112',
            'mariner_rank' => 'Chief Engineer',
            'blood_group' => 'A+',
            'district' => 'Chattogram',
            'area' => 'জিইসি মোড়',
            'is_available' => 1,
            'title' => 'মেরিনার রক্তদাতা - প্রস্তুত (চিফ ইঞ্জিনিয়ার মাহফুজুর আলম)',
            'redirect' => '/donor_dashboard.php'
        ],
        'mariner_resting' => [
            'email' => 'rashed.marine@gmail.com',
            'phone' => '01612345678',
            'name' => 'ইঞ্জিনিয়ার রাশেদুল ইসলাম',
            'role' => 'donor',
            'user_type' => 'mariner',
            'cdc_sid_no' => '3/E/12840',
            'mariner_rank' => 'Third Engineer',
            'blood_group' => 'O+',
            'district' => 'Chattogram',
            'area' => 'হালিশহর',
            'is_available' => 0,
            'last_donation_date' => date('Y-m-d', strtotime('-40 days')),
            'next_available_date' => date('Y-m-d', strtotime('+80 days')),
            'title' => 'মেরিনার রক্তদাতা - বিশ্রামে আছেন (ইঞ্জিনিয়ার রাশেদুল ইসলাম)',
            'redirect' => '/donor_dashboard.php'
        ],
        'general_donor' => [
            'email' => 'najmul.huda@gmail.com',
            'phone' => '01512345678',
            'name' => 'নাজমুল হুদা',
            'role' => 'donor',
            'user_type' => 'general',
            'cdc_sid_no' => null,
            'mariner_rank' => null,
            'blood_group' => 'O-',
            'district' => 'Khulna',
            'area' => 'খালিশপুর',
            'is_available' => 1,
            'title' => 'সাধারণ নাগরিক রক্তদাতা (নাজমুল হুদা)',
            'redirect' => '/donor_dashboard.php'
        ]
    ];

    if (!isset($testProfiles[$roleKey])) {
        set_flash('danger', 'অবৈধ টেস্ট ভূমিকা নির্বাচন করা হয়েছে।');
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }

    $profile = $testProfiles[$roleKey];

    // Find or create user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$profile['email']]);
    $user = $stmt->fetch();

    if (!$user) {
        $passHash = password_hash(($roleKey === 'admin') ? 'admin123' : 'donor123', PASSWORD_BCRYPT);
        $ins = $pdo->prepare("
            INSERT INTO users (name, email, phone, password_hash, role, user_type, cdc_sid_no, mariner_rank)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $ins->execute([
            $profile['name'], $profile['email'], $profile['phone'],
            $passHash, $profile['role'], $profile['user_type'],
            $profile['cdc_sid_no'], $profile['mariner_rank']
        ]);
        $userId = (int)$pdo->lastInsertId();

        $dStmt = $pdo->prepare("
            INSERT INTO donors (user_id, blood_group, district, area, whatsapp, is_available, last_donation_date, next_available_date, total_donations)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $dStmt->execute([
            $userId, $profile['blood_group'], $profile['district'], $profile['area'],
            $profile['phone'], $profile['is_available'],
            $profile['last_donation_date'] ?? '2026-01-10',
            $profile['next_available_date'] ?? null,
            ($roleKey === 'admin' ? 5 : ($roleKey === 'mariner_resting' ? 6 : 3))
        ]);
    } else {
        $userId = $user['id'];
        if ($roleKey === 'mariner_resting') {
            $nextDate = date('Y-m-d', strtotime('+80 days'));
            $lastDate = date('Y-m-d', strtotime('-40 days'));
            $pdo->prepare("UPDATE donors SET is_available = 0, next_available_date = ?, last_donation_date = ? WHERE user_id = ?")
                ->execute([$nextDate, $lastDate, $userId]);
        } elseif ($roleKey === 'mariner_active' || $roleKey === 'general_donor') {
            $pdo->prepare("UPDATE donors SET is_available = 1, next_available_date = NULL WHERE user_id = ?")
                ->execute([$userId]);
        }
    }

    // Set session
    $_SESSION['user_id'] = $userId;
    set_flash('success', "⚡ টেস্ট মোডে সফলভাবে লগইন সম্পন্ন হয়েছে: <strong>{$profile['title']}</strong>");
    header('Location: ' . BASE_URL . $profile['redirect']);
    exit;
}

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

    $nowStr = date('Y-m-d H:i:s');
    $stmt = $pdo->prepare("
        SELECT * FROM otp_codes 
        WHERE identifier = ? AND code = ? AND is_used = 0 AND expires_at >= ? 
        ORDER BY id DESC LIMIT 1
    ");
    $stmt->execute([$identifier, $code, $nowStr]);
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

// 4. Volunteer Form Submission with Blood Donation Opt-in
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
    $portCity = sanitize($_POST['port_city'] ?? '');
    $interest = isset($_POST['interest']) ? implode(', ', array_map('sanitize', $_POST['interest'])) : '';
    $agreeBlood = !empty($_POST['agree_blood_donation']) ? 1 : 0;
    $bloodGroup = $agreeBlood ? sanitize($_POST['blood_group'] ?? '') : null;
    $lastDonation = (!empty($_POST['last_donation_date'])) ? $_POST['last_donation_date'] : null;
    $message = sanitize($_POST['message'] ?? '');

    if (empty($name) || empty($phone) || !$email) {
        set_flash('danger', 'অনুগ্রহ করে আপনার নাম, সচল মোবাইল নম্বর ও সঠিক ইমেইল প্রদান করুন।');
        header('Location: ' . BASE_URL . '/volunteer_register.php');
        exit;
    }

    try {
        $ins = $pdo->prepare("
            INSERT INTO volunteers (name, email, phone, is_mariner, cdc_sid_no, rank_designation, port_city, interest_area, agree_blood_donation, blood_group, last_donation_date, message)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $ins->execute([$name, $email, $phone, $isMariner, $cdcSid, $rank, $portCity, $interest, $agreeBlood, $bloodGroup, $lastDonation, $message]);

        // If user agreed to donate blood, automatically enroll into BMMC Blood Donor Database!
        $bloodEnrolled = false;
        if ($agreeBlood && !empty($bloodGroup)) {
            // 1. Check if user already exists in users table
            $uStmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
            $uStmt->execute([$email, $phone]);
            $existingUser = $uStmt->fetch();

            if ($existingUser) {
                $userId = (int)$existingUser['id'];
                // Update user type if mariner
                if ($isMariner && $existingUser['user_type'] !== 'mariner') {
                    $pdo->prepare("UPDATE users SET user_type = 'mariner', cdc_sid_no = ?, mariner_rank = ? WHERE id = ?")
                        ->execute([$cdcSid, $rank, $userId]);
                }
            } else {
                // Create user account
                $tempPass = password_hash(bin2hex(random_bytes(6)), PASSWORD_DEFAULT);
                $insUser = $pdo->prepare("
                    INSERT INTO users (name, email, phone, password_hash, user_type, cdc_sid_no, mariner_rank, role)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'donor')
                ");
                $insUser->execute([$name, $email, $phone, $tempPass, $userType, $cdcSid, $rank]);
                $userId = (int)$pdo->lastInsertId();
            }

            // 2. Check if donor profile exists in donors table
            $dStmt = $pdo->prepare("SELECT * FROM donors WHERE user_id = ?");
            $dStmt->execute([$userId]);
            $existingDonor = $dStmt->fetch();

            $district = !empty($portCity) ? $portCity : 'Chattogram';
            $area = !empty($portCity) ? $portCity : 'Chattogram Port Area';

            if ($existingDonor) {
                $pdo->prepare("
                    UPDATE donors 
                    SET blood_group = ?, district = ?, area = ?, is_available = 1, last_donation_date = COALESCE(?, last_donation_date)
                    WHERE user_id = ?
                ")->execute([$bloodGroup, $district, $area, $lastDonation, $userId]);
            } else {
                $pdo->prepare("
                    INSERT INTO donors (user_id, blood_group, district, area, whatsapp, is_available, last_donation_date)
                    VALUES (?, ?, ?, ?, ?, 1, ?)
                ")->execute([$userId, $bloodGroup, $district, $area, $phone, $lastDonation]);
            }

            $bloodEnrolled = true;
            if (empty($_SESSION['user_id'])) {
                $_SESSION['user_id'] = $userId;
            }
        }

        if ($bloodEnrolled) {
            set_flash('success', "🎉 <strong>অভিনন্দন, {$name}!</strong> BMMC ভলান্টিয়ার টিমে আপনার নিবন্ধন সফল হয়েছে এবং রক্তদানে সম্মতি দেওয়ায় আপনার প্রোফাইলটি <strong>BMMC ব্লাড ডোনার নেটওয়ার্কেও</strong> সরাসরি অন্তর্ভুক্ত হয়েছে!");
            header('Location: ' . BASE_URL . '/blood.php');
        } else {
            set_flash('success', "🎉 <strong>ধন্যবাদ, {$name}!</strong> BMMC ভলান্টিয়ার হিসেবে আপনার আবেদন সফলভাবে গৃহীত হয়েছে। আমাদের সমন্বয়ক টিম খুব শীঘ্রই আপনার সাথে যোগাযোগ করবে।");
            header('Location: ' . BASE_URL . '/index.php');
        }
        exit;
    } catch (Exception $e) {
        set_flash('danger', 'আবেদন সংরক্ষণে ত্রুটি দেখা দিয়েছে: ' . $e->getMessage());
        header('Location: ' . BASE_URL . '/volunteer_register.php');
        exit;
    }
}

header('Location: ' . BASE_URL . '/index.php');
exit;
