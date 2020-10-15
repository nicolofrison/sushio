-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Ott 15, 2020 alle 08:09
-- Versione del server: 10.4.11-MariaDB
-- Versione PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sushio`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `blog`
--

CREATE TABLE `blog` (
  `blog_id` int(11) UNSIGNED NOT NULL,
  `blog_title` varchar(100) NOT NULL,
  `blog_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `groups`
--

CREATE TABLE `groups` (
  `group_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `groups`
--

INSERT INTO `groups` (`group_id`, `name`, `password`, `created_at`, `updated_at`) VALUES
(27, 'gruppo', '6ef46601909e9b878798089069cefd63', '2020-10-04 08:20:41', '2020-10-04 08:20:41'),
(28, 'nome', '666ac576aa2666cff323f5d976d592a6', '2020-10-04 12:20:35', '2020-10-04 12:20:35');

-- --------------------------------------------------------

--
-- Struttura della tabella `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `amount` int(11) NOT NULL,
  `checked` tinyint(1) NOT NULL DEFAULT 0,
  `confirmed` int(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `code`, `amount`, `checked`, `confirmed`) VALUES
(33, 35, 'n1', 7, 0, 1),
(34, 35, 'q1', 3, 0, 1),
(35, 35, 'f1', 5, 0, 1),
(36, 35, 'sh1', 2, 0, 1),
(37, 36, 'n2', 2, 0, 1),
(38, 36, 'n6', 3, 0, 1),
(39, 36, 'sh1', 2, 0, 1),
(40, 35, 'n1', 1, 0, 2),
(41, 35, 'n1', 2, 0, 2),
(42, 36, 'n2', 5, 0, 2),
(43, 35, 'n2', 2, 0, 3),
(44, 35, 'n1', 6, 0, 4),
(45, 38, 'n1', 1, 0, 1),
(46, 38, 'n2', 1, 0, 1),
(47, 38, 'h1', 1, 0, 1),
(48, 37, 'n1', 1, 0, 1),
(49, 37, 'n2', 2, 0, 1),
(50, 37, 'h2', 1, 0, 1),
(51, 37, 'h1', 1, 0, 1);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `orders_view`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `orders_view` (
`order_id` int(11)
,`code` varchar(10)
,`amount` int(11)
,`user_id` int(11)
,`name` varchar(50)
,`surname` varchar(50)
,`username` varchar(50)
,`group_id` int(11)
,`checked` tinyint(1)
,`confirmed` int(2)
);

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `group_id` int(11) NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`user_id`, `name`, `surname`, `username`, `group_id`, `confirmed`) VALUES
(35, 'nicolò', 'frison', 'nicolo', 27, 0),
(36, 'A', 'a', 'Name', 27, 0),
(37, 'nicolo', 'frison', 'fritz', 28, 1),
(38, 'Nome', 'Nome', 'Nome', 28, 1);

-- --------------------------------------------------------

--
-- Struttura per vista `orders_view`
--
DROP TABLE IF EXISTS `orders_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `orders_view`  AS  select `o`.`order_id` AS `order_id`,`o`.`code` AS `code`,`o`.`amount` AS `amount`,`u`.`user_id` AS `user_id`,`u`.`name` AS `name`,`u`.`surname` AS `surname`,`u`.`username` AS `username`,`u`.`group_id` AS `group_id`,`o`.`checked` AS `checked`,`o`.`confirmed` AS `confirmed` from (`orders` `o` join `users` `u` on(`o`.`user_id` = `u`.`user_id`)) ;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`blog_id`);

--
-- Indici per le tabelle `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indici per le tabelle `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `FK_user_id_users` (`user_id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `KEY_username_group_id` (`username`,`group_id`) USING BTREE,
  ADD KEY `FK_group_id_groups` (`group_id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `blog`
--
ALTER TABLE `blog`
  MODIFY `blog_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT per la tabella `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_user_id_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_group_id_groups` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE;

DELIMITER $$
--
-- Eventi
--
CREATE DEFINER=`root`@`localhost` EVENT `Check groups deadline` ON SCHEDULE EVERY 1 HOUR STARTS '2020-09-08 15:24:39' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM groups WHERE created_at < (now() - INTERVAL 6 HOUR)$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
