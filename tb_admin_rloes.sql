-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 06, 2023 at 11:59 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skjacth_general`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin_rloes`
--

CREATE TABLE `tb_admin_rloes` (
  `admin_rloes_id` int(11) NOT NULL COMMENT 'รหัส',
  `admin_rloes_userid` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ชื่อ',
  `admin_rloes_nanetype` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'เป็นใครในะรบบ\r\n',
  `admin_rloes_status` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'สถานะในระบบ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_admin_rloes`
--

INSERT INTO `tb_admin_rloes` (`admin_rloes_id`, `admin_rloes_userid`, `admin_rloes_nanetype`, `admin_rloes_status`) VALUES
(1, 'pers_002', 'ผู้บริหาร', 'manager'),
(2, 'pers_014', 'รองวิชาทั่วไป', 'manager'),
(3, 'pers_003', 'หัวหน้าทั่วไป', 'manager'),
(4, 'pers_021', 'เจ้าหน้าที่ทั่วไป', 'admin'),
(5, 'pers_058', 'เจ้าหน้าที่ทั่วไป', 'admin'),
(6, 'pers_059', 'เจ้าหน้าที่ทั่วไป', 'admin'),
(7, '', 'เจ้าหน้าที่ทั่วไป', 'admin'),
(8, '', 'เจ้าหน้าที่ทั่วไป', 'admin'),
(9, 'pers_072', 'เจ้าหน้าที่ทั่วไป', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin_rloes`
--
ALTER TABLE `tb_admin_rloes`
  ADD PRIMARY KEY (`admin_rloes_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin_rloes`
--
ALTER TABLE `tb_admin_rloes`
  MODIFY `admin_rloes_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัส', AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
