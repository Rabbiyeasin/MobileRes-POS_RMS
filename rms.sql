-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2024 at 08:14 PM
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
-- Database: `rms`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `Cart_ID` int(11) NOT NULL,
  `cart_code` int(255) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Product_ID` int(11) NOT NULL,
  `Unit_Price` decimal(10,2) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `discount_percent` int(3) NOT NULL,
  `Total_Price` decimal(10,2) NOT NULL,
  `validity` int(255) NOT NULL,
  `added_at` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`Cart_ID`, `cart_code`, `User_ID`, `Product_ID`, `Unit_Price`, `Quantity`, `discount_percent`, `Total_Price`, `validity`, `added_at`) VALUES
(109, 0, 2, 1, 750.00, 1, 0, 750.00, 1, 1727969513);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `unit` varchar(100) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_id`, `item_name`, `unit`, `price`) VALUES
(1, 'Chola Batora', '200', 750),
(2, 'Dal Masala', '130', 170),
(3, 'Chicken Chola Batora', '1:1', 260),
(7, 'Pudina Dosa', '200', 230),
(12, 'Dohi Fuska', '130', 100),
(983, 'Cold Coffee', '120', 150);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `cart_code` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `phone` varchar(14) NOT NULL,
  `cash` int(255) NOT NULL,
  `mfs` int(255) NOT NULL,
  `changeAmount` int(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`cart_code`, `customer_name`, `phone`, `cash`, `mfs`, `changeAmount`, `user_id`, `created_at`) VALUES
(14, 'Tawsif', '000', 990, 0, 8, 2, 1727544126),
(15, 'Akiz', '000', 1280, 1, 1, 2, 1727545266),
(16, 'test', '01953136456', 2300, 1, 21, 2, 1727552091),
(17, 'twzt', '0199999999', 400, 1, 31, 2, 1727794787),
(18, 'test 2 ', '9999999', 1000, 1, 81, 2, 1727795417),
(19, 'test', '99999', 800, 1, 51, 2, 1727795656),
(20, 'Abir', '01862930437', 1500, 1, 1, 2, 1727796614),
(21, 'test', '0199999999', 150, 1, 51, 2, 1727797372),
(22, 'twzt', '0199999999', 800, 1, 51, 2, 1727797728);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_id` int(255) NOT NULL,
  `item_id` int(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  `item_price` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `Product_ID` int(11) NOT NULL,
  `Product_Name` varchar(100) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`) VALUES
(1, 'Admin', 'admin1234', 'admin'),
(2, 'Sutrapur_2', 'yeasin', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`Cart_ID`),
  ADD KEY `User_ID` (`User_ID`),
  ADD KEY `Product_ID` (`Product_ID`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`cart_code`),
  ADD KEY `User_ID` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`Product_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `Cart_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=984;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `cart_code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `Product_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
