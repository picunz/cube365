-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 31.11.39.98:3306
-- Creato il: Lug 15, 2025 alle 11:38
-- Versione del server: 5.7.44-48-log
-- Versione PHP: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Sql1669990_2`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `Calendar`
--

CREATE TABLE `Calendar` (
  `Id` int(11) NOT NULL,
  `Date` date NOT NULL,
  `SideLeft` varchar(100) DEFAULT NULL,
  `SideRight` varchar(100) DEFAULT NULL,
  `SideFront` varchar(100) DEFAULT NULL,
  `SideBack` varchar(100) DEFAULT NULL,
  `SideBase` varchar(100) DEFAULT NULL,
  `SideTop` varchar(100) DEFAULT NULL,
  `CurrentState` int(10) NOT NULL,
  `UserId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `Device_48CA43598F54`
--

CREATE TABLE `Device_48CA43598F54` (
  `Id` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Activities` json DEFAULT NULL,
  `LastUpdate` datetime(6) NOT NULL,
  `Created` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `Device_48CA43598F54`
--

INSERT INTO `Device_48CA43598F54` (`Id`, `Date`, `Activities`, `LastUpdate`, `Created`) VALUES
(30, '2025-03-30', '[{\"Name\": \"RIGHT\", \"Side\": \"RIGHT\", \"Time\": \"23:50:48\", \"Type\": \"-\", \"CounterSec\": 0, \"ElapsedTime\": \"00:00:00\"}, {\"Name\": \"FRONT\", \"Side\": \"FRONT\", \"Time\": \"23:50:49\", \"Type\": \"-\", \"CounterSec\": 0, \"ElapsedTime\": \"00:00:00\"}]', '2025-03-30 23:50:49.000000', '2025-03-30 23:50:48.000000'),
(31, '2025-03-31', '[{\"Name\": \"STi\", \"Side\": \"FRONT\", \"Time\": \"13:31:18\", \"Type\": \"Bar\", \"CounterSec\": 47934, \"ElapsedTime\": \"13:18:54\"}, {\"Name\": \"BACK\", \"Side\": \"BACK\", \"Time\": \"09:37:37\", \"Type\": \"-\", \"CounterSec\": 33849, \"ElapsedTime\": \"09:24:09\"}, {\"Name\": \"RIGHT\", \"Side\": \"RIGHT\", \"Time\": \"13:20:29\", \"Type\": \"-\", \"CounterSec\": 13456, \"ElapsedTime\": \"03:44:16\"}, {\"Name\": \"BASE\", \"Side\": \"BASE\", \"Time\": \"09:37:45\", \"Type\": \"-\", \"CounterSec\": 66, \"ElapsedTime\": \"00:01:06\"}, {\"Name\": \"A\", \"Side\": \"LEFT\", \"Time\": \"13:21:57\", \"Type\": \"B\", \"CounterSec\": 21, \"ElapsedTime\": \"00:00:21\"}, {\"Name\": \"NO_SIDE\", \"Side\": \"NO_SIDE\", \"Time\": \"17:07:34\", \"Type\": \"-\", \"CounterSec\": 10, \"ElapsedTime\": \"00:00:10\"}, {\"Name\": \"Toppino \", \"Side\": \"TOP\", \"Time\": \"23:59:57\", \"Type\": \"Tipino\", \"CounterSec\": 24705, \"ElapsedTime\": \"06:51:45\"}]', '2025-03-31 23:59:57.000000', '2025-03-31 00:12:24.000000'),
(32, '2025-04-01', '[{\"Name\": \"Toppino \", \"Side\": \"TOP\", \"Time\": \"08:20:09\", \"Type\": \"Tipino\", \"CounterSec\": 30002, \"ElapsedTime\": \"08:20:02\"}]', '2025-04-01 08:20:09.000000', '2025-04-01 00:00:07.000000'),
(33, '2025-04-11', '[{\"Name\": \"Toppino \", \"Side\": \"TOP\", \"Time\": \"22:50:38\", \"Type\": \"Tipino\", \"CounterSec\": 25275, \"ElapsedTime\": \"07:01:15\"}]', '2025-04-11 22:50:38.000000', '2025-04-11 15:49:23.000000'),
(34, '2025-04-23', '[{\"Name\": \"Toppino \", \"Side\": \"TOP\", \"Time\": \"00:50:49\", \"Type\": \"Tipino\", \"CounterSec\": 0, \"ElapsedTime\": \"00:00:00\"}, {\"Name\": \"BASE\", \"Side\": \"BASE\", \"Time\": \"00:50:51\", \"Type\": \"-\", \"CounterSec\": 0, \"ElapsedTime\": \"00:00:00\"}, {\"Name\": \"BACK\", \"Side\": \"BACK\", \"Time\": \"00:50:52\", \"Type\": \"-\", \"CounterSec\": 0, \"ElapsedTime\": \"00:00:00\"}]', '2025-04-23 00:50:52.000000', '2025-04-23 00:50:49.000000'),
(35, '2025-05-14', '[{\"Name\": \"MULTIPLE_SIDE\", \"Side\": \"MULTIPLE_SIDE\", \"Time\": \"08:44:47\", \"Type\": \"-\", \"CounterSec\": 0, \"ElapsedTime\": \"00:00:00\"}]', '2025-05-14 08:44:47.000000', '2025-05-14 08:44:47.000000'),
(36, '2025-06-09', '[{\"Name\": \"Toppino \", \"Side\": \"TOP\", \"Time\": \"23:54:35\", \"Type\": \"Tipino\", \"CounterSec\": 1401, \"ElapsedTime\": \"00:23:21\"}]', '2025-06-09 23:54:35.000000', '2025-06-09 23:31:14.000000'),
(37, '2025-06-10', '[{\"Name\": \"Toppino \", \"Side\": \"TOP\", \"Time\": \"23:59:52\", \"Type\": \"Tipino\", \"CounterSec\": 85847, \"ElapsedTime\": \"23:50:47\"}]', '2025-06-10 23:59:52.000000', '2025-06-10 00:09:05.000000'),
(38, '2025-06-11', '[{\"Name\": \"Toppino \", \"Side\": \"TOP\", \"Time\": \"23:59:57\", \"Type\": \"Tipino\", \"CounterSec\": 86395, \"ElapsedTime\": \"23:59:55\"}]', '2025-06-11 23:59:57.000000', '2025-06-11 00:00:02.000000'),
(39, '2025-06-12', '[{\"Name\": \"Toppino \", \"Side\": \"TOP\", \"Time\": \"23:16:10\", \"Type\": \"Tipino\", \"CounterSec\": 83763, \"ElapsedTime\": \"23:16:03\"}]', '2025-06-12 23:16:10.000000', '2025-06-12 00:00:07.000000'),
(40, '2025-06-13', '[{\"Name\": \"Toppino \", \"Side\": \"TOP\", \"Time\": \"09:09:07\", \"Type\": \"Tipino\", \"CounterSec\": 2974, \"ElapsedTime\": \"00:49:34\"}]', '2025-06-13 09:09:07.000000', '2025-06-13 08:19:33.000000'),
(41, '2025-06-16', '[{\"Name\": \"Toppino \", \"Side\": \"TOP\", \"Time\": \"21:57:32\", \"Type\": \"Tipino\", \"CounterSec\": 1501, \"ElapsedTime\": \"00:25:01\"}]', '2025-06-16 21:57:32.000000', '2025-06-16 21:32:31.000000'),
(42, '2025-06-18', '[{\"Name\": \"Toppino \", \"Side\": \"TOP\", \"Time\": \"23:23:59\", \"Type\": \"Tipino\", \"CounterSec\": 7926, \"ElapsedTime\": \"02:12:06\"}]', '2025-06-18 23:23:59.000000', '2025-06-18 21:11:53.000000'),
(43, '2025-06-19', '[{\"Name\": \"Toppino \", \"Side\": \"TOP\", \"Time\": \"00:12:05\", \"Type\": \"Tipino\", \"CounterSec\": 21, \"ElapsedTime\": \"00:00:21\"}]', '2025-06-19 00:12:05.000000', '2025-06-19 00:11:44.000000'),
(44, '2025-07-06', '[{\"Name\": \"Toppino \", \"Side\": \"TOP\", \"Time\": \"23:57:23\", \"Type\": \"Tipino\", \"CounterSec\": 51856, \"ElapsedTime\": \"14:24:16\"}]', '2025-07-06 23:57:23.000000', '2025-07-06 09:33:07.000000'),
(45, '2025-07-07', '[{\"Name\": \"Toppino \", \"Side\": \"TOP\", \"Time\": \"23:42:28\", \"Type\": \"Tipino\", \"CounterSec\": 85129, \"ElapsedTime\": \"23:38:49\"}, {\"Name\": \"A\", \"Side\": \"LEFT\", \"Time\": \"23:42:37\", \"Type\": \"B\", \"CounterSec\": 84124, \"ElapsedTime\": \"23:22:04\"}, {\"Name\": \"ASA\", \"Side\": \"RIGHT\", \"Time\": \"23:42:09\", \"Type\": \"sss\", \"CounterSec\": 84095, \"ElapsedTime\": \"23:21:35\"}, {\"Name\": \"STi\", \"Side\": \"FRONT\", \"Time\": \"23:42:33\", \"Type\": \"Bar\", \"CounterSec\": 84117, \"ElapsedTime\": \"23:21:57\"}, {\"Name\": \"MULTIPLE_SIDE\", \"Side\": \"MULTIPLE_SIDE\", \"Time\": \"00:20:40\", \"Type\": \"-\", \"CounterSec\": 0, \"ElapsedTime\": \"00:00:00\"}, {\"Name\": \"NO_SIDE\", \"Side\": \"NO_SIDE\", \"Time\": \"00:20:41\", \"Type\": \"-\", \"CounterSec\": 0, \"ElapsedTime\": \"00:00:00\"}, {\"Name\": \"BACK\", \"Side\": \"BACK\", \"Time\": \"23:42:21\", \"Type\": \"-\", \"CounterSec\": 51924, \"ElapsedTime\": \"14:25:24\"}, {\"Name\": \"BASE\", \"Side\": \"BASE\", \"Time\": \"23:42:47\", \"Type\": \"-\", \"CounterSec\": 6745, \"ElapsedTime\": \"01:52:25\"}]', '2025-07-07 23:42:47.000000', '2025-07-07 00:03:39.000000'),
(48, '2025-07-08', '[{\"Name\": \"BASE\", \"Side\": \"BASE\", \"Time\": \"23:37:49\", \"Type\": \"-\", \"CounterSec\": 717, \"ElapsedTime\": \"00:11:57\"}, {\"Name\": \"NO_SIDE\", \"Side\": \"NO_SIDE\", \"Time\": \"23:41:24\", \"Type\": \"-\", \"CounterSec\": 925, \"ElapsedTime\": \"00:15:25\"}, {\"Name\": \"A\", \"Side\": \"LEFT\", \"Time\": \"23:36:57\", \"Type\": \"B\", \"CounterSec\": 367, \"ElapsedTime\": \"00:06:07\"}, {\"Name\": \"STi\", \"Side\": \"FRONT\", \"Time\": \"23:32:26\", \"Type\": \"Bar\", \"CounterSec\": 56, \"ElapsedTime\": \"00:00:56\"}, {\"Name\": \"Toppino \", \"Side\": \"TOP\", \"Time\": \"23:40:51\", \"Type\": \"Tipino\", \"CounterSec\": 541, \"ElapsedTime\": \"00:09:01\"}, {\"Name\": \"ASA\", \"Side\": \"RIGHT\", \"Time\": \"23:40:10\", \"Type\": \"sss\", \"CounterSec\": 494, \"ElapsedTime\": \"00:08:14\"}, {\"Name\": \"BACK\", \"Side\": \"BACK\", \"Time\": \"23:40:32\", \"Type\": \"-\", \"CounterSec\": 452, \"ElapsedTime\": \"00:07:32\"}, {\"Name\": \"MULTIPLE_SIDE\", \"Side\": \"MULTIPLE_SIDE\", \"Time\": \"23:37:20\", \"Type\": \"-\", \"CounterSec\": 178, \"ElapsedTime\": \"00:02:58\"}]', '2025-07-08 23:41:24.000000', '2025-07-08 23:25:52.000000'),
(49, '2025-07-09', '[{\"Name\": \"NO_SIDE\", \"Side\": \"NO_SIDE\", \"Time\": \"23:39:33\", \"Type\": \"-\", \"Counter\": 0, \"CounterSec\": 263, \"ElapsedTime\": \"00:04:23\"}, {\"Name\": \"A\", \"Side\": \"LEFT\", \"Time\": \"23:39:27\", \"Type\": \"B\", \"Counter\": 0, \"CounterSec\": 0, \"ElapsedTime\": \"00:00:00\"}, {\"Name\": \"Toppino \", \"Side\": \"TOP\", \"Time\": \"23:39:40\", \"Type\": \"Tipino\", \"Counter\": 0, \"CounterSec\": 3, \"ElapsedTime\": \"00:00:03\"}, {\"Name\": \"BASE\", \"Side\": \"BASE\", \"Time\": \"23:45:30\", \"Type\": \"-\", \"Counter\": 0, \"CounterSec\": 344, \"ElapsedTime\": \"00:05:44\"}, {\"Name\": \"MULTIPLE_SIDE\", \"Side\": \"MULTIPLE_SIDE\", \"Time\": \"23:43:50\", \"Type\": \"-\", \"Counter\": 0, \"CounterSec\": 238, \"ElapsedTime\": \"00:03:58\"}]', '2025-07-09 23:45:30.000000', '2025-07-09 23:35:10.000000'),
(50, '2025-07-10', '[{\"Name\": \"BASE\", \"Side\": \"BASE\", \"Time\": \"23:54:12\", \"Type\": \"-\", \"Counter\": 0, \"CounterSec\": 191, \"ElapsedTime\": \"00:03:11\"}, {\"Name\": \"ASA\", \"Side\": \"RIGHT\", \"Time\": \"23:51:37\", \"Type\": \"sss\", \"Counter\": 0, \"CounterSec\": 0, \"ElapsedTime\": \"00:00:00\"}, {\"Name\": \"A\", \"Side\": \"LEFT\", \"Time\": \"23:52:51\", \"Type\": \"B\", \"Counter\": 0, \"CounterSec\": 66, \"ElapsedTime\": \"00:01:06\"}, {\"Name\": \"Toppino \", \"Side\": \"TOP\", \"Time\": \"23:53:40\", \"Type\": \"Tipino\", \"Counter\": 0, \"CounterSec\": 15, \"ElapsedTime\": \"00:00:15\"}]', '2025-07-10 23:54:12.000000', '2025-07-10 23:51:01.000000'),
(53, '2025-07-11', '[{\"Name\": \"BASE\", \"Perc\": 0, \"Side\": \"BASE\", \"Time\": \"23:45:21\", \"Type\": \"-\", \"Counter\": 200, \"CounterSec\": 2000, \"ElapsedTime\": \"00:33:20\"}, {\"Name\": \"Toppino \", \"Side\": \"TOP\", \"Time\": \"23:35:58\", \"Type\": \"Tipino\", \"Counter\": 1, \"CounterSec\": 10, \"ElapsedTime\": \"00:00:10\"}, {\"Name\": \"ASA\", \"Side\": \"RIGHT\", \"Time\": \"22:27:07\", \"Type\": \"sss\", \"Counter\": 19, \"CounterSec\": 190, \"ElapsedTime\": \"00:03:10\"}, {\"Name\": \"NO_SIDE\", \"Side\": \"NO_SIDE\", \"Time\": \"23:57:52\", \"Type\": \"-\", \"Counter\": 29, \"CounterSec\": 290, \"ElapsedTime\": \"00:04:50\"}, {\"Name\": \"STi\", \"Side\": \"FRONT\", \"Time\": \"23:42:01\", \"Type\": \"Bar\", \"Counter\": 127, \"CounterSec\": 1270, \"ElapsedTime\": \"00:21:10\"}, {\"Name\": \"MULTIPLE_SIDE\", \"Side\": \"MULTIPLE_SIDE\", \"Time\": \"23:36:48\", \"Type\": \"-\", \"Counter\": 17, \"CounterSec\": 170, \"ElapsedTime\": \"00:02:50\"}]', '2025-07-11 23:57:52.000000', '2025-07-11 22:17:28.000000'),
(57, '2025-07-13', '[{\"Name\": \"NO_SIDE\", \"Side\": \"NO_SIDE\", \"Time\": \"23:10:50\", \"Type\": \"-\", \"Counter\": 46, \"CounterSec\": 460, \"ElapsedTime\": \"00:07:40\"}, {\"Name\": \"BASE\", \"Side\": \"BASE\", \"Time\": \"23:59:50\", \"Type\": \"-\", \"Counter\": 360, \"CounterSec\": 3600, \"ElapsedTime\": \"01:00:00\"}, {\"Name\": \"STi\", \"Side\": \"FRONT\", \"Time\": \"22:56:25\", \"Type\": \"Bar\", \"Counter\": 1666, \"CounterSec\": 16660, \"ElapsedTime\": \"04:37:40\"}, {\"Name\": \"MULTIPLE_SIDE\", \"Side\": \"MULTIPLE_SIDE\", \"Time\": \"23:10:57\", \"Type\": \"-\", \"Counter\": 155, \"CounterSec\": 1550, \"ElapsedTime\": \"00:25:50\"}, {\"Name\": \"ASA\", \"Side\": \"RIGHT\", \"Time\": \"23:03:04\", \"Type\": \"sss\", \"Counter\": 15, \"CounterSec\": 150, \"ElapsedTime\": \"00:02:30\"}, {\"Name\": \"Toppino \", \"Side\": \"TOP\", \"Time\": \"22:01:38\", \"Type\": \"Tipino\", \"Counter\": 2, \"CounterSec\": 20, \"ElapsedTime\": \"00:00:20\"}, {\"Name\": \"A\", \"Side\": \"LEFT\", \"Time\": \"21:50:24\", \"Type\": \"B\", \"Counter\": 25, \"CounterSec\": 250, \"ElapsedTime\": \"00:04:10\"}, {\"Name\": \"BACK\", \"Side\": \"BACK\", \"Time\": \"22:56:35\", \"Type\": \"-\", \"Counter\": 1, \"CounterSec\": 10, \"ElapsedTime\": \"00:00:10\"}]', '2025-07-13 23:59:50.000000', '2025-07-13 17:05:13.000000'),
(58, '2025-07-14', '[{\"Name\": \"BASE\", \"Side\": \"BASE\", \"Time\": \"14:04:25\", \"Type\": \"-\", \"Counter\": 3152, \"CounterSec\": 31520, \"ElapsedTime\": \"08:45:20\"}, {\"Name\": \"MULTIPLE_SIDE\", \"Side\": \"MULTIPLE_SIDE\", \"Time\": \"14:00:44\", \"Type\": \"-\", \"Counter\": 89, \"CounterSec\": 890, \"ElapsedTime\": \"00:14:50\"}, {\"Name\": \"BACK\", \"Side\": \"BACK\", \"Time\": \"12:11:14\", \"Type\": \"-\", \"Counter\": 22, \"CounterSec\": 220, \"ElapsedTime\": \"00:03:40\"}, {\"Name\": \"NO_SIDE\", \"Side\": \"NO_SIDE\", \"Time\": \"14:08:51\", \"Type\": \"-\", \"Counter\": 187, \"CounterSec\": 1870, \"ElapsedTime\": \"00:31:10\"}, {\"Name\": \"Toppino \", \"Side\": \"TOP\", \"Time\": \"14:04:35\", \"Type\": \"Tipino\", \"Counter\": 711, \"CounterSec\": 7110, \"ElapsedTime\": \"01:58:30\"}, {\"Name\": \"A\", \"Side\": \"LEFT\", \"Time\": \"13:57:14\", \"Type\": \"B\", \"Counter\": 41, \"CounterSec\": 410, \"ElapsedTime\": \"00:06:50\"}, {\"Name\": \"ASA\", \"Side\": \"RIGHT\", \"Time\": \"12:03:44\", \"Type\": \"sss\", \"Counter\": 1, \"CounterSec\": 10, \"ElapsedTime\": \"00:00:10\"}, {\"Name\": \"STi\", \"Side\": \"FRONT\", \"Time\": \"13:50:54\", \"Type\": \"Bar\", \"Counter\": 7, \"CounterSec\": 70, \"ElapsedTime\": \"00:01:10\"}]', '2025-07-14 14:08:51.000000', '2025-07-14 00:00:00.000000');

-- --------------------------------------------------------

--
-- Struttura della tabella `Notifications`
--

CREATE TABLE `Notifications` (
  `Id` int(10) NOT NULL,
  `UserId` int(10) DEFAULT NULL,
  `Title` varchar(100) NOT NULL,
  `Message` varchar(10000) NOT NULL,
  `Type` varchar(6) NOT NULL,
  `IsRead` tinyint(1) NOT NULL,
  `ResourceId` int(11) DEFAULT NULL,
  `WhenRead` datetime DEFAULT NULL,
  `WhenCreated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `Status`
--

CREATE TABLE `Status` (
  `UserId` int(10) NOT NULL,
  `Side` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `Status`
--

INSERT INTO `Status` (`UserId`, `Side`) VALUES
(0, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `Tasks`
--

CREATE TABLE `Tasks` (
  `Id` int(11) NOT NULL,
  `DeviceId` varchar(20) NOT NULL,
  `Content` json NOT NULL,
  `Description` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `Tasks`
--

INSERT INTO `Tasks` (`Id`, `DeviceId`, `Content`, `Description`) VALUES
(18, '48CA43598F54', '[{\"Name\": \"A\", \"Side\": \"LEFT\", \"Type\": \"B\"}, {\"Name\": \"STi\", \"Side\": \"FRONT\", \"Type\": \"Bar\"}, {\"Name\": \"Toppino \", \"Side\": \"TOP\", \"Type\": \"Tipino\"}, {\"Name\": \"ASA\", \"Side\": \"RIGHT\", \"Type\": \"sss\"}]', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `Test`
--

CREATE TABLE `Test` (
  `A` int(11) NOT NULL,
  `B` int(11) NOT NULL,
  `C` int(11) NOT NULL,
  `D` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `Users`
--

CREATE TABLE `Users` (
  `Id` int(10) NOT NULL,
  `Email` varchar(200) NOT NULL,
  `Name` varchar(200) NOT NULL,
  `Surname` varchar(200) NOT NULL,
  `Hash` varchar(250) DEFAULT NULL,
  `Verified` tinyint(1) NOT NULL DEFAULT '0',
  `AuthCode` int(11) DEFAULT NULL,
  `Role` varchar(20) NOT NULL DEFAULT 'USR',
  `AvatarPath` varchar(512) DEFAULT NULL,
  `Info` json DEFAULT NULL,
  `WhenCreated` datetime DEFAULT NULL,
  `LastActivity` datetime DEFAULT NULL,
  `LastAccess` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `Users`
--

INSERT INTO `Users` (`Id`, `Email`, `Name`, `Surname`, `Hash`, `Verified`, `AuthCode`, `Role`, `AvatarPath`, `Info`, `WhenCreated`, `LastActivity`, `LastAccess`) VALUES
(21, 'kiand64@gmail.com', 'Andrea', 'Chiappori', '$2y$10$nHzTzwP5DaYeeFcx0hVD1OgB2r8wlSWQA1PPXl1tdao.rl9famd8C', 1, 7013, 'USR', NULL, NULL, '2024-12-21 10:19:39', NULL, '2024-12-21 10:19:39'),
(39, 'andreviola77@gmail.com', 'Andrea', 'Viola', '$2y$10$mDU5rm6tZ3f16XNRdwQ1UefCsp/CbihGP1d/IyWFjsRzOKJx7EMsm', 1, 8209, 'USR', NULL, NULL, '2025-01-07 14:19:50', NULL, '2025-01-07 14:19:50');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `Calendar`
--
ALTER TABLE `Calendar`
  ADD PRIMARY KEY (`Id`);

--
-- Indici per le tabelle `Device_48CA43598F54`
--
ALTER TABLE `Device_48CA43598F54`
  ADD PRIMARY KEY (`Id`);

--
-- Indici per le tabelle `Notifications`
--
ALTER TABLE `Notifications`
  ADD PRIMARY KEY (`Id`);

--
-- Indici per le tabelle `Tasks`
--
ALTER TABLE `Tasks`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `DeviceId` (`DeviceId`),
  ADD UNIQUE KEY `DeviceId_2` (`DeviceId`);

--
-- Indici per le tabelle `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `Calendar`
--
ALTER TABLE `Calendar`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Device_48CA43598F54`
--
ALTER TABLE `Device_48CA43598F54`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT per la tabella `Notifications`
--
ALTER TABLE `Notifications`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `Tasks`
--
ALTER TABLE `Tasks`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=426;

--
-- AUTO_INCREMENT per la tabella `Users`
--
ALTER TABLE `Users`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
