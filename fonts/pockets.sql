-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 17, 2017 at 05:55 AM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pockets`
--

-- --------------------------------------------------------

--
-- Table structure for table `dues`
--

CREATE TABLE `dues` (
  `emailIdPayee` varchar(32) NOT NULL,
  `emailIdPaid` varchar(32) NOT NULL,
  `amount` float NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dues`
--

INSERT INTO `dues` (`emailIdPayee`, `emailIdPaid`, `amount`, `timestamp`) VALUES
('pratul.ramkumar@gmail.com', 'user1@gmail.com', 0, '2017-11-16 17:04:48'),
('pratul.ramkumar@gmail.com', 'user2@gmail.com', 0, '2017-11-16 17:04:48'),
('user1@gmail.com', 'pratul.ramkumar@gmail.com', 0, '2017-11-16 17:04:48'),
('user1@gmail.com', 'user2@gmail.com', 0, '2017-11-16 17:04:48'),
('user2@gmail.com', 'pratul.ramkumar@gmail.com', 0, '2017-11-16 17:04:48'),
('user2@gmail.com', 'user1@gmail.com', 0, '2017-11-16 17:04:48');

-- --------------------------------------------------------

--
-- Table structure for table `groupexpense`
--

CREATE TABLE `groupexpense` (
  `expenseId` int(11) NOT NULL,
  `groupId` int(11) NOT NULL,
  `emailIdAddedBy` varchar(32) NOT NULL,
  `description` varchar(2048) NOT NULL,
  `amount` float NOT NULL,
  `emailIdPaidBy` varchar(32) NOT NULL,
  `category` varchar(124) NOT NULL,
  `date` date NOT NULL,
  `dues` varchar(8196) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groupexpense`
--

INSERT INTO `groupexpense` (`expenseId`, `groupId`, `emailIdAddedBy`, `description`, `amount`, `emailIdPaidBy`, `category`, `date`, `dues`, `timestamp`) VALUES
(46, 3, 'pratul.ramkumar@gmail.com', 'Pizza', 300, 'pratul.ramkumar@gmail.com', 'food', '2017-11-16', 'pratul.ramkumar@gmail.com:300,user1@gmail.com:300,user2@gmail.com:300', '2017-11-16 17:06:37');

-- --------------------------------------------------------

--
-- Table structure for table `grouptable`
--

CREATE TABLE `grouptable` (
  `groupId` int(11) NOT NULL,
  `groupName` varchar(512) NOT NULL,
  `emailId` varchar(512) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `grouptable`
--

INSERT INTO `grouptable` (`groupId`, `groupName`, `emailId`, `timestamp`) VALUES
(31, 'Home Group', 'pratul.ramkumar@gmail.com', '2017-11-16 17:04:48');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `emailId` varchar(512) NOT NULL,
  `image` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `paymentId` int(11) NOT NULL,
  `emailIdPaid` varchar(512) NOT NULL,
  `emailIdPayee` varchar(512) NOT NULL,
  `amount` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pendingusers`
--

CREATE TABLE `pendingusers` (
  `emailId` varchar(32) NOT NULL,
  `groupId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `personalexpense`
--

CREATE TABLE `personalexpense` (
  `expenseId` int(11) NOT NULL,
  `emailId` varchar(32) NOT NULL,
  `description` varchar(2048) NOT NULL,
  `amount` float NOT NULL,
  `groupId` int(11) NOT NULL DEFAULT '0',
  `date` varchar(124) NOT NULL,
  `category` varchar(124) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `personalexpense`
--

INSERT INTO `personalexpense` (`expenseId`, `emailId`, `description`, `amount`, `groupId`, `date`, `category`, `timestamp`) VALUES
(18, 'pratul.ramkumar@gmail.com', 'Pizza', 200, 0, '2017-11-16 14:30', 'food', '2017-11-16 17:02:51'),
(19, 'pratul.ramkumar@gmail.com', 'Pizza', 300, 31, '2017-11-16', 'food', '2017-11-16 17:06:37'),
(20, 'user1@gmail.com', 'Pizza', 300, 3, '2017-11-16', 'food', '2017-11-16 17:06:37'),
(21, 'user2@gmail.com', 'Pizza', 300, 3, '2017-11-16', 'food', '2017-11-16 17:06:37'),
(22, 'pratul.ramkumar@gmail.com', 'cab', 900, 0, '2017-11-16 14:30', 'others', '2017-11-16 19:48:31');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `emailId` varchar(512) NOT NULL,
  `firstName` varchar(32) NOT NULL,
  `lastName` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`emailId`, `firstName`, `lastName`, `password`, `active`) VALUES
('pratul.ramkumar@gmail.com', 'Pratul', 'Ramkumar', '5f4dcc3b5aa765d61d8327deb882cf99', 0),
('user1@gmail.com', 'user1', 'user1', '5f4dcc3b5aa765d61d8327deb882cf99', 0),
('user2@gmail.com', 'user2', 'user2', '5f4dcc3b5aa765d61d8327deb882cf99', 0),
('user3@gmail.com', 'user3', 'user3', '3fc0a7acf087f549ac2b266baf94b8b1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `usergroups`
--

CREATE TABLE `usergroups` (
  `emailId` varchar(32) NOT NULL,
  `groupId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usergroups`
--

INSERT INTO `usergroups` (`emailId`, `groupId`) VALUES
('pratul.ramkumar@gmail.com', 31),
('user1@gmail.com', 31),
('user2@gmail.com', 31);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dues`
--
ALTER TABLE `dues`
  ADD PRIMARY KEY (`emailIdPayee`,`emailIdPaid`),
  ADD KEY `emailIdPaid` (`emailIdPaid`);

--
-- Indexes for table `groupexpense`
--
ALTER TABLE `groupexpense`
  ADD PRIMARY KEY (`expenseId`),
  ADD KEY `emailIdAddedBy` (`emailIdAddedBy`),
  ADD KEY `emailIdPaidBy` (`emailIdPaidBy`),
  ADD KEY `groupId` (`groupId`);

--
-- Indexes for table `grouptable`
--
ALTER TABLE `grouptable`
  ADD PRIMARY KEY (`groupId`),
  ADD KEY `emailId` (`emailId`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emailId` (`emailId`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`paymentId`);

--
-- Indexes for table `pendingusers`
--
ALTER TABLE `pendingusers`
  ADD PRIMARY KEY (`emailId`,`groupId`),
  ADD KEY `groupId` (`groupId`);

--
-- Indexes for table `personalexpense`
--
ALTER TABLE `personalexpense`
  ADD PRIMARY KEY (`expenseId`),
  ADD KEY `emailId` (`emailId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`emailId`);

--
-- Indexes for table `usergroups`
--
ALTER TABLE `usergroups`
  ADD PRIMARY KEY (`emailId`,`groupId`),
  ADD KEY `groupId` (`groupId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `groupexpense`
--
ALTER TABLE `groupexpense`
  MODIFY `expenseId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `grouptable`
--
ALTER TABLE `grouptable`
  MODIFY `groupId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `paymentId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personalexpense`
--
ALTER TABLE `personalexpense`
  MODIFY `expenseId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dues`
--
ALTER TABLE `dues`
  ADD CONSTRAINT `dues_ibfk_1` FOREIGN KEY (`emailIdPaid`) REFERENCES `user` (`emailId`),
  ADD CONSTRAINT `dues_ibfk_2` FOREIGN KEY (`emailIdPayee`) REFERENCES `user` (`emailId`);

--
-- Constraints for table `groupexpense`
--
ALTER TABLE `groupexpense`
  ADD CONSTRAINT `groupexpense_ibfk_1` FOREIGN KEY (`emailIdAddedBy`) REFERENCES `user` (`emailId`),
  ADD CONSTRAINT `groupexpense_ibfk_2` FOREIGN KEY (`emailIdPaidBy`) REFERENCES `user` (`emailId`);

--
-- Constraints for table `grouptable`
--
ALTER TABLE `grouptable`
  ADD CONSTRAINT `grouptable_ibfk_1` FOREIGN KEY (`emailId`) REFERENCES `user` (`emailId`);

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`emailId`) REFERENCES `user` (`emailId`);

--
-- Constraints for table `pendingusers`
--
ALTER TABLE `pendingusers`
  ADD CONSTRAINT `pendingusers_ibfk_1` FOREIGN KEY (`emailId`) REFERENCES `user` (`emailId`),
  ADD CONSTRAINT `pendingusers_ibfk_2` FOREIGN KEY (`groupId`) REFERENCES `grouptable` (`groupId`);

--
-- Constraints for table `personalexpense`
--
ALTER TABLE `personalexpense`
  ADD CONSTRAINT `personalexpense_ibfk_1` FOREIGN KEY (`emailId`) REFERENCES `user` (`emailId`);

--
-- Constraints for table `usergroups`
--
ALTER TABLE `usergroups`
  ADD CONSTRAINT `usergroups_ibfk_1` FOREIGN KEY (`emailId`) REFERENCES `user` (`emailId`),
  ADD CONSTRAINT `usergroups_ibfk_2` FOREIGN KEY (`groupId`) REFERENCES `grouptable` (`groupId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
