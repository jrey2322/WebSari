-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2026 at 02:46 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `websari`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `module` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `activity`, `module`, `details`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 7, 'Restocked product: Century Tuna', 'INVENTORY', 'Added 25 can to existing stock. New total: 25.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36', '2026-05-07 06:27:02', '2026-05-07 06:27:02'),
(2, 7, 'Restocked product: Camel Red', 'INVENTORY', 'Added 20 pack to existing stock. New total: 20.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36', '2026-05-07 07:47:08', '2026-05-07 07:47:08'),
(3, 7, 'Restocked product: C2 Green Tea', 'INVENTORY', 'Added 10 bottle to existing stock. New total: 33.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36', '2026-05-14 09:40:11', '2026-05-14 09:40:11'),
(4, 9, 'Completed sale: WS-20260514-0005', 'SALES', 'Customer: Walk-in Customer, Total: ₱152.00, Method: CASH', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36', '2026-05-14 09:54:12', '2026-05-14 09:54:12'),
(5, 9, 'Completed sale: WS-20260514-0006', 'SALES', 'Customer: Walk-in Customer, Total: ₱54.00, Method: UTANG', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36', '2026-05-14 09:54:21', '2026-05-14 09:54:21'),
(6, 9, 'Recorded payment: WS-20260514-0006', 'UTANG', 'Customer: Walk-in Customer, Amount: ₱50.00, Remaining: ₱4.00', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36', '2026-05-14 09:54:28', '2026-05-14 09:54:28');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Beverages', 'Softdrinks, juices, water', '2026-05-05 03:43:13', '2026-05-05 03:43:13'),
(2, 'Snacks', 'Chips, crackers, candy', '2026-05-05 03:43:13', '2026-05-05 03:43:13'),
(3, 'Canned Goods', 'Sardines, corned beef, tuna', '2026-05-05 03:43:13', '2026-05-05 03:43:13'),
(4, 'Condiments', 'Soy sauce, vinegar, cooking oil', '2026-05-05 03:43:13', '2026-05-05 03:43:13'),
(5, 'Personal Care', 'Shampoo, soap, toothpaste', '2026-05-05 03:43:13', '2026-05-05 03:43:13'),
(7, 'Cigarettes', 'Marlboro, Philip Morris, Fortune', '2026-05-05 03:43:13', '2026-05-05 03:43:13'),
(8, 'Bread & Pastry', 'Pandesal, loaf bread', '2026-05-05 03:43:13', '2026-05-05 03:43:13');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cost_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `low_stock_alert` int(11) DEFAULT 5,
  `unit` varchar(30) DEFAULT 'pcs',
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `barcode`, `price`, `cost_price`, `stock`, `low_stock_alert`, `unit`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Coke Mismo (250ml)', NULL, 'CK001', 18.00, 12.00, 25, 5, 'bottle', NULL, 'active', '2026-05-05 03:43:13', '2026-05-14 01:54:21'),
(2, 1, 'Royal (500ml)', NULL, 'RY001', 28.00, 20.00, 29, 5, 'bottle', NULL, 'active', '2026-05-05 03:43:13', '2026-05-06 20:45:31'),
(3, 1, 'Mineral Water (500ml)', NULL, 'MW001', 12.00, 7.00, 76, 5, 'bottle', NULL, 'active', '2026-05-05 03:43:13', '2026-05-06 20:45:31'),
(4, 1, 'C2 Green Tea', '', 'C2001', 22.00, 15.00, 33, 5, 'bottle', NULL, 'active', '2026-05-05 03:43:13', '2026-05-14 01:40:55'),
(5, 2, 'Piattos', NULL, 'PT001', 20.00, 14.00, 57, 5, 'pack', NULL, 'active', '2026-05-05 03:43:13', '2026-05-06 20:45:31'),
(6, 2, 'Oishi Prawn Crackers', NULL, 'OC001', 15.00, 10.00, 43, 5, 'pack', NULL, 'active', '2026-05-05 03:43:13', '2026-05-06 20:45:31'),
(7, 2, 'Sky Flakes', NULL, 'SF001', 10.00, 6.00, 99, 5, 'pack', NULL, 'active', '2026-05-05 03:43:13', '2026-05-06 20:45:31'),
(8, 3, 'Century Tuna', NULL, 'CT001', 38.00, 28.00, 15, 5, 'can', NULL, 'active', '2026-05-05 03:43:13', '2026-05-14 02:16:19'),
(9, 3, 'Ligo Sardines', NULL, 'LS001', 22.00, 15.00, 32, 5, 'can', NULL, 'active', '2026-05-05 03:43:13', '2026-05-14 01:48:49'),
(10, 4, 'Datu Puti Toyo', NULL, 'DP001', 15.00, 10.00, 29, 5, 'sachet', NULL, 'active', '2026-05-05 03:43:13', '2026-05-06 20:45:31'),
(11, 5, 'Head & Shoulders', NULL, 'HS001', 10.00, 6.00, 47, 5, 'sachet', NULL, 'active', '2026-05-05 03:43:13', '2026-05-06 23:44:56'),
(12, 7, 'Camel Red', '', 'MB001', 160.00, 130.00, 21, 5, 'pack', '1777957092_7606b7efe908939d14d9.png', 'active', '2026-05-05 03:43:13', '2026-05-14 01:34:05'),
(13, 8, 'Gardenia Loaf', NULL, 'GL001', 65.00, 50.00, 13, 5, 'loaf', NULL, 'active', '2026-05-05 03:43:13', '2026-05-06 23:44:56'),
(14, NULL, 'Infinix GT30 Pro', '', '', 15000.00, 13000.00, 10, 1, 'pcs', NULL, 'inactive', '2026-05-04 20:51:17', '2026-05-04 20:52:35'),
(15, 1, 'Pepsi', '', '0231', 20.00, 10.00, 41, 2, 'bottle', '1778127971_192febb995fcfc2aca9f.jpg', 'active', '2026-05-06 20:26:11', '2026-05-14 01:22:21');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `invoice_no` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT 'Walk-in',
  `subtotal` decimal(10,2) DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `total` decimal(10,2) DEFAULT 0.00,
  `amount_paid` decimal(10,2) DEFAULT 0.00,
  `change_amount` decimal(10,2) DEFAULT 0.00,
  `paid_amount` decimal(10,2) DEFAULT 0.00,
  `balance` decimal(10,2) DEFAULT 0.00,
  `payment_method` enum('cash','gcash','utang') DEFAULT 'cash',
  `status` enum('completed','void','utang') DEFAULT 'completed',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `invoice_no`, `user_id`, `customer_name`, `subtotal`, `discount`, `total`, `amount_paid`, `change_amount`, `paid_amount`, `balance`, `payment_method`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'WS-20260505-0001', 1, 'Walk-in Customer', 132.00, 0.00, 132.00, 1000.00, 868.00, 0.00, 0.00, 'cash', 'completed', '', '2026-05-04 21:52:57', '2026-05-04 21:52:57'),
(2, 'WS-20260505-0002', 1, 'robert', 160.00, 0.00, 160.00, 160.00, 0.00, 0.00, 160.00, 'utang', 'completed', '', '2026-05-04 21:54:01', '2026-05-06 22:33:51'),
(3, 'WS-20260505-0003', 1, 'Walk-in Customer', 220.00, 0.00, 220.00, 2000.00, 1780.00, 0.00, 0.00, 'gcash', 'completed', '', '2026-05-04 21:55:02', '2026-05-04 21:55:02'),
(4, 'WS-20260505-0004', 1, 'Walk-in Customer', 480.00, 0.00, 480.00, 480.00, 0.00, 0.00, 0.00, 'utang', 'completed', '', '2026-05-04 22:20:00', '2026-05-06 16:18:03'),
(5, 'WS-20260507-0001', 1, 'Osccar', 114.00, 0.00, 114.00, 114.00, 0.00, 0.00, 0.00, 'utang', 'completed', '', '2026-05-06 16:17:51', '2026-05-06 16:43:41'),
(6, 'WS-20260507-0002', 1, 'Walk-in Customer', 190.00, 0.00, 190.00, 190.00, 0.00, 0.00, 0.00, 'utang', 'completed', '', '2026-05-06 16:43:49', '2026-05-06 16:46:14'),
(7, 'WS-20260507-0003', 1, 'Walk-in Customer', 114.00, 0.00, 114.00, 114.00, 0.00, 0.00, 0.00, 'utang', 'completed', '', '2026-05-06 16:48:10', '2026-05-06 16:58:51'),
(8, 'WS-20260507-0004', 1, 'Walk-in Customer', 114.00, 0.00, 114.00, 114.00, 0.00, 0.00, 0.00, 'utang', 'completed', '', '2026-05-06 16:59:01', '2026-05-06 20:12:01'),
(9, 'WS-20260507-0005', 6, 'Walk-in Customer', 72.00, 0.00, 72.00, 100.00, 28.00, 0.00, 0.00, 'gcash', 'completed', '', '2026-05-06 20:11:43', '2026-05-06 20:11:43'),
(17, 'WS-20260507-0006', 7, 'Walk-in Customer', 80.00, 0.00, 80.00, 120.00, 40.00, 0.00, 0.00, 'cash', 'completed', '', '2026-05-06 20:27:15', '2026-05-06 20:27:15'),
(18, 'WS-20260507-0007', 7, 'Walk-in Customer', 54.00, 0.00, 54.00, 121.00, 67.00, 0.00, 0.00, 'cash', 'completed', '', '2026-05-06 20:38:21', '2026-05-06 20:38:21'),
(19, 'WS-20260507-0008', 7, 'Walk-in Customer', 72.00, 0.00, 72.00, 121.00, 49.00, 0.00, 0.00, 'cash', 'completed', '', '2026-05-06 20:38:34', '2026-05-06 20:38:34'),
(20, 'WS-20260507-0009', 7, 'Walk-in Customer', 18.00, 0.00, 18.00, 232.00, 214.00, 0.00, 0.00, 'cash', 'completed', '', '2026-05-06 20:40:05', '2026-05-06 20:40:05'),
(21, 'WS-20260507-0010', 7, 'Walk-in Customer', 1116.00, 0.00, 1116.00, 1500.00, 384.00, 0.00, 0.00, 'cash', 'completed', '', '2026-05-06 20:45:31', '2026-05-06 20:45:31'),
(22, 'WS-20260507-0011', 7, 'robert', 54.00, 0.00, 54.00, 54.00, 0.00, 0.00, 0.00, 'utang', 'completed', '', '2026-05-06 20:46:15', '2026-05-06 22:36:54'),
(23, 'WS-20260507-0012', 7, 'Walk-in Customer', 44.00, 0.00, 44.00, 44.00, 0.00, 0.00, 0.00, 'utang', 'completed', '', '2026-05-06 20:46:59', '2026-05-06 22:41:07'),
(24, 'WS-20260507-0013', 7, 'Walk-in Customer', 304.00, 0.00, 304.00, 304.00, 0.00, 0.00, 0.00, 'utang', 'completed', '', '2026-05-06 22:01:31', '2026-05-06 23:43:16'),
(25, 'WS-20260507-0014', 7, 'Walk-in Customer', 304.00, 0.00, 304.00, 500.00, 196.00, 0.00, 0.00, 'cash', 'completed', '', '2026-05-06 22:03:19', '2026-05-06 22:03:19'),
(26, 'WS-20260507-0015', 7, 'Walk-in Customer', 800.00, 0.00, 800.00, 800.00, 0.00, 0.00, 0.00, 'utang', 'completed', '', '2026-05-06 22:03:35', '2026-05-06 22:29:32'),
(27, 'WS-20260507-0016', 7, 'Walk-in Customer', 640.00, 0.00, 640.00, 640.00, 0.00, 0.00, 0.00, 'utang', 'completed', '', '2026-05-06 22:07:55', '2026-05-06 22:36:50'),
(28, 'WS-20260507-0017', 7, 'JAKE', 18.00, 0.00, 18.00, 18.00, 0.00, 0.00, 0.00, 'utang', 'completed', '', '2026-05-06 22:41:50', '2026-05-06 23:42:58'),
(29, 'WS-20260507-0018', 8, 'Walk-in Customer', 229.00, 0.00, 229.00, 500.00, 271.00, 0.00, 0.00, 'cash', 'completed', '', '2026-05-06 23:44:56', '2026-05-06 23:44:56'),
(30, 'WS-20260507-0019', 8, 'Joshua', 36.00, 0.00, 36.00, 36.00, 0.00, 0.00, 0.00, 'utang', 'completed', '', '2026-05-06 23:45:19', '2026-05-06 23:45:42'),
(31, 'WS-20260507-0020', 8, 'Walk-in Customer', 1440.00, 0.00, 1440.00, 2000.00, 560.00, 0.00, 0.00, 'cash', 'void', '', '2026-05-06 23:46:17', '2026-05-14 01:17:40'),
(32, 'WS-20260514-0001', 7, 'Walk-in Customer', 40.00, 0.00, 40.00, 200.00, 160.00, 0.00, 0.00, 'cash', 'completed', '', '2026-05-14 01:22:21', '2026-05-14 01:22:21'),
(33, 'WS-20260514-0002', 7, 'Walk-in Customer', 160.00, 0.00, 160.00, 160.00, 0.00, 0.00, 0.00, 'utang', 'completed', '', '2026-05-14 01:23:14', '2026-05-14 01:35:03'),
(34, 'WS-20260514-0003', 9, 'Jasper', 1120.00, 0.00, 1120.00, 2000.00, 880.00, 0.00, 0.00, 'cash', 'completed', '', '2026-05-14 01:34:05', '2026-05-14 01:34:05'),
(35, 'WS-20260514-0004', 9, 'Walk-in Customer', 44.00, 0.00, 44.00, 1234.00, 1190.00, 0.00, 0.00, 'cash', 'completed', '', '2026-05-14 01:48:49', '2026-05-14 01:48:49'),
(36, 'WS-20260514-0005', 9, 'Walk-in Customer', 152.00, 0.00, 152.00, 500.00, 348.00, 0.00, 0.00, 'cash', 'completed', '', '2026-05-14 01:54:12', '2026-05-14 01:54:12'),
(37, 'WS-20260514-0006', 9, 'Walk-in Customer', 54.00, 0.00, 54.00, 54.00, 0.00, 0.00, 0.00, 'utang', 'completed', '', '2026-05-14 01:54:21', '2026-05-14 02:16:47'),
(38, 'WS-20260514-0007', 7, 'Walk-in Customer', 114.00, 0.00, 114.00, 114.00, 0.00, 0.00, 0.00, 'utang', 'completed', '', '2026-05-14 02:16:19', '2026-05-14 02:16:43');

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `quantity`, `price`, `subtotal`) VALUES
(1, 1, 4, 6, 22.00, 132.00),
(2, 2, 12, 1, 160.00, 160.00),
(3, 3, 4, 10, 22.00, 220.00),
(4, 4, 12, 3, 160.00, 480.00),
(5, 5, 8, 3, 38.00, 114.00),
(6, 6, 8, 5, 38.00, 190.00),
(7, 7, 8, 3, 38.00, 114.00),
(8, 8, 8, 3, 38.00, 114.00),
(9, 9, 1, 4, 18.00, 72.00),
(10, 17, 15, 4, 20.00, 80.00),
(11, 18, 1, 3, 18.00, 54.00),
(12, 19, 1, 4, 18.00, 72.00),
(13, 20, 1, 1, 18.00, 18.00),
(14, 21, 1, 3, 18.00, 54.00),
(15, 21, 9, 5, 22.00, 110.00),
(16, 21, 11, 2, 10.00, 20.00),
(17, 21, 12, 3, 160.00, 480.00),
(18, 21, 13, 1, 65.00, 65.00),
(19, 21, 4, 1, 22.00, 22.00),
(20, 21, 10, 1, 15.00, 15.00),
(21, 21, 6, 2, 15.00, 30.00),
(22, 21, 3, 4, 12.00, 48.00),
(23, 21, 15, 3, 20.00, 60.00),
(24, 21, 8, 3, 38.00, 114.00),
(25, 21, 7, 1, 10.00, 10.00),
(26, 21, 2, 1, 28.00, 28.00),
(27, 21, 5, 3, 20.00, 60.00),
(28, 22, 1, 3, 18.00, 54.00),
(29, 23, 4, 2, 22.00, 44.00),
(30, 24, 8, 8, 38.00, 304.00),
(31, 25, 8, 8, 38.00, 304.00),
(32, 26, 12, 5, 160.00, 800.00),
(33, 27, 12, 4, 160.00, 640.00),
(34, 28, 1, 1, 18.00, 18.00),
(35, 29, 8, 3, 38.00, 114.00),
(36, 29, 1, 1, 18.00, 18.00),
(37, 29, 9, 1, 22.00, 22.00),
(38, 29, 11, 1, 10.00, 10.00),
(39, 29, 13, 1, 65.00, 65.00),
(40, 30, 1, 2, 18.00, 36.00),
(41, 31, 12, 9, 160.00, 1440.00),
(42, 32, 15, 2, 20.00, 40.00),
(43, 33, 12, 1, 160.00, 160.00),
(44, 34, 12, 7, 160.00, 1120.00),
(45, 35, 9, 2, 22.00, 44.00),
(46, 36, 8, 4, 38.00, 152.00),
(47, 37, 1, 3, 18.00, 54.00),
(48, 38, 8, 3, 38.00, 114.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('owner','staff') DEFAULT 'staff',
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone`, `address`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Store Owner', 'owner@websari.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'owner', '09171234567', NULL, 'active', '2026-05-05 03:43:13', '2026-05-05 03:43:13'),
(2, 'Juan Staff', 'staff@websari.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff', '09281234567', NULL, 'active', '2026-05-05 03:43:13', '2026-05-05 03:43:13'),
(3, 'Juans Sewtu', 'setsu@websari.com', '$2y$10$0561.ZFAcnY9MXTi3zrmw.zmYs2XKLORCWv.fXFBD.4qQK2P3Anvi', 'staff', '09123456789', NULL, 'active', '2026-05-04 20:39:25', '2026-05-04 20:39:25'),
(4, 'jose Rey Grandea', 'jrey2322@gmail.com', '$2y$10$PXzjaATus/LFvtnvTtSOTOKKl2LAwNxlfjsuIa1y43KGnNTdnOIyu', 'staff', '09947957213', NULL, 'active', '2026-05-04 20:42:55', '2026-05-04 20:42:55'),
(5, 'Harvey', 'harvey@websari.com', '$2y$10$ntyxKaLugaK6F098vDATJuyrqzdQDErfpNbpy73kCmB6/Nh1z33Eq', 'staff', '09912345678', NULL, 'active', '2026-05-04 20:44:02', '2026-05-04 20:44:02'),
(6, 'Jobert Alcantara', 'jobert@websari.com', '$2y$10$6oc52bSkf9Qbd//ecI8K1u.5lXOR99SrcmT10UEYEM2RmbooYfPbu', 'staff', '09123456789', NULL, 'active', '2026-05-06 20:11:08', '2026-05-06 20:11:08'),
(7, 'System Admin', 'admin@websari.com', '$2y$10$ZVZsXMYT0aHqFwjnKYHy4egwd.yfdkkTLsWU7GsOgVLuRGJDGSdsC', 'owner', NULL, NULL, 'active', '2026-05-06 20:27:01', '2026-05-06 20:27:01'),
(8, 'Pepsi', 'pepsi@websari.com', '$2y$10$OSQHz7qMmD.aEBNrZvgCAey0gwX5QBybDSj620Yuuek556yCNyV6.', 'staff', '09123456789', NULL, 'active', '2026-05-06 23:44:11', '2026-05-06 23:44:11'),
(9, 'erwin', 'staff2@websari.com', '$2y$10$bP088w9yJu4G7RpjcPluWusNTkw3kPxHLQ6RDNdt8GmONA68YQy3m', 'staff', '0912345678', NULL, 'active', '2026-05-14 01:32:42', '2026-05-14 01:32:42');

-- --------------------------------------------------------

--
-- Table structure for table `utang_payments`
--

CREATE TABLE `utang_payments` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `recorded_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utang_payments`
--

INSERT INTO `utang_payments` (`id`, `sale_id`, `amount`, `notes`, `recorded_by`, `created_at`) VALUES
(1, 8, 50.00, '', 1, '2026-05-06 19:56:34'),
(2, 8, 23.00, '', 1, '2026-05-06 19:56:54'),
(3, 8, 41.00, '', 6, '2026-05-06 20:12:01'),
(4, 22, 50.00, '', 7, '2026-05-06 20:46:35'),
(5, 24, 50.00, '', 7, '2026-05-06 22:40:44'),
(6, 23, 44.00, '', 7, '2026-05-06 22:41:07'),
(7, 28, 10.00, '', 7, '2026-05-06 23:42:39'),
(8, 28, 8.00, '', 7, '2026-05-06 23:42:58'),
(9, 24, 254.00, 'Full payment (Manual Override)', 7, '2026-05-06 23:43:16'),
(10, 30, 20.00, '', 8, '2026-05-06 23:45:34'),
(11, 30, 16.00, '', 8, '2026-05-06 23:45:42'),
(12, 33, 100.00, '', 7, '2026-05-14 01:24:18'),
(13, 33, 60.00, '', 9, '2026-05-14 01:35:03'),
(14, 37, 50.00, '', 9, '2026-05-14 01:54:28'),
(15, 38, 114.00, 'Full payment (Manual Override)', 7, '2026-05-14 02:16:43'),
(16, 37, 4.00, '', 7, '2026-05-14 02:16:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_no` (`invoice_no`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `utang_payments`
--
ALTER TABLE `utang_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `recorded_by` (`recorded_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `utang_payments`
--
ALTER TABLE `utang_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `utang_payments`
--
ALTER TABLE `utang_payments`
  ADD CONSTRAINT `utang_payments_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `utang_payments_ibfk_2` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
