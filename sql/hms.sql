-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 20, 2024 at 04:27 PM
-- Server version: 8.0.18
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `a_id` int(16) NOT NULL AUTO_INCREMENT,
  `a_name` varchar(256) NOT NULL,
  `a_pw` varchar(256) NOT NULL,
  `a_creation_dt` datetime NOT NULL,
  `a_updated_dt` datetime NOT NULL,
  PRIMARY KEY (`a_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`a_id`, `a_name`, `a_pw`, `a_creation_dt`, `a_updated_dt`) VALUES
(1, 'admin', 'f07f6c983d715f4155ab0e9ce5cc4805', '2023-11-30 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

DROP TABLE IF EXISTS `appointment`;
CREATE TABLE IF NOT EXISTS `appointment` (
  `a_id` int(16) NOT NULL AUTO_INCREMENT,
  `p_id` int(16) NOT NULL,
  `d_id` int(16) NOT NULL,
  `a_date` date NOT NULL,
  `a_time_slot` time NOT NULL,
  `a_placedt` datetime NOT NULL,
  `a_user_status` varchar(25) NOT NULL,
  `a_doctor_status` varchar(20) DEFAULT 'Active',
  PRIMARY KEY (`a_id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_form`
--

DROP TABLE IF EXISTS `contact_form`;
CREATE TABLE IF NOT EXISTS `contact_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `submission_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `response` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `message_status` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

DROP TABLE IF EXISTS `doctor`;
CREATE TABLE IF NOT EXISTS `doctor` (
  `d_id` int(16) NOT NULL AUTO_INCREMENT,
  `d_name` varchar(256) NOT NULL,
  `d_contact` varchar(15) NOT NULL,
  `d_address` varchar(256) NOT NULL,
  `d_dob` date NOT NULL,
  `d_gender` varchar(50) NOT NULL,
  `d_type` varchar(256) NOT NULL,
  `d_fees` float NOT NULL,
  `d_pw` varchar(256) NOT NULL,
  `d_creation_dt` datetime NOT NULL,
  `d_updated_dt` datetime NOT NULL,
  PRIMARY KEY (`d_id`),
  KEY `fk_doctor_doctor_types` (`d_type`(250))
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_schedule`
--

DROP TABLE IF EXISTS `doctor_schedule`;
CREATE TABLE IF NOT EXISTS `doctor_schedule` (
  `ds_id` int(11) NOT NULL AUTO_INCREMENT,
  `d_id` int(11) NOT NULL,
  `schedule_date` date NOT NULL,
  `available_time_start` time NOT NULL,
  `available_time_end` time NOT NULL,
  `session_duration` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(25) NOT NULL,
  PRIMARY KEY (`ds_id`),
  KEY `d_id` (`d_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_schedule_slots`
--

DROP TABLE IF EXISTS `doctor_schedule_slots`;
CREATE TABLE IF NOT EXISTS `doctor_schedule_slots` (
  `dss_id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_id` int(11) NOT NULL,
  `time_slot` time NOT NULL,
  `status` varchar(25) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`dss_id`),
  KEY `schedule_id` (`schedule_id`)
) ENGINE=MyISAM AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_types`
--

DROP TABLE IF EXISTS `doctor_types`;
CREATE TABLE IF NOT EXISTS `doctor_types` (
  `dt_id` int(11) NOT NULL AUTO_INCREMENT,
  `dt` varchar(256) NOT NULL,
  PRIMARY KEY (`dt_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctor_types`
--

INSERT INTO `doctor_types` (`dt_id`, `dt`) VALUES
(27, 'Anesthesia '),
(5, 'Pediatrics'),
(7, 'General Surgery'),
(10, 'Pathology'),
(12, 'Dental Care'),
(15, 'Neurologists');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

DROP TABLE IF EXISTS `patient`;
CREATE TABLE IF NOT EXISTS `patient` (
  `p_id` int(16) NOT NULL AUTO_INCREMENT,
  `p_name` varchar(256) NOT NULL,
  `p_contact` varchar(15) NOT NULL,
  `p_address` varchar(256) NOT NULL,
  `p_gender` varchar(50) NOT NULL,
  `p_dob` date NOT NULL,
  `p_pw` varchar(256) NOT NULL,
  `p_creation_dt` datetime NOT NULL,
  `p_updated_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`p_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `treatment_history`
--

DROP TABLE IF EXISTS `treatment_history`;
CREATE TABLE IF NOT EXISTS `treatment_history` (
  `t_id` int(16) NOT NULL AUTO_INCREMENT,
  `a_id` int(16) NOT NULL,
  `treatment_details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `remarks` text,
  PRIMARY KEY (`t_id`),
  KEY `a_id` (`a_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
