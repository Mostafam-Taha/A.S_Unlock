-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 06, 2025 at 08:38 PM
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
(12, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751825352_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:09:15', NULL),
(13, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751825419_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:10:21', NULL),
(14, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751825693_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:14:57', NULL),
(15, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751825752_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:15:56', NULL),
(16, 1, 12, 47, 'instapay', '01003504114', 'mostafamta347@gmail.com', '1751825859_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:17:41', NULL),
(17, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751826025_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:20:27', NULL),
(18, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751826134_127.0.0.1_5500_22.png', 150.00, 'pending', '2025-07-06 21:22:18', NULL),
(19, 1, 12, 47, 'instapay', '01003504114', 'mostafamta347@gmail.com', '1751826237_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:24:00', NULL),
(20, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751826293_127.0.0.1_5500_22.png', 150.00, 'pending', '2025-07-06 21:24:57', NULL),
(21, 1, 12, 47, 'vodafone_cash', '01003504114', 'mostafamta347@gmail.com', '1751826551_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:29:13', NULL),
(22, 1, 12, 47, 'vodafone_cash', '01003350412', 'mostafamta347@gmail.com', '1751826880_516ce7c930e539f73ad36ed32fa4f9d7.jpg', 150.00, 'pending', '2025-07-06 21:34:42', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
