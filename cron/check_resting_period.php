<?php
/**
 * Cron Job: Check and Reactivate Donors after 120-Day (4 Month) Resting Period
 * 
 * Set in cPanel Crontab:
 * 0 6 * * * /usr/bin/php /path/to/bmmc/cron/check_resting_period.php >> /path/to/bmmc/cron/cron.log 2>&1
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/NotificationService.php';

$pdo = DB::getConnection();
$today = date('Y-m-d');
$logFile = __DIR__ . '/cron.log';

echo "[" . date('Y-m-d H:i:s') . "] Starting resting period check...\n";

// 1. Query donors whose resting period has completed
$sql = "
    SELECT d.id AS donor_id, d.blood_group, u.name, u.email
    FROM donors d
    JOIN users u ON d.user_id = u.id
    WHERE d.is_available = 0 
      AND d.next_available_date IS NOT NULL 
      AND d.next_available_date <= ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$today]);
$reactivatedDonors = $stmt->fetchAll();

$count = 0;
$notifier = new NotificationService();

foreach ($reactivatedDonors as $donor) {
    // 2. Reactivate donor
    $upd = $pdo->prepare("UPDATE donors SET is_available = 1 WHERE id = ?");
    $upd->execute([$donor['donor_id']]);

    // 3. Send encouraging reactivation email
    if (!empty($donor['email'])) {
        $subject = "🌟 স্বাগতম! আপনার ৪ মাসের রক্তদান বিশ্রাম বিরতি সম্পন্ন হয়েছে";
        $html = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; background-color: #0b2545; color: #ffffff; border-radius: 12px;'>
            <h2 style='color: #00d2ff; text-align: center;'>বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি (BMMC)</h2>
            <div style='background: rgba(255, 255, 255, 0.08); padding: 20px; border-radius: 10px;'>
                <p>সম্মানিত রক্তদাতা <strong>" . htmlspecialchars($donor['name']) . "</strong>,</p>
                <p>আপনার সর্বশেষ রক্তদানের পর নির্ধারিত ৪ মাসের (১২০ দিন) বিশ্রাম সময়কাল সফলভাবে সম্পন্ন হয়েছে। আপনার শারীরিক সুস্থতার জন্য এই বিরতি অপরিহার্য ছিল।</p>
                <p style='color: #10b981; font-weight: bold;'>আপনার প্রোফাইল এখন পুনরায় সক্রিয় (Available) করা হয়েছে।</p>
                <p>আপনার এলাকার কাছাকাছি কোনো মুমূর্ষু রোগীর <strong>" . htmlspecialchars($donor['blood_group']) . "</strong> রক্তের প্রয়োজন হলে স্বয়ংক্রিয়ভাবে আপনাকে অবহিত করা হবে।</p>
                <div style='text-align: center; margin-top: 25px;'>
                    <a href='" . BASE_URL . "/donor_dashboard.php' style='background: #0284c7; color: #ffffff; padding: 10px 24px; text-decoration: none; border-radius: 25px;'>আপনার ড্যাশবোর্ড দেখুন</a>
                </div>
            </div>
        </div>";
        $notifier->sendEmail($donor['email'], $donor['name'], $subject, $html);
    }

    $count++;
}

$logMsg = "[" . date('Y-m-d H:i:s') . "] Finished. Reactivated {$count} donors.\n";
echo $logMsg;
file_put_contents($logFile, $logMsg, FILE_APPEND);
