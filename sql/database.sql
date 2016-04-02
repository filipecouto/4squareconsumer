-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.8-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for desafiotp
DROP DATABASE IF EXISTS `desafiotp`;
CREATE DATABASE IF NOT EXISTS `desafiotp` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `desafiotp`;


-- Dumping structure for table desafiotp.sessions
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `token` varchar(128) DEFAULT NULL,
  `validity` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `FK_USER_ID` (`user_id`),
  CONSTRAINT `FK_USER_ID` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table desafiotp.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(150) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL COMMENT 'SHA 512 Encrypted Password',
  `salt` varchar(88) DEFAULT NULL COMMENT 'Salt hash used for Encryption',
  `user_type` tinyint(4) DEFAULT '0' COMMENT '0 - common, 1 - admin',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This represents a login.\r\n\r\nIt has a login name, a hashed password and  the salt associated to hash with that password. The salt is created on the fly.';

-- Data exporting was unselected.


-- Dumping structure for table desafiotp.venues
DROP TABLE IF EXISTS `venues`;
CREATE TABLE IF NOT EXISTS `venues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `external_id` varchar(50) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `city` varchar(150) DEFAULT NULL,
  `country` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `external_id_unique` (`external_id`),
  KEY `external_id_key` (`external_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table desafiotp.venues_descriptions
DROP TABLE IF EXISTS `venues_descriptions`;
CREATE TABLE IF NOT EXISTS `venues_descriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `external_id` varchar(50) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `FK_EXTERNAL_ID` (`external_id`),
  CONSTRAINT `FK_EXTERNAL_ID` FOREIGN KEY (`external_id`) REFERENCES `venues` (`external_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table desafiotp.venue_description_comments
DROP TABLE IF EXISTS `venue_description_comments`;
CREATE TABLE IF NOT EXISTS `venue_description_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `venue_description_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text,
  `visible` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
