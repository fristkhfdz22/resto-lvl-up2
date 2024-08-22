-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 21, 2024 at 09:53 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restaurant`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`) VALUES
(1, 'khfdz', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `description`, `price`, `image`) VALUES
(2, 'mmm', '224', '22.00', 'dinner-min.jpg'),
(3, 'ff22', '22', '222.00', 'lunch-min.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `total_price`, `order_date`) VALUES
(1, 'John Doe', '32.00', '2024-08-21 08:44:05'),
(4, 'DINAS SOSIAL', '40.00', '2024-08-21 08:56:42'),
(7, 'eee', '176.00', '2024-08-21 09:01:17'),
(8, 'www', '0.00', '2024-08-21 09:04:39'),
(9, 'www', '0.00', '2024-08-21 09:08:14'),
(10, 'www', '0.00', '2024-08-21 09:08:20'),
(11, 'ddd', '0.00', '2024-08-21 09:33:14'),
(12, 'ddd', '0.00', '2024-08-21 09:35:01'),
(13, 'eee', '0.00', '2024-08-21 09:35:12'),
(14, 'eee', '0.00', '2024-08-21 09:35:39'),
(15, 'eee', '0.00', '2024-08-21 09:35:44'),
(16, 'eee', '0.00', '2024-08-21 09:36:22'),
(17, 'eee', '0.00', '2024-08-21 09:39:39'),
(18, 'eee', '0.00', '2024-08-21 09:39:43'),
(19, 'tt', '0.00', '2024-08-21 09:40:01'),
(20, 'DINAS SOSIAL', '0.00', '2024-08-21 09:41:42'),
(21, 'eee', '88.00', '2024-08-21 09:44:11'),
(22, 'eee', '754.00', '2024-08-21 09:44:18'),
(23, 'dd', '976.00', '2024-08-21 09:45:25');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `dish_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price_per_item` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `dish_id`, `quantity`, `price_per_item`, `total`) VALUES
(1, 1, 1, 2, '10.00', '20.00'),
(2, 1, 2, 1, '12.00', '12.00'),
(6, 4, 1, 4, '10.00', '40.00'),
(9, 7, 1, 4, '44.00', '176.00'),
(10, 21, 2, 4, '22.00', '88.00'),
(11, 22, 2, 4, '22.00', '88.00'),
(12, 22, 3, 3, '222.00', '666.00'),
(13, 23, 2, 4, '22.00', '88.00'),
(14, 23, 3, 4, '222.00', '888.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
