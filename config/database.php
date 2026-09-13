<?php
/**
 * Database Connection using PDO with friendly Setup Screen Fallback
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

                // Friendly Glassmorphism Error Screen with Direct Setup Assistant Link
                $setupUrl = (defined('BASE_URL') ? BASE_URL : '') . '/setup.php';
                $errorMessage = htmlspecialchars($e->getMessage());
                
                // If CLI, simple output
                if (php_sapi_name() === 'cli') {
                    die("\n[Error] Database connection failed: {$errorMessage}\nPlease run setup or configure config/database_credentials.php\n");
                }

                die("<!DOCTYPE html>
                <html lang='bn'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>ডাটাবেস সংযোগ প্রয়োজন — BMMC</title>
                    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
                    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css' rel='stylesheet'>
                    <link href='https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&display=swap' rel='stylesheet'>
                    <style>
                        body {
                            font-family: 'Hind Siliguri', sans-serif;
                            background: #061426;
                            color: #f8fafc;
                            min-height: 100vh;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            padding: 20px;
                        }
                        .glass-box {
                            background: rgba(11, 37, 69, 0.88);
                            backdrop-filter: blur(14px);
                            border: 1px solid rgba(239, 35, 60, 0.4);
                            border-radius: 20px;
                            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.6);
                            max-width: 650px;
                            width: 100%;
                            padding: 40px;
                            text-align: center;
                        }
                        .btn-setup {
                            background: linear-gradient(135deg, #00d2ff, #0284c7);
                            color: #fff;
                            font-weight: 700;
                            border-radius: 30px;
                            padding: 14px 32px;
                            text-decoration: none;
                            display: inline-block;
                            box-shadow: 0 6px 25px rgba(0, 210, 255, 0.4);
                            transition: all 0.25s ease;
                        }
                        .btn-setup:hover {
                            background: linear-gradient(135deg, #38bdf8, #00d2ff);
                            color: #fff;
                            transform: translateY(-2px);
                        }
                    </style>
                </head>
                <body>
                    <div class='glass-box'>
                        <div style='width: 64px; height: 64px; margin: 0 auto 20px; background: rgba(239, 35, 60, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #ef233c;'>
                            <i class='bi bi-database-exclamation fs-1 text-danger'></i>
                        </div>
                        <h3 class='fw-bold text-white mb-2'>ডাটাবেস কনফিগারেশন প্রয়োজন</h3>
                        <p class='text-secondary small mb-4'>
                            আপনার লাইভ সার্ভারে (cPanel) ডাটাবেস এখনো সংযুক্ত হয়নি। নিচের সেটআপ অ্যাসিস্ট্যান্ট ব্যবহার করে সহজেই cPanel ডাটাবেসের নাম, ইউজার ও পাসওয়ার্ড সেট করুন।
                        </p>
                        
                        <div class='alert alert-dark bg-opacity-50 border-secondary text-start small mb-4'>
                            <span class='text-danger d-block fw-bold mb-1'><i class='bi bi-info-circle me-1'></i>কারিগরি ত্রুটি:</span>
                            <code style='color: #ff8080;'>{$errorMessage}</code>
                        </div>

                        <a href='{$setupUrl}' class='btn-setup mb-4'>
                            <i class='bi bi-gear-fill me-2'></i> ১-ক্লিকে ডাটাবেস সেটআপ করুন
                        </a>

                        <div class='border-top border-secondary border-opacity-25 pt-3 mt-2 text-start'>
                            <small class='text-secondary d-block mb-1 fw-bold'>ম্যানুয়াল পদ্ধতি (cPanel File Manager):</small>
                            <small class='text-muted d-block'>ফাইল ম্যানেজারে <code>config/database_credentials.php</code> তৈরি করে আপনার ডাটাবেসের নাম ও পাসওয়ার্ড দিন।</small>
                        </div>
                    </div>
                </body>
                </html>");
            }
        }
        return self::$instance;
    }
}
