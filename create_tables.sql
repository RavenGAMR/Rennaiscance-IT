-- create_tables.sql
-- Usage:
-- 1) Edit your DB user/host or run as a user with permission to create databases.
-- 2) Run in MySQL client or phpMyAdmin on your localhost.
-- Example (CLI): mysql -u root -p < create_tables.sql

-- Create database (change name if you prefer)
CREATE DATABASE IF NOT EXISTS `rennaiscance_it` CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
USE `rennaiscance_it`;

-- Users table (stores password hashes)
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Purchases table (records purchases / entitlements)
CREATE TABLE IF NOT EXISTS `purchases` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `product` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX (`user_id`),
  CONSTRAINT `fk_purchases_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Articles table
CREATE TABLE IF NOT EXISTS `articles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `content` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample inserts (manual). You MUST generate a password hash in PHP and replace <hashed_password_here>.
-- To generate a hash on the server, run this PHP one-liner and copy the output:
-- php -r "echo password_hash('adminpass', PASSWORD_DEFAULT)."\n";

-- Example: replace <hashed_password_here> with the produced hash string.
-- INSERT INTO `users` (`username`,`password`,`role`) VALUES ('admin', '<hashed_password_here>', 'admin');

-- Sample articles
INSERT INTO `articles` (`title`,`slug`,`content`) VALUES
('Weblogartikel 1','weblogartikel1','This is the content for weblog article 1 stored in the database.'),
('Weblogartikel 2','weblogartikel2','This is the content for weblog article 2 stored in the database.');

-- Notes:
-- - The SQL does not insert an admin user with a plaintext password. Generate a bcrypt-compatible hash with PHP as shown above.
-- - If you prefer the DB name to be different, change the CREATE DATABASE / USE lines accordingly.
