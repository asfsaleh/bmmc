<?php
/**
 * Database Seed Script for BMMC Website
 * Run in CLI: php database/seed.php
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$pdo = DB::getConnection();
echo "🌱 Seeding BMMC Database...\n";

// 1. Create Default Admin User
$adminEmail = 'admin@bmmc.org';
$adminPass = password_hash('admin123', PASSWORD_BCRYPT);

$pdo->prepare("DELETE FROM users WHERE email = ?")->execute([$adminEmail]);
$insAdmin = $pdo->prepare("
    INSERT INTO users (name, email, phone, password_hash, role, user_type, cdc_sid_no, mariner_rank)
    VALUES ('ক্যাপ্টেন শফিকুর রহমান (অ্যাডমিন)', ?, '01711000000', ?, 'admin', 'mariner', 'C/O/08452', 'Master Mariner / Captain')
");
$insAdmin->execute([$adminEmail, $adminPass]);
$adminId = (int)$pdo->lastInsertId();

// Add Admin as a donor too
$insAdminDonor = $pdo->prepare("
    INSERT INTO donors (user_id, blood_group, district, area, whatsapp, is_available, last_donation_date, total_donations)
    VALUES (?, 'O+', 'Chattogram', 'আগ্রাবাদ সি/এ', '01711000000', 1, '2026-01-15', 5)
");
$insAdminDonor->execute([$adminId]);

// 2. Create Sample Donors (Mariners & General)
$sampleUsers = [
    [
        'name' => 'চিফ ইঞ্জিনিয়ার মাহফুজুর আলম',
        'email' => 'mahfuz.marine@gmail.com',
        'phone' => '01812345678',
        'blood_group' => 'A+',
        'district' => 'Chattogram',
        'area' => 'জিইসি মোড়',
        'user_type' => 'mariner',
        'cdc_sid_no' => 'C/E/04112',
        'rank' => 'Chief Engineer',
        'is_available' => 1,
        'last_donation_date' => '2025-11-20',
        'total_donations' => 4
    ],
    [
        'name' => '২য় অফিসার তানভীর আহমেদ',
        'email' => 'tanvir.officer@gmail.com',
        'phone' => '01912345678',
        'blood_group' => 'B+',
        'district' => 'Dhaka',
        'area' => 'উত্তরা সেক্টর ৭',
        'user_type' => 'mariner',
        'cdc_sid_no' => '2/O/09931',
        'rank' => 'Second Officer',
        'is_available' => 1,
        'last_donation_date' => '2026-02-10',
        'total_donations' => 2
    ],
    [
        'name' => 'ইঞ্জিনিয়ার রাশেদুল ইসলাম (বিশ্রামে আছেন)',
        'email' => 'rashed.marine@gmail.com',
        'phone' => '01612345678',
        'blood_group' => 'O+',
        'district' => 'Chattogram',
        'area' => 'হালিশহর',
        'user_type' => 'mariner',
        'cdc_sid_no' => '3/E/12840',
        'rank' => 'Third Engineer',
        // In resting period (Donated 40 days ago, 80 days remaining)
        'is_available' => 0,
        'last_donation_date' => date('Y-m-d', strtotime('-40 days')),
        'next_available_date' => date('Y-m-d', strtotime('+80 days')),
        'total_donations' => 6
    ],
    [
        'name' => 'নাজমুল হুদা (সাধারণ নাগরিক)',
        'email' => 'najmul.huda@gmail.com',
        'phone' => '01512345678',
        'blood_group' => 'O-',
        'district' => 'Khulna',
        'area' => 'খালিশপুর',
        'user_type' => 'general',
        'cdc_sid_no' => null,
        'rank' => null,
        'is_available' => 1,
        'last_donation_date' => '2025-10-05',
        'total_donations' => 3
    ]
];

foreach ($sampleUsers as $u) {
    // Delete if already exists
    $pdo->prepare("DELETE FROM users WHERE email = ?")->execute([$u['email']]);
    
    $ins = $pdo->prepare("
        INSERT INTO users (name, email, phone, password_hash, role, user_type, cdc_sid_no, mariner_rank)
        VALUES (?, ?, ?, ?, 'donor', ?, ?, ?)
    ");
    $ins->execute([
        $u['name'], $u['email'], $u['phone'], 
        password_hash('donor123', PASSWORD_BCRYPT), 
        $u['user_type'], $u['cdc_sid_no'], $u['rank']
    ]);
    $uid = (int)$pdo->lastInsertId();

    $insD = $pdo->prepare("
        INSERT INTO donors (user_id, blood_group, district, area, whatsapp, is_available, last_donation_date, next_available_date, total_donations)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $nextDate = $u['next_available_date'] ?? null;
    $insD->execute([$uid, $u['blood_group'], $u['district'], $u['area'], $u['phone'], $u['is_available'], $u['last_donation_date'], $nextDate, $u['total_donations']]);
}

// 3. Create Sample Blood Requests
$pdo->prepare("DELETE FROM blood_requests WHERE patient_name LIKE '%(ডেমো)%'")->execute();

$sampleRequests = [
    [
        'patient_name' => 'মোসাঃ সালেহা বেগম (ডেমো)',
        'blood_group' => 'A+',
        'bags_needed' => 2,
        'hospital' => 'চট্টগ্রাম মেডিকেল কলেজ হাসপাতাল (চমেক)',
        'district' => 'Chattogram',
        'area' => 'পাঁচলাইশ',
        'urgency' => 'emergency',
        'needed_by' => date('Y-m-d'),
        'contact_name' => 'কামরুল হাসান',
        'contact_phone' => '01899112233',
        'contact_email' => 'kamrul.relative@gmail.com',
        'notes' => 'জরুরি সার্জারির জন্য আজই ২ ব্যাগ A+ রক্ত প্রয়োজন।'
    ],
    [
        'patient_name' => 'মোহাম্মদ রফিকুল ইসলাম (ডেমো)',
        'blood_group' => 'O+',
        'bags_needed' => 1,
        'hospital' => 'ন্যাশনাল হার্ট ফাউন্ডেশন, মিরপুর',
        'district' => 'Dhaka',
        'area' => 'মিরপুর ২',
        'urgency' => 'urgent',
        'needed_by' => date('Y-m-d', strtotime('+1 day')),
        'contact_name' => 'আবুল কালাম',
        'contact_phone' => '01788223344',
        'contact_email' => 'kalam.relative@gmail.com',
        'notes' => 'বাইপাস সার্জারির পূর্ব প্রস্তুতি হিসেবে ও পজিটিভ রক্তের প্রয়োজন।'
    ]
];

foreach ($sampleRequests as $r) {
    $insReq = $pdo->prepare("
        INSERT INTO blood_requests (
            patient_name, blood_group, bags_needed, hospital, district, area, 
            urgency, status, needed_by, contact_name, contact_phone, contact_email, notes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, 'open', ?, ?, ?, ?, ?)
    ");
    $insReq->execute([
        $r['patient_name'], $r['blood_group'], $r['bags_needed'], $r['hospital'],
        $r['district'], $r['area'], $r['urgency'], $r['needed_by'], $r['contact_name'],
        $r['contact_phone'], $r['contact_email'], $r['notes']
    ]);
}

// 4. Sample Volunteer Application
$pdo->prepare("DELETE FROM volunteers WHERE email = 'volunteer.test@bmmc.org'")->execute();
$insVol = $pdo->prepare("
    INSERT INTO volunteers (name, email, phone, is_mariner, cdc_sid_no, rank_designation, interest_area, message)
    VALUES ('ক্যাডেট আদনান সামি', 'volunteer.test@bmmc.org', '01799001122', 1, 'D/C/19082', 'Deck Cadet', 'Blood Donation Coordination, Port & Hospital Liaison', 'সমুদ্রযাত্রার ফাঁকে ছুটিতে দেশের মানুষের পাশে থাকতে চাই।')
");
$insVol->execute();

echo "✅ Seeding Completed Successfully!\n";
echo "---------------------------------------------------------\n";
echo "🔑 Default Admin Login:\n";
echo "   Email:    admin@bmmc.org\n";
echo "   Password: admin123\n";
echo "---------------------------------------------------------\n";
echo "🔑 Default Donor Login:\n";
echo "   Email:    mahfuz.marine@gmail.com\n";
echo "   Password: donor123\n";
echo "---------------------------------------------------------\n";
