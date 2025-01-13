-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Gen 13, 2025 alle 19:20
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
CREATE DATABASE IF NOT EXISTS `prweb1` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `prweb1`;

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
  `date` datetime NOT NULL DEFAULT current_timestamp(),
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
(58, 20, '2025-01-03 19:28:01', 'B2', 1),
(63, 70, '2025-01-05 18:23:17', 'A7', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `order_detail`
--

CREATE TABLE `order_detail` (
  `order_id` int(11) NOT NULL,
  `prod_id` varchar(10) NOT NULL,
  `cur_price` float DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `order_detail`
--

INSERT INTO `order_detail` (`order_id`, `prod_id`, `cur_price`, `quantity`) VALUES
(28, 'A8', 49.99, 2),
(30, 'A7', 70, 1),
(30, 'A8', 49.99, 1),
(32, 'A7', 70, 1),
(32, 'A8', 49.99, 1),
(32, 'B2', 20, 1),
(32, 'E1', 49.99, 1),
(33, 'A7', 70, 1),
(33, 'E1', 49.99, 1),
(34, 'A2', 60, 2),
(34, 'E1', 49.99, 1),
(35, 'A8', 49.99, 1);

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

--
-- Dump dei dati per la tabella `order_head`
--

INSERT INTO `order_head` (`id`, `username`, `date`, `confirmed`) VALUES
(28, 'Sluke', '2025-01-04 13:54:57', 1),
(29, 'Sluke', '2025-01-04 14:28:23', 0),
(30, 'Sluke', '2025-01-04 15:43:48', 1),
(31, 'Sluke', '2025-01-04 15:43:58', 0),
(32, 'Sluke', '2025-01-05 00:00:00', 1),
(33, 'Sluke', '2025-01-05 19:12:14', 1),
(34, 'Sluke', '2025-01-08 19:21:35', 1),
(35, 'Sluke', '2025-01-08 19:21:59', 1),
(36, 'Sluke', '2025-01-13 18:27:17', 0);

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
  `active` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `product`
--

INSERT INTO `product` (`id`, `title`, `type_id`, `descr`, `bpm`, `tonality`, `genre`, `num_sample`, `num_tracks`, `audiopath`, `productpath`, `active`) VALUES
('A2', 'Neve', 1, 'A composition usually sold to singer or rapper to sing onto', 150, 'E', 'Hip Hop', 0, 0, '../AUDIO/Sluke_-_Neve.mp3', '../PRODUCTS/Sluke_-_Neve.mp3', 1),
('A7', 'Dark Road', 1, '-', 130, 'G#', 'Trap', 0, 0, '../AUDIO/Sluke_-_Dark_Road.mp3', '../PRODUCTS/Sluke_-_Dark_Road.mp3', 1),
('A8', 'Waves', 1, 'chitarrina ns', 150, 'G#', 'Trap', 0, 0, '../AUDIO/waves.mp3', '../PRODUCTS/waves.mp3', 1),
('A9', 'Sun In Our Eyes REMIX', 1, '-', 173, 'G#m', 'DNB', 0, 0, '../AUDIO/Sun_In_Our_Eyes_RMX.mp3', '../PRODUCTS/Sun_In_Our_Eyes_RMX.mp3', 1),
('B2', 'Fake Drum Kit', 2, '', 0, 'nul', 'Trap', 6, 0, '../AUDIO/among-us-role-reveal-sound.mp3', '../PRODUCTS/RISATA_ANDRE.mp3', 1),
('C1', 'Fake Sample pack', 3, 'Jamiaca inspired loops', 0, 'nul', 'Reggaeton', 4, 0, '../AUDIO/1F2F.mp3', '../PRODUCTS/RISATA_ANDRE.mp3', 1),
('D1', 'Sloothe', 4, 'Poor mans\' mid-side trackspacer, but for FL Studio patcher', 0, 'nul', 'Effect: Dyn eq', 0, 0, '../AUDIO/IN_ALTO_MARE_RMX.mp3', '../PRODUCTS/SLOOTHE.fst', 1),
('E1', 'Basic master', 5, 'I\'ll Master your mixed track!', 0, 'nul', 'Any', 0, 1, '../AUDIO/Zen_-_Overdose__MASTER_.mp3', '../PRODUCTS/RISATA_ANDRE.mp3', 1),
('F1', 'EDM Ghost production', 6, '', 0, 'nul', 'EDM', 0, 0, '../AUDIO/mind_rmx.mp3', '../PRODUCTS/error_CDOxCYm.mp3', 1);

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
('Anna', 'anna@anna.it', '$2y$10$LAmOWIkDB7iy1qL8mRHDtuPPmoLlyU0SVZKW18I.K4N2LuOaSMek2', 0, 0, 0),
('Evilsluko', 'bobmsda@fa.ga', '$2y$10$piK46kTTr1wwQnm7Ae3s0OKpPCnkI1rjbJ49KTmZ1xdr/NgRPR0zG', 0, 1, 0),
('Marius', 'marius@marius.it', '$2y$10$M2Y7HFRO6z2N/K9Gb7Pqgu0MpMBLIgbiXDnu5cUXioXsGHoCNQWIG', 1, 1, 0),
('MMesiti', 'mesiti@mesiti.it', '$2y$10$ZerH2OVmzhcsR8cbXDo4CeurLEeByoos19SrY/iZQEbzVTfw0Mjdy', 1, 1, 0),
('Sluke', 'lukeskystabi@gmail.com', '$2y$10$a//3F0DgiSKcdXTHoRgo5OLCA5xc7OeUCNHicYpZmRrT48.jo3z.C', 1, 1, 0),
('Sonne', 'SONNE@SONNE.IT', '$2y$10$cv0j.O.wlbpjYGZAAnFhDOtsFnPLJxkg/i.TzKLwPi4RnEj3HoKWC', 1, 1, 0);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT per la tabella `order_head`
--
ALTER TABLE `order_head`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

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
