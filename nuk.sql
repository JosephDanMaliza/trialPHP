-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 15, 2025 at 11:52 AM
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
-- Database: `nuk`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `attachmentID` int(11) NOT NULL,
  `postID` int(11) NOT NULL,
  `attachmentName` varchar(100) NOT NULL,
  `attachmentPath` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attachments`
--

INSERT INTO `attachments` (`attachmentID`, `postID`, `attachmentName`, `attachmentPath`) VALUES
(1, 2, 'mp4', 'assets/imgs/1.jpg'),
(2, 3, '1.jpg', 'assets/imgs/1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `commentID` int(11) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `postID` int(11) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`commentID`, `content`, `postID`, `userID`) VALUES
(1, 'wow', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `followID` int(11) NOT NULL,
  `followedID` int(11) NOT NULL,
  `followerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`followID`, `followedID`, `followerID`) VALUES
(1, 3, 2),
(2, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notificationID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `followID` int(11) NOT NULL,
  `ratingID` int(11) NOT NULL,
  `commentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notificationID`, `userID`, `followID`, `ratingID`, `commentID`) VALUES
(1, 3, 1, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `postID` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(10000) NOT NULL,
  `dateUploaded` datetime NOT NULL,
  `isDeleted` tinyint(1) DEFAULT NULL,
  `tagID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `ratingID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`postID`, `title`, `description`, `dateUploaded`, `isDeleted`, `tagID`, `userID`, `ratingID`) VALUES
(1, 'How to code \"Hello World\" in C++', 'This video will show how to code in C++.', '2025-01-08 14:52:36', NULL, 1, 2, NULL),
(2, 'Paano Magluto ng Adobo.', 'Ituturo ko sa inyo paano magluto ng adobo.', '2025-01-08 14:57:52', NULL, 4, 3, NULL),
(3, 'How to Install XAMPP on Your Computer', 'Step 1: Download XAMPP\nGo to the official XAMPP website:\nVisit https://www.apachefriends.org/index.html.\n\nSelect your operating system:\nOn the XAMPP homepage, you\'ll see options for Windows, Linux, and OS X. Choose the version that corresponds to your operating system.\n\nDownload the installer:\nClick the Download button for your operating system (e.g., Windows). The installer file will begin downloading.\n\nStep 2: Install XAMPP\nRun the installer:\nOnce the installer is downloaded, open it by double-clicking the .exe file (for Windows).\n\nStart the installation process:\nFollow the on-screen instructions:\n\nSelect Components: By default, all components (Apache, MySQL, PHP, etc.) are selected. You can leave them as is.\nChoose the installation directory: Choose the folder where you want to install XAMPP (default is usually C:\\xampp).\nStart installation: Click Next and then Install to begin the installation process.\nWait for the installation to complete:\nThe installation will take a few minutes. Once finished, click Finish to complete the process.\n\nStep 3: Start XAMPP\nLaunch the XAMPP Control Panel:\nAfter installation, open the XAMPP Control Panel. You can find it by searching for \"XAMPP Control Panel\" in the Start menu or in the installation directory.\n\nStart the services:\nIn the XAMPP Control Panel, you will see a list of services like Apache (web server) and MySQL (database).\n\nClick Start next to Apache to start the web server.\nClick Start next to MySQL to start the database server.\nCheck if everything is running:\nAfter starting the services, the Apache and MySQL rows will turn green, indicating that the services are running. You can now access the XAMPP dashboard.\n\nStep 4: Test the Installation\nOpen your browser:\nOpen your web browser (Chrome, Firefox, etc.).\n\nVisit the XAMPP dashboard:\nIn the address bar, type http://localhost/ and press Enter.\n\nCheck the XAMPP page:\nYou should see the XAMPP welcome page, confirming that Apache is working.\n\nStep 5: Use XAMPP\nTo test PHP: Create a file named index.php in the htdocs folder and your done!', '2025-01-15 08:53:28', NULL, 1, 6, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `ratingID` int(11) NOT NULL,
  `ratingValue` int(5) NOT NULL,
  `postID` int(11) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`ratingID`, `ratingValue`, `postID`, `userID`) VALUES
(1, 1, 0, 0),
(2, 2, 0, 0),
(3, 3, 0, 0),
(4, 4, 0, 0),
(5, 5, 0, 0),
(6, 5, 2, 2),
(7, 4, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `savedbookmarks`
--

CREATE TABLE `savedbookmarks` (
  `bookmarkID` int(11) NOT NULL,
  `postID` int(11) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `savedbookmarks`
--

INSERT INTO `savedbookmarks` (`bookmarkID`, `postID`, `userID`) VALUES
(1, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `tagID` int(11) NOT NULL,
  `tags` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tagID`, `tags`) VALUES
(1, 'Technology'),
(2, 'Education'),
(3, 'Lifestyle'),
(4, 'Cooking'),
(5, 'Health');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `firstName` varchar(30) NOT NULL,
  `lastName` varchar(30) NOT NULL,
  `userName` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `birthday` date NOT NULL,
  `profilePicture` varchar(100) DEFAULT NULL,
  `coverPhoto` varchar(100) DEFAULT NULL,
  `userType` varchar(10) NOT NULL,
  `phoneNumber` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `firstName`, `lastName`, `userName`, `email`, `password`, `birthday`, `profilePicture`, `coverPhoto`, `userType`, `phoneNumber`) VALUES
(1, 'Sophia', 'Harrison', 'sophieHarry', 'sophie.harrison@example.com', 'sophie123', '1995-08-14', NULL, NULL, 'user', '09234567890'),
(2, 'John', 'Doe', 'jdoe', 'jdoe@gmail.com', 'Test123!', '2015-01-01', NULL, NULL, 'admin', '09125376131'),
(3, 'Jane', 'Air', 'janeair', 'janeair@gmail.com', 'jane123', '2018-01-03', NULL, NULL, 'user', '09517829423'),
(4, 'Bill', 'Gates', 'billgates', 'billgates@gmail.com', 'bill123', '2017-03-02', NULL, NULL, 'user', '09135782811'),
(6, 'lala', 'doe', 'laladoe', 'laladoe@gmail.com', 'testing123!', '0000-00-00', NULL, NULL, '', '+6391234567');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`attachmentID`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`commentID`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`followID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notificationID`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`postID`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`ratingID`);

--
-- Indexes for table `savedbookmarks`
--
ALTER TABLE `savedbookmarks`
  ADD PRIMARY KEY (`bookmarkID`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tagID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `attachmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `commentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `follows`
--
ALTER TABLE `follows`
  MODIFY `followID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notificationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `postID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `ratingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `savedbookmarks`
--
ALTER TABLE `savedbookmarks`
  MODIFY `bookmarkID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tagID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
