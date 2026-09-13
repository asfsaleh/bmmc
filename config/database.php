<?php
/**
 * Database Connection using PDO
 */

require_once __DIR__ . '/config.php';

class DB {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                error_log("Database connection failed: " . $e->getMessage());
                die("<div style='background:#111;color:#ff5555;padding:20px;font-family:sans-serif;'>
                    <h3>ডাটাবেস সংযোগ ব্যর্থ হয়েছে (Database Connection Error)</h3>
                    <p>অনুগ্রহ করে আপনার MySQL ডাটাবেস ও কনফিগারেশন চেক করুন: " . htmlspecialchars($e->getMessage()) . "</p>
                </div>");
            }
        }
        return self::$instance;
    }
}
