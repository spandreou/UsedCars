/* Δημιουργία βάσης (μόνο αν δεν υπάρχει) */
CREATE DATABASE IF NOT EXISTS webcars
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE webcars;

/* Πίνακας users (πλήρης & σωστός) */
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password_hash VARCHAR(128) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    activation_code CHAR(5),
    is_active TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_username (username),
    UNIQUE KEY uq_email (email)
) ENGINE=InnoDB;

/* (Προαιρετικό – μόνο αν υπάρχουν ήδη πίνακες και θες να σιγουρευτείς) */
ALTER TABLE users
MODIFY created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
