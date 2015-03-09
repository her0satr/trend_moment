-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2015 at 03:13 PM
-- Server version: 5.5.32
-- PHP Version: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `trend_moment_db`
--
CREATE DATABASE IF NOT EXISTS `trend_moment_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `trend_moment_db`;

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE IF NOT EXISTS `book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `code` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`id`, `title`, `code`) VALUES
(2, 'Judul 11', 'Kode 12');

-- --------------------------------------------------------

--
-- Table structure for table `trend_moment`
--

CREATE TABLE IF NOT EXISTS `trend_moment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `waktu` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `penjualan` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `trend_moment`
--

INSERT INTO `trend_moment` (`id`, `book_id`, `waktu`, `tanggal`, `penjualan`) VALUES
(2, 2, 0, '2014-01-01', 15),
(3, 2, 1, '2014-02-01', 23),
(4, 2, 2, '2014-03-01', 10),
(5, 2, 3, '2014-04-01', 7),
(6, 2, 4, '2014-05-01', 20),
(7, 2, 5, '2014-06-01', 5),
(8, 2, 6, '2014-07-01', 12),
(9, 2, 7, '2014-08-01', 3),
(10, 2, 8, '2014-09-01', 7),
(11, 2, 9, '2014-10-01', 10),
(12, 2, 10, '2014-11-01', 9),
(13, 2, 11, '2014-12-01', 23);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
