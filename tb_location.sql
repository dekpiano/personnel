-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 06, 2023 at 12:00 PM
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
-- Table structure for table `tb_location`
--

CREATE TABLE `tb_location` (
  `location_ID` int(5) NOT NULL COMMENT 'รหัสตาราง',
  `location_name` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ชื่อห้องหรือสถานที่',
  `location_detail` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'รายละเอียด',
  `location_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'เลขหรือรหัสห้อง',
  `location_seats` varchar(5) COLLATE utf8_unicode_ci NOT NULL COMMENT 'จำนวนที่นั่ง',
  `location_img` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'รูปห้องต่าง ๆ',
  `location_create` datetime NOT NULL COMMENT 'วันที่สร้าง',
  `location_Admin` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Admin ที่เพิ่ม'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_location`
--

INSERT INTO `tb_location` (`location_ID`, `location_name`, `location_detail`, `location_number`, `location_seats`, `location_img`, `location_create`, `location_Admin`) VALUES
(7, 'ห้องประชุม 72 พรรษา', 'ขนาดกลาง', 'อาคาร 4 ชั้น 1', '50', '1693810179_b2296b4db0957d96281f.jpg', '2023-09-04 13:49:39', 'pers_021'),
(8, 'ห้องประชุมเจ้าพระยา', 'ห้องประชุมขนาดใหญ่', 'อาคารเจ้าพระยา', '200', '1693815846_3a82b90425496de93f60.jpg', '2023-09-04 15:24:06', 'pers_021');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_location`
--
ALTER TABLE `tb_location`
  ADD PRIMARY KEY (`location_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_location`
--
ALTER TABLE `tb_location`
  MODIFY `location_ID` int(5) NOT NULL AUTO_INCREMENT COMMENT 'รหัสตาราง', AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
