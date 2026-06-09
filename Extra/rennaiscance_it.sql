-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 09 jun 2026 om 14:17
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rennaiscance_it`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `articles`
--

INSERT INTO `articles` (`id`, `title`, `slug`, `content`, `image`, `price`) VALUES
(1, 'Brons', 'Bronze', 'Zonder Reclames', '1780998689_0d47afe8e4b4.png', 10.00),
(2, 'Zilver', 'Silver', 'Bijna Alle Functies', '1780998616_383320b7720f.png', 15.00),
(3, 'Goud', 'Alle Functies', 'Je kan hier alles mee', '1780998465_6c81f1cd61f6.png', 25.00),
(4, 'Platinum', 'Alles', '1 op 1 Gesprekken', '1780999071_215a57af0c58.png', 40.00);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `helpdesk_articles`
--

CREATE TABLE `helpdesk_articles` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT 'email',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `helpdesk_articles`
--

INSERT INTO `helpdesk_articles` (`id`, `title`, `slug`, `content`, `image`, `category`, `created_at`) VALUES
(1, 'Uitgaande mailserver van bestaand e-mail account wijzigen in Mozilla Thunderbird', 'thunderbird-uitgaande', '<p>Handleiding om de uitgaande mailserver van een bestaand account in Thunderbird aan te passen.</p>', 'Media/Algemeen/thund.png', 'email', '2026-06-09 10:22:15'),
(2, 'FTP verbinding instellen met Cyberduck', 'cyberduck-ftp', '<p>Cyberduck is een gebruiksvriendelijke FTP-client waarmee je verbinding maakt met een server en bestanden eenvoudig beheert. We leggen uit hoe je een FTP-verbinding instelt en opslaat voor toekomstig gebruik.</p>', 'Media/Algemeen/mail.png', 'ftp', '2026-06-09 10:22:15'),
(3, 'E-mail adres instellen Mozilla Thunderbird', 'thunderbird-instellen', '<p>We beschrijven stap voor stap hoe je je e-mailaccount instelt in Mozilla Thunderbird, inclusief de juiste serverinstellingen voor verzenden en ontvangen.</p>', 'Media/Algemeen/mail2.webp', 'email', '2026-06-09 10:22:15'),
(4, 'Hoe activeer ik in Horde webmail de prullenmand functie?', 'horde-prullenmand', 'Deze handleiding laat zien hoe je de prullenmandfunctie in Horde webmail activeert, zodat verwijderde berichten niet direct verdwijnen en je eenvoudig berichten kunt terughalen.', 'Media/Algemeen/webmail.png', 'email', '2026-06-09 10:22:15');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `purchases`
--

INSERT INTO `purchases` (`id`, `user_id`, `product`, `created_at`) VALUES
(1, 1, 'weblogartikel1', '2026-06-08 12:47:25'),
(2, 1, 'weblog_access', '2026-06-08 12:47:32'),
(3, 1, 'weblogartikel2', '2026-06-08 12:47:38'),
(4, 1, 'dit is een test', '2026-06-09 08:46:12'),
(5, 1, 'Bronze', '2026-06-09 09:51:51'),
(6, 1, 'Silver', '2026-06-09 09:54:36'),
(7, 1, 'Alles', '2026-06-09 10:05:26'),
(8, 1, 'Alle Functies', '2026-06-09 10:44:27'),
(9, 2, 'Bronze', '2026-06-09 10:59:23');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'test', 'test@gmail.com', '$2y$12$1zqAkUA3EM8pbEZ3AmyXteqag4HCy4ZfhrHygnWAl40NUsjvPySf2', 'admin'),
(2, 'test2', 'test2@gmail.com', '$2y$12$LzlB6ghqs0bfn.x7c9bmxu4liJI2dlYMdnuaXxl3AXsu63579ivYi', 'user'),
(3, 'test3', 'test3@gmail.com', '$2y$12$H2rLZXL/CP0sly2lPR4ELOvvt6ChMjpX3pCJrNbB/MiNLW.smwiGu', 'user');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `weblogs`
--

CREATE TABLE `weblogs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT 'general',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `weblogs`
--

INSERT INTO `weblogs` (`id`, `title`, `slug`, `content`, `image`, `category`, `created_at`) VALUES
(1, 'Test', 'tst', 'fsdafsad', 'uploads/weblogs/1781002927_02fa9f8d5783.png', 'general', '2026-06-09 11:02:07');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexen voor tabel `helpdesk_articles`
--
ALTER TABLE `helpdesk_articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexen voor tabel `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexen voor tabel `weblogs`
--
ALTER TABLE `weblogs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `helpdesk_articles`
--
ALTER TABLE `helpdesk_articles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT voor een tabel `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT voor een tabel `weblogs`
--
ALTER TABLE `weblogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `fk_purchases_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
