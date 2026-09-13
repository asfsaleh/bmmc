<?php
/**
 * BMMC Web-based Database Setup Assistant
 * 
 * Allows setting up the database credentials easily on live cPanel/shared hosting.
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

$configFile = __DIR__ . '/config/database_credentials.php';
$envFile = __DIR__ . '/.env';
$schemaFile = __DIR__ . '/database/schema.sql';
$seedFile = __DIR__ . '/database/seed.php';

$error = null;
$success = null;
$alreadyConnected = false;

// 1. One-click switch to SQLite
if (isset($_GET['action']) && $_GET['action'] === 'use_sqlite') {
    if (file_exists($configFile)) {
        @unlink($configFile);
    }
    // Delete custom MySQL in .env if any
    if (file_exists($envFile)) {
        $env = file_get_contents($envFile);
        $env = preg_replace('/DB_USER=.*/', 'DB_USER=root', $env);
        $env = preg_replace('/DB_PASS=.*/', 'DB_PASS=""', $env);
        file_put_contents($envFile, $env);
    }
    require_once __DIR__ . '/config/database.php';
    DB::getConnection();
    header('Location: index.php');
    exit;
}

// 2. Check existing connection
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

// 3. Handle Form Submission (MySQL Credentials)
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
                "// Saved via Setup Assistant on " . date('Y-m-d H:i:s') . "\n\n" .
                "define('DB_HOST', " . var_export($dbHost, true) . ");\n" .
                "define('DB_PORT', " . var_export($dbPort, true) . ");\n" .
                "define('DB_NAME', " . var_export($dbName, true) . ");\n" .
                "define('DB_USER', " . var_export($dbUser, true) . ");\n" .
                "define('DB_PASS', " . var_export($dbPass, true) . ");\n" .
                "define('DB_CHARSET', 'utf8mb4');\n";

            file_put_contents($configFile, $configContent);

            // Import schema if tables are empty
            $tblStmt = $pdo->query("SHOW TABLES");
            $tables = $tblStmt->fetchAll(PDO::FETCH_COLUMN);

            if (empty($tables) && file_exists($schemaFile)) {
                $sql = file_get_contents($schemaFile);
                $sql = preg_replace('/CREATE DATABASE[^;]+;/i', '', $sql);
                $sql = preg_replace('/USE [^;]+;/i', '', $sql);
                $pdo->exec($sql);

                if (file_exists($seedFile)) {
                    include $seedFile;
                }
            }

            $success = "অভিনন্দন! MySQL ডাটাবেস সফলভাবে সংযুক্ত হয়েছে।";
            $alreadyConnected = true;

            header("refresh:2;url=index.php");

        } catch (PDOException $e) {
            $error = "সংযোগ ব্যর্থ হয়েছে: " . $e->getMessage() . "<br><br><span class='text-warning'>পরামর্শ:</span> cPanel-এ নিশ্চিত করুন যে ডাটাবেস ইউজারের সাথে ডাটাবেস সংযুক্ত করে <strong>ALL PRIVILEGES</strong> দেওয়া হয়েছে।";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ডাটাবেস সেটআপ — BMMC</title>
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
            padding: 24px 0;
            background-image: radial-gradient(at 0% 0%, rgba(19, 64, 116, 0.5) 0px, transparent 50%),
                              radial-gradient(at 100% 100%, rgba(217, 4, 41, 0.2) 0px, transparent 50%);
        }
        .glass-setup {
            background: rgba(11, 37, 69, 0.90);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.6);
        }
        .form-control {
            background: rgba(255, 255, 255, 0.10) !important;
            border: 1px solid rgba(255, 255, 255, 0.25) !important;
            color: #fff !important;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 16px;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.16) !important;
            border-color: #00d2ff !important;
            box-shadow: 0 0 15px rgba(0, 210, 255, 0.4) !important;
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
                        <div style="width: 56px; height: 56px; margin: 0 auto 12px; background: linear-gradient(135deg, #00d2ff, #0284c7); border-radius: 14px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 20px rgba(0, 210, 255, 0.4);">
                            <i class="bi bi-database-check fs-2 text-white"></i>
                        </div>
                        <h3 class="fw-bold text-white mb-1">BMMC ডাটাবেস সেটিংস</h3>
                        <p class="text-light small">নিচে cPanel ডাটাবেস তথ্য দিন অথবা তাৎক্ষণিক চালু করুন</p>
                    </div>

                    <?php if ($success): ?>
                        <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-check-circle-fill fs-3 me-3"></i>
                            <div>
                                <strong>সফল!</strong> <?= $success ?>
                                <small class="d-block mt-1">হোমপেজে নিয়ে যাওয়া হচ্ছে...</small>
                            </div>
                        </div>
                        <div class="text-center mb-4">
                            <a href="index.php" class="btn btn-ocean rounded-pill px-5">এখনই হোমপেজে যান →</a>
                        </div>
                    <?php endif; ?>

                    <?php if ($alreadyConnected && !$success): ?>
                        <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-check-circle-fill fs-3 me-3 text-success"></i>
                            <div>
                                <strong>ডাটাবেস বর্তমানে সংযুক্ত ও সক্রিয় আছে!</strong> প্রয়োজনে নিচে নতুন MySQL তথ্য দিয়ে কনফিগারেশন পরিবর্তন করতে পারেন।
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger d-flex align-items-start mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill fs-3 me-3 flex-shrink-0"></i>
                            <div><?= $error ?></div>
                        </div>
                    <?php endif; ?>

                    <!-- Form for MySQL -->
                    <form method="POST" action="setup.php">
                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label class="form-label text-light small fw-bold">Database Host</label>
                                <input type="text" class="form-control" name="db_host" value="<?= htmlspecialchars($_POST['db_host'] ?? (defined('DB_HOST') ? DB_HOST : 'localhost')) ?>" required>
                                <small class="text-secondary" style="font-size: 0.75rem;">cPanel-এ সাধারণত <code>localhost</code> থাকে</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-light small fw-bold">Port</label>
                                <input type="text" class="form-control" name="db_port" value="<?= htmlspecialchars($_POST['db_port'] ?? (defined('DB_PORT') ? DB_PORT : '3306')) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-light small fw-bold">Database Name (cPanel ডাটাবেসের নাম) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="db_name" value="<?= htmlspecialchars($_POST['db_name'] ?? (defined('DB_NAME') && DB_NAME !== 'bmmc_db' ? DB_NAME : '')) ?>" placeholder="যেমন: skillsetu_bmmc" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-light small fw-bold">Database Username (cPanel ডাটাবেস ইউজার) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="db_user" value="<?= htmlspecialchars($_POST['db_user'] ?? (defined('DB_USER') && DB_USER !== 'root' ? DB_USER : '')) ?>" placeholder="যেমন: skillsetu_dbuser" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-light small fw-bold">Database Password (ডাটাবেস পাসওয়ার্ড) <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="db_pass" placeholder="আপনার ডাটাবেস ইউজারের পাসওয়ার্ড" required>
                        </div>

                        <button type="submit" class="btn btn-ocean w-100 py-3 mb-3">
                            <i class="bi bi-check2-circle me-1"></i> MySQL সংযোগ সংরক্ষণ করুন (Save & Connect)
                        </button>
                    </form>

                    <!-- Instant SQLite Alternative Button -->
                    <div class="text-center my-3">
                        <span class="text-secondary small">— অথবা —</span>
                    </div>

                    <a href="setup.php?action=use_sqlite" class="btn btn-outline-info w-100 py-3 rounded-pill fw-bold">
                        <i class="bi bi-lightning-charge-fill me-1 text-warning"></i> কোনো কনফিগ ছাড়াই সাইট চালু করুন (Instant Mode)
                    </a>

                    <div class="text-center mt-4">
                        <a href="index.php" class="text-secondary small text-decoration-none">← হোমপেজে ফিরে যান</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
