-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 06, 2023 at 05:23 AM
-- Server version: 10.11.5-MariaDB
-- PHP Version: 8.1.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `on_me`
--

-- --------------------------------------------------------

--
-- Table structure for table `business_category`
--

CREATE TABLE `business_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_category`
--

INSERT INTO `business_category` (`id`, `name`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Photography', 'active', '2023-11-30 07:25:42', NULL, NULL),
(2, 'Shopping', 'active', '2023-11-30 07:26:04', NULL, NULL),
(3, 'Karaoke', 'active', '2023-11-30 07:26:04', NULL, NULL),
(4, 'Yoga', 'active', '2023-11-30 07:26:46', NULL, NULL),
(5, 'Cooking', 'active', '2023-11-30 07:26:46', NULL, NULL),
(6, 'Tennis', 'active', '2023-11-30 07:25:42', NULL, NULL),
(7, 'Run', 'active', '2023-11-30 07:26:04', NULL, NULL),
(8, 'Swimming', 'active', '2023-11-30 07:26:04', NULL, NULL),
(9, 'Art', 'active', '2023-11-30 07:26:46', NULL, NULL),
(10, 'Traveling', 'active', '2023-11-30 07:26:46', NULL, NULL),
(11, 'Music', 'active', '2023-11-30 07:26:04', NULL, NULL),
(12, 'Drink', 'active', '2023-11-30 07:26:04', NULL, NULL),
(13, 'Video Games', 'active', '2023-11-30 07:26:46', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `business_favorite`
--

CREATE TABLE `business_favorite` (
  `id` int(11) NOT NULL,
  `bussiness_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business_favorite`
--

INSERT INTO `business_favorite` (`id`, `bussiness_id`, `user_id`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 12, 17, '0', '2023-12-04 11:43:05', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `email_otps`
--

CREATE TABLE `email_otps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` int(11) NOT NULL,
  `otp_expire_time` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gift_token`
--

CREATE TABLE `gift_token` (
  `id` int(11) NOT NULL,
  `bussiness_id` int(11) NOT NULL,
  `createdby` int(11) NOT NULL DEFAULT 0,
  `token_code` varchar(255) NOT NULL,
  `token_amount` varchar(255) NOT NULL,
  `token_validaty` date DEFAULT NULL,
  `comment` varchar(255) NOT NULL,
  `hide_token` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 = NOT Hide 1 = Hide',
  `shared_id` int(11) NOT NULL DEFAULT 0,
  `token_shared` enum('0','1') NOT NULL DEFAULT '0' COMMENT ' 1=yes , 0=No',
  `status` enum('Inactive','Active','Pending') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gift_token`
--

INSERT INTO `gift_token` (`id`, `bussiness_id`, `createdby`, `token_code`, `token_amount`, `token_validaty`, `comment`, `hide_token`, `shared_id`, `token_shared`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(7, 14, 19, 'PfPhFRlzjT', '500', '2024-01-01', 'test', '0', 17, '0', 'Active', '2023-12-06 04:29:01', '2023-12-06 04:29:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `goodwill_token`
--

CREATE TABLE `goodwill_token` (
  `id` int(11) NOT NULL,
  `bussiness_id` int(11) NOT NULL,
  `createdby` int(11) NOT NULL DEFAULT 0,
  `token_amount` int(11) NOT NULL,
  `comment` int(11) NOT NULL,
  `status` enum('Inactive','Active','Pending') NOT NULL DEFAULT 'Inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goodwill_token`
--

INSERT INTO `goodwill_token` (`id`, `bussiness_id`, `createdby`, `token_amount`, `comment`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(6, 14, 19, 500, 0, 'Inactive', '2023-12-06 05:08:25', '2023-12-06 05:08:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_02_13_125955_create_jobs_table', 1),
(6, '2023_06_12_231011_create_email_otps_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `bussiness_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '0:inactive,1:active,2:expired',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `bussiness_id`, `image`, `text`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 14, '', 'offer list', '1', '2023-12-01 08:57:41', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `token_redeem_history`
--

CREATE TABLE `token_redeem_history` (
  `id` int(11) NOT NULL,
  `token_id` int(11) NOT NULL DEFAULT 0,
  `reedem_date` date NOT NULL,
  `amount` varchar(255) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `token_redeem_history`
--

INSERT INTO `token_redeem_history` (`id`, `token_id`, `reedem_date`, `amount`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 4, '2023-12-05', '50', '0', '2023-12-04 11:20:58', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `business_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `country_code` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user','business') NOT NULL DEFAULT 'user',
  `address` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `zipcode` varchar(255) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `category` int(11) NOT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `otp_expire_time` varchar(255) DEFAULT NULL,
  `avatar` text DEFAULT NULL,
  `featured` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0:Non-featured,1:featured',
  `goodwill` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 :not goodwill , 1: goodwill',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `full_name`, `business_name`, `email`, `country_code`, `phone`, `email_verified_at`, `phone_verified_at`, `password`, `role`, `address`, `area`, `city`, `state`, `country`, `zipcode`, `latitude`, `longitude`, `category`, `otp`, `otp_expire_time`, `avatar`, `featured`, `goodwill`, `status`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'One-me', 'Admin', 'One-me Admin', NULL, 'admin@gmail.com', '', '8008008000', NULL, NULL, '$2y$10$vfwsGgnr9DBFRD.ei/pIy.OYJNbYpoH8N5jbd/1fc2QciHQcyB8Vu', 'admin', '', '', '', '', '', '', '', '', 0, '', NULL, NULL, '0', '0', 'active', NULL, '2023-11-29 04:56:55', '2023-11-29 04:56:55', NULL),
(12, 'Navneet', 'Gehlot', 'Navneet Gehlot', 'TVS', 'gsgwebsoft@gmail.com', '', '7821810600', NULL, '2023-12-05 11:08:57', '$2y$10$RXPBjz0.FlSINXffQHbtrOyr7VPEF/clgDxTrioWuT4snDUb1dhc.', 'business', 'WP8M+6G2, Vaishali Marg, Nemi Nagar Extension, Block A, Vaishali Nagar, Jaipur, Rajasthan 302021', 'Vaishali Nagar', 'Jaipur', 'Rajasthan', 'India', '302021', '26.915522', '75.733768', 8, '', '', NULL, '0', '0', 'active', NULL, '2023-11-30 22:11:26', '2023-12-05 11:08:57', NULL),
(14, 'Navneet123', 'Gehlot', 'Navneet Gehlot', 'TVS', 'gsgwebsoft12@gmail.com', '', '7821811612', NULL, NULL, '$2y$10$oUR2X3U/6waHvJkQJGxfcuxCWInjVUe44CdzxbyZf3r1TjFeFfo2m', 'business', '302006, Railway, Station Rd, Gopalbari, Jaipur, Rajasthan 302006', 'Gopalbari', 'Jaipur', 'Rajasthan', 'India', '302006', '26.916901', '75.790939', 4, '479978', '1701421809', NULL, '1', '1', 'active', NULL, '2023-11-30 22:11:26', '2023-12-01 03:35:09', NULL),
(17, 'Navneet', 'Gehlot', 'Navneet Gehlot', NULL, 'gsgwebsoft123456@gmail.com', '', '7821810612', NULL, NULL, '$2y$10$qf.r5ufH2lpea/ZWrOETee.k9JNBJUg0GXxJvWz1NXXEc450X.RlK', 'user', 'address', '', '', '', '', '', '', '', 0, NULL, NULL, NULL, '0', '0', 'active', NULL, '2023-12-01 01:44:38', '2023-12-01 04:02:05', NULL),
(19, 'Navneet', 'Gehlot', 'Navneet Gehlot', NULL, 'navneetgehlot.sst124@gmail.com', '', '7821810124', NULL, NULL, '$2y$10$li5KiAtJhEuNs1UnPNahd.HiAiJjptzZCp9mubnrszaKsKBUS9kme', 'user', 'address', '', '', '', '', '', '', '', 0, NULL, NULL, 'uploads/user/170142388210.png', '0', '0', 'active', NULL, '2023-12-01 04:13:59', '2023-12-05 09:17:21', NULL),
(21, 'Navneet', 'Gehlot', 'Navneet Gehlot', NULL, 'navneetgehlot.sst55@gmail.com', '', '7821810155', NULL, NULL, '$2y$10$Lml45Nvd6RfLr8hS4KiBKuLdM772iD2/pd6DMuwg0Z858e.0wDOpO', 'user', 'address', '', '', '', '', '', '', '', 0, NULL, NULL, NULL, '0', '0', 'active', NULL, '2023-12-05 10:11:33', '2023-12-05 10:11:33', NULL),
(22, 'Navneet', 'Gehlot', 'Navneet Gehlot', NULL, 'navneetgehlot.sst56@gmail.com', '', '7821810156', NULL, NULL, '$2y$10$/Qred7a0dYjDDASzmv/4rOXF2E0UjgQJoBPES/j82EEB8vR/YNkk2', 'user', 'address', '', '', '', '', '', '', '', 0, NULL, NULL, NULL, '0', '0', 'active', NULL, '2023-12-05 10:21:55', '2023-12-05 10:21:55', NULL),
(23, 'Navneet', 'Gehlot', 'Navneet Gehlot', NULL, 'navneetgehlot.sst59@gmail.com', '', '7821810159', NULL, NULL, '$2y$10$eaFYiCA6YaS8SAyj.qXKseIeczle50/BdGiPiUz5f/dFstNkB2WQu', 'user', 'address', '', 'city', 'state', 'country', 'zipcode', '123456', '123456', 0, NULL, NULL, NULL, '0', '0', 'active', NULL, '2023-12-06 05:00:58', '2023-12-06 05:00:58', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `business_category`
--
ALTER TABLE `business_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_favorite`
--
ALTER TABLE `business_favorite`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_otps`
--
ALTER TABLE `email_otps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `gift_token`
--
ALTER TABLE `gift_token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `goodwill_token`
--
ALTER TABLE `goodwill_token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `token_redeem_history`
--
ALTER TABLE `token_redeem_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `business_category`
--
ALTER TABLE `business_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `business_favorite`
--
ALTER TABLE `business_favorite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `email_otps`
--
ALTER TABLE `email_otps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gift_token`
--
ALTER TABLE `gift_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `goodwill_token`
--
ALTER TABLE `goodwill_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `token_redeem_history`
--
ALTER TABLE `token_redeem_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
