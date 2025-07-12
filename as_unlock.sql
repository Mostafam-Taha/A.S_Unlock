-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 11, 2025 at 07:53 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `as_unlock`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `security_question_id` int(11) NOT NULL,
  `security_answer_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `remember_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `email`, `password_hash`, `security_question_id`, `security_answer_hash`, `created_at`, `last_login`, `is_active`, `permissions`, `remember_token`, `token_expiry`, `reset_token`, `reset_token_expiry`) VALUES
(1, 'mostafamtaha1', 'mostafamtaha66@gmail.com', '$2y$12$/yGWHcde6lYYfZl9ooyfmeIC13VfvsAajyYkByKWg2M17PwEwkJW6', 1, '4593b0e03c93a1ff272d57005fa0f8a6e44078e4865e75eadd558b69141628ac', '2025-07-02 14:15:21', NULL, 1, '{\"manage_users\":true,\"manage_content\":true,\"manage_settings\":true,\"access_reports\":true}', '459e7fe1d5e6e32560dc8b01823034fd319cc892180c4c33b01eb268e62f7b46', '2025-08-01 16:54:38', '362592', '2025-07-02 19:25:11'),
(2, 'admin', 'admin@gmali.com', '$2y$12$EhAyXO83eE9cfl4sLLP96eLcpmuTPvombTUUhmLcZK4X7dyNAMYl6', 1, '49960de5880e8c687434170f6476605b8fe4aeb9a28632c7995cf3ba831d9763', '2025-07-02 14:38:02', NULL, 1, '{\"manage_users\":true,\"manage_content\":true,\"manage_settings\":true,\"access_reports\":true}', NULL, NULL, NULL, NULL),
(3, 'mosraf', 'mostafamtaha@gmail.com', '$2y$12$7inH0crTFR.e7JBCelahjuWUK1XY2d/cs32ogTIoIrv5KIr5jPRL.', 1, '49960de5880e8c687434170f6476605b8fe4aeb9a28632c7995cf3ba831d9763', '2025-07-02 14:42:38', NULL, 1, '{\"manage_users\":true,\"manage_content\":true,\"manage_settings\":true,\"access_reports\":true}', NULL, NULL, NULL, NULL),
(4, 'teste', 'localhost@gmail.com', '$2y$12$45sbF88hyTNa6xg/YuO0vuSj.uxp2yj5W9r068TbEOlHiXFBcaWfa', 1, '4593b0e03c93a1ff272d57005fa0f8a6e44078e4865e75eadd558b69141628ac', '2025-07-02 14:47:30', NULL, 1, '{\"manage_users\":true,\"manage_content\":true,\"manage_settings\":true,\"access_reports\":true}', NULL, NULL, NULL, NULL),
(5, 'test', 'localhost@gmail.codm', '$2y$12$0Zbbs6lZF24LVHT/NI8Aje4/Olphi4dOl4gjwu5BTGrR6.fh.p.z2', 1, '4593b0e03c93a1ff272d57005fa0f8a6e44078e4865e75eadd558b69141628ac', '2025-07-02 14:48:18', NULL, 1, '{\"manage_users\":true,\"manage_content\":true,\"manage_settings\":true,\"access_reports\":true}', '818d67bd333198d2785289a2ab22a55ab5dbbf446bff7554d987d934277f0f74', '2025-08-01 16:48:33', NULL, NULL),
(6, 'mostaf', 'o@gmail.com', '$2y$12$LG84C6X/3Ht.p6ySJM7qIOs2q.qjy1S0om8yKwgfgSBZBDLKyZN1W', 2, 'f0e4c2f76c58916ec258f246851bea091d14d4247a2fc3e18694461b1816e13b', '2025-07-02 14:52:22', NULL, 1, '{\"manage_users\":true,\"manage_content\":true,\"manage_settings\":true,\"access_reports\":true}', NULL, NULL, NULL, NULL),
(7, 'mostafamtaha', 'j@gmail.com', '$2y$12$uVQXtmieQBcqu1/4Vhyum.qCOW3MXygyUFga6dP.JEh1/2NqpuGSa', 2, '4593b0e03c93a1ff272d57005fa0f8a6e44078e4865e75eadd558b69141628ac', '2025-07-02 15:00:30', NULL, 1, '{\"manage_users\":true,\"manage_content\":true,\"manage_settings\":true,\"access_reports\":true}', NULL, NULL, NULL, NULL),
(8, 'AS', 'AS@gmail.com', '$2y$12$mNH4GYXHp0qdeNpL3/UmUuZD3FlTMntXO0sSO5PJh3mKb9ZihdOR2', 2, '4593b0e03c93a1ff272d57005fa0f8a6e44078e4865e75eadd558b69141628ac', '2025-07-02 15:47:00', NULL, 1, '{\"manage_users\":true,\"manage_content\":true,\"manage_settings\":true,\"access_reports\":true}', '47c0c22d3fdcbd15f03326a40df1c8e559a7939975e0d4a2da865079918255f3', '2025-08-01 17:47:25', NULL, NULL),
(9, 'ggg', 'gg@gmail.com', '$2y$12$Uql2WxIg4viMAvqBHVLMiOVarUhDJkcvzAdzyLB8TIsdPc5TzytAG', 4, '49960de5880e8c687434170f6476605b8fe4aeb9a28632c7995cf3ba831d9763', '2025-07-09 15:45:38', NULL, 1, '{\"manage_users\":true,\"manage_content\":true,\"manage_settings\":true,\"access_reports\":true}', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `digital_products`
--

CREATE TABLE `digital_products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `is_special_offer` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `digital_products`
--

INSERT INTO `digital_products` (`id`, `product_name`, `description`, `features`, `price`, `discount`, `image_path`, `is_featured`, `is_published`, `created_at`, `updated_at`, `instructions`, `is_special_offer`) VALUES
(9, 'تابلت برو مكس شكباله', 'tablet', '[\"\\u0645\\u0639\\u0627\\u0644\\u062c \\u0645\\u062c\\u0647\\u0648\\u0644\",\"3 \\u0631\\u0627\\u0645 \",\"32 \\u0642\\u064a\\u0642\\u0627\",\"\\u0628\\u064a\\u062f\\u0639\\u0645 \\u0627\\u0644\\u0644\\u063a\\u0629 \\u0627\\u0644\\u0639\\u0631\\u0628\\u064a\\u0629\",\"\\u0642\\u0627\\u0628\\u0644 \\u0644\\u0644\\u0645\\u0633 \",\"\\u064a\\u062e\\u0627\\u0641 \\u0631\\u0628\\u0647\"]', 4500.00, 6500.00, '../uploads/products/product_686970b24e240.png', 0, 1, '2025-07-05 21:36:34', NULL, NULL, 0),
(10, 'تابلت بتاع العيال للي  كانت فى 1ث', 'تابلت', '[\"\\u0634\\u063a\\u0627\\u0644\",\"\\u0628\\u064a\\u0634\\u063a\\u0644 \\u0628\\u0628\\u062c\\u064a\",\"\\u0645\\u0639\\u0627\\u0644\\u062c Core i5 \\u0642\\u0628\\u0644 \\u0627\\u0644\\u0627\\u0633\\u0644\\u0627\\u0645\"]', 9500.00, 10500.00, '../uploads/products/product_686971246fb7b.png', 1, 1, '2025-07-05 21:38:28', '2025-07-06 04:09:39', '<br />\r\n<b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>C:\\xampp\\htdocs\\A.S_Unlock\\api\\edit_product.php</b> on line <b>134</b><br />\r\n', 0),
(11, 'YouTube', 'youtube', '[\"\\u0628\\u0634\\u063a\\u0644 \\u0641\\u064a\\u062f\\u064a\\u0648\\u0647\\u0627\\u062a\",\"\\u0641\\u0649 \\u0645\\u0642\\u0627\\u0637\\u0639 Shorts\",\"\\u0628\\u062a\\u0627\\u0639 \\u062c\\u0648\\u062c\\u0644\",\"\\u0634\\u063a\\u0627\\u0644 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u062a\\u0627\\u0628\",\"\\u0627\\u0634\\u062a\\u0631\\u0627\\u0643 \\u0641\\u0649 \\u064a\\u0648\\u062a\\u064a\\u0648\\u0628 \\u0628\\u0631\\u064a\\u0645\\u064a\\u0645 1,000,000,000\"]', 1250.00, 1100.00, '../uploads/products/product_68697257e55cd.png', 1, 1, '2025-07-05 21:43:35', '2025-07-06 04:04:20', '', 1),
(12, 'بشر للبيع', 'asdf', '[\"\\u063a\\u064a\\u0631 \\u0645\\u062a\\u0632\\u0648\\u062c\",\"\\u0639\\u0628\\u062f \\u0641\\u0649 \\u0643\\u0644\\u064a\\u0629 \\u0627\\u0642\\u062a\\u0635\\u0627\\u062f \\u0648\\u0639\\u0644\\u0648\\u0645 \\u0633\\u064a\\u0627\\u0633\\u0629\",\"\\u0643\\u0644\\u064a\\u0629 \",\"\\u0642\\u0644\\u0628 \\u0648\\u0643\\u062a\\u0641\",\"\\u0639\\u0627\\u064a\\u0634 \\u0641\\u0649 \\u0627\\u0644\\u062c\\u064a\\u0632\\u0629\"]', 15.50, NULL, '../uploads/products/product_6869734857c08.jpeg', 1, 1, '2025-07-05 21:47:36', '2025-07-06 15:36:49', 'ارشادات الخطة', 1),
(14, 'يوم لا ينفع مال ولا بنون', 'يوم لا ينفع مال ولا بنون', '[\"\\u064a\\u0648\\u0645 \\u0644\\u0627 \\u064a\\u0646\\u0641\\u0639 \\u0645\\u0627\\u0644 \\u0648\\u0644\\u0627 \\u0628\\u0646\\u0648\\u0646\",\"\\u064a\\u0648\\u0645 \\u0644\\u0627 \\u064a\\u0646\\u0641\\u0639 \\u0645\\u0627\\u0644 \\u0648\\u0644\\u0627 \\u0628\\u0646\\u0648\\u0646\",\"\\u064a\\u0648\\u0645 \\u0644\\u0627 \\u064a\\u0646\\u0641\\u0639 \\u0645\\u0627\\u0644 \\u0648\\u0644\\u0627 \\u0628\\u0646\\u0648\\u0646\"]', 151.98, NULL, '../uploads/products/product_686a6da84bc8c.jpg', 1, 1, '2025-07-06 15:35:52', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL COMMENT 'مسار الصورة المحولة إلى WEBP',
  `audio_path` varchar(255) DEFAULT NULL COMMENT 'مسار الصوت (اختياري)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_featured` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `description`, `image_path`, `audio_path`, `created_at`, `updated_at`, `is_featured`) VALUES
(1, 'asdfasdf', 'asdf', '../uploads/images/img_686f054604451.webp', NULL, '2025-07-10 00:11:50', '2025-07-10 00:39:23', 0),
(4, 'شسيب', 'شسبشسيب', '../uploads/images/img_686f102694110.webp', '../uploads/audio/audio_686f1026b5fb2.m4a', '2025-07-10 00:58:14', '2025-07-10 00:58:14', 0),
(5, 'شسيب', 'شسيبشسيب', '../uploads/images/img_686f10539f2aa.webp', '../uploads/audio/audio_686f1053cc5fe.m4a', '2025-07-10 00:58:59', '2025-07-10 00:59:37', 0),
(6, 'Name Item', 'Description\r\nd', '../uploads/images/img_6870a1ff9d86e.webp', NULL, '2025-07-11 05:32:47', '2025-07-11 05:32:47', 0);

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `attempt_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `attempt_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_success` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`attempt_id`, `admin_id`, `username`, `ip_address`, `user_agent`, `attempt_time`, `is_success`) VALUES
(1, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-02 14:24:10', 1),
(2, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', '2025-07-02 14:30:44', 1),
(3, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', '2025-07-02 14:35:28', 1),
(4, 2, 'admin', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', '2025-07-02 14:39:53', 1),
(5, NULL, 'mostafa', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', '2025-07-02 14:42:57', 0),
(6, NULL, 'mostafa', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', '2025-07-02 14:43:41', 0),
(7, NULL, 'mostafa', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-02 14:44:03', 0),
(8, NULL, 'mostafa', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', '2025-07-02 14:44:07', 0),
(9, NULL, 'mostafamtaha', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', '2025-07-02 14:44:16', 0),
(10, NULL, 'mostaf', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', '2025-07-02 14:45:15', 0),
(11, NULL, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', '2025-07-02 14:45:27', 0),
(12, 5, 'test', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', '2025-07-02 14:48:33', 1),
(13, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', '2025-07-02 14:54:38', 1),
(14, NULL, 'mostafamtaha', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', '2025-07-02 15:00:58', 0),
(15, 7, 'mostafamtaha', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', '2025-07-02 15:01:10', 1),
(16, NULL, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-02 15:45:13', 0),
(17, 8, 'as', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-02 15:47:26', 1),
(18, 8, 'as', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', '2025-07-02 16:02:14', 1),
(19, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-02 19:34:28', 1),
(20, NULL, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-03 16:12:42', 0),
(21, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-03 16:12:51', 1),
(22, NULL, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', '2025-07-09 15:30:29', 0),
(23, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 15:31:32', 1),
(24, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', '2025-07-09 15:35:38', 1),
(25, 9, 'ggg', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 15:46:29', 1),
(26, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 16:06:07', 1),
(27, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 17:45:50', 1),
(28, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-09 18:15:02', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `payment_method` enum('vodafone_cash','instapay') NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `receipt_image` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','verified','completed','rejected') DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `plan_id`, `payment_method`, `phone_number`, `email`, `receipt_image`, `amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 12, 46, 'instapay', '01003504114', 'mostafamta347@gmail.com', '1751810429_1.png', 150.00, 'pending', '2025-07-06 17:00:29', NULL),
(2, 1, 12, 46, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751810434_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 17:00:34', NULL),
(3, 1, 12, 46, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751810613_cane-test.a0001.net_shop.php_i=1&limit=all&sort=default (1).png', 150.00, 'pending', '2025-07-06 17:03:33', NULL),
(4, 1, 12, 46, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751810638_cane-test.a0001.net_shop.php_i=1&limit=all&sort=default (1).png', 150.00, 'pending', '2025-07-06 17:03:58', NULL),
(5, 1, 12, 46, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751810647_cane-test.a0001.net_shop.php_i=1&limit=all&sort=default (1).png', 150.00, 'pending', '2025-07-06 17:04:07', NULL),
(6, 2, 12, 47, 'instapay', '01003504114', 'hk303792mmmm@gmail.com', '1751811677_cane-test.a0001.net_profile.php_page=settings.png', 150.00, 'pending', '2025-07-06 17:21:17', NULL),
(7, 2, 12, 47, 'vodafone_cash', '01003504114', 'hk303792mmmm@gmail.com', '1751813383_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 17:49:43', NULL),
(8, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751813758_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 17:55:58', NULL),
(9, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751814661_127.0.0.1_5500_22.png', 150.00, 'pending', '2025-07-06 18:11:13', NULL),
(10, 1, 12, 47, 'vodafone_cash', '01121561532', 'mostafamta347@gmail.com', '1751814746_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 18:12:39', NULL),
(11, 1, 12, 47, 'instapay', '01003504114', 'mostafamta347@gmail.com', '1751815123_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 18:18:48', NULL),
(12, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751825352_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'verified', '2025-07-06 21:09:15', '2025-07-10 00:04:24'),
(13, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751825419_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:10:21', NULL),
(14, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751825693_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:14:57', NULL),
(15, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751825752_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:15:56', NULL),
(16, 1, 12, 47, 'instapay', '01003504114', 'mostafamta347@gmail.com', '1751825859_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:17:41', NULL),
(17, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751826025_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:20:27', NULL),
(18, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751826134_127.0.0.1_5500_22.png', 150.00, 'pending', '2025-07-06 21:22:18', NULL),
(19, 1, 12, 47, 'instapay', '01003504114', 'mostafamta347@gmail.com', '1751826237_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:24:00', NULL),
(20, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751826293_127.0.0.1_5500_22.png', 150.00, 'pending', '2025-07-06 21:24:57', NULL),
(21, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751826551_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:29:13', NULL),
(22, 1, 12, 47, 'vodafone_cash', '01003350412', 'mostafamta347@gmail.com', '1751826880_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:34:42', NULL),
(23, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751827477_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:44:41', NULL),
(24, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751827862_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:51:06', NULL),
(25, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751828060_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:54:23', NULL),
(26, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751829791_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 22:23:15', NULL),
(27, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751829845_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 22:24:07', NULL),
(28, 1, 12, 46, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751829993_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 22:26:38', NULL),
(29, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751830289_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 22:31:31', NULL),
(30, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751830376_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 22:32:58', NULL),
(31, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751830497_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 22:35:07', NULL),
(32, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751831312_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 22:48:38', NULL),
(33, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751831478_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 22:51:23', NULL),
(34, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751831711_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 22:55:15', NULL),
(35, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751831940_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 22:59:03', NULL),
(36, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751832286_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 23:04:51', NULL),
(37, 1, 12, 47, 'vodafone_cash', '01056421221', 'mostafamta347@gmail.com', '1751832412_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 23:06:56', NULL),
(38, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751833025_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 23:17:08', NULL),
(39, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751917841_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-07 22:50:44', NULL),
(40, 1, 12, 47, 'vodafone_cash', '01121561532', 'mostafamta347@gmail.com', '1751918025_pexels-googledeepmind-25626431.jpg', 150.00, 'pending', '2025-07-07 22:53:48', NULL),
(41, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751919297_bg.png', 150.00, 'verified', '2025-07-07 23:15:02', '2025-07-09 04:14:51'),
(42, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751919505_bg.png', 150.00, 'pending', '2025-07-07 23:18:29', NULL),
(43, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751919695_bg.png', 150.00, 'pending', '2025-07-07 23:21:39', NULL),
(44, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751919783_bg.png', 150.00, 'pending', '2025-07-07 23:23:06', NULL),
(45, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751919911_bg.png', 150.00, 'pending', '2025-07-07 23:25:14', NULL),
(46, 1, 12, 47, 'instapay', '01003504114', 'mostafamta347@gmail.com', '1751920029_bg.png', 150.00, 'pending', '2025-07-07 23:27:12', NULL),
(47, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751920111_bg.png', 150.00, 'completed', '2025-07-07 23:28:34', '2025-07-10 00:04:12'),
(48, 1, 12, 47, 'instapay', '01003504114', 'mostafamta347@gmail.com', '1751920239_bg.png', 150.00, 'pending', '2025-07-07 23:30:42', NULL),
(49, 1, 12, 47, 'instapay', '01003504114', 'mostafamta347@gmail.com', '1751920239_bg.png', 150.00, 'pending', '2025-07-07 23:31:42', NULL),
(50, 1, 12, 47, 'vodafone_cash', '01003350412', 'mostafamta347@gmail.com', '1751920401_bg.png', 150.00, 'rejected', '2025-07-07 23:33:23', '2025-07-10 00:04:02'),
(51, 1, 12, 47, 'vodafone_cash', '01056421221', 'mostafamta347@gmail.com', '1751920680_bg.png', 150.00, 'pending', '2025-07-07 23:38:03', NULL),
(52, 1, 12, 47, 'instapay', '01003504114', 'mostafamta347@gmail.com', '1751920992_download.png', 150.00, 'pending', '2025-07-07 23:43:16', NULL),
(53, 1, 12, 47, 'instapay', '01003504114', 'mostafamta347@gmail.com', '1751921187_download.png', 150.00, 'completed', '2025-07-07 23:46:29', '2025-07-09 18:39:28');

-- --------------------------------------------------------

--
-- Table structure for table `product_plans`
--

CREATE TABLE `product_plans` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `plan_type` varchar(50) NOT NULL,
  `plan_name` varchar(255) NOT NULL,
  `plan_price` decimal(10,2) NOT NULL,
  `plan_features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`plan_features`)),
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_plans`
--

INSERT INTO `product_plans` (`id`, `product_id`, `plan_type`, `plan_name`, `plan_price`, `plan_features`, `created_at`, `updated_at`) VALUES
(1, 5, '1', 'شبيشسيب<?php require_once \'../includes/config.php\';  header(\'Content-Type: application/json\');  try {     if (!isset($_GET[\'id\'])) {         throw new Exception(\'معرف المنتج غير موجود\');     }          $productId = $_GET[\'id\'];     $stmt = $pdo->prepare(\"', 0.00, '[\"\\u0634\\u0633\\u064a\\u0628\",\"<?php require_once \'..\\/includes\\/config.php\';  header(\'Content-Type: application\\/json\');  try {     if (!isset($_GET[\'id\'])) {         throw new Exception(\'\\u0645\\u0639\\u0631\\u0641 \\u0627\\u0644\\u0645\\u0646\\u062a\\u062c \\u063a\\u064a\\u0631 \\u0645\\u0648\\u062c\\u0648\\u062f\');     }          $productId = $_GET[\'id\'];     $stmt = $pdo->prepare(\\\"         SELECT              d.*,             p.plan_type, p.plan_name, p.plan_price, p.plan_features         FROM digital_products d         LEFT JOIN product_plans p ON d.id = p.product_id         WHERE d.id = ?     \\\");     $stmt->execute([$productId]);     $product = $stmt->fetch(PDO::FETCH_ASSOC);          if (!$product) {         throw new Exception(\'\\u0627\\u0644\\u0645\\u0646\\u062a\\u062c \\u063a\\u064a\\u0631 \\u0645\\u0648\\u062c\\u0648\\u062f\');     }          \\/\\/ \\u062a\\u062d\\u0648\\u064a\\u0644 features \\u0648 plan_features \\u0645\\u0646 JSON \\u0625\\u0644\\u0649 \\u0645\\u0635\\u0641\\u0648\\u0641\\u0629     $product[\'features\'] = json_decode($product[\'features\'], true);     $product[\'plan_features\'] = json_decode($product[\'plan_features\'], true);          echo json_encode($product);      } catch (Exception $e) {     echo json_encode([         \'error\' => $e->getMessage()     ]); }\",\"<?php require_once \'..\\/includes\\/config.php\';  header(\'Content-Type: application\\/json\');  try {     if (!isset($_GET[\'id\'])) {         throw new Exception(\'\\u0645\\u0639\\u0631\\u0641 \\u0627\\u0644\\u0645\\u0646\\u062a\\u062c \\u063a\\u064a\\u0631 \\u0645\\u0648\\u062c\\u0648\\u062f\');     }          $productId = $_GET[\'id\'];     $stmt = $pdo->prepare(\\\"         SELECT              d.*,             p.plan_type, p.plan_name, p.plan_price, p.plan_features         FROM digital_products d         LEFT JOIN product_plans p ON d.id = p.product_id         WHERE d.id = ?     \\\");     $stmt->execute([$productId]);     $product = $stmt->fetch(PDO::FETCH_ASSOC);          if (!$product) {         throw new Exception(\'\\u0627\\u0644\\u0645\\u0646\\u062a\\u062c \\u063a\\u064a\\u0631 \\u0645\\u0648\\u062c\\u0648\\u062f\');     }          \\/\\/ \\u062a\\u062d\\u0648\\u064a\\u0644 features \\u0648 plan_features \\u0645\\u0646 JSON \\u0625\\u0644\\u0649 \\u0645\\u0635\\u0641\\u0648\\u0641\\u0629     $product[\'features\'] = json_decode($product[\'features\'], true);     $product[\'plan_features\'] = json_decode($product[\'plan_features\'], true);          echo json_encode($product);      } catch (Exception $e) {     echo json_encode([         \'error\' => $e->getMessage()     ]); }\"]', '2025-07-03 18:48:37', '2025-07-03 19:02:54'),
(7, 6, '1', 'asdfasdf', 125.00, '[\"vxczxcvasdfasdf\",\"asdfasdfasdfsaf\"]', '2025-07-03 19:04:20', '2025-07-03 20:03:47'),
(24, 7, '1', '', 0.00, '[]', '2025-07-04 17:03:43', NULL),
(25, 8, '3', 'test plan', 210.00, '[\"ddd\",\"ddd\"]', '2025-07-05 18:26:05', '2025-07-05 18:27:19'),
(28, 10, '2', '<br /><b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>C:\\xampp\\htdocs\\A.S_Unlock\\api\\edit_product.php</b> on line <b>211</b><br />', 120.00, '[]', '2025-07-05 21:40:43', NULL),
(29, 11, '1', '<br /><b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>C:\\xampp\\htdocs\\A.S_Unlock\\api\\edit_product.php</b> on line <b>211</b><br />', 0.00, '[]', '2025-07-05 21:44:48', NULL),
(30, 13, '1', 'fsdg', 0.00, '[\"asdf\",\"asdf\"]', '2025-07-05 22:43:11', '2025-07-05 22:44:11'),
(35, 13, '3', 'شسيب', 0.00, '[\"\\u0634\\u0633\\u064a\\u0628\"]', '2025-07-05 22:58:11', NULL),
(36, 12, '2', 'يشيب', 0.00, '[\"\\u0634\\u0633\\u064a\\u0628\\u0634\\u0633\\u064a\\u0628\",\"\\u0634\\u0633\\u064a\\u0628\\u0634\\u064a\\u0628\"]', '2025-07-05 23:23:36', NULL),
(37, 12, '1', 'يشيبي', 120.00, '[\"\\u0634\\u0633\\u064a\\u0628\\u0634\\u0633\\u064a\\u0628\",\"\\u0634\\u0633\\u064a\\u0628\\u0634\\u064a\\u0628\"]', '2025-07-05 23:24:16', NULL),
(40, 12, '3', 'يشيبي', 120.00, '[\"\\u0634\\u0633\\u064a\\u0628\\u0634\\u0633\\u064a\\u0628\",\"\\u0634\\u0633\\u064a\\u0628\",\"\\u0634\\u0633\\u064a\\u0628\\u0634\\u0633\\u064a\\u0628\",\"\\u0634\\u0633\\u064a\\u0628\"]', '2025-07-05 23:49:01', NULL),
(41, 11, '1', '<br /><b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>C:\\xampp\\htdocs\\A.S_Unlock\\api\\edit_product.php</b> on line <b>211</b><br />', 0.00, '[]', '2025-07-06 04:04:20', NULL),
(42, 10, '1', '', 0.00, '[]', '2025-07-06 04:06:36', NULL),
(43, 10, '2', '<br /><b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>C:\\xampp\\htdocs\\A.S_Unlock\\api\\edit_product.php</b> on line <b>211</b><br />', 120.00, '[]', '2025-07-06 04:09:16', NULL),
(44, 10, '2', '<br /><b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>C:\\xampp\\htdocs\\A.S_Unlock\\api\\edit_product.php</b> on line <b>211</b><br />', 120.00, '[]', '2025-07-06 04:09:39', NULL),
(45, 12, '1', 'الاساسية', 150.00, '[\"\\u064a\\u0648\\u0645 \\u0644\\u0627 \\u064a\\u0646\\u0641\\u0639 \\u0645\\u0627\\u0644 \\u0648\\u0644\\u0627 \\u0628\\u0646\\u0648\\u0646\",\"\\u064a\\u0648\\u0645 \\u0644\\u0627 \\u064a\\u0646\\u0641\\u0639 \\u0645\\u0627\\u0644 \\u0648\\u0644\\u0627 \\u0628\\u0646\\u0648\\u0646\"]', '2025-07-06 15:36:19', NULL),
(46, 12, '2', 'الاكثر طلبٌا', 150.00, '[\"\\u064a\\u0648\\u0645 \\u0644\\u0627 \\u064a\\u0646\\u0641\\u0639 \\u0645\\u0627\\u0644 \\u0648\\u0644\\u0627 \\u0628\\u0646\\u0648\\u0646\",\"\\u064a\\u0648\\u0645 \\u0644\\u0627 \\u064a\\u0646\\u0641\\u0639 \\u0645\\u0627\\u0644 \\u0648\\u0644\\u0627 \\u0628\\u0646\\u0648\\u0646\"]', '2025-07-06 15:36:38', NULL),
(47, 12, '3', 'الاحترافية', 150.00, '[\"\\u064a\\u0648\\u0645 \\u0644\\u0627 \\u064a\\u0646\\u0641\\u0639 \\u0645\\u0627\\u0644 \\u0648\\u0644\\u0627 \\u0628\\u0646\\u0648\\u0646\",\"\\u064a\\u0648\\u0645 \\u0644\\u0627 \\u064a\\u0646\\u0641\\u0639 \\u0645\\u0627\\u0644 \\u0648\\u0644\\u0627 \\u0628\\u0646\\u0648\\u0646\"]', '2025-07-06 15:36:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `security_questions`
--

CREATE TABLE `security_questions` (
  `question_id` int(11) NOT NULL,
  `question_text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `security_questions`
--

INSERT INTO `security_questions` (`question_id`, `question_text`) VALUES
(1, 'ما اسم أول مدرسة التحقت بها؟'),
(2, 'ما هو اسم حي طفولتك؟'),
(3, 'ما هو اسم والدتك قبل الزواج؟'),
(4, 'ما هو اسم أول حيوان أليف امتلكته؟'),
(5, 'ما هو أفضل صديق في طفولتك؟');

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `upload_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('uploading','completed','paused','failed') NOT NULL DEFAULT 'uploading',
  `link_url` varchar(255) DEFAULT NULL,
  `link_name` varchar(255) DEFAULT NULL,
  `link_size` int(11) DEFAULT NULL,
  `link_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uploads`
--

INSERT INTO `uploads` (`id`, `file_name`, `file_path`, `file_size`, `file_type`, `description`, `upload_date`, `status`, `link_url`, `link_name`, `link_size`, `link_description`) VALUES
(171, 'GrammarlyInstaller', '../uploads/file-1752011887490-324_GrammarlyInstaller.cZL3a9fbbwzc8v0ic6610b80.exe', 18603920, 'application/x-dosexec', '', '2025-07-09 00:59:54', 'completed', NULL, NULL, NULL, NULL),
(172, 'QuickShare', '../uploads/file-1752011892268-794_QuickShareSetup.exe', 11332480, 'application/x-dosexec', '', '2025-07-09 00:59:55', 'completed', NULL, NULL, NULL, NULL),
(173, 'PCRemoteReceiver', '../uploads/file-1752011901797-821_PCRemoteReceiverSetup_7_5_8.exe', 67351008, 'application/x-dosexec', '', '2025-07-09 00:59:55', 'completed', NULL, NULL, NULL, NULL),
(175, 'tick_win_setup', '../uploads/file-1752011932442-956_tick_win_setup_release_x64_5060.exe', 15775504, 'application/x-dosexec', '', '2025-07-09 00:59:55', 'completed', NULL, NULL, NULL, NULL),
(176, '', '', 0, '', NULL, '2025-07-10 00:07:30', 'completed', 'dddafds', 'asdf', 10221, 'dddaf'),
(177, '', '', 0, '', NULL, '2025-07-10 00:08:04', 'completed', 'asdf', 'asdf', 12210, 'asdf');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(20) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `banned` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `google_id`, `avatar`, `created_at`, `phone`, `verified`, `banned`) VALUES
(1, 'Mostafam Ta3', 'mostafamta347@gmail.com', NULL, '118380663984086018501', 'https://lh3.googleusercontent.com/a/ACg8ocKoPaBU55MVAUPWK8vAXRZL5l2zajBxEXOg3H-IujH4wFfVIA=s96-c', '2025-07-01 17:48:48', NULL, 1, 1),
(2, 'Mmm Mm', 'hk303792mmmm@gmail.com', NULL, '112343287434448403765', 'https://lh3.googleusercontent.com/a/ACg8ocJJA2_4La57lpo17PrrsseW814JZlZIttFLu98VwWjPzaNu-Q=s96-c', '2025-07-01 17:53:41', NULL, 0, 0),
(3, 'Miraat al-Mumin', 'miraatalmumin@gmail.com', NULL, '110520728785467475730', 'https://lh3.googleusercontent.com/a/ACg8ocK4gVWJYCKqft4oVQh15lUYBiE6TzMKORmIKykivgV57q044Q=s96-c', '2025-07-01 18:16:41', NULL, 0, 0),
(4, 'mostafa mtaha', 'admin@gmail.com', '$2y$10$s4iO5bv0J7O7paYb2FCnUefruvOLufZ94/iakUTKH7UtyKN5ZOsMS', NULL, NULL, '2025-07-01 18:35:09', NULL, 0, 0),
(5, 'asdfasdf', 'dd@gmail.com', '$2y$10$UqmaDes6Ml3JSuXTCH6.aeKl1xp8NItW8iZsN/6Bw4oltbITE5552', NULL, NULL, '2025-07-01 18:39:02', NULL, 0, 0),
(6, 'localhost', 'localhost@gmail.com', '$2y$10$LdVSkiSs9i.PPpoWWpnDNeRRUPpqqFQP7lx45TST4k/MuKzQJwe3C', NULL, NULL, '2025-07-02 12:55:37', NULL, 0, 0),
(7, 'Sahat Al_llm', 'sahatalllm@gmail.com', NULL, '107681726258782612532', 'https://lh3.googleusercontent.com/a/ACg8ocLrDbRb0QsuRgLrzrZXTm1_3TlNq0CmHB9ElRUKmvJIDJIO=s96-c', '2025-07-09 14:49:13', NULL, 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `digital_products`
--
ALTER TABLE `digital_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`attempt_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indexes for table `product_plans`
--
ALTER TABLE `product_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `security_questions`
--
ALTER TABLE `security_questions`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `google_id` (`google_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `digital_products`
--
ALTER TABLE `digital_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `attempt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `product_plans`
--
ALTER TABLE `product_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `security_questions`
--
ALTER TABLE `security_questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD CONSTRAINT `login_attempts_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
