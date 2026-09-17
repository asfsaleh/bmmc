<?php
/**
 * Bangladesh Merchant Mariners Community (BMMC)
 * Global Configuration File
 */

// Timezone
date_default_timezone_set('Asia/Dhaka');

// Session start if not active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('ROOT_PATH', dirname(__DIR__));

// 1. Check for dedicated database credentials file (e.g. created on cPanel or by setup wizard)
if (file_exists(__DIR__ . '/database_credentials.php')) {
    require_once __DIR__ . '/database_credentials.php';
}

// 2. Load .env file if present
function loadEnv(string $path): void {
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || str_starts_with($line, '#')) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            $value = trim($value, '"\''); // strip quotes
            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv("{$name}={$value}");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

// Try loading from root .env or config/.env
loadEnv(ROOT_PATH . '/.env');
loadEnv(__DIR__ . '/.env');

// Error reporting based on environment
$appEnv = $_ENV['APP_ENV'] ?? 'production';
if ($appEnv === 'development') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// Site constants
define('SITE_NAME', 'বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি');
define('SITE_NAME_EN', 'Bangladesh Merchant Mariners Community (BMMC)');
define('SITE_TAGLINE', 'মেরিনারদের কল্যাণে, মেরিনারদের দ্বারা — শতভাগ অরাজনৈতিক ও অলাভজনক স্বেচ্ছাসেবা');

// Dynamic base URL detection or override from .env
if (!empty($_ENV['APP_URL'])) {
    define('BASE_URL', rtrim($_ENV['APP_URL'], '/'));
} else {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? ($_ENV['APP_DOMAIN'] ?? 'bmmc.skillsetup.org');
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $scriptDir = rtrim($scriptDir, '/');
    $scriptDir = preg_replace('/(\/admin|\/controllers|\/cron|\/templates|\/tests).*$/', '', $scriptDir);
    if ($scriptDir === '.' || $scriptDir === '/') {
        $scriptDir = '';
    }
    define('BASE_URL', $protocol . $host . $scriptDir);
}

// Blood donation resting period in days (4 months = ~120 days)
define('RESTING_PERIOD_DAYS', (int)($_ENV['RESTING_PERIOD_DAYS'] ?? 120));

// Check Maintenance Mode (Serves Mechanical Maintenance Screen if enabled)
$maintenanceConfigFile = __DIR__ . '/maintenance.json';
if (file_exists($maintenanceConfigFile)) {
    $mState = json_decode(file_get_contents($maintenanceConfigFile), true);
    if (!empty($mState['enabled'])) {
        $reqUri = $_SERVER['REQUEST_URI'] ?? '';
        $reqScript = $_SERVER['SCRIPT_NAME'] ?? '';
        
        // Exemptions: CLI scripts, /m, /m.php, /assets/, setup.php, maintenance.php, and admin bypass session
        $isMaintenanceExempt = (
            php_sapi_name() === 'cli' ||
            str_contains($reqUri, '/m.php') ||
            str_contains($reqScript, 'm.php') ||
            preg_match('#^/m(/|\?|$)#', $reqUri) ||
            preg_match('#/m(/|\?|$)#', $reqScript) ||
            str_contains($reqUri, 'maintenance.php') ||
            str_contains($reqUri, 'setup.php') ||
            str_starts_with($reqUri, '/assets/') ||
            !empty($_SESSION['maintenance_bypass'])
        );

        if (!$isMaintenanceExempt) {
            http_response_code(503);
            header('Retry-After: 3600');
            require_once ROOT_PATH . '/maintenance.php';
            exit;
        }
    }
}


// Database Configuration
// Checks: 1) constants in database_credentials.php, 2) $_ENV, 3) fallback defaults
if (!defined('DB_HOST')) define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
if (!defined('DB_PORT')) define('DB_PORT', $_ENV['DB_PORT'] ?? '3306');
if (!defined('DB_NAME')) define('DB_NAME', $_ENV['DB_NAME'] ?? 'bmmc_db');
if (!defined('DB_USER')) define('DB_USER', $_ENV['DB_USER'] ?? 'root');
if (!defined('DB_PASS')) define('DB_PASS', $_ENV['DB_PASS'] ?? '');
if (!defined('DB_CHARSET')) define('DB_CHARSET', $_ENV['DB_CHARSET'] ?? 'utf8mb4');

// Email & SMTP Configuration
define('MAIL_SIMULATE', filter_var($_ENV['MAIL_SIMULATE'] ?? 'true', FILTER_VALIDATE_BOOLEAN));
define('SMTP_HOST', $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com');
define('SMTP_PORT', (int)($_ENV['SMTP_PORT'] ?? 587));
define('SMTP_SECURE', $_ENV['SMTP_SECURE'] ?? 'tls');
define('SMTP_USER', $_ENV['SMTP_USER'] ?? 'bmmc.welfare@gmail.com');
define('SMTP_PASS', $_ENV['SMTP_PASS'] ?? 'your_app_password');
define('SMTP_FROM_NAME', $_ENV['SMTP_FROM_NAME'] ?? 'BMMC Blood Portal');
define('SMTP_FROM_EMAIL', $_ENV['SMTP_FROM_EMAIL'] ?? 'bmmc.welfare@gmail.com');

// Fixed Community Admins
$envAdmins = !empty($_ENV['ADMIN_EMAILS']) ? explode(',', $_ENV['ADMIN_EMAILS']) : [];
$FIXED_ADMIN_EMAILS = array_merge([
    'admin@bmmc.org',
    'secretary@bmmc.org',
    'welfare@bmmc.org',
    'mariner.admin@bmmc.skillsetup.org'
], array_map('trim', $envAdmins));

define('FIXED_ADMINS', array_unique($FIXED_ADMIN_EMAILS));
