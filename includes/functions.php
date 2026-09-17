<?php
/**
 * Helper Utility Functions
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

function sanitize(string|null $data): string {
    if ($data === null) return '';
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function validate_csrf(): bool {
    $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';
    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        return false;
    }
    return true;
}

function set_flash(string $type, string $message): void {
    $_SESSION['flash'] = [
        'type' => $type, // 'success', 'danger', 'warning', 'info'
        'message' => $message
    ];
}

function get_flash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function is_logged_in(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function current_user(): ?array {
    if (!is_logged_in()) return null;
    
    static $cachedUser = null;
    if ($cachedUser !== null) return $cachedUser;

    $pdo = DB::getConnection();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $cachedUser = $stmt->fetch();
    return $cachedUser ?: null;
}

function is_admin(): bool {
    $user = current_user();
    if (!$user) return false;
    
    // Check role or fixed admin emails
    if ($user['role'] === 'admin') return true;
    if (!empty($user['email']) && in_array(strtolower($user['email']), array_map('strtolower', FIXED_ADMINS))) {
        return true;
    }
    return false;
}

function require_login(): void {
    if (!is_logged_in()) {
        set_flash('warning', 'এই পেজটিতে প্রবেশ করতে অনুগ্রহ করে প্রথমে লগইন করুন।');
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}

function require_admin(): void {
    require_login();
    if (!is_admin()) {
        set_flash('danger', 'আপনার কাছে এই প্রশাসনিক পেজে প্রবেশের অনুমতি নেই।');
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }
}

function to_bangla_number(int|string|null $number): string {
    if ($number === null) return is_english() ? '0' : '০';
    if (is_english()) {
        return (string)$number;
    }
    $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    $en = ['0','1','2','3','4','5','6','7','8','9'];
    return str_replace($en, $bn, (string)$number);
}

function format_date_bn(string|null $dateStr): string {
    if (!$dateStr) return is_english() ? 'N/A' : 'প্রযোজ্য নয়';
    $time = strtotime($dateStr);
    if (!$time) return $dateStr;

    if (is_english()) {
        return date('d M, Y', $time);
    }

    $months_en = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $months_bn = ['জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];
    
    $d = to_bangla_number(date('d', $time));
    $m = str_replace($months_en, $months_bn, date('M', $time));
    $y = to_bangla_number(date('Y', $time));
    
    return "{$d} {$m}, {$y}";
}

function get_blood_badge(string $group): string {
    return '<span class="badge bg-danger text-white px-2 py-1 fs-6 fw-bold shadow-sm"><i class="bi bi-droplet-fill me-1"></i>' . htmlspecialchars($group) . '</span>';
}

function get_urgency_badge(string $urgency): string {
    $isEn = is_english();
    return match (strtolower($urgency)) {
        'emergency' => '<span class="badge bg-danger text-white px-2 py-1"><i class="bi bi-exclamation-octagon-fill me-1"></i>' . ($isEn ? 'Emergency' : 'অতি জরুরি (Emergency)') . '</span>',
        'urgent' => '<span class="badge bg-warning text-dark px-2 py-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>' . ($isEn ? 'Urgent' : 'জরুরি (Urgent)') . '</span>',
        default => '<span class="badge bg-info text-dark px-2 py-1"><i class="bi bi-info-circle-fill me-1"></i>' . ($isEn ? 'Normal' : 'সাধারণ (Normal)') . '</span>',
    };
}

function get_status_badge(string $status): string {
    $isEn = is_english();
    return match (strtolower($status)) {
        'open' => '<span class="badge bg-primary"><i class="bi bi-broadcast me-1"></i>' . ($isEn ? 'Open' : 'চলমান (Open)') . '</span>',
        'matched' => '<span class="badge bg-info text-dark"><i class="bi bi-person-check me-1"></i>' . ($isEn ? 'Matched' : 'ডোনার ম্যাচড (Matched)') . '</span>',
        'fulfilled' => '<span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>' . ($isEn ? 'Fulfilled' : 'সম্পন্ন (Fulfilled)') . '</span>',
        'cancelled' => '<span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>' . ($isEn ? 'Cancelled' : 'বাতিল (Cancelled)') . '</span>',
        default => htmlspecialchars($status),
    };
}

/**
 * Check and auto-update resting status for a donor if 120 days have elapsed
 */
function refresh_donor_resting_status(int $donorId): void {
    $pdo = DB::getConnection();
    $stmt = $pdo->prepare("SELECT id, is_available, next_available_date FROM donors WHERE id = ?");
    $stmt->execute([$donorId]);
    $donor = $stmt->fetch();
    if ($donor && $donor['is_available'] == 0 && !empty($donor['next_available_date'])) {
        if (strtotime($donor['next_available_date']) <= time()) {
            $upd = $pdo->prepare("UPDATE donors SET is_available = 1 WHERE id = ?");
            $upd->execute([$donorId]);
        }
    }
}
