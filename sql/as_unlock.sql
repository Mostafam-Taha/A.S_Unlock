-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2025 at 06:03 PM
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
(1, 'mostafamtaha1', 'mostafamtaha66@gmail.com', '$2y$12$hqpq/7YFjFv/KOr0O.zPfuUNA3j5T8hk.PhXCmWgeCtqI83L.zDY2', 1, '4593b0e03c93a1ff272d57005fa0f8a6e44078e4865e75eadd558b69141628ac', '2025-07-02 14:15:21', NULL, 1, '{\"manage_users\":true,\"manage_content\":true,\"manage_settings\":true,\"access_reports\":true}', '459e7fe1d5e6e32560dc8b01823034fd319cc892180c4c33b01eb268e62f7b46', '2025-08-01 16:54:38', '362592', '2025-07-02 19:25:11'),
(3, 'mosraf', 'mostafamtaha@gmail.com', '$2y$12$7inH0crTFR.e7JBCelahjuWUK1XY2d/cs32ogTIoIrv5KIr5jPRL.', 1, '49960de5880e8c687434170f6476605b8fe4aeb9a28632c7995cf3ba831d9763', '2025-07-02 14:42:38', NULL, 1, '{\"manage_users\":true,\"manage_content\":true,\"manage_settings\":true,\"access_reports\":true}', NULL, NULL, NULL, NULL),
(4, 'teste', 'localhost@gmail.com', '$2y$12$45sbF88hyTNa6xg/YuO0vuSj.uxp2yj5W9r068TbEOlHiXFBcaWfa', 1, '4593b0e03c93a1ff272d57005fa0f8a6e44078e4865e75eadd558b69141628ac', '2025-07-02 14:47:30', NULL, 1, '{\"manage_users\":true,\"manage_content\":true,\"manage_settings\":true,\"access_reports\":true}', NULL, NULL, NULL, NULL),
(5, 'test', 'localhost@gmail.codm', '$2y$12$0Zbbs6lZF24LVHT/NI8Aje4/Olphi4dOl4gjwu5BTGrR6.fh.p.z2', 1, '4593b0e03c93a1ff272d57005fa0f8a6e44078e4865e75eadd558b69141628ac', '2025-07-02 14:48:18', NULL, 1, '{\"manage_users\":true,\"manage_content\":true,\"manage_settings\":true,\"access_reports\":true}', '818d67bd333198d2785289a2ab22a55ab5dbbf446bff7554d987d934277f0f74', '2025-08-01 16:48:33', NULL, NULL),
(6, 'mostaf', 'o@gmail.com', '$2y$12$LG84C6X/3Ht.p6ySJM7qIOs2q.qjy1S0om8yKwgfgSBZBDLKyZN1W', 2, 'f0e4c2f76c58916ec258f246851bea091d14d4247a2fc3e18694461b1816e13b', '2025-07-02 14:52:22', NULL, 1, '{\"manage_users\":true,\"manage_content\":true,\"manage_settings\":true,\"access_reports\":true}', NULL, NULL, NULL, NULL),
(7, 'mostafamtaha', 'j@gmail.com', '$2y$12$uVQXtmieQBcqu1/4Vhyum.qCOW3MXygyUFga6dP.JEh1/2NqpuGSa', 2, '4593b0e03c93a1ff272d57005fa0f8a6e44078e4865e75eadd558b69141628ac', '2025-07-02 15:00:30', NULL, 1, '{\"manage_users\":true,\"manage_content\":true,\"manage_settings\":true,\"access_reports\":true}', NULL, NULL, NULL, NULL),
(8, 'AS', 'AS@gmail.com', '$2y$12$mNH4GYXHp0qdeNpL3/UmUuZD3FlTMntXO0sSO5PJh3mKb9ZihdOR2', 2, '4593b0e03c93a1ff272d57005fa0f8a6e44078e4865e75eadd558b69141628ac', '2025-07-02 15:47:00', NULL, 1, '{\"manage_users\":true,\"manage_content\":true,\"manage_settings\":true,\"access_reports\":true}', '47c0c22d3fdcbd15f03326a40df1c8e559a7939975e0d4a2da865079918255f3', '2025-08-01 17:47:25', NULL, NULL),
(9, 'ggg', 'gg@gmail.com', '$2y$12$Uql2WxIg4viMAvqBHVLMiOVarUhDJkcvzAdzyLB8TIsdPc5TzytAG', 4, '49960de5880e8c687434170f6476605b8fe4aeb9a28632c7995cf3ba831d9763', '2025-07-09 15:45:38', NULL, 1, '{\"manage_users\":true,\"manage_content\":true,\"manage_settings\":true,\"access_reports\":true}', NULL, NULL, NULL, NULL),
(10, 'mostafamtaha11', 'admin@gmail.com', '$2y$12$.ms2SPfFHGz2BMCmsXZ/Kub9eIm2z51wc5dq3e2iU53fJV.lYCjtC', 3, '4593b0e03c93a1ff272d57005fa0f8a6e44078e4865e75eadd558b69141628ac', '2025-07-15 19:15:16', NULL, 1, '{\"manage_users\":true,\"manage_content\":true,\"manage_settings\":true,\"access_reports\":true}', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `digital_products`
--

CREATE TABLE `digital_products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `service_type` varchar(255) DEFAULT NULL,
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
  `is_special_offer` tinyint(1) DEFAULT 0,
  `warranty_duration_days` int(11) DEFAULT NULL COMMENT 'مدة الضمان بالأيام'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `digital_products`
--

INSERT INTO `digital_products` (`id`, `product_name`, `service_type`, `description`, `features`, `price`, `discount`, `image_path`, `is_featured`, `is_published`, `created_at`, `updated_at`, `instructions`, `is_special_offer`, `warranty_duration_days`) VALUES
(21, 'SM-T509', NULL, 'SM-T509 ', '[\"SM-T509\",\"SM-T509\",\"SM-T509\",\"SM-T509\"]', 70.00, NULL, '../uploads/products/product_6878517c48e7b.png', 1, 1, '2025-07-17 04:27:24', '2025-07-17 04:38:39', 'الارشادات', 1, 201),
(22, 'Tablet A9+', '', 'SM-T509', '[\"Tablet A9+\",\"Tablet A9+\",\"Tablet A9+\"]', 75.00, 5.00, '../uploads/products/product_687851c255cab.png', 1, 1, '2025-07-17 04:28:34', '2025-07-21 17:46:44', '', 0, 1),
(25, 'خدمة رقم 1', '', '.\r\n', '[\"\\u062e\\u062f\\u0645\\u0629 \\u0631\\u0642\\u0645 1\",\"\\u062e\\u062f\\u0645\\u0629 \\u0631\\u0642\\u0645 1\"]', 120.00, NULL, '../uploads/products/product_687857ffaf47b.png', 0, 1, '2025-07-17 04:55:11', '2025-07-21 17:47:09', '', 0, 665),
(29, 'InstaPay', NULL, 'InstaPay', '[\"InstaPay\",\"InstaPay\",\"InstaPay\",\"InstaPay\"]', 120.00, NULL, '../uploads/products/product_68785a5e8f682.jfif', 0, 1, '2025-07-17 05:05:18', '2025-07-21 00:52:55', 'شسيبشسيبشسي', 0, 60),
(32, 'JavaScript', 'JavaScript', 'JS', '[\"JavaScript\",\"JavaScript\",\"JavaScript\"]', 100.00, 10.00, '../uploads/products/product_687d8f410085a.png', 1, 0, '2025-07-21 03:06:19', '2025-07-21 17:46:00', 'JavaScript', 1, 100),
(33, 'أول شبر', 'أول شبر', 'أول شبر', '[\"\\u0623\\u0648\\u0644 \\u0634\\u0628\\u0631\",\"\\u0623\\u0648\\u0644 \\u0634\\u0628\\u0631\",\"\\u0623\\u0648\\u0644 \\u0634\\u0628\\u0631\",\"\\u0623\\u0648\\u0644 \\u0634\\u0628\\u0631\"]', 132.00, 2.00, '../uploads/products/product_687d8efc997b3.jpg', 1, 0, '2025-07-21 03:51:08', '2025-07-21 04:11:29', '', 0, 100),
(34, 'JS', 'JavaScript', 'أول شبر', '[\"JavaScript\",\"JavaScript\",\"JavaScript\"]', 198.00, 8.00, '../uploads/products/product_687d900876096.jpg', 1, 1, '2025-07-21 03:55:36', '2025-07-21 19:57:54', 'JS', 1, 12),
(35, 'منتج', 'Website', 'وصف', '[\"\\u0627\\u0644\\u0645\\u064a\\u0632\\u0629 \",\"Website\",\"Website\"]', 1568.00, 1000.00, '../uploads/products/product_687da0f4abe97.png', 0, 1, '2025-07-21 05:07:48', NULL, NULL, 0, 65);

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
(4, 'شسيب', 'شسبشسيب', '../uploads/images/img_686f102694110.webp', '../uploads/audio/audio_686f1026b5fb2.m4a', '2025-07-10 00:58:14', '2025-07-21 01:41:44', 1),
(5, 'شسيب', 'شسيبشسيب', '../uploads/images/img_686f10539f2aa.webp', '../uploads/audio/audio_686f1053cc5fe.m4a', '2025-07-10 00:58:59', '2025-07-20 00:21:27', 1),
(7, 'asdf', 'asdf', '../uploads/images/img_6870a9baa07ac.webp', NULL, '2025-07-11 06:05:47', '2025-07-12 22:57:15', 1),
(8, 'itam', 'd', '../uploads/images/img_6870ab8cd8c1e.webp', '../uploads/audio/audio_6870ab8cef1ef.mp3', '2025-07-11 06:13:33', '2025-07-12 22:57:14', 1),
(9, 'عميل طرش', '\"عملاؤنا هم رواد الأعمال ومديرو الشركات الناشئة والمؤسسات المتوسطة الذين يبحثون عن حلول برمجية مخصصة لتحسين إنتاجيتهم. هم بحاجة إلى أنظمة سهلة الاستخدام وقابلة للتطوير، مع دعم فني ممتاز لمساعدتهم على تحقيق أهدافهم التقنية.\"', '../uploads/images/img_6872ad539ff81.webp', NULL, '2025-07-12 18:45:39', '2025-07-21 01:16:11', 1);

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `icon` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `links`
--

INSERT INTO `links` (`id`, `name`, `url`, `is_published`, `created_at`, `icon`) VALUES
(7, 'Youtube', 'https://www.youtube.com/@as_unlock', 1, '2025-07-21 03:13:25', '<i class=\"fa-brands fa-youtube\"></i>'),
(8, 'Instgram', 'd', 0, '2025-07-21 03:13:55', '<i class=\"fa-brands fa-instagram\"></i>'),
(9, 't.me', 'https://t.me/as_unlock', 1, '2025-07-21 03:15:27', '<i class=\"fa-brands fa-telegram\"></i>'),
(10, 'Whatsapp', 'https://wa.me/201069062005?text=مرحبًا%20اريد%20الاستفسار%20عن', 1, '2025-07-21 03:17:30', '<i class=\"fa-brands fa-whatsapp\"></i>'),
(14, 'asdf', 'asf', 1, '2025-07-21 03:57:46', '<i class=\"fa-brands fa-facebook\"></i>'),
(15, 'asdf', 'sdf', 1, '2025-07-21 03:57:55', '<i class=\"fa-brands fa-facebook-messenger\"></i>');

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
  `device_model` varchar(100) DEFAULT NULL,
  `device_type` varchar(50) DEFAULT NULL,
  `ram` varchar(20) DEFAULT NULL,
  `storage` varchar(20) DEFAULT NULL,
  `processor` varchar(50) DEFAULT NULL,
  `os_version` varchar(50) DEFAULT NULL,
  `location_coords` varchar(50) DEFAULT NULL,
  `battery_status` varchar(20) DEFAULT NULL,
  `attempt_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_success` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`attempt_id`, `admin_id`, `username`, `ip_address`, `user_agent`, `device_model`, `device_type`, `ram`, `storage`, `processor`, `os_version`, `location_coords`, `battery_status`, `attempt_time`, `is_success`) VALUES
(1, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-02 14:24:10', 1),
(2, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-02 14:30:44', 1),
(3, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-02 14:35:28', 1),
(5, NULL, 'mostafa', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-02 14:42:57', 0),
(6, NULL, 'mostafa', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-02 14:43:41', 0),
(7, NULL, 'mostafa', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-02 14:44:03', 0),
(8, NULL, 'mostafa', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-02 14:44:07', 0),
(9, NULL, 'mostafamtaha', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-02 14:44:16', 0),
(10, NULL, 'mostaf', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-02 14:45:15', 0),
(11, NULL, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-02 14:45:27', 0),
(12, 5, 'test', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-02 14:48:33', 1),
(13, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-02 14:54:38', 1),
(14, NULL, 'mostafamtaha', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-02 15:00:58', 0),
(15, 7, 'mostafamtaha', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-02 15:01:10', 1),
(16, NULL, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-02 15:45:13', 0),
(17, 8, 'as', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-02 15:47:26', 1),
(18, 8, 'as', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-02 16:02:14', 1),
(19, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-02 19:34:28', 1),
(20, NULL, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-03 16:12:42', 0),
(21, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-03 16:12:51', 1),
(22, NULL, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-09 15:30:29', 0),
(23, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-09 15:31:32', 1),
(24, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-09 15:35:38', 1),
(25, 9, 'ggg', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-09 15:46:29', 1),
(26, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-09 16:06:07', 1),
(27, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-09 17:45:50', 1),
(28, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-09 18:15:02', 1),
(29, NULL, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-11 06:02:35', 0),
(30, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-11 06:02:43', 1),
(31, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-12 17:53:37', 1),
(32, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-13 12:46:59', 1),
(33, NULL, 'mostafamtaha', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-14 02:15:03', 0),
(34, NULL, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-14 02:15:16', 0),
(35, NULL, 'mostafamtaha', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-14 02:15:30', 0),
(36, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-14 02:15:40', 1),
(37, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-14 02:34:14', 1),
(38, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-14 13:29:36', 1),
(39, NULL, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-15 08:48:51', 0),
(40, NULL, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-15 08:49:38', 0),
(41, NULL, 'mostafamtaha', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-15 08:49:57', 0),
(42, NULL, 'mostafamtaha', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-15 08:50:51', 0),
(43, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-15 08:51:04', 1),
(44, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-15 10:05:43', 1),
(45, NULL, 'mostafamtaha', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-15 10:08:08', 0),
(46, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-15 10:08:20', 1),
(47, NULL, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-15 15:39:34', 0),
(48, NULL, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-15 15:39:43', 0),
(49, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-15 15:39:51', 1),
(50, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-15 19:03:23', 1),
(51, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-15 19:06:19', 1),
(52, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-15 19:13:31', 1),
(53, 10, 'mostafamtaha11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-15 19:15:28', 1),
(54, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-15 19:18:45', 1),
(55, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Sa', NULL, '4 GB', 'غير معروف', '4 أنوية', 'Win32', '', '100% (شحن)', '2025-07-15 19:21:31', 1),
(56, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome', NULL, '4 GB', 'غير معروف', '4 أنوية', 'Win32', '', '100% (شحن)', '2025-07-15 19:22:48', 1),
(57, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Sa', NULL, '4 GB', 'غير معروف', '4 أنوية', 'Win32', '29.7967,31.2854', '100% (شحن)', '2025-07-16 00:22:04', 1),
(58, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Sa', NULL, '4 GB', 'غير معروف', '4 أنوية', 'Win32', '29.7967,31.2854', '100% (شحن)', '2025-07-16 03:27:58', 1),
(59, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Sa', NULL, '4 GB', 'غير معروف', '4 أنوية', 'Win32', '29.7866,31.3013', '100% (شحن)', '2025-07-16 23:45:05', 1),
(60, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Sa', NULL, '4 GB', 'غير معروف', '4 أنوية', 'Win32', '29.7866,31.3013', '100% (شحن)', '2025-07-17 00:53:38', 1),
(61, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Sa', NULL, '4 GB', 'غير معروف', '4 أنوية', 'Win32', '30.3992,31.24', '100% (شحن)', '2025-07-19 20:44:51', 1),
(62, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Sa', NULL, '4 GB', 'غير معروف', '4 أنوية', 'Win32', '30.0434,31.2352', '100% (شحن)', '2025-07-20 20:56:35', 1),
(63, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Sa', NULL, '4 GB', 'غير معروف', '4 أنوية', 'Win32', '30.0434,31.2352', '100% (شحن)', '2025-07-21 12:37:56', 1),
(64, 1, 'mostafamtaha1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Sa', NULL, '4 GB', 'غير معروف', '4 أنوية', 'Win32', '', '100% (شحن)', '2025-07-22 13:43:09', 1);

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
  `subscription_email` varchar(255) DEFAULT NULL,
  `receipt_image` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','verified','completed','rejected') DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `plan_id`, `payment_method`, `phone_number`, `email`, `subscription_email`, `receipt_image`, `amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 12, 46, 'instapay', '01003504114', 'mostafamta347@gmail.com', NULL, '1751810429_1.png', 150.00, 'completed', '2025-06-25 17:00:29', '2025-07-14 18:04:35'),
(2, 1, 12, 46, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751810434_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 17:00:34', NULL),
(3, 1, 12, 46, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751810613_cane-test.a0001.net_shop.php_i=1&limit=all&sort=default (1).png', 150.00, 'pending', '2025-07-06 17:03:33', NULL),
(4, 1, 11, 46, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751810638_cane-test.a0001.net_shop.php_i=1&limit=all&sort=default (1).png', 150.00, 'completed', '2025-07-06 17:03:58', '2025-07-14 18:04:55'),
(5, 1, 12, 46, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751810647_cane-test.a0001.net_shop.php_i=1&limit=all&sort=default (1).png', 150.00, 'pending', '2025-07-06 17:04:07', NULL),
(6, 2, 12, 47, 'instapay', '01003504114', 'hk303792mmmm@gmail.com', NULL, '1751811677_cane-test.a0001.net_profile.php_page=settings.png', 150.00, 'pending', '2025-07-06 17:21:17', NULL),
(7, 2, 12, 47, 'vodafone_cash', '01003504114', 'hk303792mmmm@gmail.com', NULL, '1751813383_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 17:49:43', NULL),
(8, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751813758_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 17:55:58', NULL),
(9, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751814661_127.0.0.1_5500_22.png', 150.00, 'pending', '2025-07-06 18:11:13', NULL),
(10, 1, 12, 47, 'vodafone_cash', '01121561532', 'mostafamta347@gmail.com', NULL, '1751814746_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 18:12:39', NULL),
(11, 1, 12, 47, 'instapay', '01003504114', 'mostafamta347@gmail.com', NULL, '1751815123_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 18:18:48', NULL),
(12, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751825352_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'verified', '2025-07-06 21:09:15', '2025-07-10 00:04:24'),
(13, 1, 10, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751825419_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'completed', '2025-06-10 21:10:21', '2025-07-14 18:05:42'),
(14, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751825693_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'completed', '2025-06-24 21:14:57', '2025-07-14 18:06:36'),
(15, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751825752_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:15:56', NULL),
(16, 1, 12, 47, 'instapay', '01003504114', 'mostafamta347@gmail.com', NULL, '1751825859_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:17:41', NULL),
(17, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751826025_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:20:27', NULL),
(18, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751826134_127.0.0.1_5500_22.png', 150.00, 'pending', '2025-07-06 21:22:18', NULL),
(19, 1, 12, 47, 'instapay', '01003504114', 'mostafamta347@gmail.com', NULL, '1751826237_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:24:00', NULL),
(20, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751826293_127.0.0.1_5500_22.png', 150.00, 'completed', '2025-06-10 21:24:57', '2025-07-14 18:05:53'),
(21, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751826551_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:29:13', NULL),
(22, 1, 12, 47, 'vodafone_cash', '01003350412', 'mostafamta347@gmail.com', NULL, '1751826880_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:34:42', NULL),
(23, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751827477_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:44:41', NULL),
(24, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751827862_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:51:06', NULL),
(25, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751828060_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'completed', '2025-05-15 21:54:23', '2025-07-14 18:07:02'),
(26, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751829791_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 22:23:15', NULL),
(27, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751829845_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 22:24:07', NULL),
(28, 1, 12, 46, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751829993_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 22:26:38', NULL),
(29, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751830289_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'completed', '2025-05-07 22:31:31', '2025-07-14 18:07:37'),
(30, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751830376_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'completed', '2023-04-11 22:32:58', '2025-07-14 18:07:55'),
(31, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751830497_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 22:35:07', NULL),
(32, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751831312_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 22:48:38', NULL),
(33, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751831478_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 22:51:23', NULL),
(34, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751831711_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'completed', '2025-04-20 22:55:15', '2025-07-14 18:08:53'),
(35, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751831940_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 22:59:03', NULL),
(36, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751832286_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 23:04:51', NULL),
(37, 1, 12, 47, 'vodafone_cash', '01056421221', 'mostafamta347@gmail.com', NULL, '1751832412_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'completed', '2025-05-20 23:06:56', '2025-07-14 18:09:09'),
(38, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751833025_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 23:17:08', NULL),
(39, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751917841_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-07 22:50:44', NULL),
(40, 1, 12, 47, 'vodafone_cash', '01121561532', 'mostafamta347@gmail.com', NULL, '1751918025_pexels-googledeepmind-25626431.jpg', 150.00, 'pending', '2025-07-07 22:53:48', NULL),
(41, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751919297_bg.png', 150.00, 'verified', '2025-07-07 23:15:02', '2025-07-09 04:14:51'),
(42, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751919505_bg.png', 150.00, 'pending', '2025-07-07 23:18:29', NULL),
(43, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751919695_bg.png', 150.00, 'pending', '2025-07-07 23:21:39', NULL),
(44, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751919783_bg.png', 150.00, 'pending', '2025-07-07 23:23:06', NULL),
(45, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751919911_bg.png', 150.00, 'pending', '2025-07-07 23:25:14', NULL),
(46, 1, 12, 47, 'instapay', '01003504114', 'mostafamta347@gmail.com', NULL, '1751920029_bg.png', 150.00, 'pending', '2025-07-07 23:27:12', NULL),
(47, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1751920111_bg.png', 150.00, 'completed', '2025-07-07 23:28:34', '2025-07-10 00:04:12'),
(48, 1, 12, 47, 'instapay', '01003504114', 'mostafamta347@gmail.com', NULL, '1751920239_bg.png', 150.00, 'pending', '2025-07-07 23:30:42', NULL),
(49, 1, 12, 47, 'instapay', '01003504114', 'mostafamta347@gmail.com', NULL, '1751920239_bg.png', 150.00, 'pending', '2025-07-07 23:31:42', NULL),
(50, 1, 12, 47, 'vodafone_cash', '01003350412', 'mostafamta347@gmail.com', NULL, '1751920401_bg.png', 150.00, 'rejected', '2025-07-07 23:33:23', '2025-07-10 00:04:02'),
(51, 1, 12, 47, 'vodafone_cash', '01056421221', 'mostafamta347@gmail.com', NULL, '1751920680_bg.png', 150.00, 'pending', '2025-07-07 23:38:03', NULL),
(52, 1, 12, 47, 'instapay', '01003504114', 'mostafamta347@gmail.com', NULL, '1751920992_download.png', 150.00, 'pending', '2025-07-07 23:43:16', NULL),
(53, 1, 12, 47, 'instapay', '01003504114', 'mostafamta347@gmail.com', NULL, '1751921187_download.png', 150.00, 'completed', '2025-07-07 23:46:29', '2025-07-09 18:39:28'),
(54, 1, 12, 47, 'vodafone_cash', '01003504114', 'admin@gmail.com', NULL, '1752370982_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-13 04:43:06', NULL),
(55, 3, 12, 47, 'instapay', '01003504114', 'hk303792mmmm@gmail.com', NULL, '1752459523_2.png', 150.00, 'completed', '2025-07-14 05:18:48', '2025-07-14 17:26:19'),
(56, 3, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1752460582_pexels-googledeepmind-25626431.jpg', 150.00, 'pending', '2025-07-14 05:36:27', NULL),
(57, 3, 12, 47, 'instapay', '01003504114', 'hk303792mmmm@gmail.com', NULL, '1752460729_cane-test.a0001.net_shop-checkout.php.png', 150.00, 'pending', '2025-07-14 05:38:57', NULL),
(58, 3, 12, 47, 'instapay', '01003504114', 'hk303792mmmm@gmail.com', NULL, '1752462012_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-14 06:00:19', NULL),
(59, 3, 12, 47, 'instapay', '01003350412', 'hk303792mmmm@gmail.com', NULL, '1752462047_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-14 06:00:54', NULL),
(60, 3, 12, 47, 'instapay', '01003504114', 'hk303792mmmm@gmail.com', NULL, '1752462108_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-14 06:01:51', NULL),
(61, 2, 12, 47, 'vodafone_cash', '01003504114', 'mostafamtaha66@gmail.com', NULL, '1752486081_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-14 12:41:28', NULL),
(62, 2, 12, 47, 'instapay', '01003504114', 'mostafamtaha66@gmail.com', NULL, '1752486456_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'verified', '2025-07-14 12:47:45', '2025-07-14 16:52:33'),
(63, 2, 12, 46, 'instapay', '01003504114', 'mostafamtaha66@gmail.com', NULL, '1752518794_localhost_A.S_Unlock_admin_dashboard.php.png', 150.00, 'verified', '2025-07-14 21:53:33', '2025-07-15 19:04:48'),
(64, 3, 12, 45, 'instapay', '01003504114', 'hk303792mmmm@gmail.com', NULL, '1752573885_localhost_A.S_Unlock_admin_dashboard.php.png', 150.00, 'completed', '2025-07-15 13:05:02', '2025-07-15 13:06:21'),
(65, 5, 12, 47, 'instapay', '01003504114', 'admin@gmail.com', NULL, '1752596297_localhost_A.S_Unlock_admin_dashboard.php.png', 150.00, 'pending', '2025-07-15 19:20:57', NULL),
(66, 5, 12, 47, 'instapay', '01003504114', 'admin@gmail.com', NULL, '1752635938_localhost_A.S_Unlock_public_products.php.png', 150.00, 'pending', '2025-07-16 06:21:27', NULL),
(67, 5, 29, 58, 'instapay', '01003504114', 'admin@gmail.com', NULL, '1752721473_vodafone-cash.png', 70.00, 'completed', '2025-07-17 06:04:39', '2025-07-20 01:04:22'),
(68, 5, 22, 53, 'instapay', '01121561532', 'admin@gmail.com', NULL, '1752723075_Team 4841324322.jpeg', 120.00, 'pending', '2025-07-17 06:31:18', NULL),
(69, 5, 21, 56, 'instapay', '01056421221', 'admin@gmail.com', NULL, '1752723409_vodafone-cash.png', 70.00, 'rejected', '2025-07-17 06:36:52', '2025-07-20 03:05:46'),
(70, 5, 21, 56, 'vodafone_cash', '01003350412', 'admin@gmail.com', NULL, '1752723512_vodafone-cash.png', 70.00, 'completed', '2025-07-17 06:38:35', '2025-07-20 01:11:09'),
(71, 1, 29, 58, 'instapay', '01003504114', 'mostafamta347@gmail.com', NULL, '1752961518_ultraviewer.png', 70.00, 'completed', '2025-07-20 00:45:21', '2025-07-20 01:00:22'),
(72, 1, 29, 58, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1752961708_ultraviewer.png', 70.00, 'completed', '2025-07-20 00:48:31', '2025-07-20 01:11:37'),
(73, 1, 29, 58, 'instapay', '01003504114', 'mostafamta347@gmail.com', NULL, '1752963202_vodafone-cash.png', 70.00, 'completed', '2025-07-20 01:13:26', '2025-07-20 01:13:38'),
(74, 1, 21, 56, 'instapay', '01003504114', 'mostafamta347@gmail.com', NULL, '1752978390_ultraviewer.png', 70.00, 'pending', '2025-07-20 05:28:59', NULL),
(75, 1, 21, 56, 'instapay', '01003504114', 'mostafamta347@gmail.com', NULL, '1752978829_ultraviewer.png', 70.00, 'pending', '2025-07-20 05:33:57', NULL),
(76, 1, 22, 53, 'instapay', '01003504114', 'mostafamta347@gmail.com', NULL, '1752979569_ultraviewer.png', 120.00, 'pending', '2025-07-20 05:46:23', NULL),
(77, 1, 22, 53, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', NULL, '1752980194_ultraviewer.png', 120.00, 'pending', '2025-07-20 05:56:37', NULL),
(78, 3, 21, 55, 'vodafone_cash', '01121561532', 'hk303792mmmm@gmail.com', NULL, '1753018589_ultraviewer.png', 70.00, 'pending', '2025-07-20 16:36:37', NULL),
(79, 3, 21, 56, 'instapay', '01121561532', 'hk303792mmmm@gmail.com', NULL, '1753018716_ultraviewer.png', 70.00, 'pending', '2025-07-20 16:38:40', NULL),
(80, 3, 21, 56, 'instapay', '01121561532', 'hk303792mmmm@gmail.com', NULL, '1753018825_vodafone-cash.png', 70.00, 'pending', '2025-07-20 16:40:32', NULL),
(81, 3, 29, 58, 'instapay', '01121561532', 'hk303792mmmm@gmail.com', 'mostafamtaha66@gmail.com', '1753050330_localhost_A.S_Unlock_public_products.php.png', 70.00, 'completed', '2025-07-21 01:25:37', '2025-07-21 04:12:39'),
(82, 3, 32, 61, 'instapay', '01121561532', 'hk303792mmmm@gmail.com', 'mostafamta347@gmail.com', '1753060823_cane-test.a0001.net_order_order_details.php_order_id=17&i=1.png', 100.00, 'completed', '2025-04-08 04:20:27', '2025-07-22 16:53:15');

-- --------------------------------------------------------

--
-- Table structure for table `persons`
--

CREATE TABLE `persons` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `job` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `persons`
--

INSERT INTO `persons` (`id`, `type`, `name`, `job`, `description`, `image_path`, `created_at`) VALUES
(15, 'ادارة', 'Mostafamtaha', 'Dev', 'Mostafamtaha', '687eb550a57a4.jpg', '2025-07-21 21:46:56'),
(16, 'العمل المساعد', 'pexels', 'pexels', 'pexels', '687edca76985d.jpg', '2025-07-22 00:34:47');

-- --------------------------------------------------------

--
-- Table structure for table `person_skills`
--

CREATE TABLE `person_skills` (
  `id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `skill` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `person_skills`
--

INSERT INTO `person_skills` (`id`, `person_id`, `skill`) VALUES
(33, 15, 'Mostafamtaha'),
(34, 15, 'Mostafamtaha'),
(35, 15, 'Mostafamtaha'),
(36, 15, 'Mostafamtaha'),
(37, 16, 'pexels'),
(38, 16, 'pexels'),
(39, 16, 'pexels'),
(40, 16, 'pexels');

-- --------------------------------------------------------

--
-- Table structure for table `person_social_links`
--

CREATE TABLE `person_social_links` (
  `id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `icon_class` varchar(100) NOT NULL,
  `link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `person_social_links`
--

INSERT INTO `person_social_links` (`id`, `person_id`, `icon_class`, `link`) VALUES
(24, 15, 'fab fa-whatsapp', 'asfd'),
(25, 15, 'fab fa-youtube', 'asdfasdf'),
(26, 15, 'fab fa-facebook', 'asdf'),
(27, 16, 'bi bi-facebook', 'pexels');

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` int(11) NOT NULL,
  `icon` text DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` int(11) DEFAULT 0,
  `best_seller` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `icon`, `name`, `price`, `discount`, `best_seller`, `created_at`, `updated_at`, `status`) VALUES
(2, 'asdf', 'asdf', 120.00, 27, 1, '2025-07-22 15:38:08', NULL, 1),
(3, 'شسيب', 'شسيب', 32.00, 21, 0, '2025-07-22 15:40:11', NULL, 1),
(4, 'سيشب', 'شسيب', 321.00, 21, 1, '2025-07-22 15:44:08', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `plan_features`
--

CREATE TABLE `plan_features` (
  `id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `feature` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plan_features`
--

INSERT INTO `plan_features` (`id`, `plan_id`, `feature`) VALUES
(2, 2, 'dasf'),
(3, 2, 'asdf'),
(4, 2, 'adf'),
(5, 2, 'asdf'),
(6, 3, 'شيسب'),
(7, 4, '20'),
(8, 4, '02');

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
(51, 16, '1', 'الاحترافية', 120.00, '[\"\\u064a\\u0648\\u0645 \\u0644\\u0627 \\u064a\\u0646\\u0641\\u0639 \\u0645\\u0627\\u0644 \\u0648\\u0644\\u0627 \\u0628\\u0646\\u0648\\u0646\"]', '2025-07-17 03:39:11', NULL),
(52, 22, '1', 'الاحترافية', 120.00, '[\"YouTube\",\"YouTube\",\"YouTube\"]', '2025-07-17 04:30:14', NULL),
(53, 22, '2', 'الاحترافية', 120.00, '[\"YouTube\",\"YouTube\",\"YouTube\"]', '2025-07-17 04:30:23', NULL),
(54, 22, '3', 'الاحترافية', 120.00, '[\"YouTube\",\"YouTube\",\"YouTube\"]', '2025-07-17 04:30:27', NULL),
(55, 21, '1', 'خطة', 70.00, '[\"\\u062a\\u0627\\u0628\\u0644\\u062a\",\"\\u062a\\u0627\\u0628\\u0644\\u062a\",\"\\u062a\\u0627\\u0628\\u0644\\u062a\"]', '2025-07-17 04:37:16', NULL),
(56, 21, '2', 'خطة', 70.00, '[\"\\u062a\\u0627\\u0628\\u0644\\u062a\",\"\\u062a\\u0627\\u0628\\u0644\\u062a\",\"\\u062a\\u0627\\u0628\\u0644\\u062a\"]', '2025-07-17 04:37:24', NULL),
(57, 21, '3', 'خطة', 70.00, '[\"\\u062a\\u0627\\u0628\\u0644\\u062a\",\"\\u062a\\u0627\\u0628\\u0644\\u062a\",\"\\u062a\\u0627\\u0628\\u0644\\u062a\"]', '2025-07-17 04:37:27', NULL),
(58, 29, '1', 'خطة', 70.00, '[\"\\u062a\\u0627\\u0628\\u0644\\u062a\"]', '2025-07-17 06:03:58', NULL),
(60, 30, '2', 'URL Plus', 100.00, '[\"URL\"]', '2025-07-21 03:02:38', NULL),
(61, 32, '1', 'JavaScript', 100.00, '[\"URL\"]', '2025-07-21 03:07:19', NULL),
(63, 34, '3', 'الاحترافية', 100.00, '[\"URL\"]', '2025-07-21 04:19:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question`, `answer`, `is_published`, `created_at`) VALUES
(12, 'ما هي خدماتكم الرئيسية؟', 'نقدم مجموعة متنوعة من الخدمات تشمل التصميم الجرافيكي، تطوير الويب، التسويق الرقمي، واستشارات تكنولوجيا المعلومات. يمكنك الاطلاع على جميع خدماتنا في صفحة الخدمات.', 1, '2025-07-21 14:08:45'),
(13, 'كم تستغرق مدة تنفيذ المشروع؟', 'مدة التنفيذ تختلف حسب نوع المشروع وتعقيده. عادةً ما تستغرق المشاريع الصغيرة من أسبوع إلى أسبوعين، بينما المشاريع المتوسطة قد تستغرق من شهر إلى شهرين، والمشاريع الكبيرة قد تستغرق عدة أشهر.', 0, '2025-07-21 14:09:08'),
(14, 'هل تقدمون دعمًا بعد التسليم؟', 'نعم، نقدم دعمًا مجانيًا لمدة شهر بعد تسليم المشروع. بعد هذه الفترة، يمكن تمديد الدعم بموجب عقد صيانة سنوي أو شهري حسب احتياجات العميل.', 0, '2025-07-21 14:09:35');

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
(177, '', '', 0, '', NULL, '2025-07-10 00:08:04', 'completed', 'asdf', 'asdf', 12210, 'asdf'),
(178, 'Postman-win64-Setup.exe', '../uploads/file-1752442615970-445_Postman-win64-Setup.exe', 141986432, 'application/x-dosexec', NULL, '2025-07-14 00:37:23', 'completed', NULL, NULL, NULL, NULL),
(179, 'jpg', '../uploads/file-1753061066117-185_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 165054, 'image/jpeg', '', '2025-07-21 04:24:28', 'completed', NULL, NULL, NULL, NULL);

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
(1, 'admin', 'mostafamta347@gmail.com', NULL, '118380663984086018501', 'https://lh3.googleusercontent.com/a/ACg8ocKoPaBU55MVAUPWK8vAXRZL5l2zajBxEXOg3H-IujH4wFfVIA=s96-c', '2025-07-13 01:42:35', '01003504114', 1, 0),
(2, 'mostafa mtaha', 'mostafamtaha66@gmail.com', '$2y$10$.qnSI0zZVTwsBKRHxmT7KOBrrQ/JisVN1kFeqJhQrUnCvc54BJCiG', NULL, NULL, '2025-07-14 01:25:29', NULL, 1, 0),
(3, 'Mmm Mm', 'hk303792mmmm@gmail.com', NULL, '112343287434448403765', 'https://lh3.googleusercontent.com/a/ACg8ocJJA2_4La57lpo17PrrsseW814JZlZIttFLu98VwWjPzaNu-Q=s96-c', '2025-07-14 01:49:50', '01121561532', 1, 0),
(4, 'mostafa mtaha', 'mostafamtaha66@gmail.codm', '$2y$10$gMOjsQ5mXvHF2IcJRSsjReopDgsraCHTJuNE1fQr0EQWmNZnsVbGO', NULL, NULL, '2025-07-15 15:21:35', NULL, 1, 0),
(5, 'mostafamtaha1', 'admin@gmail.com', '$2y$10$Asw/3T2F7e2Xif8aO3in1ug34IXTbzLKflfNsshOkk3Z2ynexN5Hy', NULL, NULL, '2025-07-15 15:39:02', NULL, 1, 0),
(6, 'mostafamtaha1', 'jahsdfkha@adsfkajs.com', '$2y$10$7lLnh5MMjaTsEgAo4hqEXeO6gHuLwRLKPgUNU4W3Wl5p9IjtPAUwy', NULL, NULL, '2025-06-17 03:17:25', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `warranties`
--

CREATE TABLE `warranties` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` datetime NOT NULL DEFAULT current_timestamp(),
  `end_date` datetime NOT NULL,
  `duration_days` int(11) NOT NULL,
  `status` enum('active','expired','void') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for table `links`
--
ALTER TABLE `links`
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
-- Indexes for table `persons`
--
ALTER TABLE `persons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `person_skills`
--
ALTER TABLE `person_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `person_id` (`person_id`);

--
-- Indexes for table `person_social_links`
--
ALTER TABLE `person_social_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `person_id` (`person_id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plan_features`
--
ALTER TABLE `plan_features`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indexes for table `product_plans`
--
ALTER TABLE `product_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
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
-- Indexes for table `warranties`
--
ALTER TABLE `warranties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `digital_products`
--
ALTER TABLE `digital_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `attempt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `persons`
--
ALTER TABLE `persons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `person_skills`
--
ALTER TABLE `person_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `person_social_links`
--
ALTER TABLE `person_social_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `plan_features`
--
ALTER TABLE `plan_features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_plans`
--
ALTER TABLE `product_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `security_questions`
--
ALTER TABLE `security_questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `warranties`
--
ALTER TABLE `warranties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD CONSTRAINT `login_attempts_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`) ON DELETE CASCADE;

--
-- Constraints for table `person_skills`
--
ALTER TABLE `person_skills`
  ADD CONSTRAINT `person_skills_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `persons` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `person_social_links`
--
ALTER TABLE `person_social_links`
  ADD CONSTRAINT `person_social_links_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `persons` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `plan_features`
--
ALTER TABLE `plan_features`
  ADD CONSTRAINT `plan_features_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
