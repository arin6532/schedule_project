-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 02, 2026 at 10:35 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lastproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `friend_user`
--

CREATE TABLE `friend_user` (
  `id` int(10) NOT NULL,
  `id_sender` int(10) NOT NULL,
  `id_recipient` int(10) NOT NULL,
  `friend_name` varchar(255) NOT NULL,
  `friend_email` varchar(255) NOT NULL,
  `friend_imgg` varchar(255) NOT NULL,
  `friend_type` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `sch_id` int(11) NOT NULL,
  `sch_id_sender` int(10) NOT NULL,
  `sch_checkbox` varchar(255) NOT NULL,
  `sch_name` varchar(255) NOT NULL,
  `time_sch` datetime NOT NULL,
  `sch_description` varchar(255) NOT NULL,
  `type_noti` int(1) NOT NULL,
  `time_create_schedule` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `end_time_sch` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_file`
--

CREATE TABLE `schedule_file` (
  `schedule_file_no` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `sch_file_id` int(10) NOT NULL,
  `schedule_file_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_friend`
--

CREATE TABLE `schedule_friend` (
  `id` int(11) NOT NULL,
  `schedule_friend_id` int(10) NOT NULL,
  `schedule_friend_sender` int(11) NOT NULL,
  `schedule_friend_recipient` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `urgent_schedule`
--

CREATE TABLE `urgent_schedule` (
  `urgent_no` int(10) NOT NULL,
  `urgent_id_sender` int(10) NOT NULL,
  `urgent_id_recipient` int(10) NOT NULL,
  `urgent_title` varchar(255) NOT NULL,
  `urgent_description` varchar(255) NOT NULL,
  `urgent_time_sch` datetime NOT NULL,
  `urgent_time_create` datetime NOT NULL DEFAULT current_timestamp(),
  `urgent_end_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userlogin`
--

CREATE TABLE `userlogin` (
  `user_id` int(10) NOT NULL,
  `username` varchar(30) NOT NULL,
  `pwd` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(200) NOT NULL,
  `imgg` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `friend_user`
--
ALTER TABLE `friend_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`sch_id`);

--
-- Indexes for table `schedule_file`
--
ALTER TABLE `schedule_file`
  ADD PRIMARY KEY (`schedule_file_no`);

--
-- Indexes for table `schedule_friend`
--
ALTER TABLE `schedule_friend`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `urgent_schedule`
--
ALTER TABLE `urgent_schedule`
  ADD PRIMARY KEY (`urgent_no`);

--
-- Indexes for table `userlogin`
--
ALTER TABLE `userlogin`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `friend_user`
--
ALTER TABLE `friend_user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `sch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=330;

--
-- AUTO_INCREMENT for table `schedule_file`
--
ALTER TABLE `schedule_file`
  MODIFY `schedule_file_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=348;

--
-- AUTO_INCREMENT for table `schedule_friend`
--
ALTER TABLE `schedule_friend`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT for table `urgent_schedule`
--
ALTER TABLE `urgent_schedule`
  MODIFY `urgent_no` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `userlogin`
--
ALTER TABLE `userlogin`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10022;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
