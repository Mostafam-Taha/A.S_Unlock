-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 21, 2025 at 09:27 PM
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
(1, 'ادارة', 'asdfasdf', 'asdfasdfasdfasdf', '<i class=\"fab fa-whatsapp\"></i><i class=\"fab fa-whatsapp\"></i><i class=\"fab fa-whatsapp\"></i>', '687e875bbebbb.jpg', '2025-07-21 18:30:51'),
(2, 'ادارة', 'asdfasdf', 'asdfasdfasdfasdf', '<i class=\"fab fa-whatsapp\"></i><i class=\"fab fa-whatsapp\"></i><i class=\"fab fa-whatsapp\"></i>', '687e87618e671.jpg', '2025-07-21 18:30:57'),
(3, 'ادارة', 'asdfasdf', 'asdfasdfasdfasdf', '<i class=\"fab fa-whatsapp\"></i><i class=\"fab fa-whatsapp\"></i><i class=\"fab fa-whatsapp\"></i>', '687e8787284e2.jpg', '2025-07-21 18:31:35'),
(4, 'ادارة', 'asdfasdf', 'asdfasdfasdfasdf', '<i class=\"fab fa-whatsapp\"></i><i class=\"fab fa-whatsapp\"></i><i class=\"fab fa-whatsapp\"></i>', '687e87c1b6245.jpg', '2025-07-21 18:32:33'),
(5, 'العمل المساعد', 'شسيبسشيبسشيب', 'شسيبشسيب', 'شسيبشسيبشسيب', '687e87e90807e.jpg', '2025-07-21 18:33:13'),
(6, 'العمل المساعد', 'شسيبسشيبسشيب', 'شسيبشسيب', 'شسيبشسيبشسيب', '687e87fe4e1d9.jpg', '2025-07-21 18:33:34'),
(7, 'العمل المساعد', 'شسيبسشيبسشيب', 'شسيبشسيب', 'شسيبشسيبشسيب', '687e88155a297.jpg', '2025-07-21 18:33:57'),
(8, 'ادارة', 'شسيبشسي', 'شسيب', 'شسيبسشيب', '687e882d91d93.jpg', '2025-07-21 18:34:21'),
(9, 'ادارة', 'شسيبشسي', 'شسيب', 'شسيبسشيب', '687e88410c49d.png', '2025-07-21 18:34:41'),
(11, 'العمل المساعد', 'asdfسشيبشسيبشسيب', 'sadfasdfasdfشسبشسيبشسيب', 'sadfdsشسيبشيبشسيب', '687e8d6c7d31c.jpg', '2025-07-21 18:56:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `persons`
--
ALTER TABLE `persons`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `persons`
--
ALTER TABLE `persons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
