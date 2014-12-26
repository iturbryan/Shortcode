-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 26, 2014 at 10:06 AM
-- Server version: 5.5.36-cll-lve
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `qualitex_bulk`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contacts`
--

DROP TABLE IF EXISTS `tbl_contacts`;
CREATE TABLE IF NOT EXISTS `tbl_contacts` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `telephone` varchar(255) NOT NULL,
  `shortCode` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32768 ;

--
-- Triggers `tbl_contacts`
--
DROP TRIGGER IF EXISTS `trg_001`;
DELIMITER //
CREATE TRIGGER `trg_001` AFTER INSERT ON `tbl_contacts`
 FOR EACH ROW BEGIN

INSERT INTO `tbl_subscriptions` VALUES(NULL, NEW.`telephone`, 1, NOW());

END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_002`;
DELIMITER //
CREATE TRIGGER `trg_002` BEFORE DELETE ON `tbl_contacts`
 FOR EACH ROW BEGIN
INSERT INTO `tbl_subscriptions` VALUES(NULL, OLD.`telephone`, 0, NOW());
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_keywords`
--

DROP TABLE IF EXISTS `tbl_keywords`;
CREATE TABLE IF NOT EXISTS `tbl_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Keywords' AUTO_INCREMENT=72 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_outbox`
--

DROP TABLE IF EXISTS `tbl_outbox`;
CREATE TABLE IF NOT EXISTS `tbl_outbox` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `telephone` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `messageID` varchar(255) NOT NULL,
  `amount` double NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_settings`
--

DROP TABLE IF EXISTS `tbl_settings`;
CREATE TABLE IF NOT EXISTS `tbl_settings` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tbl_settings`
--

INSERT INTO `tbl_settings` (`id`, `key`, `value`) VALUES
(1, 'username', 'Zetumobile'),
(2, 'api_key', 'a3231de0c58e86f35df860ba808461cb13036dc716cca4971c7e836e06c12beb'),
(3, 'from', '22560');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subscriptions`
--

DROP TABLE IF EXISTS `tbl_subscriptions`;
CREATE TABLE IF NOT EXISTS `tbl_subscriptions` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `telephone` varchar(255) NOT NULL,
  `subscribe` tinyint(1) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

DROP TABLE IF EXISTS `tbl_users`;
CREATE TABLE IF NOT EXISTS `tbl_users` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `username`, `password`, `last_login`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '2014-12-04 18:22:16');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
