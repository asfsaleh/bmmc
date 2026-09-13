<?php
/**
 * BMMC Web-based Database Setup Assistant
 * 
 * Allows setting up the database credentials easily on live cPanel/shared hosting.
 */

// Error display for setup
ini_set('display_errors', 1);
error_reporting(E_ALL);

$configFile = __DIR__ . '/config/database_credentials.php';
$envFile = __DIR__ . '/.env';
$schemaFile = __DIR__ . '/database/schema.sql';
$seedFile = __DIR__ . '/database/seed.php';

$error = null;
$success = null;
$alreadyConnected = false;

// Check existing connection
if (file_exists($configFile)) {
    require_once $configFile;
    if (defined('DB_HOST') && defined('DB_NAME') && defined('DB_USER')) {
        try {
            $testPdo = new PDO("mysql:host=" . DB_HOST . ";port=" . (defined('DB_PORT') ? DB_PORT : 3306) . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            $alreadyConnected = true;
        } catch (PDOException $e) {
            $alreadyConnected = false;
        }
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbHost = trim($_POST['db_host'] ?? 'localhost');
    $dbPort = trim($_POST['db_port'] ?? '3306');
    $dbName = trim($_POST['db_name'] ?? '');
    $dbUser = trim($_POST['db_user'] ?? '');
    $dbPass = trim($_POST['db_pass'] ?? '');

    if (empty($dbName) || empty($dbUser)) {
        $error = "অনুগ্রহ করে ডাটাবেসের নাম এবং ডাটাবেস ইউজারনেম প্রদান করুন।";
    } else {
        // Test connection
        try {
            $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
            $pdo = new PDO($dsn, $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            // Save to config/database_credentials.php
            $configContent = "<?php\n" .
                "// BMMC Database Credentials\n" .
                "// Generated automatically via Setup Assistant on " . date('Y-m-d H:i:s') . "\n\n" .
                "define('DB_HOST', " . var_export($dbHost, true) . ");\n" .
                "define('DB_PORT', " . var_export($dbPort, true) . ");\n" .
                "define('DB_NAME', " . var_export($dbName, true) . ");\n" .
                "define('DB_USER', " . var_export($dbUser, true) . ");\n" .
                "define('DB_PASS', " . var_export($dbPass, true) . ");\n" .
                "define('DB_CHARSET', 'utf8mb4');\n";

            file_put_contents($configFile, $configContent);

            // Also update/write .env if writable
            $envContent = "APP_ENV=production\n" .
                "APP_DOMAIN=bmmc.skillsetup.org\n" .
                "APP_URL=https://bmmc.skillsetup.org\n\n" .
                "DB_HOST={$dbHost}\n" .
                "DB_PORT={$dbPort}\n" .
                "DB_NAME={$dbName}\n" .
                "DB_USER={$dbUser}\n" .
                "DB_PASS=\"{$dbPass}\"\n" .
                "DB_CHARSET=utf8mb4\n\n" .
                "MAIL_SIMULATE=false\n";
            @file_put_contents($envFile, $envContent);

            // Check if tables exist, if not, auto-import schema.sql
            $tblStmt = $pdo->query("SHOW TABLES");
            $tables = $tblStmt->fetchAll(PDO::FETCH_COLUMN);

            if (empty($tables) && file_exists($schemaFile)) {
                $sql = file_get_contents($schemaFile);
                // Remove CREATE DATABASE & USE lines to avoid permissions issue on shared hosts
                $sql = preg_replace('/CREATE DATABASE[^;]+;/i', '', $sql);
                $sql = preg_replace('/USE [^;]+;/i', '', $sql);
                $pdo->exec($sql);

                // Run seed if available
                if (file_exists($seedFile)) {
                    include $seedFile;
                }
            }

            $success = "অভিনন্দন! ডাটাবেস সফলভাবে সংযুক্ত হয়েছে এবং স্কিমা যাচাই করা হয়েছে।";
            $alreadyConnected = true;

            // Auto redirect after 2 seconds
            header("refresh:2;url=index.php");

        } catch (PDOException $e) {
            $error = "সংযোগ ব্যর্থ হয়েছে: " . $e->getMessage() . "<br><br><strong>পরামর্শ:</strong> cPanel MySQL Database Wizard-এ নিশ্চিত করুন যে তৈরি করা ডাটাবেস ইউজারের সাথে ডাটাবেসটি সংযুক্ত করে <code>ALL PRIVILEGES</code> অনুমতি দেওয়া হয়েছে।";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ডাটাবেস সেটআপ অ্যাসিস্ট্যান্ট — BMMC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background: #061426;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
            background-image: radial-gradient(at 0% 0%, rgba(19, 64, 116, 0.4) 0px, transparent 50%),
                              radial-gradient(at 100% 100%, rgba(217, 4, 41, 0.2) 0px, transparent 50%);
        }
        .glass-setup {
            background: rgba(11, 37, 69, 0.85);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.5);
        }
        .form-control {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            color: #fff !important;
            padding: 12px 14px;
            border-radius: 10px;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.12) !important;
            border-color: #00d2ff !important;
            box-shadow: 0 0 15px rgba(0, 210, 255, 0.3) !important;
        }
        .btn-ocean {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: #fff;
            border-radius: 30px;
            font-weight: 700;
            padding: 12px 24px;
            border: none;
            box-shadow: 0 4px 15px rgba(2, 132, 199, 0.4);
        }
        .btn-ocean:hover {
            background: linear-gradient(135deg, #00d2ff 0%, #0284c7 100%);
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                <div class="glass-setup p-4 p-md-5">
                    <!-- Brand -->
                    <div class="text-center mb-4">
                        <div style="width: 56px; height: 56px; margin: 0 auto 12px; background: linear-gradient(135deg, #00d2ff, #0284c7); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-database-fill-gear fs-2 text-white"></i>
                        </div>
                        <h3 class="fw-bold text-white mb-1">BMMC ডাটাবেস সেটআপ অ্যাসিস্ট্যান্ট</h3>
                        <p class="text-secondary small">আপনার cPanel ডাটাবেসের তথ্য দিয়ে সংযোগ সম্পন্ন করুন</p>
                    </div>

                    <?php if ($success): ?>
                        <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-check-circle-fill fs-3 me-3"></i>
                            <div>
                                <strong>সফল!</strong> <?= $success ?>
                                <small class="d-block mt-1">২ সেকেন্ডের মধ্যে স্বয়ংক্রিয়ভাবে হোমপেজে নিয়ে যাওয়া হচ্ছে...</small>
                            </div>
                        </div>
                        <div class="text-center mb-4">
                            <a href="index.php" class="btn btn-ocean rounded-pill px-5">এখনই হোমপেজে যান →</a>
                        </div>
                    <?php endif; ?>

                    <?php if ($alreadyConnected && !$success): ?>
                        <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-info-circle-fill fs-3 me-3"></i>
                            <div>
                                <strong>ডাটাবেস বর্তমানে সংযুক্ত আছে!</strong> আপনার সাইটটি সক্রিয়। প্রয়োজনে নিচে নতুন তথ্য দিয়ে ডাটাবেস কনফিগারেশন আপডেট করতে পারেন।
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
                            <div><?= $error ?></div>
                        </div>
                    <?php endif; ?>

                    <!-- Form -->
                    <form method="POST" action="setup.php">
                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label class="form-label text-light small fw-bold">Database Host</label>
                                <input type="text" class="form-control" name="db_host" value="<?= htmlspecialchars($_POST['db_host'] ?? (defined('DB_HOST') ? DB_HOST : 'localhost')) ?>" required>
                                <small class="text-secondary" style="font-size: 0.75rem;">cPanel-এর ক্ষেত্রে সাধারণত <code>localhost</code> থাকে</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-light small fw-bold">Port</label>
                                <input type="text" class="form-control" name="db_port" value="<?= htmlspecialchars($_POST['db_port'] ?? (defined('DB_PORT') ? DB_PORT : '3306')) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-light small fw-bold">Database Name (ডাটাবেসের নাম) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="db_name" value="<?= htmlspecialchars($_POST['db_name'] ?? (defined('DB_NAME') && DB_NAME !== 'bmmc_db' ? DB_NAME : '')) ?>" placeholder="যেমন: skillsetu_bmmc" required>
                            <small class="text-secondary" style="font-size: 0.75rem;">cPanel-এ আপনার তৈরি করা ডাটাবেসের সম্পূর্ণ নাম (Prefix সহ)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-light small fw-bold">Database Username (ডাটাবেস ইউজার) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="db_user" value="<?= htmlspecialchars($_POST['db_user'] ?? (defined('DB_USER') && DB_USER !== 'root' ? DB_USER : '')) ?>" placeholder="যেমন: skillsetu_dbuser" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-light small fw-bold">Database Password (ডাটাবেস পাসওয়ার্ড) <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="db_pass" placeholder="আপনার ডাটাবেস ইউজারের পাসওয়ার্ড" required>
                        </div>

                        <button type="submit" class="btn btn-ocean w-100 py-3 mb-3">
                            <i class="bi bi-link-45deg me-1"></i> সংযোগ যাচাই ও সংরক্ষণ করুন (Save & Connect)
                        </button>
                    </form>

                    <!-- cPanel Quick Help Box -->
                    <div class="mt-4 p-3 rounded-3 border border-secondary border-opacity-25" style="background: rgba(0,0,0,0.2);">
                        <h6 class="text-info fw-bold mb-2 small"><i class="bi bi-question-circle me-1"></i>cPanel-এ ডাটাবেস তৈরির নিয়ম:</h6>
                        <ol class="text-secondary small mb-0 ps-3" style="line-height: 1.6;">
                            <li>cPanel-এ লগইন করে <strong>MySQL® Database Wizard</strong>-এ যান।</li>
                            <li>ডাটাবেসের নাম ও ইউজার তৈরি করে একটি শক্তিশালী পাসওয়ার্ড দিন।</li>
                            <li><strong>"Add User to Database"</strong> ধাপে <strong>ALL PRIVILEGES</strong> বক্সে টিক দিন।</li>
                            <li>উপরে সেই ডাটাবেস ও ইউজারের নাম বসিয়ে সেভ করুন।</li>
                        </ol>
                    </div>

                    <div class="text-center mt-3">
                        <a href="index.php" class="text-secondary small text-decoration-none">← হোমপেজে ফিরে যান</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
