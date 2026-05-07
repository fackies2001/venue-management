-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2026 at 02:45 AM
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
-- Database: `venue_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `subject_type` varchar(255) NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `subject_type`, `subject_id`, `description`, `properties`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, NULL, 'created', 'App\\Models\\Booking', 5, 'Jer Y. Yon submitted new booking \"Sky\"', '{\"event_title\":\"Sky\",\"venue\":\"Fairview branch\",\"event_date\":\"Apr 30, 2026\",\"booker_name\":\"Jer Y. Yon\",\"division\":\"PDEA\",\"status\":\"pending\"}', '127.0.0.1', '2026-04-30 05:54:20', '2026-04-30 05:54:20'),
(2, NULL, 'deleted', 'App\\Models\\Booking', 5, 'Jer Y. Yon deleted booking \"Sky\"', '{\"event_title\":\"Sky\",\"venue\":\"Fairview branch\",\"event_date\":\"Apr 30, 2026\",\"booker_name\":\"Jer Y. Yon\",\"division\":\"PDEA\",\"status\":\"pending\"}', '127.0.0.1', '2026-04-30 06:00:28', '2026-04-30 06:00:28'),
(3, 1, 'restored', 'App\\Models\\Booking', 5, 'John Vincent Fabay restored deleted booking \"Sky\"', '{\"event_title\":\"Sky\",\"venue\":\"Fairview branch\",\"event_date\":\"Apr 30, 2026\",\"booker_name\":\"Jer Y. Yon\",\"division\":\"PDEA\",\"status\":\"pending\"}', '127.0.0.1', '2026-04-30 06:01:29', '2026-04-30 06:01:29'),
(4, NULL, 'rejected', 'App\\Models\\Booking', 5, 'Jer Y. Yon rejected booking \"Sky\"', '{\"event_title\":\"Sky\",\"venue\":\"Fairview branch\",\"event_date\":\"Apr 30, 2026\",\"booker_name\":\"Jer Y. Yon\",\"division\":\"PDEA\",\"status\":\"rejected\",\"reason\":\"Reject Test\"}', '127.0.0.1', '2026-04-30 06:03:30', '2026-04-30 06:03:30'),
(5, 1, 'deleted', 'App\\Models\\Booking', 5, 'John Vincent Fabay deleted booking \"Sky\"', '{\"event_title\":\"Sky\",\"venue\":\"Fairview branch\",\"event_date\":\"Apr 30, 2026\",\"booker_name\":\"Jer Y. Yon\",\"division\":\"PDEA\",\"status\":\"rejected\"}', '127.0.0.1', '2026-04-30 07:47:28', '2026-04-30 07:47:28'),
(6, 1, 'deleted', 'App\\Models\\Booking', 4, 'John Vincent Fabay deleted booking \"Test3\"', '{\"event_title\":\"Test3\",\"venue\":\"OCD Main Building\",\"event_date\":\"Apr 30, 2026\",\"booker_name\":\"Jer Y. Yon\",\"division\":\"NBI\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-01 04:37:07', '2026-05-01 04:37:07'),
(7, 1, 'deleted', 'App\\Models\\Booking', 1, 'John Vincent Fabay deleted booking \"Test 1\"', '{\"event_title\":\"Test 1\",\"venue\":\"Room Test\",\"event_date\":\"Apr 30, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"gg\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-02 15:19:29', '2026-05-02 15:19:29'),
(8, 1, 'deleted', 'App\\Models\\Booking', 2, 'John Vincent Fabay deleted booking \"gg\"', '{\"event_title\":\"gg\",\"venue\":\"Room Test\",\"event_date\":\"May 01, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"gg\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-02 15:19:31', '2026-05-02 15:19:31'),
(9, NULL, 'created', 'App\\Models\\Booking', 6, 'Vincent Fabay submitted new booking \"Test 1\"', '{\"event_title\":\"Test 1\",\"venue\":\"Room Test\",\"event_date\":\"May 05, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"NBI\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-02 15:20:39', '2026-05-02 15:20:39'),
(10, NULL, 'cancelled', 'App\\Models\\Booking', 6, 'Vincent Fabay cancelled booking \"Test 1\"', '{\"event_title\":\"Test 1\",\"venue\":\"Room Test\",\"event_date\":\"May 05, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"NBI\",\"status\":\"cancelled\"}', '127.0.0.1', '2026-05-02 15:21:04', '2026-05-02 15:21:04'),
(11, 1, 'permanently_deleted', 'App\\Models\\Booking', 4, 'John Vincent Fabay permanently deleted booking \"Test3\"', '{\"event_title\":\"Test3\",\"venue\":\"OCD Main Building\",\"event_date\":\"Apr 30, 2026\",\"booker_name\":\"Jer Y. Yon\",\"division\":\"NBI\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-02 15:21:18', '2026-05-02 15:21:18'),
(12, 1, 'permanently_deleted', 'App\\Models\\Booking', 1, 'John Vincent Fabay permanently deleted booking \"Test 1\"', '{\"event_title\":\"Test 1\",\"venue\":\"Room Test\",\"event_date\":\"Apr 30, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"gg\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-02 15:21:22', '2026-05-02 15:21:22'),
(13, 1, 'permanently_deleted', 'App\\Models\\Booking', 2, 'John Vincent Fabay permanently deleted booking \"gg\"', '{\"event_title\":\"gg\",\"venue\":\"Room Test\",\"event_date\":\"May 01, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"gg\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-02 15:21:24', '2026-05-02 15:21:24'),
(14, NULL, 'created', 'App\\Models\\Booking', 7, 'Vincent Fabay submitted new booking \"das\"', '{\"event_title\":\"das\",\"venue\":\"Room Test\",\"event_date\":\"May 07, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"NBI\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-02 15:22:23', '2026-05-02 15:22:23'),
(15, 1, 'approved', 'App\\Models\\Booking', 7, 'John Vincent Fabay approved booking \"das\"', '{\"event_title\":\"das\",\"venue\":\"Room Test\",\"event_date\":\"May 07, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"NBI\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-02 15:22:57', '2026-05-02 15:22:57'),
(16, 1, 'updated', 'App\\Models\\User', 4, 'John Vincent Fabay changed password for user \"Reign Santos\"', '{\"name\":\"Reign Santos\",\"email\":\"reignsantos82@gmail.com\",\"role\":\"user\"}', '127.0.0.1', '2026-05-02 16:15:26', '2026-05-02 16:15:26'),
(17, 1, 'updated', 'App\\Models\\User', 4, 'John Vincent Fabay updated profile of user \"Reign Santos\"', '{\"name\":\"Reign Santos\",\"email\":\"reignsantos82@gmail.com\",\"role\":\"admin\",\"status\":\"active\",\"before\":\"name: Reign Santos, email: reignsantos82@gmail.com, role: user, is_active: active\"}', '127.0.0.1', '2026-05-02 16:18:58', '2026-05-02 16:18:58'),
(18, NULL, 'created', 'App\\Models\\Booking', 8, 'Vincent Fabay submitted new booking \"sdas\"', '{\"event_title\":\"sdas\",\"venue\":\"Room Test\",\"event_date\":\"May 08, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"gg\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-02 16:37:16', '2026-05-02 16:37:16'),
(19, NULL, 'approved', 'App\\Models\\Booking', 8, 'Reign Santos approved booking \"sdas\"', '{\"event_title\":\"sdas\",\"venue\":\"Room Test\",\"event_date\":\"May 08, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"gg\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-02 16:37:49', '2026-05-02 16:37:49'),
(20, NULL, 'deleted', 'App\\Models\\Booking', 6, 'Reign Santos deleted booking \"Test 1\"', '{\"event_title\":\"Test 1\",\"venue\":\"Room Test\",\"event_date\":\"May 05, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"NBI\",\"status\":\"cancelled\"}', '127.0.0.1', '2026-05-02 16:38:06', '2026-05-02 16:38:06'),
(21, NULL, 'created', 'App\\Models\\Booking', 9, 'Vincent Fabay submitted new booking \"sdsad\"', '{\"event_title\":\"sdsad\",\"venue\":\"Room Test\",\"event_date\":\"May 13, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"blabla1\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-02 16:42:30', '2026-05-02 16:42:30'),
(22, NULL, 'cancelled', 'App\\Models\\Booking', 9, 'Vincent Fabay cancelled booking \"sdsad\"', '{\"event_title\":\"sdsad\",\"venue\":\"Room Test\",\"event_date\":\"May 13, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"blabla1\",\"status\":\"cancelled\"}', '127.0.0.1', '2026-05-02 16:42:52', '2026-05-02 16:42:52'),
(23, 1, 'deleted', 'App\\Models\\Booking', 9, 'John Vincent Fabay deleted booking \"sdsad\"', '{\"event_title\":\"sdsad\",\"venue\":\"Room Test\",\"event_date\":\"May 13, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"blabla1\",\"status\":\"cancelled\"}', '127.0.0.1', '2026-05-02 16:43:08', '2026-05-02 16:43:08'),
(24, 1, 'created', 'App\\Models\\Booking', 10, 'John Vincent Fabay submitted new booking \"sdasd\"', '{\"event_title\":\"sdasd\",\"venue\":\"Room Test\",\"event_date\":\"May 06, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-02 18:27:45', '2026-05-02 18:27:45'),
(25, 1, 'approved', 'App\\Models\\Booking', 10, 'John Vincent Fabay approved booking \"sdasd\"', '{\"event_title\":\"sdasd\",\"venue\":\"Room Test\",\"event_date\":\"May 06, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-02 18:27:50', '2026-05-02 18:27:50'),
(26, 1, 'created', 'App\\Models\\Booking', 11, 'John Vincent Fabay submitted new booking \"sdasdsafgdfg\"', '{\"event_title\":\"sdasdsafgdfg\",\"venue\":\"Room Test\",\"event_date\":\"May 07, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-02 18:28:33', '2026-05-02 18:28:33'),
(27, 1, 'approved', 'App\\Models\\Booking', 11, 'John Vincent Fabay approved booking \"sdasdsafgdfg\"', '{\"event_title\":\"sdasdsafgdfg\",\"venue\":\"Room Test\",\"event_date\":\"May 07, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-02 18:28:44', '2026-05-02 18:28:44'),
(28, NULL, 'created', 'App\\Models\\Booking', 12, 'Vincent Fabay submitted new booking \"Test 1\"', '{\"event_title\":\"Test 1\",\"venue\":\"Meeting Room 4B\",\"event_date\":\"May 13, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"dd\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-03 13:48:26', '2026-05-03 13:48:26'),
(29, NULL, 'approved', 'App\\Models\\Booking', 12, 'Reign Santos approved booking \"Test 1\"', '{\"event_title\":\"Test 1\",\"venue\":\"Meeting Room 4B\",\"event_date\":\"May 13, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"dd\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-03 13:48:58', '2026-05-03 13:48:58'),
(30, 1, 'deleted', 'App\\Models\\Booking', 12, 'John Vincent Fabay deleted booking \"Test 1\"', '{\"event_title\":\"Test 1\",\"venue\":\"Meeting Room 4B\",\"event_date\":\"May 13, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"dd\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-04 06:19:34', '2026-05-04 06:19:34'),
(31, 1, 'deleted', 'App\\Models\\Booking', 11, 'John Vincent Fabay deleted booking \"sdasdsafgdfg\"', '{\"event_title\":\"sdasdsafgdfg\",\"venue\":\"Room Test\",\"event_date\":\"May 07, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-04 06:19:36', '2026-05-04 06:19:36'),
(32, 1, 'deleted', 'App\\Models\\Booking', 10, 'John Vincent Fabay deleted booking \"sdasd\"', '{\"event_title\":\"sdasd\",\"venue\":\"Room Test\",\"event_date\":\"May 06, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-04 06:19:38', '2026-05-04 06:19:38'),
(33, 1, 'deleted', 'App\\Models\\Booking', 8, 'John Vincent Fabay deleted booking \"sdas\"', '{\"event_title\":\"sdas\",\"venue\":\"Room Test\",\"event_date\":\"May 08, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"gg\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-04 06:19:39', '2026-05-04 06:19:39'),
(34, 1, 'deleted', 'App\\Models\\Booking', 7, 'John Vincent Fabay deleted booking \"das\"', '{\"event_title\":\"das\",\"venue\":\"Room Test\",\"event_date\":\"May 07, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"NBI\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-04 06:19:41', '2026-05-04 06:19:41'),
(35, 1, 'deleted', 'App\\Models\\User', 3, 'John Vincent Fabay deleted user \"Vincent Fabay\"', '{\"name\":\"Vincent Fabay\",\"email\":\"vincentfabs2001@gmail.com\",\"role\":\"user\",\"status\":\"active\"}', '127.0.0.1', '2026-05-04 06:27:04', '2026-05-04 06:27:04'),
(36, 1, 'deleted', 'App\\Models\\User', 4, 'John Vincent Fabay deleted user \"Reign Santos\"', '{\"name\":\"Reign Santos\",\"email\":\"reignsantos82@gmail.com\",\"role\":\"admin\",\"status\":\"active\"}', '127.0.0.1', '2026-05-04 06:27:06', '2026-05-04 06:27:06'),
(37, NULL, 'created', 'App\\Models\\Booking', 13, 'Vincent Fabay submitted new booking \"gg\"', '{\"event_title\":\"gg\",\"venue\":\"Room Test\",\"event_date\":\"May 07, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"SIMS\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-04 06:38:23', '2026-05-04 06:38:23'),
(38, 1, 'updated', 'App\\Models\\User', 6, 'John Vincent Fabay updated profile of user \"Reign Santos\"', '{\"name\":\"Reign Santos\",\"email\":\"reignsantos82@gmail.com\",\"role\":\"admin\",\"status\":\"active\",\"before\":\"name: Reign Santos, email: reignsantos82@gmail.com, role: user, is_active: active\"}', '127.0.0.1', '2026-05-04 06:40:10', '2026-05-04 06:40:10'),
(39, NULL, 'updated', 'App\\Models\\Booking', 13, 'Vincent Fabay updated their pending booking \"gg\"', '{\"event_title\":\"gg\",\"venue\":\"Room Test\",\"event_date\":\"May 07, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"OFFICE OF CIVIL DEFENSE\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-04 06:42:22', '2026-05-04 06:42:22'),
(40, NULL, 'approved', 'App\\Models\\Booking', 13, 'Reign Santos approved booking \"gg\"', '{\"event_title\":\"gg\",\"venue\":\"Room Test\",\"event_date\":\"May 07, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"OFFICE OF CIVIL DEFENSE\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-04 06:42:41', '2026-05-04 06:42:41'),
(41, NULL, 'created', 'App\\Models\\Booking', 14, 'Vincent Fabay submitted new booking \"sdasda\"', '{\"event_title\":\"sdasda\",\"venue\":\"Room Test\",\"event_date\":\"May 09, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-04 06:45:24', '2026-05-04 06:45:24'),
(42, NULL, 'cancelled', 'App\\Models\\Booking', 14, 'Vincent Fabay cancelled booking \"sdasda\"', '{\"event_title\":\"sdasda\",\"venue\":\"Room Test\",\"event_date\":\"May 09, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"cancelled\"}', '127.0.0.1', '2026-05-04 06:46:12', '2026-05-04 06:46:12'),
(43, NULL, 'deleted', 'App\\Models\\Booking', 14, 'Reign Santos deleted booking \"sdasda\"', '{\"event_title\":\"sdasda\",\"venue\":\"Room Test\",\"event_date\":\"May 09, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"cancelled\"}', '127.0.0.1', '2026-05-04 06:46:30', '2026-05-04 06:46:30'),
(44, NULL, 'archived', 'App\\Models\\Booking', 13, 'Reign Santos archived booking \"gg\"', '{\"event_title\":\"gg\",\"venue\":\"Room Test\",\"event_date\":\"May 07, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"OFFICE OF CIVIL DEFENSE\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-04 06:56:07', '2026-05-04 06:56:07'),
(45, NULL, 'archived', 'App\\Models\\Booking', 14, 'Reign Santos archived booking \"sdasda\"', '{\"event_title\":\"sdasda\",\"venue\":\"Room Test\",\"event_date\":\"May 09, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"cancelled\"}', '127.0.0.1', '2026-05-04 07:01:28', '2026-05-04 07:01:28'),
(46, NULL, 'archived', 'App\\Models\\Booking', 13, 'Reign Santos archived booking \"gg\"', '{\"event_title\":\"gg\",\"venue\":\"Room Test\",\"event_date\":\"May 07, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"OFFICE OF CIVIL DEFENSE\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-04 07:01:30', '2026-05-04 07:01:30'),
(47, NULL, 'archived', 'App\\Models\\Booking', 11, 'Reign Santos archived booking \"sdasdsafgdfg\"', '{\"event_title\":\"sdasdsafgdfg\",\"venue\":\"Room Test\",\"event_date\":\"May 07, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-04 07:01:31', '2026-05-04 07:01:31'),
(48, NULL, 'archived', 'App\\Models\\Booking', 10, 'Reign Santos archived booking \"sdasd\"', '{\"event_title\":\"sdasd\",\"venue\":\"Room Test\",\"event_date\":\"May 06, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-04 07:01:33', '2026-05-04 07:01:33'),
(49, 1, 'permanently_deleted', 'App\\Models\\Booking', 14, 'John Vincent Fabay permanently deleted booking \"sdasda\"', '{\"event_title\":\"sdasda\",\"venue\":\"Room Test\",\"event_date\":\"May 09, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"cancelled\"}', '127.0.0.1', '2026-05-04 07:02:05', '2026-05-04 07:02:05'),
(50, 1, 'permanently_deleted', 'App\\Models\\Booking', 13, 'John Vincent Fabay permanently deleted booking \"gg\"', '{\"event_title\":\"gg\",\"venue\":\"Room Test\",\"event_date\":\"May 07, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"OFFICE OF CIVIL DEFENSE\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-04 07:02:08', '2026-05-04 07:02:08'),
(51, 1, 'permanently_deleted', 'App\\Models\\Booking', 11, 'John Vincent Fabay permanently deleted booking \"sdasdsafgdfg\"', '{\"event_title\":\"sdasdsafgdfg\",\"venue\":\"Room Test\",\"event_date\":\"May 07, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-04 07:02:12', '2026-05-04 07:02:12'),
(52, 1, 'permanently_deleted', 'App\\Models\\Booking', 10, 'John Vincent Fabay permanently deleted booking \"sdasd\"', '{\"event_title\":\"sdasd\",\"venue\":\"Room Test\",\"event_date\":\"May 06, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-04 07:02:15', '2026-05-04 07:02:15'),
(53, 1, 'updated', 'App\\Models\\User', 1, 'John Vincent Fabay updated profile of user \"John Vincent Fabay\"', '{\"name\":\"John Vincent Fabay\",\"email\":\"fabayjohnvincent@gmail.com\",\"role\":\"super_admin\",\"status\":\"active\",\"before\":\"name: John Vincent Fabay, email: fabayjohnvincent@gmail.com, role: super_admin, is_active: active\"}', '127.0.0.1', '2026-05-04 07:03:14', '2026-05-04 07:03:14'),
(54, 1, 'deleted', 'App\\Models\\User', 8, 'John Vincent Fabay deleted user \"None Reply\"', '{\"name\":\"None Reply\",\"email\":\"nonereply@ocd.gov.ph\",\"role\":\"user\",\"status\":\"inactive\"}', '127.0.0.1', '2026-05-04 07:42:25', '2026-05-04 07:42:25'),
(55, 1, 'updated', 'App\\Models\\User', 9, 'John Vincent Fabay updated profile of user \"Nore Ply\"', '{\"name\":\"Nore Ply\",\"email\":\"noreply@ocd.gov.ph\",\"role\":\"super_admin\",\"status\":\"active\",\"before\":\"name: Nore Ply, email: noreply@ocd.gov.ph, role: user, is_active: active\"}', '127.0.0.1', '2026-05-04 07:47:44', '2026-05-04 07:47:44'),
(56, 1, 'updated', 'App\\Models\\User', 7, 'John Vincent Fabay updated profile of user \"ICTS admin\"', '{\"name\":\"ICTS admin\",\"email\":\"icts@ocd.gov.ph\",\"role\":\"super_admin\",\"status\":\"active\",\"before\":\"name: ICTS admin, email: icts@ocd.gov.ph, role: user, is_active: active\"}', '127.0.0.1', '2026-05-04 08:16:09', '2026-05-04 08:16:09'),
(57, NULL, 'created', 'App\\Models\\Booking', 15, 'Vincent Fabay submitted new booking \"Orientation of VMS\"', '{\"event_title\":\"Orientation of VMS\",\"venue\":\"Meeting Room A\",\"event_date\":\"May 13, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"SIMS\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-04 08:25:19', '2026-05-04 08:25:19'),
(58, NULL, 'updated', 'App\\Models\\Booking', 15, 'Vincent Fabay updated their pending booking \"Orientation of VMSS\"', '{\"event_title\":\"Orientation of VMSS\",\"venue\":\"Meeting Room A\",\"event_date\":\"May 13, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"OFFICE OF CIVIL DEFENSE\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-04 08:26:16', '2026-05-04 08:26:16'),
(59, NULL, 'approved', 'App\\Models\\Booking', 15, 'Reign Santos approved booking \"Orientation of VMSS\"', '{\"event_title\":\"Orientation of VMSS\",\"venue\":\"Meeting Room A\",\"event_date\":\"May 13, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"OFFICE OF CIVIL DEFENSE\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-04 08:28:17', '2026-05-04 08:28:17'),
(60, NULL, 'cancelled', 'App\\Models\\Booking', 15, 'Reign Santos cancelled booking \"Orientation of VMSS\"', '{\"event_title\":\"Orientation of VMSS\",\"venue\":\"Meeting Room A\",\"event_date\":\"May 13, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"OFFICE OF CIVIL DEFENSE\",\"status\":\"cancelled\"}', '127.0.0.1', '2026-05-04 08:30:58', '2026-05-04 08:30:58'),
(61, NULL, 'archived', 'App\\Models\\Booking', 15, 'Reign Santos archived booking \"Orientation of VMSS\"', '{\"event_title\":\"Orientation of VMSS\",\"venue\":\"Meeting Room A\",\"event_date\":\"May 13, 2026\",\"booker_name\":\"Vincent Fabay\",\"division\":\"OFFICE OF CIVIL DEFENSE\",\"status\":\"cancelled\"}', '127.0.0.1', '2026-05-04 08:33:23', '2026-05-04 08:33:23'),
(62, 1, 'deleted', 'App\\Models\\User', 5, 'John Vincent Fabay deleted user \"Vincent Fabay\"', '{\"name\":\"Vincent Fabay\",\"email\":\"vincentfabs2001@gmail.com\",\"role\":\"user\",\"status\":\"active\"}', '127.0.0.1', '2026-05-04 14:44:15', '2026-05-04 14:44:15'),
(63, 1, 'deleted', 'App\\Models\\User', 2, 'John Vincent Fabay deleted user \"Jer Y. Yon\"', '{\"name\":\"Jer Y. Yon\",\"email\":\"jeryon21@gmail.com\",\"role\":\"admin\",\"status\":\"active\"}', '127.0.0.1', '2026-05-04 14:44:18', '2026-05-04 14:44:18'),
(64, 1, 'deleted', 'App\\Models\\User', 10, 'John Vincent Fabay deleted user \"Vincent Fabay\"', '{\"name\":\"Vincent Fabay\",\"email\":\"vincentfabs2001@gmail.com\",\"role\":\"user\",\"status\":\"active\"}', '127.0.0.1', '2026-05-04 14:50:12', '2026-05-04 14:50:12'),
(65, 1, 'activated', 'App\\Models\\User', 11, 'John Vincent Fabay activated user \"Vincent Fabay\"', '{\"name\":\"Vincent Fabay\",\"email\":\"vincentfabs2001@gmail.com\",\"role\":\"user\"}', '127.0.0.1', '2026-05-04 14:57:46', '2026-05-04 14:57:46'),
(66, 1, 'updated', 'App\\Models\\User', 7, 'John Vincent Fabay updated profile of user \"ICTS admin\"', '{\"name\":\"ICTS admin\",\"email\":\"icts@ocd.gov.ph\",\"role\":\"admin\",\"status\":\"active\",\"before\":\"name: ICTS admin, email: icts@ocd.gov.ph, role: super_admin, is_active: active\"}', '127.0.0.1', '2026-05-05 00:30:12', '2026-05-05 00:30:12'),
(67, 9, 'activated', 'App\\Models\\User', 12, 'Nore Ply activated user \"Ruben Tanaji Carandang II\"', '{\"name\":\"Ruben Tanaji Carandang II\",\"email\":\"ruben.carandangii@ocd.gov.ph\",\"role\":\"user\"}', '127.0.0.1', '2026-05-05 00:41:29', '2026-05-05 00:41:29'),
(68, NULL, 'created', 'App\\Models\\Booking', 16, 'Ruben Tanaji Carandang II submitted new booking \"Orientation of VMS\"', '{\"event_title\":\"Orientation of VMS\",\"venue\":\"Multi Meeting Room\",\"event_date\":\"May 06, 2026\",\"booker_name\":\"Ruben Tanaji Carandang II\",\"division\":\"Systems Development, Integration and Management Division\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-05 00:51:36', '2026-05-05 00:51:36'),
(69, NULL, 'updated', 'App\\Models\\Booking', 16, 'Ruben Tanaji Carandang II updated their pending booking \"Orientation of VMS\"', '{\"event_title\":\"Orientation of VMS\",\"venue\":\"Multi Meeting Room\",\"event_date\":\"May 06, 2026\",\"booker_name\":\"Ruben Tanaji Carandang II\",\"division\":\"OFFICE OF CIVIL DEFENSE\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-05 00:52:41', '2026-05-05 00:52:41'),
(70, 7, 'approved', 'App\\Models\\Booking', 16, 'ICTS admin approved booking \"Orientation of VMS\"', '{\"event_title\":\"Orientation of VMS\",\"venue\":\"Multi Meeting Room\",\"event_date\":\"May 06, 2026\",\"booker_name\":\"Ruben Tanaji Carandang II\",\"division\":\"OFFICE OF CIVIL DEFENSE\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-05 00:56:04', '2026-05-05 00:56:04'),
(71, NULL, 'created', 'App\\Models\\Booking', 17, 'Ruben Tanaji Carandang II submitted new booking \"Orientation of DMS\"', '{\"event_title\":\"Orientation of DMS\",\"venue\":\"Meeting Room A\",\"event_date\":\"May 06, 2026\",\"booker_name\":\"Ruben Tanaji Carandang II\",\"division\":\"SDIMD\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-05 01:02:26', '2026-05-05 01:02:26'),
(72, NULL, 'created', 'App\\Models\\Booking', 18, 'Ruben Tanaji Carandang II submitted new booking \"Test 1\"', '{\"event_title\":\"Test 1\",\"venue\":\"Multi Meeting Room\",\"event_date\":\"May 06, 2026\",\"booker_name\":\"Ruben Tanaji Carandang II\",\"division\":\"SDIMD\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-05 01:03:08', '2026-05-05 01:03:08'),
(73, 7, 'rejected', 'App\\Models\\Booking', 18, 'ICTS admin rejected booking \"Test 1\"', '{\"event_title\":\"Test 1\",\"venue\":\"Multi Meeting Room\",\"event_date\":\"May 06, 2026\",\"booker_name\":\"Ruben Tanaji Carandang II\",\"division\":\"SDIMD\",\"status\":\"rejected\",\"reason\":\"same booking time\"}', '127.0.0.1', '2026-05-05 01:04:05', '2026-05-05 01:04:05'),
(74, 7, 'archived', 'App\\Models\\Booking', 18, 'ICTS admin archived booking \"Test 1\"', '{\"event_title\":\"Test 1\",\"venue\":\"Multi Meeting Room\",\"event_date\":\"May 06, 2026\",\"booker_name\":\"Ruben Tanaji Carandang II\",\"division\":\"SDIMD\",\"status\":\"rejected\"}', '127.0.0.1', '2026-05-05 01:04:15', '2026-05-05 01:04:15'),
(75, 7, 'approved', 'App\\Models\\Booking', 17, 'ICTS admin approved booking \"Orientation of DMS\"', '{\"event_title\":\"Orientation of DMS\",\"venue\":\"Meeting Room A\",\"event_date\":\"May 06, 2026\",\"booker_name\":\"Ruben Tanaji Carandang II\",\"division\":\"SDIMD\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-05 01:04:19', '2026-05-05 01:04:19'),
(76, 9, 'deactivated', 'App\\Models\\User', 12, 'Nore Ply deactivated user \"Ruben Tanaji Carandang II\"', '{\"name\":\"Ruben Tanaji Carandang II\",\"email\":\"ruben.carandangii@ocd.gov.ph\",\"role\":\"user\"}', '127.0.0.1', '2026-05-05 01:09:20', '2026-05-05 01:09:20'),
(77, 1, 'deleted', 'App\\Models\\User', 6, 'John Vincent Fabay deleted user \"Reign Santos\"', '{\"name\":\"Reign Santos\",\"email\":\"reignsantos82@gmail.com\",\"role\":\"admin\",\"status\":\"active\"}', '127.0.0.1', '2026-05-05 01:16:59', '2026-05-05 01:16:59'),
(78, 1, 'deleted', 'App\\Models\\User', 11, 'John Vincent Fabay deleted user \"Vincent Fabay\"', '{\"name\":\"Vincent Fabay\",\"email\":\"vincentfabs2001@gmail.com\",\"role\":\"user\",\"status\":\"active\"}', '127.0.0.1', '2026-05-05 01:17:05', '2026-05-05 01:17:05'),
(79, 1, 'deleted', 'App\\Models\\User', 13, 'John Vincent Fabay deleted user \"Vincent Fabay\"', '{\"name\":\"Vincent Fabay\",\"email\":\"vincentfabs2001@gmail.com\",\"role\":\"user\",\"status\":\"inactive\"}', '127.0.0.1', '2026-05-05 01:38:29', '2026-05-05 01:38:29'),
(80, 1, 'deleted', 'App\\Models\\User', 14, 'John Vincent Fabay deleted user \"Vincent Fabay\"', '{\"name\":\"Vincent Fabay\",\"email\":\"vincentfabs2001@gmail.com\",\"role\":\"user\",\"status\":\"inactive\"}', '127.0.0.1', '2026-05-05 02:12:35', '2026-05-05 02:12:35'),
(81, 1, 'deleted', 'App\\Models\\User', 15, 'John Vincent Fabay deleted user \"Vincent Fabay\"', '{\"name\":\"Vincent Fabay\",\"email\":\"vincentfabs2001@gmail.com\",\"role\":\"user\",\"status\":\"inactive\"}', '127.0.0.1', '2026-05-05 02:15:47', '2026-05-05 02:15:47'),
(82, 1, 'created', 'App\\Models\\Booking', 19, 'John Vincent Fabay submitted new booking \"Test 1\"', '{\"event_title\":\"Test 1\",\"venue\":\"Meeting Room A\",\"event_date\":\"May 07, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-05 02:52:51', '2026-05-05 02:52:51'),
(83, 1, 'created', 'App\\Models\\Booking', 20, 'John Vincent Fabay submitted new booking \"Test 2\"', '{\"event_title\":\"Test 2\",\"venue\":\"Main Conference\",\"event_date\":\"May 08, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-05 02:53:23', '2026-05-05 02:53:23'),
(84, 1, 'created', 'App\\Models\\Booking', 21, 'John Vincent Fabay submitted new booking \"Test 3\"', '{\"event_title\":\"Test 3\",\"venue\":\"Meeting Room B\",\"event_date\":\"May 09, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-05 02:54:06', '2026-05-05 02:54:06'),
(85, 1, 'created', 'App\\Models\\Booking', 22, 'John Vincent Fabay submitted new booking \"Test 4\"', '{\"event_title\":\"Test 4\",\"venue\":\"Main Conference VIP Multi Media\",\"event_date\":\"May 11, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-05 02:54:56', '2026-05-05 02:54:56'),
(86, 1, 'created', 'App\\Models\\Booking', 23, 'John Vincent Fabay submitted new booking \"Test 5\"', '{\"event_title\":\"Test 5\",\"venue\":\"Execom Meeting Room\",\"event_date\":\"May 12, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-05 02:55:24', '2026-05-05 02:55:24'),
(87, 1, 'created', 'App\\Models\\Booking', 24, 'John Vincent Fabay submitted new booking \"Test 11\"', '{\"event_title\":\"Test 11\",\"venue\":\"Meeting Room A\",\"event_date\":\"May 06, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-05 03:10:37', '2026-05-05 03:10:37'),
(88, 1, 'activated', 'App\\Models\\User', 16, 'John Vincent Fabay activated user \"Vincent Fabay\"', '{\"name\":\"Vincent Fabay\",\"email\":\"vincentfabs2001@gmail.com\",\"role\":\"user\"}', '127.0.0.1', '2026-05-05 04:42:55', '2026-05-05 04:42:55'),
(89, 1, 'updated', 'App\\Models\\User', 16, 'John Vincent Fabay updated profile of user \"Vincent Fabay\"', '{\"name\":\"Vincent Fabay\",\"email\":\"vincentfabs2001@gmail.com\",\"role\":\"admin\",\"status\":\"active\",\"before\":\"name: Vincent Fabay, email: vincentfabs2001@gmail.com, role: user, is_active: active\"}', '127.0.0.1', '2026-05-05 04:43:00', '2026-05-05 04:43:00'),
(90, 1, 'deleted', 'App\\Models\\User', 16, 'John Vincent Fabay deleted user \"Vincent Fabay\"', '{\"name\":\"Vincent Fabay\",\"email\":\"vincentfabs2001@gmail.com\",\"role\":\"admin\",\"status\":\"active\"}', '127.0.0.1', '2026-05-05 04:43:42', '2026-05-05 04:43:42'),
(91, 1, 'activated', 'App\\Models\\User', 17, 'John Vincent Fabay activated user \"Vincent Fabay\"', '{\"name\":\"Vincent Fabay\",\"email\":\"vincentfabs2001@gmail.com\",\"role\":\"user\"}', '127.0.0.1', '2026-05-05 04:44:43', '2026-05-05 04:44:43'),
(92, 1, 'updated', 'App\\Models\\User', 17, 'John Vincent Fabay updated profile of user \"Vincent Fabay\"', '{\"name\":\"Vincent Fabay\",\"email\":\"vincentfabs2001@gmail.com\",\"role\":\"admin\",\"status\":\"active\",\"before\":\"name: Vincent Fabay, email: vincentfabs2001@gmail.com, role: user, is_active: active\"}', '127.0.0.1', '2026-05-05 04:45:20', '2026-05-05 04:45:20'),
(93, 1, 'approved', 'App\\Models\\Booking', 24, 'John Vincent Fabay approved booking \"Test 11\"', '{\"event_title\":\"Test 11\",\"venue\":\"Meeting Room A\",\"event_date\":\"May 06, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-05 05:46:34', '2026-05-05 05:46:34'),
(94, 1, 'cancelled', 'App\\Models\\Booking', 24, 'John Vincent Fabay cancelled booking \"Test 11\"', '{\"event_title\":\"Test 11\",\"venue\":\"Meeting Room A\",\"event_date\":\"May 06, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"cancelled\"}', '127.0.0.1', '2026-05-05 05:46:45', '2026-05-05 05:46:45'),
(95, 1, 'approved', 'App\\Models\\Booking', 23, 'John Vincent Fabay approved booking \"Test 5\"', '{\"event_title\":\"Test 5\",\"venue\":\"Execom Meeting Room\",\"event_date\":\"May 12, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-05 05:54:20', '2026-05-05 05:54:20'),
(96, 1, 'cancelled', 'App\\Models\\Booking', 23, 'John Vincent Fabay cancelled booking \"Test 5\"', '{\"event_title\":\"Test 5\",\"venue\":\"Execom Meeting Room\",\"event_date\":\"May 12, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"cancelled\"}', '127.0.0.1', '2026-05-05 05:54:39', '2026-05-05 05:54:39'),
(97, 1, 'cancelled', 'App\\Models\\Booking', 17, 'John Vincent Fabay cancelled booking \"Orientation of DMS\"', '{\"event_title\":\"Orientation of DMS\",\"venue\":\"Meeting Room A\",\"event_date\":\"May 06, 2026\",\"booker_name\":\"Ruben Tanaji Carandang II\",\"division\":\"SDIMD\",\"status\":\"cancelled\"}', '127.0.0.1', '2026-05-05 05:55:08', '2026-05-05 05:55:08'),
(98, 1, 'approved', 'App\\Models\\Booking', 22, 'John Vincent Fabay approved booking \"Test 4\"', '{\"event_title\":\"Test 4\",\"venue\":\"Main Conference VIP Multi Media\",\"event_date\":\"May 11, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-05 05:57:48', '2026-05-05 05:57:48'),
(99, 1, 'cancelled', 'App\\Models\\Booking', 22, 'John Vincent Fabay cancelled booking \"Test 4\"', '{\"event_title\":\"Test 4\",\"venue\":\"Main Conference VIP Multi Media\",\"event_date\":\"May 11, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"cancelled\"}', '127.0.0.1', '2026-05-05 05:58:11', '2026-05-05 05:58:11'),
(100, 1, 'approved', 'App\\Models\\Booking', 21, 'John Vincent Fabay approved booking \"Test 3\"', '{\"event_title\":\"Test 3\",\"venue\":\"Meeting Room B\",\"event_date\":\"May 09, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-05 06:02:18', '2026-05-05 06:02:18'),
(101, 1, 'cancelled', 'App\\Models\\Booking', 21, 'John Vincent Fabay cancelled booking \"Test 3\"', '{\"event_title\":\"Test 3\",\"venue\":\"Meeting Room B\",\"event_date\":\"May 09, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"cancelled\",\"reason\":\"No reason provided\"}', '127.0.0.1', '2026-05-05 06:02:36', '2026-05-05 06:02:36'),
(102, 1, 'rejected', 'App\\Models\\Booking', 20, 'John Vincent Fabay rejected booking \"Test 2\"', '{\"event_title\":\"Test 2\",\"venue\":\"Main Conference\",\"event_date\":\"May 08, 2026\",\"booker_name\":\"John Vincent Fabay\",\"division\":\"SDIMD\",\"status\":\"rejected\",\"reason\":\"No reason provided\"}', '127.0.0.1', '2026-05-05 06:11:03', '2026-05-05 06:11:03'),
(103, 1, 'deleted', 'App\\Models\\User', 17, 'John Vincent Fabay deleted user \"Vincent Fabay\"', '{\"name\":\"Vincent Fabay\",\"email\":\"vincentfabs2001@gmail.com\",\"role\":\"admin\",\"status\":\"active\"}', '127.0.0.1', '2026-05-05 07:12:51', '2026-05-05 07:12:51'),
(104, 1, 'deleted', 'App\\Models\\User', 18, 'John Vincent Fabay deleted user \"Vincent Fabay\"', '{\"name\":\"Vincent Fabay\",\"email\":\"vincentfabs2001@gmail.com\",\"role\":\"user\",\"status\":\"active\"}', '127.0.0.1', '2026-05-05 07:15:05', '2026-05-05 07:15:05'),
(105, 1, 'activated', 'App\\Models\\User', 19, 'John Vincent Fabay activated user \"Vincent Fabay\"', '{\"name\":\"Vincent Fabay\",\"email\":\"vincentfabs2001@gmail.com\",\"role\":\"user\"}', '127.0.0.1', '2026-05-05 07:15:46', '2026-05-05 07:15:46'),
(106, 9, 'deleted', 'App\\Models\\User', 12, 'Nore Ply deleted user \"Ruben Tanaji Carandang II\"', '{\"name\":\"Ruben Tanaji Carandang II\",\"email\":\"ruben.carandangii@ocd.gov.ph\",\"role\":\"user\",\"status\":\"inactive\"}', '127.0.0.1', '2026-05-05 08:28:01', '2026-05-05 08:28:01'),
(107, 9, 'activated', 'App\\Models\\User', 20, 'Nore Ply activated user \"Ruben T Carandang II\"', '{\"name\":\"Ruben T Carandang II\",\"email\":\"ruben.carandangii@ocd.gov.ph\",\"role\":\"user\"}', '127.0.0.1', '2026-05-05 08:33:35', '2026-05-05 08:33:35'),
(108, 20, 'created', 'App\\Models\\Booking', 25, 'Ruben T Carandang II submitted new booking \"Test 1\"', '{\"event_title\":\"Test 1\",\"venue\":\"Meeting Room B\",\"event_date\":\"May 07, 2026\",\"booker_name\":\"Ruben T Carandang II\",\"division\":\"SDIMD\",\"status\":\"pending\"}', '127.0.0.1', '2026-05-05 08:52:22', '2026-05-05 08:52:22'),
(109, 1, 'approved', 'App\\Models\\Booking', 25, 'John Vincent Fabay approved booking \"Test 1\"', '{\"event_title\":\"Test 1\",\"venue\":\"Meeting Room B\",\"event_date\":\"May 07, 2026\",\"booker_name\":\"Ruben T Carandang II\",\"division\":\"SDIMD\",\"status\":\"approved\"}', '127.0.0.1', '2026-05-05 08:56:02', '2026-05-05 08:56:02');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `venue_id` bigint(20) UNSIGNED NOT NULL,
  `event_title` varchar(255) NOT NULL,
  `agenda` varchar(255) DEFAULT NULL,
  `event_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `expected_attendees` int(10) UNSIGNED NOT NULL,
  `purpose` text DEFAULT NULL,
  `booker_name` varchar(255) NOT NULL,
  `service` varchar(255) NOT NULL,
  `division` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','cancelled','completed','moved') NOT NULL DEFAULT 'pending',
  `admin_remarks` text DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `venue_id`, `event_title`, `agenda`, `event_date`, `start_time`, `end_time`, `expected_attendees`, `purpose`, `booker_name`, `service`, `division`, `email`, `phone`, `attachment_path`, `remarks`, `status`, `admin_remarks`, `approved_by`, `approved_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(19, 1, 7, 'Test 1', 'Test 1', '2026-05-07', '13:00:00', '14:00:00', 4, NULL, 'John Vincent Fabay', 'INFORMATION AND COMMUNICATION TECHNOLOGY SERVICE', 'SDIMD', 'fabayjohnvincent@gmail.com', '09683131352', NULL, NULL, 'pending', NULL, NULL, NULL, '2026-05-05 02:52:51', '2026-05-05 02:52:51', NULL),
(20, 1, 8, 'Test 2', 'Test 2', '2026-05-08', '14:30:00', '15:30:00', 7, NULL, 'John Vincent Fabay', 'INFORMATION AND COMMUNICATION TECHNOLOGY SERVICE', 'SDIMD', 'fabayjohnvincent@gmail.com', '09683131352', NULL, NULL, 'rejected', NULL, 1, '2026-05-05 06:11:03', '2026-05-05 02:53:23', '2026-05-05 06:11:03', NULL),
(21, 1, 9, 'Test 3', 'Test 3', '2026-05-09', '16:00:00', '17:00:00', 5, NULL, 'John Vincent Fabay', 'INFORMATION AND COMMUNICATION TECHNOLOGY SERVICE', 'SDIMD', 'fabayjohnvincent@gmail.com', '09683131352', NULL, 'sadsadsada', 'cancelled', NULL, 1, '2026-05-05 06:02:36', '2026-05-05 02:54:06', '2026-05-05 06:02:36', NULL),
(22, 1, 10, 'Test 4', 'Test 4', '2026-05-11', '10:00:00', '11:30:00', 4, NULL, 'John Vincent Fabay', 'INFORMATION AND COMMUNICATION TECHNOLOGY SERVICE', 'SDIMD', 'fabayjohnvincent@gmail.com', '09683131352', NULL, NULL, 'cancelled', NULL, 1, '2026-05-05 05:57:48', '2026-05-05 02:54:56', '2026-05-05 05:58:11', NULL),
(23, 1, 11, 'Test 5', 'Test 5', '2026-05-12', '14:30:00', '17:00:00', 2, NULL, 'John Vincent Fabay', 'INFORMATION AND COMMUNICATION TECHNOLOGY SERVICE', 'SDIMD', 'fabayjohnvincent@gmail.com', '09683131352', NULL, NULL, 'cancelled', NULL, 1, '2026-05-05 05:54:20', '2026-05-05 02:55:23', '2026-05-05 05:54:39', NULL),
(24, 1, 7, 'Test 11', 'Test 11', '2026-05-06', '09:00:00', '10:00:00', 5, NULL, 'John Vincent Fabay', 'INFORMATION AND COMMUNICATION TECHNOLOGY SERVICE', 'SDIMD', 'fabayjohnvincent@gmail.com', '09683131352', NULL, NULL, 'cancelled', NULL, 1, '2026-05-05 05:46:34', '2026-05-05 03:10:37', '2026-05-05 05:46:45', NULL),
(25, 20, 9, 'Test 1', 'Test 1', '2026-05-07', '09:00:00', '10:00:00', 15, NULL, 'Ruben T Carandang II', 'INFORMATION AND COMMUNICATION TECHNOLOGY SERVICE', 'SDIMD', 'ruben.carandangii@ocd.gov.ph', '209', NULL, NULL, 'approved', NULL, 1, '2026-05-05 08:56:02', '2026-05-05 08:52:22', '2026-05-05 08:56:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `buildings`
--

CREATE TABLE `buildings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buildings`
--

INSERT INTO `buildings` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(8, 'NDRRMC', 1, '2026-05-04 07:04:14', '2026-05-04 07:04:14'),
(9, 'NAB', 1, '2026-05-04 07:05:38', '2026-05-04 07:05:38');

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

CREATE TABLE `divisions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`id`, `name`, `created_at`, `updated_at`) VALUES
(5, 'OFFICE OF CIVIL DEFENSE', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(6, 'OFFICE OF CIVIL DEFENSE DEPUTY FOR OPERATIONS', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(7, 'OFFICE OF CIVIL DEFENSE DEPUTY FOR ADMINISTRATION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(8, 'OFFICE OF CIVIL DEFENSE DEPUTY FOR CIVIL DEFENSE AND STRATEGY MANAGEMENT', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(9, 'OFFICE OF THE HEAD EXECUTIVE ASSISTANT', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(10, 'LEGAL AND LEGISLATIVE OFFICE', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(11, 'INTERNAL MONITORING AND EVALUATION OFFICE', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(12, 'ADMINISTRATIVE SERVICE', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(13, 'HUMAN RESOURCE MANAGEMENT AND DEVELOPMENT DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(14, 'GENERAL SERVICE DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(15, 'PROCUREMENT MANAGEMENT DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(16, 'PLANNING AND FINANCIAL MANAGEMENT SERVICE', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(17, 'BUDGET DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(18, 'ACCOUNTING DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(19, 'PLANNING DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(20, 'INFORMATION AND COMMUNICATION TECHNOLOGY SERVICE', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(21, 'OPERATIONS SERVICE', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(22, 'EARLY WARNING DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(23, 'LOGISTICS MANAGEMENT DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(24, 'RESPONSE DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(25, 'EARLY RECOVERY DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(26, 'ACCREDITATION AND MOBILIZATION DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(27, 'DISASTER RESILIENCE SERVICE', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(28, 'DRRM STANDARDS AND MONITORING DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(29, 'PREVENTION AND MITIGATION DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(30, 'DISASTER PREPAREDNESS SERVICE', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(31, 'DOMESTIC COOPERATION DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(32, 'READINESS DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(33, 'STRATEGIC COMMUNICATION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(34, 'REHABILITATION AND RECOVERY MANAGEMENT SERVICE', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(35, 'POST DISASTER EVALUATION AND MANAGEMENT DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(36, 'DRRM FUND MANAGEMENT DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(37, 'RECOVERY IMPLEMENTATION AND MONITORING DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(38, 'STRATEGY MANAGEMENT SERVICE', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(39, 'INTERNATIONAL COOPERATIONS DIVISION (ICD)', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(40, 'RISK GOVERNANCE AND SPECIAL DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(41, 'CIVIL DEFENSE SERVICE', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(42, 'CIVIL DEFENSE ENHANCEMENT DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(43, 'CIVIL DEFENSE FORCE DEVELOPMENT DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(44, 'CIVIL DEFENSE AND DISASTER MANAGEMENT TRAINING INSTITUTE', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(45, 'CURRICULUM DEVELOPMENT DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(46, 'TRAINING AND EDUCATION DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26'),
(47, 'KNOWLEDGE MANAGEMENT DIVISION', '2026-05-04 06:16:26', '2026-05-04 06:16:26');

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
(1, '2026_04_19_000000_create_buildings_table', 1),
(2, '2026_04_20_025558_create_users_table', 1),
(3, '2026_04_20_025615_create_venues_table', 1),
(4, '2026_04_20_032053_create_bookings_events_table', 1),
(5, '2026_04_20_032054_create_venue_events_tables', 1),
(6, '2026_04_20_033726_create_sessions_table', 1),
(7, '2026_04_20_124206_add_building_to_venues_table', 1),
(8, '2026_04_21_145837_add_email_verified_at_to_users_table', 1),
(9, '2026_04_22_152629_add_last_login_at_to_users_table', 1),
(10, '2026_04_24_153712_add_remarks_to_bookings_table', 1),
(11, '2026_04_28_163334_create_divisions_table', 1),
(12, '2026_04_28_163447_add_division_id_to_users_table', 1),
(13, '2026_04_29_133519_add_building_id_to_venues_table', 2),
(14, '2026_04_30_082955_add_room_floor_to_venues_table', 3),
(15, '2026_04_30_101325_remove_building_from_bookings_table', 4),
(16, '2026_04_30_131854_create_activity_logs_table', 5),
(17, '2026_04_30_132105_add_soft_deletes_to_bookings_table', 5),
(18, '2026_05_04_220501_add_is_approved_to_users_table', 6),
(19, '2026_05_05_142911_add_otp_columns_to_users_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('9TRF9lpAxnBoYBswcdwFcRTrjGSKIpZz5NcTOpe7', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJaWjA3M0ZYRHNDMXlDMWxOMmQ3NkZFb21SUzBnMFdhcm4yckRHTkpMIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9fQ==', 1777971561),
('qb3lmiyHkDtxiRlHpnBgHE5kjjOGC6yt0rM68z54', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJGRklac2QzaTBMbXBFdmFlZ2FRWlhSZGxraE1Nem9FV1IzMFFqd1dJIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9fQ==', 1777971576);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin','super_admin') NOT NULL DEFAULT 'user',
  `contact_number` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `is_approved` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `division_id` bigint(20) UNSIGNED DEFAULT NULL,
  `otp_code` varchar(255) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `contact_number`, `is_active`, `is_approved`, `last_login_at`, `remember_token`, `created_at`, `updated_at`, `division_id`, `otp_code`, `otp_expires_at`) VALUES
(1, 'John Vincent Fabay', 'fabayjohnvincent@gmail.com', '2026-04-29 05:32:44', '$2y$12$RyPk.3ONmXV//l.JyezFBuuZzFNlGVcna/1.RcZh5HlHUpRVDgA.O', 'super_admin', '09683131352', 1, 'approved', NULL, 'quFYclgXTE12eOYUl9CCazBLoPxySySPwBD84ONac1Oe2Mump2xEBHTsd0uS', '2026-04-29 05:32:01', '2026-05-05 08:55:55', 28, NULL, NULL),
(7, 'ICTS admin', 'icts@ocd.gov.ph', '2026-05-04 08:15:23', '$2y$12$FFZE8v5AtwSdvqZuvOeNX.NmAjReR7RB77E9mDAHnB1N1Vo6htr.W', 'admin', '09123154665', 1, 'approved', NULL, NULL, '2026-05-04 07:35:26', '2026-05-05 08:23:23', 20, '521543', '2026-05-05 08:33:23'),
(9, 'Nore Ply', 'noreply@ocd.gov.ph', '2026-05-04 07:47:05', '$2y$12$KpV975pyk7oUMoCRyYqmiedNNskbgT2qsc/x1FC0RReKDv0OScu46', 'super_admin', '09123456788', 1, 'approved', NULL, NULL, '2026-05-04 07:44:16', '2026-05-05 08:33:02', 20, NULL, NULL),
(19, 'Vincent Fabay', 'vincentfabs2001@gmail.com', '2026-05-05 07:15:36', '$2y$12$H8KAZRbbmOPCbPFLGkPtdOGMRXRvRF0dcDSVIqdfGBgCFOZhdYWeK', 'user', NULL, 1, 'approved', NULL, NULL, '2026-05-05 07:15:24', '2026-05-05 07:16:14', 20, NULL, NULL),
(20, 'Ruben T Carandang II', 'ruben.carandangii@ocd.gov.ph', '2026-05-05 08:39:28', '$2y$12$hyiFGbH0fsLyXzES8PGF6uIVV3ACSbUtnbtaXCUjLOEGp9xIfX9JC', 'user', NULL, 1, 'approved', NULL, NULL, '2026-05-05 08:31:39', '2026-05-05 08:44:25', 20, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE `venues` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `building_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `room_floor` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amenities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`amenities`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`id`, `building_id`, `name`, `room_floor`, `color`, `location`, `capacity`, `description`, `amenities`, `is_active`, `created_at`, `updated_at`) VALUES
(7, 8, 'Meeting Room A', '1st Floor', '#6c757d', NULL, NULL, NULL, NULL, 1, '2026-05-04 07:04:44', '2026-05-04 07:04:44'),
(8, 8, 'Main Conference', '3rd Floor', '#6c757d', NULL, NULL, NULL, NULL, 1, '2026-05-04 07:05:00', '2026-05-04 07:05:00'),
(9, 8, 'Meeting Room B', '3rd Floor', '#517da4', NULL, NULL, NULL, NULL, 1, '2026-05-04 07:05:20', '2026-05-04 07:05:20'),
(10, 9, 'Main Conference VIP Multi Media', '7th Floor', '#31af51', NULL, NULL, NULL, NULL, 1, '2026-05-04 07:06:19', '2026-05-04 07:06:19'),
(11, 9, 'Execom Meeting Room', '7th Floor', '#c1bb15', NULL, NULL, NULL, NULL, 1, '2026-05-04 07:06:46', '2026-05-04 07:06:46'),
(12, 9, 'Multi Meeting Room', '8th Floor', '#cc1414', NULL, NULL, NULL, NULL, 1, '2026-05-04 07:07:09', '2026-05-04 07:07:09');

-- --------------------------------------------------------

--
-- Table structure for table `venue_events`
--

CREATE TABLE `venue_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `venue_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `color` varchar(10) NOT NULL DEFAULT '#3788d8',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `venue_events`
--

INSERT INTO `venue_events` (`id`, `venue_id`, `booking_id`, `title`, `description`, `event_date`, `start_time`, `end_time`, `color`, `created_by`, `created_at`, `updated_at`) VALUES
(9, 12, NULL, 'Orientation of VMS', NULL, '2026-05-06', '2026-05-06 11:00:00', '2026-05-06 12:00:00', '#3788d8', 7, '2026-05-05 00:56:04', '2026-05-05 00:56:04'),
(10, 7, NULL, 'Orientation of DMS', NULL, '2026-05-06', '2026-05-06 09:00:00', '2026-05-06 10:00:00', '#3788d8', 7, '2026-05-05 01:04:19', '2026-05-05 01:04:19'),
(11, 7, 24, 'Test 11', NULL, '2026-05-06', '2026-05-06 09:00:00', '2026-05-06 10:00:00', '#3788d8', 1, '2026-05-05 05:46:34', '2026-05-05 05:46:34'),
(12, 11, 23, 'Test 5', NULL, '2026-05-12', '2026-05-12 14:30:00', '2026-05-12 17:00:00', '#3788d8', 1, '2026-05-05 05:54:20', '2026-05-05 05:54:20'),
(13, 10, 22, 'Test 4', NULL, '2026-05-11', '2026-05-11 10:00:00', '2026-05-11 11:30:00', '#3788d8', 1, '2026-05-05 05:57:48', '2026-05-05 05:57:48'),
(15, 9, 25, 'Test 1', NULL, '2026-05-07', '2026-05-07 09:00:00', '2026-05-07 10:00:00', '#3788d8', 1, '2026-05-05 08:56:02', '2026-05-05 08:56:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`),
  ADD KEY `activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_user_id_foreign` (`user_id`),
  ADD KEY `bookings_approved_by_foreign` (`approved_by`),
  ADD KEY `bookings_venue_id_event_date_status_index` (`venue_id`,`event_date`,`status`);

--
-- Indexes for table `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `divisions_name_unique` (`name`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_division_id_foreign` (`division_id`);

--
-- Indexes for table `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venues_building_id_foreign` (`building_id`);

--
-- Indexes for table `venue_events`
--
ALTER TABLE `venue_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venue_events_booking_id_foreign` (`booking_id`),
  ADD KEY `venue_events_created_by_foreign` (`created_by`),
  ADD KEY `venue_events_venue_id_event_date_index` (`venue_id`,`event_date`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `buildings`
--
ALTER TABLE `buildings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `divisions`
--
ALTER TABLE `divisions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `venue_events`
--
ALTER TABLE `venue_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `venues`
--
ALTER TABLE `venues`
  ADD CONSTRAINT `venues_building_id_foreign` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `venue_events`
--
ALTER TABLE `venue_events`
  ADD CONSTRAINT `venue_events_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `venue_events_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `venue_events_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
