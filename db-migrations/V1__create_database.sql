-- phpMyAdmin SQL Dump
-- version 4.1.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 23 Mar 2015 la 03:03
-- Server version: 5.6.15
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fingerprints`
--
CREATE DATABASE IF NOT EXISTS `fingerprints` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `fingerprints`;

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `fp_fingerprints`
--

CREATE TABLE IF NOT EXISTS `fp_fingerprints` (
  `id` char(36) NOT NULL,
  `file_name` varchar(1024) NOT NULL,
  `session_id` char(36) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `fp_hardware_sessions`
--

CREATE TABLE IF NOT EXISTS `fp_hardware_sessions` (
  `id` char(36) NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `fp_persons`
--

CREATE TABLE IF NOT EXISTS `fp_persons` (
  `id` char(36) NOT NULL,
  `first_name` varchar(200) NOT NULL,
  `last_name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `photo_id` char(36) DEFAULT NULL,
  `fingerprints_id` char(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `first_name` (`first_name`,`last_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `fp_photos`
--

CREATE TABLE IF NOT EXISTS `fp_photos` (
  `id` char(36) NOT NULL,
  `file_name` varchar(1024) NOT NULL,
  `session_id` char(36) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
