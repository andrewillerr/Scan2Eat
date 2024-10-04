-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2024 at 06:57 AM
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
-- Database: `scan2eat`
--

-- --------------------------------------------------------

--
-- Table structure for table `confirmations`
--

CREATE TABLE `confirmations` (
  `confirmation_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `table_number` int(11) NOT NULL,
  `confirmed_by` varchar(255) NOT NULL,
  `confirmation_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_price` decimal(10,2) NOT NULL,
  `category` enum('Isaan','Thai','Drink') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`item_id`, `item_name`, `item_price`, `category`) VALUES
(1, 'ส้มตำ', 50.00, 'Isaan'),
(2, 'ลาบหมู', 60.00, 'Isaan'),
(3, 'ไก่ย่าง', 100.00, 'Isaan'),
(4, 'หมูย่าง', 120.00, 'Isaan'),
(5, 'ต้มยำกุ้ง', 150.00, 'Thai'),
(6, 'ผัดไทย', 80.00, 'Thai'),
(7, 'ข้าวผัด', 60.00, 'Thai'),
(8, 'แกงเขียวหวาน', 100.00, 'Thai'),
(9, 'แกงมัสมั่น', 120.00, 'Thai'),
(10, 'ผัดกระเพรา', 90.00, 'Thai'),
(11, 'ยำวุ้นเส้น', 80.00, 'Isaan'),
(12, 'หมูทอด', 100.00, 'Isaan'),
(13, 'ยำเนื้อ', 110.00, 'Isaan'),
(14, 'น้ำตกหมู', 110.00, 'Isaan'),
(15, 'ไก่ผัดพริกขี้หนู', 120.00, 'Thai'),
(16, 'ผัดกะเพราหมู', 90.00, 'Thai'),
(17, 'ต้มข่าไก่', 120.00, 'Thai'),
(18, 'ไก่ผัดเม็ดมะม่วง', 130.00, 'Thai'),
(19, 'ข้าวซอย', 140.00, 'Thai'),
(20, 'ปลาหมึกย่าง', 150.00, 'Isaan'),
(21, 'ยำปลาดุกฟู', 130.00, 'Isaan'),
(22, 'ข้าวหมูแดง', 80.00, 'Thai'),
(23, 'หมูกระทะ', 150.00, 'Thai'),
(24, 'ผัดซีอิ๊ว', 90.00, 'Thai'),
(25, 'ยำทะเล', 140.00, 'Isaan'),
(26, 'ลาบไก่', 75.00, 'Isaan'),
(27, 'ส้มตำปูปลาร้า', 65.00, 'Isaan'),
(28, 'ยำมะม่วง', 70.00, 'Isaan'),
(29, 'ผัดกระเพราหมู', 85.00, 'Thai'),
(30, 'หมูผัดพริกไทยดำ', 95.00, 'Thai'),
(31, 'ขนมจีนน้ำยา', 120.00, 'Thai'),
(32, 'ต้มยำน้ำข้น', 100.00, 'Isaan'),
(33, 'ผัดน้ำพริกอ่อง', 110.00, 'Isaan'),
(34, 'ปลานึ่งมะนาว', 160.00, 'Thai'),
(35, 'ข้าวผัดไก่', 75.00, 'Thai'),
(36, 'ชาเย็น', 30.00, 'Drink'),
(37, 'ชามะนาว', 40.00, 'Drink'),
(38, 'น้ำมะพร้าว', 35.00, 'Drink'),
(39, 'น้ำมะนาว', 25.00, 'Drink'),
(40, 'กาแฟไทย', 45.00, 'Drink'),
(41, 'มะพร้าวปั่น', 50.00, 'Drink'),
(42, 'กาแฟดำ', 35.00, 'Drink'),
(43, 'ชานม', 45.00, 'Drink'),
(44, 'สมูทตี้ผลไม้', 55.00, 'Drink'),
(45, 'น้ำส้ม', 40.00, 'Drink');

-- --------------------------------------------------------

--
-- Table structure for table `ordered_items`
--

CREATE TABLE `ordered_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ordered_items`
--

INSERT INTO `ordered_items` (`order_item_id`, `order_id`, `item_id`, `quantity`) VALUES
(1, 1, 5, 1),
(2, 2, 5, 1),
(3, 2, 2, 2),
(4, 3, 5, 1),
(5, 3, 2, 2),
(6, 4, 2, 1),
(7, 5, 2, 1),
(8, 6, 7, 2),
(9, 7, 1, 2),
(10, 8, 3, 2),
(11, 9, 3, 1),
(44, 40, 3, 1),
(45, 40, 11, 1),
(60, 46, 2, 1),
(61, 46, 3, 1),
(62, 46, 4, 1),
(81, 72, 11, 1),
(82, 74, 1, 1),
(83, 74, 32, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `table_number` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','paid') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `table_number`, `total_price`, `order_date`, `status`) VALUES
(1, 2, 150.00, '2024-10-02 17:27:25', 'paid'),
(2, 2, 270.00, '2024-10-02 17:28:09', 'paid'),
(3, 2, 270.00, '2024-10-02 17:37:43', 'paid'),
(4, 1, 60.00, '2024-10-02 17:38:04', 'paid'),
(5, 2, 60.00, '2024-10-02 17:38:31', 'paid'),
(6, 1, 120.00, '2024-10-02 18:33:04', 'paid'),
(7, 10, 100.00, '2024-10-02 19:03:15', 'paid'),
(8, 4, 200.00, '2024-10-02 19:25:42', 'paid'),
(9, 4, 100.00, '2024-10-02 19:32:37', 'paid'),
(40, 1, 180.00, '2024-10-03 13:45:51', 'paid'),
(41, 1, 180.00, '2024-10-03 13:45:51', 'paid'),
(43, 1, 1060.00, '2024-10-03 13:46:59', 'paid'),
(44, 1, 120.00, '2024-10-03 14:34:03', 'paid'),
(45, 1, 120.00, '2024-10-03 14:34:03', 'paid'),
(46, 1, 280.00, '2024-10-03 14:46:05', 'paid'),
(47, 1, 280.00, '2024-10-03 14:46:05', 'paid'),
(48, 2, 180.00, '2024-10-03 16:05:52', 'paid'),
(49, 2, 180.00, '2024-10-03 16:05:52', 'paid'),
(71, 2, 60.00, '2024-10-04 04:19:03', 'paid'),
(72, 5, 80.00, '2024-10-04 04:30:55', 'paid'),
(73, 5, 80.00, '2024-10-04 04:30:55', 'paid'),
(74, 6, 150.00, '2024-10-04 04:33:25', 'paid'),
(75, 6, 150.00, '2024-10-04 04:33:25', 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','completed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `table_id` int(11) NOT NULL,
  `table_number` int(11) NOT NULL,
  `status` enum('available','not available') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`table_id`, `table_number`, `status`) VALUES
(1, 1, 'available'),
(2, 2, 'available'),
(3, 3, 'available'),
(4, 4, 'available'),
(5, 5, 'available'),
(6, 6, 'available'),
(7, 7, 'available'),
(8, 8, 'available'),
(9, 9, 'available'),
(10, 10, 'available');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `confirmations`
--
ALTER TABLE `confirmations`
  ADD PRIMARY KEY (`confirmation_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `ordered_items`
--
ALTER TABLE `ordered_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `table_number` (`table_number`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`table_id`),
  ADD UNIQUE KEY `table_number` (`table_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `confirmations`
--
ALTER TABLE `confirmations`
  MODIFY `confirmation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `ordered_items`
--
ALTER TABLE `ordered_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `table_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `confirmations`
--
ALTER TABLE `confirmations`
  ADD CONSTRAINT `confirmations_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `ordered_items`
--
ALTER TABLE `ordered_items`
  ADD CONSTRAINT `ordered_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `ordered_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `menu` (`item_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`table_number`) REFERENCES `tables` (`table_number`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
