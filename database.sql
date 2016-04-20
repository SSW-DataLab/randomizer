-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 30, 2014 at 06:47 AM
-- Server version: 5.5.38
-- PHP Version: 5.4.4-14+deb7u14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `umproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal Group ID',
  `group_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Group Name',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Group definitions for Random Database project.' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`group_id`, `group_name`) VALUES
(1, 'Treatment Group'),
(2, 'Control Group');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal Role ID',
  `role_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Role Name',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='User role definitions for Random Database project.' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'Superuser'),
(2, 'Experiment Administrator'),
(3, 'Site User');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Setting Name',
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Setting Value',
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`key`, `value`) VALUES
('project_email', 'project@localhost'),
('external_id_label','Youth ID'),
('org_title','University of Michigan: Child & Adolescent Data Lab'),
('page_title','University of Michigan - Pay for Success Random Assignment'),
('project_label','Pay For Success Random Assignment'),
('random_seed', '1'),
('email_from_address','umproject@localhost'),
('email_from_name','UM Child Data Lab'),
('group_label','special services');

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE IF NOT EXISTS `sites` (
  `site_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal Site ID',
  `site_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Site Name',
  `site_ratio` decimal(4,4) NOT NULL DEFAULT '0.5000' COMMENT 'Ratio of experiment:control at site',
  `disabled` tinyint(1) NOT NULL COMMENT 'Site unavailable - 0 = false, 1 = true',
  PRIMARY KEY (`site_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Site definitions for Random Database project.' AUTO_INCREMENT=18 ;

--
-- Dumping data for table `sites`
--

INSERT INTO `sites` (`site_id`, `site_name`, `site_ratio`, `disabled`) VALUES
(1, 'Site 1', 0.5000, 0),
(2, 'Site 2', 0.5000, 0),
(3, 'Site 3', 0.5000, 0),
(4, 'Site 4', 0.5000, 0),
(5, 'Site 5', 0.5000, 0),
(6, 'Site 6', 0.5000, 0),
(7, 'Site 7', 0.5000, 0),
(8, 'Site 8', 0.5000, 0),
(9, 'Site 9', 0.5000, 0),
(10, 'Site 10', 0.2000, 0),
(11, 'Site 11', 0.5000, 0),
(12, 'Site 12', 0.5000, 0),
(13, 'Site 13', 0.5000, 0),
(14, 'Site 14', 0.5000, 0),
(15, 'Site 15', 0.5000, 0),
(16, 'Site 16', 0.5000, 0),
(17, 'Site 17', 0.5000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE IF NOT EXISTS `subjects` (
  `subject_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal Subject ID',
  `external_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'External Subject ID',
  `creator_id` int(11) NOT NULL COMMENT 'User who entered this record',
  `group_id` int(11) NOT NULL COMMENT 'Group to which External ID assigned',
  `site_id` int(11) NOT NULL COMMENT 'Site to which External ID belongs',
  `site_ratio` decimal(4,4) NOT NULL COMMENT 'Site ratio in effect at record creation',
  `created` datetime NOT NULL COMMENT 'Time record was created.',
  `record_invalid` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Subject Record Invalidated? (1 = invalid)',
  `invalidated_by` int(11) DEFAULT NULL COMMENT 'ID of user that invalidated this record.',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date Validity Last Modified',
  PRIMARY KEY (`subject_id`),
  UNIQUE KEY `external_id` (`external_id`),
  KEY `creator_id` (`creator_id`),
  KEY `site_id` (`site_id`),
  KEY `group_id` (`group_id`),
  KEY `invalidated_by` (`invalidated_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Subjects in Random Assignment Experiment' AUTO_INCREMENT=1 ;
-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal User ID',
  `fullname` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Full Name (e.g., First I. Last)',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Email Address',
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Username',
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Password (SHA256)',
  `created` datetime NOT NULL COMMENT 'Date User Created',
  `disabled` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Account Status (1 = disabled)',
  `role_id` int(11) NOT NULL COMMENT 'User Role',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date User Last Modified',
  `modified_by` int(11) DEFAULT NULL COMMENT 'Last Modified By User',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  KEY `role_id` (`role_id`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Users of Random Assignment Experiment Database' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `fullname`, `email`, `username`, `password`, `created`, `disabled`, `role_id`, `modified`, `modified_by`) VALUES
(1, 'Super User', 'super@xyz.com', 'superuser', '8f8adb2160ae1e3aa5c9d8878cb2332624e6b7968b4908344d82af7dafe11e36', '2014-08-20 05:41:06', 0, 1, '2014-08-30 02:49:33', 1),
(2, 'Test User A', 'testuser@localhost', 'testuser', '5c118f3502d9453f640d2c62c2a23199e4ebd9d24d1f32ddccab36eef1218483', '2014-08-21 17:55:05', 0, 3, '2014-08-30 03:10:09', 3),
(3, 'Test User B', 'testuser2@localhost', 'testuser2', '5c118f3502d9453f640d2c62c2a23199e4ebd9d24d1f32ddccab36eef1218483', '2014-08-30 02:24:43', 0, 2, '2014-08-30 06:40:20', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `subjects_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`),
  ADD CONSTRAINT `subjects_ibfk_3` FOREIGN KEY (`site_id`) REFERENCES `sites` (`site_id`),
  ADD CONSTRAINT `subjects_ibfk_4` FOREIGN KEY (`invalidated_by`) REFERENCES `users` (`user_id`);


--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `users` (`user_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
