-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Gen 03, 2025 alle 22:09
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prweb1`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `list_head`
--

CREATE TABLE `list_head` (
  `id` int(11) NOT NULL,
  `descr` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `list_head`
--

INSERT INTO `list_head` (`id`, `descr`) VALUES
(1, 'Standard');

-- --------------------------------------------------------

--
-- Struttura della tabella `list_prices`
--

CREATE TABLE `list_prices` (
  `id` int(11) NOT NULL,
  `price` float NOT NULL,
  `date` datetime NOT NULL,
  `prod_id` varchar(10) NOT NULL,
  `list_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `list_prices`
--

INSERT INTO `list_prices` (`id`, `price`, `date`, `prod_id`, `list_id`) VALUES
(2, 60, '2024-11-08 01:53:21', 'A2', 1),
(3, 70, '2024-11-08 01:53:52', 'A7', 1),
(33, 65, '2024-11-10 19:33:20', 'A2', 1),
(34, 30, '2024-11-10 19:35:26', 'A7', 1),
(35, 65, '2024-11-10 19:35:47', 'A2', 1),
(36, 75, '2024-11-10 19:45:07', 'A7', 1),
(37, 60, '2024-11-10 21:49:28', 'A2', 1),
(38, 19.99, '2024-11-11 20:40:45', 'A8', 1),
(39, 49.99, '2024-11-11 23:22:51', 'A8', 1),
(41, 24.99, '2024-11-11 23:28:17', 'C1', 1),
(42, 49.99, '2024-11-11 23:42:30', 'E1', 1),
(45, 0, '2024-11-12 01:54:31', 'D1', 1),
(46, 0, '2024-11-12 01:55:11', 'C1', 1),
(56, 0, '2024-11-13 10:59:11', 'A9', 1),
(57, 500, '2025-01-03 19:14:53', 'F1', 1),
(58, 20, '2025-01-03 19:28:01', 'B2', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `order_detail`
--

CREATE TABLE `order_detail` (
  `order_id` int(11) NOT NULL,
  `prod_id` varchar(10) NOT NULL,
  `cur_price` float NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `order_head`
--

CREATE TABLE `order_head` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `confirmed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `product`
--

CREATE TABLE `product` (
  `id` varchar(10) NOT NULL,
  `title` varchar(50) NOT NULL,
  `type_id` int(11) NOT NULL,
  `descr` text NOT NULL,
  `bpm` int(11) DEFAULT NULL,
  `tonality` varchar(3) DEFAULT NULL,
  `genre` varchar(20) DEFAULT NULL,
  `num_sample` int(11) DEFAULT NULL,
  `num_tracks` int(11) DEFAULT NULL,
  `audiopath` varchar(255) DEFAULT NULL,
  `productpath` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `product`
--

INSERT INTO `product` (`id`, `title`, `type_id`, `descr`, `bpm`, `tonality`, `genre`, `num_sample`, `num_tracks`, `audiopath`, `productpath`, `active`) VALUES
('A2', 'Neve', 1, 'A composition usually sold to singer or rapper to sing onto', 140, 'E', 'Emo Trap', 0, 0, '../AUDIO/Sluke - Neve.mp3', '../PRODUCTS/Sluke - Neve.wav', 1),
('A7', 'Dark Road', 1, '-', 132, 'Cm', 'Trap', 0, 0, '../AUDIO/Sluke - Dark Road.mp3', '../PRODUCTS/Sluke - Dark Road.wav', 1),
('A8', 'Waves', 1, 'chitarrina ns', 145, 'Gm', 'Trap', 0, 0, '../AUDIO/waves.mp3', '../PRODUCTS/waves.mp3', 1),
('A9', 'Sun In Our Eyes REMIX', 1, '-', 174, 'G#m', 'DNB', 0, 0, '../AUDIO/Sun In Our Eyes RMX.mp3', '../PRODUCTS/Sun In Our Eyes RMX.mp3', 1),
('B2', 'Fake Drum Kit', 2, '', 0, 'nul', 'Trap', 1, 0, '../AUDIO/ding-sound-effect_2.mp3', '../PRODUCTS/Fake Drum Kit.txt', 1),
('C1', 'Fake Sample pack', 3, 'Jamiaca inspired loops', 0, 'nul', 'Raggae', 1, 0, '../AUDIO/Baby Keem & Kendrick Lamar - Family Ties (Distorzion Remix).mp3', '', 1),
('D1', 'Sloothe', 4, 'Poor mans\' mid-side trackspacer, but for FL Studio patcher', 0, 'nul', 'Effect: Dyn eq', 0, 0, '../AUDIO/IN ALTO MARE RMX.mp3', '../PRODUCTS/SLOOTHE.fst', 1),
('E1', 'Basic master', 5, 'I\'ll Master your mixed track!', 0, 'nul', 'Any', 0, 1, '../AUDIO/Zen - Overdose (MASTER).mp3', '../PRODUCTS/mixmasterSluke.txt', 1),
('F1', 'EDM Ghost production', 6, '', NULL, 'C', 'EDM', NULL, NULL, '../AUDIO/mind rmx.mp3', '../PRODUCTS/mixmasterSluke.txt', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `type`
--

CREATE TABLE `type` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `descr` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `type`
--

INSERT INTO `type` (`id`, `name`, `descr`) VALUES
(1, 'Beat/Instrumental', 'A composition usually sold to singer or rapper to sing onto'),
(2, 'Drum Kit', 'A digital pack for producer which contains drum samples or more effect'),
(3, 'Sample Pack', 'A digital pack for producer which contains melody loops'),
(4, 'Plugin', 'A software used for sound manipulation or generation'),
(5, 'Mix & Master', 'Mixing: This is the process of combining all individual tracks of a recording (like vocals, drums, guitars, etc.) and adjusting their levels, panning, and effects (e.g., reverb, compression) to create a balanced and polished sound. The goal is to make each instrument clear and balanced in the context of the full song.\r\n\r\nMastering: This is the final stage of audio production. In mastering, the mixed track is processed to ensure it sounds consistent across different playback systems (like headphones, car stereos, and speakers) and has a competitive loudness level. It involves fine-tuning the overall EQ, compression, limiting, and sometimes stereo widening to prepare the track for distribution.\r\n\r\nTogether, \"Mix & Master\" gives a track its final professional quality, making it ready for streaming, radio, or physical release.'),
(6, 'Ghost Production', 'Ghost production is when a producer creates a music track that is credited to another artist or DJ instead of themselves. Essentially, the ghost producer is paid to create the track but remains anonymous, allowing the client (usually a DJ, artist, or record label) to release it under their own name.\r\n\r\nGhost production is common in genres like electronic dance music (EDM), hip-hop, and pop, where the demand for new tracks is high. Some artists and DJs use ghost producers to maintain their release schedules or to explore different sounds without being limited by their own production skills.\r\n\r\nIn this arrangement:\r\n\r\nThe ghost producer typically receives a one-time fee for the track or an ongoing share of royalties, depending on the agreement.\r\nThe client takes full credit for the song\'s production and is free to promote and perform it as their own.');

-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

CREATE TABLE `user` (
  `username` varchar(20) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `authorized` tinyint(1) NOT NULL DEFAULT 0,
  `blocked` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `user`
--

INSERT INTO `user` (`username`, `mail`, `pwd`, `admin`, `authorized`, `blocked`) VALUES
('Marius', 'marius@marius.it', '$2y$10$M2Y7HFRO6z2N/K9Gb7Pqgu0MpMBLIgbiXDnu5cUXioXsGHoCNQWIG', 1, 1, 0),
('Master', 'mfs_mgv@yahoo.com', '$2y$10$zMkyDM.mNfzhaBZ1HH81SOjNxTwftVL3jv1MYQ8N3svsdjm0hGgXC', 0, 1, 0),
('MMesiti', 'mesiti@mesiti.it', '$2y$10$ZerH2OVmzhcsR8cbXDo4CeurLEeByoos19SrY/iZQEbzVTfw0Mjdy', 1, 1, 0),
('Sluke', 'lukeskystabi@gmail.com', '$2y$10$a//3F0DgiSKcdXTHoRgo5OLCA5xc7OeUCNHicYpZmRrT48.jo3z.C', 1, 1, 0),
('Sonne', 'SONNE@SONNE.IT', '$2y$10$cv0j.O.wlbpjYGZAAnFhDOtsFnPLJxkg/i.TzKLwPi4RnEj3HoKWC', 1, 1, 0),
('Tiopio1', 'lamiasamu2013@libero.it', '$2y$10$4ef2F12QV/Bmy1iC7Wbn3.3wkQHvlZFu.xKhLNP/F2wdzysxOdSoW', 0, 1, 0),
('user1', 'user1@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user10', 'user10@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user14', 'user14@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user15', 'user15@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user16', 'user16@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user17', 'user17@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user18', 'user18@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user19', 'user19@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user2', 'user2@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user20', 'user20@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user21', 'user21@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user22', 'user22@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user23', 'user23@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user24', 'user24@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user25', 'user25@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user26', 'user26@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user27', 'user27@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user28', 'user28@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user29', 'user29@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user3', 'user3@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 0, 1),
('user30', 'user30@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user4', 'user4@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user5', 'user5@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user6', 'user6@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user7', 'user7@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user8', 'user8@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0),
('user9', 'user9@example.com', '$2y$10$HQ6QrIvLVbvAbo.G/ut4QumvKpl8ApHhR68/JcYfsI3imXt1t7kp6', 0, 1, 0);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `list_head`
--
ALTER TABLE `list_head`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `list_prices`
--
ALTER TABLE `list_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `price_prod` (`prod_id`),
  ADD KEY `list` (`list_id`);

--
-- Indici per le tabelle `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`order_id`,`prod_id`),
  ADD KEY `order_prod` (`prod_id`);

--
-- Indici per le tabelle `order_head`
--
ALTER TABLE `order_head`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_user` (`username`);

--
-- Indici per le tabelle `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type_id`);

--
-- Indici per le tabelle `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `list_prices`
--
ALTER TABLE `list_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT per la tabella `order_head`
--
ALTER TABLE `order_head`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT per la tabella `type`
--
ALTER TABLE `type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `list_prices`
--
ALTER TABLE `list_prices`
  ADD CONSTRAINT `list` FOREIGN KEY (`list_id`) REFERENCES `list_head` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `price_prod` FOREIGN KEY (`prod_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_internal` FOREIGN KEY (`order_id`) REFERENCES `order_head` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_prod` FOREIGN KEY (`prod_id`) REFERENCES `product` (`id`);

--
-- Limiti per la tabella `order_head`
--
ALTER TABLE `order_head`
  ADD CONSTRAINT `order_user` FOREIGN KEY (`username`) REFERENCES `user` (`username`);

--
-- Limiti per la tabella `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `type` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
