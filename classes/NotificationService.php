<?php
/**
 * Notification Service using PHPMailer & Email Logs
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Check vendor autoload for PHPMailer
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class NotificationService {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = DB::getConnection();
    }

    /**
     * Dispatch an email or simulate
     */
    public function sendEmail(string $recipientEmail, string $recipientName, string $subject, string $htmlBody): bool {
        $status = 'sent';

        // Check if simulation mode is active or PHPMailer is missing
        if (defined('MAIL_SIMULATE') && MAIL_SIMULATE) {
            $status = 'simulated';
            $this->logEmail($recipientEmail, $recipientName, $subject, $htmlBody, $status);
            return true;
        }

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USER;
            $mail->Password   = SMTP_PASS;
            $mail->SMTPSecure = SMTP_SECURE === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = SMTP_PORT;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
            $mail->addAddress($recipientEmail, $recipientName);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            $mail->AltBody = strip_tags($htmlBody);

            $mail->send();
            $this->logEmail($recipientEmail, $recipientName, $subject, $htmlBody, 'sent');
            return true;
        } catch (Exception $e) {
            error_log("PHPMailer Error: " . $e->getMessage());
            $this->logEmail($recipientEmail, $recipientName, $subject, $htmlBody, 'failed');
            return false;
        }
    }

    private function logEmail(string $email, string $name, string $subject, string $body, string $status): void {
        try {
            $preview = mb_substr(strip_tags($body), 0, 250);
            $stmt = $this->pdo->prepare("INSERT INTO email_logs (recipient_email, recipient_name, subject, body_preview, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$email, $name, $subject, $preview, $status]);
        } catch (PDOException $e) {
            error_log("Error logging email: " . $e->getMessage());
        }
    }

    /**
     * Notify matching donor about a blood request
     */
    public function notifyDonorForRequest(array $donor, array $request, string $responseToken): bool {
        $agreeUrl = BASE_URL . "/respond.php?token={$responseToken}&action=agree";
        $declineUrl = BASE_URL . "/respond.php?token={$responseToken}&action=decline";
        $subject = "🚨 জরুরি রক্তের অনুরোধ: {$request['blood_group']} রক্ত প্রয়োজন ({$request['hospital']}, {$request['district']})";

        $html = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; background-color: #0b2545; color: #ffffff; border-radius: 12px;'>
            <div style='text-align: center; margin-bottom: 20px;'>
                <h2 style='color: #00d2ff; margin-bottom: 5px;'>বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি (BMMC)</h2>
                <p style='color: #a0aec0; margin: 0;'>মানবতার সেবায় মেরিনারদের রক্তদান প্লাটফর্ম</p>
            </div>
            
            <div style='background: rgba(255, 255, 255, 0.08); padding: 20px; border-radius: 10px; border: 1px solid rgba(255, 255, 255, 0.15);'>
                <p style='font-size: 16px;'>সম্মানিত মেরিনার/ডোনার <strong>" . htmlspecialchars($donor['name']) . "</strong>,</p>
                <p>আপনার এলাকায় জরুরি ভিত্তিতে <strong><span style='color: #ef233c; font-size: 18px;'>" . htmlspecialchars($request['blood_group']) . "</span></strong> গ্রুপের রক্ত প্রয়োজন।</p>
                
                <table style='width: 100%; margin: 15px 0; color: #ffffff; border-collapse: collapse;'>
                    <tr><td style='padding: 8px 0; color: #a0aec0;'>রোগীর নাম:</td><td><strong>" . htmlspecialchars($request['patient_name']) . "</strong></td></tr>
                    <tr><td style='padding: 8px 0; color: #a0aec0;'>হাসপাতাল:</td><td>" . htmlspecialchars($request['hospital']) . "</td></tr>
                    <tr><td style='padding: 8px 0; color: #a0aec0;'>স্থান/জেলা:</td><td>" . htmlspecialchars($request['area']) . ", " . htmlspecialchars($request['district']) . "</td></tr>
                    <tr><td style='padding: 8px 0; color: #a0aec0;'>প্রয়োজনীয় ব্যাগ:</td><td>" . to_bangla_number($request['bags_needed']) . " ব্যাগ</td></tr>
                    <tr><td style='padding: 8px 0; color: #a0aec0;'>জরুরিতার মাত্রা:</td><td><strong style='color: #ffcc00;'>" . strtoupper($request['urgency']) . "</strong></td></tr>
                </table>
                
                <p style='margin-top: 20px; text-align: center; font-size: 15px;'>আপনি কি এই মুহূর্তে রক্ত দিয়ে রোগীর জীবন বাঁচাতে সম্মত?</p>
                
                <div style='text-align: center; margin: 25px 0;'>
                    <a href='{$agreeUrl}' style='background: #ef233c; color: #ffffff; padding: 12px 28px; text-decoration: none; border-radius: 30px; font-weight: bold; display: inline-block; margin-right: 10px; font-size: 16px;'>
                        ✅ হ্যাঁ, আমি রক্ত দিতে রাজি
                    </a>
                    <a href='{$declineUrl}' style='background: rgba(255,255,255,0.2); color: #e2e8f0; padding: 12px 20px; text-decoration: none; border-radius: 30px; display: inline-block; font-size: 14px;'>
                        ❌ অপারগ
                    </a>
                </div>
                <p style='font-size: 12px; color: #a0aec0; text-align: center;'>আপনি 'রাজি' বাটনে ক্লিক করলে তাৎক্ষণিকভাবে উভয়পক্ষের যোগাযোগের নম্বর বিনিময় করা হবে।</p>
            </div>
            
            <div style='text-align: center; margin-top: 20px; font-size: 12px; color: #718096;'>
                <p>© BMMC Blood Portal | একটি সম্পূর্ণ অরাজনৈতিক ও অলাভজনক স্বেচ্ছাসেবা উদ্যোগ</p>
            </div>
        </div>";

        return $this->sendEmail($donor['email'], $donor['name'], $subject, $html);
    }

    /**
     * Share mutual contacts when donor accepts
     */
    public function sendMutualContactSharing(array $donor, array $request): void {
        // 1. Email to Requester
        $requesterEmail = $request['contact_email'];
        if (!empty($requesterEmail)) {
            $subjectReq = "🎉 ডোনার পাওয়া গেছে! {$request['patient_name']}-এর জন্য ডোনার সম্মত হয়েছেন";
            $marinerBadge = ($donor['user_type'] === 'mariner') ? " (মেরিনার - " . htmlspecialchars($donor['mariner_rank'] ?? 'Seafarer') . ")" : "";
            $htmlReq = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; background-color: #0b2545; color: #ffffff; border-radius: 12px;'>
                <h2 style='color: #00d2ff; text-align: center;'>শুভ সংবাদ! রক্তদাতা পাওয়া গেছে</h2>
                <div style='background: rgba(255, 255, 255, 0.08); padding: 20px; border-radius: 10px; border: 1px solid rgba(255, 255, 255, 0.15);'>
                    <p>আপনার রক্তের অনুরোধের প্রেক্ষিতে একজন মহান রক্তদাতা রক্তদানে সম্মতি জানিয়েছেন।</p>
                    <table style='width: 100%; color: #ffffff; border-collapse: collapse; margin: 15px 0;'>
                        <tr><td style='padding: 6px 0; color: #a0aec0;'>ডোনারের নাম:</td><td><strong>" . htmlspecialchars($donor['name']) . "{$marinerBadge}</strong></td></tr>
                        <tr><td style='padding: 6px 0; color: #a0aec0;'>মোবাইল নম্বর:</td><td><a href='tel:{$donor['phone']}' style='color: #00d2ff; font-weight: bold; font-size: 18px;'>{$donor['phone']}</a></td></tr>
                        <tr><td style='padding: 6px 0; color: #a0aec0;'>হোয়াটসঅ্যাপ:</td><td>" . htmlspecialchars($donor['whatsapp'] ?: $donor['phone']) . "</td></tr>
                        <tr><td style='padding: 6px 0; color: #a0aec0;'>ব্লাড গ্রুপ:</td><td><strong style='color: #ef233c;'>" . htmlspecialchars($donor['blood_group']) . "</strong></td></tr>
                        <tr><td style='padding: 6px 0; color: #a0aec0;'>জেলা/এলাকা:</td><td>" . htmlspecialchars($donor['area']) . ", " . htmlspecialchars($donor['district']) . "</td></tr>
                    </table>
                    <p style='color: #ffcc00; font-size: 14px;'>অনুগ্রহ করে অবিলম্বে রক্তদাতার সাথে ফোনে যোগাযোগ করে সময় ও হাসপাতালের বিস্তারিত সমন্বয় করুন।</p>
                </div>
            </div>";
            $this->sendEmail($requesterEmail, $request['contact_name'], $subjectReq, $htmlReq);
        }

        // 2. Email to Donor with Patient & Requester Details
        $subjectDonor = "✅ রক্তদানে সম্মতি নিশ্চিতকরণ — রোগীর ও হাসপাতালের তথ্য";
        $htmlDonor = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; background-color: #0b2545; color: #ffffff; border-radius: 12px;'>
            <h2 style='color: #00d2ff; text-align: center;'>রক্তদানে সম্মতি জানানোর জন্য ধন্যবাদ!</h2>
            <div style='background: rgba(255, 255, 255, 0.08); padding: 20px; border-radius: 10px; border: 1px solid rgba(255, 255, 255, 0.15);'>
                <p>প্রিয় <strong>" . htmlspecialchars($donor['name']) . "</strong>, রোগীর পক্ষ থেকে আপনার সাথে যোগাযোগ করা হবে অথবা আপনি সরাসরি নিচের নম্বরে যোগাযোগ করতে পারেন:</p>
                <table style='width: 100%; color: #ffffff; border-collapse: collapse; margin: 15px 0;'>
                    <tr><td style='padding: 6px 0; color: #a0aec0;'>যোগাযোগকারী:</td><td><strong>" . htmlspecialchars($request['contact_name']) . "</strong></td></tr>
                    <tr><td style='padding: 6px 0; color: #a0aec0;'>ফোন নম্বর:</td><td><a href='tel:{$request['contact_phone']}' style='color: #00d2ff; font-weight: bold; font-size: 18px;'>{$request['contact_phone']}</a></td></tr>
                    <tr><td style='padding: 6px 0; color: #a0aec0;'>রোগীর নাম:</td><td>" . htmlspecialchars($request['patient_name']) . "</td></tr>
                    <tr><td style='padding: 6px 0; color: #a0aec0;'>হাসপাতাল:</td><td>" . htmlspecialchars($request['hospital']) . "</td></tr>
                    <tr><td style='padding: 6px 0; color: #a0aec0;'>ঠিকানা:</td><td>" . htmlspecialchars($request['area']) . ", " . htmlspecialchars($request['district']) . "</td></tr>
                </table>
                <p style='color: #38bdf8; font-size: 14px;'>রক্তদান সফলভাবে সম্পন্ন হলে ওয়েবসাইটে আপনার ড্যাশবোর্ড থেকে রক্তদান নিশ্চিত করবেন। এর মাধ্যমে আপনার ৪ মাসের সুস্থতা/বিশ্রাম সময়কাল গণনা শুরু হবে।</p>
            </div>
        </div>";
        $this->sendEmail($donor['email'], $donor['name'], $subjectDonor, $htmlDonor);
    }

    /**
     * Send OTP Code
     */
    public function sendOTP(string $identifier, string $code): bool {
        $subject = "BMMC পোর্টাল ভেরিফিকেশন কোড: {$code}";
        $html = "
        <div style='font-family: Arial, sans-serif; max-width: 500px; margin: auto; padding: 20px; background-color: #0b2545; color: #ffffff; border-radius: 12px; text-align: center;'>
            <h2 style='color: #00d2ff;'>বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি</h2>
            <p>আপনার ওটিপি (OTP) ভেরিফিকেশন কোড:</p>
            <div style='background: #ef233c; color: #fff; font-size: 32px; font-weight: bold; letter-spacing: 5px; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                {$code}
            </div>
            <p style='font-size: 13px; color: #a0aec0;'>কোডটি আগামী ৫ মিনিট কার্যকর থাকবে। কাউকে এই কোড জানাবেন না।</p>
        </div>";
        return $this->sendEmail($identifier, 'User', $subject, $html);
    }
}
