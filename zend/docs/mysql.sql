-- phpMyAdmin SQL Dump
-- version 3.3.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 23, 2012 at 01:31 PM
-- Server version: 5.5.11
-- PHP Version: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zend`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `user_email_address` varchar(120) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('inactive','active') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_email_address` (`user_email_address`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `first_name`, `last_name`, `user_email_address`, `password`, `status`) VALUES
(1, 'vikas', 'chawla', 'info@socialnetworksoftware.com', '21232f297a57a5a743894a0e4a801fc3', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `user_email_address` varchar(120) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('inactive','active') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_email_address` (`user_email_address`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `user_email_address`, `password`, `status`) VALUES
(1, 'vikas', 'chawla', 'info@socialnetworksoftware.com', 'bebe68374a49cb41b7c9219e97250044', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `website`
--

CREATE TABLE IF NOT EXISTS `website` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `user_id` int(9) NOT NULL,
  `title` varchar(128) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `website`
--

INSERT INTO `website` (`id`, `user_id`, `title`, `url`) VALUES
(1, 1, 'socialnetworksoftware', 'http://info@socialnetworksoftware.com'),
(2, 1, 'Aynsoft', 'http://aynsoft.com'),
(3, 1, 'ejobsitesoftware', 'http://ejobsitesoftware.com');
