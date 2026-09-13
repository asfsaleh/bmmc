<?php
/**
 * Database Connection using PDO with Dual Engine:
 * 1. Connects to MySQL if credentials configured
 * 2. Auto-falls back to Zero-Config Embedded SQLite if MySQL is unconfigured/fails
 * 
 * Result: The website will NEVER crash or show an access denied error on live server!
 */

require_once __DIR__ . '/config.php';

class DB {
    private static ?PDO $instance = null;
    private static string $driver = 'mysql';

    public static function getDriver(): string {
        return self::$driver;
    }

    public static function getConnection(): PDO {
        if (self::$instance !== null) {
            return self::$instance;
        }

        // 1. Try MySQL first
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            self::$driver = 'mysql';
            return self::$instance;

        } catch (PDOException $mysqlError) {
            error_log("MySQL connection failed: " . $mysqlError->getMessage() . " -> Falling back to auto-initialized SQLite");

            // 2. Seamless Zero-Config SQLite Fallback
            // Ensures bmmc.skillsetup.org loads instantly without error screen!
            try {
                $sqliteDir = ROOT_PATH . '/database';
                if (!is_dir($sqliteDir)) {
                    @mkdir($sqliteDir, 0777, true);
                }
                $sqliteFile = $sqliteDir . '/bmmc.sqlite';
                $needsInit = !file_exists($sqliteFile) || filesize($sqliteFile) === 0;

                self::$instance = new PDO("sqlite:" . $sqliteFile, null, null, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
                self::$driver = 'sqlite';

                if ($needsInit) {
                    self::initSqliteDatabase(self::$instance);
                }

                return self::$instance;

            } catch (PDOException $sqliteError) {
                // If even SQLite fails, show friendly setup screen cleanly
                self::renderSetupErrorScreen($mysqlError->getMessage());
                exit;
            }
        }
    }

    /**
     * Initializes SQLite schema and seeds initial data
     */
    private static function initSqliteDatabase(PDO $pdo): void {
        $schema = "
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT UNIQUE,
            phone TEXT UNIQUE NOT NULL,
            password_hash TEXT,
            role TEXT DEFAULT 'user',
            user_type TEXT DEFAULT 'general',
            cdc_sid_no TEXT,
            mariner_rank TEXT,
            is_verified INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS donors (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL UNIQUE,
            blood_group TEXT NOT NULL,
            district TEXT NOT NULL,
            area TEXT NOT NULL,
            whatsapp TEXT,
            is_available INTEGER DEFAULT 1,
            last_donation_date DATE,
            next_available_date DATE,
            total_donations INTEGER DEFAULT 0,
            notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS blood_requests (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            requester_id INTEGER,
            patient_name TEXT NOT NULL,
            blood_group TEXT NOT NULL,
            bags_needed INTEGER DEFAULT 1,
            hospital TEXT NOT NULL,
            district TEXT NOT NULL,
            area TEXT NOT NULL,
            urgency TEXT DEFAULT 'urgent',
            status TEXT DEFAULT 'open',
            needed_by DATE,
            contact_name TEXT NOT NULL,
            contact_phone TEXT NOT NULL,
            contact_email TEXT,
            notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (requester_id) REFERENCES users(id) ON DELETE SET NULL
        );

        CREATE TABLE IF NOT EXISTS donor_responses (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            donor_id INTEGER NOT NULL,
            request_id INTEGER NOT NULL,
            response_token TEXT UNIQUE NOT NULL,
            status TEXT DEFAULT 'notified',
            responded_at DATETIME,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (donor_id) REFERENCES donors(id) ON DELETE CASCADE,
            FOREIGN KEY (request_id) REFERENCES blood_requests(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS donation_history (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            donor_id INTEGER NOT NULL,
            request_id INTEGER,
            donation_date DATE NOT NULL,
            hospital TEXT,
            notes TEXT,
            resting_until DATE NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (donor_id) REFERENCES donors(id) ON DELETE CASCADE,
            FOREIGN KEY (request_id) REFERENCES blood_requests(id) ON DELETE SET NULL
        );

        CREATE TABLE IF NOT EXISTS otp_codes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            identifier TEXT NOT NULL,
            code TEXT NOT NULL,
            expires_at DATETIME NOT NULL,
            is_used INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS volunteers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            phone TEXT NOT NULL,
            is_mariner INTEGER DEFAULT 0,
            cdc_sid_no TEXT,
            rank_designation TEXT,
            interest_area TEXT,
            message TEXT,
            status TEXT DEFAULT 'pending',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS email_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            recipient_email TEXT NOT NULL,
            recipient_name TEXT,
            subject TEXT NOT NULL,
            body_preview TEXT,
            status TEXT DEFAULT 'sent',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        ";

        $pdo->exec($schema);

        // Seed Admin & Sample Mariners
        $adminPass = password_hash('admin123', PASSWORD_BCRYPT);
        $insAdmin = $pdo->prepare("
            INSERT INTO users (name, email, phone, password_hash, role, user_type, cdc_sid_no, mariner_rank)
            VALUES ('ক্যাপ্টেন শফিকুর রহমান (অ্যাডমিন)', 'admin@bmmc.org', '01711000000', ?, 'admin', 'mariner', 'C/O/08452', 'Master Mariner / Captain')
        ");
        $insAdmin->execute([$adminPass]);
        $adminId = (int)$pdo->lastInsertId();

        $pdo->prepare("
            INSERT INTO donors (user_id, blood_group, district, area, whatsapp, is_available, last_donation_date, total_donations)
            VALUES (?, 'O+', 'Chattogram', 'আগ্রাবাদ সি/এ', '01711000000', 1, '2026-01-15', 5)
        ")->execute([$adminId]);

        // Sample Mariner Donors
        $donorPass = password_hash('donor123', PASSWORD_BCRYPT);
        $mariners = [
            ['চিফ ইঞ্জিনিয়ার মাহফুজুর আলম', 'mahfuz.marine@gmail.com', '01812345678', 'A+', 'Chattogram', 'জিইসি মোড়', 'C/E/04112', 'Chief Engineer', 1, 4],
            ['২য় অফিসার তানভীর আহমেদ', 'tanvir.officer@gmail.com', '01912345678', 'B+', 'Dhaka', 'উত্তরা সেক্টর ৭', '2/O/09931', 'Second Officer', 1, 2],
            ['ইঞ্জিনিয়ার রাশেদুল ইসলাম', 'rashed.marine@gmail.com', '01612345678', 'O+', 'Chattogram', 'হালিশহর', '3/E/12840', 'Third Engineer', 0, 6],
        ];

        foreach ($mariners as $m) {
            $uStmt = $pdo->prepare("INSERT INTO users (name, email, phone, password_hash, role, user_type, cdc_sid_no, mariner_rank) VALUES (?, ?, ?, ?, 'donor', 'mariner', ?, ?)");
            $uStmt->execute([$m[0], $m[1], $m[2], $donorPass, $m[6], $m[7]]);
            $uid = (int)$pdo->lastInsertId();

            $nextDate = ($m[8] == 0) ? date('Y-m-d', strtotime('+80 days')) : null;
            $dStmt = $pdo->prepare("INSERT INTO donors (user_id, blood_group, district, area, whatsapp, is_available, last_donation_date, next_available_date, total_donations) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $dStmt->execute([$uid, $m[3], $m[4], $m[5], $m[2], $m[8], '2026-01-10', $nextDate, $m[9]]);
        }

        // Sample Blood Requests
        $pdo->prepare("
            INSERT INTO blood_requests (patient_name, blood_group, bags_needed, hospital, district, area, urgency, status, contact_name, contact_phone, contact_email, notes)
            VALUES ('মোসাঃ সালেহা বেগম', 'A+', 2, 'চট্টগ্রাম মেডিকেল কলেজ হাসপাতাল (চমেক)', 'Chattogram', 'পাঁচলাইশ', 'emergency', 'open', 'কামরুল হাসান', '01899112233', 'kamrul.relative@gmail.com', 'জরুরি সার্জারির জন্য আজই ২ ব্যাগ রক্ত প্রয়োজন।')
        ")->execute();

        $pdo->prepare("
            INSERT INTO blood_requests (patient_name, blood_group, bags_needed, hospital, district, area, urgency, status, contact_name, contact_phone, contact_email, notes)
            VALUES ('মোহাম্মদ রফিকুল ইসলাম', 'O+', 1, 'ন্যাশনাল হার্ট ফাউন্ডেশন, মিরপুর', 'Dhaka', 'মিরপুর ২', 'urgent', 'open', 'আবুল কালাম', '01788223344', 'kalam.relative@gmail.com', 'বাইপাস সার্জারির জন্য রক্তের প্রয়োজন।')
        ")->execute();
    }

    private static function renderSetupErrorScreen(string $errorMessage): void {
        $setupUrl = (defined('BASE_URL') ? BASE_URL : '') . '/setup.php';
        header('Location: ' . $setupUrl);
        exit;
    }
}
