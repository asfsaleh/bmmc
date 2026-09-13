-- =======================================================
-- Bangladesh Merchant Mariners Community (BMMC)
-- Database Schema: bmmc_db
-- =======================================================

CREATE DATABASE IF NOT EXISTS bmmc_db 
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE bmmc_db;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NULL,
    phone VARCHAR(20) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NULL,
    role ENUM('user', 'donor', 'admin') DEFAULT 'user',
    user_type ENUM('mariner', 'general') DEFAULT 'general',
    cdc_sid_no VARCHAR(50) NULL,
    mariner_rank VARCHAR(100) NULL,
    is_verified TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_type (user_type),
    INDEX idx_phone (phone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Donors Table
CREATE TABLE IF NOT EXISTS donors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    blood_group ENUM('A+','A-','B+','B-','O+','O-','AB+','AB-') NOT NULL,
    district VARCHAR(50) NOT NULL,
    area VARCHAR(100) NOT NULL,
    whatsapp VARCHAR(20) NULL,
    is_available TINYINT(1) DEFAULT 1,
    last_donation_date DATE NULL,
    next_available_date DATE NULL,
    total_donations INT DEFAULT 0,
    notes TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_blood_group (blood_group),
    INDEX idx_district (district),
    INDEX idx_availability (is_available, next_available_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Blood Requests Table
CREATE TABLE IF NOT EXISTS blood_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    requester_id INT NULL,
    patient_name VARCHAR(100) NOT NULL,
    blood_group ENUM('A+','A-','B+','B-','O+','O-','AB+','AB-') NOT NULL,
    bags_needed INT DEFAULT 1,
    hospital VARCHAR(200) NOT NULL,
    district VARCHAR(50) NOT NULL,
    area VARCHAR(100) NOT NULL,
    urgency ENUM('normal','urgent','emergency') DEFAULT 'urgent',
    status ENUM('open','matched','fulfilled','cancelled') DEFAULT 'open',
    needed_by DATE NULL,
    contact_name VARCHAR(100) NOT NULL,
    contact_phone VARCHAR(20) NOT NULL,
    contact_email VARCHAR(150) NULL,
    notes TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (requester_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_req_blood (blood_group),
    INDEX idx_req_status (status),
    INDEX idx_req_district (district)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Donor Responses Table (When matching engine notifies donors)
CREATE TABLE IF NOT EXISTS donor_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT NOT NULL,
    request_id INT NOT NULL,
    response_token VARCHAR(64) UNIQUE NOT NULL,
    status ENUM('notified','agreed','declined','expired') DEFAULT 'notified',
    responded_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (donor_id) REFERENCES donors(id) ON DELETE CASCADE,
    FOREIGN KEY (request_id) REFERENCES blood_requests(id) ON DELETE CASCADE,
    INDEX idx_resp_token (response_token),
    INDEX idx_resp_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Donation History Table
CREATE TABLE IF NOT EXISTS donation_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT NOT NULL,
    request_id INT NULL,
    donation_date DATE NOT NULL,
    hospital VARCHAR(200) NULL,
    notes TEXT NULL,
    resting_until DATE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (donor_id) REFERENCES donors(id) ON DELETE CASCADE,
    FOREIGN KEY (request_id) REFERENCES blood_requests(id) ON DELETE SET NULL,
    INDEX idx_history_date (donation_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- OTP Codes Table
CREATE TABLE IF NOT EXISTS otp_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identifier VARCHAR(150) NOT NULL,
    code VARCHAR(10) NOT NULL,
    expires_at DATETIME NOT NULL,
    is_used TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_otp_lookup (identifier, code, is_used)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volunteer Registrations Table
CREATE TABLE IF NOT EXISTS volunteers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    is_mariner TINYINT(1) DEFAULT 0,
    cdc_sid_no VARCHAR(50) NULL,
    rank_designation VARCHAR(100) NULL,
    interest_area VARCHAR(255) NULL,
    message TEXT NULL,
    status ENUM('pending', 'approved', 'active') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Email / Notification Logs
CREATE TABLE IF NOT EXISTS email_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipient_email VARCHAR(150) NOT NULL,
    recipient_name VARCHAR(100) NULL,
    subject VARCHAR(255) NOT NULL,
    body_preview TEXT NULL,
    status ENUM('sent', 'failed', 'simulated') DEFAULT 'sent',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
