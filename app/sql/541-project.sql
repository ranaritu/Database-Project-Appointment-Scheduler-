-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 08, 2016 at 06:35 PM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `541-project`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_appointment` (IN `user_id` INT(11), IN `calendar_id` INT(11), IN `title` VARCHAR(255), IN `location` VARCHAR(255), IN `start_time` DATETIME, IN `stop_time` DATETIME)  NO SQL
INSERT INTO Appointment(user_id, calendar_id, title, location, start_time, stop_time) VALUES (user_id, calendar_id, title, location, start_time, stop_time)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_avail_rule` (IN `calendar_id` INT(11), IN `weekday` VARCHAR(11), IN `start_time` TIME, IN `stop_time` TIME)  MODIFIES SQL DATA
INSERT INTO avilRules (calendar_id, weekday, start_time, stop_time) VALUES (calendar_id, weekday, start_time, stop_time)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_mon_fri` (IN `calendar_id` INT(11))  NO SQL
BEGIN

DECLARE start_time TIME DEFAULT '9:00';
DECLARE stop_time TIME;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_recurrence_rule` (IN `frequency` VARCHAR(11), IN `times` INT(11), OUT `insert_id` INT)  MODIFIES SQL DATA
BEGIN
INSERT INTO recRule (frequency, recurrences) VALUES (frequency, times);

SELECT last_insert_id() INTO insert_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_user` (IN `first_name` VARCHAR(32), IN `last_name` VARCHAR(32), IN `business` VARCHAR(32), IN `userEmail` VARCHAR(60), IN `userPassword` VARCHAR(255), IN `gender` VARCHAR(32))  MODIFIES SQL DATA
INSERT INTO Users (first_name, last_name, business, userEmail, userPassword, gender) VALUES (first_name, last_name, business, userEmail, userPassword, gender)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `check_avail_times` (IN `id` INT)  READS SQL DATA
SELECT start_time, stop_time, weekday FROM avilRules WHERE calendar_id = id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `check_calendar_appointments` (IN `calendar_id` INT)  MODIFIES SQL DATA
SELECT start_time, stop_time FROM Appointment WHERE calendar_id = calendar_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `check_calendar_exclusive` (IN `calendar_id` INT)  READS SQL DATA
SELECT exclusive FROM Calendar WHERE id = calendar_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `check_time_overlap` (IN `id` INT(11))  READS SQL DATA
SELECT start_time, stop_time FROM Appointment WHERE user_id = id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_calendar` (IN `calendar_name` VARCHAR(255), IN `is_exclusive` TINYINT(1))  NO SQL
INSERT INTO Calendar(name, exclusive) VALUES (calendar_name, is_exclusive)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_appointments` (IN `id` INT)  READS SQL DATA
SELECT A.title, A.location, A.start_time, A.stop_time, C.name
FROM Appointment A, Calendar C
WHERE A.user_id = id and C.id = A.calendar_id$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Appointment`
--

CREATE TABLE `Appointment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `calendar_id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `location` varchar(30) NOT NULL,
  `start_time` datetime NOT NULL,
  `stop_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `Appointment`
--
DELIMITER $$
CREATE TRIGGER `validate_start_stop` BEFORE INSERT ON `Appointment` FOR EACH ROW BEGIN
  IF NEW.start_time >= NEW.stop_time THEN 
  SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'stop earlier than start';
END IF;
  IF NEW.start_time <= CURDATE() THEN
  SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'start time must be in the future';
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `avilRules`
--

CREATE TABLE `avilRules` (
  `id` int(11) NOT NULL,
  `calendar_id` int(11) NOT NULL,
  `weekday` varchar(10) NOT NULL,
  `start_time` time NOT NULL,
  `stop_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Calendar`
--

CREATE TABLE `Calendar` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `exclusive` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `userID` int(11) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `business` varchar(32) NOT NULL,
  `userEmail` varchar(60) NOT NULL,
  `userPass` varchar(255) NOT NULL,
  `gender` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Appointment`
--
ALTER TABLE `Appointment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user_id`),
  ADD KEY `calendar` (`calendar_id`);

--
-- Indexes for table `avilRules`
--
ALTER TABLE `avilRules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `calendar` (`calendar_id`);

--
-- Indexes for table `Calendar`
--
ALTER TABLE `Calendar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Appointment`
--
ALTER TABLE `Appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `avilRules`
--
ALTER TABLE `avilRules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `Calendar`
--
ALTER TABLE `Calendar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `Appointment`
--
ALTER TABLE `Appointment`
  ADD CONSTRAINT `calendar_constraint` FOREIGN KEY (`calendar_id`) REFERENCES `Calendar` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_appointment_constraint` FOREIGN KEY (`user_id`) REFERENCES `Users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `avilRules`
--
ALTER TABLE `avilRules`
  ADD CONSTRAINT `calendar_avail_constraint` FOREIGN KEY (`calendar_id`) REFERENCES `Calendar` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
