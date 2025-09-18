-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2025 at 10:44 AM
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
-- Database: `skjacth_pa_evaluation`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_evaluator_scores`
--

CREATE TABLE `tb_evaluator_scores` (
  `es_id` varchar(50) NOT NULL COMMENT 'รหัสผลคะแนนที่กรรมการให้',
  `ev_id` varchar(50) NOT NULL COMMENT 'รหัสการประเมิน (Foreign Key)',
  `e_id` varchar(50) NOT NULL COMMENT 'รหัสกรรมการ (Foreign Key)',
  `es_part1_score` int(11) DEFAULT NULL COMMENT 'คะแนนรวมส่วนที่ 1',
  `es_part2_score` int(11) DEFAULT NULL COMMENT 'คะแนนรวมส่วนที่ 2',
  `es_total_score` int(11) DEFAULT NULL COMMENT 'คะแนนรวมทั้งหมด',
  `es_strong_points` text DEFAULT NULL COMMENT 'จุดเด่นที่กรรมการให้ความเห็น',
  `es_areas_for_improvement` text DEFAULT NULL COMMENT 'จุดที่ควรพัฒนา',
  `es_comments` text DEFAULT NULL COMMENT 'ข้อคิดเห็นเพิ่มเติม'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_evaluator_scores`
--
ALTER TABLE `tb_evaluator_scores`
  ADD PRIMARY KEY (`es_id`),
  ADD KEY `ev_id` (`ev_id`),
  ADD KEY `e_id` (`e_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_evaluator_scores`
--
ALTER TABLE `tb_evaluator_scores`
  ADD CONSTRAINT `tb_evaluator_scores_ibfk_1` FOREIGN KEY (`ev_id`) REFERENCES `tb_evaluations` (`ev_id`),
  ADD CONSTRAINT `tb_evaluator_scores_ibfk_2` FOREIGN KEY (`e_id`) REFERENCES `tb_evaluators` (`e_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
