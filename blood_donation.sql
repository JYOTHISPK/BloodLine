-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Oct 04, 2025 at 03:27 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blood_donation`
--

-- --------------------------------------------------------

--
-- Table structure for table `accepts`
--

CREATE TABLE `accepts` (
  `accept_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `donor_admission_no` varchar(50) NOT NULL,
  `status` enum('accepted','declined') DEFAULT 'accepted',
  `accept_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donated`
--

CREATE TABLE `donated` (
  `donation_id` int(11) NOT NULL,
  `donor_admission_no` varchar(20) DEFAULT NULL,
  `requester_admission_no` varchar(20) DEFAULT NULL,
  `donation_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE `records` (
  `record_id` int(11) NOT NULL,
  `donor_admission_no` varchar(20) NOT NULL,
  `requester_admission_no` varchar(20) NOT NULL,
  `donated_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `request_id` int(11) NOT NULL,
  `requester_admission_no` varchar(20) DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `location` varchar(20) DEFAULT NULL,
  `reason` varchar(20) DEFAULT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `admission_no` varchar(20) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `dept` varchar(30) DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `contact1` varchar(15) DEFAULT NULL,
  `contact2` varchar(15) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `password` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`admission_no`, `name`, `age`, `semester`, `dept`, `blood_group`, `contact1`, `contact2`, `location`, `password`) VALUES
('A001', 'Rahul', 20, 2, 'CSE', 'A+', '9876543210', '9123456789', 'Ernakulam', '12345'),
('A002', 'Sneha', 21, 3, 'ECE', 'B+', '9898989898', '9000000000', 'Thrissur', '23456'),
('A003', 'Arjun', 19, 1, 'ME', 'O-', '9888888888', '9111111111', 'Kottayam', '34567'),
('A004', 'Meera', 22, 4, 'EEE', 'AB+', '9777777777', '9666666666', 'Kollam', '45678'),
('A005', 'Vishnu', 20, 2, 'CIVIL', 'B-', '9555555555', '9333333333', 'Alappuzha', '56789'),
('A006', 'Anjali', 21, 3, 'IT', 'O+', '9444444444', '9881234567', 'Kozhikode', '67890'),
('A007', 'Manoj', 23, 4, 'CSE', 'A-', '9222222222', '9001111111', 'Kannur', '78901'),
('A008', 'Nithya', 18, 1, 'ECE', 'B+', '9332222222', '9112222222', 'Palakkad', '89012'),
('A009', 'Hari', 19, 1, 'ME', 'O+', '9552222222', '9771111111', 'Malappuram', '90123'),
('A010', 'Devika', 22, 4, 'CSE', 'AB-', '9778888888', '9334444444', 'Trivandrum', '01234');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accepts`
--
ALTER TABLE `accepts`
  ADD PRIMARY KEY (`accept_id`),
  ADD UNIQUE KEY `uq_request_donor` (`request_id`,`donor_admission_no`);

--
-- Indexes for table `donated`
--
ALTER TABLE `donated`
  ADD PRIMARY KEY (`donation_id`),
  ADD UNIQUE KEY `donor_admission_no` (`donor_admission_no`,`requester_admission_no`),
  ADD KEY `requester_admission_no` (`requester_admission_no`);

--
-- Indexes for table `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`record_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `requester_admission_no` (`requester_admission_no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`admission_no`),
  ADD UNIQUE KEY `password` (`password`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accepts`
--
ALTER TABLE `accepts`
  MODIFY `accept_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=960;

--
-- AUTO_INCREMENT for table `donated`
--
ALTER TABLE `donated`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accepts`
--
ALTER TABLE `accepts`
  ADD CONSTRAINT `fk_accepts_requests` FOREIGN KEY (`request_id`) REFERENCES `requests` (`request_id`) ON DELETE CASCADE;

--
-- Constraints for table `donated`
--
ALTER TABLE `donated`
  ADD CONSTRAINT `donated_ibfk_1` FOREIGN KEY (`donor_admission_no`) REFERENCES `users` (`admission_no`),
  ADD CONSTRAINT `donated_ibfk_2` FOREIGN KEY (`requester_admission_no`) REFERENCES `users` (`admission_no`);

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`requester_admission_no`) REFERENCES `users` (`admission_no`) ON DELETE CASCADE;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `remove_old_donors` ON SCHEDULE EVERY 1 SECOND STARTS '2025-09-23 22:32:16' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM donated
  WHERE donation_date < NOW() - INTERVAL 2 MINUTE$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
