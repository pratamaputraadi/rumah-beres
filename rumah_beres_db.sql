-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2025 at 10:46 AM
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
-- Database: `rumah_beres_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'Cooling & Air', '1.png', '2025-12-13 17:37:10', '2025-12-13 17:37:10'),
(2, 'Cleaning', '2.png', '2025-12-13 17:37:10', '2025-12-13 17:37:10'),
(3, 'Electronics', '3.png', '2025-12-13 17:37:10', '2025-12-13 17:37:10');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `message` text DEFAULT NULL,
  `file_url` varchar(255) DEFAULT NULL,
  `media_type` varchar(20) NOT NULL DEFAULT 'text',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_12_14_003343_create_categories_table', 1),
(5, '2025_12_14_003436_create_orders_table', 1),
(6, '2025_12_14_020846_add_ktp_to_users_table', 2),
(7, '2025_12_14_030025_add_bio_to_users_table', 3),
(8, '2025_12_14_070454_create_chat_messages_table', 4),
(9, '2025_12_14_080546_create_order_items_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `technician_id` bigint(20) UNSIGNED DEFAULT NULL,
  `appliance_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `total_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `technician_id`, `appliance_name`, `description`, `status`, `total_price`, `created_at`, `updated_at`) VALUES
(4, 11, 21, 'Sanyo', 'Rusak', 'closed', 115000.00, '2025-12-13 23:21:25', '2025-12-14 02:08:53'),
(5, 12, 16, 'AC', 'Rusak', 'closed', 115000.00, '2025-12-13 23:26:33', '2025-12-14 01:51:10');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `item_type` enum('service','part','platform') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `item_name`, `quantity`, `unit_price`, `item_type`, `created_at`, `updated_at`) VALUES
(1, 5, 'Service Fee (Diagnosis, Repair, Cleaning)', 1, 100000.00, 'service', '2025-12-14 01:23:36', '2025-12-14 01:23:36'),
(2, 5, 'Platform Fee', 1, 15000.00, 'platform', '2025-12-14 01:23:36', '2025-12-14 01:23:36'),
(3, 4, 'Service Fee (Diagnosis, Repair, Cleaning)', 1, 100000.00, 'service', '2025-12-14 02:05:58', '2025-12-14 02:05:58'),
(4, 4, 'Platform Fee', 1, 15000.00, 'platform', '2025-12-14 02:05:58', '2025-12-14 02:05:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `ktp_photo` varchar(255) DEFAULT NULL,
  `certificate_photo` varchar(255) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone`, `address`, `bio`, `avatar`, `ktp_photo`, `certificate_photo`, `specialization`, `is_verified`, `created_at`, `updated_at`) VALUES
(1, 'CTO - Adi Pratama Putra', 'admin@rumahberes.com', '$2y$12$JG7Dc5UBoX8vufEajZ2fNepjNFGcLjf/eSGbdNY1mqpFUd3xfuF3O', 'admin', NULL, NULL, 'CTO', 'avatars/zmeaEXcPkEmAQiaKG41CM9agQerfmExX8fEZX9fB.png', NULL, NULL, NULL, 1, '2025-12-13 17:37:10', '2025-12-13 22:32:59'),
(10, 'CEO - Pangundian Siagian', 'ceo@rumahberes.com', '$2y$12$D0gHWK8lgEVf1XsZ8sL1keiX8cb/3rf6PE2Czh6yCeMKFhECRukcy', 'admin', NULL, NULL, 'CEO', 'avatars/ji6u8MUwtKZBHlIkuHeLgoHnB5M1JTPQEiZ1bCbs.png', NULL, NULL, NULL, 1, '2025-12-13 21:54:07', '2025-12-13 22:32:33'),
(11, 'Lando Norris', 'lando@email.com', '$2y$12$6S3kB71aVsG5sdYZ3EXVveScU5oR/oxW9TKzZEZTKsD1SjWIlo4xG', 'customer', NULL, NULL, NULL, 'avatars/qHT1KF9pQH51XEMYIFOkzENoXde7iML9k09OIh7r.png', NULL, NULL, NULL, 1, '2025-12-13 22:05:10', '2025-12-14 01:53:20'),
(12, 'Oscar Piastri', 'piastri@email.com', '$2y$12$0MVJnKRbtyIqs8ZWVjZH7.bKELq10r83Cn2nk78Q.4GMXcDG1MUuS', 'customer', NULL, 'Indonesia', NULL, 'avatars/qEKQsvJbhArqLnbM3qsmIxN3kyQK2yxeRKB8PzIi.png', NULL, NULL, NULL, 1, '2025-12-13 22:05:51', '2025-12-14 01:52:21'),
(13, 'COO - Erizka Nia Ramadhani', 'coo@rumahberes.com', '$2y$12$MSV5gUuUMCS9V3vNORewlu5NEG5AfQDccAPjLfmngsyQmiBG4NZGq', 'admin', NULL, NULL, 'CFO', 'avatars/uHXPBcoYW3Gq233bAUXL4mn5FlvH6xNzjiAn8U9A.png', NULL, NULL, NULL, 1, '2025-12-13 22:15:05', '2025-12-13 22:31:57'),
(14, 'CFO - Ilham Syihabudin', 'cfo@rumahberes.com', '$2y$12$Gaj7J8qu1XLbotdCYWZK.Oe6kxfFvod2WG/hvv/nNwVhXyHroGv1m', 'admin', NULL, NULL, 'CFO', 'avatars/XIum8VUCkFzQSsNTlqIG0nVqeabF5qWRRPkrcrvY.png', NULL, NULL, NULL, 1, '2025-12-13 22:16:24', '2025-12-13 22:30:47'),
(15, 'CMO - Brandon Haniif Lastomo', 'cmo@rumahberes.com', '$2y$12$4EVFlDh4GF7OVR2nluc8FOrkuZ7SAZZ2zU1ualYHhWOzM83BWorvO', 'admin', NULL, NULL, 'CMO', 'avatars/FzDn2FIixyfEoULs7cL204aF5Fyls1CpYPUD14a6.png', NULL, NULL, NULL, 1, '2025-12-13 22:17:29', '2025-12-13 22:30:00'),
(16, 'Lewis Hamilton', 'hamilton@email.com', '$2y$12$sQG5Edmq1eyK/bYwPYJUte2H6SLCzW7OebUVSC3w77o00ueJGcq86', 'technician', NULL, 'Indonesia', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'avatars/5GLGtd9dcNCYFBiPDbShrMQjf3KoEjUQCHbaUooG.png', 'uploads/ktp/DuRa5tAhAnRS9Zox3isM5Z0fFs2AgDYhPAnYJnNu.png', 'uploads/certificates/htDjpMoAmeXzboqJZj9XQinzBlxZy9yO7JuMtxeH.png', 'Cooling', 1, '2025-12-13 22:38:20', '2025-12-13 22:59:26'),
(17, 'Charles Leclerc', 'leclerc@email.com', '$2y$12$oCCyZcaAnS2WD/ta9R2VeOFuCQs5p9InwTnOJ5a9GzJ7Vhrl70qBK', 'technician', NULL, 'Indonesia', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'avatars/tTw4cLWNrZAEwMyuA7OwFClc51AegdnGOEuuBiRA.png', 'uploads/ktp/A6Ie3nNtSdEHTwyyCSgm6eJoHhgXr0P6S6loNbQJ.png', 'uploads/certificates/jq9qndtdojtBfIRtMOqzbcOO0TBOOfKoTHiqgqSa.png', 'Entertainment', 1, '2025-12-13 22:41:49', '2025-12-13 23:00:16'),
(18, 'Max Verstappen', 'verstappen@email.com', '$2y$12$rCKjASavanOcnNe/SzaO../mhPPNOOJpjS3fGnkNaNo3G8r3NroNO', 'technician', NULL, 'Indonesia', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'avatars/4HroirURqMHogcsgh0iEstukIOnvstLj9NFeU3hp.png', 'uploads/ktp/My47Kc7YLSUordgP8kz4w0ynzetz6Z9rDs2uOuOr.png', 'uploads/certificates/ZHAMXlXPbH0Dr8CkPmRnj0z6kHjqNsxbePjmM2Hb.png', 'Laundry', 1, '2025-12-13 22:45:40', '2025-12-13 23:01:34'),
(19, 'Isack Hadjar', 'hadjar@email.com', '$2y$12$bHEF8sA/FuRxum3xkKk4Luxb5PiKJHioPNMevMlt/90VWM6sRQvoC', 'technician', NULL, 'Indonesia', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'avatars/5dq3ezM6iO1LZqheWdpRXyZUz3e9f6IglVJ4LJ7v.png', 'uploads/ktp/5xFZfnjY2ryeTvjdEH3BzN2bQDBicmsQsiNWB0Lc.png', 'uploads/certificates/GrT3fgUyRKAn0kMgMzndRP2j5VFdy2xwcYHTnDdp.png', 'Kitchen', 1, '2025-12-13 22:46:18', '2025-12-13 23:02:05'),
(20, 'George Russell', 'russell@email.com', '$2y$12$kdYsr3f7BJ1LluVPP8.9HeF4s5WQVtV8QQZtduO9ePkkeCZYRYGlO', 'technician', NULL, 'Indonesia', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'avatars/DoMg8jiNHCzwHDWdeQp71aUZ8LGYqCpEOu0mSoes.png', 'uploads/ktp/OhTtuBiWLL2UWN6UfyboU46FjuDZyFnFT9rQK0zW.png', 'uploads/certificates/DZlO87DVZQVrgMHxJIkxSwYR4x280aDmh0nY04vk.png', 'Computing', 1, '2025-12-13 22:47:20', '2025-12-13 23:00:48'),
(21, 'Kimi Antonelli', 'antonelli@email.com', '$2y$12$wCO/6WktAFB4uKs8PSLxUO.u08Q/z2w36eIKsWC8DNPtEs8izQ0SS', 'technician', NULL, 'Indonesia', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'avatars/fTxscShs0UV0IpQS3MLOPB8iIsWP5WYvUo5dfPKR.png', 'uploads/ktp/21Pj8pNga9wybxr9kl3050ldX6pADMkD697w2Nby.png', 'uploads/certificates/agkaWbDzXIrBz4mBVIM6rGbWwZbjbaUlV9SpgqT1.png', 'Water', 1, '2025-12-13 22:47:56', '2025-12-13 23:01:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_messages_sender_id_foreign` (`sender_id`),
  ADD KEY `chat_messages_order_id_created_at_index` (`order_id`,`created_at`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_customer_id_foreign` (`customer_id`),
  ADD KEY `orders_technician_id_foreign` (`technician_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_technician_id_foreign` FOREIGN KEY (`technician_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
