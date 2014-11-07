-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 26, 2014 at 03:15 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rent_autos`
--

-- --------------------------------------------------------

--
-- Table structure for table `autos`
--

CREATE TABLE IF NOT EXISTS `autos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `auto_type` int(10) unsigned NOT NULL,
  `mark` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `model` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `build_year` int(10) unsigned NOT NULL,
  `description` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `engine_type` enum('BENZINE','DIESEL','ELECTRO','HYBRID') COLLATE utf8_unicode_ci NOT NULL COMMENT 'Тип двигател',
  `power` int(11) NOT NULL COMMENT 'Конски сили',
  `expense_100km` decimal(4,2) NOT NULL COMMENT 'Разход на 100 км/ч',
  `transmission` enum('MANUEL','AUTUMATIC','HALF_AUTUMATIC') COLLATE utf8_unicode_ci NOT NULL COMMENT 'Скоростна кутия',
  `activ` tinyint(1) NOT NULL,
  `create_user_id` int(10) unsigned NOT NULL,
  `update_user_id` int(10) unsigned NOT NULL,
  `create_datetime` datetime NOT NULL,
  `update_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `autos`
--


-- --------------------------------------------------------

--
-- Table structure for table `autos_advanced_characteristics`
--

CREATE TABLE IF NOT EXISTS `autos_advanced_characteristics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `auto_id` int(11) NOT NULL,
  `characteristic_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `characteristic_value` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `create_user_id` int(10) unsigned NOT NULL,
  `update_user_id` int(10) unsigned NOT NULL,
  `create_datetime` datetime NOT NULL,
  `update_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `autos_advanced_characteristics`
--


-- --------------------------------------------------------

--
-- Table structure for table `autos_characteristics`
--

CREATE TABLE IF NOT EXISTS `autos_characteristics` (
  `auto_id` int(10) unsigned NOT NULL,
  `defs_autos_characteristic_id` int(10) unsigned NOT NULL,
  `value` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`auto_id`,`defs_autos_characteristic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `autos_characteristics`
--


-- --------------------------------------------------------

--
-- Table structure for table `autos_images`
--

CREATE TABLE IF NOT EXISTS `autos_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `auto_id` int(10) unsigned NOT NULL,
  `title` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `thumb_path` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `is_master` tinyint(1) NOT NULL,
  `view_pos` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `autos_images`
--


-- --------------------------------------------------------

--
-- Table structure for table `autos_prices`
--

CREATE TABLE IF NOT EXISTS `autos_prices` (
  `auto_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  ` defs_price_period_id` int(11) unsigned NOT NULL,
  `defs_rent_days_period_id` int(11) unsigned NOT NULL,
  `price` decimal(8,2) NOT NULL,
  PRIMARY KEY (`auto_id`,` defs_price_period_id`,`defs_rent_days_period_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `autos_prices`
--


-- --------------------------------------------------------

--
-- Table structure for table `defs_autos_characteristics`
--

CREATE TABLE IF NOT EXISTS `defs_autos_characteristics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `extra_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Дефиници на характериситики за автомобили' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `defs_autos_characteristics`
--


-- --------------------------------------------------------

--
-- Table structure for table `defs_autos_characteristics_groups`
--

CREATE TABLE IF NOT EXISTS `defs_autos_characteristics_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `defs_autos_characteristics_groups`
--


-- --------------------------------------------------------

--
-- Table structure for table `defs_autos_types`
--

CREATE TABLE IF NOT EXISTS `defs_autos_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'тип автомобил',
  `create_user_id` int(10) unsigned NOT NULL,
  `update_user_id` int(10) unsigned NOT NULL,
  `create_datetime` datetime NOT NULL,
  `update_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Дефиниции на типове автомобили' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `defs_autos_types`
--


-- --------------------------------------------------------

--
-- Table structure for table `defs_price_periods`
--

CREATE TABLE IF NOT EXISTS `defs_price_periods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `period_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Дефиниции на ценовите периоди' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `defs_price_periods`
--


-- --------------------------------------------------------

--
-- Table structure for table `defs_rent_days_periods`
--

CREATE TABLE IF NOT EXISTS `defs_rent_days_periods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `min_days` int(10) unsigned NOT NULL,
  `max_days` int(10) unsigned NOT NULL,
  `period_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Дефиниции на периодите на наемане' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `defs_rent_days_periods`
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
