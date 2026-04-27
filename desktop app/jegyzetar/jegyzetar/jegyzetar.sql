-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2026 at 01:41 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jegyzetar`
--

-- --------------------------------------------------------

--
-- Table structure for table `2fa_codes`
--

CREATE TABLE `2fa_codes` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- Dumping data for table `2fa_codes`
--

INSERT INTO `2fa_codes` (`id`, `userid`, `code`) VALUES
(6, 4, 82329),
(8, 4, 26092),
(9, 4, 87859),
(13, 5, 90064),
(20, 1, 21959),
(21, 1, 64985),
(22, 1, 47713),
(28, 1, 42618),
(29, 1, 15302);

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

CREATE TABLE `badges` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `slug` varchar(64) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `icon` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `badges`
--

INSERT INTO `badges` (`id`, `name`, `slug`, `description`, `icon`) VALUES
(1, 'Tulajdonos', 'owner', 'Az oldal tulajdonosa', '🔰'),
(2, 'Prémium tag', 'premium', 'Prémium tagsággal rendelkezik', '💎');

-- --------------------------------------------------------

--
-- Table structure for table `bug_reports`
--

CREATE TABLE `bug_reports` (
  `id` int(11) NOT NULL,
  `category` enum('bug','feature','abuse','other') NOT NULL,
  `title` varchar(120) NOT NULL,
  `description` text NOT NULL,
  `page_url` varchar(255) DEFAULT NULL,
  `steps` text DEFAULT NULL,
  `expected_result` text DEFAULT NULL,
  `actual_result` text DEFAULT NULL,
  `priority` enum('low','medium','high','critical') DEFAULT 'medium',
  `user_id` int(11) DEFAULT NULL,
  `contact_email` varchar(190) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  `text` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `userid`, `postid`, `text`) VALUES
(1, 1, 3, 'easy peasy'),
(2, 33, 8, 'Ez segített!'),
(3, 34, 38, 'Köszi!'),
(4, 17, 18, 'Nagyon jó!'),
(5, 11, 11, 'Van folytatás?'),
(6, 27, 24, 'Ez segített!'),
(7, 23, 30, 'Ez segített!'),
(8, 37, 46, 'Van folytatás?'),
(9, 26, 6, 'Van folytatás?'),
(10, 25, 37, 'Nagyon jó!'),
(11, 38, 35, 'Ez segített!'),
(12, 34, 20, 'Ez segített!'),
(13, 12, 2, 'Van folytatás?'),
(14, 23, 28, 'Ez segített!'),
(15, 20, 27, 'Nem teljesen értem'),
(16, 27, 1, 'Köszi!'),
(17, 22, 15, 'Nagyon jó!'),
(18, 34, 35, 'Van folytatás?'),
(19, 32, 46, 'Köszi!'),
(20, 24, 39, 'Köszi!'),
(21, 23, 11, 'Nem teljesen értem'),
(22, 30, 20, 'Van folytatás?'),
(23, 30, 24, 'Köszi!'),
(24, 14, 42, 'Nem teljesen értem'),
(25, 37, 26, 'Nem teljesen értem'),
(26, 22, 37, 'Ez segített!'),
(27, 36, 7, 'Nagyon jó!'),
(28, 33, 41, 'Nem teljesen értem'),
(29, 17, 50, 'Köszi!'),
(30, 12, 42, 'Van folytatás?'),
(31, 33, 20, 'Ez segített!'),
(32, 33, 10, 'Ez segített!'),
(33, 13, 2, 'Nem teljesen értem'),
(34, 34, 39, 'Köszi!'),
(35, 26, 30, 'Köszi!'),
(36, 35, 18, 'Nagyon jó!'),
(37, 32, 15, 'Nagyon jó!'),
(38, 11, 34, 'Köszi!'),
(39, 13, 46, 'Köszi!'),
(40, 19, 45, 'Ez segített!'),
(42, 21, 8, 'Nem teljesen értem'),
(43, 30, 30, 'Van folytatás?'),
(44, 32, 49, 'Nem teljesen értem'),
(45, 20, 40, 'Van folytatás?'),
(46, 11, 33, 'Nagyon jó!'),
(47, 21, 34, 'Köszi!'),
(48, 11, 30, 'Van folytatás?'),
(49, 24, 40, 'Ez segített!'),
(50, 15, 20, 'Ez segített!'),
(51, 10, 37, 'Nem teljesen értem'),
(52, 37, 37, 'Van folytatás?'),
(53, 17, 28, 'Nagyon jó!'),
(54, 27, 38, 'Van folytatás?'),
(55, 31, 29, 'Nem teljesen értem'),
(57, 14, 23, 'Van folytatás?'),
(58, 31, 10, 'Nem teljesen értem'),
(59, 19, 14, 'Köszi!'),
(60, 11, 8, 'Nem teljesen értem'),
(61, 30, 27, 'Ez segített!'),
(62, 15, 17, 'Nagyon jó!'),
(63, 20, 26, 'Ez segített!'),
(64, 16, 24, 'Nem teljesen értem'),
(65, 36, 21, 'Köszi!'),
(66, 32, 27, 'Ez segített!'),
(67, 30, 50, 'Van folytatás?'),
(68, 29, 22, 'Köszi!'),
(69, 36, 38, 'Nagyon jó!'),
(70, 14, 26, 'Nagyon jó!'),
(71, 10, 35, 'Ez segített!'),
(72, 17, 44, 'Nem teljesen értem'),
(73, 24, 30, 'Ez segített!'),
(74, 26, 16, 'Van folytatás?'),
(75, 27, 11, 'Köszi!'),
(76, 10, 1, 'Nagyon jó!'),
(77, 21, 30, 'Van folytatás?'),
(78, 24, 43, 'Nem teljesen értem'),
(79, 20, 19, 'Van folytatás?'),
(80, 10, 30, 'Van folytatás?'),
(81, 28, 26, 'Nem teljesen értem'),
(83, 31, 28, 'Nem teljesen értem'),
(84, 23, 18, 'Ez segített!'),
(85, 10, 44, 'Köszi!'),
(86, 35, 16, 'Nagyon jó!'),
(87, 20, 29, 'Nem teljesen értem'),
(88, 17, 43, 'Ez segített!'),
(89, 14, 7, 'Köszi!'),
(90, 32, 4, 'Nagyon jó!'),
(91, 17, 1, 'Köszi!'),
(92, 21, 7, 'Ez segített!'),
(93, 32, 22, 'Van folytatás?'),
(94, 16, 19, 'Köszi!'),
(96, 15, 18, 'Köszi!'),
(97, 13, 47, 'Köszi!'),
(98, 26, 47, 'Nagyon jó!'),
(99, 21, 42, 'Van folytatás?'),
(100, 18, 28, 'Van folytatás?'),
(101, 35, 29, 'Köszi!');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `sender_name` varchar(255) NOT NULL,
  `sender_email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` longtext NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `read_by_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deleted_users`
--

CREATE TABLE `deleted_users` (
  `id` int(11) NOT NULL,
  `original_id` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  `email` varchar(255) NOT NULL,
  `firstname` varchar(128) DEFAULT NULL,
  `lastname` varchar(128) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `registration_date` datetime DEFAULT NULL,
  `was_admin` tinyint(1) NOT NULL DEFAULT 0,
  `was_teacher` tinyint(1) NOT NULL DEFAULT 0,
  `was_premium` tinyint(1) NOT NULL DEFAULT 0,
  `upload_count` int(11) NOT NULL DEFAULT 0,
  `download_count` int(11) NOT NULL DEFAULT 0,
  `deleted_by` int(11) NOT NULL COMMENT 'admin users.id aki törölte',
  `deleted_at` datetime NOT NULL DEFAULT current_timestamp(),
  `reason` text DEFAULT NULL COMMENT 'opcionális indoklás'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deleted_users`
--

INSERT INTO `deleted_users` (`id`, `original_id`, `username`, `email`, `firstname`, `lastname`, `birthdate`, `registration_date`, `was_admin`, `was_teacher`, `was_premium`, `upload_count`, `download_count`, `deleted_by`, `deleted_at`, `reason`) VALUES
(1, 39, 'user39', 'user39@test.hu', 'Anna', 'Molnár', '2004-04-15', '2026-01-16 09:41:20', 0, 0, 0, 1, 0, 1, '2026-03-30 09:25:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `file_id`, `created_at`) VALUES
(1, 4, 1, '2025-12-02 10:54:38'),
(3, 1, 2, '2026-01-17 16:36:51'),
(5, 1, 3, '2026-02-21 21:05:14'),
(6, 1, 5, '2026-02-25 13:21:43');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(1024) DEFAULT NULL,
  `tags` varchar(255) NOT NULL,
  `tn_name` varchar(255) DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `download_count` int(11) NOT NULL DEFAULT 0,
  `content_text` longtext DEFAULT NULL,
  `edu_stage` enum('hs','uni') DEFAULT NULL,
  `edu_level` tinyint(4) DEFAULT NULL,
  `external_url` varchar(255) NOT NULL,
  `content_type` enum('file','note') NOT NULL DEFAULT 'file',
  `note_markdown` mediumtext DEFAULT NULL,
  `note_excerpt` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `uploaded_by`, `name`, `file_name`, `description`, `file_path`, `tags`, `tn_name`, `file_size`, `download_count`, `content_text`, `edu_stage`, `edu_level`, `external_url`, `content_type`, `note_markdown`, `note_excerpt`) VALUES
(1, 15, 'Jegyzet #1', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'uni', 3, '', 'note', '# Jegyzet #1\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 15\n', NULL),
(2, 11, 'Jegyzet #2', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'hs', 13, '', 'note', '# Jegyzet #2\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 11\n', NULL),
(3, 33, 'Jegyzet #3', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'uni', 7, '', 'note', '# Jegyzet #3\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 33\n', NULL),
(4, 31, 'Jegyzet #4', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'uni', 3, '', 'note', '# Jegyzet #4\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 31\n', NULL),
(5, 16, 'Jegyzet #5', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'uni', 5, '', 'note', '# Jegyzet #5\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 16\n', NULL),
(6, 37, 'Jegyzet #6', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'hs', 11, '', 'note', '# Jegyzet #6\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 37\n', NULL),
(7, 37, 'Jegyzet #7', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'uni', 7, '', 'note', '# Jegyzet #7\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 37\n', NULL),
(8, 34, 'Jegyzet #8', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'uni', 5, '', 'note', '# Jegyzet #8\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 34\n', NULL),
(9, 21, 'Jegyzet #9', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'uni', 6, '', 'note', '# Jegyzet #9\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 21\n', NULL),
(10, 22, 'Jegyzet #10', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'uni', 6, '', 'note', '# Jegyzet #10\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 22\n', NULL),
(11, 27, 'Jegyzet #12', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'uni', 3, '', 'note', '# Jegyzet #12\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 27\n', NULL),
(12, 10, 'Jegyzet #13', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'uni', 2, '', 'note', '# Jegyzet #13\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 10\n', NULL),
(13, 21, 'Jegyzet #14', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'uni', 3, '', 'note', '# Jegyzet #14\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 21\n', NULL),
(14, 34, 'Jegyzet #15', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'hs', 11, '', 'note', '# Jegyzet #15\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 34\n', NULL),
(15, 38, 'Jegyzet #16', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'hs', 12, '', 'note', '# Jegyzet #16\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 38\n', NULL),
(16, 17, 'Jegyzet #17', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'uni', 4, '', 'note', '# Jegyzet #17\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 17\n', NULL),
(17, 24, 'Jegyzet #18', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'uni', 7, '', 'note', '# Jegyzet #18\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 24\n', NULL),
(18, 26, 'Jegyzet #19', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'hs', 9, '', 'note', '# Jegyzet #19\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 26\n', NULL),
(19, 22, 'Jegyzet #20', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'hs', 13, '', 'note', '# Jegyzet #20\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 22\n', NULL),
(20, 21, 'Jegyzet #21', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'uni', 7, '', 'note', '# Jegyzet #21\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 21\n', NULL),
(21, 31, 'Jegyzet #22', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'hs', 9, '', 'note', '# Jegyzet #22\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 31\n', NULL),
(22, 22, 'Jegyzet #23', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'uni', 4, '', 'note', '# Jegyzet #23\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 22\n', NULL),
(23, 38, 'Jegyzet #24', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'hs', 12, '', 'note', '# Jegyzet #24\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 38\n', NULL),
(24, 24, 'Jegyzet #25', NULL, 'Részletes tananyag jegyzet', NULL, 'matek,programozas', NULL, NULL, 0, NULL, 'hs', 11, '', 'note', '# Jegyzet #25\n\n## 📚 Bevezetés\nEz a jegyzet a következő témát dolgozza fel: **matek,programozas**.\n\n## 🧠 Fő fogalmak\n- Alapfogalom 1\n- Alapfogalom 2\n- Fontos összefüggések\n\n## ✏️ Példa\n```js\nfunction pelda() {\n  return \"Ez egy példa\";\n}\n```\n\n## ⚡ Összefoglalás\n> Ez a jegyzet segít megérteni az alapokat.\n\n---\n👤 Feltöltő ID: 24\n', NULL),
(25, 1, 'PDF Minta Dokumentum', 'pdf-sample_0.pdf', 'Ez egy teszt PDF fájl a jegyzetar alkalmazáshoz.', 'C:\\xampp\\htdocs\\jegyzetar.eu-src\\src\\users\\ceci\\pdf-sample_0.pdf', 'minta,teszt,pdf', NULL, 102400, 0, NULL, NULL, NULL, '', 'file', NULL, NULL),
(26, 1, 'Matematika Jegyzet', 'matek_1.pdf', 'Matematika alapok - algebrai kifejezések', 'C:\\xampp\\htdocs\\jegyzetar.eu-src\\src\\users\\ceci\\pdf-sample_0.pdf', 'matematika,algebra,jegyzet', NULL, 256000, 0, NULL, NULL, NULL, '', 'file', NULL, NULL),
(27, 1, 'Fizika Összefoglaló', 'fizika_summary.pdf', 'Fizika tételek összefoglalója érettségire', 'C:\\xampp\\htdocs\\jegyzetar.eu-src\\src\\users\\ceci\\pdf-sample_0.pdf', 'fizika,érettségi,összefoglaló', NULL, 189440, 0, NULL, NULL, NULL, '', 'file', NULL, NULL),
(28, 1, 'Történelem Jegyzet', 'tortenelem_19szazad.pdf', '19. századi európai történelem', 'C:\\xampp\\htdocs\\jegyzetar.eu-src\\src\\users\\ceci\\pdf-sample_0.pdf', 'történelem,19.század,európa', NULL, 345600, 0, NULL, NULL, NULL, '', 'file', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `file_events`
--

CREATE TABLE `file_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `file_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_type` enum('view','download','favorite_add','favorite_remove','rate','comment','report') NOT NULL,
  `rating` tinyint(4) DEFAULT NULL,
  `ip` varbinary(16) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `file_events`
--

INSERT INTO `file_events` (`id`, `file_id`, `user_id`, `event_type`, `rating`, `ip`, `user_agent`, `created_at`) VALUES
(1, 2, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-17 15:50:28'),
(2, 2, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-17 16:04:33'),
(3, 2, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-17 16:34:47'),
(4, 2, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36', '2026-01-17 18:09:20'),
(5, 2, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36', '2026-01-17 18:19:57'),
(6, 2, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-17 19:58:01'),
(7, 2, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-24 23:09:54'),
(8, 2, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-08 22:58:04'),
(9, 2, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-10 09:12:17'),
(10, 2, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 17:02:32'),
(11, 2, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-18 21:21:11'),
(12, 2, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-18 21:39:57'),
(13, 3, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-19 00:52:53'),
(14, 2, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-19 09:21:08'),
(15, 3, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-19 09:21:19'),
(16, 3, 1, 'favorite_add', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-19 09:27:51'),
(17, 3, 1, 'favorite_remove', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-19 09:27:53'),
(18, 3, 1, 'comment', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-19 09:28:06'),
(19, 3, 1, 'rate', 4, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-19 09:28:29'),
(20, 3, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-19 09:37:23'),
(21, 3, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-21 21:00:08'),
(22, 3, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-21 22:27:19'),
(23, 3, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-21 22:49:54'),
(24, 3, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-21 23:07:58'),
(25, 3, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-23 17:48:11'),
(26, 4, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-24 09:34:46'),
(27, 3, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-24 09:35:03'),
(28, 3, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-24 09:45:11'),
(29, 4, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-24 09:45:16'),
(30, 4, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-25 13:05:41'),
(31, 5, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-25 13:14:25'),
(32, 5, 1, 'rate', 5, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-25 13:21:40'),
(33, 5, 1, 'favorite_add', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-25 13:21:43'),
(34, 5, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-25 13:30:33'),
(35, 5, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-26 20:55:35'),
(36, 5, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-02 02:40:26'),
(37, 5, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-09 22:14:53'),
(38, 5, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-16 19:43:11'),
(39, 30, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-29 23:40:31'),
(40, 1, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-31 00:51:55'),
(41, 1, 1, 'favorite_add', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-31 00:53:22'),
(42, 1, 1, 'favorite_remove', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-31 00:53:24'),
(43, 1, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-31 01:03:23'),
(44, 24, 1, 'view', NULL, 0x00000000000000000000000000000001, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-31 01:18:36');

-- --------------------------------------------------------

--
-- Table structure for table `file_stats_daily`
--

CREATE TABLE `file_stats_daily` (
  `file_id` int(11) NOT NULL,
  `day` date NOT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `downloads` int(11) NOT NULL DEFAULT 0,
  `favorites` int(11) NOT NULL DEFAULT 0,
  `ratings_count` int(11) NOT NULL DEFAULT 0,
  `ratings_sum` int(11) NOT NULL DEFAULT 0,
  `flashcards` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `id` int(11) NOT NULL,
  `fromid` int(11) NOT NULL,
  `toid` int(11) NOT NULL,
  `status` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`id`, `fromid`, `toid`, `status`) VALUES
(1, 8, 1, 1),
(2, 33, 39, 1),
(3, 29, 17, 1),
(4, 18, 29, 1),
(5, 22, 13, 1),
(6, 19, 15, 1),
(7, 12, 33, 1),
(8, 31, 16, 1),
(9, 39, 18, 1),
(10, 22, 17, 1),
(11, 38, 12, 1);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `owner_id` int(11) NOT NULL,
  `is_private` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `reviewed_at` datetime DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`, `owner_id`, `is_private`, `created_at`, `reviewed_at`, `reviewed_by`, `status`) VALUES
(1, 'Java Dolgozat felkészítő', 'Java dolgozatokra való felkészítés zajlik itt', 1, 0, '2026-02-10 09:10:06', '2026-02-12 10:50:42', 1, 'approved'),
(2, 'C# dolgozat felkészítő', 'Gyertek szísárpozni', 1, 0, '2026-03-16 10:46:37', '2026-03-16 10:46:45', 1, 'approved'),
(3, 'Kémia dolgozat felkészito', 'felkészülés a nagy dolgozatra', 10, 0, '2026-03-16 23:05:42', '2026-03-16 23:06:14', 10, 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `group_comments`
--

CREATE TABLE `group_comments` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `group_comments`
--

INSERT INTO `group_comments` (`id`, `group_id`, `user_id`, `comment_text`, `created_at`) VALUES
(1, 3, 10, 'Szerdán lesz a doga ezt ki is raktam eseményekbe!', '2026-03-16 23:07:30');

-- --------------------------------------------------------

--
-- Table structure for table `group_events`
--

CREATE TABLE `group_events` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` datetime NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `group_events`
--

INSERT INTO `group_events` (`id`, `group_id`, `created_by`, `title`, `description`, `event_date`, `created_at`) VALUES
(1, 3, 10, 'Dolgozat', 'Mindenki készüljön!', '2025-09-17 10:00:00', '2026-03-16 23:08:19');

-- --------------------------------------------------------

--
-- Table structure for table `group_files`
--

CREATE TABLE `group_files` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `group_files`
--

INSERT INTO `group_files` (`id`, `group_id`, `uploaded_by`, `name`, `description`, `file_name`, `created_at`, `is_approved`) VALUES
(1, 3, 10, 'Kémia tananyag', 'Ebbol tanuljatok', 'Mesterséges intelligencia vállalati alkalmazása – HR szakemberi kérdőív.pdf', '2026-03-16 23:06:59', 1);

-- --------------------------------------------------------

--
-- Table structure for table `group_file_comments`
--

CREATE TABLE `group_file_comments` (
  `id` int(11) NOT NULL,
  `group_file_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `group_file_comments`
--

INSERT INTO `group_file_comments` (`id`, `group_file_id`, `user_id`, `comment_text`, `created_at`) VALUES
(1, 1, 10, 'asd', '2026-03-16 23:58:41');

-- --------------------------------------------------------

--
-- Table structure for table `group_flashcards`
--

CREATE TABLE `group_flashcards` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `created_at` datetime NOT NULL,
  `correct_count` int(11) NOT NULL DEFAULT 0,
  `wrong_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `group_flashcards`
--

INSERT INTO `group_flashcards` (`id`, `group_id`, `created_by`, `question`, `answer`, `created_at`, `correct_count`, `wrong_count`) VALUES
(1, 1, 10, 'asd', 'asd', '2026-02-24 01:48:55', 2, 0),
(2, 1, 10, 'fgh', 'fgh', '2026-02-24 01:49:02', 2, 0),
(3, 2, 10, 'asd', 'asd', '2026-02-24 01:49:36', 2, 0),
(4, 2, 10, 'fgh', 'fgh', '2026-02-24 01:49:41', 2, 0),
(5, 3, 10, 'Szén vegyjele?', 'C', '2026-03-16 23:08:51', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE `group_members` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` enum('owner','moderator','member') NOT NULL DEFAULT 'member',
  `status` enum('accepted','pending') NOT NULL DEFAULT 'accepted',
  `joined_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `group_members`
--

INSERT INTO `group_members` (`id`, `group_id`, `user_id`, `role`, `status`, `joined_at`) VALUES
(1, 0, 1, 'owner', 'accepted', '2026-02-10 09:10:06'),
(2, 2, 1, 'owner', 'accepted', '2026-03-16 10:46:37'),
(3, 2, 10, 'member', 'accepted', '2026-03-16 23:04:49'),
(4, 3, 10, 'owner', 'accepted', '2026-03-16 23:05:43'),
(5, 3, 11, 'member', 'accepted', '2026-03-16 23:33:19'),
(6, 2, 11, 'member', 'accepted', '2026-03-16 23:35:30');

-- --------------------------------------------------------

--
-- Table structure for table `group_polls`
--

CREATE TABLE `group_polls` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `closed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_poll_options`
--

CREATE TABLE `group_poll_options` (
  `id` int(11) NOT NULL,
  `poll_id` int(11) NOT NULL,
  `option_text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_poll_votes`
--

CREATE TABLE `group_poll_votes` (
  `id` int(11) NOT NULL,
  `poll_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `code` varchar(5) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `code`, `name`) VALUES
(1, 'hu', 'Magyar'),
(2, 'en', 'English'),
(3, 'de', 'Deutsch');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `fromid` int(255) NOT NULL,
  `toid` int(255) NOT NULL,
  `content` text NOT NULL,
  `sent_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `fromid`, `toid`, `content`, `sent_at`) VALUES
(1, 1, 8, 'Szia', '2026-01-25 19:04:32'),
(2, 1, 8, 'Helló', '2026-03-29 21:45:28'),
(3, 8, 1, 'Helló', '2026-03-29 21:45:33'),
(4, 1, 8, 'Mizu?', '2026-03-29 21:45:37'),
(5, 8, 1, 'Na mostmár működik a rendszer xd', '2026-03-29 21:45:48');

-- --------------------------------------------------------

--
-- Table structure for table `namedays`
--

CREATE TABLE `namedays` (
  `id` int(11) NOT NULL,
  `datum` varchar(5) DEFAULT NULL,
  `nevek` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `namedays`
--

INSERT INTO `namedays` (`id`, `datum`, `nevek`) VALUES
(1, '01-01', 'Alpár, Fruzsina, Bazil'),
(2, '01-02', 'Ábel, Gergely, Vazul'),
(3, '01-03', 'Genovéva, Gyöngyvér, Benjámin, Dzsenifer, ((Jennifer))'),
(4, '01-04', 'Titusz, Leona, Angéla'),
(5, '01-05', 'Simon, Emília'),
(6, '01-06', 'Gáspár, Menyhért, Boldizsár'),
(7, '01-07', 'Attila, Etele, Ramóna, Rajmund, Bálint'),
(8, '01-08', 'Gyöngyvér, Keve, Szeverin, Szörény'),
(9, '01-09', 'Marcell, Juliánusz'),
(10, '01-10', 'Melánia, Vilmos, Vilma'),
(11, '01-11', 'Ágota, Honoráta'),
(12, '01-12', 'Ernő, Erneszta, Tatjána'),
(13, '01-13', 'Veronika, Csongor, Yvett'),
(14, '01-14', 'Bódog, Félix'),
(15, '01-15', 'Lóránt, Loránd, Pál'),
(16, '01-16', 'Gusztáv, Marcell'),
(17, '01-17', 'Antal, Antónia'),
(18, '01-18', 'Margit, Piroska'),
(19, '01-19', 'Sára, Márta, Márió'),
(20, '01-20', 'Fábián, Sebestyén'),
(21, '01-21', 'Ágnes, Agnéta'),
(22, '01-22', 'Vince, Artúr'),
(23, '01-23', 'Zelma, Rajmund, Emerencia, Emese, Freja, Frej'),
(24, '01-24', 'Timót, Ferenc'),
(25, '01-25', 'Pál, Henrik'),
(26, '01-26', 'Vanda, Paula, Timóteusz'),
(27, '01-27', 'Angéla, Angelika'),
(28, '01-28', 'Károly, Karola, Tamás'),
(29, '01-29', 'Adél, Valér'),
(30, '01-30', 'Martina, Gerda, Jácinta'),
(31, '01-31', 'Marcella, János'),
(32, '02-01', 'Ignác, Brigitta, Kincső, Renátó'),
(33, '02-02', 'Karolina, Karola, Aida'),
(34, '02-03', 'Balázs, Oszkár, Celerina'),
(35, '02-04', 'Ráhel, Csenge, Veronika, András'),
(36, '02-05', 'Ágota, Ingrid, Etelka, Léda'),
(37, '02-06', 'Dorottya, Dóra, Pál'),
(38, '02-07', 'Tódor, Rómeó, Richárd'),
(39, '02-08', 'Aranka, Jeromos'),
(40, '02-09', 'Abigél, Alex, Apollónia'),
(41, '02-10', 'Elvira'),
(42, '02-11', 'Bertold, Marietta'),
(43, '02-12', 'Lívia, Lídia, Eulália'),
(44, '02-13', 'Ella, Linda, Levente, Katalin'),
(45, '02-14', 'Bálint, Valentin, Cirill, Metód'),
(46, '02-15', 'Kolos, Györgyi, Georgina'),
(47, '02-16', 'Julianna, Lilla, Filippa'),
(48, '02-17', 'Donát'),
(49, '02-18', 'Bernadett, Simon, Zenkő'),
(50, '02-19', 'Zsuzsanna, Eliza, Konrád'),
(51, '02-20', 'Aladár, Álmos, Leó'),
(52, '02-21', 'Eleonóra, Zelmira, Péter'),
(53, '02-22', 'Gerzson, Margit, Zétény'),
(54, '02-23', 'Alfréd, Polikárp, Mirtill'),
(55, '02-24', 'Mátyás, Jázmin'),
(56, '02-25', 'Géza, Cézár, Vanda'),
(57, '02-26', 'Viktor, Győző, Edina'),
(58, '02-27', 'Ákos, Bátor, Gábor'),
(59, '02-28', 'Elemér, Oszvald, Román'),
(60, '03-01', 'Albin, Albina, Leonita'),
(61, '03-02', 'Lujza, Ágnes, Henrik, Magor'),
(62, '03-03', 'Kornélia, Kunigunda, Frigyes'),
(63, '03-04', 'Kázmér, Lúciusz, Zorán'),
(64, '03-05', 'Adorján, Adrián'),
(65, '03-06', 'Leonóra, Inez, Koletta, Felicitász'),
(66, '03-07', 'Tamás, Perpétua, Ubul'),
(67, '03-08', 'János, Zoltán, Apolka'),
(68, '03-09', 'Franciska, Fanni'),
(69, '03-10', 'Ildikó, Emil, Gusztáv'),
(70, '03-11', 'Szilárd, Tímea, Konstantin'),
(71, '03-12', 'Gergely, Maximilián'),
(72, '03-13', 'Krisztián, Ajtony, Egyed, Patrícia'),
(73, '03-14', 'Matild, Matilda, Trilla'),
(74, '03-15', 'Kristóf, Kelemen'),
(75, '03-16', 'Henrietta, Herbert'),
(76, '03-17', 'Gertrúd, Patrik'),
(77, '03-18', 'Sándor, Ede, Cirill'),
(78, '03-19', 'József, Bánk'),
(79, '03-20', 'Klaudia, Alexandra'),
(80, '03-21', 'Benedek, Bence, Miklós'),
(81, '03-22', 'Beáta, Izolda, Lea'),
(82, '03-23', 'Emőke, Botond, Ottó, Kartal'),
(83, '03-24', 'Gábor, Karina'),
(84, '03-25', 'Irén, Írisz, Lúcia'),
(85, '03-26', 'Emánuel, Emánuéla, Lara, Larissza, Árpád'),
(86, '03-27', 'Hajnalka, Lídia, Auguszta'),
(87, '03-28', 'Gedeon, Johanna'),
(88, '03-29', 'Auguszta, Bercel, Bertold'),
(89, '03-30', 'Zalán'),
(90, '03-31', 'Árpád, Benjámin, Benő'),
(91, '04-01', 'Hugó, Agád'),
(92, '04-02', 'Áron, Ferenc'),
(93, '04-03', 'Buda, Richárd, Hóvirág, Indira'),
(94, '04-04', 'Izidor'),
(95, '04-05', 'Vince, Irén, Teodóra'),
(96, '04-06', 'Vilmos, Bíborka, Taksony, Celesztin'),
(97, '04-07', 'Herman, János'),
(98, '04-08', 'Dénes, Valér, Valter'),
(99, '04-09', 'Erhard, Ákos, Döme'),
(100, '04-10', 'Zsolt, Ezékiel'),
(101, '04-11', 'Leó, Szaniszló, Glória'),
(102, '04-12', 'Gyula, Baldvin, Sába, Nara'),
(103, '04-13', 'Ida, Márton, Hermina'),
(104, '04-14', 'Tibor'),
(105, '04-15', 'Anasztázia, Tas, Oktávia'),
(106, '04-16', 'Csongor, Bernadett'),
(107, '04-17', 'Rudolf, Izidóra'),
(108, '04-18', 'Andrea, Ilma, Apolló, Aladár'),
(109, '04-19', 'Emma, Malvin, Zseraldina'),
(110, '04-20', 'Tivadar, Tihamér, Töhötöm'),
(111, '04-21', 'Konrád, Zelmira, Anzelm'),
(112, '04-22', 'Csilla, Noémi, Kájusz, Noé'),
(113, '04-23', 'Béla, Adalbert'),
(114, '04-24', 'György, Fidél, Debóra'),
(115, '04-25', 'Márk, Ányos, Mohamed'),
(116, '04-26', 'Ervin, Klétusz'),
(117, '04-27', 'Zita, Mariann, Anasztáz'),
(118, '04-28', 'Valéria, Péter'),
(119, '04-29', 'Péter, Katalin, Roberta'),
(120, '04-30', 'Katalin, Kitti, Zsófia, Piusz'),
(121, '05-01', 'Fülöp, Jakab, Zsaklin, Jefte, József, Valburga, Fédra'),
(122, '05-02', 'Zsigmond, Idir, Zoé'),
(123, '05-03', 'Tímea, Irma, Jakab, Fülöp'),
(124, '05-04', 'Mónika, Flórián'),
(125, '05-05', 'Györgyi, Irén'),
(126, '05-06', 'Ivett, Frida, Judit, Yvett'),
(127, '05-07', 'Gizella, Gusztáv, Bendegúz, Gália'),
(128, '05-08', 'Mihály, Győző'),
(129, '05-09', 'Gergely, Katinka, Alberta, Édua, Mira'),
(130, '05-10', 'Ármin, Pálma, Izidor'),
(131, '05-11', 'Ferenc, Sára'),
(132, '05-12', 'Pongrác'),
(133, '05-13', 'Szervác, Imola, Imelda'),
(134, '05-14', 'Bonifác, Gyöngyi'),
(135, '05-15', 'Bodza, Zsófia, Szonja, Döníz'),
(136, '05-16', 'Mózes, Botond, János'),
(137, '05-17', 'Paszkál, Ditmár, Rezeda'),
(138, '05-18', 'Erik, Alexandra, János, Hanga'),
(139, '05-19', 'Ivó, Iván, Milán'),
(140, '05-20', 'Bernát, Bernardin, Felícia'),
(141, '05-21', 'Konstantin, András'),
(142, '05-22', 'Júlia, Rita, Emil'),
(143, '05-23', 'Dezső, Vilmos, Renáta'),
(144, '05-24', 'Eszter, Eliza, Vanessza'),
(145, '05-25', 'Orbán, Gergely'),
(146, '05-26', 'Fülöp, Evelin'),
(147, '05-27', 'Hella, Pelbárt, Ágoston'),
(148, '05-28', 'Emil, Csanád, Vilmos'),
(149, '05-29', 'Magdolna, Magda, Ervin, Léna'),
(150, '05-30', 'Janka, Zsanett, Johanna, Nándor'),
(151, '05-31', 'Angéla, Petronella'),
(152, '06-01', 'Tünde, Jusztinusz'),
(153, '06-02', 'Kármen, Anita, Péter, Marcellinusz'),
(154, '06-03', 'Klotild, Cecília, Károly, Kevin'),
(155, '06-04', 'Bulcsú, Kerény, Kerubin'),
(156, '06-05', 'Frézia, Zenke, Fatime, Fatima, Bonifác'),
(157, '06-06', 'Norbert, Norberta, Cintia'),
(158, '06-07', 'Róbert, Robertina, Arianna, Fülöp, Roberta'),
(159, '06-08', 'Medárd, Helga'),
(160, '06-09', 'Félix, Előd, Annamária, Annabella'),
(161, '06-10', 'Margit, Gréta'),
(162, '06-11', 'Barnabás, Barangó'),
(163, '06-12', 'Villő, Orfeusz, Adelaida, Duru'),
(164, '06-13', 'Antal, Anett'),
(165, '06-14', 'Vazul, Elizeus, Herta'),
(166, '06-15', 'Jolán, Vid, Viola, Ariana'),
(167, '06-16', 'Jusztin, Jusztina, Auréliusz'),
(168, '06-17', 'Laura, Alida, Alina, Szabolcs, Adolf, Bató'),
(169, '06-18', 'Arnold, Levente, Doloróza'),
(170, '06-19', 'Gyárfás, Romuald, Azurea, Zorka'),
(171, '06-20', 'Rafael, Dina'),
(172, '06-21', 'Alajos, Leila'),
(173, '06-22', 'Paulina, Tamás'),
(174, '06-23', 'Zoltán, Szultána'),
(175, '06-24', 'János, Iván'),
(176, '06-25', 'Vilmos, Viola, Vilma'),
(177, '06-26', 'János, Pál, Cirill'),
(178, '06-27', 'László, Sámson'),
(179, '06-28', 'Levente, Irén, Iréneusz'),
(180, '06-29', 'Péter, Pál, Adeliz, Adeliza, Emőke, Judit, Petra, Szulamit, Ivett'),
(181, '06-30', 'Pál'),
(182, '07-01', 'Tihamér, Annamária, Olivér, Áron'),
(183, '07-02', 'Ottó'),
(184, '07-03', 'Kornél, Soma, Tamás'),
(185, '07-04', 'Ulrik, Erzsébet, Fédra, Babett'),
(186, '07-05', 'Emese, Sarolta, Lotti, Antal, Nara'),
(187, '07-06', 'Csaba, Mária'),
(188, '07-07', 'Apollónia, Vilibald, Bene'),
(189, '07-08', 'Ellák, Edgár, Eperke, Zsóka'),
(190, '07-09', 'Lukrécia, Veronika, Hajnalka'),
(191, '07-10', 'Amália, Melina, Engelbert, Ulrika'),
(192, '07-11', 'Nóra, Lili, Nelli, Benedek'),
(193, '07-12', 'Izabella, Dalma, Eleonóra'),
(194, '07-13', 'Jenő, Henrik'),
(195, '07-14', 'Örs, Stella, Kamil'),
(196, '07-15', 'Örkény, Henrik, Roland, Bonaventúra, Csegő'),
(197, '07-16', 'Valter, Irma'),
(198, '07-17', 'Endre, Elek, András'),
(199, '07-18', 'Szömér, Frigyes, Milla, Hedvig, Mirkó'),
(200, '07-19', 'Emília'),
(201, '07-20', 'Illés, Margaréta'),
(202, '07-21', 'Dániel, Daniella, Lőrinc'),
(203, '07-22', 'Magdolna, Mária, Magda, Nara'),
(204, '07-23', 'Lenke, Brigitta, Apollinár'),
(205, '07-24', 'Kinga, Kunigunda, Kincső, Krisztina'),
(206, '07-25', 'Kristóf, Jakab'),
(207, '07-26', 'Panna, Anna, Anikó, Joakim'),
(208, '07-27', 'Olga, Liliána, Natália, Pantaleon'),
(209, '07-28', 'Szabolcs, Alina, Ince, Győző'),
(210, '07-29', 'Márta, Flóra'),
(211, '07-30', 'Judit, Xénia, Péter'),
(212, '07-31', 'Oszkár, Ignác, Bató'),
(213, '08-01', 'Boglárka, Nimród, Alfonz'),
(214, '08-02', 'Lehel'),
(215, '08-03', 'Hermina, Lídia, Kamélia, Kíra, Mirtill'),
(216, '08-04', 'Domonkos, Dominik, János, Dominika'),
(217, '08-05', 'Krisztina'),
(218, '08-06', 'Berta, Bettina'),
(219, '08-07', 'Ibolya'),
(220, '08-08', 'László, Domonkos'),
(221, '08-09', 'Emőd, Román'),
(222, '08-10', 'Lőrinc, Blanka, Csilla'),
(223, '08-11', 'Zsuzsanna, Tiborc, Klára'),
(224, '08-12', 'Klára, Hilária, Diána'),
(225, '08-13', 'Ipoly, Ince, Vitália'),
(226, '08-14', 'Marcell, Maximilián'),
(227, '08-15', 'Mária'),
(228, '08-16', 'Ábrahám, Rókus'),
(229, '08-17', 'Jácint, Réka, Hetény'),
(230, '08-18', 'Ilona, Rajnald'),
(231, '08-19', 'Huba, Marián, Emília'),
(232, '08-20', 'István, Bernát'),
(233, '08-21', 'Sámuel, Hajna, Piusz'),
(234, '08-22', 'Menyhért, Mirjam, Merse'),
(235, '08-23', 'Bence, Róza, Szidónia'),
(236, '08-24', 'Bertalan, Aliz, Detre'),
(237, '08-25', 'Lajos, Patrícia'),
(238, '08-26', 'Izsó, Tália, Natália, Zamfira'),
(239, '08-27', 'Gáspár, Mónika'),
(240, '08-28', 'Ágoston, Mózes'),
(241, '08-29', 'Beatrix, Erna'),
(242, '08-30', 'Rózsa, Félix, Letícia'),
(243, '08-31', 'Erika, Bella, Arisztid, Hanga, Amina'),
(244, '09-01', 'Egyed, Egon, Noémi, Tamara'),
(245, '09-02', 'Rebeka, Dorina, Renáta, Ingrid, István, Axel, Fédra'),
(246, '09-03', 'Hilda, Gergely'),
(247, '09-04', 'Rozália, Róza, Ida'),
(248, '09-05', 'Viktor, Lőrinc, Ofélia'),
(249, '09-06', 'Zakariás, Beáta, Brájen'),
(250, '09-07', 'Regina'),
(251, '09-08', 'Mária, Adrienn'),
(252, '09-09', 'Ádám, Péter'),
(253, '09-10', 'Nikolett, Hunor, Miklós'),
(254, '09-11', 'Teodóra, Jácint, Igor, Helga'),
(255, '09-12', 'Mária, Irma'),
(256, '09-13', 'Kornél, János'),
(257, '09-14', 'Szeréna, Roxána'),
(258, '09-15', 'Enikő, Melitta'),
(259, '09-16', 'Edit, Ciprián'),
(260, '09-17', 'Zsófia, Róbert'),
(261, '09-18', 'Diána, József'),
(262, '09-19', 'Vilhelmina, Januáriusz, Dorián'),
(263, '09-20', 'Friderika'),
(264, '09-21', 'Máté, Mirella, Jónás'),
(265, '09-22', 'Móric, Tamás'),
(266, '09-23', 'Tekla, Líviusz, Ila, Nara'),
(267, '09-24', 'Gellért, Gerda, Mercédesz, Dodo'),
(268, '09-25', 'Eufrozina, Kende'),
(269, '09-26', 'Jusztina, Kozma, Damján'),
(270, '09-27', 'Adalbert, Vince'),
(271, '09-28', 'Vencel, Salamon'),
(272, '09-29', 'Mihály, Gábor, Rafael, Mirabella'),
(273, '09-30', 'Jeromos, Honória, Hunor'),
(274, '10-01', 'Malvin, Teréz'),
(275, '10-02', 'Petra, Örs'),
(276, '10-03', 'Helga, Évald'),
(277, '10-04', 'Ferenc, Hajnalka, Zorka'),
(278, '10-05', 'Aurél, Placid, Attila'),
(279, '10-06', 'Brúnó, Renáta, Renátó'),
(280, '10-07', 'Amália, Bekény'),
(281, '10-08', 'Koppány, Benedikta'),
(282, '10-09', 'Dénes, János'),
(283, '10-10', 'Gedeon, Ferenc, Bendegúz'),
(284, '10-11', 'Brigitta, Placida, Etel, Gitta'),
(285, '10-12', 'Miksa, Rezső, Edvin'),
(286, '10-13', 'Kálmán, Ede, Edvárd'),
(287, '10-14', 'Helén, Kaldixtusz'),
(288, '10-15', 'Teréz, Aranka'),
(289, '10-16', 'Gál, Margit, Hedvig'),
(290, '10-17', 'Hedvig, Ignác, Rudolf'),
(291, '10-18', 'Lukács, Jusztusz'),
(292, '10-19', 'Nándor, János, Pál'),
(293, '10-20', 'Vendel, Irén, Kleopátra'),
(294, '10-21', 'Orsolya, Zsolt'),
(295, '10-22', 'Előd, Szalóme, Kordélia'),
(296, '10-23', 'Gyöngyvér, János, Gyöngyi'),
(297, '10-24', 'Salamon, Antal'),
(298, '10-25', 'Blanka, Bianka, Dália, Beniel, Mór'),
(299, '10-26', 'Dömötör, Armand, Örs'),
(300, '10-27', 'Szabina, Antonietta'),
(301, '10-28', 'Simon, Szimonetta, Szimóna, Júdás, Tádé'),
(302, '10-29', 'Nárcisz, Melinda, Őzike'),
(303, '10-30', 'Alfonz, Zenóbia'),
(304, '10-31', 'Farkas, Rodrigó'),
(305, '11-01', 'Marianna'),
(306, '11-02', 'Achilles, Bató'),
(307, '11-03', 'Győző, Márton'),
(308, '11-04', 'Károly, Karola'),
(309, '11-05', 'Imre, Zakariás, Tétény'),
(310, '11-06', 'Lénárd'),
(311, '11-07', 'Csenger, Rezső, Ernő, Florentin'),
(312, '11-08', 'Zsombor, Kolos, Gottfrid'),
(313, '11-09', 'Tivadar'),
(314, '11-10', 'Réka, András, Leó'),
(315, '11-11', 'Márton, Atád, Tódor'),
(316, '11-12', 'Jónás, Renátó, Jozafát'),
(317, '11-13', 'Szilvia, Szaniszló'),
(318, '11-14', 'Aliz, Vanda, Huba, Klementina'),
(319, '11-15', 'Albert, Lipót'),
(320, '11-16', 'Ödön, Margit'),
(321, '11-17', 'Hortenzia, Gergő, Dénes'),
(322, '11-18', 'Jenő, Noé'),
(323, '11-19', 'Erzsébet'),
(324, '11-20', 'Jolán, Zsolt, Ödön, Bódog'),
(325, '11-21', 'Olivér'),
(326, '11-22', 'Cecília, Filemon'),
(327, '11-23', 'Kelemen, Klementina, Kolumbán'),
(328, '11-24', 'Emma, Flóra, Virág, Emmaróza'),
(329, '11-25', 'Katalin, Liza, Katinka'),
(330, '11-26', 'Virág, Szvetlana, Konrád, Viktória, Milos'),
(331, '11-27', 'Virgil, Virgínia'),
(332, '11-28', 'Stefánia, Jakab'),
(333, '11-29', 'Taksony, Ilma, Filoména'),
(334, '11-30', 'András, Andor, Andrea'),
(335, '12-01', 'Elza, Natália, Blanka, Bonita'),
(336, '12-02', 'Melinda, Vivien, Aranka'),
(337, '12-03', 'Ferenc, Olívia'),
(338, '12-04', 'Borbála, Barbara, János'),
(339, '12-05', 'Vilma, Ünige, Csaba'),
(340, '12-06', 'Miklós, Csinszka, Gyopár, Gyopárka'),
(341, '12-07', 'Ambrus, Ambrózia'),
(342, '12-08', 'Mária, Emőke'),
(343, '12-09', 'Natália, Valéria, Filótea'),
(344, '12-10', 'Judit, Loretta, Eulália'),
(345, '12-11', 'Árpád, Árpádina, Damazusz'),
(346, '12-12', 'Gabriella, Johanna, Franciska'),
(347, '12-13', 'Luca, Otília, Lúcia, Éda, Tilia'),
(348, '12-14', 'Szilárda, Szilárd, János'),
(349, '12-15', 'Valér, Detre'),
(350, '12-16', 'Etelka, Aletta, Adelaida'),
(351, '12-17', 'Lázár, Olimpia'),
(352, '12-18', 'Auguszta, Gracián'),
(353, '12-19', 'Viola, Anasztáz'),
(354, '12-20', 'Teofil, Liberátusz'),
(355, '12-21', 'Tamás, Péter'),
(356, '12-22', 'Zénó, Flórián'),
(357, '12-23', 'Viktória, János'),
(358, '12-24', 'Ádám, Éva, Adél, Noé'),
(359, '12-25', 'Eugénia, Anasztázia, Noel'),
(360, '12-26', 'István'),
(361, '12-27', 'János, Teodor'),
(362, '12-28', 'Kamilla, Apor'),
(363, '12-29', 'Tamás, Tamara'),
(364, '12-30', 'Dávid, Hunor, Libériusz'),
(365, '12-31', 'Szilveszter, Donáta');

-- --------------------------------------------------------

--
-- Table structure for table `notifys`
--

CREATE TABLE `notifys` (
  `id` int(11) NOT NULL,
  `fromid` int(255) NOT NULL,
  `toid` int(255) NOT NULL,
  `notifytype` varchar(100) NOT NULL,
  `readed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `notifys`
--

INSERT INTO `notifys` (`id`, `fromid`, `toid`, `notifytype`, `readed`) VALUES
(1, 8, 1, 'friend', 1),
(2, 36, 39, 'comment', 0),
(3, 15, 18, 'group_invite', 0),
(4, 24, 35, 'group_invite', 0),
(5, 18, 12, 'friend_request', 0),
(6, 25, 39, 'like', 0),
(7, 31, 26, 'friend_request', 0),
(8, 25, 29, 'friend_request', 0),
(9, 25, 26, 'comment', 0),
(10, 39, 25, 'friend_request', 0),
(11, 31, 27, 'group_invite', 0);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_attempts`
--

CREATE TABLE `password_reset_attempts` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `attempts` int(11) NOT NULL DEFAULT 1,
  `locked_until` datetime DEFAULT NULL,
  `last_attempt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `premium_users`
--

CREATE TABLE `premium_users` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `premium_until` datetime NOT NULL,
  `premium_ig` datetime NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `premium_users`
--

INSERT INTO `premium_users` (`id`, `user_id`, `premium_until`, `premium_ig`, `created_at`, `updated_at`) VALUES
(5, 1, '0000-00-00 00:00:00', '2026-04-29 09:53:09', '2026-02-10 11:13:35', '2026-03-30 09:53:09');

-- --------------------------------------------------------

--
-- Table structure for table `profanity_filter`
--

CREATE TABLE `profanity_filter` (
  `id` int(11) NOT NULL,
  `words` varchar(100) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `profanity_filter`
--

INSERT INTO `profanity_filter` (`id`, `words`, `type`) VALUES
(1, 'kaka', 'f'),
(2, 'Aberált', 'm'),
(3, 'Aberrált', 'm'),
(4, 'Abortuszmaradék', 'f'),
(5, 'Abszolút hülye', 'm'),
(6, 'Agyalágyult', 'm'),
(7, 'Agyatlan', 'm'),
(8, 'Agybatetovált', 'm'),
(9, 'Ágybavizelős', 'm'),
(10, 'Agyfasz', 'f'),
(11, 'Agyhalott', 'f'),
(12, 'Agyonkúrt', 'm'),
(13, 'Agyonvert', 'm'),
(14, 'Agyrákos', 'm'),
(15, 'AIDS-es', 'm'),
(16, 'Alapvetően fasz', 'm'),
(17, 'Animalsex-mániás', 'f'),
(18, 'Antibarom', 'f'),
(19, 'Aprófaszú', 'm'),
(20, 'Arcbarakott', 'm'),
(21, 'Aszaltfaszú', 'm'),
(22, 'Aszott', 'm'),
(23, 'Átbaszott', 'm'),
(24, 'Azt a kurva de fasz', 'f'),
(25, 'Balatonberényben napvilágot látott', 'm'),
(26, 'Balfasz', 'f'),
(27, 'Balfészek', 'f'),
(28, 'Baromfifasz', 'f'),
(29, 'Basz-o-matic', 'f'),
(30, 'Baszhatatlan', 'm'),
(31, 'Basznivaló', 'm'),
(32, 'Bebaszott', 'm'),
(33, 'Befosi', 'm'),
(34, 'Békapicsa', 'm'),
(35, 'Bélböfi', 'm'),
(36, 'Beleiből kiforgatott', 'm'),
(37, 'Bélszél', 'f'),
(38, 'Bronz térdű', 'm'),
(39, 'Brunya', 'f'),
(40, 'Büdös szájú', 'm'),
(41, 'Büdösszájú', 'm'),
(42, 'Búvalbaszott', 'm'),
(43, 'Buzeráns', 'f'),
(44, 'Buzernyák', 'm'),
(45, 'Buzi', 'f'),
(46, 'Buzikurva', 'f'),
(47, 'Cafat', 'f'),
(48, 'Cafka', 'f'),
(49, 'Céda', 'f'),
(50, 'Cérnafaszú', 'm'),
(51, 'Cottonfej', 'f'),
(52, 'Csempe szobában felneveltetett', 'm'),
(53, 'Cseszett', 'm'),
(54, 'Csibefasz', 'f'),
(55, 'Csipszar', 'f'),
(56, 'Csirkefaszú', 'm'),
(57, 'Csitri', 'f'),
(58, 'Csöcs', 'f'),
(59, 'Csöcsfej', 'f'),
(60, 'Csöppszar', 'f'),
(61, 'Csőszkunyhóban elrejtett', 'm'),
(62, 'Csupaszfarkú', 'm'),
(63, 'Cuncipunci', 'f'),
(64, 'Deformáltfaszú', 'm'),
(65, 'Dekorált pofájú', 'm'),
(66, 'Döbbenetesen segg', 'm'),
(67, 'Dobseggű', 'm'),
(68, 'Dughatatlan', 'm'),
(69, 'Dunyhavalagú', 'm'),
(70, 'Duplafaszú', 'm'),
(71, 'Ebfasz', 'f'),
(72, 'Egyszerűen fasz', 'm'),
(73, 'Elbaszott', 'm'),
(74, 'Eleve hülye', 'm'),
(75, 'Extrahülye', 'm'),
(76, 'Fafogú rézfűrésszel megsebzett', 'm'),
(77, 'Fantasztikusan segg', 'm'),
(78, 'Fasszopó', 'f'),
(79, 'Fasz', 'm'),
(80, 'Fasz-emulátor', 'm'),
(81, 'Faszagyú', 'm'),
(82, 'Faszarc', 'f'),
(83, 'Faszfej', 'f'),
(84, 'Faszfészek', 'f'),
(85, 'Faszkalap', 'f'),
(86, 'Faszkarika', 'f'),
(87, 'Faszkedvelő', 'm'),
(88, 'Faszkópé', 'f'),
(89, 'Faszogány', 'f'),
(90, 'Faszpörgettyű', 'f'),
(91, 'Faszsapka', 'f'),
(92, 'Faszszagú', 'm'),
(93, 'Faszszopó', 'f'),
(94, 'Fasztalan', 'm'),
(95, 'Fasztarisznya', 'f'),
(96, 'Fasztengely', 'f'),
(97, 'Fasztolvaj', 'f'),
(98, 'Faszváladék', 'f'),
(99, 'Faszverő', 'f'),
(100, 'Félrebaszott', 'm'),
(101, 'Félrefingott', 'm'),
(102, 'Félreszart', 'm'),
(103, 'Félribanc', 'f'),
(104, 'Fing', 'f'),
(105, 'Fölcsinált', 'm'),
(106, 'Fölfingott', 'm'),
(107, 'Fos', 'f'),
(108, 'Foskemence', 'f'),
(109, 'Fospisztoly', 'f'),
(110, 'Fospumpa', 'f'),
(111, 'Fostalicska', 'f'),
(112, 'Fütyi', 'f'),
(113, 'Fütyinyalogató', 'm'),
(114, 'Fütykös', 'f'),
(115, 'Geci', 'f'),
(116, 'Gecinyelő', 'm'),
(117, 'Geciszaró', 'm'),
(118, 'Geciszívó', 'm'),
(119, 'Genny', 'f'),
(120, 'Gennyesszájú', 'm'),
(121, 'Gennygóc', 'f'),
(122, 'Genyac', 'f'),
(123, 'Genyó', 'f'),
(124, 'Gólyafos', 'f'),
(125, 'Görbefaszú', 'm'),
(126, 'Gyennyszopó', 'm'),
(127, 'Gyíkfing', 'f'),
(128, 'Hájpacni', 'f'),
(129, 'Hatalmas nagy fasz', 'f'),
(130, 'Hátbabaszott', 'm'),
(131, 'Házikurva', 'f'),
(132, 'Hererákos', 'm'),
(133, 'Hígagyú', 'm'),
(134, 'Hihetetlenül fasz', 'm'),
(135, 'Hikomat', 'f'),
(136, 'Hímnőstény', 'f'),
(137, 'Hímringyó', 'f'),
(138, 'Hiperstrici', 'm'),
(139, 'Hitler-imádó', 'm'),
(140, 'Hitlerista', 'm'),
(141, 'Hivatásos balfasz', 'f'),
(142, 'Hú de segg', 'm'),
(143, 'Hugyagyú', 'm'),
(144, 'Hugyos', 'm'),
(145, 'Hugytócsa', 'f'),
(146, 'Hüje', 'm'),
(147, 'Hüle', 'm'),
(148, 'Hülye', 'm'),
(149, 'Hülyécske', 'm'),
(150, 'Hülyegyerek', 'f'),
(151, 'Inkubátor-szökevény', 'f'),
(152, 'Integrált barom', 'f'),
(153, 'Ionizált faszú', 'm'),
(154, 'IQ bajnok', 'f'),
(155, 'IQ fighter', 'f'),
(156, 'IQ hiányos', 'm'),
(157, 'Irdatlanul köcsög', 'm'),
(158, 'Íveltfaszú', 'm'),
(159, 'Jajj de barom', 'm'),
(160, 'Jókora fasz', 'm'),
(161, 'Kaka', 'f'),
(162, 'Kakamatyi', 'f'),
(163, 'Kaki', 'f'),
(164, 'Kaksi', 'f'),
(165, 'Kecskebaszó', 'm'),
(166, 'Kellően fasz', 'm'),
(167, 'Képlékeny faszú', 'm'),
(168, 'Keresve sem található fasz', 'f'),
(169, 'Kétfaszú', 'm'),
(170, 'Kétszer agyonbaszott', 'm'),
(171, 'Ki-bebaszott', 'm'),
(172, 'Kibaszott', 'm'),
(173, 'Kifingott', 'm'),
(174, 'Kiherélt', 'm'),
(175, 'Kikakkantott', 'm'),
(176, 'Kikészült', 'm'),
(177, 'Kimagaslóan fasz', 'm'),
(178, 'Kimondhatatlan pöcs', 'm'),
(179, 'Kis szaros', 'f'),
(180, 'Kisfütyi', 'f'),
(181, 'Klotyószagú', 'm'),
(182, 'Ködmönbe bújtatott', 'm'),
(183, 'Kojak-faszú', 'm'),
(184, 'Kopárfaszú', 'm'),
(185, 'Korlátolt gecizésű', 'm'),
(186, 'Kotonszökevény', 'f'),
(187, 'Középszar', 'm'),
(188, 'Kretén', 'f'),
(189, 'Kuki', 'f'),
(190, 'Kula', 'f'),
(191, 'Kunkorított faszú', 'm'),
(192, 'Kurva', 'f'),
(193, 'Kurvaanyjú', 'm'),
(194, 'Kurvapecér', 'f'),
(195, 'Kutyakaki', 'f'),
(196, 'Kutyapina', 'f'),
(197, 'Kutyaszar', 'f'),
(198, 'Lankadtfaszú', 'm'),
(199, 'Lebaszirgált', 'm'),
(200, 'Lebaszott', 'm'),
(201, 'Lecseszett', 'm'),
(202, 'Leírhatatlanul segg', 'm'),
(203, 'Lemenstruált', 'm'),
(204, 'Leokádott', 'm'),
(205, 'Lepkefing', 'f'),
(206, 'Leprafészek', 'f'),
(207, 'Leszart', 'm'),
(208, 'Leszbikus', 'm'),
(209, 'Lőcs', 'f'),
(210, 'Lőcsgéza', 'f'),
(211, 'Lófasz', 'f'),
(212, 'Lógócsöcsű', 'm'),
(213, 'Lóhugy', 'f'),
(214, 'Lotyó', 'f'),
(215, 'Lucskos', 'm'),
(216, 'Lugnya', 'f'),
(217, 'Lyukasbelű', 'm'),
(218, 'Lyukasfaszú', 'm'),
(219, 'Lyukát vakaró', 'm'),
(220, 'Lyuktalanított', 'm'),
(221, 'Mamutsegg', 'f'),
(222, 'Maszturbációs görcs', 'f'),
(223, 'Maszturbagép', 'f'),
(224, 'Maszturbáltatott', 'm'),
(225, 'Megfingatott', 'm'),
(226, 'Megkettyintett', 'm'),
(227, 'Megkúrt', 'm'),
(228, 'Megszopatott', 'm'),
(229, 'Mesterséges faszú', 'm'),
(230, 'Méteres kékeres', 'f'),
(231, 'Mikrotökű', 'm'),
(232, 'Mocskos', 'm'),
(233, 'Mojfing', 'f'),
(234, 'Műfaszú', 'm'),
(235, 'Muff', 'f'),
(236, 'Multifasz', 'f'),
(237, 'Műtöttpofájú', 'm'),
(238, 'Náci', 'm'),
(239, 'Nagyfejű', 'm'),
(240, 'Nikotinpatkány', 'f'),
(241, 'Nimfomániás', 'm'),
(242, 'Nuna', 'f'),
(243, 'Nunci', 'f'),
(244, 'Nuncóka', 'f'),
(245, 'Nyalábfasz', 'f'),
(246, 'Nyelestojás', 'f'),
(247, 'Nyúlszar', 'f'),
(248, 'Oltári nagy fasz', 'f'),
(249, 'Ondónyelő', 'm'),
(250, 'Orbitálisan hülye', 'm'),
(251, 'Ordenálé', 'm'),
(252, 'Összebaszott', 'm'),
(253, 'Ötcsillagos fasz', 'f'),
(254, 'Óvszerezett', 'm'),
(255, 'Pénisz', 'f'),
(256, 'Peremesfaszú', 'm'),
(257, 'Picsa', 'f'),
(258, 'Picsafej', 'f'),
(259, 'Picsameresztő', 'm'),
(260, 'Picsánnyalt', 'm'),
(261, 'Picsánrugott', 'm'),
(262, 'Picsányi', 'm'),
(263, 'Pikkelypáncélt hordó', 'm'),
(264, 'Pina', 'f'),
(265, 'Pisa', 'f'),
(266, 'Pisaszagú', 'm'),
(267, 'Pisis', 'm'),
(268, 'Pöcs', 'f'),
(269, 'Pöcsfej', 'f'),
(270, 'Porbafingó', 'm'),
(271, 'Pornóbuzi', 'f'),
(272, 'Pornómániás', 'm'),
(273, 'Pudvás', 'm'),
(274, 'Pudváslikú', 'm'),
(275, 'Puhafaszú', 'm'),
(276, 'Punci', 'f'),
(277, 'Puncimókus', 'f'),
(278, 'Puncis', 'm'),
(279, 'Punciutáló', 'f'),
(280, 'Puncivirág', 'f'),
(281, 'Qki', 'f'),
(282, 'Qrva', 'f'),
(283, 'Qtyaszar', 'f'),
(284, 'Rabló', 'm'),
(285, 'Rágcsáltfaszú', 'm'),
(286, 'Redva', 'f'),
(287, 'Rendkívül fasz', 'm'),
(288, 'Repedtsarkú', 'm'),
(289, 'Rétó-román', 'm'),
(290, 'Rézhasú', 'm'),
(291, 'Ribanc', 'f'),
(292, 'Riherongy', 'f'),
(293, 'Ritka fogú', 'm'),
(294, 'Rivalizáló', 'm'),
(295, 'Rőfös fasz', 'f'),
(296, 'Rojtospicsájú', 'm'),
(297, 'Rongyospinájú', 'm'),
(298, 'Roppant hülye', 'm'),
(299, 'Rossz kurva', 'f'),
(300, 'Saját nemével kefélő', 'm'),
(301, 'Segg', 'f'),
(302, 'Seggarc', 'f'),
(303, 'Seggdugó', 'f'),
(304, 'Seggfej', 'f'),
(305, 'Seggnyaló', 'f'),
(306, 'Seggszőr', 'f'),
(307, 'Seggtorlasz', 'f'),
(308, 'Sikoltozásokba öltöztetett', 'm'),
(309, 'Strici', 'f'),
(310, 'Suttyó', 'm'),
(311, 'Sutyerák', 'm'),
(312, 'Szálkafaszú', 'm'),
(313, 'Szar', 'f'),
(314, 'Szaralak', 'f'),
(315, 'Szárazfing', 'f'),
(316, 'Szarbojler', 'f'),
(317, 'Szarcsimbók', 'f'),
(318, 'Szarevő', 'm'),
(319, 'Szarfaszú', 'm'),
(320, 'Szarházi', 'f'),
(321, 'Szarjankó', 'f'),
(322, 'Szarnivaló', 'm'),
(323, 'Szarosvalagú', 'm'),
(324, 'Szarrá vágott', 'm'),
(325, 'Szarrágó', 'f'),
(326, 'Szarszagú', 'm'),
(327, 'Szarszájú', 'm'),
(328, 'Szartragacs', 'f'),
(329, 'Szarzsák', 'f'),
(330, 'Szégyencsicska', 'f'),
(331, 'Szifiliszes', 'm'),
(332, 'Szivattyús kurva', 'f'),
(333, 'Szófosó', 'm'),
(334, 'Szokatlanul fasz', 'm'),
(335, 'Szop-o-matic', 'f'),
(336, 'Szopógép', 'f'),
(337, 'Szopógörcs', 'f'),
(338, 'Szopós kurva', 'f'),
(339, 'Szopottfarkú', 'm'),
(340, 'Szűklyukú', 'm'),
(341, 'Szultán udvarát megjárt', 'm'),
(342, 'Szúnyogfaszni', 'f'),
(343, 'Szuperbuzi', 'f'),
(344, 'Szuperkurva', 'f'),
(345, 'Szűzhártya-repedéses', 'm'),
(346, 'Szűzkurva', 'f'),
(347, 'Szűzpicsa', 'f'),
(348, 'Szűzpunci', 'f'),
(349, 'Tetves', 'm'),
(350, 'Tikfos', 'f'),
(351, 'Tikszar', 'f'),
(352, 'Tompatökű', 'm'),
(353, 'Törpefaszú', 'm'),
(354, 'Toszatlan', 'm'),
(355, 'Toszott', 'm'),
(356, 'Totálisan hülye', 'm'),
(357, 'Tyű de picsa', 'm'),
(358, 'Tyúkfasznyi', 'm'),
(359, 'Tyúkszar', 'f'),
(360, 'Vadfasz', 'f'),
(361, 'Valag', 'f'),
(362, 'Valagváladék', 'f'),
(363, 'Végbélféreg', 'f'),
(364, 'Xar', 'f'),
(365, 'Zsugorított faszú', 'm');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `file_id`, `user_id`, `rating`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 5, '2025-12-02 10:55:24', '2025-12-02 10:55:24'),
(2, 2, 1, 5, '2025-12-16 01:00:56', '2026-01-17 20:16:40'),
(3, 3, 1, 4, '2026-02-19 09:28:29', '2026-02-19 09:28:29'),
(4, 5, 1, 5, '2026-02-25 13:21:40', '2026-02-25 13:21:40'),
(5, 16, 35, 1, '2026-03-29 23:34:20', '2026-03-29 23:34:20'),
(6, 26, 13, 2, '2026-03-29 23:34:20', '2026-03-29 23:34:20'),
(7, 3, 33, 5, '2026-03-29 23:34:20', '2026-03-29 23:34:20'),
(8, 16, 22, 2, '2026-03-29 23:34:20', '2026-03-29 23:34:20'),
(9, 3, 27, 3, '2026-03-29 23:34:20', '2026-03-29 23:34:20');

-- --------------------------------------------------------

--
-- Table structure for table `registration_code_uses`
--

CREATE TABLE `registration_code_uses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reg_code` varchar(64) NOT NULL,
  `used_at` datetime NOT NULL DEFAULT current_timestamp(),
  `used_ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reg_codes`
--

CREATE TABLE `reg_codes` (
  `id` int(11) NOT NULL,
  `code` varchar(64) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `max_uses` int(11) DEFAULT NULL,
  `used` int(11) NOT NULL DEFAULT 0,
  `expires_at` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `reg_codes`
--

INSERT INTO `reg_codes` (`id`, `code`, `description`, `max_uses`, `used`, `expires_at`, `active`, `created_at`) VALUES
(1, 'EARLY-BETA-2025', 'Nagyon korai béta tesztelő kód', 10, 4, NULL, 0, '2025-12-07 14:31:16');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `reporter_id` int(11) NOT NULL,
  `target_type` enum('user','group','note') NOT NULL,
  `target_id` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('open','resolved','dismissed') NOT NULL DEFAULT 'open',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `handled_by` int(11) DEFAULT NULL,
  `handled_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `reporter_id`, `target_type`, `target_id`, `reason`, `status`, `created_at`, `handled_by`, `handled_at`) VALUES
(1, 4, 'note', 2, 'Nincs megadott indok.', 'dismissed', '2025-12-07 13:43:45', 4, '2025-12-07 13:44:03'),
(2, 8, 'note', 2, 'Ez egy teszt', 'dismissed', '2025-12-16 00:21:29', 1, '2026-01-25 18:56:51'),
(3, 1, 'user', 8, 'Ez egy teszt jelentés egy user felé', 'dismissed', '2025-12-16 00:31:04', 1, '2026-01-25 18:58:34');

-- --------------------------------------------------------

--
-- Table structure for table `saved_searches`
--

CREATE TABLE `saved_searches` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `params_json` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `last_seen_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `search_logs`
--

CREATE TABLE `search_logs` (
  `id` bigint(20) NOT NULL,
  `q` varchar(255) DEFAULT NULL,
  `year` tinyint(4) DEFAULT NULL,
  `tag` varchar(100) DEFAULT NULL,
  `sort` varchar(20) NOT NULL,
  `results_count` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `tags` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `tags`) VALUES
(51, 'Adatbázis-kezelés'),
(36, 'Adatbázis-kezelés I.'),
(37, 'Adatbázis-kezelés II.'),
(34, 'Asztali alkalmazások fejlesztése I.'),
(35, 'Asztali alkalmazások fejlesztése II.'),
(38, 'Asztali és mobil alkalmazások fejlesztése és tesztelés I.'),
(39, 'Asztali és mobil alkalmazások fejlesztése és tesztelés II.'),
(45, 'Backend programozás és tesztelés I.'),
(46, 'Backend programozás és tesztelés II.'),
(2, 'Budapesti Műszaki és Gazdaságtudományi Egyetem'),
(4, 'Corvinus Egyetem'),
(6, 'Debreceni Egyetem'),
(12, 'Dunaújvárosi Egyetem'),
(1, 'Eötvös Loránd Tudományegyetem'),
(14, 'Eszterházy Károly Katolikus Egyetem'),
(43, 'Frontend programozás és tesztelés I.'),
(44, 'Frontend programozás és tesztelés II.'),
(23, 'Gazdálkodás és menedzsment'),
(17, 'Gazdaságinformatikus'),
(19, 'Gépészmérnök'),
(49, 'Hálózat programozása és IoT'),
(48, 'Hálózatok'),
(32, 'IKT projektmunka I.'),
(33, 'IKT projektmunka II.'),
(28, 'Informatikai és távközlési alapok I.'),
(29, 'Informatikai és távközlési alapok II.'),
(30, 'Informatikai és távközlési alapok III.'),
(21, 'Informatikus könyvtáros'),
(25, 'Kommunikáció és médiatudomány'),
(22, 'Közgazdász'),
(16, 'Mérnökinformatikus'),
(9, 'Miskolci Egyetem'),
(27, 'Munkavállalói idegen nyelv'),
(26, 'Munkavállalói ismeretek'),
(11, 'Neumann János Egyetem'),
(13, 'Nyíregyházi Egyetem'),
(5, 'Óbudai Egyetem'),
(8, 'Pécsi Tudományegyetem'),
(24, 'Pénzügy és számvitel'),
(31, 'Programozási alapok'),
(15, 'Programtervező informatikus'),
(3, 'Semmelweis Egyetem'),
(47, 'Szakmai angol'),
(10, 'Széchenyi István Egyetem'),
(7, 'Szegedi Tudományegyetem'),
(50, 'Szerverek és felhőszolgáltatások'),
(40, 'Szoftvertesztelés'),
(20, 'Üzemmérnök-informatikus'),
(18, 'Villamosmérnök'),
(41, 'Webprogramozás I.'),
(42, 'Webprogramozás II.'),
(43, 'Tesnevelési Egyetem'),
(44, 'Schola Europa Akadémia Technikum, Gimnázium és Alapfokú Művészeti Iskola'),
(45, 'Budapesti Műszaki SZC Bláthy Ottó Titusz Informatikai Technikum'),
(46, 'Budapesti Műszaki SZC Egressy Gábor Két Tanítási Nyelvű Technikum'),
(47, 'Budapesti Műszaki SZC Petrik Lajos Két Tanítási Nyelvű Technikum'),
(48, 'Újpesti Két Tanítási Nyelvű Műszaki Technikum'),
(49, 'Budapesti Műszaki SZC Trefort Ágoston Két Tanítási Nyelvű Technikum'),
(50, 'Budapesti Műszaki SZC Puskás Tivadar Távközlési Technikum'),
(51, 'Budapesti Műszaki SZC Neumann János Informatikai Technikum'),
(52, 'Budapesti Műszaki SZC Pataky István Híradásipari és Informatikai Technikum'),
(52, 'Budapesti Műszaki SZC Bolyai János Technikum és Kollégium'),
(53, 'Budapesti Műszaki SZC Verebély László Technikum'),
(54, 'Budapesti Műszaki SZC Than Károly Technikum és Szakképző Iskola'),
(55, 'Petőfi Sándor Római Katolikus Általános Iskola és Gimnázium');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`id`, `user_id`, `token`, `created_at`) VALUES
(1, 8, 120502, '2025-12-15 22:19:29'),
(2, 9, 668874, '2026-02-11 14:15:18');

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

CREATE TABLE `translations` (
  `id` int(11) NOT NULL,
  `t_key` varchar(100) NOT NULL,
  `lang_code` varchar(5) NOT NULL,
  `text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `translations`
--

INSERT INTO `translations` (`id`, `t_key`, `lang_code`, `text`) VALUES
(1, 'nav_home', 'hu', 'Főoldal'),
(2, 'nav_home', 'en', 'Home'),
(3, 'nav_upload', 'hu', 'Új jegyzet'),
(4, 'nav_upload', 'en', 'New note'),
(5, 'nav_messages', 'hu', 'Üzenetek'),
(6, 'nav_messages', 'en', 'Messages'),
(7, 'nav_login', 'hu', 'Bejelentkezés'),
(8, 'nav_login', 'en', 'Login'),
(9, 'nav_logout', 'hu', 'Kijelentkezés'),
(10, 'nav_logout', 'en', 'Logout'),
(11, 'hero_greeting', 'hu', 'Szia,'),
(12, 'hero_greeting', 'en', 'Hi,'),
(13, 'hero_nameday', 'hu', 'Mai névnap'),
(14, 'hero_nameday', 'en', 'Name day today'),
(15, 'guest', 'hu', 'Vendég'),
(16, 'guest', 'en', 'Guest'),
(17, 'footer_copy', 'hu', '&copy; 2025 Jegyzetár'),
(18, 'footer_copy', 'en', '&copy; 2025 NoteShare'),
(19, 'site_tagline', 'hu', 'Iskolai jegyzeteket megosztó oldal'),
(20, 'site_tagline', 'en', 'A platform for sharing school notes'),
(21, 'hero_logged_out_subtitle', 'hu', 'Jelentkezz be vagy hozz létre új fiókot az induláshoz.'),
(22, 'hero_logged_out_subtitle', 'en', 'Log in or create a new account to get started.'),
(23, 'hero_welcome', 'hu', 'Üdvözlünk a Jegyzetár rendszerében!'),
(24, 'hero_welcome', 'en', 'Welcome to the NoteShare system!'),
(25, 'nameday_none', 'hu', 'Nincs névnap ma.'),
(26, 'nameday_none', 'en', 'There is no name day today.'),
(27, 'birthday_congrats', 'hu', 'Boldog születésnapot,'),
(28, 'birthday_congrats', 'en', 'Happy birthday,'),
(29, 'meta_keywords', 'hu', 'iskola, jegyzet, megosztás, tanulás'),
(30, 'meta_keywords', 'en', 'school, notes, sharing, studying'),
(31, 'nav_new_note_plus', 'hu', '+ Új jegyzet'),
(32, 'nav_new_note_plus', 'en', '+ New note'),
(33, 'btn_details', 'hu', 'Részletek'),
(34, 'btn_details', 'en', 'Details'),
(35, 'btn_download', 'hu', 'Letöltés'),
(36, 'btn_download', 'en', 'Download'),
(37, 'btn_delete', 'hu', 'Törlés'),
(38, 'btn_delete', 'en', 'Delete'),
(39, 'btn_back_home', 'hu', 'Vissza a főoldalra'),
(40, 'btn_back_home', 'en', 'Back to home'),
(41, 'btn_back_login', 'hu', 'Vissza a bejelentkezéshez'),
(42, 'btn_back_login', 'en', 'Back to login'),
(43, 'btn_accept', 'hu', 'Elfogadás'),
(44, 'btn_accept', 'en', 'Accept'),
(45, 'btn_send', 'hu', 'Küldés'),
(46, 'btn_send', 'en', 'Send'),
(47, 'btn_send_alt', 'hu', 'Elküldés'),
(48, 'btn_send_alt', 'en', 'Send'),
(49, 'btn_login_cta', 'hu', 'Lépj be!'),
(50, 'btn_login_cta', 'en', 'Log in!'),
(51, 'btn_register_cta', 'hu', 'Regisztrálj!'),
(52, 'btn_register_cta', 'en', 'Register!'),
(53, 'btn_continue_discord', 'hu', 'Folytatás Discorddal'),
(54, 'btn_continue_discord', 'en', 'Continue with Discord'),
(55, 'btn_go_to_note', 'hu', 'Ugrás a jegyzetre'),
(56, 'btn_go_to_note', 'en', 'Go to note'),
(57, 'auth_login_title', 'hu', 'Belépés'),
(58, 'auth_login_title', 'en', 'Login'),
(59, 'auth_register_title', 'hu', 'Regisztráció'),
(60, 'auth_register_title', 'en', 'Registration'),
(61, 'auth_already_have_account', 'hu', 'Már van fiókod?'),
(62, 'auth_already_have_account', 'en', 'Already have an account?'),
(63, 'auth_no_account_yet', 'hu', 'Még nincs fiókod?'),
(64, 'auth_no_account_yet', 'en', 'Don’t have an account yet?'),
(65, 'auth_field_lastname', 'hu', 'Vezetéknév'),
(66, 'auth_field_lastname', 'en', 'Last name'),
(67, 'auth_field_firstname', 'hu', 'Keresztnév'),
(68, 'auth_field_firstname', 'en', 'First name'),
(69, 'auth_field_username', 'hu', 'Felhasználónév'),
(70, 'auth_field_username', 'en', 'Username'),
(71, 'auth_field_username_colon', 'hu', 'Felhasználónév:'),
(72, 'auth_field_username_colon', 'en', 'Username:'),
(73, 'auth_field_email', 'hu', 'Email'),
(74, 'auth_field_email', 'en', 'Email'),
(75, 'auth_field_password', 'hu', 'Jelszó'),
(76, 'auth_field_password', 'en', 'Password'),
(77, 'auth_field_password_again', 'hu', 'Jelszó újra'),
(78, 'auth_field_password_again', 'en', 'Repeat password'),
(79, 'auth_field_password_again_colon', 'hu', 'Jelszó újra:'),
(80, 'auth_field_password_again_colon', 'en', 'Repeat password:'),
(81, 'auth_field_birthdate', 'hu', 'Születési dátum'),
(82, 'auth_field_birthdate', 'en', 'Date of birth'),
(83, 'auth_field_gender', 'hu', 'Nem'),
(84, 'auth_field_gender', 'en', 'Gender'),
(85, 'auth_gender_male', 'hu', 'Férfi'),
(86, 'auth_gender_male', 'en', 'Male'),
(87, 'auth_gender_female', 'hu', 'Nő'),
(88, 'auth_gender_female', 'en', 'Female'),
(89, 'auth_gender_other', 'hu', 'Egyéb'),
(90, 'auth_gender_other', 'en', 'Other'),
(91, 'auth_field_security_question', 'hu', 'Biztonsági kérdés:'),
(92, 'auth_field_security_question', 'en', 'Security question:'),
(93, 'auth_field_security_answer', 'hu', 'Biztonsági kérdés válasza:'),
(94, 'auth_field_security_answer', 'en', 'Answer to security question:'),
(95, 'auth_field_answer', 'hu', 'Válasz'),
(96, 'auth_field_answer', 'en', 'Answer'),
(97, 'auth_placeholder_start_typing', 'hu', 'Kezdj el gépelni...'),
(98, 'auth_placeholder_start_typing', 'en', 'Start typing...'),
(99, 'secq_fav_book', 'hu', 'Mi a kedvenc könyved?'),
(100, 'secq_fav_book', 'en', 'What is your favourite book?'),
(101, 'secq_fav_food', 'hu', 'Mi a kedvenc ételed?'),
(102, 'secq_fav_food', 'en', 'What is your favourite food?'),
(103, 'secq_birth_city', 'hu', 'Mi a születési városod?'),
(104, 'secq_birth_city', 'en', 'What is your birth city?'),
(105, 'secq_mothers_maiden', 'hu', 'Mi az édesanyád leánykori neve?'),
(106, 'secq_mothers_maiden', 'en', 'What is your mother’s maiden name?'),
(107, 'secq_first_pet', 'hu', 'Mi volt az első háziállatod neve?'),
(108, 'secq_first_pet', 'en', 'What was the name of your first pet?'),
(109, 'forgot_title', 'hu', 'Elfelejtett Jelszó'),
(110, 'forgot_title', 'en', 'Forgotten password'),
(111, 'forgot_heading', 'hu', 'Jelszó visszaállítása'),
(112, 'forgot_heading', 'en', 'Password reset'),
(113, 'forgot_new_password', 'hu', 'Új jelszó beállítása'),
(114, 'forgot_new_password', 'en', 'Set new password'),
(115, 'forgot_new_password_label', 'hu', 'Új jelszó:'),
(116, 'forgot_new_password_label', 'en', 'New password:'),
(117, 'msg_password_change_success', 'hu', 'A jelszavad sikeresen megváltozott!'),
(118, 'msg_password_change_success', 'en', 'Your password has been successfully changed!'),
(119, 'msg_passwords_not_match', 'hu', 'A jelszavak nem egyeznek!'),
(120, 'msg_passwords_not_match', 'en', 'The passwords do not match!'),
(121, 'msg_password_same_as_old', 'hu', 'Az új jelszavad nem egyezhet a régivel.'),
(122, 'msg_password_same_as_old', 'en', 'Your new password cannot be the same as the old one.'),
(123, 'error_wrong_security_answer', 'hu', 'Helytelen biztonsági válasz!'),
(124, 'error_wrong_security_answer', 'en', 'Incorrect security answer!'),
(125, 'error_wrong_password', 'hu', 'Hibás jelszó!'),
(126, 'error_wrong_password', 'en', 'Incorrect password!'),
(127, 'email_change_page_title', 'hu', 'Email cím módosítása'),
(128, 'email_change_page_title', 'en', 'Change email address'),
(129, 'email_change_title', 'hu', 'Email Módosítás'),
(130, 'email_change_title', 'en', 'Email change'),
(131, 'email_edit_title', 'hu', 'Email módosítás'),
(132, 'email_edit_title', 'en', 'Change email'),
(133, 'email_change_label_new', 'hu', 'Új email cím'),
(134, 'email_change_label_new', 'en', 'New email address'),
(135, 'email_change_label_new_colon', 'hu', 'Új email cím:'),
(136, 'email_change_label_new_colon', 'en', 'New email address:'),
(137, 'email_change_label_again', 'hu', 'Email cím újra:'),
(138, 'email_change_label_again', 'en', 'Email again:'),
(139, 'email_change_label_again_short', 'hu', 'Email újra'),
(140, 'email_change_label_again_short', 'en', 'Email again'),
(141, 'msg_email_change_success', 'hu', 'Az új email címed sikeresen megváltozott!'),
(142, 'msg_email_change_success', 'en', 'Your new email address has been successfully changed!'),
(143, 'msg_email_same_as_old', 'hu', 'Az új email címed nem egyezhet a régivel.'),
(144, 'msg_email_same_as_old', 'en', 'Your new email address cannot be the same as the old one.'),
(145, 'msg_emails_not_match', 'hu', 'A két email cím nem egyezik!'),
(146, 'msg_emails_not_match', 'en', 'The two email addresses do not match!'),
(147, 'msg_email_exists', 'hu', 'Már létezik ilyen email cím!'),
(148, 'msg_email_exists', 'en', 'This email address is already in use!'),
(149, 'msg_generic_success_change', 'hu', 'Sikeres módosítás!'),
(150, 'msg_generic_success_change', 'en', 'Change successful!'),
(151, 'msg_file_upload_failed', 'hu', 'A fájl feltöltése sikertelen!'),
(152, 'msg_file_upload_failed', 'en', 'File upload failed!'),
(153, 'msg_file_uploaded', 'hu', 'A fájl sikeresen feltöltve!'),
(154, 'msg_file_uploaded', 'en', 'File uploaded successfully!'),
(155, 'msg_only_pdf_mp4_docx', 'hu', 'Csak PDF, MP4 vagy DOCX fájlokat lehet feltölteni!'),
(156, 'msg_only_pdf_mp4_docx', 'en', 'Only PDF, MP4 or DOCX files can be uploaded!'),
(157, 'msg_storage_create_failed', 'hu', 'Nem sikerült létrehozni a tárhelyet!'),
(158, 'msg_storage_create_failed', 'en', 'Failed to create storage!'),
(159, 'msg_storage_created', 'hu', 'Tárhely sikeresen létrehozva!'),
(160, 'msg_storage_created', 'en', 'Storage created successfully!'),
(161, 'msg_user_not_found', 'hu', 'Nincs ilyen felhasználó!'),
(162, 'msg_user_not_found', 'en', 'No such user found!'),
(163, 'msg_no_permission_admin', 'hu', 'Nincs jogosultságod az admin felület megtekintéséhez.'),
(164, 'msg_no_permission_admin', 'en', 'You do not have permission to view the admin panel.'),
(165, 'msg_friendid_missing', 'hu', 'Hiba: hiányzó barát azonosító.'),
(166, 'msg_friendid_missing', 'en', 'Error: missing friend ID.'),
(167, 'msg_invalid_user_id', 'hu', 'Hiányzó vagy érvénytelen felhasználó azonosító!'),
(168, 'msg_invalid_user_id', 'en', 'Missing or invalid user identifier!'),
(169, 'msg_invalid_profile_id', 'hu', 'Érvénytelen profil azonosító.'),
(170, 'msg_invalid_profile_id', 'en', 'Invalid profile ID.'),
(171, 'msg_message_empty', 'hu', 'Az üzenet nem lehet üres!'),
(172, 'msg_message_empty', 'en', 'The message cannot be empty!'),
(173, 'msg_comment_write_error', 'hu', 'Hiba történt a komment írásakor!'),
(174, 'msg_comment_write_error', 'en', 'An error occurred while posting the comment!'),
(175, 'msg_file_upload_error', 'hu', 'Hiba történt a fájl feltöltésekor.'),
(176, 'msg_file_upload_error', 'en', 'An error occurred while uploading the file.'),
(177, 'msg_note_not_found', 'hu', 'Jegyzet nem található!'),
(178, 'msg_note_not_found', 'en', 'Note not found!'),
(179, 'msg_note_missing_or_deleted', 'hu', 'A keresett jegyzet nem létezik vagy törölve lett.'),
(180, 'msg_note_missing_or_deleted', 'en', 'The requested note does not exist or has been deleted.'),
(181, 'msg_profile_not_found', 'hu', 'A keresett profil nem található.'),
(182, 'msg_profile_not_found', 'en', 'The requested profile was not found.'),
(183, 'empty_no_comments', 'hu', 'Még nincs komment.'),
(184, 'empty_no_comments', 'en', 'There are no comments yet.'),
(185, 'empty_no_files', 'hu', 'Még nincsenek feltöltött fájlok.'),
(186, 'empty_no_files', 'en', 'There are no uploaded files yet.'),
(187, 'empty_no_friends', 'hu', 'Még nincsenek barátaid.'),
(188, 'empty_no_friends', 'en', 'You don’t have any friends yet.'),
(189, 'empty_no_notifications', 'hu', 'Nincs új értesítésed.'),
(190, 'empty_no_notifications', 'en', 'You have no new notifications.'),
(191, 'empty_no_messages', 'hu', 'Nincsenek üzenetek.'),
(192, 'empty_no_messages', 'en', 'There are no messages.'),
(193, 'empty_no_users_search', 'hu', 'Nincs felhasználó találat.'),
(194, 'empty_no_users_search', 'en', 'No users found.'),
(195, 'empty_no_files_search', 'hu', 'Nincs fájl találat a megadott szűrőkre.'),
(196, 'empty_no_files_search', 'en', 'No files match the given filters.'),
(197, 'upload_page_title', 'hu', 'Anyag feltöltése'),
(198, 'upload_page_title', 'en', 'Upload material'),
(199, 'upload_label_file', 'hu', 'Fájl kiválasztása:'),
(200, 'upload_label_file', 'en', 'Select file:'),
(201, 'upload_label_subject', 'hu', 'Tárgy:'),
(202, 'upload_label_subject', 'en', 'Subject:'),
(203, 'upload_label_tags', 'hu', 'Kulcsszavak, címkék:'),
(204, 'upload_label_tags', 'en', 'Keywords, tags:'),
(205, 'upload_label_description', 'hu', 'Leírás'),
(206, 'upload_label_description', 'en', 'Description'),
(207, 'upload_label_description_colon', 'hu', 'Leírás:'),
(208, 'upload_label_description_colon', 'en', 'Description:'),
(209, 'upload_btn_upload', 'hu', 'Feltöltés'),
(210, 'upload_btn_upload', 'en', 'Upload'),
(211, 'upload_heading_uploaded_files', 'hu', 'Feltöltött anyagok'),
(212, 'upload_heading_uploaded_files', 'en', 'Uploaded materials'),
(213, 'msg_fill_subject_and_tags', 'hu', 'Kérjük, adja meg a tárgyat és a címkéket!'),
(214, 'msg_fill_subject_and_tags', 'en', 'Please provide the subject and tags.'),
(215, 'label_uploaded_by', 'hu', 'Feltöltötte:'),
(216, 'label_uploaded_by', 'en', 'Uploaded by:'),
(217, 'label_rating_average', 'hu', 'Átlag értékelés:'),
(218, 'label_rating_average', 'en', 'Average rating:'),
(219, 'suffix_ratings_short', 'hu', 'ért.'),
(220, 'suffix_ratings_short', 'en', 'ratings'),
(221, 'suffix_ratings_paren', 'hu', 'ért.)'),
(222, 'suffix_ratings_paren', 'en', 'ratings)'),
(223, 'suffix_rating_singular', 'hu', 'értékelés)'),
(224, 'suffix_rating_singular', 'en', 'rating)'),
(225, 'sidebar_top_rated', 'hu', 'Top értékelt'),
(226, 'sidebar_top_rated', 'en', 'Top rated'),
(227, 'label_new_uploads', 'hu', 'Új feltöltések'),
(228, 'label_new_uploads', 'en', 'New uploads'),
(229, 'label_new_comment', 'hu', 'Új hozzászólás'),
(230, 'label_new_comment', 'en', 'New comment'),
(231, 'label_text', 'hu', 'Szöveg'),
(232, 'label_text', 'en', 'Text'),
(233, 'comments_heading', 'hu', 'Kommentek'),
(234, 'comments_heading', 'en', 'Comments'),
(235, 'comments_placeholder', 'hu', 'Írj kommentet.'),
(236, 'comments_placeholder', 'en', 'Write a comment.'),
(237, 'btn_send_rating', 'hu', 'Értékelés küldése'),
(238, 'btn_send_rating', 'en', 'Send rating'),
(239, 'label_rating', 'hu', 'Értékelés'),
(240, 'label_rating', 'en', 'Rating'),
(241, 'label_mark', 'hu', 'Jelölés'),
(242, 'label_mark', 'en', 'Mark'),
(243, 'btn_login_to_rate', 'hu', 'Belépés az értékeléshez'),
(244, 'btn_login_to_rate', 'en', 'Log in to rate'),
(245, 'label_favorite', 'hu', 'Kedvencezés'),
(246, 'label_favorite', 'en', 'Add to favourites'),
(247, 'search_label_keyword', 'hu', 'Kulcsszó:'),
(248, 'search_label_keyword', 'en', 'Keyword:'),
(249, 'search_label_users', 'hu', 'Felhasználók'),
(250, 'search_label_users', 'en', 'Users'),
(251, 'search_label_files', 'hu', 'Fájlok'),
(252, 'search_label_files', 'en', 'Files'),
(253, 'search_btn_search', 'hu', 'Keresés'),
(254, 'search_btn_search', 'en', 'Search'),
(255, 'search_filters_title', 'hu', 'Keresés'),
(256, 'search_filters_title', 'en', 'Search'),
(257, 'search_placeholder', 'hu', 'Keresés…'),
(258, 'search_placeholder', 'en', 'Search…'),
(259, 'profile_heading', 'hu', 'Saját fiók'),
(260, 'profile_heading', 'en', 'My account'),
(261, 'profile_label_name', 'hu', 'Teljes név'),
(262, 'profile_label_name', 'en', 'Full name'),
(263, 'profile_label_firstname', 'hu', 'Keresztnév'),
(264, 'profile_label_firstname', 'en', 'First name'),
(265, 'profile_label_lastname', 'hu', 'Vezetéknév'),
(266, 'profile_label_lastname', 'en', 'Last name'),
(267, 'profile_label_username', 'hu', 'Felhasználó'),
(268, 'profile_label_username', 'en', 'User'),
(269, 'profile_label_birthdate', 'hu', 'Születési dátum'),
(270, 'profile_label_birthdate', 'en', 'Date of birth'),
(271, 'profile_label_avatar', 'hu', 'Profilkép'),
(272, 'profile_label_avatar', 'en', 'Profile picture'),
(273, 'profile_label_avatar_upload', 'hu', 'Profilkép feltöltése'),
(274, 'profile_label_avatar_upload', 'en', 'Upload profile picture'),
(275, 'admin_title', 'hu', 'Admin Panel'),
(276, 'admin_title', 'en', 'Admin Panel'),
(277, 'admin_users_manage', 'hu', 'Felhasználók kezelése'),
(278, 'admin_users_manage', 'en', 'Manage users'),
(279, 'admin_files_manage', 'hu', 'Fájlok kezelése'),
(280, 'admin_files_manage', 'en', 'Manage files'),
(281, 'admin_comments_manage', 'hu', 'Kommentek kezelése'),
(282, 'admin_comments_manage', 'en', 'Manage comments'),
(283, 'admin_categories_manage', 'hu', 'Kategóriák kezelése'),
(284, 'admin_categories_manage', 'en', 'Manage categories'),
(285, 'admin_column_name', 'hu', 'Név'),
(286, 'admin_column_name', 'en', 'Name'),
(287, 'admin_column_fullname', 'hu', 'Teljes név'),
(288, 'admin_column_fullname', 'en', 'Full name'),
(289, 'admin_column_username', 'hu', 'Felhasználónév'),
(290, 'admin_column_username', 'en', 'Username'),
(291, 'admin_column_email', 'hu', 'Email'),
(292, 'admin_column_email', 'en', 'Email'),
(293, 'admin_column_subject', 'hu', 'Kategória'),
(294, 'admin_column_subject', 'en', 'Category'),
(295, 'admin_column_action', 'hu', 'Művelet'),
(296, 'admin_column_action', 'en', 'Action'),
(297, 'notify_title', 'hu', 'Értesítések'),
(298, 'notify_title', 'en', 'Notifications'),
(299, 'notify_clear_all', 'hu', 'Összes értesítés törlése'),
(300, 'notify_clear_all', 'en', 'Clear all notifications'),
(301, 'notify_more', 'hu', 'Összes →'),
(302, 'notify_more', 'en', 'All →'),
(303, 'notify_friend_request_sent_to_you', 'hu', 'A felhasználó küldött neked barátfelkérést.'),
(304, 'notify_friend_request_sent_to_you', 'en', 'This user sent you a friend request.'),
(305, 'notify_you_sent_request', 'hu', 'Te küldted a barátfelkérést.'),
(306, 'notify_you_sent_request', 'en', 'You sent the friend request.'),
(307, 'notify_is_your_friend', 'hu', 'Ti már barátok vagytok!'),
(308, 'notify_is_your_friend', 'en', 'You are already friends!'),
(309, 'notify_marked_as_friend', 'hu', 'barátnak jelölt!'),
(310, 'notify_marked_as_friend', 'en', 'marked you as a friend!'),
(311, 'notify_commented_post', 'hu', 'hozzászólt egy posztodhoz!'),
(312, 'notify_commented_post', 'en', 'commented on your post!'),
(313, 'friends_list_title', 'hu', 'Barátaid'),
(314, 'friends_list_title', 'en', 'Your friends'),
(315, 'friends_request_title', 'hu', 'Barátjelölés'),
(316, 'friends_request_title', 'en', 'Friend request'),
(317, 'friends_mark_as_friend', 'hu', 'Barátnak jelölés'),
(318, 'friends_mark_as_friend', 'en', 'Mark as friend'),
(319, 'friends_relationship', 'hu', 'Barátság'),
(320, 'friends_relationship', 'en', 'Friendship'),
(321, 'friends_already_handled', 'hu', 'Már feldolgozott barátjelölés.'),
(322, 'friends_already_handled', 'en', 'This friend request has already been handled.'),
(323, 'friends_no_friends_yet', 'hu', 'Még nincsenek barátaid.'),
(324, 'friends_no_friends_yet', 'en', 'You don’t have any friends yet.'),
(325, 'messages_title', 'hu', 'Üzenetek'),
(326, 'messages_title', 'en', 'Messages'),
(327, 'messages_no_messages', 'hu', 'Nincsenek üzenetek.'),
(328, 'messages_no_messages', 'en', 'There are no messages.'),
(329, 'messages_choose_friend', 'hu', 'Válassz egy barátot az üzenetküldéshez.'),
(330, 'messages_choose_friend', 'en', 'Choose a friend to start messaging.'),
(331, 'messages_placeholder', 'hu', 'Írj egy üzenetet...'),
(332, 'messages_placeholder', 'en', 'Write a message...'),
(333, 'label_name', 'hu', 'Név'),
(334, 'label_name', 'en', 'Name'),
(335, 'label_firstname', 'hu', 'Keresztnév'),
(336, 'label_firstname', 'en', 'First name'),
(337, 'label_lastname', 'hu', 'Vezetéknév'),
(338, 'label_lastname', 'en', 'Last name'),
(339, 'label_users', 'hu', 'Felhasználók'),
(340, 'label_users', 'en', 'Users'),
(341, 'label_files', 'hu', 'Fájlok'),
(342, 'label_files', 'en', 'Files'),
(343, 'label_video', 'hu', 'Videó'),
(344, 'label_video', 'en', 'Video'),
(345, 'label_new_password_same_error', 'hu', 'Az új jelszavad nem egyezhet a régivel.'),
(346, 'label_new_password_same_error', 'en', 'Your new password cannot be the same as the old one.'),
(347, 'nav_admin', 'hu', 'Admin Panel'),
(348, 'nav_admin', 'en', 'Admin Panel'),
(349, 'nav_profil', 'hu', 'Fiók'),
(350, 'nav_profil', 'en', 'Account'),
(351, 'nav_search', 'hu', 'Keresés'),
(352, 'nav_search', 'en', 'Search'),
(353, 'nav_notify', 'hu', 'Értesítések'),
(354, 'nav_notify', 'en', 'Notifications'),
(355, 'note_docx_download_hint', 'hu', '.docx fájl - töltsd le a megnyitáshoz.'),
(356, 'note_docx_download_hint', 'en', 'This is a .docx file - download it to open.'),
(357, 'nameday_none_today', 'hu', 'Nincs névnap ma.'),
(358, 'nameday_none_today', 'en', 'There is no name day today.'),
(359, 'meta_description_home', 'hu', 'Iskolai jegyzeteket megosztó oldal'),
(360, 'meta_description_home', 'en', 'A site for sharing school notes'),
(361, 'meta_keywords_home', 'hu', 'iskola, jegyzet, megosztás, tanulás'),
(362, 'meta_keywords_home', 'en', 'school, notes, sharing, learning'),
(363, 'home_new_uploads', 'hu', 'Új feltöltések'),
(364, 'home_new_uploads', 'en', 'New uploads'),
(365, 'home_all_arrow', 'hu', 'Összes →'),
(366, 'home_all_arrow', 'en', 'All →'),
(367, 'upload_title', 'hu', 'Feltöltés'),
(368, 'upload_title', 'en', 'Upload'),
(369, 'index_title', 'hu', 'Főoldal'),
(370, 'index_title', 'en', 'Home'),
(371, 'upload_heading', 'hu', 'Anyag feltöltése'),
(372, 'upload_heading', 'en', 'Upload material'),
(373, 'upload_label_name', 'hu', 'Anyag neve:'),
(374, 'upload_label_name', 'en', 'Material name:'),
(375, 'upload_placeholder_name', 'hu', 'pl. Fizika ZH anyag'),
(376, 'upload_placeholder_name', 'en', 'e.g. Physics test material'),
(377, 'upload_placeholder_description', 'hu', 'Rövid leírás az anyagról...'),
(378, 'upload_placeholder_description', 'en', 'Short description of the material...'),
(379, 'upload_placeholder_subject', 'hu', 'pl. fizika, történelem'),
(380, 'upload_placeholder_subject', 'en', 'e.g. physics, history'),
(381, 'upload_placeholder_tags', 'hu', 'pl. ZH, jegyzet, beadandó'),
(382, 'upload_placeholder_tags', 'en', 'e.g. test, notes, assignment'),
(383, 'profile_title', 'hu', 'Fiók'),
(384, 'profile_title', 'en', 'Account'),
(385, 'profile_of', 'hu', 'profilja'),
(386, 'profile_of', 'en', 's profile'),
(387, 'profile_data', 'hu', 'Profil adatok'),
(388, 'profile_data', 'en', 'Profile information'),
(389, 'profile_fullname', 'hu', 'Teljes név'),
(390, 'profile_fullname', 'en', 'Full name'),
(391, 'profile_username', 'hu', 'Felhasználónév'),
(392, 'profile_username', 'en', 'Username'),
(393, 'btn_edit_email', 'hu', 'Email szerkesztése'),
(394, 'btn_edit_email', 'en', 'Edit email'),
(395, 'btn_upload_profile_pic', 'hu', 'Profilkép feltöltése'),
(396, 'btn_upload_profile_pic', 'en', 'Upload profile picture'),
(397, 'btn_add_friend', 'hu', 'Barátnak jelölés'),
(398, 'btn_add_friend', 'en', 'Add friend'),
(399, 'profile_friendship', 'hu', 'Barátság'),
(400, 'profile_friendship', 'en', 'Friendship'),
(401, 'friend_status_friends', 'hu', 'Ti már barátok vagytok!'),
(402, 'friend_status_friends', 'en', 'You are already friends!'),
(403, 'friend_status_sent_by_you', 'hu', 'Te küldted a barátfelkérést.'),
(404, 'friend_status_sent_by_you', 'en', 'You sent the friend request.'),
(405, 'friend_status_sent_to_you', 'hu', 'A felhasználó küldött neked barátfelkérést.'),
(406, 'friend_status_sent_to_you', 'en', 'This user sent you a friend request.'),
(407, 'profile_uploaded_files', 'hu', 'Feltöltött anyagok'),
(408, 'profile_uploaded_files', 'en', 'Uploaded materials'),
(409, 'label_subject', 'hu', 'Tárgy:'),
(410, 'label_subject', 'en', 'Subject:'),
(411, 'label_tags', 'hu', 'Címkék:'),
(412, 'label_tags', 'en', 'Tags:'),
(413, 'docx_warning', 'hu', 'Ez egy .docx fájl. A megtekintéshez töltsd le és nyisd meg Microsoft Word-ben.'),
(414, 'docx_warning', 'en', 'This is a .docx file. Download it and open it in Microsoft Word.'),
(415, 'bday_title', 'hu', 'Boldog születésnapot,'),
(416, 'bday_title', 'en', 'Happy Birthday,'),
(417, 'bday_message', 'hu', 'Kívánunk sok sikert és rengeteg kreatív ötletet!'),
(418, 'bday_message', 'en', 'We wish you lots of success and many creative ideas!'),
(419, 'profile_picture_alt', 'hu', 'Profilkép'),
(420, 'profile_picture_alt', 'en', 'Profile picture'),
(421, 'profile_registration', 'hu', 'Regisztráció'),
(422, 'profile_registration', 'en', 'Registration'),
(423, 'btn_delete_file', 'hu', 'Törlés'),
(424, 'btn_delete_file', 'en', 'Delete'),
(425, 'search_title', 'hu', 'Keresés'),
(426, 'search_title', 'en', 'Search'),
(427, 'search_keyword', 'hu', 'Kulcsszó:'),
(428, 'search_keyword', 'en', 'Keyword:'),
(429, 'search_scope_label', 'hu', 'Keresés típusa'),
(430, 'search_scope_label', 'en', 'Search scope'),
(431, 'search_scope_all', 'hu', 'Mindkettő'),
(432, 'search_scope_all', 'en', 'Both'),
(433, 'search_scope_files', 'hu', 'Csak fájlok'),
(434, 'search_scope_files', 'en', 'Files only'),
(435, 'search_scope_users', 'hu', 'Csak felhasználók'),
(436, 'search_scope_users', 'en', 'Users only'),
(437, 'search_type_label', 'hu', 'Fájltípus'),
(438, 'search_type_label', 'en', 'File type'),
(439, 'search_type_all', 'hu', 'Összes fájl'),
(440, 'search_type_all', 'en', 'All files'),
(441, 'search_type_pdf', 'hu', 'PDF'),
(442, 'search_type_pdf', 'en', 'PDF'),
(443, 'search_type_mp4', 'hu', 'Videó (MP4)'),
(444, 'search_type_mp4', 'en', 'Video (MP4)'),
(445, 'search_type_docx', 'hu', 'Word (DOCX)'),
(446, 'search_type_docx', 'en', 'Word (DOCX)'),
(447, 'search_sort_label', 'hu', 'Rendezés'),
(448, 'search_sort_label', 'en', 'Sort by'),
(449, 'search_sort_new', 'hu', 'Legújabb elöl'),
(450, 'search_sort_new', 'en', 'Newest first'),
(451, 'search_sort_old', 'hu', 'Legrégebbi elöl'),
(452, 'search_sort_old', 'en', 'Oldest first'),
(453, 'search_sort_top', 'hu', 'Top értékelt'),
(454, 'search_sort_top', 'en', 'Top rated'),
(455, 'search_btn', 'hu', 'Keresés'),
(456, 'search_btn', 'en', 'Search'),
(457, 'pill_pdf', 'hu', 'PDF'),
(458, 'pill_pdf', 'en', 'PDF'),
(459, 'pill_video', 'hu', 'Videó'),
(460, 'pill_video', 'en', 'Video'),
(461, 'pill_word', 'hu', 'Word'),
(462, 'pill_word', 'en', 'Word'),
(463, 'pill_top_rated', 'hu', 'Top értékelt'),
(464, 'pill_top_rated', 'en', 'Top rated'),
(465, 'pill_users', 'hu', 'Felhasználók'),
(466, 'pill_users', 'en', 'Users'),
(467, 'result_users', 'hu', 'Felhasználók'),
(468, 'result_users', 'en', 'Users'),
(469, 'result_files', 'hu', 'Fájlok'),
(470, 'result_files', 'en', 'Files'),
(471, 'empty_no_users', 'hu', 'Nincs felhasználó találat.'),
(472, 'empty_no_users', 'en', 'No matching users found.'),
(473, 'empty_no_files_filter', 'hu', 'Nincs fájl találat a megadott szűrőkre.'),
(474, 'empty_no_files_filter', 'en', 'No files match the selected filters.'),
(475, 'video_fallback', 'hu', 'A böngésződ nem támogatja a video lejátszást.'),
(476, 'video_fallback', 'en', 'Your browser does not support video playback.'),
(477, 'rating_average', 'hu', 'Átlag értékelés:'),
(478, 'rating_average', 'en', 'Average rating:'),
(479, 'rating_count_suffix', 'hu', 'ért.'),
(480, 'rating_count_suffix', 'en', 'ratings'),
(481, 'notif_friend_request_title', 'hu', 'Barátjelölés'),
(482, 'notif_friend_request_title', 'en', 'Friend request'),
(483, 'notif_friend_marked_you', 'hu', 'barátnak jelölt!'),
(484, 'notif_friend_marked_you', 'en', 'sent you a friend request!'),
(485, 'btn_accept_friend', 'hu', 'Elfogadás'),
(486, 'btn_accept_friend', 'en', 'Accept'),
(487, 'notif_friend_already_processed', 'hu', 'Már feldolgozott barátjelölés.'),
(488, 'notif_friend_already_processed', 'en', 'This friend request has already been handled.'),
(489, 'notif_new_comment_title', 'hu', 'Új hozzászólás'),
(490, 'notif_new_comment_title', 'en', 'New comment'),
(491, 'notif_comment_your_post', 'hu', 'hozzászólt egy posztodhoz!'),
(492, 'notif_comment_your_post', 'en', 'commented on one of your posts!'),
(493, 'btn_delete_all_notifications', 'hu', 'Összes értesítés törlése'),
(494, 'btn_delete_all_notifications', 'en', 'Delete all notifications'),
(495, 'messages_friends_heading', 'hu', 'Barátaid'),
(496, 'messages_friends_heading', 'en', 'Your friends'),
(497, 'messages_no_friends', 'hu', 'Még nincsenek barátaid.'),
(498, 'messages_no_friends', 'en', 'You have no friends yet.'),
(499, 'messages_friend_not_found', 'hu', 'A kiválasztott felhasználó nem található.'),
(500, 'messages_friend_not_found', 'en', 'The selected user could not be found.'),
(501, 'msg_message_send_error', 'hu', 'Hiba történt az üzenet küldésekor.'),
(502, 'msg_message_send_error', 'en', 'An error occurred while sending the message.'),
(503, 'auth_page_title', 'hu', 'Bejelentkezés'),
(504, 'auth_page_title', 'en', 'Log in'),
(505, 'auth_welcome_title', 'hu', 'Üdvözlünk a Jegyzetár rendszerében!'),
(506, 'auth_welcome_title', 'en', 'Welcome to the Jegyzetár system!'),
(507, 'auth_welcome_subtitle', 'hu', 'Jelentkezz be vagy hozz létre új fiókot az induláshoz.'),
(508, 'auth_welcome_subtitle', 'en', 'Log in or create a new account to get started.'),
(509, 'auth_login_heading', 'hu', 'Bejelentkezés'),
(510, 'auth_login_heading', 'en', 'Log in'),
(511, 'label_username', 'hu', 'Felhasználónév'),
(512, 'label_username', 'en', 'Username'),
(513, 'label_password', 'hu', 'Jelszó'),
(514, 'label_password', 'en', 'Password'),
(515, 'auth_btn_login', 'hu', 'Belépés'),
(516, 'auth_btn_login', 'en', 'Log in'),
(517, 'auth_forgot_password', 'hu', 'Elfelejtetted a jelszavad?'),
(518, 'auth_forgot_password', 'en', 'Forgot your password?'),
(519, 'auth_no_account', 'hu', 'Még nincs fiókod?'),
(520, 'auth_no_account', 'en', 'Don’t have an account yet?'),
(521, 'auth_link_register', 'hu', 'Regisztrálj!'),
(522, 'auth_link_register', 'en', 'Sign up!'),
(523, 'auth_register_heading', 'hu', 'Regisztráció'),
(524, 'auth_register_heading', 'en', 'Registration'),
(525, 'label_birthdate', 'hu', 'Születési dátum'),
(526, 'label_birthdate', 'en', 'Date of birth'),
(527, 'label_gender', 'hu', 'Nem'),
(528, 'label_gender', 'en', 'Gender'),
(529, 'gender_male', 'hu', 'Férfi'),
(530, 'gender_male', 'en', 'Male'),
(531, 'gender_female', 'hu', 'Nő'),
(532, 'gender_female', 'en', 'Female'),
(533, 'gender_other', 'hu', 'Egyéb'),
(534, 'gender_other', 'en', 'Other'),
(535, 'label_email', 'hu', 'Email'),
(536, 'label_email', 'en', 'Email'),
(537, 'label_password_again', 'hu', 'Jelszó újra'),
(538, 'label_password_again', 'en', 'Password again'),
(539, 'auth_security_question_label', 'hu', 'Biztonsági kérdés:'),
(540, 'auth_security_question_label', 'en', 'Security question:'),
(541, 'auth_security_answer_label', 'hu', 'Válasz'),
(542, 'auth_security_answer_label', 'en', 'Answer'),
(543, 'auth_btn_register', 'hu', 'Regisztráció'),
(544, 'auth_btn_register', 'en', 'Register'),
(545, 'auth_have_account', 'hu', 'Már van fiókod?'),
(546, 'auth_have_account', 'en', 'Already have an account?'),
(547, 'auth_link_login', 'hu', 'Lépj be!'),
(548, 'auth_link_login', 'en', 'Log in!'),
(549, 'auth_continue_with_discord', 'hu', 'Folytatás Discorddal'),
(550, 'auth_continue_with_discord', 'en', 'Continue with Discord'),
(551, 'sec_q_favorite_book', 'hu', 'Mi a kedvenc könyved?'),
(552, 'sec_q_favorite_book', 'en', 'What is your favorite book?'),
(553, 'sec_q_first_pet_name', 'hu', 'Mi volt az első háziállatod neve?'),
(554, 'sec_q_first_pet_name', 'en', 'What was the name of your first pet?'),
(555, 'sec_q_mother_maiden_name', 'hu', 'Mi az édesanyád leánykori neve?'),
(556, 'sec_q_mother_maiden_name', 'en', 'What is your mother’s maiden name?'),
(557, 'sec_q_birth_city', 'hu', 'Mi a születési városod?'),
(558, 'sec_q_birth_city', 'en', 'What is your birth city?'),
(559, 'sec_q_favorite_food', 'hu', 'Mi a kedvenc ételed?'),
(560, 'sec_q_favorite_food', 'en', 'What is your favorite food?'),
(561, 'msg_storage_failed', 'hu', 'Nem sikerült létrehozni a tárhelyet!'),
(562, 'msg_storage_failed', 'en', 'Failed to create storage!'),
(563, 'msg_username_exists', 'hu', 'Már létezik ilyen felhasználó!'),
(564, 'msg_username_exists', 'en', 'This username is already taken!'),
(565, 'msg_wrong_password', 'hu', 'Hibás jelszó!'),
(566, 'msg_wrong_password', 'en', 'Incorrect password!'),
(567, 'msg_registration_failed', 'hu', 'Hiba történt a regisztráció során.'),
(568, 'msg_registration_failed', 'en', 'An error occurred during registration.'),
(569, 'email_edit_heading_main', 'hu', 'Email cím módosítása'),
(570, 'email_edit_heading_main', 'en', 'Change email address'),
(571, 'email_edit_heading_new', 'hu', 'Új email cím'),
(572, 'email_edit_heading_new', 'en', 'New email address'),
(573, 'label_new_email', 'hu', 'Új email cím:'),
(574, 'label_new_email', 'en', 'New email address:'),
(575, 'label_new_email_again', 'hu', 'Email cím újra:'),
(576, 'label_new_email_again', 'en', 'Email address again:'),
(577, 'placeholder_email', 'hu', 'Email'),
(578, 'placeholder_email', 'en', 'Email'),
(579, 'placeholder_email_again', 'hu', 'Email újra'),
(580, 'placeholder_email_again', 'en', 'Email again'),
(581, 'label_security_answer_full', 'hu', 'Biztonsági kérdés válasza:'),
(582, 'label_security_answer_full', 'en', 'Answer to the security question:'),
(583, 'placeholder_security_answer', 'hu', 'Válasz'),
(584, 'placeholder_security_answer', 'en', 'Answer'),
(585, 'btn_submit', 'hu', 'Elküldés'),
(586, 'btn_submit', 'en', 'Submit'),
(587, 'btn_back_profile', 'hu', 'Vissza a profilhoz'),
(588, 'btn_back_profile', 'en', 'Back to profile'),
(589, 'change_success_title', 'hu', 'Sikeres módosítás!'),
(590, 'change_success_title', 'en', 'Change successful!'),
(591, 'email_edit_success_text', 'hu', 'Az új email címed sikeresen megváltozott!'),
(592, 'email_edit_success_text', 'en', 'Your email address has been successfully updated!'),
(593, 'msg_wrong_security_answer', 'hu', 'Helytelen biztonsági válasz!'),
(594, 'msg_wrong_security_answer', 'en', 'Incorrect security answer!'),
(595, 'placeholder_username', 'hu', 'Felhasználónév'),
(596, 'placeholder_username', 'en', 'Username'),
(597, 'password_forgot_title', 'hu', 'Elfelejtett jelszó'),
(598, 'password_forgot_title', 'en', 'Forgot password'),
(599, 'password_reset_heading_main', 'hu', 'Jelszó visszaállítása'),
(600, 'password_reset_heading_main', 'en', 'Password reset'),
(601, 'password_reset_heading_new', 'hu', 'Új jelszó beállítása'),
(602, 'password_reset_heading_new', 'en', 'Set a new password'),
(603, 'label_new_password', 'hu', 'Új jelszó:'),
(604, 'label_new_password', 'en', 'New password:'),
(605, 'placeholder_password', 'hu', 'Jelszó'),
(606, 'placeholder_password', 'en', 'Password'),
(607, 'placeholder_password_again', 'hu', 'Jelszó újra'),
(608, 'placeholder_password_again', 'en', 'Password again'),
(609, 'btn_change_password', 'hu', 'Jelszó módosítása'),
(610, 'btn_change_password', 'en', 'Change password'),
(611, 'password_change_success_text', 'hu', 'A jelszavad sikeresen megváltozott!'),
(612, 'password_change_success_text', 'en', 'Your password has been successfully changed!'),
(613, 'btn_go_to_login', 'hu', 'Bejelentkezés'),
(614, 'btn_go_to_login', 'en', 'Back to login'),
(615, 'link_back_to_login', 'hu', 'Vissza a bejelentkezéshez'),
(616, 'link_back_to_login', 'en', 'Back to login screen'),
(617, 'footer_developers_label', 'hu', 'Fejlesztők'),
(618, 'footer_developers_label', 'en', 'Developers'),
(619, 'footer_github_link', 'hu', 'GitHub'),
(620, 'footer_github_link', 'en', 'GitHub'),
(621, 'footer_rights', 'hu', '© 2025 Jegyzetár'),
(622, 'footer_rights', 'en', '© 2025 Jegyzetár'),
(623, 'nav_home', 'de', 'Startseite'),
(624, 'nav_upload', 'de', 'Hochladen'),
(625, 'nav_messages', 'de', 'Nachrichten'),
(626, 'nav_login', 'de', 'Anmelden'),
(627, 'nav_logout', 'de', 'Abmelden'),
(628, 'hero_greeting', 'de', 'Hallo'),
(629, 'hero_nameday', 'de', 'Namenstag heute'),
(630, 'guest', 'de', 'Gast'),
(631, 'footer_copy', 'de', '© 2025 Jegyzetár'),
(632, 'site_tagline', 'de', 'Plattform zum Teilen von Schulnotizen'),
(633, 'hero_logged_out_subtitle', 'de', 'Melde dich an oder erstelle ein Konto, um zu starten.'),
(634, 'hero_welcome', 'de', 'Willkommen im Jegyzetár-System!'),
(635, 'nameday_none', 'de', 'Heute gibt es keinen Namenstag.'),
(636, 'birthday_congrats', 'de', 'Alles Gute zum Geburtstag,'),
(637, 'meta_keywords', 'de', 'Schule, Notizen, Teilen, Lernen'),
(638, 'btn_details', 'de', 'Details'),
(639, 'btn_download', 'de', 'Herunterladen'),
(640, 'btn_delete', 'de', 'Löschen'),
(641, 'btn_back_home', 'de', 'Zur Startseite'),
(642, 'btn_back_login', 'de', 'Zur Anmeldung'),
(643, 'btn_accept', 'de', 'Akzeptieren'),
(644, 'btn_send', 'de', 'Senden'),
(645, 'btn_send_alt', 'de', 'Senden'),
(646, 'btn_login_cta', 'de', 'Jetzt anmelden'),
(647, 'btn_register_cta', 'de', 'Registrieren'),
(648, 'btn_continue_discord', 'de', 'Mit Discord fortfahren'),
(649, 'btn_go_to_note', 'de', 'Zur Notiz'),
(650, 'footer_developers_label', 'de', 'Entwickler'),
(651, 'footer_github_link', 'de', 'GitHub'),
(652, 'footer_rights', 'de', '© 2025 Jegyzetár'),
(653, 'site_title', 'de', 'Jegyzetár'),
(654, 'meta_description_home', 'de', 'Plattform zum Teilen von Schulnotizen'),
(655, 'meta_keywords_home', 'de', 'Schule, Notizen, Teilen, Lernen'),
(656, 'nav_notifications', 'de', 'Benachrichtigungen'),
(657, 'nav_profile', 'de', 'Profil'),
(658, 'nav_register', 'de', 'Registrieren'),
(659, 'btn_edit', 'de', 'Bearbeiten'),
(660, 'btn_save', 'de', 'Speichern'),
(661, 'btn_cancel', 'de', 'Abbrechen'),
(662, 'btn_back_profile', 'de', 'Zurück zum Profil'),
(663, 'btn_accept_friend', 'de', 'Freundschaft akzeptieren'),
(664, 'btn_send_message', 'de', 'Nachricht senden'),
(665, 'btn_delete_all_notifications', 'de', 'Alle Benachrichtigungen löschen'),
(666, 'nameday_today', 'de', 'Namenstag heute'),
(667, 'nameday_none_today', 'de', 'Heute gibt es keinen Namenstag.'),
(668, 'empty_no_notifications', 'de', 'Du hast keine neuen Benachrichtigungen.'),
(669, 'empty_no_files', 'de', 'Keine Dateien gefunden.'),
(670, 'empty_no_users', 'de', 'Keine Benutzer gefunden.'),
(671, 'empty_no_messages', 'de', 'Noch keine Nachrichten.'),
(672, 'empty_no_friends', 'de', 'Du hast noch keine Freunde.'),
(673, 'notify_title', 'de', 'Benachrichtigungen'),
(674, 'notif_friend_request_title', 'de', 'Freundschaftsanfrage'),
(675, 'notif_friend_marked_you', 'de', 'hat dich als Freund markiert!'),
(676, 'nofif_friend_already_processed', 'de', 'Freundschaftsanfrage bereits bearbeitet.'),
(677, 'notif_new_comment_title', 'de', 'Neuer Kommentar'),
(678, 'notif_comment_your_post', 'de', 'hat deinen Beitrag kommentiert.'),
(679, 'notify_mark_all_read', 'de', 'Alle als gelesen markieren'),
(680, 'search_title', 'de', 'Suche'),
(681, 'search_keyword', 'de', 'Suchbegriff'),
(682, 'search_placeholder', 'de', 'Suche...'),
(683, 'search_scope_all', 'de', 'Alles'),
(684, 'search_scope_files', 'de', 'Nur Dateien'),
(685, 'search_scope_users', 'de', 'Nur Benutzer'),
(686, 'search_type_all', 'de', 'Alle Dateitypen'),
(687, 'search_type_pdf', 'de', 'PDF'),
(688, 'search_type_mp4', 'de', 'Video (MP4)'),
(689, 'search_type_docx', 'de', 'Word (DOCX)'),
(690, 'search_sort_new', 'de', 'Neueste zuerst'),
(691, 'search_sort_old', 'de', 'Älteste zuerst'),
(692, 'search_sort_top', 'de', 'Top bewertet'),
(693, 'profile_title', 'de', 'Profil'),
(694, 'profile_edit', 'de', 'Profil bearbeiten'),
(695, 'profile_email', 'de', 'E-Mail'),
(696, 'profile_username', 'de', 'Benutzername'),
(697, 'profile_birthdate', 'de', 'Geburtsdatum'),
(698, 'profile_gender', 'de', 'Geschlecht'),
(699, 'profile_registration_date', 'de', 'Registrierungsdatum'),
(700, 'gender_male', 'de', 'Männlich'),
(701, 'gender_female', 'de', 'Weiblich'),
(702, 'gender_other', 'de', 'Divers'),
(703, 'email_edit_title', 'de', 'E-Mail ändern'),
(704, 'email_new', 'de', 'Neue E-Mail-Adresse'),
(705, 'email_new_repeat', 'de', 'Neue E-Mail-Adresse wiederholen'),
(706, 'email_change_success', 'de', 'E-Mail erfolgreich geändert!'),
(707, 'password_reset_title', 'de', 'Passwort zurücksetzen'),
(708, 'password_new', 'de', 'Neues Passwort'),
(709, 'password_new_repeat', 'de', 'Passwort wiederholen'),
(710, 'password_change_success', 'de', 'Das Passwort wurde erfolgreich geändert!'),
(711, 'auth_page_title', 'de', 'Anmeldung'),
(712, 'auth_welcome_title', 'de', 'Willkommen im Jegyzetár-System!'),
(713, 'auth_welcome_subtitle', 'de', 'Melde dich an oder erstelle ein neues Konto, um zu starten.'),
(714, 'auth_login_heading', 'de', 'Anmeldung'),
(715, 'auth_register_heading', 'de', 'Registrierung'),
(716, 'label_username', 'de', 'Benutzername'),
(717, 'label_password', 'de', 'Passwort'),
(718, 'label_lastname', 'de', 'Nachname'),
(719, 'label_firstname', 'de', 'Vorname'),
(720, 'label_birthdate', 'de', 'Geburtsdatum'),
(721, 'label_gender', 'de', 'Geschlecht'),
(722, 'label_email', 'de', 'E-Mail'),
(723, 'label_password_again', 'de', 'Passwort erneut'),
(724, 'auth_security_question_label', 'de', 'Sicherheitsfrage:'),
(725, 'auth_security_answer_label', 'de', 'Antwort'),
(726, 'auth_forgot_password', 'de', 'Passwort vergessen?'),
(727, 'auth_btn_login', 'de', 'Anmelden'),
(728, 'auth_btn_register', 'de', 'Registrieren'),
(729, 'auth_continue_with_discord', 'de', 'Mit Discord fortfahren'),
(730, 'auth_no_account', 'de', 'Du hast noch kein Konto?'),
(731, 'auth_link_register', 'de', 'Registriere dich!'),
(732, 'auth_have_account', 'de', 'Du hast schon ein Konto?'),
(733, 'auth_link_login', 'de', 'Melde dich an!'),
(734, 'sec_q_favorite_book', 'de', 'Was ist dein Lieblingsbuch?'),
(735, 'sec_q_first_pet_name', 'de', 'Wie hieß dein erstes Haustier?'),
(736, 'sec_q_mother_maiden_name', 'de', 'Wie lautet der Mädchenname deiner Mutter?'),
(737, 'sec_q_birth_city', 'de', 'In welcher Stadt bist du geboren?'),
(738, 'sec_q_favorite_food', 'de', 'Was ist dein Lieblingsessen?'),
(739, 'msg_storage_created', 'de', 'Speicher wurde erfolgreich erstellt!'),
(740, 'msg_storage_failed', 'de', 'Speicher konnte nicht erstellt werden!'),
(741, 'msg_storage_create_failed', 'de', 'Speicher konnte nicht erstellt werden!'),
(742, 'msg_passwords_not_match', 'de', 'Die Passwörter stimmen nicht überein!'),
(743, 'msg_email_exists', 'de', 'Diese E-Mail-Adresse wird bereits verwendet!'),
(744, 'msg_username_exists', 'de', 'Dieser Benutzername ist bereits vergeben!'),
(745, 'msg_wrong_password', 'de', 'Falsches Passwort!'),
(746, 'msg_user_not_found', 'de', 'Es wurde kein solcher Benutzer gefunden!'),
(747, 'msg_registration_failed', 'de', 'Bei der Registrierung ist ein Fehler aufgetreten.'),
(748, 'msg_wrong_security_answer', 'de', 'Falsche Antwort auf die Sicherheitsfrage!'),
(749, 'msg_invalid_user_id', 'de', 'Fehlende oder ungültige Benutzer-ID!'),
(750, 'msg_email_same_as_old', 'de', 'Deine neue E-Mail-Adresse darf nicht mit der alten übereinstimmen.'),
(751, 'msg_emails_not_match', 'de', 'Die zwei E-Mail-Adressen stimmen nicht überein!'),
(752, 'msg_password_same_as_old', 'de', 'Dein neues Passwort darf nicht mit dem alten übereinstimmen.'),
(753, 'email_edit_heading_main', 'de', 'E-Mail-Adresse ändern'),
(754, 'email_edit_heading_new', 'de', 'Neue E-Mail-Adresse'),
(755, 'label_new_email', 'de', 'Neue E-Mail-Adresse:'),
(756, 'label_new_email_again', 'de', 'E-Mail-Adresse erneut:'),
(757, 'placeholder_email', 'de', 'E-Mail'),
(758, 'placeholder_email_again', 'de', 'E-Mail erneut'),
(759, 'placeholder_username', 'de', 'Benutzername'),
(760, 'label_security_answer_full', 'de', 'Antwort auf die Sicherheitsfrage:'),
(761, 'placeholder_security_answer', 'de', 'Antwort'),
(762, 'btn_submit', 'de', 'Absenden'),
(763, 'change_success_title', 'de', 'Änderung erfolgreich!'),
(764, 'email_edit_success_text', 'de', 'Deine neue E-Mail-Adresse wurde erfolgreich gespeichert!'),
(765, 'password_forgot_title', 'de', 'Passwort vergessen'),
(766, 'password_reset_heading_main', 'de', 'Passwort zurücksetzen'),
(767, 'password_reset_heading_new', 'de', 'Neues Passwort festlegen'),
(768, 'label_new_password', 'de', 'Neues Passwort:'),
(769, 'placeholder_password', 'de', 'Passwort'),
(770, 'placeholder_password_again', 'de', 'Passwort erneut'),
(771, 'btn_change_password', 'de', 'Passwort ändern'),
(772, 'password_change_success_text', 'de', 'Dein Passwort wurde erfolgreich geändert!'),
(773, 'btn_go_to_login', 'de', 'Zur Anmeldung'),
(774, 'link_back_to_login', 'de', 'Zurück zum Anmeldebildschirm'),
(775, 'home_new_uploads', 'de', 'Neue Uploads'),
(776, 'home_all_arrow', 'de', 'Alle →'),
(777, 'messages_title', 'de', 'Nachrichten'),
(778, 'messages_friends_heading', 'de', 'Deine Freunde'),
(779, 'messages_no_friends', 'de', 'Du hast noch keine Freunde.'),
(780, 'messages_no_messages', 'de', 'Noch keine Nachrichten.'),
(781, 'messages_placeholder', 'de', 'Schreibe eine Nachricht...'),
(782, 'messages_choose_friend', 'de', 'Wähle einen Freund, um eine Nachricht zu schreiben.'),
(783, 'messages_friend_not_found', 'de', 'Der ausgewählte Benutzer wurde nicht gefunden.'),
(784, 'btn_upload_profile_pic', 'de', 'Profilbild hochladen'),
(785, 'label_subject', 'de', 'Fach'),
(786, 'label_tags', 'de', 'Schlagwörter'),
(787, 'label_rating_average', 'de', 'Durchschnittliche Bewertung:'),
(788, 'rating_average_label', 'de', 'Durchschnittliche Bewertung'),
(789, 'rating_count_suffix', 'de', 'Bewertungen'),
(790, 'suffix_rating_singular', 'de', 'Bewertung'),
(791, 'suffix_rating_plural', 'de', 'Bewertungen'),
(792, 'upload_page_title', 'de', 'Hochladen'),
(793, 'upload_title', 'de', 'Hochladen'),
(794, 'upload_heading', 'de', 'Neue Notiz hochladen'),
(795, 'upload_heading_uploaded_files', 'de', 'Deine hochgeladenen Dateien'),
(796, 'upload_label_name', 'de', 'Titel:'),
(797, 'upload_label_subject', 'de', 'Fach:'),
(798, 'upload_label_description', 'de', 'Beschreibung:'),
(799, 'upload_label_description_colon', 'de', 'Beschreibung:'),
(800, 'upload_label_tags', 'de', 'Schlagwörter:'),
(801, 'upload_label_file', 'de', 'Datei:'),
(802, 'upload_placeholder_name', 'de', 'z. B. Physik-Testmaterial'),
(803, 'upload_placeholder_subject', 'de', 'z. B. Physik, Geschichte'),
(804, 'upload_placeholder_description', 'de', 'Kurze Beschreibung des Materials...'),
(805, 'upload_placeholder_tags', 'de', 'z. B. Test, Notizen, Abgabe'),
(806, 'upload_btn_upload', 'de', 'Hochladen'),
(807, 'msg_file_upload_success', 'de', 'Die Datei wurde erfolgreich hochgeladen!'),
(808, 'msg_file_upload_failed', 'de', 'Beim Hochladen der Datei ist ein Fehler aufgetreten.'),
(809, 'msg_file_upload_error', 'de', 'Beim Hochladen der Datei ist ein Fehler aufgetreten.'),
(810, 'msg_invalid_filetype', 'de', 'Dieser Dateityp ist nicht erlaubt.'),
(811, 'msg_no_file_selected', 'de', 'Es wurde keine Datei ausgewählt.'),
(812, 'docx_warning', 'de', 'Dies ist eine .docx-Datei. Lade sie herunter und öffne sie in Microsoft Word.'),
(813, 'video_fallback', 'de', 'Dein Browser unterstützt die Videowiedergabe nicht.'),
(814, 'note_details_title', 'de', 'Notizdetails'),
(815, 'note_uploaded_by', 'de', 'Hochgeladen von'),
(816, 'note_uploaded_at', 'de', 'Hochgeladen am'),
(817, 'note_subject', 'de', 'Fach'),
(818, 'note_description', 'de', 'Beschreibung'),
(819, 'note_tags', 'de', 'Tags'),
(820, 'note_file_type', 'de', 'Dateityp'),
(821, 'note_back_to_search', 'de', 'Zurück zur Suche'),
(822, 'file_preview_pdf', 'de', 'PDF-Vorschau'),
(823, 'file_preview_mp4', 'de', 'Videovorschau'),
(824, 'file_preview_docx', 'de', 'Dies ist eine DOCX-Datei. Lade sie herunter, um sie in Microsoft Word zu öffnen.'),
(825, 'file_preview_not_supported', 'de', 'Dieser Dateityp kann nicht direkt angezeigt werden.'),
(826, 'note_rating_title', 'de', 'Bewertung'),
(827, 'note_rating_your_rating', 'de', 'Deine Bewertung'),
(828, 'note_rating_average', 'de', 'Durchschnittliche Bewertung'),
(829, 'note_rating_total', 'de', 'Anzahl Bewertungen'),
(830, 'note_rating_login_needed', 'de', 'Du musst angemeldet sein, um zu bewerten.'),
(831, 'note_rating_thanks', 'de', 'Danke für deine Bewertung!'),
(832, 'comments_title', 'de', 'Kommentare'),
(833, 'comments_none', 'de', 'Keine Kommentare vorhanden.'),
(834, 'comments_add_comment', 'de', 'Kommentar hinzufügen'),
(835, 'comments_placeholder', 'de', 'Schreibe einen Kommentar...'),
(836, 'comments_btn_send', 'de', 'Absenden'),
(837, 'msg_comment_added', 'de', 'Kommentar erfolgreich hinzugefügt!'),
(838, 'msg_comment_failed', 'de', 'Beim Hinzufügen des Kommentars ist ein Fehler aufgetreten.'),
(839, 'file_info_title', 'de', 'Dateiinformationen'),
(840, 'file_info_size', 'de', 'Dateigröße'),
(841, 'file_info_name', 'de', 'Dateiname'),
(842, 'file_info_extension', 'de', 'Erweiterung'),
(843, 'file_info_last_modified', 'de', 'Zuletzt geändert'),
(844, 'file_info_downloads', 'de', 'Downloads'),
(845, 'uploader_profile', 'de', 'Zum Profil'),
(846, 'uploader_other_files', 'de', 'Andere Dateien dieses Benutzers'),
(847, 'note_btn_download', 'de', 'Datei herunterladen'),
(848, 'note_btn_back', 'de', 'Zurück'),
(849, 'note_btn_delete', 'de', 'Notiz löschen'),
(850, 'note_btn_edit', 'de', 'Notiz bearbeiten'),
(851, 'note_delete_confirm_title', 'de', 'Löschen bestätigen'),
(852, 'note_delete_confirm_text', 'de', 'Bist du sicher, dass du diese Notiz löschen möchtest?'),
(853, 'note_delete_success', 'de', 'Die Notiz wurde gelöscht!'),
(854, 'note_delete_failed', 'de', 'Beim Löschen der Notiz ist ein Fehler aufgetreten.'),
(855, 'note_related_files', 'de', 'Ähnliche Dateien'),
(856, 'note_related_no_files', 'de', 'Keine ähnlichen Dateien gefunden.'),
(857, 'msg_email_invalid', 'de', 'Ungültige E-Mail-Adresse'),
(858, 'msg_invalid_credentials', 'de', 'Ungültige Anmeldedaten'),
(859, 'error_generic', 'de', 'Es ist ein Fehler aufgetreten'),
(860, 'msg_profile_update_success', 'de', 'Profil wurde aktualisiert'),
(861, 'msg_profile_update_failed', 'de', 'Profilaktualisierung fehlgeschlagen'),
(862, 'btn_update', 'de', 'Aktualisieren'),
(863, 'btn_change', 'de', 'Ändern'),
(864, 'btn_retry', 'de', 'Erneut versuchen'),
(865, 'sidebar_top_rated', 'de', 'Top bewertet'),
(866, 'pill_top_rated', 'de', 'Top bewertet'),
(867, 'admin_categories_manage', 'de', 'Kategorien verwalten'),
(868, 'admin_column_action', 'de', 'Aktion'),
(869, 'admin_column_email', 'de', 'E-Mail'),
(870, 'admin_column_fullname', 'de', 'Vollständiger Name'),
(871, 'admin_column_name', 'de', 'Name'),
(872, 'admin_column_subject', 'de', 'Kategorie'),
(873, 'admin_column_username', 'de', 'Benutzername'),
(874, 'admin_comments_manage', 'de', 'Kommentare verwalten'),
(875, 'admin_files_manage', 'de', 'Dateien verwalten'),
(876, 'admin_title', 'de', 'Adminbereich'),
(877, 'admin_users_manage', 'de', 'Benutzer verwalten'),
(878, 'auth_already_have_account', 'de', 'Du hast schon ein Konto?'),
(879, 'auth_field_answer', 'de', 'Antwort'),
(880, 'auth_field_birthdate', 'de', 'Geburtsdatum'),
(881, 'auth_field_email', 'de', 'E-Mail'),
(882, 'auth_field_firstname', 'de', 'Vorname'),
(883, 'auth_field_gender', 'de', 'Geschlecht'),
(884, 'auth_field_lastname', 'de', 'Nachname'),
(885, 'auth_field_password', 'de', 'Passwort'),
(886, 'auth_field_password_again', 'de', 'Passwort erneut'),
(887, 'auth_field_password_again_colon', 'de', 'Passwort erneut:'),
(888, 'auth_field_security_answer', 'de', 'Antwort auf die Sicherheitsfrage:'),
(889, 'auth_field_security_question', 'de', 'Sicherheitsfrage:'),
(890, 'auth_field_username', 'de', 'Benutzername'),
(891, 'auth_field_username_colon', 'de', 'Benutzername:'),
(892, 'auth_gender_female', 'de', 'Weiblich'),
(893, 'auth_gender_male', 'de', 'Männlich'),
(894, 'auth_gender_other', 'de', 'Divers'),
(895, 'auth_login_title', 'de', 'Anmeldung'),
(896, 'auth_no_account_yet', 'de', 'Du hast noch kein Konto?'),
(897, 'auth_placeholder_start_typing', 'de', 'Fang an zu tippen...'),
(898, 'auth_register_title', 'de', 'Registrierung'),
(899, 'bday_message', 'de', 'Wir wünschen dir viel Erfolg und viele kreative Ideen!'),
(900, 'bday_title', 'de', 'Alles Gute zum Geburtstag,'),
(901, 'btn_add_friend', 'de', 'Als Freund markieren'),
(902, 'btn_delete_file', 'de', 'Datei löschen'),
(903, 'btn_login_to_rate', 'de', 'Zum Bewerten anmelden'),
(904, 'btn_send_rating', 'de', 'Bewertung senden'),
(905, 'comments_heading', 'de', 'Kommentare'),
(906, 'email_change_label_again', 'de', 'E-Mail-Adresse erneut:'),
(907, 'email_change_label_again_short', 'de', 'E-Mail erneut');
INSERT INTO `translations` (`id`, `t_key`, `lang_code`, `text`) VALUES
(908, 'email_change_label_new', 'de', 'Neue E-Mail-Adresse'),
(909, 'email_change_label_new_colon', 'de', 'Neue E-Mail-Adresse:'),
(910, 'email_change_page_title', 'de', 'E-Mail-Adresse ändern'),
(911, 'email_change_title', 'de', 'E-Mail-Änderung'),
(912, 'empty_no_comments', 'de', 'Es gibt noch keine Kommentare.'),
(913, 'empty_no_files_filter', 'de', 'Keine Dateien entsprechen den ausgewählten Filtern.'),
(914, 'empty_no_files_search', 'de', 'Keine Dateien entsprechen den angegebenen Filtern.'),
(915, 'empty_no_users_search', 'de', 'Es wurden keine Benutzer gefunden.'),
(916, 'error_wrong_password', 'de', 'Falsches Passwort!'),
(917, 'error_wrong_security_answer', 'de', 'Falsche Antwort auf die Sicherheitsfrage!'),
(918, 'forgot_heading', 'de', 'Passwort zurücksetzen'),
(919, 'forgot_new_password', 'de', 'Neues Passwort festlegen'),
(920, 'forgot_new_password_label', 'de', 'Neues Passwort:'),
(921, 'forgot_title', 'de', 'Passwort vergessen'),
(922, 'friend_status_friends', 'de', 'Ihr seid bereits befreundet!'),
(923, 'friend_status_sent_by_you', 'de', 'Du hast die Freundschaftsanfrage gesendet.'),
(924, 'friend_status_sent_to_you', 'de', 'Dieser Benutzer hat dir eine Freundschaftsanfrage gesendet.'),
(925, 'friends_already_handled', 'de', 'Diese Freundschaftsanfrage wurde bereits bearbeitet.'),
(926, 'friends_list_title', 'de', 'Deine Freunde'),
(927, 'friends_mark_as_friend', 'de', 'Als Freund markieren'),
(928, 'friends_no_friends_yet', 'de', 'Du hast noch keine Freunde.'),
(929, 'friends_relationship', 'de', 'Freundschaft'),
(930, 'friends_request_title', 'de', 'Freundschaftsanfrage'),
(931, 'index_title', 'de', 'Startseite'),
(932, 'label_favorite', 'de', 'Zu Favoriten hinzufügen'),
(933, 'label_files', 'de', 'Dateien'),
(934, 'label_mark', 'de', 'Markierung'),
(935, 'label_name', 'de', 'Name'),
(936, 'label_new_comment', 'de', 'Neuer Kommentar'),
(937, 'label_new_password_same_error', 'de', 'Dein neues Passwort darf nicht mit dem alten übereinstimmen.'),
(938, 'label_new_uploads', 'de', 'Neue Uploads'),
(939, 'label_rating', 'de', 'Bewertung'),
(940, 'label_text', 'de', 'Text'),
(941, 'label_uploaded_by', 'de', 'Hochgeladen von:'),
(942, 'label_users', 'de', 'Benutzer'),
(943, 'label_video', 'de', 'Video'),
(944, 'msg_comment_write_error', 'de', 'Beim Schreiben des Kommentars ist ein Fehler aufgetreten!'),
(945, 'msg_email_change_success', 'de', 'Deine neue E-Mail-Adresse wurde erfolgreich geändert!'),
(946, 'msg_file_uploaded', 'de', 'Die Datei wurde erfolgreich hochgeladen!'),
(947, 'msg_fill_subject_and_tags', 'de', 'Bitte gib das Fach und die Schlagwörter an!'),
(948, 'msg_friendid_missing', 'de', 'Fehler: fehlende Freund-ID.'),
(949, 'msg_generic_success_change', 'de', 'Änderung erfolgreich!'),
(950, 'msg_invalid_profile_id', 'de', 'Ungültige Profil-ID.'),
(951, 'msg_message_empty', 'de', 'Die Nachricht darf nicht leer sein!'),
(952, 'msg_message_send_error', 'de', 'Beim Senden der Nachricht ist ein Fehler aufgetreten.'),
(953, 'msg_no_permission_admin', 'de', 'Du hast keine Berechtigung, das Admin-Panel zu sehen.'),
(954, 'msg_note_missing_or_deleted', 'de', 'Die angeforderte Notiz existiert nicht oder wurde gelöscht.'),
(955, 'msg_note_not_found', 'de', 'Notiz nicht gefunden!'),
(956, 'msg_only_pdf_mp4_docx', 'de', 'Es können nur PDF-, MP4- oder DOCX-Dateien hochgeladen werden!'),
(957, 'msg_password_change_success', 'de', 'Dein Passwort wurde erfolgreich geändert!'),
(958, 'msg_profile_not_found', 'de', 'Das angeforderte Profil wurde nicht gefunden.'),
(959, 'nav_admin', 'de', 'Admin-Panel'),
(960, 'nav_new_note_plus', 'de', '+ Neue Notiz'),
(961, 'nav_notify', 'de', 'Benachrichtigungen'),
(962, 'nav_profil', 'de', 'Konto'),
(963, 'nav_search', 'de', 'Suche'),
(964, 'note_docx_download_hint', 'de', 'Dies ist eine .docx-Datei – lade sie herunter, um sie zu öffnen.'),
(965, 'notify_clear_all', 'de', 'Alle Benachrichtigungen löschen'),
(966, 'notify_commented_post', 'de', 'hat deinen Beitrag kommentiert!'),
(967, 'notify_friend_request_sent_to_you', 'de', 'Dieser Benutzer hat dir eine Freundschaftsanfrage gesendet.'),
(968, 'notify_is_your_friend', 'de', 'Ihr seid bereits befreundet!'),
(969, 'notify_marked_as_friend', 'de', 'hat dich als Freund markiert!'),
(970, 'notify_more', 'de', 'Alle →'),
(971, 'notify_you_sent_request', 'de', 'Du hast die Freundschaftsanfrage gesendet.'),
(972, 'pill_pdf', 'de', 'PDF'),
(973, 'pill_users', 'de', 'Benutzer'),
(974, 'pill_video', 'de', 'Video'),
(975, 'pill_word', 'de', 'Word'),
(976, 'profile_data', 'de', 'Profildaten'),
(977, 'profile_friendship', 'de', 'Freundschaft'),
(978, 'profile_fullname', 'de', 'Vollständiger Name'),
(979, 'profile_heading', 'de', 'Mein Konto'),
(980, 'profile_label_avatar', 'de', 'Profilbild'),
(981, 'profile_label_avatar_upload', 'de', 'Profilbild hochladen'),
(982, 'profile_label_birthdate', 'de', 'Geburtsdatum'),
(983, 'profile_label_firstname', 'de', 'Vorname'),
(984, 'profile_label_lastname', 'de', 'Nachname'),
(985, 'profile_label_name', 'de', 'Vollständiger Name'),
(986, 'profile_label_username', 'de', 'Benutzer'),
(987, 'profile_of', 'de', 'Profil'),
(988, 'profile_picture_alt', 'de', 'Profilbild'),
(989, 'profile_registration', 'de', 'Registrierung'),
(990, 'profile_uploaded_files', 'de', 'Hochgeladene Materialien'),
(991, 'rating_average', 'de', 'Durchschnittliche Bewertung:'),
(992, 'result_files', 'de', 'Dateien'),
(993, 'result_users', 'de', 'Benutzer'),
(994, 'search_btn', 'de', 'Suche'),
(995, 'search_btn_search', 'de', 'Suche'),
(996, 'search_filters_title', 'de', 'Suche'),
(997, 'search_label_files', 'de', 'Dateien'),
(998, 'search_label_keyword', 'de', 'Suchbegriff:'),
(999, 'search_label_users', 'de', 'Benutzer'),
(1000, 'search_scope_label', 'de', 'Suchbereich'),
(1001, 'search_sort_label', 'de', 'Sortieren nach'),
(1002, 'search_type_label', 'de', 'Dateityp'),
(1003, 'secq_birth_city', 'de', 'Wie heißt deine Geburtsstadt?'),
(1004, 'secq_fav_book', 'de', 'Was ist dein Lieblingsbuch?'),
(1005, 'secq_fav_food', 'de', 'Was ist dein Lieblingsessen?'),
(1006, 'secq_first_pet', 'de', 'Wie hieß dein erstes Haustier?'),
(1007, 'secq_mothers_maiden', 'de', 'Wie lautet der Mädchenname deiner Mutter?'),
(1008, 'suffix_ratings_paren', 'de', 'Bewertungen)'),
(1009, 'suffix_ratings_short', 'de', 'Bew.'),
(1010, 'sec_q_first_pet_name', 'hu', 'Mi volt az első háziállatod neve?'),
(1011, 'sec_q_first_pet_name', 'en', 'What was the name of your first pet?'),
(1012, 'sec_q_first_pet_name', 'de', 'Wie hieß dein erstes Haustier?'),
(1013, 'sec_q_favorite_food', 'hu', 'Mi a kedvenc ételed?'),
(1014, 'sec_q_favorite_food', 'en', 'What is your favourite food?'),
(1015, 'sec_q_favorite_food', 'de', 'Was ist dein Lieblingsessen?'),
(1016, 'error_all_fields_required', 'hu', 'Minden mező kötelező.'),
(1017, 'error_all_fields_required', 'en', 'All fields are required.'),
(1018, 'error_all_fields_required', 'de', 'Alle Felder sind erforderlich.'),
(1019, 'error_bad_email_format', 'hu', 'Hibás email formátum.'),
(1020, 'error_bad_email_format', 'en', 'Invalid email format.'),
(1021, 'error_bad_email_format', 'de', 'Ungültiges E-Mail-Format.'),
(1022, 'error_security_answer_required', 'hu', 'Biztonsági kérdésre választ kell adnod.'),
(1023, 'error_security_answer_required', 'en', 'Security question answer is required.'),
(1024, 'error_security_answer_required', 'de', 'Antwort auf die Sicherheitsfrage erforderlich.'),
(1025, 'msg_file_upload_error', 'hu', 'Hiba történt a fájl feltöltésekor.'),
(1026, 'msg_file_upload_error', 'en', 'An error occurred while uploading the file.'),
(1027, 'msg_file_upload_error', 'de', 'Beim Hochladen der Datei ist ein Fehler aufgetreten.'),
(1028, 'profile_title', 'hu', 'Profil'),
(1029, 'profile_title', 'en', 'Profile'),
(1030, 'profile_title', 'de', 'Profil'),
(1031, 'meta_description_home', 'hu', 'Iskolai jegyzetek megosztása, letöltése, feltöltése.'),
(1032, 'meta_description_home', 'en', 'Share, download and upload school notes.'),
(1033, 'meta_description_home', 'de', 'Schulnotizen teilen, herunterladen und hochladen.'),
(1034, 'meta_keywords_home', 'hu', 'iskola, jegyzet, megosztás, tanulás'),
(1035, 'meta_keywords_home', 'en', 'school, notes, sharing, studying'),
(1036, 'meta_keywords_home', 'de', 'Schule, Notizen, Teilen, Lernen'),
(1037, 'profile_of', 'hu', 'profilja'),
(1038, 'profile_of', 'en', 'profile'),
(1039, 'bday_title', 'hu', 'Boldog születésnapot,'),
(1040, 'bday_title', 'en', 'Happy birthday,'),
(1041, 'bday_title', 'de', 'Alles Gute zum Geburtstag,'),
(1042, 'bday_message', 'hu', 'Kívánunk sok boldogságot!'),
(1043, 'bday_message', 'en', 'We wish you many happy returns!'),
(1044, 'bday_message', 'de', 'Wir wünschen dir alles Gute!'),
(1045, 'btn_upload_profile_pic', 'hu', 'Feltöltés'),
(1046, 'btn_upload_profile_pic', 'en', 'Upload'),
(1047, 'btn_upload_profile_pic', 'de', 'Hochladen'),
(1048, 'nav_favorites', 'hu', 'Kedvenceim'),
(1049, 'nav_favorites', 'en', 'Favorites'),
(1050, 'nav_favorites', 'de', 'Favoriten'),
(1051, 'profile_friendship', 'hu', 'Barátság státusz'),
(1052, 'profile_friendship', 'en', 'Friendship status'),
(1053, 'profile_friendship', 'de', 'Freundschaftsstatus'),
(1054, 'friend_status_friends', 'hu', 'Barátok'),
(1055, 'friend_status_friends', 'en', 'Friends'),
(1056, 'friend_status_friends', 'de', 'Freunde'),
(1057, 'friend_status_sent_by_you', 'hu', 'Kérés elküldve (te)'),
(1058, 'friend_status_sent_by_you', 'en', 'Request sent (by you)'),
(1059, 'friend_status_sent_by_you', 'de', 'Anfrage gesendet (von dir)'),
(1060, 'friend_status_sent_to_you', 'hu', 'Neked küldtek kérelmet'),
(1061, 'friend_status_sent_to_you', 'en', 'Request sent (to you)'),
(1062, 'friend_status_sent_to_you', 'de', 'Anfrage gesendet (an dich)'),
(1063, 'btn_add_friend', 'hu', 'Barát hozzáadása'),
(1064, 'btn_add_friend', 'en', 'Add friend'),
(1065, 'btn_add_friend', 'de', 'Freund hinzufügen'),
(1066, 'profile_data', 'hu', 'Adatok'),
(1067, 'profile_data', 'en', 'Profile information'),
(1068, 'profile_data', 'de', 'Profilinformationen'),
(1069, 'profile_fullname', 'hu', 'Teljes név'),
(1070, 'profile_fullname', 'en', 'Full name'),
(1071, 'profile_fullname', 'de', 'Vollständiger Name'),
(1072, 'profile_username', 'hu', 'Felhasználónév'),
(1073, 'profile_username', 'en', 'Username'),
(1074, 'profile_username', 'de', 'Benutzername'),
(1075, 'profile_email', 'hu', 'Email'),
(1076, 'profile_email', 'en', 'Email'),
(1077, 'profile_email', 'de', 'E-Mail'),
(1078, 'profile_birthdate', 'hu', 'Születésnap'),
(1079, 'profile_birthdate', 'en', 'Birthday'),
(1080, 'profile_birthdate', 'de', 'Geburtstag'),
(1081, 'profile_registration', 'hu', 'Regisztráció dátuma'),
(1082, 'profile_registration', 'en', 'Registered'),
(1083, 'profile_registration', 'de', 'Registriert'),
(1084, 'btn_edit_profile_data', 'hu', 'Adatok szerkesztése'),
(1085, 'btn_edit_profile_data', 'en', 'Edit profile data'),
(1086, 'btn_edit_profile_data', 'de', 'Daten bearbeiten'),
(1087, 'profile_security_intro', 'hu', 'Mielőtt mentenénk az adataidat, állíts be egy biztonsági kérdést is.'),
(1088, 'profile_security_intro', 'en', 'Before saving your data, set a security question.'),
(1089, 'profile_security_intro', 'de', 'Bevor wir deine Daten speichern, richte bitte eine Sicherheitsfrage ein.'),
(1090, 'auth_field_security_question', 'hu', 'Biztonsági kérdés'),
(1091, 'auth_field_security_question', 'en', 'Security question'),
(1092, 'auth_field_security_question', 'de', 'Sicherheitsfrage'),
(1093, 'auth_field_answer', 'hu', 'Válasz'),
(1094, 'auth_field_answer', 'en', 'Answer'),
(1095, 'auth_field_answer', 'de', 'Antwort'),
(1096, 'placeholder_security_answer', 'hu', 'Írd ide a választ'),
(1097, 'placeholder_security_answer', 'en', 'Enter the answer'),
(1098, 'placeholder_security_answer', 'de', 'Antwort eingeben'),
(1099, 'profile_customization', 'hu', 'Profil testreszabása'),
(1100, 'profile_customization', 'en', 'Profile customization'),
(1101, 'profile_customization', 'de', 'Profilanpassung'),
(1102, 'profile_bio', 'hu', 'Bemutatkozás'),
(1103, 'profile_bio', 'en', 'About'),
(1104, 'profile_bio', 'de', 'Über mich'),
(1105, 'css_placeholder', 'hu', '/* Írd ide a CSS-t a profilodhoz */'),
(1106, 'css_placeholder', 'en', '/* Write the CSS for your profile here */'),
(1107, 'css_placeholder', 'de', '/* Schreibe hier das CSS für dein Profil */'),
(1108, 'msg_css_empty_reset', 'hu', 'A CSS mező üres — visszaállítva.'),
(1109, 'msg_css_empty_reset', 'en', 'CSS field is empty — reset.'),
(1110, 'msg_css_empty_reset', 'de', 'CSS-Feld ist leer — zurückgesetzt.'),
(1111, 'msg_css_approved_by_admin', 'hu', 'A CSS csak akkor lép életbe, ha egy admin jóváhagyja.'),
(1112, 'msg_css_approved_by_admin', 'en', 'The CSS takes effect only after an admin approves it.'),
(1113, 'msg_css_approved_by_admin', 'de', 'Das CSS tritt erst in Kraft, nachdem ein Admin es genehmigt hat.'),
(1114, 'css_approval_note', 'hu', 'A CSS csak akkor lép életbe, ha egy admin jóváhagyja.'),
(1115, 'css_approval_note', 'en', 'The CSS only takes effect after admin approval.'),
(1116, 'css_approval_note', 'de', 'Das CSS wirkt nur nach Admin-Freigabe.'),
(1117, 'profile_theme', 'hu', 'Téma'),
(1118, 'profile_theme', 'en', 'Theme'),
(1119, 'profile_theme', 'de', 'Thema'),
(1120, 'profile_theme_default', 'hu', 'Alap (Sötét)'),
(1121, 'profile_theme_default', 'en', 'Default (Dark)'),
(1122, 'profile_theme_default', 'de', 'Standard (Dunkel)'),
(1123, 'profile_theme_pastel', 'hu', 'Pastel'),
(1124, 'profile_theme_pastel', 'en', 'Pastel'),
(1125, 'profile_theme_pastel', 'de', 'Pastellfarbe'),
(1126, 'profile_theme_forest', 'hu', 'Forest'),
(1127, 'profile_theme_forest', 'en', 'Forest'),
(1128, 'profile_theme_forest', 'de', 'Wald'),
(1129, 'profile_theme_light', 'hu', 'Világos'),
(1130, 'profile_theme_light', 'en', 'Light'),
(1131, 'profile_theme_light', 'de', 'Hell'),
(1132, 'btn_save', 'hu', 'Mentés'),
(1133, 'btn_save', 'en', 'Save'),
(1134, 'btn_save', 'de', 'Speichern'),
(1135, 'profile_custom_css_request', 'hu', 'Egyedi CSS kérés'),
(1136, 'profile_custom_css_request', 'en', 'Custom CSS request'),
(1137, 'profile_custom_css_request', 'de', 'Benutzerdefinierte CSS-Anfrage'),
(1138, 'profile_last_request_status', 'hu', 'Utolsó kérésed státusza:'),
(1139, 'profile_last_request_status', 'en', 'Status of your last request:'),
(1140, 'profile_last_request_status', 'de', 'Status deiner letzten Anfrage:'),
(1141, 'profile_custom_css_not_requested', 'hu', 'Még nem adtál le egyedi CSS kérést.'),
(1142, 'profile_custom_css_not_requested', 'en', 'You have not submitted a custom CSS request yet.'),
(1143, 'profile_custom_css_not_requested', 'de', 'Du hast noch keine benutzerdefinierte CSS-Anfrage gestellt.'),
(1144, 'profile_css_tutorial_summary', 'hu', 'Segítség: hogyan írj saját CSS-t a profilodhoz?'),
(1145, 'profile_css_tutorial_summary', 'en', 'Help: how to write custom CSS for your profile?'),
(1146, 'profile_css_tutorial_summary', 'de', 'Hilfe: Wie schreibt man eigenes CSS für das Profil?'),
(1147, 'profile_css_tutorial_intro', 'hu', 'Az itt megadott CSS csak a profilodra fog hatni, miután egy admin jóváhagyta. Nyugodtan használj olyan szelektorokat, mint body, .main, .card, .profile-name, .profile-username, stb.'),
(1148, 'profile_css_tutorial_intro', 'en', 'The CSS entered here will only affect your profile after an admin approves it. You may use selectors like body, .main, .card, .profile-name, .profile-username, etc.'),
(1149, 'profile_css_tutorial_intro', 'de', 'Das hier eingegebene CSS wirkt erst auf dein Profil, nachdem ein Admin es freigegeben hat. Du kannst Selektoren wie body, .main, .card, .profile-name, .profile-username usw. verwenden.'),
(1150, 'profile_css_tutorial_example', 'hu', 'Példa: sötét, \"neonos\" profil téma — kiindulási alap:'),
(1151, 'profile_css_tutorial_example', 'en', 'Example: dark, \"neon\" profile theme — you can use this as a starting point:'),
(1152, 'profile_css_tutorial_example', 'de', 'Beispiel: dunkles, \"neon\"-Profilthema — du kannst es als Ausgangspunkt verwenden:'),
(1153, 'tip_profile_custom_css', 'hu', 'Tipp: ha csak a profilod egy részét akarod módosítani (pl. a gombokat), elég azokat a classokat piszkálni, amik ide tartoznak, pl. .btn-cta, .btn-ghost, .profile-info-item, .profile-badges, .badge-pill.'),
(1154, 'tip_profile_custom_css', 'en', 'Tip: if you only want to change part of your profile (e.g., the buttons), modify the relevant classes such as .btn-cta, .btn-ghost, .profile-info-item, .profile-badges, .badge-pill.'),
(1155, 'tip_profile_custom_css', 'de', 'Tipp: Wenn du nur einen Teil deines Profils ändern möchtest (z. B. die Buttons), bearbeite die entsprechenden Klassen wie .btn-cta, .btn-ghost, .profile-info-item, .profile-badges, .badge-pill.'),
(1156, 'profile_css_label', 'hu', 'CSS kód'),
(1157, 'profile_css_label', 'en', 'CSS code'),
(1158, 'profile_css_label', 'de', 'CSS-Code'),
(1159, 'profile_custom_css_submit', 'hu', 'Egyedi CSS elküldése'),
(1160, 'profile_custom_css_submit', 'en', 'Submit custom CSS'),
(1161, 'profile_custom_css_submit', 'de', 'Benutzerdefiniertes CSS senden'),
(1162, 'profile_custom_css_reset_btn', 'hu', 'Egyedi CSS visszaállítása'),
(1163, 'profile_custom_css_reset_btn', 'en', 'Reset custom CSS'),
(1164, 'profile_custom_css_reset_btn', 'de', 'Benutzerdefiniertes CSS zurücksetzen'),
(1165, 'profile_uploaded_files', 'hu', 'Feltöltött anyagok'),
(1166, 'profile_uploaded_files', 'en', 'Uploaded files'),
(1167, 'profile_uploaded_files', 'de', 'Hochgeladene Dateien'),
(1168, 'label_unknown_user', 'hu', 'ismeretlen'),
(1169, 'label_unknown_user', 'en', 'unknown'),
(1170, 'label_unknown_user', 'de', 'unbekannt'),
(1171, 'btn_details', 'hu', 'Részletek'),
(1172, 'btn_details', 'en', 'Details'),
(1173, 'btn_details', 'de', 'Details'),
(1174, 'btn_download', 'hu', 'Letöltés'),
(1175, 'btn_download', 'en', 'Download'),
(1176, 'btn_download', 'de', 'Herunterladen'),
(1177, 'label_subject', 'hu', 'Tantárgy:'),
(1178, 'label_subject', 'en', 'Subject:'),
(1179, 'label_subject', 'de', 'Fach:'),
(1180, 'docx_warning', 'hu', 'A DOCX fájl megtekintése nem támogatott, töltsd le a fájlt.'),
(1181, 'docx_warning', 'en', 'Viewing DOCX files is not supported; please download the file.'),
(1182, 'docx_warning', 'de', 'Die Anzeige von DOCX-Dateien wird nicht unterstützt; bitte lade die Datei herunter.'),
(1183, 'label_tags', 'hu', 'Címkék:'),
(1184, 'label_tags', 'en', 'Tags:'),
(1185, 'label_tags', 'de', 'Tags:'),
(1186, 'btn_delete_file', 'hu', 'Törlés'),
(1187, 'btn_delete_file', 'en', 'Delete'),
(1188, 'btn_delete_file', 'de', 'Löschen'),
(1189, 'empty_no_files', 'hu', 'Nincs feltöltött fájl.'),
(1190, 'empty_no_files', 'en', 'No files uploaded.'),
(1191, 'empty_no_files', 'de', 'Keine Dateien hochgeladen.'),
(1192, 'msg_profile_update_success', 'hu', 'Profil frissítve ✅'),
(1193, 'msg_profile_update_success', 'en', 'Profile updated ✅'),
(1194, 'msg_profile_update_success', 'de', 'Profil aktualisiert ✅'),
(1195, 'btn_cancel', 'hu', 'Mégse'),
(1196, 'btn_cancel', 'en', 'Cancel'),
(1197, 'search_page_title', 'hu', 'Keresés'),
(1198, 'search_page_title', 'en', 'Search'),
(1199, 'search_page_title', 'de', 'Suche'),
(1200, 'search_input_label', 'hu', 'Keresés'),
(1201, 'search_input_label', 'en', 'Search'),
(1202, 'search_input_label', 'de', 'Suche'),
(1203, 'search_input_placeholder', 'hu', 'Írd be mit keresel...'),
(1204, 'search_input_placeholder', 'en', 'Type what you are looking for...'),
(1205, 'search_input_placeholder', 'de', 'Gib ein, wonach du suchst...'),
(1206, 'search_scope_all_everywhere', 'hu', 'Mindenhol'),
(1207, 'search_scope_all_everywhere', 'en', 'Everywhere'),
(1208, 'search_scope_all_everywhere', 'de', 'Überall'),
(1209, 'search_scope_files_only', 'hu', 'Csak fájlok'),
(1210, 'search_scope_files_only', 'en', 'Files only'),
(1211, 'search_scope_files_only', 'de', 'Nur Dateien'),
(1212, 'search_scope_users_only', 'hu', 'Csak felhasználók'),
(1213, 'search_scope_users_only', 'en', 'Users only'),
(1214, 'search_scope_users_only', 'de', 'Nur Benutzer'),
(1215, 'search_type_all_types', 'hu', 'Összes típus'),
(1216, 'search_type_all_types', 'en', 'All types'),
(1217, 'search_type_all_types', 'de', 'Alle Typen'),
(1218, 'search_type_video_mp4', 'hu', 'Videó (MP4)'),
(1219, 'search_type_video_mp4', 'en', 'Video (MP4)'),
(1220, 'search_type_video_mp4', 'de', 'Video (MP4)'),
(1221, 'search_type_word_docx', 'hu', 'Word (DOCX)'),
(1222, 'search_type_word_docx', 'en', 'Word (DOCX)'),
(1223, 'search_type_word_docx', 'de', 'Word (DOCX)'),
(1224, 'search_level_label', 'hu', 'Szint'),
(1225, 'search_level_label', 'en', 'Level'),
(1226, 'search_level_label', 'de', 'Stufe'),
(1227, 'search_level_all', 'hu', 'Összes'),
(1228, 'search_level_all', 'en', 'All'),
(1229, 'search_level_all', 'de', 'Alle'),
(1230, 'search_level_none', 'hu', 'Nincs megadva'),
(1231, 'search_level_none', 'en', 'Not specified'),
(1232, 'search_level_none', 'de', 'Nicht angegeben'),
(1233, 'search_level_group_hs', 'hu', 'Technikum (9-13)'),
(1234, 'search_level_group_hs', 'en', 'Technical school (9-13)'),
(1235, 'search_level_group_hs', 'de', 'Technikum (9-13)'),
(1236, 'search_level_group_uni', 'hu', 'Egyetem (1-7. félév)'),
(1237, 'search_level_group_uni', 'en', 'University (semester 1-7)'),
(1238, 'search_level_group_uni', 'de', 'Universität (1.-7. Semester)'),
(1239, 'search_hs_year_fmt', 'hu', '%d. évfolyam'),
(1240, 'search_hs_year_fmt', 'en', 'Year %d'),
(1241, 'search_hs_year_fmt', 'de', '%d. Jahrgang'),
(1242, 'search_uni_semester_fmt', 'hu', '%d. félév'),
(1243, 'search_uni_semester_fmt', 'en', 'Semester %d'),
(1244, 'search_uni_semester_fmt', 'de', '%d. Semester'),
(1245, 'search_tag_label', 'hu', 'Tag'),
(1246, 'search_tag_label', 'en', 'Tag'),
(1247, 'search_tag_label', 'de', 'Tag'),
(1248, 'search_tag_placeholder', 'hu', 'pl. Tankönyv'),
(1249, 'search_tag_placeholder', 'en', 'e.g. Textbook'),
(1250, 'search_tag_placeholder', 'de', 'z. B. Lehrbuch'),
(1251, 'search_mode_label', 'hu', 'Keresési mód'),
(1252, 'search_mode_label', 'en', 'Search mode'),
(1253, 'search_mode_label', 'de', 'Suchmodus'),
(1254, 'search_mode_all_words', 'hu', 'Minden szó (AND)'),
(1255, 'search_mode_all_words', 'en', 'All words (AND)'),
(1256, 'search_mode_all_words', 'de', 'Alle Wörter (UND)'),
(1257, 'search_mode_any_word', 'hu', 'Bármely szó (OR)'),
(1258, 'search_mode_any_word', 'en', 'Any word (OR)'),
(1259, 'search_mode_any_word', 'de', 'Beliebiges Wort (ODER)'),
(1260, 'search_sort_relevance', 'hu', 'Relevancia'),
(1261, 'search_sort_relevance', 'en', 'Relevance'),
(1262, 'search_sort_relevance', 'de', 'Relevanz'),
(1263, 'search_sort_newest', 'hu', 'Legújabb elöl'),
(1264, 'search_sort_newest', 'en', 'Newest first'),
(1265, 'search_sort_newest', 'de', 'Neueste zuerst'),
(1266, 'search_sort_oldest', 'hu', 'Legrégebbi elöl'),
(1267, 'search_sort_oldest', 'en', 'Oldest first'),
(1268, 'search_sort_oldest', 'de', 'Älteste zuerst'),
(1269, 'search_sort_downloads', 'hu', 'Legtöbb letöltés'),
(1270, 'search_sort_downloads', 'en', 'Most downloads'),
(1271, 'search_sort_downloads', 'de', 'Meiste Downloads'),
(1272, 'search_sort_rating', 'hu', 'Legjobb értékelés'),
(1273, 'search_sort_rating', 'en', 'Best rated'),
(1274, 'search_sort_rating', 'de', 'Beste Bewertung'),
(1275, 'search_download_counter_missing', 'hu', '(Nincs letöltésszámláló oszlop a files táblában)'),
(1276, 'search_download_counter_missing', 'en', '(No download counter column in the files table)'),
(1277, 'search_download_counter_missing', 'de', '(Keine Download-Zähler-Spalte in der files-Tabelle vorhanden)'),
(1278, 'search_facet_level', 'hu', 'Szint:'),
(1279, 'search_facet_level', 'en', 'Level:'),
(1280, 'search_facet_level', 'de', 'Stufe:'),
(1281, 'search_facet_all_fmt', 'hu', 'Összes (%d)'),
(1282, 'search_facet_all_fmt', 'en', 'All (%d)'),
(1283, 'search_facet_all_fmt', 'de', 'Alle (%d)'),
(1284, 'search_facet_none_fmt', 'hu', 'Nincs megadva (%d)'),
(1285, 'search_facet_none_fmt', 'en', 'Not specified (%d)'),
(1286, 'search_facet_none_fmt', 'de', 'Nicht angegeben (%d)'),
(1287, 'search_results_showing_fmt', 'hu', 'Mutatom: <strong>%d-%d</strong> / %d'),
(1288, 'search_results_showing_fmt', 'en', 'Showing: <strong>%d-%d</strong> / %d'),
(1289, 'search_results_showing_fmt', 'de', 'Angezeigt: <strong>%d-%d</strong> / %d'),
(1290, 'search_edu_none', 'hu', 'Nincs megadva'),
(1291, 'search_edu_none', 'en', 'Not specified'),
(1292, 'search_edu_none', 'de', 'Nicht angegeben'),
(1293, 'search_edu_hs_fmt', 'hu', 'Technikum - %d. évfolyam'),
(1294, 'search_edu_hs_fmt', 'en', 'Technical school - Year %d'),
(1295, 'search_edu_hs_fmt', 'de', 'Technikum - %d. Jahrgang'),
(1296, 'search_edu_uni_fmt', 'hu', 'Egyetem - %d. félév'),
(1297, 'search_edu_uni_fmt', 'en', 'University - Semester %d'),
(1298, 'search_edu_uni_fmt', 'de', 'Universität - %d. Semester'),
(1299, 'search_score_fmt', 'hu', 'Pont: %d'),
(1300, 'search_score_fmt', 'en', 'Score: %d'),
(1301, 'search_score_fmt', 'de', 'Punktzahl: %d'),
(1302, 'search_more', 'hu', 'Továbbiak →'),
(1303, 'search_more', 'en', 'Load more →'),
(1304, 'search_more', 'de', 'Mehr laden →'),
(1305, 'search_back_to_paging', 'hu', 'Vissza lapozáshoz'),
(1306, 'search_back_to_paging', 'en', 'Back to paging'),
(1307, 'search_back_to_paging', 'de', 'Zur Seitennavigation zurück'),
(1308, 'search_prev', 'hu', '← Előző'),
(1309, 'search_prev', 'en', '← Previous'),
(1310, 'search_prev', 'de', '← Zurück'),
(1311, 'search_next', 'hu', 'Következő →'),
(1312, 'search_next', 'en', 'Next →'),
(1313, 'search_next', 'de', 'Weiter →'),
(1314, 'search_page_fmt', 'hu', 'Oldal %d / %d'),
(1315, 'search_page_fmt', 'en', 'Page %d / %d'),
(1316, 'search_page_fmt', 'de', 'Seite %d / %d'),
(1317, 'search_no_results_title', 'hu', 'Nincs találat'),
(1318, 'search_no_results_title', 'en', 'No results'),
(1319, 'search_no_results_title', 'de', 'Keine Treffer'),
(1320, 'search_no_results_accent_tip_fmt', 'hu', 'Tipp: próbáld ékezetek nélkül: %s'),
(1321, 'search_no_results_accent_tip_fmt', 'en', 'Tip: try without accents: %s'),
(1322, 'search_no_results_accent_tip_fmt', 'de', 'Tipp: versuche es ohne Akzente: %s'),
(1323, 'search_did_you_mean', 'hu', 'Erre gondoltál?'),
(1324, 'search_did_you_mean', 'en', 'Did you mean?'),
(1325, 'search_did_you_mean', 'de', 'Meintest du?'),
(1326, 'search_no_results_try_shorter', 'hu', 'Próbáld meg rövidebb kulcsszóval.'),
(1327, 'search_no_results_try_shorter', 'en', 'Try a shorter keyword.'),
(1328, 'search_no_results_try_shorter', 'de', 'Versuche ein kürzeres Stichwort.'),
(1329, 'search_no_results_clear_filters', 'hu', 'Töröld a szűrőket (szint / tag / típus), és nézd meg úgy.'),
(1330, 'search_no_results_clear_filters', 'en', 'Clear the filters (level / tag / type) and try again.'),
(1331, 'search_no_results_clear_filters', 'de', 'Entferne die Filter (Stufe / Tag / Typ) und versuche es erneut.'),
(1332, 'search_no_results_browse_empty', 'hu', 'Ha csak böngésznél, hagyd üresen a keresést.'),
(1333, 'search_no_results_browse_empty', 'en', 'If you just want to browse, leave the search empty.'),
(1334, 'search_no_results_browse_empty', 'de', 'Wenn du nur stöbern möchtest, lasse die Suche leer.'),
(1335, 'result_users', 'de', 'Benutzer'),
(1336, 'result_files', 'de', 'Dateien'),
(1337, 'open_profile', 'de', 'Profil öffnen'),
(1338, 'btn_details', 'de', 'Details'),
(1339, 'label_uploaded_by', 'de', 'Hochgeladen von:'),
(1340, 'label_scope', 'de', 'Wo soll gesucht werden?'),
(1341, 'label_type', 'de', 'Dateityp'),
(1342, 'label_sort', 'de', 'Sortierung'),
(1343, 'search_placeholder', 'de', 'Suche…'),
(1344, 'upload_page_title', 'hu', 'Új jegyzet feltöltése'),
(1345, 'upload_page_title', 'en', 'Upload new note'),
(1346, 'upload_page_title', 'de', 'Neue Notiz hochladen'),
(1347, 'upload_label_name', 'hu', 'Anyag neve:'),
(1348, 'upload_label_name', 'en', 'Title:'),
(1349, 'upload_label_name', 'de', 'Titel:'),
(1350, 'upload_placeholder_name', 'hu', 'pl. Fizika ZH anyag'),
(1351, 'upload_placeholder_name', 'en', 'e.g. Physics test material'),
(1352, 'upload_placeholder_name', 'de', 'z. B. Physik-Testmaterial'),
(1353, 'upload_label_description', 'hu', 'Leírás:'),
(1354, 'upload_label_description', 'en', 'Description:'),
(1355, 'upload_label_description', 'de', 'Beschreibung:'),
(1356, 'upload_placeholder_description', 'hu', 'Rövid leírás az anyagról...'),
(1357, 'upload_placeholder_description', 'en', 'Short description of the material...'),
(1358, 'upload_placeholder_description', 'de', 'Kurze Beschreibung des Materials...'),
(1359, 'upload_label_subject', 'hu', 'Tárgy:'),
(1360, 'upload_label_subject', 'en', 'Subject:'),
(1361, 'upload_label_subject', 'de', 'Fach:'),
(1362, 'upload_placeholder_subject', 'hu', 'pl. fizika, történelem'),
(1363, 'upload_placeholder_subject', 'en', 'e.g. physics, history'),
(1364, 'upload_placeholder_subject', 'de', 'z. B. Physik, Geschichte'),
(1365, 'upload_private_note', 'hu', 'Privát jegyzet (csak te látod) – prémium'),
(1366, 'upload_private_note', 'en', 'Private note (only visible to you) – premium'),
(1367, 'upload_private_note', 'de', 'Private Notiz (nur für dich sichtbar) – Premium'),
(1368, 'upload_private_premium_required', 'hu', 'A privát feltöltéshez prémium szükséges.'),
(1369, 'upload_private_premium_required', 'en', 'Premium is required for private uploads.'),
(1370, 'upload_private_premium_required', 'de', 'Für private Uploads ist Premium erforderlich.'),
(1371, 'upload_label_level', 'hu', 'Évfolyam / félév:'),
(1372, 'upload_label_level', 'en', 'Year / semester:'),
(1373, 'upload_label_level', 'de', 'Jahrgang / Semester:'),
(1374, 'upload_public_visible', 'hu', 'Nyilvános (megjelenjen a keresőben)'),
(1375, 'upload_public_visible', 'en', 'Public (visible in search)'),
(1376, 'upload_public_visible', 'de', 'Öffentlich (in der Suche sichtbar)'),
(1377, 'upload_label_tags', 'hu', 'Címkék:'),
(1378, 'upload_label_tags', 'en', 'Tags:'),
(1379, 'upload_label_tags', 'de', 'Tags:'),
(1380, 'upload_placeholder_tags', 'hu', 'Címkék.'),
(1381, 'upload_placeholder_tags', 'en', 'Tags.'),
(1382, 'upload_placeholder_tags', 'de', 'Tags.'),
(1383, 'upload_label_content_type', 'hu', 'Feltöltés típusa:'),
(1384, 'upload_label_content_type', 'en', 'Upload type:'),
(1385, 'upload_label_content_type', 'de', 'Upload-Typ:'),
(1386, 'upload_mode_file', 'hu', 'Fájl feltöltése'),
(1387, 'upload_mode_file', 'en', 'Upload file'),
(1388, 'upload_mode_file', 'de', 'Datei hochladen'),
(1389, 'upload_mode_markdown', 'hu', 'Markdown jegyzet írása'),
(1390, 'upload_mode_markdown', 'en', 'Write markdown note'),
(1391, 'upload_mode_markdown', 'de', 'Markdown-Notiz schreiben'),
(1392, 'upload_mode_link', 'hu', 'Videó / Link megosztása'),
(1393, 'upload_mode_link', 'en', 'Share video / link'),
(1394, 'upload_mode_link', 'de', 'Video / Link teilen'),
(1395, 'upload_label_file', 'hu', 'Fájl kiválasztása:'),
(1396, 'upload_label_file', 'en', 'Choose file:'),
(1397, 'upload_label_file', 'de', 'Datei auswählen:'),
(1398, 'upload_allowed_types_fmt', 'hu', 'Engedélyezett: PDF, MP4, DOCX • Max: %s'),
(1399, 'upload_allowed_types_fmt', 'en', 'Allowed: PDF, MP4, DOCX • Max: %s'),
(1400, 'upload_allowed_types_fmt', 'de', 'Erlaubt: PDF, MP4, DOCX • Max: %s'),
(1401, 'upload_label_markdown', 'hu', 'Markdown jegyzet:'),
(1402, 'upload_label_markdown', 'en', 'Markdown note:'),
(1403, 'upload_label_markdown', 'de', 'Markdown-Notiz:'),
(1404, 'upload_placeholder_markdown', 'hu', '# Cím\r\n\r\n- Lista\r\n- **Félkövér**\r\n- `kód`\r\n\r\nIde írd a jegyzetet...'),
(1405, 'upload_placeholder_markdown', 'en', '# Title\r\n\r\n- List\r\n- **Bold**\r\n- `code`\r\n\r\nWrite your note here...'),
(1406, 'upload_placeholder_markdown', 'de', '# Titel\r\n\r\n- Liste\r\n- **Fett**\r\n- `Code`\r\n\r\nSchreibe hier deine Notiz...'),
(1407, 'upload_markdown_tip', 'hu', 'Tipp: Markdown szintaxis (#, **félkövér**, - lista, `kód` stb.). Max 2MB.'),
(1408, 'upload_markdown_tip', 'en', 'Tip: Markdown syntax (#, **bold**, - list, `code`, etc.). Max 2MB.'),
(1409, 'upload_markdown_tip', 'de', 'Tipp: Markdown-Syntax (#, **fett**, - Liste, `Code` usw.). Max. 2MB.'),
(1410, 'upload_label_video_link', 'hu', 'Videó link:'),
(1411, 'upload_label_video_link', 'en', 'Video link:'),
(1412, 'upload_label_video_link', 'de', 'Videolink:'),
(1413, 'upload_placeholder_video_link', 'hu', 'https://www.youtube.com/watch?v=... vagy más videó link'),
(1414, 'upload_placeholder_video_link', 'en', 'https://www.youtube.com/watch?v=... or another video link'),
(1415, 'upload_placeholder_video_link', 'de', 'https://www.youtube.com/watch?v=... oder ein anderer Videolink'),
(1416, 'upload_video_tip', 'hu', 'Tipp: YouTube, Vimeo, TikTok, Drive megosztás - bármi jöhet, ami URL.'),
(1417, 'upload_video_tip', 'en', 'Tip: YouTube, Vimeo, TikTok, Drive share - any valid URL works.'),
(1418, 'upload_video_tip', 'de', 'Tipp: YouTube, Vimeo, TikTok, Drive-Freigabe - jede gültige URL funktioniert.'),
(1419, 'upload_submit', 'hu', 'Feltöltés'),
(1420, 'upload_submit', 'en', 'Upload'),
(1421, 'upload_submit', 'de', 'Hochladen'),
(1422, 'upload_ok', 'hu', 'A feltöltés sikeres volt.'),
(1423, 'upload_ok', 'en', 'Upload was successful.'),
(1424, 'upload_ok', 'de', 'Der Upload war erfolgreich.'),
(1425, 'upload_err_missing_migration', 'hu', 'Hiányzik az adatbázis frissítés (content_type / external_url).'),
(1426, 'upload_err_missing_migration', 'en', 'Database update is missing (content_type / external_url).'),
(1427, 'upload_err_missing_migration', 'de', 'Datenbank-Update fehlt (content_type / external_url).'),
(1428, 'upload_err_link_empty', 'hu', 'A videó/link mező nem lehet üres.'),
(1429, 'upload_err_link_empty', 'en', 'The video/link field cannot be empty.'),
(1430, 'upload_err_link_empty', 'de', 'Das Video-/Link-Feld darf nicht leer sein.'),
(1431, 'upload_err_invalid_link', 'hu', 'Érvénytelen link.'),
(1432, 'upload_err_invalid_link', 'en', 'Invalid link.'),
(1433, 'upload_err_invalid_link', 'de', 'Ungültiger Link.'),
(1434, 'upload_err_only_http', 'hu', 'Csak http/https link engedélyezett.'),
(1435, 'upload_err_only_http', 'en', 'Only http/https links are allowed.'),
(1436, 'upload_err_only_http', 'de', 'Nur http/https-Links sind erlaubt.'),
(1437, 'upload_err_file_upload', 'hu', 'Hiba a fájl feltöltésekor.'),
(1438, 'upload_err_file_upload', 'en', 'Error while uploading the file.'),
(1439, 'upload_err_file_upload', 'de', 'Fehler beim Hochladen der Datei.'),
(1440, 'upload_err_empty_file', 'hu', 'Üres fájl vagy ismeretlen fájlméret.'),
(1441, 'upload_err_empty_file', 'en', 'Empty file or unknown file size.'),
(1442, 'upload_err_empty_file', 'de', 'Leere Datei oder unbekannte Dateigröße.'),
(1443, 'upload_err_file_too_large_fmt', 'hu', 'Túl nagy a fájl. Maximum %d MB-os fájlt tölthetsz fel.'),
(1444, 'upload_err_file_too_large_fmt', 'en', 'The file is too large. Maximum allowed size is %d MB.'),
(1445, 'upload_err_file_too_large_fmt', 'de', 'Die Datei ist zu groß. Maximale Dateigröße: %d MB.'),
(1446, 'upload_err_quota_fmt', 'hu', 'Nincs elég hely a felhasználói kvótádban. Max %d MB-ot használhatsz. Jelenleg ~%d MB-ot használsz, a fájl mérete ~%d MB.'),
(1447, 'upload_err_quota_fmt', 'en', 'There is not enough space in your user quota. You may use up to %d MB. Currently you use ~%d MB, and the file size is ~%d MB.'),
(1448, 'upload_err_quota_fmt', 'de', 'Nicht genug Speicher in deinem Benutzerkontingent. Du darfst maximal %d MB verwenden. Aktuell nutzt du ~%d MB, die Datei ist ~%d MB groß.'),
(1449, 'footer_group_info', 'hu', 'Információ'),
(1450, 'footer_group_info', 'en', 'Information'),
(1451, 'footer_group_info', 'de', 'Information'),
(1452, 'footer_link_about', 'hu', 'Rólunk'),
(1453, 'footer_link_about', 'en', 'About us'),
(1454, 'footer_link_about', 'de', 'Über uns'),
(1455, 'footer_link_team', 'hu', 'Csapattagjaink'),
(1456, 'footer_link_team', 'en', 'Our team'),
(1457, 'footer_link_team', 'de', 'Unser Team'),
(1458, 'footer_link_partners', 'hu', 'Partnereink'),
(1459, 'footer_link_partners', 'en', 'Our partners'),
(1460, 'footer_link_partners', 'de', 'Unsere Partner'),
(1461, 'footer_link_faq', 'hu', 'GYIK'),
(1462, 'footer_link_faq', 'en', 'FAQ'),
(1463, 'footer_link_faq', 'de', 'FAQ'),
(1464, 'footer_link_rules', 'hu', 'Szabályzat'),
(1465, 'footer_link_rules', 'en', 'Rules'),
(1466, 'footer_link_rules', 'de', 'Regeln'),
(1467, 'footer_link_report', 'hu', 'Hibajelentés'),
(1468, 'footer_link_report', 'en', 'Bug report'),
(1469, 'footer_link_report', 'de', 'Fehlermeldung'),
(1470, 'footer_group_legal', 'hu', 'Jogi'),
(1471, 'footer_group_legal', 'en', 'Legal'),
(1472, 'footer_group_legal', 'de', 'Rechtliches'),
(1473, 'footer_link_privacy', 'hu', 'Adatvédelem'),
(1474, 'footer_link_privacy', 'en', 'Privacy policy'),
(1475, 'footer_link_privacy', 'de', 'Datenschutz'),
(1476, 'footer_link_terms', 'hu', 'ÁSZF'),
(1477, 'footer_link_terms', 'en', 'Terms of service'),
(1478, 'footer_link_terms', 'de', 'Nutzungsbedingungen'),
(1479, 'footer_link_contact', 'hu', 'Kapcsolat'),
(1480, 'footer_link_contact', 'en', 'Contact'),
(1481, 'footer_link_contact', 'de', 'Kontakt'),
(1482, 'footer_group_community', 'hu', 'Közösség'),
(1483, 'footer_group_community', 'en', 'Community'),
(1484, 'footer_group_community', 'de', 'Community'),
(1485, 'footer_built_with', 'hu', 'Készítette ❤️ a NoteForge Development'),
(1486, 'footer_built_with', 'en', 'Built with ❤️ by NoteForge Development'),
(1487, 'footer_built_with', 'de', 'Erstellt mit ❤️ von NoteForge Development'),
(1488, 'nav_groups', 'hu', 'Csoportok'),
(1489, 'nav_groups', 'en', 'Groups'),
(1490, 'nav_groups', 'de', 'Gruppen'),
(1491, 'nav_exams', 'hu', 'Vizsgák'),
(1492, 'nav_exams', 'en', 'Exams'),
(1493, 'nav_exams', 'de', 'Prüfungen'),
(1494, 'nav_favorites', 'hu', 'Kedvencek'),
(1495, 'nav_favorites', 'en', 'Favorites'),
(1496, 'nav_favorites', 'de', 'Favoriten'),
(1497, 'nav_profil', 'de', 'Konto'),
(1498, 'nav_search', 'de', 'Suche'),
(1499, 'nav_notify', 'de', 'Benachrichtigungen'),
(1500, 'search_page_title', 'hu', 'Keresés'),
(1501, 'search_page_title', 'en', 'Search'),
(1502, 'search_page_title', 'de', 'Suche'),
(1503, 'search_input_label', 'hu', 'Keresés'),
(1504, 'search_input_label', 'en', 'Search'),
(1505, 'search_input_label', 'de', 'Suche'),
(1506, 'search_input_placeholder', 'hu', 'Írd be mit keresel...'),
(1507, 'search_input_placeholder', 'en', 'Type what you are looking for...'),
(1508, 'search_input_placeholder', 'de', 'Wonach suchst du?'),
(1509, 'label_scope', 'hu', 'Hol keressünk?'),
(1510, 'label_scope', 'en', 'Search in'),
(1511, 'label_scope', 'de', 'Suchen in'),
(1512, 'search_scope_all_everywhere', 'hu', 'Mindenhol'),
(1513, 'search_scope_all_everywhere', 'en', 'Everywhere'),
(1514, 'search_scope_all_everywhere', 'de', 'Überall'),
(1515, 'search_scope_files_only', 'hu', 'Csak fájlok'),
(1516, 'search_scope_files_only', 'en', 'Files only'),
(1517, 'search_scope_files_only', 'de', 'Nur Dateien'),
(1518, 'search_scope_users_only', 'hu', 'Csak felhasználók'),
(1519, 'search_scope_users_only', 'en', 'Users only'),
(1520, 'search_scope_users_only', 'de', 'Nur Benutzer'),
(1521, 'label_type', 'hu', 'Fájltípus'),
(1522, 'label_type', 'en', 'File type'),
(1523, 'label_type', 'de', 'Dateityp'),
(1524, 'search_type_all_types', 'hu', 'Összes típus'),
(1525, 'search_type_all_types', 'en', 'All types'),
(1526, 'search_type_all_types', 'de', 'Alle Typen'),
(1527, 'search_type_video_mp4', 'hu', 'Videó (MP4)'),
(1528, 'search_type_video_mp4', 'en', 'Video (MP4)'),
(1529, 'search_type_video_mp4', 'de', 'Video (MP4)'),
(1530, 'search_type_word_docx', 'hu', 'Word (DOCX)'),
(1531, 'search_type_word_docx', 'en', 'Word (DOCX)'),
(1532, 'search_type_word_docx', 'de', 'Word (DOCX)'),
(1533, 'search_level_label', 'hu', 'Szint'),
(1534, 'search_level_label', 'en', 'Level'),
(1535, 'search_level_label', 'de', 'Stufe'),
(1536, 'search_level_all', 'hu', 'Összes'),
(1537, 'search_level_all', 'en', 'All'),
(1538, 'search_level_all', 'de', 'Alle'),
(1539, 'search_level_none', 'hu', 'Nincs megadva'),
(1540, 'search_level_none', 'en', 'Not specified'),
(1541, 'search_level_none', 'de', 'Nicht angegeben'),
(1542, 'search_level_group_hs', 'hu', 'Technikum (9–13)'),
(1543, 'search_level_group_hs', 'en', 'Secondary school (9–13)'),
(1544, 'search_level_group_hs', 'de', 'Fachschule (9–13)'),
(1545, 'search_level_group_uni', 'hu', 'Egyetem (1–7. félév)'),
(1546, 'search_level_group_uni', 'en', 'University (semester 1–7)'),
(1547, 'search_level_group_uni', 'de', 'Universität (1.–7. Semester)'),
(1548, 'search_hs_year_fmt', 'hu', '%d. évfolyam'),
(1549, 'search_hs_year_fmt', 'en', 'Year %d'),
(1550, 'search_hs_year_fmt', 'de', '%d. Jahrgang'),
(1551, 'search_uni_semester_fmt', 'hu', '%d. félév'),
(1552, 'search_uni_semester_fmt', 'en', 'Semester %d'),
(1553, 'search_uni_semester_fmt', 'de', '%d. Semester'),
(1554, 'search_tag_label', 'hu', 'Tag'),
(1555, 'search_tag_label', 'en', 'Tag'),
(1556, 'search_tag_label', 'de', 'Tag'),
(1557, 'search_tag_placeholder', 'hu', 'pl. Tankönyv'),
(1558, 'search_tag_placeholder', 'en', 'e.g. Textbook'),
(1559, 'search_tag_placeholder', 'de', 'z. B. Lehrbuch'),
(1560, 'search_mode_label', 'hu', 'Keresési mód'),
(1561, 'search_mode_label', 'en', 'Search mode'),
(1562, 'search_mode_label', 'de', 'Suchmodus'),
(1563, 'search_mode_all_words', 'hu', 'Minden szó (AND)'),
(1564, 'search_mode_all_words', 'en', 'All words (AND)'),
(1565, 'search_mode_all_words', 'de', 'Alle Wörter (AND)'),
(1566, 'search_mode_any_word', 'hu', 'Bármely szó (OR)'),
(1567, 'search_mode_any_word', 'en', 'Any word (OR)'),
(1568, 'search_mode_any_word', 'de', 'Beliebiges Wort (OR)'),
(1569, 'label_sort', 'hu', 'Rendezés'),
(1570, 'label_sort', 'en', 'Sort by'),
(1571, 'label_sort', 'de', 'Sortieren nach'),
(1572, 'search_sort_relevance', 'hu', 'Relevancia'),
(1573, 'search_sort_relevance', 'en', 'Relevance'),
(1574, 'search_sort_relevance', 'de', 'Relevanz'),
(1575, 'search_sort_newest', 'hu', 'Legújabb elöl'),
(1576, 'search_sort_newest', 'en', 'Newest first'),
(1577, 'search_sort_newest', 'de', 'Neueste zuerst'),
(1578, 'search_sort_oldest', 'hu', 'Legrégebbi elöl'),
(1579, 'search_sort_oldest', 'en', 'Oldest first'),
(1580, 'search_sort_oldest', 'de', 'Älteste zuerst'),
(1581, 'search_sort_downloads', 'hu', 'Legtöbb letöltés'),
(1582, 'search_sort_downloads', 'en', 'Most downloads'),
(1583, 'search_sort_downloads', 'de', 'Meiste Downloads'),
(1584, 'search_sort_rating', 'hu', 'Legjobb értékelés'),
(1585, 'search_sort_rating', 'en', 'Best rating'),
(1586, 'search_sort_rating', 'de', 'Beste Bewertung'),
(1587, 'search_download_counter_missing', 'hu', '(Nincs letöltésszámláló oszlop a files táblában)'),
(1588, 'search_download_counter_missing', 'en', '(No download counter column in the files table)'),
(1589, 'search_download_counter_missing', 'de', '(Keine Download-Zähler-Spalte in der Dateientabelle)'),
(1590, 'search_facet_level', 'hu', 'Szint:'),
(1591, 'search_facet_level', 'en', 'Level:'),
(1592, 'search_facet_level', 'de', 'Stufe:'),
(1593, 'search_facet_all_fmt', 'hu', 'Összes (%d)'),
(1594, 'search_facet_all_fmt', 'en', 'All (%d)'),
(1595, 'search_facet_all_fmt', 'de', 'Alle (%d)'),
(1596, 'search_facet_none_fmt', 'hu', 'Nincs megadva (%d)'),
(1597, 'search_facet_none_fmt', 'en', 'Not specified (%d)'),
(1598, 'search_facet_none_fmt', 'de', 'Nicht angegeben (%d)'),
(1599, 'open_profile', 'hu', 'Profil megnyitása'),
(1600, 'open_profile', 'en', 'Open profile'),
(1601, 'open_profile', 'de', 'Profil öffnen'),
(1602, 'search_results_showing_fmt', 'hu', 'Mutatom: <strong>%d–%d</strong> / %d'),
(1603, 'search_results_showing_fmt', 'en', 'Showing: <strong>%d–%d</strong> of %d'),
(1604, 'search_results_showing_fmt', 'de', 'Zeige: <strong>%d–%d</strong> von %d'),
(1605, 'search_edu_none', 'hu', 'Nincs megadva'),
(1606, 'search_edu_none', 'en', 'Not specified'),
(1607, 'search_edu_none', 'de', 'Nicht angegeben'),
(1608, 'search_edu_hs_fmt', 'hu', 'Technikum – %d. évfolyam'),
(1609, 'search_edu_hs_fmt', 'en', 'Secondary school – Year %d'),
(1610, 'search_edu_hs_fmt', 'de', 'Fachschule – %d. Jahrgang'),
(1611, 'search_edu_uni_fmt', 'hu', 'Egyetem – %d. félév'),
(1612, 'search_edu_uni_fmt', 'en', 'University – Semester %d'),
(1613, 'search_edu_uni_fmt', 'de', 'Universität – %d. Semester'),
(1614, 'search_score_fmt', 'hu', 'Pont: %d'),
(1615, 'search_score_fmt', 'en', 'Score: %d'),
(1616, 'search_score_fmt', 'de', 'Punkte: %d'),
(1617, 'search_more', 'hu', 'Továbbiak →'),
(1618, 'search_more', 'en', 'Load more →'),
(1619, 'search_more', 'de', 'Mehr laden →'),
(1620, 'search_back_to_paging', 'hu', 'Vissza lapozáshoz'),
(1621, 'search_back_to_paging', 'en', 'Back to paging'),
(1622, 'search_back_to_paging', 'de', 'Zurück zur Seitennavigation'),
(1623, 'search_prev', 'hu', '← Előző'),
(1624, 'search_prev', 'en', '← Previous'),
(1625, 'search_prev', 'de', '← Vorherige'),
(1626, 'search_next', 'hu', 'Következő →'),
(1627, 'search_next', 'en', 'Next →'),
(1628, 'search_next', 'de', 'Nächste →'),
(1629, 'search_page_fmt', 'hu', 'Oldal %d / %d'),
(1630, 'search_page_fmt', 'en', 'Page %d of %d'),
(1631, 'search_page_fmt', 'de', 'Seite %d von %d'),
(1632, 'search_no_results_title', 'hu', 'Nincs találat'),
(1633, 'search_no_results_title', 'en', 'No results'),
(1634, 'search_no_results_title', 'de', 'Keine Ergebnisse'),
(1635, 'search_no_results_accent_tip_fmt', 'hu', 'Tipp: próbáld ékezetek nélkül: %s'),
(1636, 'search_no_results_accent_tip_fmt', 'en', 'Tip: try without accents: %s'),
(1637, 'search_no_results_accent_tip_fmt', 'de', 'Tipp: versuche es ohne Akzente: %s'),
(1638, 'search_did_you_mean', 'hu', 'Erre gondoltál?'),
(1639, 'search_did_you_mean', 'en', 'Did you mean?'),
(1640, 'search_did_you_mean', 'de', 'Meintest du?'),
(1641, 'search_no_results_try_shorter', 'hu', 'Próbáld meg rövidebb kulcsszóval.'),
(1642, 'search_no_results_try_shorter', 'en', 'Try a shorter keyword.'),
(1643, 'search_no_results_try_shorter', 'de', 'Versuche es mit einem kürzeren Suchbegriff.'),
(1644, 'search_no_results_clear_filters', 'hu', 'Töröld a szűrőket (szint / tag / típus), és nézd meg úgy.'),
(1645, 'search_no_results_clear_filters', 'en', 'Clear the filters (level / tag / type) and try again.'),
(1646, 'search_no_results_clear_filters', 'de', 'Lösche die Filter (Stufe / Tag / Typ) und versuche es erneut.'),
(1647, 'search_no_results_browse_empty', 'hu', 'Ha csak böngésznél, hagyd üresen a keresést.'),
(1648, 'search_no_results_browse_empty', 'en', 'If you just want to browse, leave the search field empty.'),
(1649, 'search_no_results_browse_empty', 'de', 'Wenn du nur stöbern möchtest, lass das Suchfeld leer.'),
(1650, 'about_title', 'hu', 'Rólunk'),
(1651, 'about_title', 'en', 'About us'),
(1652, 'about_title', 'de', 'Über uns'),
(1653, 'about_hero_sub', 'hu', 'A <strong>Jegyzetár</strong> egy közösségi jegyzetmegosztó platform, amely főként a <strong>Szoftverfejlesztő és -tesztelő</strong>, valamint az <strong>Informatikai rendszer- és alkalmazás-üzemeltető technikus</strong> ágazatok tananyagaihoz készült. Cél'),
(1654, 'about_hero_sub', 'en', '<strong>Jegyzetár</strong> is a community note-sharing platform built primarily for the <strong>Software Developer &amp; Tester</strong> and <strong>IT Systems &amp; Application Operations Technician</strong> study programmes. Our goal is to make notes, w'),
(1655, 'about_hero_sub', 'de', '<strong>Jegyzetár</strong> ist eine gemeinschaftliche Notizen-Sharing-Plattform, die hauptsächlich für die Studiengänge <strong>Softwareentwicklung &amp; -test</strong> und <strong>IT-System- und Anwendungsbetrieb</strong> entwickelt wurde. Unser Ziel ist'),
(1656, 'about_pill_community', 'hu', 'Közösségi tudásmegosztás'),
(1657, 'about_pill_community', 'en', 'Community knowledge sharing'),
(1658, 'about_pill_community', 'de', 'Gemeinschaftliches Wissenssharing'),
(1659, 'about_pill_structure', 'hu', 'Áttekinthető felépítés'),
(1660, 'about_pill_structure', 'en', 'Clear structure'),
(1661, 'about_pill_structure', 'de', 'Übersichtlicher Aufbau'),
(1662, 'about_pill_ui', 'hu', 'Reszponzív, modern UI'),
(1663, 'about_pill_ui', 'en', 'Responsive, modern UI'),
(1664, 'about_pill_ui', 'de', 'Responsives, modernes UI'),
(1665, 'about_pill_dev', 'hu', 'Folyamatos fejlesztés'),
(1666, 'about_pill_dev', 'en', 'Continuous development'),
(1667, 'about_pill_dev', 'de', 'Kontinuierliche Entwicklung'),
(1668, 'about_btn_faq', 'hu', 'Gyakori Kérdések'),
(1669, 'about_btn_faq', 'en', 'FAQ'),
(1670, 'about_btn_faq', 'de', 'Häufige Fragen'),
(1671, 'about_btn_report', 'hu', 'Hibajelentés'),
(1672, 'about_btn_report', 'en', 'Bug report'),
(1673, 'about_btn_report', 'de', 'Fehlermeldung'),
(1674, 'about_what_title', 'hu', 'Mi az a Jegyzetár?'),
(1675, 'about_what_title', 'en', 'What is Jegyzetár?'),
(1676, 'about_what_title', 'de', 'Was ist Jegyzetár?'),
(1677, 'about_what_p1', 'hu', 'A Jegyzetár egy diákok által épített, webalapú tudástár, ami elsősorban a <strong>Szoftverfejlesztő és -tesztelő</strong> és az <strong>Informatikai rendszer- és alkalmazás-üzemeltető technikus</strong> képzésekhez kapcsolódó jegyzeteket és gyakorlati any'),
(1678, 'about_what_p1', 'en', 'Jegyzetár is a student-built, web-based knowledge base that collects notes and practical materials related primarily to the <strong>Software Developer &amp; Tester</strong> and <strong>IT Systems &amp; Application Operations</strong> programmes in one pla'),
(1679, 'about_what_p1', 'de', 'Jegyzetár ist eine von Schülern entwickelte, webbasierte Wissenssammlung, die Notizen und Übungsmaterialien zu den Studiengängen <strong>Softwareentwicklung &amp; -test</strong> und <strong>IT-System- und Anwendungsbetrieb</strong> an einem Ort zusammenfa'),
(1680, 'about_what_p2', 'hu', 'Úgy raktuk össze, hogy gyorsan lehessen keresni tantárgyak, témák és kulcsszavak alapján, és ne kelljen többé szétszórt Messenger/Drive linkekből vadászni a tartalmakat.'),
(1681, 'about_what_p2', 'en', 'We built it so you can quickly search by subject, topic and keywords - no more hunting through scattered Messenger or Drive links.'),
(1682, 'about_what_p2', 'de', 'Wir haben es so aufgebaut, dass du schnell nach Fach, Thema und Stichwörtern suchen kannst - kein lästiges Suchen mehr in verstreuten Messenger- oder Drive-Links.'),
(1683, 'about_principles_title', 'hu', 'Alapelveink'),
(1684, 'about_principles_title', 'en', 'Our principles'),
(1685, 'about_principles_title', 'de', 'Unsere Grundsätze'),
(1686, 'about_principle_1', 'hu', 'Egyszerű használat'),
(1687, 'about_principle_1', 'en', 'Simple to use'),
(1688, 'about_principle_1', 'de', 'Einfache Bedienung'),
(1689, 'about_principle_2', 'hu', 'Áttekinthető felépítés'),
(1690, 'about_principle_2', 'en', 'Clear structure'),
(1691, 'about_principle_2', 'de', 'Übersichtlicher Aufbau'),
(1692, 'about_principle_3', 'hu', 'Ágazat-központú rendszerezés (Szoftver + Üzemeltetés)'),
(1693, 'about_principle_3', 'en', 'Sector-focused organisation (Software + Operations)'),
(1694, 'about_principle_3', 'de', 'Branchenzentrierte Strukturierung (Software + Betrieb)'),
(1695, 'about_principle_4', 'hu', 'Közösségi tudásmegosztás'),
(1696, 'about_principle_4', 'en', 'Community knowledge sharing'),
(1697, 'about_principle_4', 'de', 'Gemeinschaftliches Wissenssharing'),
(1698, 'about_principle_5', 'hu', 'Biztonságos, felelős működés'),
(1699, 'about_principle_5', 'en', 'Safe and responsible operation'),
(1700, 'about_principle_5', 'de', 'Sicherer und verantwortungsvoller Betrieb'),
(1701, 'about_features_title', 'hu', 'Fő funkciók');
INSERT INTO `translations` (`id`, `t_key`, `lang_code`, `text`) VALUES
(1702, 'about_features_title', 'en', 'Key features'),
(1703, 'about_features_title', 'de', 'Hauptfunktionen'),
(1704, 'about_feature_notes_title', 'hu', 'Jegyzetkezelés'),
(1705, 'about_feature_notes_title', 'en', 'Note management'),
(1706, 'about_feature_notes_title', 'de', 'Notizverwaltung'),
(1707, 'about_feature_notes_desc', 'hu', 'Jegyzetek, kidolgozott tételek, kódpéldák és gyakorlati segédletek feltöltése és rendszerezése; gyors keresés tantárgy, ágazat és kulcsszó alapján.'),
(1708, 'about_feature_notes_desc', 'en', 'Upload and organise notes, worked exam questions, code examples and practical guides; fast search by subject, sector and keyword.'),
(1709, 'about_feature_notes_desc', 'de', 'Hochladen und Organisieren von Notizen, ausgearbeiteten Prüfungsthemen, Codebeispielen und Praxisleitfäden; schnelle Suche nach Fach, Branche und Stichwort.'),
(1710, 'about_feature_community_title', 'hu', 'Közösségi funkciók'),
(1711, 'about_feature_community_title', 'en', 'Community features'),
(1712, 'about_feature_community_title', 'de', 'Community-Funktionen'),
(1713, 'about_feature_community_desc', 'hu', 'Kommentelés, értékelés, kedvencek, és visszajelzés a feltöltött anyagokra - hogy a legjobb tartalmak előre kerüljenek.'),
(1714, 'about_feature_community_desc', 'en', 'Comments, ratings, favourites and feedback on uploaded materials - so the best content rises to the top.'),
(1715, 'about_feature_community_desc', 'de', 'Kommentare, Bewertungen, Favoriten und Feedback zu hochgeladenen Materialien - damit die besten Inhalte nach oben kommen.'),
(1716, 'about_feature_gamification_title', 'hu', 'Gamifikáció & bővítés'),
(1717, 'about_feature_gamification_title', 'en', 'Gamification & expansion'),
(1718, 'about_feature_gamification_title', 'de', 'Gamification & Erweiterung'),
(1719, 'about_feature_gamification_desc', 'hu', 'Pontok, jelvények és (jövőben) valós idejű közös jegyzetelés, AI-alapú funkciók.'),
(1720, 'about_feature_gamification_desc', 'en', 'Points, badges and (coming soon) real-time collaborative note-taking, AI-powered features.'),
(1721, 'about_feature_gamification_desc', 'de', 'Punkte, Abzeichen und (demnächst) Echtzeit-Kollaborationsnotizen sowie KI-Funktionen.'),
(1722, 'about_motto', 'hu', 'Közösségi mottónk: <strong>\"Tanuljunk együtt, ne külön-külön.\"</strong>'),
(1723, 'about_motto', 'en', 'Our community motto: <strong>\"Learn together, not apart.\"</strong>'),
(1724, 'about_motto', 'de', 'Unser Gemeinschaftsmotto: <strong>\"Gemeinsam lernen, nicht alleine.\"</strong>'),
(1725, 'about_tech_title', 'hu', 'Használt technológiák'),
(1726, 'about_tech_title', 'en', 'Technologies used'),
(1727, 'about_tech_title', 'de', 'Verwendete Technologien'),
(1728, 'about_tech_col_part', 'hu', 'Rész'),
(1729, 'about_tech_col_part', 'en', 'Layer'),
(1730, 'about_tech_col_part', 'de', 'Bereich'),
(1731, 'about_tech_col_tech', 'hu', 'Technológia'),
(1732, 'about_tech_col_tech', 'en', 'Technology'),
(1733, 'about_tech_col_tech', 'de', 'Technologie'),
(1734, 'about_tech_frontend', 'hu', 'React.js (tervezett), modern reszponzív UI'),
(1735, 'about_tech_frontend', 'en', 'React.js (planned), modern responsive UI'),
(1736, 'about_tech_frontend', 'de', 'React.js (geplant), modernes responsives UI'),
(1737, 'about_tech_docs', 'hu', 'A részletes telepítési/használati útmutató a fejlesztői dokumentációban található.'),
(1738, 'about_tech_docs', 'en', 'Detailed installation and usage instructions can be found in the developer documentation.'),
(1739, 'about_tech_docs', 'de', 'Ausführliche Installations- und Nutzungshinweise findest du in der Entwicklerdokumentation.'),
(1740, 'about_team_title', 'hu', 'NoteForge Development - a csapat és a megalakulás'),
(1741, 'about_title', 'hu', 'Rólunk'),
(1742, 'about_title', 'en', 'About us'),
(1743, 'about_title', 'de', 'Über uns'),
(1744, 'about_hero_sub', 'hu', 'A <strong>Jegyzetár</strong> egy közösségi jegyzetmegosztó platform, amely főként a <strong>Szoftverfejlesztő és -tesztelő</strong>, valamint az <strong>Informatikai rendszer- és alkalmazás-üzemeltető technikus</strong> ágazatok tananyagaihoz készült. Cél'),
(1745, 'about_hero_sub', 'en', '<strong>Jegyzetár</strong> is a community note-sharing platform built primarily for the <strong>Software Developer &amp; Tester</strong> and <strong>IT Systems &amp; Application Operations Technician</strong> study programmes. Our goal is to make notes, w'),
(1746, 'about_hero_sub', 'de', '<strong>Jegyzetár</strong> ist eine gemeinschaftliche Notizen-Sharing-Plattform, die hauptsächlich für die Studiengänge <strong>Softwareentwicklung &amp; -test</strong> und <strong>IT-System- und Anwendungsbetrieb</strong> entwickelt wurde. Unser Ziel ist'),
(1747, 'about_pill_community', 'hu', 'Közösségi tudásmegosztás'),
(1748, 'about_pill_community', 'en', 'Community knowledge sharing'),
(1749, 'about_pill_community', 'de', 'Gemeinschaftliches Wissenssharing'),
(1750, 'about_pill_structure', 'hu', 'Áttekinthető felépítés'),
(1751, 'about_pill_structure', 'en', 'Clear structure'),
(1752, 'about_pill_structure', 'de', 'Übersichtlicher Aufbau'),
(1753, 'about_pill_ui', 'hu', 'Reszponzív, modern UI'),
(1754, 'about_pill_ui', 'en', 'Responsive, modern UI'),
(1755, 'about_pill_ui', 'de', 'Responsives, modernes UI'),
(1756, 'about_pill_dev', 'hu', 'Folyamatos fejlesztés'),
(1757, 'about_pill_dev', 'en', 'Continuous development'),
(1758, 'about_pill_dev', 'de', 'Kontinuierliche Entwicklung'),
(1759, 'about_btn_faq', 'hu', 'Gyakori Kérdések'),
(1760, 'about_btn_faq', 'en', 'FAQ'),
(1761, 'about_btn_faq', 'de', 'Häufige Fragen'),
(1762, 'about_btn_report', 'hu', 'Hibajelentés'),
(1763, 'about_btn_report', 'en', 'Bug report'),
(1764, 'about_btn_report', 'de', 'Fehlermeldung'),
(1765, 'about_what_title', 'hu', 'Mi az a Jegyzetár?'),
(1766, 'about_what_title', 'en', 'What is Jegyzetár?'),
(1767, 'about_what_title', 'de', 'Was ist Jegyzetár?'),
(1768, 'about_what_p1', 'hu', 'A Jegyzetár egy diákok által épített, webalapú tudástár, ami elsősorban a <strong>Szoftverfejlesztő és -tesztelő</strong> és az <strong>Informatikai rendszer- és alkalmazás-üzemeltető technikus</strong> képzésekhez kapcsolódó jegyzeteket és gyakorlati any'),
(1769, 'about_what_p1', 'en', 'Jegyzetár is a student-built, web-based knowledge base that collects notes and practical materials related primarily to the <strong>Software Developer &amp; Tester</strong> and <strong>IT Systems &amp; Application Operations</strong> programmes in one pla'),
(1770, 'about_what_p1', 'de', 'Jegyzetár ist eine von Schülern entwickelte, webbasierte Wissenssammlung, die Notizen und Übungsmaterialien zu den Studiengängen <strong>Softwareentwicklung &amp; -test</strong> und <strong>IT-System- und Anwendungsbetrieb</strong> an einem Ort zusammenfa'),
(1771, 'about_what_p2', 'hu', 'Úgy raktuk össze, hogy gyorsan lehessen keresni tantárgyak, témák és kulcsszavak alapján, és ne kelljen többé szétszórt Messenger/Drive linkekből vadászni a tartalmakat.'),
(1772, 'about_what_p2', 'en', 'We built it so you can quickly search by subject, topic and keywords - no more hunting through scattered Messenger or Drive links.'),
(1773, 'about_what_p2', 'de', 'Wir haben es so aufgebaut, dass du schnell nach Fach, Thema und Stichwörtern suchen kannst - kein lästiges Suchen mehr in verstreuten Messenger- oder Drive-Links.'),
(1774, 'about_principles_title', 'hu', 'Alapelveink'),
(1775, 'about_principles_title', 'en', 'Our principles'),
(1776, 'about_principles_title', 'de', 'Unsere Grundsätze'),
(1777, 'about_principle_1', 'hu', 'Egyszerű használat'),
(1778, 'about_principle_1', 'en', 'Simple to use'),
(1779, 'about_principle_1', 'de', 'Einfache Bedienung'),
(1780, 'about_principle_2', 'hu', 'Áttekinthető felépítés'),
(1781, 'about_principle_2', 'en', 'Clear structure'),
(1782, 'about_principle_2', 'de', 'Übersichtlicher Aufbau'),
(1783, 'about_principle_3', 'hu', 'Ágazat-központú rendszerezés (Szoftver + Üzemeltetés)'),
(1784, 'about_principle_3', 'en', 'Sector-focused organisation (Software + Operations)'),
(1785, 'about_principle_3', 'de', 'Branchenzentrierte Strukturierung (Software + Betrieb)'),
(1786, 'about_principle_4', 'hu', 'Közösségi tudásmegosztás'),
(1787, 'about_principle_4', 'en', 'Community knowledge sharing'),
(1788, 'about_principle_4', 'de', 'Gemeinschaftliches Wissenssharing'),
(1789, 'about_principle_5', 'hu', 'Biztonságos, felelős működés'),
(1790, 'about_principle_5', 'en', 'Safe and responsible operation'),
(1791, 'about_principle_5', 'de', 'Sicherer und verantwortungsvoller Betrieb'),
(1792, 'about_features_title', 'hu', 'Fő funkciók'),
(1793, 'about_features_title', 'en', 'Key features'),
(1794, 'about_features_title', 'de', 'Hauptfunktionen'),
(1795, 'about_feature_notes_title', 'hu', 'Jegyzetkezelés'),
(1796, 'about_feature_notes_title', 'en', 'Note management'),
(1797, 'about_feature_notes_title', 'de', 'Notizverwaltung'),
(1798, 'about_feature_notes_desc', 'hu', 'Jegyzetek, kidolgozott tételek, kódpéldák és gyakorlati segédletek feltöltése és rendszerezése; gyors keresés tantárgy, ágazat és kulcsszó alapján.'),
(1799, 'about_feature_notes_desc', 'en', 'Upload and organise notes, worked exam questions, code examples and practical guides; fast search by subject, sector and keyword.'),
(1800, 'about_feature_notes_desc', 'de', 'Hochladen und Organisieren von Notizen, ausgearbeiteten Prüfungsthemen, Codebeispielen und Praxisleitfäden; schnelle Suche nach Fach, Branche und Stichwort.'),
(1801, 'about_feature_community_title', 'hu', 'Közösségi funkciók'),
(1802, 'about_feature_community_title', 'en', 'Community features'),
(1803, 'about_feature_community_title', 'de', 'Community-Funktionen'),
(1804, 'about_feature_community_desc', 'hu', 'Kommentelés, értékelés, kedvencek, és visszajelzés a feltöltött anyagokra - hogy a legjobb tartalmak előre kerüljenek.'),
(1805, 'about_feature_community_desc', 'en', 'Comments, ratings, favourites and feedback on uploaded materials - so the best content rises to the top.'),
(1806, 'about_feature_community_desc', 'de', 'Kommentare, Bewertungen, Favoriten und Feedback zu hochgeladenen Materialien - damit die besten Inhalte nach oben kommen.'),
(1807, 'about_feature_gamification_title', 'hu', 'Gamifikáció & bővítés'),
(1808, 'about_feature_gamification_title', 'en', 'Gamification & expansion'),
(1809, 'about_feature_gamification_title', 'de', 'Gamification & Erweiterung'),
(1810, 'about_feature_gamification_desc', 'hu', 'Pontok, jelvények és (jövőben) valós idejű közös jegyzetelés, AI-alapú funkciók.'),
(1811, 'about_feature_gamification_desc', 'en', 'Points, badges and (coming soon) real-time collaborative note-taking, AI-powered features.'),
(1812, 'about_feature_gamification_desc', 'de', 'Punkte, Abzeichen und (demnächst) Echtzeit-Kollaborationsnotizen sowie KI-Funktionen.'),
(1813, 'about_motto', 'hu', 'Közösségi mottónk: <strong>\"Tanuljunk együtt, ne külön-külön.\"</strong>'),
(1814, 'about_motto', 'en', 'Our community motto: <strong>\"Learn together, not apart.\"</strong>'),
(1815, 'about_motto', 'de', 'Unser Gemeinschaftsmotto: <strong>\"Gemeinsam lernen, nicht alleine.\"</strong>'),
(1816, 'about_tech_title', 'hu', 'Használt technológiák'),
(1817, 'about_tech_title', 'en', 'Technologies used'),
(1818, 'about_tech_title', 'de', 'Verwendete Technologien'),
(1819, 'about_tech_col_part', 'hu', 'Rész'),
(1820, 'about_tech_col_part', 'en', 'Layer'),
(1821, 'about_tech_col_part', 'de', 'Bereich'),
(1822, 'about_tech_col_tech', 'hu', 'Technológia'),
(1823, 'about_tech_col_tech', 'en', 'Technology'),
(1824, 'about_tech_col_tech', 'de', 'Technologie'),
(1825, 'about_tech_frontend', 'hu', 'React.js (tervezett), modern reszponzív UI'),
(1826, 'about_tech_frontend', 'en', 'React.js (planned), modern responsive UI'),
(1827, 'about_tech_frontend', 'de', 'React.js (geplant), modernes responsives UI'),
(1828, 'about_tech_docs', 'hu', 'A részletes telepítési/használati útmutató a fejlesztői dokumentációban található.'),
(1829, 'about_tech_docs', 'en', 'Detailed installation and usage instructions can be found in the developer documentation.'),
(1830, 'about_tech_docs', 'de', 'Ausführliche Installations- und Nutzungshinweise findest du in der Entwicklerdokumentation.'),
(1831, 'about_team_title', 'hu', 'NoteForge Development - a csapat és a megalakulás'),
(1832, 'about_team_title', 'en', 'NoteForge Development - the team and how it started'),
(1833, 'about_team_title', 'de', 'NoteForge Development - das Team und die Entstehung'),
(1834, 'about_team_p1', 'hu', 'A NoteForge Development a Jegyzetár mögött álló fejlesztői csapat. A projektet diákok indították, azzal a céllal, hogy egy modern, közösségi tudástár szülessen, ami valódi problémát old meg a mindennapi tanulásban.'),
(1835, 'about_team_p1', 'en', 'NoteForge Development is the team behind Jegyzetár. The project was started by students with the goal of creating a modern, community-driven knowledge base that solves a real problem in everyday learning.'),
(1836, 'about_team_p1', 'de', 'NoteForge Development ist das Entwicklerteam hinter Jegyzetár. Das Projekt wurde von Schülern gegründet, mit dem Ziel, eine moderne, gemeinschaftsgetriebene Wissensbasis zu schaffen, die ein echtes Problem im Alltag des Lernens löst.'),
(1837, 'about_team_p2', 'hu', 'A fejlesztés folyamatos, a projekt nyitott új ötletekre, visszajelzésekre és későbbi közreműködőkre.'),
(1838, 'about_team_p2', 'en', 'Development is ongoing; the project is open to new ideas, feedback and future contributors.'),
(1839, 'about_team_p2', 'de', 'Die Entwicklung geht weiter; das Projekt ist offen für neue Ideen, Rückmeldungen und zukünftige Mitwirkende.'),
(1840, 'about_legal_title', 'hu', 'Rövid jogi nyilatkozat'),
(1841, 'about_legal_title', 'en', 'Brief legal notice'),
(1842, 'about_legal_title', 'de', 'Kurzer rechtlicher Hinweis'),
(1843, 'about_legal_p1', 'hu', 'A Jegyzetár egy <strong>oktatási célú</strong>, diákok által fejlesztett projekt. A platformon megjelenő tartalmakért elsődlegesen a feltöltők felelnek.'),
(1844, 'about_legal_p1', 'en', 'Jegyzetár is an <strong>educational</strong> project developed by students. The uploaders are primarily responsible for the content published on the platform.'),
(1845, 'about_legal_p1', 'de', 'Jegyzetár ist ein von Schülern entwickeltes <strong>Bildungsprojekt</strong>. Für die auf der Plattform veröffentlichten Inhalte sind in erster Linie die Hochladenden verantwortlich.'),
(1846, 'about_legal_copyright', 'hu', '<strong>Jogvédett tartalom:</strong> kérünk, ne tölts fel teljes tankönyveket, fizetős anyagokat vagy más, engedélyhez kötött tartalmat.'),
(1847, 'about_legal_copyright', 'en', '<strong>Copyrighted content:</strong> please do not upload full textbooks, paid materials or other licensed content.'),
(1848, 'about_legal_copyright', 'de', '<strong>Urheberrechtlich geschützter Inhalt:</strong> Bitte lade keine vollständigen Lehrbücher, kostenpflichtigen Materialien oder anderen lizenzierten Inhalte hoch.'),
(1849, 'about_legal_personal', 'hu', '<strong>Személyes adatok:</strong> ne ossz meg érzékeny információkat (pl. lakcím, telefonszám, diákigazolvány, osztálynapló fotó).'),
(1850, 'about_legal_personal', 'en', '<strong>Personal data:</strong> do not share sensitive information (e.g. home address, phone number, student ID, class register photo).'),
(1851, 'about_legal_personal', 'de', '<strong>Personenbezogene Daten:</strong> Teile keine sensiblen Informationen (z. B. Wohnadresse, Telefonnummer, Schülerausweis, Klassenbuchfoto).'),
(1852, 'about_legal_liability', 'hu', '<strong>Felelősségkorlátozás:</strong> mindent ésszerű keretek között teszünk a biztonságért, de a szolgáltatást \"ahogy van\" alapon biztosítjuk.'),
(1853, 'about_legal_liability', 'en', '<strong>Limitation of liability:</strong> we do everything reasonable for security, but the service is provided \"as is\".'),
(1854, 'about_legal_liability', 'de', '<strong>Haftungsbeschränkung:</strong> Wir tun alles Zumutbare für die Sicherheit, aber der Dienst wird \"wie besehen\" bereitgestellt.'),
(1855, 'about_legal_links', 'hu', 'Részletekért nézd meg az <a href=\"terms.php\">ÁSZF</a> és az <a href=\"privacy.php\">Adatvédelem</a> oldalt.'),
(1856, 'about_legal_links', 'en', 'For details see the <a href=\"terms.php\">Terms of Service</a> and <a href=\"privacy.php\">Privacy Policy</a> pages.'),
(1857, 'about_legal_links', 'de', 'Details findest du auf den Seiten <a href=\"terms.php\">Nutzungsbedingungen</a> und <a href=\"privacy.php\">Datenschutz</a>.'),
(1858, 'about_cta_title', 'hu', 'Van ötleted vagy észrevételed?'),
(1859, 'about_cta_title', 'en', 'Have an idea or feedback?'),
(1860, 'about_cta_title', 'de', 'Hast du eine Idee oder Anmerkung?'),
(1861, 'about_cta_sub', 'hu', 'Írj nekünk, vagy jelezd a hibát - a Jegyzetár attól lesz jobb, hogy használjátok és visszajelzést adtok.'),
(1862, 'about_cta_sub', 'en', 'Write to us or report a bug - Jegyzetár gets better because you use it and give feedback.'),
(1863, 'about_cta_sub', 'de', 'Schreib uns oder melde einen Fehler - Jegyzetár wird besser, weil ihr es nutzt und Feedback gebt.'),
(1864, 'about_btn_contact', 'hu', 'Kapcsolat'),
(1865, 'about_btn_contact', 'en', 'Contact'),
(1866, 'about_btn_contact', 'de', 'Kontakt'),
(1867, 'team_title', 'hu', 'Csapattagjaink'),
(1868, 'team_title', 'en', 'Our team'),
(1869, 'team_title', 'de', 'Unser Team'),
(1870, 'team_hero_sub', 'hu', 'A Jegyzetár mögött egy lelkes diákcsapat áll, akik elkötelezettek a tudásmegosztás és a tanulás iránt. Ismerd meg a csapattagokat, akik nap mint nap azon dolgoznak, hogy a Jegyzetár egy jobb hely legyen!'),
(1871, 'team_hero_sub', 'en', 'Behind Jegyzetár stands an enthusiastic student team committed to knowledge sharing and learning. Meet the team members who work every day to make Jegyzetár a better place!'),
(1872, 'team_hero_sub', 'de', 'Hinter Jegyzetár steht ein begeistertes Schülerteam, das sich dem Wissensaustausch und dem Lernen verschrieben hat. Lerne die Teammitglieder kennen, die täglich daran arbeiten, Jegyzetár besser zu machen!'),
(1873, 'team_norbert_alt', 'hu', 'Baranyi Norbert profilképe'),
(1874, 'team_norbert_alt', 'en', 'Baranyi Norbert profile picture'),
(1875, 'team_norbert_alt', 'de', 'Profilbild von Baranyi Norbert'),
(1876, 'team_norbert_bio', 'hu', 'Baranyi Norbert a Jegyzetár fejlesztésében elsősorban a backend és az adatkezelés kulcsterületein dolgozott. Nevéhez köthető a hitelesítési folyamatok megvalósítása (például a kétlépcsős azonosítás és az e-mailes visszaigazolás), valamint a jegyzetekhez k'),
(1877, 'team_norbert_bio', 'en', 'Baranyi Norbert worked primarily on the backend and data management key areas of Jegyzetár. He implemented the authentication flows (such as two-factor authentication and email verification), as well as note-related features: the note detail page, ratings'),
(1878, 'team_norbert_bio', 'de', 'Baranyi Norbert arbeitete hauptsächlich im Backend und in den Schlüsselbereichen der Datenverwaltung von Jegyzetár. Er implementierte die Authentifizierungsabläufe (z. B. Zwei-Faktor-Authentifizierung und E-Mail-Bestätigung) sowie notizenbezogene Funktion'),
(1879, 'team_anastasia_alt', 'hu', 'Anastasia profilképe'),
(1880, 'team_anastasia_alt', 'en', 'Anastasia profile picture'),
(1881, 'team_anastasia_alt', 'de', 'Profilbild von Anastasia'),
(1882, 'team_anastasia_bio', 'hu', 'Anasztázia a Jegyzetár alapítója és vezető fejlesztője. A projektben a felhasználói élmény, a vizuális egység és a rendszer-szintű minőség fejlesztése volt a fő fókusza: a teljes felület újradizájnolása (Aurora UI stílus, reszponzív layout, navigáció újra'),
(1883, 'team_anastasia_bio', 'en', 'Anastasia is the founder and lead developer of Jegyzetár. Her main focus was user experience, visual consistency and system-level quality: a full UI redesign (Aurora UI style, responsive layout, navigation overhaul), introducing the multilingual system, a'),
(1884, 'team_anastasia_bio', 'de', 'Anasztázia ist die Gründerin und leitende Entwicklerin von Jegyzetár. Ihr Hauptfokus lag auf der Nutzererfahrung, visueller Konsistenz und Systemqualität: ein vollständiges UI-Redesign (Aurora-UI-Stil, responsives Layout, Navigationsüberarbeitung), die Ei'),
(1885, 'team_paladitech_alt', 'hu', 'Paladitech profilképe'),
(1886, 'team_paladitech_alt', 'en', 'Paladitech profile picture'),
(1887, 'team_paladitech_alt', 'de', 'Profilbild von Paladitech'),
(1888, 'team_paladitech_bio', 'hu', 'Paladitech a Jegyzetár közösségi bővítésein és mobilos használhatóságán dolgozott. Teljes körűen megvalósította a tanulócsoport funkciókat (csoport létrehozás, tagságkezelés, jelentkezések), valamint a csoporton belüli jegyzetfeltöltést jóváhagyási és mod'),
(1889, 'team_paladitech_bio', 'en', 'Paladitech worked on the community extensions and mobile usability of Jegyzetár. He fully implemented the study group features (group creation, membership management, applications) as well as in-group note uploads with an approval and moderation workflow.'),
(1890, 'team_paladitech_bio', 'de', 'Paladitech arbeitete an den Community-Erweiterungen und der mobilen Nutzbarkeit von Jegyzetár. Er implementierte vollständig die Lerngruppen-Funktionen (Gruppenerstellung, Mitgliederverwaltung, Anmeldungen) sowie gruppeninterne Notiz-Uploads mit einem Gen'),
(1891, 'partners_title', 'hu', 'Partnereink'),
(1892, 'partners_title', 'en', 'Our partners'),
(1893, 'partners_title', 'de', 'Unsere Partner'),
(1894, 'partners_intro', 'hu', 'Itt találod azokat a projekteket / közösségeket, akikkel együttműködünk.'),
(1895, 'partners_intro', 'en', 'Here you can find the projects and communities we collaborate with.'),
(1896, 'partners_intro', 'de', 'Hier findest du die Projekte und Communities, mit denen wir zusammenarbeiten.'),
(1897, 'partners_btn_website', 'hu', 'Weboldal'),
(1898, 'partners_btn_website', 'en', 'Website'),
(1899, 'partners_btn_website', 'de', 'Webseite'),
(1900, 'partners_btn_invite', 'hu', 'Meghívás'),
(1901, 'partners_btn_invite', 'en', 'Invite'),
(1902, 'partners_btn_invite', 'de', 'Einladen'),
(1903, 'faq_title', 'hu', 'GYIK'),
(1904, 'faq_title', 'en', 'FAQ'),
(1905, 'faq_title', 'de', 'FAQ'),
(1906, 'faq_heading', 'hu', 'Gyakran ismételt kérdések'),
(1907, 'faq_heading', 'en', 'Frequently asked questions'),
(1908, 'faq_heading', 'de', 'Häufig gestellte Fragen'),
(1909, 'faq_q1', 'hu', 'Mi az a Jegyzetár?'),
(1910, 'faq_q1', 'en', 'What is Jegyzetár?'),
(1911, 'faq_q1', 'de', 'Was ist Jegyzetár?'),
(1912, 'faq_a1', 'hu', 'A Jegyzetár egy online platform, ahol iskolai jegyzeteket lehet feltölteni, rendszerezni és megosztani, hogy a tanulás gyorsabb és közösségibb legyen.'),
(1913, 'faq_a1', 'en', 'Jegyzetár is an online platform where you can upload, organise and share school notes to make learning faster and more collaborative.'),
(1914, 'faq_a1', 'de', 'Jegyzetár ist eine Online-Plattform, auf der du Schulnotizen hochladen, organisieren und teilen kannst, um das Lernen schneller und gemeinschaftlicher zu machen.'),
(1915, 'faq_q2', 'hu', 'Kell regisztrálnom a használathoz?'),
(1916, 'faq_q2', 'en', 'Do I need to register to use it?'),
(1917, 'faq_q2', 'de', 'Muss ich mich registrieren, um es zu nutzen?'),
(1918, 'faq_a2', 'hu', 'A böngészés részben elérhető vendégként is, de a feltöltés, értékelés, kedvencek és tanulócsoportok használatához bejelentkezés szükséges.'),
(1919, 'faq_a2', 'en', 'Browsing is partially available as a guest, but uploading, rating, favourites and study groups require you to log in.'),
(1920, 'faq_a2', 'de', 'Das Durchsuchen ist teilweise als Gast möglich, aber für das Hochladen, Bewerten, Favorisieren und die Nutzung von Lerngruppen ist eine Anmeldung erforderlich.'),
(1921, 'faq_q3', 'hu', 'Hogyan tudok jegyzetet feltölteni?'),
(1922, 'faq_q3', 'en', 'How do I upload a note?'),
(1923, 'faq_q3', 'de', 'Wie lade ich eine Notiz hoch?'),
(1924, 'faq_a3', 'hu', 'Bejelentkezés után a \"Jegyzet feltöltése\" oldalon megadod a címét, leírását, címkéit (tagek), és feltöltöd a fájlt.'),
(1925, 'faq_a3', 'en', 'After logging in, go to the \"Upload note\" page, fill in the title, description and tags, then upload the file.'),
(1926, 'faq_a3', 'de', 'Gehe nach dem Einloggen auf die Seite \"Notiz hochladen\", gib Titel, Beschreibung und Tags ein und lade dann die Datei hoch.'),
(1927, 'faq_q4', 'hu', 'Mi az a tanulócsoport és mire jó?'),
(1928, 'faq_q4', 'en', 'What is a study group and what is it for?'),
(1929, 'faq_q4', 'de', 'Was ist eine Lerngruppe und wofür ist sie gut?'),
(1930, 'faq_a4', 'hu', 'A tanulócsoportok egy adott tantárgy vagy téma köré szerveződnek, ahol a tagok megoszthatják a jegyzeteiket. A csoporton belüli feltöltések jóváhagyással/moderációval kezelhetők.'),
(1931, 'faq_a4', 'en', 'Study groups are organised around a particular subject or topic where members can share their notes. Uploads within the group can be managed with approval/moderation.'),
(1932, 'faq_a4', 'de', 'Lerngruppen werden um ein bestimmtes Fach oder Thema herum organisiert, in dem die Mitglieder ihre Notizen teilen können. Uploads innerhalb der Gruppe können mit Genehmigung/Moderation verwaltet werden.'),
(1933, 'faq_q5', 'hu', 'Biztonságos a Jegyzetár használata?'),
(1934, 'faq_q5', 'en', 'Is Jegyzetár safe to use?'),
(1935, 'faq_q5', 'de', 'Ist die Nutzung von Jegyzetár sicher?'),
(1936, 'faq_a5', 'hu', 'Igen. A rendszer több biztonsági megoldást alkalmaz (pl. biztonságos adatkezelés és védelmek), valamint lehetőség van külső bejelentkezésre (pl. Discord OAuth).'),
(1937, 'faq_a5', 'en', 'Yes. The system applies multiple security measures (e.g. secure data handling and protections), and external login is available (e.g. Discord OAuth).'),
(1938, 'faq_a5', 'de', 'Ja. Das System wendet mehrere Sicherheitsmaßnahmen an (z. B. sichere Datenverarbeitung und Schutzmaßnahmen), und eine externe Anmeldung ist möglich (z. B. Discord OAuth).'),
(1939, 'rules_title', 'hu', 'Szabályzat'),
(1940, 'rules_title', 'en', 'Rules'),
(1941, 'rules_title', 'de', 'Regeln'),
(1942, 'rules_h1', 'hu', 'Jegyzetár - Szabályzat'),
(1943, 'rules_h1', 'en', 'Jegyzetár - Rules'),
(1944, 'rules_h1', 'de', 'Jegyzetár - Regeln'),
(1945, 'rules_min_age_badge', 'hu', 'Minimális korhatár: %d év'),
(1946, 'rules_min_age_badge', 'en', 'Minimum age: %d years'),
(1947, 'rules_min_age_badge', 'de', 'Mindestalter: %d Jahre'),
(1948, 'rules_last_updated', 'hu', 'Utolsó frissítés: %s • A Jegyzetár használatával elfogadod az alábbi szabályokat.'),
(1949, 'rules_last_updated', 'en', 'Last updated: %s • By using Jegyzetár you accept the following rules.'),
(1950, 'rules_last_updated', 'de', 'Letzte Aktualisierung: %s • Durch die Nutzung von Jegyzetár akzeptierst du die folgenden Regeln.'),
(1951, 'rules_summary', 'hu', '<strong>Röviden:</strong> légy tiszteletteljes, ne tölts fel illegális / jogsértő tartalmat, ne csalj a pontokkal, és csak akkor használd a platformot, ha betöltötted a %d. életéved.'),
(1952, 'rules_summary', 'en', '<strong>In brief:</strong> be respectful, do not upload illegal or infringing content, do not cheat with points, and only use the platform if you are at least %d years old.'),
(1953, 'rules_summary', 'de', '<strong>Kurz gesagt:</strong> Sei respektvoll, lade keine illegalen oder rechtswidrigen Inhalte hoch, betrüge nicht bei den Punkten, und nutze die Plattform nur, wenn du mindestens %d Jahre alt bist.'),
(1954, 'rules_s1_title', 'hu', '1) Korhatár és jogosultság'),
(1955, 'rules_s1_title', 'en', '1) Age requirement and eligibility'),
(1956, 'rules_s1_title', 'de', '1) Altersbeschränkung und Berechtigung'),
(1957, 'rules_s1_li1', 'hu', 'A Jegyzetár használatához minimum <strong>%d éves</strong> életkor szükséges.'),
(1958, 'rules_s1_li1', 'en', 'Using Jegyzetár requires a minimum age of <strong>%d years</strong>.'),
(1959, 'rules_s1_li1', 'de', 'Die Nutzung von Jegyzetár erfordert ein Mindestalter von <strong>%d Jahren</strong>.'),
(1960, 'rules_s1_li2', 'hu', 'Regisztrációkor valós adatokat adj meg (különösen a születési dátumot).'),
(1961, 'rules_s1_li2', 'en', 'Provide accurate information when registering (especially your date of birth).'),
(1962, 'rules_s1_li2', 'de', 'Gib bei der Registrierung echte Daten an (insbesondere das Geburtsdatum).'),
(1963, 'rules_s1_li3', 'hu', 'Ha kiderül, hogy a felhasználó nem érte el a korhatárt, a fiókot <strong>korlátozhatjuk vagy törölhetjük</strong>.'),
(1964, 'rules_s1_li3', 'en', 'If it turns out a user has not reached the minimum age, the account may be <strong>restricted or deleted</strong>.'),
(1965, 'rules_s1_li3', 'de', 'Wenn sich herausstellt, dass ein Nutzer das Mindestalter nicht erreicht hat, kann das Konto <strong>eingeschränkt oder gelöscht</strong> werden.'),
(1966, 'rules_s2_title', 'hu', '2) Fiókbiztonság és hozzáférés'),
(1967, 'rules_s2_title', 'en', '2) Account security and access'),
(1968, 'rules_s2_title', 'de', '2) Kontosicherheit und Zugang'),
(1969, 'rules_s2_li1', 'hu', 'A fiókodért te felelsz: jelszót, 2FA-t (ha van) ne oszd meg másokkal.'),
(1970, 'rules_s2_li1', 'en', 'You are responsible for your account: do not share your password or 2FA (if applicable) with others.'),
(1971, 'rules_s2_li1', 'de', 'Du bist für dein Konto verantwortlich: Teile dein Passwort oder 2FA (falls vorhanden) nicht mit anderen.'),
(1972, 'rules_s2_li2', 'hu', 'Tilos más fiókjába belépni, vagy erre kísérletet tenni.'),
(1973, 'rules_s2_li2', 'en', 'Accessing or attempting to access someone else\'s account is prohibited.'),
(1974, 'rules_s2_li2', 'de', 'Der Zugang zu einem fremden Konto oder der Versuch dazu ist verboten.'),
(1975, 'rules_s2_li3', 'hu', 'Gyanús tevékenységet jelents a platformon belül vagy az adminoknak.'),
(1976, 'rules_s2_li3', 'en', 'Report suspicious activity within the platform or to the admins.'),
(1977, 'rules_s2_li3', 'de', 'Melde verdächtige Aktivitäten innerhalb der Plattform oder an die Admins.'),
(1978, 'rules_s3_title', 'hu', '3) Engedélyezett és tiltott tartalmak'),
(1979, 'rules_s3_title', 'en', '3) Allowed and prohibited content'),
(1980, 'rules_s3_title', 'de', '3) Erlaubte und verbotene Inhalte'),
(1981, 'rules_s3_intro', 'hu', 'A Jegyzetár célja az <strong>oktatási jellegű</strong> jegyzetek megosztása. Ennek megfelelően:'),
(1982, 'rules_s3_intro', 'en', 'The purpose of Jegyzetár is to share <strong>educational</strong> notes. Accordingly:'),
(1983, 'rules_s3_intro', 'de', 'Der Zweck von Jegyzetár ist das Teilen von <strong>Bildungsnotizen</strong>. Dementsprechend:'),
(1984, 'rules_s3_li1', 'hu', '<strong>Engedélyezett:</strong> saját készítésű jegyzet, összefoglaló, kidolgozott tétel, gyakorló feladat (jogszerűen).'),
(1985, 'rules_s3_li1', 'en', '<strong>Allowed:</strong> self-made notes, summaries, worked exam questions, practice exercises (lawfully).'),
(1986, 'rules_s3_li1', 'de', '<strong>Erlaubt:</strong> selbst erstellte Notizen, Zusammenfassungen, ausgearbeitete Prüfungsthemen, Übungsaufgaben (rechtmäßig).'),
(1987, 'rules_s3_li2', 'hu', '<strong>Tiltott:</strong> gyűlöletkeltő, zaklató, pornográf, erőszakos, önsértésre buzdító, vagy bármilyen jogellenes tartalom.'),
(1988, 'rules_s3_li2', 'en', '<strong>Prohibited:</strong> hate-inciting, harassing, pornographic, violent, self-harm-promoting or any other illegal content.'),
(1989, 'rules_s3_li2', 'de', '<strong>Verboten:</strong> hassanstachelnde, belästigende, pornografische, gewalttätige, selbstverletzungsfördernde oder sonstige rechtswidrige Inhalte.'),
(1990, 'rules_s3_li3', 'hu', '<strong>Tiltott:</strong> személyes adatok közzététele (pl. telefonszám, lakcím, mások e-mailje, osztálylista, igazolvány).'),
(1991, 'rules_s3_li3', 'en', '<strong>Prohibited:</strong> publishing personal data (e.g. phone number, home address, others\' email, class list, ID card).'),
(1992, 'rules_s3_li3', 'de', '<strong>Verboten:</strong> Veröffentlichung personenbezogener Daten (z. B. Telefonnummer, Wohnadresse, E-Mail anderer, Klassenliste, Ausweis).'),
(1993, 'rules_s3_li4', 'hu', '<strong>Tiltott:</strong> vírusos / kártékony fájlok, linkek, adathalászat.'),
(1994, 'rules_s3_li4', 'en', '<strong>Prohibited:</strong> virus/malware files, links, phishing.'),
(1995, 'rules_s3_li4', 'de', '<strong>Verboten:</strong> Viren-/Schadprogrammdateien, Links, Phishing.'),
(1996, 'rules_s4_title', 'hu', '4) Szerzői jog és forrásmegjelölés'),
(1997, 'rules_s4_title', 'en', '4) Copyright and attribution'),
(1998, 'rules_s4_title', 'de', '4) Urheberrecht und Quellenangabe'),
(1999, 'rules_s4_li1', 'hu', 'Csak olyan anyagot tölts fel, aminek a megosztására <strong>jogosult vagy</strong> (saját anyag, vagy engedéllyel / szabad licenc alatt).'),
(2000, 'rules_s4_li1', 'en', 'Only upload material you are <strong>authorised to share</strong> (your own work, or with permission / under a free licence).'),
(2001, 'rules_s4_li1', 'de', 'Lade nur Material hoch, zu dessen Weitergabe du <strong>berechtigt bist</strong> (eigenes Material oder mit Erlaubnis / unter freier Lizenz).'),
(2002, 'rules_s4_li2', 'hu', 'Tilos tankönyvek, fizetős kurzusanyagok, megvásárolt PDF-ek, zárt rendszerekből származó anyagok teljes terjedelmű feltöltése.'),
(2003, 'rules_s4_li2', 'en', 'It is forbidden to upload textbooks, paid course materials, purchased PDFs or materials from closed systems in their entirety.'),
(2004, 'rules_s4_li2', 'de', 'Das vollständige Hochladen von Lehrbüchern, kostenpflichtigen Kursmaterialien, gekauften PDFs oder Materialien aus geschlossenen Systemen ist verboten.'),
(2005, 'rules_s4_li3', 'hu', 'Ha hivatkozol más forrásra, jelöld meg (pl. könyv címe, szerző, link, év).'),
(2006, 'rules_s4_li3', 'en', 'If you reference another source, cite it (e.g. book title, author, link, year).'),
(2007, 'rules_s4_li3', 'de', 'Wenn du auf eine andere Quelle verweist, gib sie an (z. B. Buchtitel, Autor, Link, Jahr).'),
(2008, 'rules_s4_note', 'hu', '<strong>Fontos:</strong> jogsértő anyag esetén a tartalmat eltávolíthatjuk, és ismételt esetben a fiókot korlátozhatjuk.'),
(2009, 'rules_s4_note', 'en', '<strong>Important:</strong> in case of infringing material we may remove the content, and in repeated cases restrict the account.'),
(2010, 'rules_s4_note', 'de', '<strong>Wichtig:</strong> Bei rechtswidrigen Materialien können wir den Inhalt entfernen und im Wiederholungsfall das Konto einschränken.'),
(2011, 'rules_s5_title', 'hu', '5) Közösségi viselkedés (kommentek, értékelések)'),
(2012, 'rules_s5_title', 'en', '5) Community conduct (comments, ratings)'),
(2013, 'rules_s5_title', 'de', '5) Gemeinschaftsverhalten (Kommentare, Bewertungen)'),
(2014, 'rules_s5_li1', 'hu', 'Légy kulturált: tilos a személyeskedés, zaklatás, fenyegetés, sértegetés.'),
(2015, 'rules_s5_li1', 'en', 'Be civil: personal attacks, harassment, threats and insults are prohibited.'),
(2016, 'rules_s5_li1', 'de', 'Sei höflich: persönliche Angriffe, Belästigung, Drohungen und Beleidigungen sind verboten.'),
(2017, 'rules_s5_li2', 'hu', 'Ne spam-elj (ismétlődő kommentek, reklám, értelmetlen tartalom).'),
(2018, 'rules_s5_li2', 'en', 'Do not spam (repeated comments, advertising, meaningless content).'),
(2019, 'rules_s5_li2', 'de', 'Kein Spam (wiederholte Kommentare, Werbung, sinnloser Inhalt).'),
(2020, 'rules_s5_li3', 'hu', 'Az értékelés legyen őszinte és releváns (nem bosszúból / haveri alapon).'),
(2021, 'rules_s5_li3', 'en', 'Ratings should be honest and relevant (not out of spite or favouritism).'),
(2022, 'rules_s5_li3', 'de', 'Bewertungen sollten ehrlich und relevant sein (nicht aus Rache oder Gefälligkeit).'),
(2023, 'rules_s6_title', 'hu', '6) Pontok, badge-ek és visszaélések'),
(2024, 'rules_s6_title', 'en', '6) Points, badges and abuse'),
(2025, 'rules_s6_title', 'de', '6) Punkte, Abzeichen und Missbrauch'),
(2026, 'rules_s6_li1', 'hu', 'Tilos a pontokkal való manipuláció (pl. tömeges kamu fiókok, egymás mesterséges felpontozása).'),
(2027, 'rules_s6_li1', 'en', 'Manipulating points is prohibited (e.g. mass fake accounts, artificially boosting each other).'),
(2028, 'rules_s6_li1', 'de', 'Die Manipulation von Punkten ist verboten (z. B. Massenkonten, gegenseitiges künstliches Aufwerten).'),
(2029, 'rules_s6_li2', 'hu', 'Tilos automatizált eszközök használata (botok, scriptelt letöltések/feltöltések) a rendszer kijátszására.'),
(2030, 'rules_s6_li2', 'en', 'Using automated tools (bots, scripted downloads/uploads) to game the system is prohibited.'),
(2031, 'rules_s6_li2', 'de', 'Die Verwendung automatisierter Tools (Bots, gescriptete Downloads/Uploads) zur Systemumgehung ist verboten.'),
(2032, 'rules_s6_li3', 'hu', 'Visszaélés esetén pontlevonás, badge visszavonás, ideiglenes vagy végleges tiltás alkalmazható.'),
(2033, 'rules_s6_li3', 'en', 'In case of abuse, point deductions, badge revocations, temporary or permanent bans may be applied.'),
(2034, 'rules_s6_li3', 'de', 'Bei Missbrauch können Punktabzüge, Abzeichenentzug sowie temporäre oder permanente Sperren verhängt werden.'),
(2035, 'rules_s7_title', 'hu', '7) Moderáció és jelentések'),
(2036, 'rules_s7_title', 'en', '7) Moderation and reports'),
(2037, 'rules_s7_title', 'de', '7) Moderation und Meldungen'),
(2038, 'rules_s7_li1', 'hu', 'A moderátorok/adminok eltávolíthatnak tartalmat, ha az sérti a szabályzatot.'),
(2039, 'rules_s7_li1', 'en', 'Moderators/admins may remove content that violates the rules.'),
(2040, 'rules_s7_li1', 'de', 'Moderatoren/Admins können Inhalte entfernen, die gegen die Regeln verstoßen.'),
(2041, 'rules_s7_li2', 'hu', 'Ha szabályszegést látsz, jelentsd (pl. jegyzet oldalon / kommentnél).'),
(2042, 'rules_s7_li2', 'en', 'If you see a rule violation, report it (e.g. on the note page or in comments).'),
(2043, 'rules_s7_li2', 'de', 'Wenn du einen Regelverstoß siehst, melde ihn (z. B. auf der Notizseite oder bei Kommentaren).'),
(2044, 'rules_s7_li3', 'hu', 'Ismételt vagy súlyos szabályszegés fióktiltással járhat.'),
(2045, 'rules_s7_li3', 'en', 'Repeated or serious rule violations may result in an account ban.'),
(2046, 'rules_s7_li3', 'de', 'Wiederholte oder schwerwiegende Regelverstöße können zu einer Kontosperrung führen.'),
(2047, 'rules_s8_title', 'hu', '8) Prémium funkciók és fizetés (ha bevezetésre kerül)'),
(2048, 'rules_s8_title', 'en', '8) Premium features and payment (if introduced)'),
(2049, 'rules_s8_title', 'de', '8) Premium-Funktionen und Zahlung (falls eingeführt)'),
(2050, 'rules_s8_li1', 'hu', 'A prémium célja: extra kényelmi funkciók (offline, statisztikák, reklámmentesség, AI-funkciók).'),
(2051, 'rules_s8_li1', 'en', 'The purpose of premium: extra convenience features (offline, statistics, ad-free, AI features).'),
(2052, 'rules_s8_li1', 'de', 'Zweck von Premium: zusätzliche Komfortfunktionen (Offline, Statistiken, Werbefreiheit, KI-Funktionen).'),
(2053, 'rules_s8_li2', 'hu', 'A vásárlás/fizetés részleteit külön ÁSZF / előfizetési feltételek szabályozhatják.'),
(2054, 'rules_s8_li2', 'en', 'The details of purchases/payments may be governed by separate Terms of Service / subscription terms.'),
(2055, 'rules_s8_li2', 'de', 'Die Details zu Käufen/Zahlungen können durch separate Nutzungsbedingungen / Abonnementbedingungen geregelt werden.'),
(2056, 'rules_s9_title', 'hu', '9) Adatvédelem és személyes adatok'),
(2057, 'rules_s9_title', 'en', '9) Privacy and personal data'),
(2058, 'rules_s9_title', 'de', '9) Datenschutz und personenbezogene Daten'),
(2059, 'rules_s9_intro', 'hu', 'A személyes adatok kezeléséről az <strong><a href=\"privacy.php\">Adatkezelési tájékoztató</a></strong> ad részletes információt.'),
(2060, 'rules_s9_intro', 'en', 'The <strong><a href=\"privacy.php\">Privacy Policy</a></strong> provides detailed information about the handling of personal data.'),
(2061, 'rules_s9_intro', 'de', 'Die <strong><a href=\"privacy.php\">Datenschutzerklärung</a></strong> enthält detaillierte Informationen zur Verarbeitung personenbezogener Daten.'),
(2062, 'rules_s9_li1', 'hu', 'Ne ossz meg mások személyes adatait.'),
(2063, 'rules_s9_li1', 'en', 'Do not share other people\'s personal data.'),
(2064, 'rules_s9_li1', 'de', 'Teile keine personenbezogenen Daten anderer Personen.'),
(2065, 'rules_s9_li2', 'hu', 'Ha 13-18 éves vagy, különösen figyelj arra, hogy mit töltesz fel (név, osztály, iskola, arc a dokumentumon stb.).'),
(2066, 'rules_s9_li2', 'en', 'If you are 13-18 years old, pay special attention to what you upload (name, class, school, face in a document, etc.).'),
(2067, 'rules_s9_li2', 'de', 'Wenn du 13-18 Jahre alt bist, achte besonders darauf, was du hochlädst (Name, Klasse, Schule, Gesicht in einem Dokument usw.).'),
(2068, 'rules_s10_title', 'hu', '10) Szabályzat módosítása'),
(2069, 'rules_s10_title', 'en', '10) Changes to these rules'),
(2070, 'rules_s10_title', 'de', '10) Änderungen der Regeln'),
(2071, 'rules_s10_p', 'hu', 'A szabályzatot időnként frissíthetjük (funkcióbővítés, jogi megfelelés, biztonság). A változások a közzétételtől érvényesek.'),
(2072, 'rules_s10_p', 'en', 'We may update these rules from time to time (feature additions, legal compliance, security). Changes take effect from the date of publication.'),
(2073, 'rules_s10_p', 'de', 'Wir können diese Regeln von Zeit zu Zeit aktualisieren (Funktionserweiterungen, Rechtseinhaltung, Sicherheit). Änderungen treten ab dem Veröffentlichungsdatum in Kraft.'),
(2074, 'rules_footer_note', 'hu', 'Ha kérdésed van, írj az adminoknak / projektcsapatnak a platformon megadott elérhetőségen.'),
(2075, 'rules_footer_note', 'en', 'If you have a question, write to the admins / project team via the contact details provided on the platform.'),
(2076, 'rules_footer_note', 'de', 'Bei Fragen wende dich an die Admins / das Projektteam über die auf der Plattform angegebenen Kontaktdaten.'),
(2077, 'terms_title', 'hu', 'Felhasználási feltételek - Jegyzetár'),
(2078, 'terms_title', 'en', 'Terms of Service - Jegyzetár'),
(2079, 'terms_title', 'de', 'Nutzungsbedingungen - Jegyzetár'),
(2080, 'terms_h1', 'hu', 'Felhasználási feltételek'),
(2081, 'terms_h1', 'en', 'Terms of Service'),
(2082, 'terms_h1', 'de', 'Nutzungsbedingungen'),
(2083, 'terms_effective', 'hu', 'Hatályos: 2025. január 1-től'),
(2084, 'terms_effective', 'en', 'Effective: from 1 January 2025'),
(2085, 'terms_effective', 'de', 'Gültig: ab 1. Januar 2025'),
(2086, 'terms_s1_title', 'hu', '1. Általános információk'),
(2087, 'terms_s1_title', 'en', '1. General information'),
(2088, 'terms_s1_title', 'de', '1. Allgemeine Informationen'),
(2089, 'terms_s1_p1', 'hu', 'A Jegyzetár (\"Szolgáltatás\") egy közösségi alapú platform, amely lehetőséget biztosít jegyzetek, tananyagok és egyéb oktatási tartalmak megosztására.'),
(2090, 'terms_s1_p1', 'en', 'Jegyzetár (\"Service\") is a community-based platform that enables the sharing of notes, study materials and other educational content.'),
(2091, 'terms_s1_p1', 'de', 'Jegyzetár (\"Dienst\") ist eine gemeinschaftsbasierte Plattform, die das Teilen von Notizen, Lernmaterialien und anderen Bildungsinhalten ermöglicht.'),
(2092, 'terms_s1_p2', 'hu', 'A Szolgáltatás használatával a felhasználó elfogadja jelen Felhasználási feltételeket.'),
(2093, 'terms_s1_p2', 'en', 'By using the Service the user accepts these Terms of Service.'),
(2094, 'terms_s1_p2', 'de', 'Durch die Nutzung des Dienstes akzeptiert der Nutzer diese Nutzungsbedingungen.'),
(2095, 'terms_s2_title', 'hu', '2. Felhasználói fiók'),
(2096, 'terms_s2_title', 'en', '2. User account'),
(2097, 'terms_s2_title', 'de', '2. Benutzerkonto'),
(2098, 'terms_s2_li1', 'hu', 'A Szolgáltatás egyes funkciói regisztrációhoz kötöttek.'),
(2099, 'terms_s2_li1', 'en', 'Certain features of the Service require registration.'),
(2100, 'terms_s2_li1', 'de', 'Bestimmte Funktionen des Dienstes erfordern eine Registrierung.'),
(2101, 'terms_s2_li2', 'hu', 'A felhasználó köteles valós adatokat megadni.'),
(2102, 'terms_s2_li2', 'en', 'The user is required to provide accurate information.'),
(2103, 'terms_s2_li2', 'de', 'Der Nutzer ist verpflichtet, korrekte Angaben zu machen.'),
(2104, 'terms_s2_li3', 'hu', 'A fiók biztonságáért a felhasználó felelős.'),
(2105, 'terms_s2_li3', 'en', 'The user is responsible for the security of their account.'),
(2106, 'terms_s2_li3', 'de', 'Der Nutzer ist für die Sicherheit seines Kontos verantwortlich.'),
(2107, 'terms_s3_title', 'hu', '3. Feltöltött tartalmak'),
(2108, 'terms_s3_title', 'en', '3. Uploaded content'),
(2109, 'terms_s3_title', 'de', '3. Hochgeladene Inhalte'),
(2110, 'terms_s3_p1', 'hu', 'A felhasználó által feltöltött fájlokért és azok jogtisztaságáért kizárólag a feltöltő felel.'),
(2111, 'terms_s3_p1', 'en', 'The uploader is solely responsible for files uploaded by the user and their legal compliance.'),
(2112, 'terms_s3_p1', 'de', 'Der Uploader ist allein verantwortlich für die hochgeladenen Dateien und deren rechtliche Zulässigkeit.'),
(2113, 'terms_s3_li1', 'hu', 'Tilos szerzői jogot sértő tartalom feltöltése.'),
(2114, 'terms_s3_li1', 'en', 'Uploading copyright-infringing content is prohibited.'),
(2115, 'terms_s3_li1', 'de', 'Das Hochladen urheberrechtsverletzender Inhalte ist verboten.'),
(2116, 'terms_s3_li2', 'hu', 'Tilos jogellenes, sértő vagy megtévesztő tartalom közzététele.'),
(2117, 'terms_s3_li2', 'en', 'Publishing illegal, offensive or misleading content is prohibited.'),
(2118, 'terms_s3_li2', 'de', 'Die Veröffentlichung illegaler, beleidigender oder irreführender Inhalte ist verboten.'),
(2119, 'terms_s3_li3', 'hu', 'A Szolgáltató jogosult a szabályokat sértő tartalmak eltávolítására.'),
(2120, 'terms_s3_li3', 'en', 'The Service Provider is entitled to remove content that violates the rules.'),
(2121, 'terms_s3_li3', 'de', 'Der Dienstanbieter ist berechtigt, regelwidrige Inhalte zu entfernen.'),
(2122, 'terms_s4_title', 'hu', '4. Kedvencek, értékelések, közösségi funkciók'),
(2123, 'terms_s4_title', 'en', '4. Favourites, ratings, community features'),
(2124, 'terms_s4_title', 'de', '4. Favoriten, Bewertungen, Community-Funktionen'),
(2125, 'terms_s4_p1', 'hu', 'A közösségi funkciók (kedvencek, értékelések, kommentek stb.) kizárólag rendeltetésszerűen használhatók.'),
(2126, 'terms_s4_p1', 'en', 'Community features (favourites, ratings, comments, etc.) may only be used for their intended purpose.'),
(2127, 'terms_s4_p1', 'de', 'Community-Funktionen (Favoriten, Bewertungen, Kommentare usw.) dürfen nur bestimmungsgemäß verwendet werden.'),
(2128, 'terms_s5_title', 'hu', '5. Moderáció és fiók felfüggesztés'),
(2129, 'terms_s5_title', 'en', '5. Moderation and account suspension'),
(2130, 'terms_s5_title', 'de', '5. Moderation und Kontosperrung'),
(2131, 'terms_s5_p1', 'hu', 'A Szolgáltató fenntartja a jogot, hogy:'),
(2132, 'terms_s5_p1', 'en', 'The Service Provider reserves the right to:'),
(2133, 'terms_s5_p1', 'de', 'Der Dienstanbieter behält sich das Recht vor:'),
(2134, 'terms_s5_li1', 'hu', 'figyelmeztetést adjon'),
(2135, 'terms_s5_li1', 'en', 'issue a warning'),
(2136, 'terms_s5_li1', 'de', 'eine Verwarnung auszusprechen'),
(2137, 'terms_s5_li2', 'hu', 'tartalmat eltávolítson'),
(2138, 'terms_s5_li2', 'en', 'remove content'),
(2139, 'terms_s5_li2', 'de', 'Inhalte zu entfernen'),
(2140, 'terms_s5_li3', 'hu', 'felhasználói fiókot ideiglenesen vagy véglegesen felfüggesszen'),
(2141, 'terms_s5_li3', 'en', 'temporarily or permanently suspend a user account'),
(2142, 'terms_s5_li3', 'de', 'ein Benutzerkonto vorübergehend oder dauerhaft zu sperren'),
(2143, 'terms_s6_title', 'hu', '6. Felelősség korlátozása'),
(2144, 'terms_s6_title', 'en', '6. Limitation of liability'),
(2145, 'terms_s6_title', 'de', '6. Haftungsbeschränkung'),
(2146, 'terms_s6_p1', 'hu', 'A Szolgáltatás \"ahogy van\" alapon működik. A Szolgáltató nem vállal felelősséget az esetleges adatvesztésért, hibákért vagy szolgáltatáskimaradásokért.'),
(2147, 'terms_s6_p1', 'en', 'The Service operates on an \"as is\" basis. The Service Provider accepts no liability for any data loss, errors or service outages.'),
(2148, 'terms_s6_p1', 'de', 'Der Dienst wird \"wie besehen\" bereitgestellt. Der Dienstanbieter übernimmt keine Haftung für etwaige Datenverluste, Fehler oder Dienstausfälle.'),
(2149, 'terms_s7_title', 'hu', '7. Adatkezelés'),
(2150, 'terms_s7_title', 'en', '7. Data processing'),
(2151, 'terms_s7_title', 'de', '7. Datenverarbeitung'),
(2152, 'terms_s7_p1', 'hu', 'A személyes adatok kezelésére az <a href=\"privacy.php\">Adatkezelési tájékoztató</a> vonatkozik.'),
(2153, 'terms_s7_p1', 'en', 'The <a href=\"privacy.php\">Privacy Policy</a> applies to the processing of personal data.'),
(2154, 'terms_s7_p1', 'de', 'Für die Verarbeitung personenbezogener Daten gilt die <a href=\"privacy.php\">Datenschutzerklärung</a>.'),
(2155, 'terms_s8_title', 'hu', '8. A feltételek módosítása'),
(2156, 'terms_s8_title', 'en', '8. Changes to these terms'),
(2157, 'terms_s8_title', 'de', '8. Änderungen dieser Bedingungen'),
(2158, 'terms_s8_p1', 'hu', 'A Szolgáltató jogosult jelen Felhasználási feltételeket módosítani. A módosítások a közzététellel lépnek hatályba.'),
(2159, 'terms_s8_p1', 'en', 'The Service Provider is entitled to modify these Terms of Service. Modifications take effect upon publication.'),
(2160, 'terms_s8_p1', 'de', 'Der Dienstanbieter ist berechtigt, diese Nutzungsbedingungen zu ändern. Änderungen treten mit der Veröffentlichung in Kraft.'),
(2161, 'terms_s9_title', 'hu', '9. Kapcsolat'),
(2162, 'terms_s9_title', 'en', '9. Contact'),
(2163, 'terms_s9_title', 'de', '9. Kontakt'),
(2164, 'terms_s9_p1', 'hu', 'Kapcsolatfelvétel:'),
(2165, 'terms_s9_p1', 'en', 'Contact:'),
(2166, 'terms_s9_p1', 'de', 'Kontakt:'),
(2167, 'terms_last_updated', 'hu', 'Utolsó frissítés: %s'),
(2168, 'terms_last_updated', 'en', 'Last updated: %s'),
(2169, 'terms_last_updated', 'de', 'Letzte Aktualisierung: %s'),
(2170, 'contact_title', 'hu', 'Kapcsolatfelvétel'),
(2171, 'contact_title', 'en', 'Contact us'),
(2172, 'contact_title', 'de', 'Kontakt'),
(2173, 'contact_h1', 'hu', 'Kapcsolatfelvétel'),
(2174, 'contact_h1', 'en', 'Contact us'),
(2175, 'contact_h1', 'de', 'Kontakt'),
(2176, 'contact_sub', 'hu', 'Van kérdésed vagy javaslat? Írj nekünk!'),
(2177, 'contact_sub', 'en', 'Got a question or suggestion? Write to us!'),
(2178, 'contact_sub', 'de', 'Hast du eine Frage oder einen Vorschlag? Schreib uns!'),
(2179, 'contact_label_name', 'hu', 'Név *'),
(2180, 'contact_label_name', 'en', 'Name *'),
(2181, 'contact_label_name', 'de', 'Name *'),
(2182, 'contact_placeholder_name', 'hu', 'Teljes neved'),
(2183, 'contact_placeholder_name', 'en', 'Your full name'),
(2184, 'contact_placeholder_name', 'de', 'Dein vollständiger Name'),
(2185, 'contact_label_email', 'hu', 'Email cím *'),
(2186, 'contact_label_email', 'en', 'Email address *'),
(2187, 'contact_label_email', 'de', 'E-Mail-Adresse *'),
(2188, 'contact_label_subject', 'hu', 'Tárgy *'),
(2189, 'contact_label_subject', 'en', 'Subject *'),
(2190, 'contact_label_subject', 'de', 'Betreff *'),
(2191, 'contact_placeholder_subject', 'hu', 'Miről szeretnél írni?');
INSERT INTO `translations` (`id`, `t_key`, `lang_code`, `text`) VALUES
(2192, 'contact_placeholder_subject', 'en', 'What would you like to write about?'),
(2193, 'contact_placeholder_subject', 'de', 'Worüber möchtest du schreiben?'),
(2194, 'contact_label_message', 'hu', 'Üzenet *'),
(2195, 'contact_label_message', 'en', 'Message *'),
(2196, 'contact_label_message', 'de', 'Nachricht *'),
(2197, 'contact_placeholder_message', 'hu', 'Írd ide az üzenedet...'),
(2198, 'contact_placeholder_message', 'en', 'Write your message here...'),
(2199, 'contact_placeholder_message', 'de', 'Schreib hier deine Nachricht...'),
(2200, 'contact_hint_chars', 'hu', 'Minimum 10 karakter, maximum 5000.'),
(2201, 'contact_hint_chars', 'en', 'Minimum 10 characters, maximum 5000.'),
(2202, 'contact_hint_chars', 'de', 'Mindestens 10 Zeichen, maximal 5000.'),
(2203, 'contact_btn_send', 'hu', 'Üzenet küldése'),
(2204, 'contact_btn_send', 'en', 'Send message'),
(2205, 'contact_btn_send', 'de', 'Nachricht senden'),
(2206, 'contact_hint_no_sensitive', 'hu', 'Ne adj meg jelszót vagy érzékeny adatot.'),
(2207, 'contact_hint_no_sensitive', 'en', 'Do not enter your password or sensitive data.'),
(2208, 'contact_hint_no_sensitive', 'de', 'Gib kein Passwort oder sensible Daten ein.'),
(2209, 'contact_card_email_title', 'hu', 'E-mail'),
(2210, 'contact_card_email_title', 'en', 'E-mail'),
(2211, 'contact_card_email_title', 'de', 'E-Mail'),
(2212, 'contact_card_discord_title', 'hu', 'Discord'),
(2213, 'contact_card_discord_title', 'en', 'Discord'),
(2214, 'contact_card_discord_title', 'de', 'Discord'),
(2215, 'contact_card_discord_text', 'hu', 'Discord szerverünket a Közösség menüpont alatt találod, csatlakozz és írj nekünk ott is!'),
(2216, 'contact_card_discord_text', 'en', 'Find our Discord server under the Community menu, join and write to us there too!'),
(2217, 'contact_card_discord_text', 'de', 'Unser Discord-Server befindet sich im Menü \"Community\". Tritt bei und schreib uns auch dort!'),
(2218, 'privacy_title', 'hu', 'Adatkezelés'),
(2219, 'privacy_title', 'en', 'Privacy'),
(2220, 'privacy_title', 'de', 'Datenschutz'),
(2221, 'privacy_h1', 'hu', 'Adatvédelmi Tájékoztató'),
(2222, 'privacy_h1', 'en', 'Privacy Policy'),
(2223, 'privacy_h1', 'de', 'Datenschutzerklärung'),
(2224, 'privacy_effective', 'hu', 'hatálybalépés:'),
(2225, 'privacy_effective', 'en', 'effective from:'),
(2226, 'privacy_effective', 'de', 'gültig ab:'),
(2227, 'privacy_intro', 'hu', 'Ez a tájékoztató azt írja le, hogyan kezeljük a személyes adatokat a %s weboldal és szolgáltatás használata során.'),
(2228, 'privacy_intro', 'en', 'This policy describes how we handle personal data when you use the %s website and service.'),
(2229, 'privacy_intro', 'de', 'Diese Erklärung beschreibt, wie wir personenbezogene Daten bei der Nutzung der %s-Website und des Dienstes verarbeiten.'),
(2230, 'privacy_s0_title', 'hu', '0. Rövid összefoglalás'),
(2231, 'privacy_s0_title', 'en', '0. Brief summary'),
(2232, 'privacy_s0_title', 'de', '0. Kurzzusammenfassung'),
(2233, 'privacy_s0_li1_notes', 'hu', ', és a jegyzetek tartalma'),
(2234, 'privacy_s0_li1_notes', 'en', ', and the content of notes'),
(2235, 'privacy_s0_li1_notes', 'de', ', und der Inhalt der Notizen'),
(2236, 'privacy_s0_li1', 'hu', 'A %s a szolgáltatás működtetéséhez szükséges adatokat kezeli (pl. fiókadatok, technikai naplók%s).'),
(2237, 'privacy_s0_li1', 'en', '%s processes only the data necessary to operate the service (e.g. account data, technical logs%s).'),
(2238, 'privacy_s0_li1', 'de', '%s verarbeitet nur die zur Erbringung des Dienstes notwendigen Daten (z. B. Kontodaten, technische Logs%s).'),
(2239, 'privacy_s0_li2', 'hu', 'Csak olyan adatot kérünk, ami a működéshez kell; nem értékesítünk személyes adatokat.'),
(2240, 'privacy_s0_li2', 'en', 'We only ask for data that is necessary for operation; we do not sell personal data.'),
(2241, 'privacy_s0_li2', 'de', 'Wir fragen nur nach Daten, die für den Betrieb notwendig sind; wir verkaufen keine personenbezogenen Daten.'),
(2242, 'privacy_s0_li3_cookies', 'hu', 'A weboldal munkamenet (session) cookie-kat használhat a belépés és a biztonság érdekében.'),
(2243, 'privacy_s0_li3_cookies', 'en', 'The website may use session cookies for login and security purposes.'),
(2244, 'privacy_s0_li3_cookies', 'de', 'Die Website kann Sitzungs-Cookies für Anmeldung und Sicherheit verwenden.'),
(2245, 'privacy_s0_li3_nocookies', 'hu', 'A weboldal nem használ a működéshez szükséges cookie-kon túlmutató sütiket.'),
(2246, 'privacy_s0_li3_nocookies', 'en', 'The website does not use cookies beyond those necessary for operation.'),
(2247, 'privacy_s0_li3_nocookies', 'de', 'Die Website verwendet keine Cookies, die über die für den Betrieb notwendigen hinausgehen.'),
(2248, 'privacy_s0_li4', 'hu', 'Hibabejelentéskor / kapcsolatfelvételkor te döntöd el, mit osztasz meg velünk; ezeket a probléma rendezése után töröljük vagy anonimizáljuk.'),
(2249, 'privacy_s0_li4', 'en', 'When submitting a bug report or getting in touch, you decide what you share with us; we delete or anonymise this after the issue is resolved.'),
(2250, 'privacy_s0_li4', 'de', 'Bei einer Fehlermeldung oder Kontaktaufnahme entscheidest du, was du mit uns teilst; wir löschen oder anonymisieren dies nach der Problemlösung.'),
(2251, 'privacy_s0_li5', 'hu', 'Adatvédelmi kérdésben a következő címen érsz el minket:'),
(2252, 'privacy_s0_li5', 'en', 'For privacy questions you can reach us at:'),
(2253, 'privacy_s0_li5', 'de', 'Bei Datenschutzfragen erreichst du uns unter:'),
(2254, 'privacy_s1_title', 'hu', '1. Bevezetés'),
(2255, 'privacy_s1_title', 'en', '1. Introduction'),
(2256, 'privacy_s1_title', 'de', '1. Einleitung'),
(2257, 'privacy_s1_p', 'hu', 'A %s (a továbbiakban: \"szolgáltatás\") célja, hogy jegyzetek készítését, rendszerezését és kezelését tegye lehetővé. Ez a tájékoztató bemutatja, milyen adatokat kezelünk, milyen célból, mennyi ideig, és milyen jogaid vannak.'),
(2258, 'privacy_s1_p', 'en', '%s (hereinafter \"service\") aims to enable the creation, organisation and management of notes. This policy explains what data we process, for what purpose, for how long, and what rights you have.'),
(2259, 'privacy_s1_p', 'de', '%s (im Folgenden \"Dienst\") hat zum Ziel, die Erstellung, Organisation und Verwaltung von Notizen zu ermöglichen. Diese Erklärung beschreibt, welche Daten wir verarbeiten, zu welchem Zweck, wie lange und welche Rechte du hast.'),
(2260, 'privacy_s2_title', 'hu', '2. Adatkezelő azonosítása'),
(2261, 'privacy_s2_title', 'en', '2. Data controller identification'),
(2262, 'privacy_s2_title', 'de', '2. Identifikation des Verantwortlichen'),
(2263, 'privacy_s2_controller', 'hu', 'Adatkezelő:'),
(2264, 'privacy_s2_controller', 'en', 'Data controller:'),
(2265, 'privacy_s2_controller', 'de', 'Verantwortlicher:'),
(2266, 'privacy_s2_contact', 'hu', 'Kapcsolattartó e-mail:'),
(2267, 'privacy_s2_contact', 'en', 'Contact e-mail:'),
(2268, 'privacy_s2_contact', 'de', 'Kontakt-E-Mail:'),
(2269, 'privacy_s3_title', 'hu', '3. Milyen adatokat kezelünk és honnan?'),
(2270, 'privacy_s3_title', 'en', '3. What data do we process and where from?'),
(2271, 'privacy_s3_title', 'de', '3. Welche Daten verarbeiten wir und woher?'),
(2272, 'privacy_s3_1_title', 'hu', '3.1. Fiókhoz kapcsolódó adatok'),
(2273, 'privacy_s3_1_title', 'en', '3.1. Account-related data'),
(2274, 'privacy_s3_1_title', 'de', '3.1. Kontobezogene Daten'),
(2275, 'privacy_s3_1_li1', 'hu', 'kötelezően megadott adatok: e-mail cím (és/vagy felhasználónév), jelszó <em>(jelszót csak titkosított/hash formában tárolunk)</em>'),
(2276, 'privacy_s3_1_li1', 'en', 'mandatory data: e-mail address (and/or username), password <em>(passwords are stored only in encrypted/hashed form)</em>'),
(2277, 'privacy_s3_1_li1', 'de', 'Pflichtangaben: E-Mail-Adresse (und/oder Benutzername), Passwort <em>(Passwörter werden nur verschlüsselt/gehasht gespeichert)</em>'),
(2278, 'privacy_s3_1_li2', 'hu', 'opcionális adatok: megjelenített név, profilkép, beállítások'),
(2279, 'privacy_s3_1_li2', 'en', 'optional data: display name, profile picture, settings'),
(2280, 'privacy_s3_1_li2', 'de', 'optionale Daten: Anzeigename, Profilbild, Einstellungen'),
(2281, 'privacy_s3_1_li3', 'hu', 'biztonsági adatok: belépési események, gyanús aktivitás technikai metaadatai'),
(2282, 'privacy_s3_1_li3', 'en', 'security data: login events, technical metadata of suspicious activity'),
(2283, 'privacy_s3_1_li3', 'de', 'Sicherheitsdaten: Anmeldeereignisse, technische Metadaten verdächtiger Aktivitäten'),
(2284, 'privacy_s3_2_title', 'hu', '3.2. Jegyzetek és tartalmak'),
(2285, 'privacy_s3_2_title', 'en', '3.2. Notes and content'),
(2286, 'privacy_s3_2_title', 'de', '3.2. Notizen und Inhalte'),
(2287, 'privacy_s3_2_li1', 'hu', 'a létrehozott jegyzetek tartalma (szöveg, címkék, mappák, csatolmányok - ha van)'),
(2288, 'privacy_s3_2_li1', 'en', 'the content of created notes (text, tags, folders, attachments - if any)'),
(2289, 'privacy_s3_2_li1', 'de', 'der Inhalt erstellter Notizen (Text, Tags, Ordner, Anhänge - falls vorhanden)'),
(2290, 'privacy_s3_2_li2', 'hu', 'a tartalmakhoz kapcsolódó metaadatok (létrehozás/módosítás ideje, tulajdonos felhasználó azonosítója)'),
(2291, 'privacy_s3_2_li2', 'en', 'metadata related to the content (creation/modification time, owner user ID)'),
(2292, 'privacy_s3_2_li2', 'de', 'mit dem Inhalt verbundene Metadaten (Erstellungs-/Änderungszeit, Eigentümer-Benutzer-ID)'),
(2293, 'privacy_s3_2_note', 'hu', 'Fontos: a jegyzetek tartalma személyes adatot is tartalmazhat attól függően, mit írsz bele. Kérjük, csak saját felelősségedre tölts fel érzékeny adatot.'),
(2294, 'privacy_s3_2_note', 'en', 'Important: the content of notes may contain personal data depending on what you write. Please only upload sensitive data at your own risk.'),
(2295, 'privacy_s3_2_note', 'de', 'Wichtig: Der Inhalt von Notizen kann je nach dem, was du schreibst, personenbezogene Daten enthalten. Bitte lade sensible Daten nur auf eigene Verantwortung hoch.'),
(2296, 'privacy_s3_3_title', 'hu', '3.3. Technikai adatok (automatikusan keletkeznek)'),
(2297, 'privacy_s3_3_title', 'en', '3.3. Technical data (generated automatically)'),
(2298, 'privacy_s3_3_title', 'de', '3.3. Technische Daten (werden automatisch erzeugt)'),
(2299, 'privacy_s3_3_li1', 'hu', 'IP-cím, dátum/idő, kért URL, HTTP státuszkód'),
(2300, 'privacy_s3_3_li1', 'en', 'IP address, date/time, requested URL, HTTP status code'),
(2301, 'privacy_s3_3_li1', 'de', 'IP-Adresse, Datum/Uhrzeit, angeforderte URL, HTTP-Statuscode'),
(2302, 'privacy_s3_3_li2', 'hu', 'User-Agent (böngésző és eszköz típus), nyelvi beállítás'),
(2303, 'privacy_s3_3_li2', 'en', 'User-Agent (browser and device type), language setting'),
(2304, 'privacy_s3_3_li2', 'de', 'User-Agent (Browser- und Gerätetyp), Spracheinstellung'),
(2305, 'privacy_s3_3_li3_cookie', 'hu', 'munkamenet-azonosító (cookie-ban tárolva), biztonsági események'),
(2306, 'privacy_s3_3_li3_cookie', 'en', 'session ID (stored in a cookie), security events'),
(2307, 'privacy_s3_3_li3_cookie', 'de', 'Sitzungs-ID (in einem Cookie gespeichert), Sicherheitsereignisse'),
(2308, 'privacy_s3_3_li3_nocookie', 'hu', 'munkamenet-azonosító, biztonsági események'),
(2309, 'privacy_s3_3_li3_nocookie', 'en', 'session ID, security events'),
(2310, 'privacy_s3_3_li3_nocookie', 'de', 'Sitzungs-ID, Sicherheitsereignisse'),
(2311, 'privacy_s3_4_title', 'hu', '3.4. Kapcsolatfelvétel / hibabejelentés'),
(2312, 'privacy_s3_4_title', 'en', '3.4. Contact / bug reports'),
(2313, 'privacy_s3_4_title', 'de', '3.4. Kontaktaufnahme / Fehlermeldungen'),
(2314, 'privacy_s3_4_li1', 'hu', 'név (ha megadod), e-mail cím, üzenet tartalma'),
(2315, 'privacy_s3_4_li1', 'en', 'name (if provided), e-mail address, message content'),
(2316, 'privacy_s3_4_li1', 'de', 'Name (falls angegeben), E-Mail-Adresse, Nachrichteninhalt'),
(2317, 'privacy_s3_4_li2', 'hu', 'csatolmányok (pl. képernyőkép) - csak ha te feltöltöd'),
(2318, 'privacy_s3_4_li2', 'en', 'attachments (e.g. screenshot) - only if you upload them'),
(2319, 'privacy_s3_4_li2', 'de', 'Anhänge (z. B. Screenshot) - nur wenn du sie hochlädst'),
(2320, 'privacy_s4_title', 'hu', '4. Adataid tárolásának helye és biztonsága'),
(2321, 'privacy_s4_title', 'en', '4. Where and how your data is stored and secured'),
(2322, 'privacy_s4_title', 'de', '4. Speicherort und Sicherheit deiner Daten'),
(2323, 'privacy_s4_li1', 'hu', 'A szolgáltatás HTTPS kapcsolaton keresztül kommunikál.'),
(2324, 'privacy_s4_li1', 'en', 'The service communicates over HTTPS.'),
(2325, 'privacy_s4_li1', 'de', 'Der Dienst kommuniziert über HTTPS.'),
(2326, 'privacy_s4_li2', 'hu', 'Hozzáférés-vezérlést, naplózást és alapvető biztonsági intézkedéseket alkalmazunk a jogosulatlan hozzáférés ellen.'),
(2327, 'privacy_s4_li2', 'en', 'We apply access controls, logging and basic security measures against unauthorised access.'),
(2328, 'privacy_s4_li2', 'de', 'Wir setzen Zugangskontrolle, Protokollierung und grundlegende Sicherheitsmaßnahmen gegen unbefugten Zugriff ein.'),
(2329, 'privacy_s4_li3', 'hu', 'Jelszavakat nem olvasható formában (hash) tárolunk, és törekszünk az adatminimalizálásra.'),
(2330, 'privacy_s4_li3', 'en', 'Passwords are stored in non-readable form (hash), and we strive for data minimisation.'),
(2331, 'privacy_s4_li3', 'de', 'Passwörter werden in nicht lesbarer Form (Hash) gespeichert, und wir streben Datenminimierung an.'),
(2332, 'privacy_s4_note', 'hu', 'A pontos tárhely és infrastruktúra szolgáltatóidat (adatfeldolgozókat) a 7. pontban sorold fel.'),
(2333, 'privacy_s4_note', 'en', 'List your exact hosting and infrastructure providers (data processors) in section 7.'),
(2334, 'privacy_s4_note', 'de', 'Liste deine genauen Hosting- und Infrastrukturanbieter (Auftragsverarbeiter) in Abschnitt 7 auf.'),
(2335, 'privacy_s5_title', 'hu', '5. Az adatkezelés céljai és jogalapjai'),
(2336, 'privacy_s5_title', 'en', '5. Purposes and legal bases of data processing'),
(2337, 'privacy_s5_title', 'de', '5. Zwecke und Rechtsgrundlagen der Datenverarbeitung'),
(2338, 'privacy_s5_li1', 'hu', '<strong>A szolgáltatás nyújtása és a fiók működtetése</strong> - cél: beléptetés, jogosultságkezelés, alapfunkciók biztosítása. <strong>Jogalap:</strong> szerződés teljesítése (GDPR 6. cikk (1) b)).'),
(2339, 'privacy_s5_li1', 'en', '<strong>Providing the service and operating the account</strong> - purpose: login, permission management, basic functionality. <strong>Legal basis:</strong> performance of a contract (GDPR Art. 6(1)(b)).'),
(2340, 'privacy_s5_li1', 'de', '<strong>Erbringung des Dienstes und Kontobetrieb</strong> - Zweck: Anmeldung, Berechtigungsverwaltung, Grundfunktionen. <strong>Rechtsgrundlage:</strong> Vertragserfüllung (DSGVO Art. 6 Abs. 1 lit. b).'),
(2341, 'privacy_s5_li2', 'hu', '<strong>Jegyzetek tárolása és szinkronizálása</strong> - cél: a felhasználó által létrehozott tartalom elérhetővé tétele. <strong>Jogalap:</strong> szerződés teljesítése (GDPR 6. cikk (1) b)).'),
(2342, 'privacy_s5_li2', 'en', '<strong>Storing and syncing notes</strong> - purpose: making user-created content available. <strong>Legal basis:</strong> performance of a contract (GDPR Art. 6(1)(b)).'),
(2343, 'privacy_s5_li2', 'de', '<strong>Speicherung und Synchronisierung von Notizen</strong> - Zweck: Bereitstellung benutzererstellter Inhalte. <strong>Rechtsgrundlage:</strong> Vertragserfüllung (DSGVO Art. 6 Abs. 1 lit. b).'),
(2344, 'privacy_s5_li3', 'hu', '<strong>Biztonság és visszaélések megelőzése, technikai naplók</strong> - cél: hibakeresés, incidenskezelés, támadások kiszűrése. <strong>Jogalap:</strong> jogos érdek (GDPR 6. cikk (1) f)).'),
(2345, 'privacy_s5_li3', 'en', '<strong>Security and abuse prevention, technical logs</strong> - purpose: debugging, incident handling, filtering attacks. <strong>Legal basis:</strong> legitimate interest (GDPR Art. 6(1)(f)).'),
(2346, 'privacy_s5_li3', 'de', '<strong>Sicherheit und Missbrauchsprävention, technische Logs</strong> - Zweck: Fehlersuche, Vorfallsbearbeitung, Angriffsfilterung. <strong>Rechtsgrundlage:</strong> berechtigtes Interesse (DSGVO Art. 6 Abs. 1 lit. f).'),
(2347, 'privacy_s5_li4', 'hu', '<strong>Kapcsolatfelvétel / hibajegyek kezelése</strong> - cél: ügyfélszolgálat és problémamegoldás. <strong>Jogalap:</strong> hozzájárulás (GDPR 6. cikk (1) a)) vagy jogos érdek (GDPR 6. cikk (1) f)) a megkeresés jellegétől függően.'),
(2348, 'privacy_s5_li4', 'en', '<strong>Contact / ticket handling</strong> - purpose: customer support and problem solving. <strong>Legal basis:</strong> consent (GDPR Art. 6(1)(a)) or legitimate interest (GDPR Art. 6(1)(f)) depending on the nature of the enquiry.'),
(2349, 'privacy_s5_li4', 'de', '<strong>Kontaktaufnahme / Ticket-Verwaltung</strong> - Zweck: Kundenservice und Problemlösung. <strong>Rechtsgrundlage:</strong> Einwilligung (DSGVO Art. 6 Abs. 1 lit. a) oder berechtigtes Interesse (DSGVO Art. 6 Abs. 1 lit. f) je nach Art der Anfrage.'),
(2350, 'privacy_s6_title', 'hu', '6. Adatmegőrzési időtartam'),
(2351, 'privacy_s6_title', 'en', '6. Data retention period'),
(2352, 'privacy_s6_title', 'de', '6. Datenspeicherdauer'),
(2353, 'privacy_s6_li_account', 'hu', '<strong>Fiókadatok:</strong> a fiók fennállásáig; törlés kérésére ésszerű időn belül töröljük/anonymizáljuk, kivéve ha jogi kötelezettség mást ír elő.'),
(2354, 'privacy_s6_li_account', 'en', '<strong>Account data:</strong> for the duration of the account; deleted/anonymised within a reasonable time upon request, unless a legal obligation requires otherwise.'),
(2355, 'privacy_s6_li_account', 'de', '<strong>Kontodaten:</strong> für die Dauer des Kontos; auf Anfrage innerhalb einer angemessenen Frist gelöscht/anonymisiert, sofern keine gesetzliche Pflicht entgegensteht.'),
(2356, 'privacy_s6_li_notes', 'hu', '<strong>Jegyzetek tartalma:</strong> a felhasználó fiókjában a törlésig, illetve fióktörlésig.'),
(2357, 'privacy_s6_li_notes', 'en', '<strong>Note content:</strong> in the user account until deletion or account deletion.'),
(2358, 'privacy_s6_li_notes', 'de', '<strong>Notizinhalt:</strong> im Benutzerkonto bis zur Löschung oder Kontolöschung.'),
(2359, 'privacy_s6_li_logs', 'hu', '<strong>Technikai naplók:</strong> jellemzően legfeljebb 30-90 nap (biztonsági és hibakeresési célból), kivéve incidens vizsgálata esetén, amikor hosszabb megőrzés indokolt lehet.'),
(2360, 'privacy_s6_li_logs', 'en', '<strong>Technical logs:</strong> typically up to 30-90 days (for security and debugging), except in case of an incident investigation where longer retention may be justified.'),
(2361, 'privacy_s6_li_logs', 'de', '<strong>Technische Logs:</strong> in der Regel bis zu 30-90 Tage (zu Sicherheits- und Debugging-Zwecken), außer bei einer Vorfallsuntersuchung, bei der eine längere Aufbewahrung gerechtfertigt sein kann.'),
(2362, 'privacy_s6_li_contact', 'hu', '<strong>Kapcsolatfelvételi üzenetek / hibajegyek:</strong> a megkeresés lezárásáig, majd ésszerű időn belül törlésre kerülnek, kivéve ha a további megőrzés jogi igények miatt szükséges.'),
(2363, 'privacy_s6_li_contact', 'en', '<strong>Contact messages / tickets:</strong> until the enquiry is closed, then deleted within a reasonable time, unless further retention is necessary for legal claims.'),
(2364, 'privacy_s6_li_contact', 'de', '<strong>Kontaktnachrichten / Tickets:</strong> bis zur Schließung der Anfrage, dann innerhalb einer angemessenen Frist gelöscht, sofern keine weitere Aufbewahrung für rechtliche Ansprüche erforderlich ist.'),
(2365, 'privacy_s7_title', 'hu', '7. Adattovábbítás harmadik feleknek és nemzetközi adattovábbítás'),
(2366, 'privacy_s7_title', 'en', '7. Data transfers to third parties and international transfers'),
(2367, 'privacy_s7_title', 'de', '7. Datenweitergabe an Dritte und internationale Datenübermittlung'),
(2368, 'privacy_s7_p', 'hu', 'Személyes adatot nem adunk el. Adatot kizárólag a szolgáltatás működtetéséhez szükséges szolgáltatóknak továbbíthatunk (adatfeldolgozók), illetve jogszabályi kötelezettség esetén hatóságoknak.'),
(2369, 'privacy_s7_p', 'en', 'We do not sell personal data. Data may only be transferred to providers necessary for operating the service (data processors), or to authorities in case of a legal obligation.'),
(2370, 'privacy_s7_p', 'de', 'Wir verkaufen keine personenbezogenen Daten. Daten werden nur an zur Erbringung des Dienstes notwendige Anbieter (Auftragsverarbeiter) vagy bei gesetzlicher Verpflichtung an Behörden weitergegeben.'),
(2371, 'privacy_s7_1_title', 'hu', '7.1. Adatfeldolgozók (példák)'),
(2372, 'privacy_s7_1_title', 'en', '7.1. Data processors (examples)'),
(2373, 'privacy_s7_1_title', 'de', '7.1. Auftragsverarbeiter (Beispiele)'),
(2374, 'privacy_s7_location', 'hu', 'adatkezelés helye:'),
(2375, 'privacy_s7_location', 'en', 'data processing location:'),
(2376, 'privacy_s7_location', 'de', 'Datenverarbeitungsort:'),
(2377, 'privacy_s7_note', 'hu', 'Ha a szolgáltató EU-n kívülre továbbít adatot (pl. USA), akkor megfelelő garanciákat alkalmazhat (pl. EU-s megfelelőségi határozat, SCC).'),
(2378, 'privacy_s7_note', 'en', 'If a provider transfers data outside the EU (e.g. USA), it may apply appropriate safeguards (e.g. EU adequacy decision, SCCs).'),
(2379, 'privacy_s7_note', 'de', 'Wenn ein Anbieter Daten außerhalb der EU übermittelt (z. B. USA), kann er geeignete Garantien anwenden (z. B. EU-Angemessenheitsbeschluss, SCC).'),
(2380, 'privacy_s8_title', 'hu', '8. Az érintett jogai és ezek gyakorlása'),
(2381, 'privacy_s8_title', 'en', '8. Data subject rights and how to exercise them'),
(2382, 'privacy_s8_title', 'de', '8. Rechte der betroffenen Person und deren Ausübung'),
(2383, 'privacy_s8_intro', 'hu', 'Az érintett az alábbi jogokkal rendelkezik:'),
(2384, 'privacy_s8_intro', 'en', 'The data subject has the following rights:'),
(2385, 'privacy_s8_intro', 'de', 'Die betroffene Person hat folgende Rechte:'),
(2386, 'privacy_s8_li1', 'hu', '<strong>Hozzáférés joga</strong> a kezelt személyes adatokhoz'),
(2387, 'privacy_s8_li1', 'en', '<strong>Right of access</strong> to the personal data processed'),
(2388, 'privacy_s8_li1', 'de', '<strong>Recht auf Auskunft</strong> über die verarbeiteten personenbezogenen Daten'),
(2389, 'privacy_s8_li2', 'hu', '<strong>Helyesbítés joga</strong> (pontatlan adatok javítása)'),
(2390, 'privacy_s8_li2', 'en', '<strong>Right to rectification</strong> (correction of inaccurate data)'),
(2391, 'privacy_s8_li2', 'de', '<strong>Recht auf Berichtigung</strong> (Korrektur ungenauer Daten)'),
(2392, 'privacy_s8_li3', 'hu', '<strong>Törlés joga</strong> (\"elfeledtetés joga\"), ha nincs jogalap a további kezelésre'),
(2393, 'privacy_s8_li3', 'en', '<strong>Right to erasure</strong> (\"right to be forgotten\"), where there is no legal basis for further processing'),
(2394, 'privacy_s8_li3', 'de', '<strong>Recht auf Löschung</strong> (\"Recht auf Vergessenwerden\"), wenn keine Rechtsgrundlage für die weitere Verarbeitung besteht'),
(2395, 'privacy_s8_li4', 'hu', '<strong>Adatkezelés korlátozásának joga</strong>'),
(2396, 'privacy_s8_li4', 'en', '<strong>Right to restriction of processing</strong>'),
(2397, 'privacy_s8_li4', 'de', '<strong>Recht auf Einschränkung der Verarbeitung</strong>'),
(2398, 'privacy_s8_li5', 'hu', '<strong>Adathordozhatóság joga</strong> (ha alkalmazható)'),
(2399, 'privacy_s8_li5', 'en', '<strong>Right to data portability</strong> (where applicable)'),
(2400, 'privacy_s8_li5', 'de', '<strong>Recht auf Datenübertragbarkeit</strong> (soweit anwendbar)'),
(2401, 'privacy_s8_li6', 'hu', '<strong>Tiltakozás joga</strong> jogos érdek esetén'),
(2402, 'privacy_s8_li6', 'en', '<strong>Right to object</strong> in case of legitimate interest'),
(2403, 'privacy_s8_li6', 'de', '<strong>Widerspruchsrecht</strong> bei berechtigtem Interesse'),
(2404, 'privacy_s8_li7', 'hu', '<strong>Hozzájárulás visszavonása</strong> (ha a jogalap hozzájárulás)'),
(2405, 'privacy_s8_li7', 'en', '<strong>Withdrawal of consent</strong> (where the legal basis is consent)'),
(2406, 'privacy_s8_li7', 'de', '<strong>Widerruf der Einwilligung</strong> (wenn die Rechtsgrundlage die Einwilligung ist)'),
(2407, 'privacy_s8_contact', 'hu', 'A jogok gyakorlásához írj a <a href=\"mailto:%s\">%s</a> címre. A kérelem beérkezését követően ésszerű időn belül, de legkésőbb 1 hónapon belül válaszolunk; összetett ügyben ez 2 hónappal hosszabbítható.'),
(2408, 'privacy_s8_contact', 'en', 'To exercise your rights write to <a href=\"mailto:%s\">%s</a>. We will respond within a reasonable time, and no later than 1 month; for complex cases this can be extended by 2 months.'),
(2409, 'privacy_s8_contact', 'de', 'Zur Ausübung deiner Rechte schreib an <a href=\"mailto:%s\">%s</a>. Wir antworten innerhalb einer angemessenen Frist, spätestens jedoch innerhalb von 1 Monat; bei komplexen Fällen kann dies um 2 Monate verlängert werden.'),
(2410, 'privacy_s9_title', 'hu', '9. Panasz és jogorvoslat'),
(2411, 'privacy_s9_title', 'en', '9. Complaints and redress'),
(2412, 'privacy_s9_title', 'de', '9. Beschwerden und Rechtsbehelfe'),
(2413, 'privacy_s9_intro', 'hu', 'Amennyiben úgy ítéled meg, hogy a jogaid sérültek, panasszal fordulhatsz a felügyeleti hatósághoz:'),
(2414, 'privacy_s9_intro', 'en', 'If you believe your rights have been infringed, you may lodge a complaint with the supervisory authority:'),
(2415, 'privacy_s9_intro', 'de', 'Wenn du der Ansicht bist, dass deine Rechte verletzt wurden, kannst du bei der Aufsichtsbehörde Beschwerde einreichen:'),
(2416, 'privacy_s9_court', 'hu', 'Az érintett bírósági úton is érvényesítheti igényét.'),
(2417, 'privacy_s9_court', 'en', 'The data subject may also enforce their claim through the courts.'),
(2418, 'privacy_s9_court', 'de', 'Die betroffene Person kann ihre Ansprüche auch gerichtlich geltend machen.'),
(2419, 'privacy_s10_title', 'hu', '10. Automatizált döntéshozatal és profilalkotás'),
(2420, 'privacy_s10_title', 'en', '10. Automated decision-making and profiling'),
(2421, 'privacy_s10_title', 'de', '10. Automatisierte Entscheidungsfindung und Profiling'),
(2422, 'privacy_s10_p', 'hu', 'A %s nem alkalmaz automatizált döntéshozatalt vagy profilalkotást olyan módon, amely joghatással járna rád nézve.'),
(2423, 'privacy_s10_p', 'en', '%s does not apply automated decision-making or profiling in a way that would have a legal effect on you.'),
(2424, 'privacy_s10_p', 'de', '%s wendet keine automatisierte Entscheidungsfindung oder Profiling an, das rechtliche Auswirkungen auf dich hätte.'),
(2425, 'privacy_s11_title', 'hu', '11. Egyéb rendelkezések'),
(2426, 'privacy_s11_title', 'en', '11. Other provisions'),
(2427, 'privacy_s11_title', 'de', '11. Sonstige Bestimmungen'),
(2428, 'privacy_s11_li1', 'hu', '<strong>A tájékoztató frissítése:</strong> szükség szerint frissítjük; lényeges változás esetén a weboldalon közzétesszük.'),
(2429, 'privacy_s11_li1', 'en', '<strong>Updating this policy:</strong> we update it as necessary; in case of material changes we will publish them on the website.'),
(2430, 'privacy_s11_li1', 'de', '<strong>Aktualisierung dieser Erklärung:</strong> Wir aktualisieren sie bei Bedarf; bei wesentlichen Änderungen veröffentlichen wir diese auf der Website.'),
(2431, 'privacy_s11_li2', 'hu', '<strong>Kapcsolat:</strong> adatvédelemmel kapcsolatos megkeresés:'),
(2432, 'privacy_s11_li2', 'en', '<strong>Contact:</strong> privacy-related enquiries:'),
(2433, 'privacy_s11_li2', 'de', '<strong>Kontakt:</strong> datenschutzbezogene Anfragen:'),
(2434, 'report_page_title', 'hu', 'Hibajelentés'),
(2435, 'report_page_title', 'en', 'Bug report'),
(2436, 'report_page_title', 'de', 'Fehlermeldung'),
(2437, 'report_meta_desc', 'hu', 'Hibajelentés és visszajelzés a Jegyzetárhoz'),
(2438, 'report_meta_desc', 'en', 'Bug report and feedback for Jegyzetár'),
(2439, 'report_meta_desc', 'de', 'Fehlermeldung und Feedback für Jegyzetár'),
(2440, 'report_meta_keywords', 'hu', 'hibajelentés, visszajelzés, jegyzetár'),
(2441, 'report_meta_keywords', 'en', 'bug report, feedback, jegyzetar'),
(2442, 'report_meta_keywords', 'de', 'Fehlermeldung, Feedback, Jegyzetár'),
(2443, 'report_h1', 'hu', 'Hibajelentés / Visszajelzés'),
(2444, 'report_h1', 'en', 'Bug report / Feedback'),
(2445, 'report_h1', 'de', 'Fehlermeldung / Feedback'),
(2446, 'report_sub', 'hu', 'Írd le röviden, mit tapasztalsz. Minél pontosabb (eszköz, lépések), annál gyorsabb a javítás.'),
(2447, 'report_sub', 'en', 'Briefly describe what you experience. The more precise (device, steps), the faster we can fix it.'),
(2448, 'report_sub', 'de', 'Beschreibe kurz, was du erlebst. Je genauer (Gerät, Schritte), desto schneller können wir es beheben.'),
(2449, 'report_success', 'hu', 'Köszi! A jelentést megkaptuk.'),
(2450, 'report_success', 'en', 'Thanks! We have received your report.'),
(2451, 'report_success', 'de', 'Danke! Wir haben deine Meldung erhalten.'),
(2452, 'report_error_heading', 'hu', 'Hiba történt:'),
(2453, 'report_error_heading', 'en', 'An error occurred:'),
(2454, 'report_error_heading', 'de', 'Ein Fehler ist aufgetreten:'),
(2455, 'report_err_csrf', 'hu', 'Érvénytelen munkamenet (CSRF). Frissítsd az oldalt és próbáld újra.'),
(2456, 'report_err_csrf', 'en', 'Invalid session (CSRF). Please refresh the page and try again.'),
(2457, 'report_err_csrf', 'de', 'Ungültige Sitzung (CSRF). Bitte lade die Seite neu und versuche es erneut.'),
(2458, 'report_err_invalid_type', 'hu', 'Érvénytelen kategória.'),
(2459, 'report_err_invalid_type', 'en', 'Invalid category.'),
(2460, 'report_err_invalid_type', 'de', 'Ungültige Kategorie.'),
(2461, 'report_err_invalid_severity', 'hu', 'Érvénytelen prioritás.'),
(2462, 'report_err_invalid_severity', 'en', 'Invalid priority.'),
(2463, 'report_err_invalid_severity', 'de', 'Ungültige Priorität.'),
(2464, 'report_err_title_short', 'hu', 'A cím legyen legalább 4 karakter.'),
(2465, 'report_err_title_short', 'en', 'The title must be at least 4 characters long.'),
(2466, 'report_err_title_short', 'de', 'Der Titel muss mindestens 4 Zeichen lang sein.'),
(2467, 'report_err_desc_short', 'hu', 'A leírás legyen legalább 10 karakter.'),
(2468, 'report_err_desc_short', 'en', 'The description must be at least 10 characters long.'),
(2469, 'report_err_desc_short', 'de', 'Die Beschreibung muss mindestens 10 Zeichen lang sein.'),
(2470, 'report_err_email_invalid', 'hu', 'A megadott e-mail cím formátuma nem megfelelő.'),
(2471, 'report_err_email_invalid', 'en', 'The e-mail address format is invalid.'),
(2472, 'report_err_email_invalid', 'de', 'Das Format der E-Mail-Adresse ist ungültig.'),
(2473, 'report_err_url_invalid', 'hu', 'Az oldal linkje nem tűnik érvényes URL-nek.'),
(2474, 'report_err_url_invalid', 'en', 'The page URL does not appear to be a valid URL.'),
(2475, 'report_err_url_invalid', 'de', 'Die Seiten-URL scheint keine gültige URL zu sein.'),
(2476, 'report_err_save_failed', 'hu', 'Nem sikerült menteni a hibajelentést. Próbáld újra később.'),
(2477, 'report_err_save_failed', 'en', 'Could not save the bug report. Please try again later.'),
(2478, 'report_err_save_failed', 'de', 'Die Fehlermeldung konnte nicht gespeichert werden. Bitte versuche es später erneut.'),
(2479, 'report_label_type', 'hu', 'Kategória'),
(2480, 'report_label_type', 'en', 'Category'),
(2481, 'report_label_type', 'de', 'Kategorie'),
(2482, 'report_type_bug', 'hu', 'Hiba'),
(2483, 'report_type_bug', 'en', 'Bug'),
(2484, 'report_type_bug', 'de', 'Fehler'),
(2485, 'report_type_feature', 'hu', 'Javaslat'),
(2486, 'report_type_feature', 'en', 'Feature request'),
(2487, 'report_type_feature', 'de', 'Funktionswunsch'),
(2488, 'report_type_abuse', 'hu', 'Szabályszegés / visszaélés'),
(2489, 'report_type_abuse', 'en', 'Rule violation / abuse'),
(2490, 'report_type_abuse', 'de', 'Regelverstoß / Missbrauch'),
(2491, 'report_type_other', 'hu', 'Egyéb'),
(2492, 'report_type_other', 'en', 'Other'),
(2493, 'report_type_other', 'de', 'Sonstiges'),
(2494, 'report_label_severity', 'hu', 'Prioritás'),
(2495, 'report_label_severity', 'en', 'Priority'),
(2496, 'report_label_severity', 'de', 'Priorität'),
(2497, 'report_severity_low', 'hu', 'Alacsony'),
(2498, 'report_severity_low', 'en', 'Low'),
(2499, 'report_severity_low', 'de', 'Niedrig'),
(2500, 'report_severity_medium', 'hu', 'Közepes'),
(2501, 'report_severity_medium', 'en', 'Medium'),
(2502, 'report_severity_medium', 'de', 'Mittel'),
(2503, 'report_severity_high', 'hu', 'Magas'),
(2504, 'report_severity_high', 'en', 'High'),
(2505, 'report_severity_high', 'de', 'Hoch'),
(2506, 'report_severity_critical', 'hu', 'Kritikus'),
(2507, 'report_severity_critical', 'en', 'Critical'),
(2508, 'report_severity_critical', 'de', 'Kritisch'),
(2509, 'report_label_title', 'hu', 'Rövid cím'),
(2510, 'report_label_title', 'en', 'Short title'),
(2511, 'report_label_title', 'de', 'Kurztitel'),
(2512, 'report_placeholder_title', 'hu', 'Pl.: Letöltés gomb nem működik mobilon'),
(2513, 'report_placeholder_title', 'en', 'E.g.: Download button not working on mobile'),
(2514, 'report_placeholder_title', 'de', 'Z. B.: Download-Schaltfläche funktioniert auf dem Handy nicht'),
(2515, 'report_label_description', 'hu', 'Leírás'),
(2516, 'report_label_description', 'en', 'Description'),
(2517, 'report_label_description', 'de', 'Beschreibung'),
(2518, 'report_placeholder_description', 'hu', 'Írd le részletesen, mit tapasztalsz...'),
(2519, 'report_placeholder_description', 'en', 'Describe in detail what you experience...'),
(2520, 'report_placeholder_description', 'de', 'Beschreibe ausführlich, was du erlebst...'),
(2521, 'report_label_page_url', 'hu', 'Érintett oldal linkje (opcionális)'),
(2522, 'report_label_page_url', 'en', 'Affected page URL (optional)'),
(2523, 'report_label_page_url', 'de', 'URL der betroffenen Seite (optional)'),
(2524, 'report_hint_page_url', 'hu', 'Tipp: másold be a címsorból.'),
(2525, 'report_hint_page_url', 'en', 'Tip: copy it from the address bar.'),
(2526, 'report_hint_page_url', 'de', 'Tipp: Kopiere es aus der Adressleiste.'),
(2527, 'report_label_contact_email', 'hu', 'Kapcsolati e-mail (opcionális)'),
(2528, 'report_label_contact_email', 'en', 'Contact e-mail (optional)'),
(2529, 'report_label_contact_email', 'de', 'Kontakt-E-Mail (optional)'),
(2530, 'report_placeholder_contact_email', 'hu', 'ha szeretnél választ'),
(2531, 'report_placeholder_contact_email', 'en', 'if you would like a reply'),
(2532, 'report_placeholder_contact_email', 'de', 'wenn du eine Antwort möchtest'),
(2533, 'report_label_steps', 'hu', 'Lépések a reprodukáláshoz (opcionális)'),
(2534, 'report_label_steps', 'en', 'Steps to reproduce (optional)'),
(2535, 'report_label_steps', 'de', 'Schritte zur Reproduktion (optional)'),
(2536, 'report_label_expected', 'hu', 'Elvárt eredmény (opcionális)'),
(2537, 'report_label_expected', 'en', 'Expected result (optional)'),
(2538, 'report_label_expected', 'de', 'Erwartetes Ergebnis (optional)'),
(2539, 'report_placeholder_expected', 'hu', 'Mit kellett volna történnie?'),
(2540, 'report_placeholder_expected', 'en', 'What should have happened?'),
(2541, 'report_placeholder_expected', 'de', 'Was hätte passieren sollen?'),
(2542, 'report_label_actual', 'hu', 'Tényleges eredmény (opcionális)'),
(2543, 'report_label_actual', 'en', 'Actual result (optional)'),
(2544, 'report_label_actual', 'de', 'Tatsächliches Ergebnis (optional)'),
(2545, 'report_placeholder_actual', 'hu', 'Mi történt helyette?'),
(2546, 'report_placeholder_actual', 'en', 'What happened instead?'),
(2547, 'report_placeholder_actual', 'de', 'Was ist stattdessen passiert?'),
(2548, 'report_btn_submit', 'hu', 'Jelentés elküldése'),
(2549, 'report_btn_submit', 'en', 'Submit report'),
(2550, 'report_btn_submit', 'de', 'Meldung absenden'),
(2551, 'report_hint_no_sensitive', 'hu', 'Ne adj meg jelszót vagy érzékeny adatot.'),
(2552, 'report_hint_no_sensitive', 'en', 'Do not enter your password or sensitive data.'),
(2553, 'report_hint_no_sensitive', 'de', 'Gib kein Passwort oder sensible Daten ein.'),
(2554, 'label_role', 'hu', 'Szerepkör'),
(2555, 'label_role', 'en', 'Role'),
(2556, 'label_role', 'de', 'Rolle'),
(2557, 'role_student', 'hu', 'Diák'),
(2558, 'role_student', 'en', 'Student'),
(2559, 'role_student', 'de', 'Schüler/in'),
(2560, 'role_student_desc', 'hu', 'Böngészés, letöltés, értékelés és csoportokhoz csatlakozhatsz'),
(2561, 'role_student_desc', 'en', 'Browse, download, rate and join groups'),
(2562, 'role_student_desc', 'de', 'Durchsuchen, herunterladen, bewerten und Gruppen beitreten'),
(2563, 'role_teacher', 'hu', 'Tanár'),
(2564, 'role_teacher', 'en', 'Teacher'),
(2565, 'role_teacher', 'de', 'Lehrer/in'),
(2566, 'role_teacher_desc', 'hu', 'Tanári jogosultságok és extra funkciók'),
(2567, 'role_teacher_desc', 'en', 'Teacher permissions and extra features'),
(2568, 'role_teacher_desc', 'de', 'Lehrerberechtigung und zusätzliche Funktionen'),
(2569, 'role_badge_admin', 'hu', 'Admin'),
(2570, 'role_badge_admin', 'en', 'Admin'),
(2571, 'role_badge_admin', 'de', 'Admin'),
(2572, 'role_badge_admin_title', 'hu', 'Ez a felhasználó adminisztrátor'),
(2573, 'role_badge_admin_title', 'en', 'This user is an administrator'),
(2574, 'role_badge_admin_title', 'de', 'Dieser Nutzer ist Administrator'),
(2575, 'role_badge_teacher', 'hu', 'Tanár'),
(2576, 'role_badge_teacher', 'en', 'Teacher'),
(2577, 'role_badge_teacher', 'de', 'Lehrer/in'),
(2578, 'role_badge_teacher_title', 'hu', 'Ez a felhasználó tanár'),
(2579, 'role_badge_teacher_title', 'en', 'This user is a teacher'),
(2580, 'role_badge_teacher_title', 'de', 'Dieser Nutzer ist Lehrer/in'),
(2581, 'role_badge_student', 'hu', 'Diák'),
(2582, 'role_badge_student', 'en', 'Student'),
(2583, 'role_badge_student', 'de', 'Schüler/in'),
(2584, 'role_badge_student_title', 'hu', 'Ez a felhasználó diák'),
(2585, 'role_badge_student_title', 'en', 'This user is a student'),
(2586, 'role_badge_student_title', 'de', 'Dieser Nutzer ist Schüler/in'),
(2587, 'upload_tags_hint', 'hu', 'Enter, vessző vagy pontosvessző a tag hozzáadásához • max. 10 tag • Backspace törli az utolsót'),
(2588, 'upload_tags_hint', 'en', 'Press Enter, comma or semicolon to add a tag • max. 10 tags • Backspace removes the last one'),
(2589, 'upload_tags_hint', 'de', 'Enter, Komma oder Semikolon zum Hinzufügen • max. 10 Tags • Rücktaste löscht das letzte'),
(2590, 'upload_tags_hint', 'hu', 'Válassz a listából vagy írj be saját taget – Enter, vessző vagy pontosvessző a hozzáadáshoz • max. 10 tag • Backspace törli az utolsót'),
(2591, 'upload_tags_hint', 'en', 'Pick from the list or type your own tag – Enter, comma or semicolon to add • max. 10 tags • Backspace removes the last one'),
(2592, 'upload_tags_hint', 'de', 'Aus der Liste wählen oder eigenen Tag eingeben – Enter, Komma oder Semikolon zum Hinzufügen • max. 10 Tags • Rücktaste löscht das letzte'),
(0, 'profile_saved_searches_title', 'hu', 'Mentett keresések'),
(0, 'profile_saved_searches_empty', 'hu', 'Még nem mentettél el egyetlen keresést sem.'),
(0, 'profile_saved_searches_title', 'en', 'Saved searches'),
(0, 'profile_saved_searches_empty', 'en', 'You haven\'t saved any searches yet.'),
(0, 'profile_saved_searches_title', 'de', 'Gespeicherte Suchen'),
(0, 'profile_saved_searches_empty', 'de', 'Du hast noch keine Suchen gespeichert.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `security_question` varchar(255) NOT NULL,
  `security_answer` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `teacher` tinyint(1) DEFAULT NULL,
  `registration_date` datetime DEFAULT NULL,
  `language` varchar(5) NOT NULL DEFAULT 'hu',
  `oauth_provider` varchar(20) DEFAULT NULL,
  `oauth_sub` varchar(191) DEFAULT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `bio` text DEFAULT NULL,
  `profile_theme` varchar(32) NOT NULL DEFAULT 'default',
  `twofa_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `used_reg_code` varchar(64) DEFAULT NULL,
  `used_reg_code_at` datetime DEFAULT NULL,
  `show_fullname` tinyint(1) NOT NULL DEFAULT 1,
  `show_email` tinyint(1) NOT NULL DEFAULT 0,
  `show_birthdate` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `lastname`, `firstname`, `username`, `birthdate`, `gender`, `email`, `profile_picture`, `password`, `security_question`, `security_answer`, `admin`, `teacher`, `registration_date`, `language`, `oauth_provider`, `oauth_sub`, `email_verified`, `bio`, `profile_theme`, `twofa_enabled`, `used_reg_code`, `used_reg_code_at`, `show_fullname`, `show_email`, `show_birthdate`) VALUES
(1, 'Csontos', 'Kincső', 'csontoskincso05', '2005-04-04', 'female', 'csontoskincso@doomhyena.hu', 'Rudo Surebrec.jpg', '$2y$10$ZLWnsc4oApKzTPcMkkeC8OcVEmKA3PVyV2Fu7Mn4cCKTrQR5wmLgK', 'Mi a kedvenc könyved?', 'Harry Potter', 1, NULL, '2026-01-21 15:42:52', 'hu', 'discord', '864583234158460938', 1, 'Software & Systems Engineer Student', 'light', 0, NULL, NULL, 0, 0, 0),
(8, 'Teszt', 'User', 'tesztuser', '2005-12-16', 'female', 'csontoskincso05@gmail.com', NULL, '$2y$10$rsRPmF5j81OCfV3xbpkIHOCGXeKXLTOkUIb7tH4j73o74H8QQiHRK', 'Mi az édesanyád leánykori neve?', 'Harry Potter', 0, NULL, '2025-12-10 21:51:35', 'hu', NULL, NULL, 1, NULL, 'default', 0, NULL, NULL, 1, 0, 0),
(9, 'Csontos', 'Kincső', 'doomhyena', '2005-04-04', 'female', 'csontoskincso@proton.me', NULL, '$2y$10$i7QzVcekWMXr3DJD6BUmgeR0Wal7AmZyBjDfjjVf6eowweutMErEa', 'Mi a kedvenc könyved?', '$2y$10$c5BtBT/ODqFT/OeHGYLuCeMCsdJ5sScb2Xjgg/lkJ77I9whd323tG', 0, 1, '2026-02-20 15:54:27', 'hu', NULL, NULL, 1, NULL, 'default', 0, NULL, NULL, 1, 0, 0),
(10, 'Horváth', 'Eszter', 'user10', '2006-07-03', 'other', 'user10@test.hu', NULL, 'hashed', 'Mi a kedvenc könyved?', 'budapest', 0, NULL, '2026-01-15 15:30:27', 'hu', NULL, NULL, 0, '📖 Jegyzeteket gyűjtök és megosztok.', 'default', 0, NULL, NULL, 1, 0, 0),
(11, 'Horváth', 'Levente', 'user11', '2002-12-05', 'other', 'user11@test.hu', NULL, 'hashed', 'Mi a kedvenc ételed?', 'kutyus', 0, NULL, '2025-12-16 13:28:36', 'hu', NULL, NULL, 0, '🚀 Cél: minél jobb eredmény!', 'default', 0, NULL, NULL, 1, 0, 0),
(12, 'Farkas', 'Nóra', 'user12', '2003-01-12', 'female', 'user12@test.hu', NULL, 'hashed', 'Mi volt az első háziállatod neve?', 'cica', 0, NULL, '2025-12-06 14:29:18', 'hu', NULL, NULL, 0, '💻 Programozás és matek fan.', 'default', 0, NULL, NULL, 1, 0, 0),
(13, 'Kiss', 'Nóra', 'user13', '2006-02-19', 'male', 'user13@test.hu', NULL, 'hashed', 'Mi a születési városod?', 'teszt123', 0, NULL, '2026-02-24 15:59:17', 'hu', NULL, NULL, 0, '📖 Jegyzeteket gyűjtök és megosztok.', 'default', 0, NULL, NULL, 1, 0, 0),
(14, 'Kovács', 'Eszter', 'user14', '2008-02-09', 'male', 'user14@test.hu', NULL, 'hashed', 'Mi a kedvenc könyved?', 'cica', 0, NULL, '2026-01-08 11:22:56', 'hu', NULL, NULL, 0, '📖 Jegyzeteket gyűjtök és megosztok.', 'default', 0, NULL, NULL, 1, 0, 0),
(15, 'Nagy', 'Anna', 'user15', '2000-12-03', 'female', 'user15@test.hu', NULL, 'hashed', 'Mi volt az első háziállatod neve?', 'alma', 0, NULL, '2026-02-10 18:22:14', 'hu', NULL, NULL, 0, '💻 Programozás és matek fan.', 'default', 0, NULL, NULL, 1, 0, 0),
(16, 'Szabó', 'Dávid', 'user16', '2007-07-17', 'female', 'user16@test.hu', NULL, 'hashed', 'Mi volt az első háziállatod neve?', 'cica', 0, NULL, '2026-02-17 12:36:14', 'hu', NULL, NULL, 0, '🎓 Érettségire készülök, minden segítség jól jön!', 'default', 0, NULL, NULL, 1, 0, 0),
(17, 'Kiss', 'Eszter', 'user17', '2001-12-11', 'other', 'user17@test.hu', NULL, 'hashed', 'Mi a kedvenc ételed?', 'anon', 0, NULL, '2025-12-12 20:08:24', 'hu', NULL, NULL, 0, '🚀 Cél: minél jobb eredmény!', 'default', 0, NULL, NULL, 1, 0, 0),
(18, 'Tóth', 'Ádám', 'user18', '2001-07-27', 'female', 'user18@test.hu', NULL, 'hashed', 'Mi a születési városod?', 'budapest', 0, NULL, '2026-03-20 11:13:28', 'hu', NULL, NULL, 0, '📖 Jegyzeteket gyűjtök és megosztok.', 'default', 0, NULL, NULL, 1, 0, 0),
(19, 'Tóth', 'Anna', 'user19', '2008-02-15', 'male', 'user19@test.hu', NULL, 'hashed', 'Mi a kedvenc ételed?', 'csoki', 0, NULL, '2026-02-24 14:00:26', 'hu', NULL, NULL, 0, '🚀 Cél: minél jobb eredmény!', 'default', 0, NULL, NULL, 1, 0, 0),
(20, 'Szabó', 'Máté', 'user20', '2001-08-13', 'other', 'user20@test.hu', NULL, 'hashed', 'Mi az édesanyád leánykori neve?', 'anon', 0, NULL, '2026-01-27 15:07:10', 'hu', NULL, NULL, 0, '🎓 Érettségire készülök, minden segítség jól jön!', 'default', 0, NULL, NULL, 1, 0, 0),
(21, 'Farkas', 'Luca', 'user21', '2002-12-04', 'male', 'user21@test.hu', NULL, 'hashed', 'Mi a kedvenc ételed?', 'csoki', 0, NULL, '2026-02-18 11:12:24', 'hu', NULL, NULL, 0, '🎓 Érettségire készülök, minden segítség jól jön!', 'default', 0, NULL, NULL, 1, 0, 0),
(22, 'Horváth', 'Luca', 'user22', '2008-06-18', 'female', 'user22@test.hu', NULL, 'hashed', 'Mi a kedvenc ételed?', 'cica', 0, NULL, '2026-01-25 10:51:05', 'hu', NULL, NULL, 0, '📖 Jegyzeteket gyűjtök és megosztok.', 'default', 0, NULL, NULL, 1, 0, 0),
(23, 'Szabó', 'Ádám', 'user23', '2006-08-18', 'female', 'user23@test.hu', NULL, 'hashed', 'Mi a kedvenc könyved?', 'anon', 0, NULL, '2026-03-20 15:19:07', 'hu', NULL, NULL, 0, '🚀 Cél: minél jobb eredmény!', 'default', 0, NULL, NULL, 1, 0, 0),
(24, 'Tóth', 'Anna', 'user24', '2009-02-26', 'other', 'user24@test.hu', NULL, 'hashed', 'Mi a kedvenc könyved?', 'kutyus', 0, NULL, '2026-01-19 19:23:39', 'hu', NULL, NULL, 0, '💻 Programozás és matek fan.', 'default', 0, NULL, NULL, 1, 0, 0),
(25, 'Balogh', 'Zsófia', 'user25', '1999-04-14', 'female', 'user25@test.hu', NULL, 'hashed', 'Mi az édesanyád leánykori neve?', 'anon', 0, NULL, '2026-02-15 20:37:26', 'hu', NULL, NULL, 0, '💻 Programozás és matek fan.', 'default', 0, NULL, NULL, 1, 0, 0),
(26, 'Molnár', 'Bence', 'user26', '2003-05-08', 'female', 'user26@test.hu', NULL, 'hashed', 'Mi a születési városod?', 'cica', 0, NULL, '2026-01-27 19:18:31', 'hu', NULL, NULL, 0, '📖 Jegyzeteket gyűjtök és megosztok.', 'default', 0, NULL, NULL, 1, 0, 0),
(27, 'Szabó', 'Bence', 'user27', '2005-04-30', 'other', 'user27@test.hu', NULL, 'hashed', 'Mi az édesanyád leánykori neve?', 'budapest', 0, NULL, '2026-01-19 19:14:49', 'hu', NULL, NULL, 0, '📖 Jegyzeteket gyűjtök és megosztok.', 'default', 0, NULL, NULL, 1, 0, 0),
(28, 'Farkas', 'Máté', 'user28', '2008-02-18', 'other', 'user28@test.hu', NULL, 'hashed', 'Mi a kedvenc ételed?', 'cica', 0, NULL, '2026-02-09 17:54:52', 'hu', NULL, NULL, 0, '📖 Jegyzeteket gyűjtök és megosztok.', 'default', 0, NULL, NULL, 1, 0, 0),
(29, 'Varga', 'Ádám', 'user29', '2002-10-14', 'male', 'user29@test.hu', NULL, 'hashed', 'Mi a kedvenc ételed?', 'kutyus', 0, NULL, '2026-01-30 17:09:14', 'hu', NULL, NULL, 0, '📖 Jegyzeteket gyűjtök és megosztok.', 'default', 0, NULL, NULL, 1, 0, 0),
(30, 'Tóth', 'Levente', 'user30', '2003-12-31', 'female', 'user30@test.hu', NULL, 'hashed', 'Mi a születési városod?', 'anon', 0, NULL, '2026-01-21 14:41:40', 'hu', NULL, NULL, 0, '📚 Szeretek tanulni és jegyzeteket készíteni.', 'default', 0, NULL, NULL, 1, 0, 0),
(31, 'Varga', 'Nóra', 'user31', '2001-08-22', 'male', 'user31@test.hu', NULL, 'hashed', 'Mi a születési városod?', 'kutyus', 0, NULL, '2026-02-15 10:48:39', 'hu', NULL, NULL, 0, '🚀 Cél: minél jobb eredmény!', 'default', 0, NULL, NULL, 1, 0, 0),
(32, 'Kovács', 'Zsófia', 'user32', '2007-08-02', 'other', 'user32@test.hu', NULL, 'hashed', 'Mi volt az első háziállatod neve?', 'csoki', 0, NULL, '2026-01-16 19:09:24', 'hu', NULL, NULL, 0, '💻 Programozás és matek fan.', 'default', 0, NULL, NULL, 1, 0, 0),
(33, 'Nagy', 'Nóra', 'user33', '2009-02-25', 'other', 'user33@test.hu', NULL, 'hashed', 'Mi a születési városod?', 'pizza', 0, NULL, '2026-02-14 20:40:43', 'hu', NULL, NULL, 0, '📚 Szeretek tanulni és jegyzeteket készíteni.', 'default', 0, NULL, NULL, 1, 0, 0),
(34, 'Kovács', 'Anna', 'user34', '2007-12-15', 'male', 'user34@test.hu', NULL, 'hashed', 'Mi a kedvenc könyved?', 'csoki', 0, NULL, '2026-02-02 10:29:17', 'hu', NULL, NULL, 0, '📖 Jegyzeteket gyűjtök és megosztok.', 'default', 0, NULL, NULL, 1, 0, 0),
(35, 'Molnár', 'Luca', 'user35', '2001-01-27', 'male', 'user35@test.hu', NULL, 'hashed', 'Mi a születési városod?', 'anon', 0, NULL, '2026-02-07 19:09:49', 'hu', NULL, NULL, 0, '🎓 Érettségire készülök, minden segítség jól jön!', 'default', 0, NULL, NULL, 1, 0, 0),
(36, 'Farkas', 'Bence', 'user36', '2004-04-19', 'male', 'user36@test.hu', NULL, 'hashed', 'Mi a kedvenc ételed?', 'csoki', 0, NULL, '2025-12-11 11:43:03', 'hu', NULL, NULL, 0, '📖 Jegyzeteket gyűjtök és megosztok.', 'default', 0, NULL, NULL, 1, 0, 0),
(37, 'Horváth', 'Luca', 'user37', '2001-04-01', 'male', 'user37@test.hu', NULL, 'hashed', 'Mi a kedvenc könyved?', 'kutyus', 0, NULL, '2026-01-23 20:09:47', 'hu', NULL, NULL, 0, '💻 Programozás és matek fan.', 'default', 0, NULL, NULL, 1, 0, 0),
(38, 'Molnár', 'Zsófia', 'user38', '2003-04-09', 'other', 'user38@test.hu', NULL, 'hashed', 'Mi a kedvenc ételed?', 'pizza', 0, NULL, '2026-03-13 19:38:26', 'hu', NULL, NULL, 0, '💻 Programozás és matek fan.', 'default', 0, NULL, NULL, 1, 0, 0),
(40, NULL, NULL, 'asd', NULL, NULL, '', NULL, '$2a$10$zPnXqTCczWok8mErM5200eex66DHgUBufqL/WAOr7sEZ837v1S/Eu', '', '', 0, NULL, NULL, 'hu', NULL, NULL, 0, NULL, 'default', 0, NULL, NULL, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_badges`
--

CREATE TABLE `user_badges` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `badge_id` int(11) NOT NULL,
  `granted_by` int(11) DEFAULT NULL,
  `granted_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `user_badges`
--

INSERT INTO `user_badges` (`id`, `user_id`, `badge_id`, `granted_by`, `granted_at`) VALUES
(2, 1, 1, 1, '2026-01-25 18:58:27'),
(4, 1, 2, NULL, '2026-02-10 11:13:35');

-- --------------------------------------------------------

--
-- Table structure for table `user_custom_css_archive`
--

CREATE TABLE `user_custom_css_archive` (
  `id` int(11) NOT NULL,
  `original_request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `css` mediumtext NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL,
  `created_at` datetime NOT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `archived_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_custom_css_archive`
--

INSERT INTO `user_custom_css_archive` (`id`, `original_request_id`, `user_id`, `css`, `status`, `created_at`, `reviewed_at`, `reviewed_by`, `archived_at`) VALUES
(1, 1, 4, 'body {\r\n    background:\r\n        radial-gradient(circle at 0% 0%, rgba(244,114,182,.35), transparent 60%),\r\n        radial-gradient(circle at 100% 0%, rgba(56,189,248,.28), transparent 55%),\r\n        radial-gradient(circle at 50% 100%, rgba(167,139,250,.3), transparent 55%),\r\n        linear-gradient(180deg, #050816 0%, #020617 100%);\r\n    color: #e5e7eb;\r\n}\r\n\r\n.main {\r\n    border-radius: 28px;\r\n    border: 1px solid rgba(148,163,184,.35);\r\n    background:\r\n        radial-gradient(circle at 0% 0%, rgba(244,114,182,.12), transparent 55%),\r\n        radial-gradient(circle at 100% 0%, rgba(56,189,248,.10), transparent 55%),\r\n        linear-gradient(180deg, rgba(15,23,42,.96), rgba(15,23,42,.94));\r\n    box-shadow: 0 24px 60px rgba(0,0,0,.7);\r\n    padding: 40px 34px;\r\n}', 'approved', '2025-12-02 10:57:19', '2025-12-02 10:58:14', 4, '2025-12-07 13:24:08');

-- --------------------------------------------------------

--
-- Table structure for table `user_custom_css_requests`
--

CREATE TABLE `user_custom_css_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `css` mediumtext NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `reviewed_at` datetime DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `2fa_codes`
--
ALTER TABLE `2fa_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `bug_reports`
--
ALTER TABLE `bug_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `read_by_admin` (`read_by_admin`);

--
-- Indexes for table `deleted_users`
--
ALTER TABLE `deleted_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_original_id` (`original_id`),
  ADD KEY `idx_deleted_at` (`deleted_at`),
  ADD KEY `idx_deleted_by` (`deleted_by`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `file_events`
--
ALTER TABLE `file_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_file_time` (`file_id`,`created_at`),
  ADD KEY `idx_file_type_time` (`file_id`,`event_type`,`created_at`),
  ADD KEY `idx_user_time` (`user_id`,`created_at`);

--
-- Indexes for table `file_stats_daily`
--
ALTER TABLE `file_stats_daily`
  ADD PRIMARY KEY (`file_id`,`day`),
  ADD KEY `idx_day` (`day`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fromid` (`fromid`),
  ADD KEY `toid` (`toid`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_comments`
--
ALTER TABLE `group_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_events`
--
ALTER TABLE `group_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_files`
--
ALTER TABLE `group_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_file_comments`
--
ALTER TABLE `group_file_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_flashcards`
--
ALTER TABLE `group_flashcards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_polls`
--
ALTER TABLE `group_polls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_poll_options`
--
ALTER TABLE `group_poll_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_poll_votes`
--
ALTER TABLE `group_poll_votes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifys`
--
ALTER TABLE `notifys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_attempts`
--
ALTER TABLE `password_reset_attempts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user_ip` (`username`,`ip_address`);

--
-- Indexes for table `premium_users`
--
ALTER TABLE `premium_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user` (`user_id`);

--
-- Indexes for table `profanity_filter`
--
ALTER TABLE `profanity_filter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user_file` (`file_id`,`user_id`),
  ADD KEY `idx_file` (`file_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `registration_code_uses`
--
ALTER TABLE `registration_code_uses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_code` (`reg_code`);

--
-- Indexes for table `reg_codes`
--
ALTER TABLE `reg_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saved_searches`
--
ALTER TABLE `saved_searches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_saved_searches_user` (`user_id`);

--
-- Indexes for table `search_logs`
--
ALTER TABLE `search_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_search_logs_created` (`created_at`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tokens_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `uniq_oauth` (`oauth_provider`,`oauth_sub`),
  ADD KEY `fk_user_lang` (`language`);

--
-- Indexes for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_badges_ibfk_1` (`user_id`),
  ADD KEY `user_badges_ibfk_2` (`badge_id`),
  ADD KEY `user_badges_ibfk_3` (`granted_by`);

--
-- Indexes for table `user_custom_css_archive`
--
ALTER TABLE `user_custom_css_archive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_custom_css_requests`
--
ALTER TABLE `user_custom_css_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_css_reviewer` (`reviewed_by`),
  ADD KEY `fk_css_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `2fa_codes`
--
ALTER TABLE `2fa_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bug_reports`
--
ALTER TABLE `bug_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deleted_users`
--
ALTER TABLE `deleted_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `file_events`
--
ALTER TABLE `file_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `group_comments`
--
ALTER TABLE `group_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `group_events`
--
ALTER TABLE `group_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `group_files`
--
ALTER TABLE `group_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `group_file_comments`
--
ALTER TABLE `group_file_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `group_flashcards`
--
ALTER TABLE `group_flashcards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `group_members`
--
ALTER TABLE `group_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `group_polls`
--
ALTER TABLE `group_polls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_poll_options`
--
ALTER TABLE `group_poll_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_poll_votes`
--
ALTER TABLE `group_poll_votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifys`
--
ALTER TABLE `notifys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `password_reset_attempts`
--
ALTER TABLE `password_reset_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `premium_users`
--
ALTER TABLE `premium_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `profanity_filter`
--
ALTER TABLE `profanity_filter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=367;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `registration_code_uses`
--
ALTER TABLE `registration_code_uses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reg_codes`
--
ALTER TABLE `reg_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `saved_searches`
--
ALTER TABLE `saved_searches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `search_logs`
--
ALTER TABLE `search_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `user_badges`
--
ALTER TABLE `user_badges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_custom_css_archive`
--
ALTER TABLE `user_custom_css_archive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_custom_css_requests`
--
ALTER TABLE `user_custom_css_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD CONSTRAINT `contact_messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `premium_users`
--
ALTER TABLE `premium_users`
  ADD CONSTRAINT `fk_premium_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `registration_code_uses`
--
ALTER TABLE `registration_code_uses`
  ADD CONSTRAINT `fk_regcode_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `fk_tokens_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD CONSTRAINT `user_badges_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_badges_ibfk_2` FOREIGN KEY (`badge_id`) REFERENCES `badges` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_badges_ibfk_3` FOREIGN KEY (`granted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_custom_css_requests`
--
ALTER TABLE `user_custom_css_requests`
  ADD CONSTRAINT `fk_css_reviewer` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_css_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
