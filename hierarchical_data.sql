-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 01, 2021 at 12:14 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hierarchical_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `hierarchical_referrer`
--

CREATE TABLE `hierarchical_referrer` (
  `ref` int(11) NOT NULL,
  `referrer_parent_id` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `referrer_user_id` char(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `hierarchical_referrer`
--

INSERT INTO `hierarchical_referrer` (`ref`, `referrer_parent_id`, `referrer_user_id`) VALUES
(1, 'vy7735', 'hk8366'),
(2, 'vy7735', 'bb7388'),
(3, 'hk8366', '8117nk'),
(4, 'hk8366', 'yu6277'),
(5, 'bb7388', '7hhbba'),
(6, '8117nk', 'bg511');

-- --------------------------------------------------------

--
-- Table structure for table `hierarchical_users`
--

CREATE TABLE `hierarchical_users` (
  `sn` int(11) NOT NULL,
  `user_id` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_name` char(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `hierarchical_users`
--

INSERT INTO `hierarchical_users` (`sn`, `user_id`, `user_name`) VALUES
(1, 'vy7735', 'Peter'),
(2, 'hk8366', 'Ada'),
(3, 'bb7388', 'Faith'),
(4, '8117nk', 'Ibrahim'),
(5, 'yu6277', 'John'),
(6, '7hhbba', 'Paul'),
(7, 'ygb33', 'Joe'),
(8, 'lopa22', 'Deo'),
(9, 'bg511', 'Kika');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hierarchical_referrer`
--
ALTER TABLE `hierarchical_referrer`
  ADD PRIMARY KEY (`ref`);

--
-- Indexes for table `hierarchical_users`
--
ALTER TABLE `hierarchical_users`
  ADD PRIMARY KEY (`sn`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hierarchical_referrer`
--
ALTER TABLE `hierarchical_referrer`
  MODIFY `ref` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hierarchical_users`
--
ALTER TABLE `hierarchical_users`
  MODIFY `sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
