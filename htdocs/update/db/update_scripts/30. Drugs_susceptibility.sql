-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 02, 2014 at 08:08 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `blis_301`
--

-- --------------------------------------------------------

--
-- Table structure for table `drugs`
--

CREATE TABLE IF NOT EXISTS `drugs` (
  `drug_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL DEFAULT '',
  `description` varchar(100) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `disabled` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`drug_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `drugs`
--

INSERT INTO `drugs` (`drug_id`, `name`, `description`, `ts`, `disabled`) VALUES
(1, 'AMPICILLIN', '', '2014-04-28 10:16:19', 0),
(2, 'PENICILLIN', '', '2014-04-28 11:04:14', 0),
(3, 'OXACILLIN (CEFOXITIN)', '', '2014-04-28 11:04:42', 0),
(4, 'CLINDAMYCIN', '', '2014-04-28 11:05:00', 0),
(5, 'TRIMETHOPRIM/SULFA', '', '2014-04-28 11:05:24', 0),
(6, 'TETRACYCLINE', '', '2014-04-28 11:05:49', 0),
(7, 'CIPROFLOXACIN', '', '2014-04-28 11:06:07', 0),
(8, 'NITROFURANTOIN', '', '2014-04-28 11:06:26', 0),
(9, 'CHLORAMPHENICOL', '', '2014-04-28 11:06:46', 0),
(10, 'CEFAZOLIN', '', '2014-04-28 11:07:09', 0),
(11, 'GENTAMICIN', '', '2014-04-28 11:07:23', 0),
(12, 'AMOXICILLIN - CLAV', '', '2014-04-28 11:07:44', 0),
(13, 'CEPHALOTHIN', '', '2014-04-28 11:07:58', 0),
(14, 'CEFUROXIME', '', '2014-04-28 11:08:13', 0),
(15, 'CEFOTAXIME', '', '2014-04-28 11:08:25', 0),
(16, 'PIPERACILLIN', '', '2014-04-28 11:08:56', 0);

-- --------------------------------------------------------

--
-- Table structure for table `drug_susceptibility`
--

CREATE TABLE IF NOT EXISTS `drug_susceptibility` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `testId` int(11) NOT NULL,
  `drugId` int(11) NOT NULL,
  `zone` int(11) NOT NULL,
  `interpretation` varchar(1) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `drug_test`
--

CREATE TABLE IF NOT EXISTS `drug_test` (
  `test_type_id` int(11) unsigned NOT NULL DEFAULT '0',
  `drug_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `test_type_id` (`test_type_id`),
  KEY `drug_id` (`drug_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `drug_test`
--

INSERT INTO `drug_test` (`test_type_id`, `drug_id`, `ts`) VALUES
(276, 12, '2014-04-29 06:48:17'),
(276, 1, '2014-04-29 06:48:17'),
(276, 14, '2014-04-29 06:48:17'),
(276, 13, '2014-04-29 06:48:18'),
(276, 4, '2014-04-29 06:48:18'),
(276, 11, '2014-04-29 06:48:18'),
(276, 2, '2014-04-29 06:48:18'),
(276, 16, '2014-04-29 06:48:18'),
(263, 12, '2014-04-29 09:32:21'),
(263, 1, '2014-04-29 09:32:21'),
(263, 10, '2014-04-29 09:32:21'),
(263, 15, '2014-04-29 09:32:22'),
(263, 14, '2014-04-29 09:32:22'),
(263, 13, '2014-04-29 09:32:22'),
(263, 9, '2014-04-29 09:32:22'),
(263, 7, '2014-04-29 09:32:22');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
