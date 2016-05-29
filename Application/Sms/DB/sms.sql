-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-05-29 03:13:19
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sms`
--

-- --------------------------------------------------------

--
-- 表的结构 `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `course_recid` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` varchar(7) NOT NULL,
  `name` varchar(20) NOT NULL,
  `teacher_id` varchar(5) NOT NULL,
  `credit` decimal(10,0) NOT NULL,
  `allowed_grade` smallint(6) NOT NULL,
  `cancel_grade` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`course_recid`),
  UNIQUE KEY `course_id` (`course_id`),
  KEY `teacher_id` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `enroll`
--

CREATE TABLE IF NOT EXISTS `enroll` (
  `enroll_id` int(11) NOT NULL AUTO_INCREMENT,
  `sutdent_id` varchar(10) NOT NULL,
  `course_id` varchar(7) NOT NULL,
  `enroll_year` smallint(6) NOT NULL,
  `grades` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`enroll_id`),
  UNIQUE KEY `unique_enroll` (`sutdent_id`,`course_id`,`enroll_year`),
  KEY `sutdent_id` (`sutdent_id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `student_recid` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `entrance_age` tinyint(4) NOT NULL,
  `entrance_year` smallint(6) NOT NULL,
  `class` varchar(20) NOT NULL,
  PRIMARY KEY (`student_recid`),
  UNIQUE KEY `student_id` (`student_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `student`
--

INSERT INTO `student` (`student_recid`, `student_id`, `name`, `gender`, `entrance_age`, `entrance_year`, `class`) VALUES
(5, '1430540160', '李狗嗨', 0, 15, 2012, 'A'),
(6, '1430540269', '小红', 1, 12, 2014, 'B');

-- --------------------------------------------------------

--
-- 表的结构 `teacher`
--

CREATE TABLE IF NOT EXISTS `teacher` (
  `teacher_recid` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` varchar(5) NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`teacher_recid`),
  UNIQUE KEY `teacher_id` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `identity` int(1) NOT NULL DEFAULT '0',
  `user` varchar(10) NOT NULL,
  `password` varchar(30) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`rec_id`),
  UNIQUE KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 限制导出的表
--

--
-- 限制表 `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`);

--
-- 限制表 `enroll`
--
ALTER TABLE `enroll`
  ADD CONSTRAINT `enroll_ibfk_1` FOREIGN KEY (`sutdent_id`) REFERENCES `student` (`student_id`),
  ADD CONSTRAINT `enroll_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
