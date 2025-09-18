-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2025 at 12:01 PM
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
-- Table structure for table `tb_evaluations`
--

CREATE TABLE `tb_evaluations` (
  `ev_id` varchar(50) NOT NULL COMMENT 'รหัสการประเมินในแต่ละครั้ง',
  `t_id` varchar(50) NOT NULL COMMENT 'รหัสครูที่ถูกประเมิน (Foreign Key)',
  `ev_fiscal_year` int(11) DEFAULT NULL COMMENT 'ปีงบประมาณที่ทำการประเมิน',
  `ev_start_date` date DEFAULT NULL COMMENT 'วันที่เริ่มต้นรอบการประเมิน',
  `ev_end_date` date DEFAULT NULL COMMENT 'วันที่สิ้นสุดรอบการประเมิน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_evaluators`
--

CREATE TABLE `tb_evaluators` (
  `e_id` varchar(50) NOT NULL COMMENT 'รหัสประจำตัวกรรมการ',
  `e_first_name` varchar(100) NOT NULL COMMENT 'ชื่อจริง',
  `e_last_name` varchar(100) NOT NULL COMMENT 'นามสกุล',
  `e_position` varchar(100) DEFAULT NULL COMMENT 'ตำแหน่ง (เช่น ผู้อำนวยการสถานศึกษา, ศึกษานิเทศก์)',
  `e_academic_standing` varchar(100) DEFAULT NULL COMMENT 'วิทยฐานะ',
  `e_organization` varchar(255) DEFAULT NULL COMMENT 'หน่วยงานที่ทำงาน',
  `e_Username` varchar(30) NOT NULL COMMENT 'ชื่อผู้ใช้งาน',
  `e_Password` text NOT NULL COMMENT 'รหัสผ่าน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `tb_item_scores`
--

CREATE TABLE `tb_item_scores` (
  `is_id` varchar(50) NOT NULL COMMENT 'รหัสคะแนนรายหัวข้อ',
  `es_id` varchar(50) NOT NULL COMMENT 'รหัสคะแนนรายกรรมการ (Foreign Key)',
  `ri_id` varchar(50) NOT NULL COMMENT 'รหัสหัวข้อการประเมิน (Foreign Key)',
  `is_score` int(11) DEFAULT NULL COMMENT 'คะแนนที่ได้รับในหัวข้อนั้น (1-4)',
  `is_notes` text DEFAULT NULL COMMENT 'หมายเหตุของกรรมการสำหรับหัวข้อนี้'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_rubric_items`
--

CREATE TABLE `tb_rubric_items` (
  `ri_id` varchar(50) NOT NULL COMMENT 'รหัสหัวข้อการประเมิน',
  `ri_part` int(11) NOT NULL COMMENT 'ส่วนของการประเมิน (1 หรือ 2)',
  `ri_domain` varchar(255) DEFAULT NULL COMMENT 'หมวดหมู่ของงาน (เช่น ด้านการจัดการเรียนรู้)',
  `ri_item_number` varchar(10) DEFAULT NULL COMMENT 'ลำดับหัวข้อ (เช่น 1.1, 2.3)',
  `ri_item_description` text DEFAULT NULL COMMENT 'รายละเอียดลักษณะงานที่ปฏิบัติ',
  `ri_expected_level_description` text DEFAULT NULL COMMENT 'คำอธิบายระดับการปฏิบัติที่คาดหวัง'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_evaluations`
--
ALTER TABLE `tb_evaluations`
  ADD PRIMARY KEY (`ev_id`);

--
-- Indexes for table `tb_evaluators`
--
ALTER TABLE `tb_evaluators`
  ADD PRIMARY KEY (`e_id`);

--
-- Indexes for table `tb_evaluator_scores`
--
ALTER TABLE `tb_evaluator_scores`
  ADD PRIMARY KEY (`es_id`),
  ADD KEY `ev_id` (`ev_id`),
  ADD KEY `e_id` (`e_id`);

--
-- Indexes for table `tb_item_scores`
--
ALTER TABLE `tb_item_scores`
  ADD PRIMARY KEY (`is_id`),
  ADD KEY `es_id` (`es_id`),
  ADD KEY `ri_id` (`ri_id`);

--
-- Indexes for table `tb_rubric_items`
--
ALTER TABLE `tb_rubric_items`
  ADD PRIMARY KEY (`ri_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_evaluations`
--
ALTER TABLE `tb_evaluations`
  ADD CONSTRAINT `tb_evaluations_ibfk_1` FOREIGN KEY (`t_id`) REFERENCES `tb_personnel` (`pers_id`);

--
-- Constraints for table `tb_evaluator_scores`
--
ALTER TABLE `tb_evaluator_scores`
  ADD CONSTRAINT `tb_evaluator_scores_ibfk_1` FOREIGN KEY (`ev_id`) REFERENCES `tb_evaluations` (`ev_id`),
  ADD CONSTRAINT `tb_evaluator_scores_ibfk_2` FOREIGN KEY (`e_id`) REFERENCES `tb_evaluators` (`e_id`);

--
-- Constraints for table `tb_item_scores`
--
ALTER TABLE `tb_item_scores`
  ADD CONSTRAINT `tb_item_scores_ibfk_1` FOREIGN KEY (`es_id`) REFERENCES `tb_evaluator_scores` (`es_id`),
  ADD CONSTRAINT `tb_item_scores_ibfk_2` FOREIGN KEY (`ri_id`) REFERENCES `tb_rubric_items` (`ri_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
