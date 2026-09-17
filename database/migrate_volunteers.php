<?php
/**
 * Migration: Upgrade volunteers table with blood donation opt-in and location
 * Supports both MySQL and SQLite drivers
 */
require_once __DIR__ . '/../config/database.php';

try {
    $pdo = DB::getConnection();
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    $existingColumns = [];

    if ($driver === 'sqlite') {
        $stmt = $pdo->query("PRAGMA table_info(volunteers)");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $existingColumns[] = $row['name'];
        }
    } else {
        $stmt = $pdo->query("SHOW COLUMNS FROM volunteers");
        $existingColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    if (!in_array('agree_blood_donation', $existingColumns)) {
        $pdo->exec("ALTER TABLE volunteers ADD COLUMN agree_blood_donation TINYINT(1) DEFAULT 0");
        echo "Added column agree_blood_donation\n";
    }
    if (!in_array('blood_group', $existingColumns)) {
        $pdo->exec("ALTER TABLE volunteers ADD COLUMN blood_group VARCHAR(10) NULL");
        echo "Added column blood_group\n";
    }
    if (!in_array('port_city', $existingColumns)) {
        $pdo->exec("ALTER TABLE volunteers ADD COLUMN port_city VARCHAR(100) NULL");
        echo "Added column port_city\n";
    }
    if (!in_array('last_donation_date', $existingColumns)) {
        $pdo->exec("ALTER TABLE volunteers ADD COLUMN last_donation_date DATE NULL");
        echo "Added column last_donation_date\n";
    }

    echo "Migration completed successfully on driver: {$driver}!\n";
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
