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
-- Table structure for table `tb_booking`
--

CREATE TABLE `tb_booking` (
  `booking_id` int(10) NOT NULL,
  `booking_locationroom` int(10) NOT NULL,
  `booking_number` int(5) NOT NULL,
  `booking_title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `booking_dateStart` date NOT NULL,
  `booking_timeStart` time NOT NULL,
  `booking_dateEnd` datetime NOT NULL,
  `booking_timeEnd` time NOT NULL,
  `booking_typeuse` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `booking_equipment` text COLLATE utf8_unicode_ci NOT NULL,
  `booking_other` text COLLATE utf8_unicode_ci NOT NULL,
  `booking_Booker` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `booking_telephone` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `booking_status` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'สถานะการจอง',
  `booking_reason` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'เหตุผลการจอง',
  `booking_datecheck` datetime NOT NULL COMMENT 'วันที่ตรวจสอบ',
  `booking_usercheck` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ผู้ตรวจการจอง'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_booking`
--

INSERT INTO `tb_booking` (`booking_id`, `booking_locationroom`, `booking_number`, `booking_title`, `booking_dateStart`, `booking_timeStart`, `booking_dateEnd`, `booking_timeEnd`, `booking_typeuse`, `booking_equipment`, `booking_other`, `booking_Booker`, `booking_telephone`, `booking_status`, `booking_reason`, `booking_datecheck`, `booking_usercheck`) VALUES
(1, 7, 50, 'โครงการอะไรง่ะ', '2023-09-06', '20:26:00', '2023-09-06 00:00:00', '13:26:00', 'ประชุม', 'เครื่องคอมพิวเตอร์|จอโปรเจ็คเตอร์|เครื่องฉายแผ่นใส', 'อะไร', 'pers_021', '0652608656', 'ยกเลิกโดยผู้จอง', '', '0000-00-00 00:00:00', ''),
(2, 8, 90, 'อบรมหมอลำ', '2023-09-11', '20:20:00', '2023-09-12 00:00:00', '15:20:00', 'อบรม', 'เครื่องคอมพิวเตอร์|จอโปรเจ็คเตอร์|เครื่องฉายแผ่นใส|เครื่องขยายเสียง', '', 'pers_021', '0923844886', 'รอตรวจสอบ', '', '0000-00-00 00:00:00', ''),
(3, 8, 0, '', '0000-00-00', '00:00:00', '0000-00-00 00:00:00', '00:00:00', 'ประชุม', '', '', 'pers_021', '', 'รอตรวจสอบ', '', '0000-00-00 00:00:00', ''),
(4, 7, 0, '', '0000-00-00', '00:00:00', '0000-00-00 00:00:00', '00:00:00', 'ประชุม', '', '', 'pers_021', '', 'ยกเลิกโดยผู้จอง', '', '0000-00-00 00:00:00', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_booking`
--
ALTER TABLE `tb_booking`
  ADD PRIMARY KEY (`booking_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_booking`
--
ALTER TABLE `tb_booking`
  MODIFY `booking_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
