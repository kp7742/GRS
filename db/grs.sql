-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2019 at 09:36 AM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `grs`
--

-- --------------------------------------------------------

--
-- Table structure for table `grievance`
--

CREATE TABLE `grievance` (
  `Id` int(10) NOT NULL,
  `Uid` int(10) NOT NULL,
  `EMail` varchar(50) NOT NULL,
  `GType` varchar(30) NOT NULL,
  `GState` varchar(1000) NOT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `otp`
--

CREATE TABLE `otp` (
  `Id` int(10) NOT NULL,
  `EMail` varchar(50) NOT NULL,
  `Otp` int(6) NOT NULL,
  `Verify` tinyint(1) NOT NULL DEFAULT '0',
  `CreationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `otp`
--

INSERT INTO `otp` (`Id`, `EMail`, `Otp`, `Verify`, `CreationDate`) VALUES
(1, 'patel.kuldip91@gmail.com', 225542, 1, '2019-01-18 19:52:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id` int(10) NOT NULL,
  `Type` varchar(20) NOT NULL,
  `Enroll` varchar(12) DEFAULT NULL,
  `Dept` varchar(30) DEFAULT NULL,
  `Sem` int(1) DEFAULT NULL,
  `FName` varchar(30) NOT NULL,
  `LName` varchar(40) NOT NULL,
  `EMail` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `Mobile` varchar(20) NOT NULL,
  `BirthDate` date NOT NULL,
  `Gender` text NOT NULL,
  `Admin` tinyint(1) NOT NULL DEFAULT '0',
  `CreationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `Type`, `Enroll`, `Dept`, `Sem`, `FName`, `LName`, `EMail`, `Password`, `Mobile`, `BirthDate`, `Gender`, `Admin`, `CreationDate`) VALUES
(1, 'student', '160840107036', 'computer', 6, 'Kuldip', 'Patel', 'patel.kuldip91@gmail.com', 'f865b53623b121fd34ee5426c792e5c33af8c227', '+919998897742', '1998-10-11', 'male', 0, '2019-01-18 19:52:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `grievance`
--
ALTER TABLE `grievance`
  ADD PRIMARY KEY (`Id`,`Uid`);

--
-- Indexes for table `otp`
--
ALTER TABLE `otp`
  ADD PRIMARY KEY (`Id`,`EMail`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`,`EMail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `grievance`
--
ALTER TABLE `grievance`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp`
--
ALTER TABLE `otp`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
