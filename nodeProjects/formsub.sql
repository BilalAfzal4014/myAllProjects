-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2019 at 09:49 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `formsub`
--

-- --------------------------------------------------------

--
-- Table structure for table `authtokens`
--

CREATE TABLE `authtokens` (
  `id` int(10) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `authtokens`
--

INSERT INTO `authtokens` (`id`, `user_id`, `token`) VALUES
(103, 4, '$2b$10$u6a8XFUETPOUD78d0z/dcOxi9Dr4CB4OjelPUVkynGGrBQCvsfBYu'),
(107, 2, '$2b$10$MA4btqPOo7FrLOoceLAMFeBPyxZkxIp2QYQ2VxJbXZVraGVYVYGHm');

-- --------------------------------------------------------

--
-- Table structure for table `chat_members`
--

CREATE TABLE `chat_members` (
  `chat_id` int(10) UNSIGNED NOT NULL,
  `member_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chat_members`
--

INSERT INTO `chat_members` (`chat_id`, `member_id`) VALUES
(3, 2),
(3, 4),
(3, 3),
(5, 2),
(5, 3),
(6, 4),
(6, 3),
(7, 2),
(7, 4),
(7, 5),
(7, 3),
(8, 4),
(8, 5),
(8, 3),
(9, 3),
(9, 21),
(9, 2);

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `chat_id` int(10) UNSIGNED NOT NULL,
  `sender_id` int(10) UNSIGNED NOT NULL,
  `message` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`chat_id`, `sender_id`, `message`) VALUES
(5, 3, 'Rose i m in here'),
(5, 2, 'ok jack i m coming'),
(3, 2, 'this is chatroom 1 4014'),
(3, 2, 'anybody hear me'),
(5, 3, 'sure sure'),
(5, 2, 'ok'),
(3, 3, 'take this'),
(3, 4, 'hello i m here'),
(3, 4, 'hello anybody there'),
(3, 2, 'go away'),
(3, 2, 'ok'),
(3, 3, 'ok'),
(3, 3, 'sure sure'),
(6, 3, 'hello dont text'),
(6, 4, 'ok'),
(6, 4, 'allow me to introduce a bewafa person'),
(6, 3, 'yeah her name is begum'),
(5, 3, 'hello'),
(5, 3, 'asd'),
(5, 3, 'hello there'),
(5, 2, 'yes what do you want'),
(5, 3, 'i love you bilal afzal'),
(5, 2, 'i love you too '),
(5, 2, 'hello'),
(5, 3, 'yes'),
(5, 3, 'hello back'),
(5, 3, 'hello can you hear me'),
(5, 2, 'yes i can hear you'),
(5, 3, 'i m going'),
(5, 3, 'rest in peace'),
(5, 2, 'you too'),
(5, 3, 'hello come here'),
(5, 3, 'i m coming'),
(5, 3, 'any more'),
(5, 3, 'hello from the other side'),
(5, 3, 'abc'),
(3, 3, 'go over everything'),
(3, 2, 'time will stop to heal you but i don enough heal'),
(3, 3, 'its fast now'),
(3, 3, 'my begum is sleepy'),
(3, 2, 'meri bhi aisi he hai'),
(3, 3, 'hello bol do na zara dil mein jo hai likha mein kisi sy kahon ga nahe'),
(3, 2, 'lakh di lanat tawady mooh ty'),
(3, 2, 'hello its me'),
(5, 2, 'hello its me'),
(5, 3, 'can i see you'),
(5, 2, 'hello'),
(5, 3, 'haan g'),
(3, 2, 'mein hoon don'),
(3, 3, 'mujh ko pehchan lo'),
(3, 2, 'mein hoon don'),
(3, 2, 'hello hunny bunny'),
(3, 3, 'hello from the other side'),
(3, 3, 'i must have called you thousand times'),
(3, 2, 'hello'),
(3, 3, 'hi i m fine'),
(3, 3, 'hey bakar'),
(3, 2, 'hello back'),
(5, 3, 'mama hatwse'),
(7, 3, 'hello beautiful'),
(7, 3, 'there ?'),
(7, 3, 'oh mine'),
(9, 21, 'asd'),
(3, 3, 'hello there'),
(3, 3, 'i m in '),
(3, 3, 'jklaasdasda'),
(5, 3, 'hello muzammil jamil khokhar'),
(5, 2, 'g btaaein'),
(5, 3, 'yar i hate my job'),
(5, 2, 'hello soban how are you ?'),
(5, 2, 'i m fine'),
(5, 3, 'are you fine ?'),
(5, 3, 'lk;lk');

-- --------------------------------------------------------

--
-- Table structure for table `chat_room`
--

CREATE TABLE `chat_room` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chat_room`
--

INSERT INTO `chat_room` (`id`, `name`) VALUES
(3, 'chatroom1'),
(5, 'chatroom2'),
(6, 'chatroom3'),
(7, 'chatroom4'),
(8, 'chatroom5'),
(9, 'chatroomCreated');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(10) UNSIGNED NOT NULL,
  `pid` int(10) UNSIGNED NOT NULL,
  `cityName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `pid`, `cityName`, `created_at`, `updated_at`) VALUES
(1, 1, 'Lahore', NULL, NULL),
(2, 1, 'Kasur', NULL, NULL),
(3, 2, 'Karachi', NULL, NULL),
(4, 2, 'Jhang', NULL, NULL),
(5, 3, 'Peshawar', NULL, NULL),
(6, 3, 'Hunza', NULL, NULL),
(7, 3, 'Swat', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `custom_tokens`
--

CREATE TABLE `custom_tokens` (
  `id` int(10) UNSIGNED NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `custom_tokens`
--

INSERT INTO `custom_tokens` (`id`, `token`, `created_at`, `updated_at`) VALUES
(1, 'abc123', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `file_entries`
--

CREATE TABLE `file_entries` (
  `id` int(10) UNSIGNED NOT NULL,
  `clientGivenFilename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `file_entries`
--

INSERT INTO `file_entries` (`id`, `clientGivenFilename`, `filename`, `mime`, `original_filename`, `created_at`, `updated_at`) VALUES
(3, 'Slider1', 'php87C4.tmp.jpg', 'image/jpeg', 'img1.jpg', '2017-05-16 09:26:05', '2017-05-16 09:26:05'),
(4, 'Slider2', 'phpBAD5.tmp.jpg', 'image/jpeg', 'img2.jpg', '2017-05-16 09:26:18', '2017-05-16 09:26:18'),
(5, 'Slider3', 'php32C.tmp.jpg', 'image/jpeg', 'img3.jpg', '2017-05-16 09:26:37', '2017-05-16 09:26:37'),
(6, 'Bilal', 'phpD79.tmp.jpg', 'image/jpeg', 'IMG_20160731_185716.jpg', '2017-05-16 09:32:07', '2017-05-16 09:32:07'),
(7, 'mp3 file', 'php9A00.tmp.mp3', 'audio/mpeg', 'godfather_2.mp3', '2017-05-16 09:32:43', '2017-05-16 09:32:43'),
(8, 'document file', 'php95AE.tmp.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'tempDoc.docx', '2017-05-16 09:33:47', '2017-05-16 09:33:47'),
(9, 'html file', 'phpDFC8.tmp.html', 'text/html', 'test.html', '2017-05-16 09:34:07', '2017-05-16 09:34:07'),
(11, 'Abubakar', 'php55A.tmp.jpg', 'image/jpeg', 'IMG20160728123149.jpg', '2017-05-20 04:08:18', '2017-05-20 04:08:18'),
(12, 'Wasket', 'phpCE83.tmp.jpg', 'image/jpeg', 'IMG_20170415_140418.jpg', '2017-05-23 08:48:37', '2017-05-23 08:48:37'),
(13, 'Fahad', 'php696A.tmp.jpg', 'image/jpeg', 'IMG_20160724_192102.jpg', '2017-05-24 02:50:38', '2017-05-24 02:50:38'),
(14, 'usman', 'php749A.tmp.jpg', 'image/jpeg', 'img_ny.jpg', '2017-06-16 12:09:30', '2017-06-16 12:09:30');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2017_04_19_123055_create_users_table', 1),
(2, '2017_05_04_091901_create_file_entries_table', 1),
(3, '2017_05_12_115521_create_custom_tokens_table', 1),
(8, '2017_05_19_115506_create_provinces_table', 2),
(9, '2017_05_19_115528_create_cities_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` int(10) UNSIGNED NOT NULL,
  `provinceName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `provinceName`, `created_at`, `updated_at`) VALUES
(1, 'Punjab', NULL, NULL),
(2, 'Sindth', NULL, NULL),
(3, 'Kpk', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sockets`
--

CREATE TABLE `sockets` (
  `id` int(11) NOT NULL,
  `auth_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `socketObj` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contactNo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `contactNo`, `token`, `created_at`, `updated_at`) VALUES
(2, 'bilalafzal4014@gmail.com', '$2b$10$gPOFT5/iWo3HVEsFmqJFeeFOkYOmPxWiRXik5U4e8PrvXXXt.0CXy', '0345-4130565', 'abc123', '2017-05-15 12:46:24', '2017-05-15 12:46:24'),
(3, 'bilalafzal004@gmail.com', '$2b$10$gPOFT5/iWo3HVEsFmqJFeeFOkYOmPxWiRXik5U4e8PrvXXXt.0CXy', '+1(111)111-1111', 'abc123', '2017-05-16 08:44:29', '2017-05-16 08:44:29'),
(4, 'a@gmail.com', '$2b$10$gPOFT5/iWo3HVEsFmqJFeeFOkYOmPxWiRXik5U4e8PrvXXXt.0CXy', '+1(111)111-3333', 'abc123', '2017-05-16 09:08:40', '2017-05-16 09:08:40'),
(5, 'abc@gmail.com', '$2y$10$8WaL1w/gJxW1ICg00Ad.puuJ8fhDTPpYXKTDK6lLwM4NX/tJwYBnK', '+1(111)111-1111', 'abc123', '2017-05-16 09:09:04', '2017-05-16 09:09:04'),
(6, 'aa@gmail.com', '$2y$10$beX1DK4mEDmqgkD.HpOd3O5zLffIxZpiONc71ly3s.Ur.N3CSS1YO', '+1(111)111-1111', 'abc123', '2017-05-16 09:09:47', '2017-05-16 09:09:47'),
(7, 'b1aaa@gmail.com', '$2y$10$DFDf8JMD.qbqQjoaHukFjO1uIU21Hfz17X8yLt9FYMQDoiV0FhwTq', '+1(111)111-1111', 'abc123', '2017-05-16 09:10:25', '2017-05-16 09:10:25'),
(8, 'b11a@gmail.com', '$2y$10$RQW/faHFdMzb7UGWCCed..aR16K0g/PdOGdwrLEyvJwjybQnAfZ.q', '+1(111)111-1111', 'abc123', '2017-05-16 09:12:42', '2017-05-16 09:12:42'),
(9, 'bbbb@gmail.com', '$2y$10$OyS8h1QDA0I.arvtnKGiDO9IpSEv4J6U2l5M7veXHhwSMnHM8/1f2', '+1(111)111-1111', 'abc123', '2017-05-16 09:13:07', '2017-05-16 09:13:07'),
(10, 'b@gmail.com', '$2y$10$l0/Lzsb6hTpT9nmkrmz20OgABM/uu2M/Re2sqFT8F5O/DrkMLlLx6', '+1(111)111-1111', 'abc123', '2017-05-16 09:13:36', '2017-05-16 09:13:36'),
(11, 'b1a@gmail.comm', '$2y$10$BPT4gT4Ld9NDXuZ1y6lY5.zclblRDrNKgVrPUxUWfW.W93ApGztJG', '+1(111)111-1111', 'abc123', '2017-05-16 09:14:04', '2017-05-16 09:14:04'),
(12, 'b1a@gmail.com', '$2y$10$fdqGXA6h4nzJxz/8SotEHOdSvEarqjCRhfBeKZ8qBMPRzmaZdtv/.', '+1(111)111-1111', 'abc123', '2017-05-16 09:14:46', '2017-05-16 09:14:46'),
(13, 'b1@gmail.com', '$2y$10$r8X3qtL47k9wMqWTPDC0VuIJekuQDNSQShzpum4VbpkFlOEvMDGgK', '+1(111)111-1111', 'abc123', '2017-05-16 09:16:02', '2017-05-16 09:16:02'),
(14, 'shahzaib@gmail.com', '$2y$10$5i6oLD9j6yzUJFzJ5AMh3.xWLEmhvVmAQTaKmkSixvRUSSRFrxEAi', '+1(111)111-1234', 'abc123', '2017-05-16 09:16:39', '2017-05-16 09:16:39'),
(15, 'hamza@gmail.com', '$2y$10$Zq1r4svlBvabhMsHTpYDH.9LnmOB/alwtNBGROUqajdRBqG0x/4wm', '+1(111)111-3334', 'abc123', '2017-05-16 09:17:13', '2017-05-16 09:17:13'),
(16, 'bilal.afzal786yahoo@gmail.com', '$2y$10$1Lj8TZ4hkXQQX8wxwOm6XO1Vng2b30T3jIw3DykPzpBrcx4wLFGiS', '+1(111)111-1234', 'abc123', '2017-05-16 09:17:51', '2017-05-16 09:17:51'),
(17, 'ranazeeshan41@gmail.com', '$2y$10$jV6Aq5gkU/GYYCV0MjZ.9.fsnlFjSWmaOFvu.aIkOl4mtP.q6mHNm', '+1(111)111-1234', 'abc123', '2017-05-16 09:18:26', '2017-05-16 09:18:26'),
(18, 'ranazeeshan41@gmail.commmm', '$2y$10$bfCf/xDBAQgwihI3TnlT1ekyD5rtnhY6aa5VUysM8KYEnk4HE39ri', '+1(111)111-1234', 'abc123', '2017-05-16 09:18:53', '2017-05-16 09:18:53'),
(19, 'bbcc@gmail.com', '$2y$10$wtpn8vjMWHgp4yQQah1YhO1mrjwG5T3L5VHkQPKmcaz.cLNef1OTK', '+1(111)111-1234', 'abc123', '2017-05-16 09:20:17', '2017-05-16 09:20:17'),
(20, 'bilal.afzal786@yahoo.com', '$2y$10$ui.xmnZhbsO7PfF.FIpIAOlYNmu6p7dQuN29JFTq8VPDzbssKK6fq', '+1(111)111-1234', 'abc123', '2017-05-16 09:21:47', '2017-05-16 09:21:47'),
(21, 'bg@gmail.com', '$2b$10$gPOFT5/iWo3HVEsFmqJFeeFOkYOmPxWiRXik5U4e8PrvXXXt.0CXy', '+1(111)111-1111', 'abc123', '2017-05-23 02:32:13', '2017-05-23 02:32:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authtokens`
--
ALTER TABLE `authtokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `chat_members`
--
ALTER TABLE `chat_members`
  ADD KEY `chat_id` (`chat_id`),
  ADD KEY `user_id` (`member_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD KEY `chat_id` (`chat_id`),
  ADD KEY `user_id` (`sender_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `chat_room`
--
ALTER TABLE `chat_room`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cities_pid_foreign` (`pid`);

--
-- Indexes for table `custom_tokens`
--
ALTER TABLE `custom_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `file_entries`
--
ALTER TABLE `file_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sockets`
--
ALTER TABLE `sockets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_id` (`auth_id`),
  ADD KEY `auth_id_2` (`auth_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authtokens`
--
ALTER TABLE `authtokens`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;
--
-- AUTO_INCREMENT for table `chat_room`
--
ALTER TABLE `chat_room`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `custom_tokens`
--
ALTER TABLE `custom_tokens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `file_entries`
--
ALTER TABLE `file_entries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `sockets`
--
ALTER TABLE `sockets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `authtokens`
--
ALTER TABLE `authtokens`
  ADD CONSTRAINT `foreignKey` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `chat_members`
--
ALTER TABLE `chat_members`
  ADD CONSTRAINT `foreignKey1` FOREIGN KEY (`chat_id`) REFERENCES `chat_room` (`id`),
  ADD CONSTRAINT `foreignKey2` FOREIGN KEY (`member_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chat_room` (`id`),
  ADD CONSTRAINT `chat_messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `provinces` (`id`);

--
-- Constraints for table `sockets`
--
ALTER TABLE `sockets`
  ADD CONSTRAINT `sockets_ibfk_1` FOREIGN KEY (`auth_id`) REFERENCES `authtokens` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
