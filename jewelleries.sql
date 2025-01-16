-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2025 at 06:40 AM
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
-- Database: `jewelleries`
--

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `id` int(5) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `category_id` int(255) NOT NULL,
  `category_type_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`id`, `brand`, `status`, `category_id`, `category_type_id`) VALUES
(30, 'Gold Necklaces', 1, 16, 16),
(31, 'Gold Earrings', 1, 16, 13),
(32, 'Earrings', 1, 16, 16),
(33, 'Dolor exercitationem', 1, 18, 18);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(5) NOT NULL,
  `category` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category`, `type`, `status`) VALUES
(13, 'Women', 'Necklaces', 1),
(16, 'KIDS', 'Necklaces', 1),
(18, 'Fugiat veniam do a', 'Illo ipsum impedit ', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `gallery_name` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `gallery_name`, `image_path`, `created_at`) VALUES
(1, '2', '../uploads/car1.jpeg', '2024-11-23 09:46:17');

-- --------------------------------------------------------

--
-- Table structure for table `information`
--

CREATE TABLE `information` (
  `id` int(11) NOT NULL,
  `header_logo` varchar(255) DEFAULT NULL,
  `footer_logo` varchar(255) DEFAULT NULL,
  `mobile_no` varchar(20) DEFAULT NULL,
  `location` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `twitter_link` varchar(255) DEFAULT NULL,
  `facebook_link` varchar(255) DEFAULT NULL,
  `instagram_link` varchar(255) DEFAULT NULL,
  `linkedin_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `information`
--

INSERT INTO `information` (`id`, `header_logo`, `footer_logo`, `mobile_no`, `location`, `email`, `twitter_link`, `facebook_link`, `instagram_link`, `linkedin_link`, `created_at`, `updated_at`) VALUES
(3, 'logo-removebg-preview.png', 'logo-removebg-preview.png', '7000479276', 'Sadar Bazar , Bhind ( M P )', 'parasjn007@gmail.com', '#', '#', '#', '##', '2024-11-19 05:06:00', '2024-12-28 11:41:40');

-- --------------------------------------------------------

--
-- Table structure for table `offer`
--

CREATE TABLE `offer` (
  `id` int(5) NOT NULL,
  `offer_price` varchar(255) NOT NULL,
  `image_paths` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offer`
--

INSERT INTO `offer` (`id`, `offer_price`, `image_paths`, `created_at`, `status`) VALUES
(14, '50', '../uploads/banner-one.png', '2024-12-30 06:20:42', 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `discount_price` decimal(10,2) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `offer_price` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_paths` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `product_price`, `discount_price`, `brand`, `type`, `offer_price`, `category`, `description`, `image_paths`, `status`) VALUES
(185, 'Conan Thomas', 281.00, 607.00, '30', '16', '14', '13', 'Rerum cum lorem dolo', '../uploads/img4.jpeg', 1),
(187, 'Zachery Abbott', 498.00, 904.00, '30', '13', '14', '16', 'Ad aperiam quam veli', '../uploads/img4.jpeg', 0),
(188, 'Solomon Clarke', 981.00, 459.00, '32', '17', '14', '16', 'Eos voluptas fugiat', '../uploads/img8.jpeg', 0),
(189, 'Velma Gonzales', 409.00, 165.00, '33', '18', '14', '18', 'Ut quasi libero qui ', '../uploads/img5.jpeg', 0),
(190, 'Joan Bruce', 590.00, 909.00, '32', '18', '14', '13', 'Hic sed pariatur Re', '../uploads/img7.jpeg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `otp`, `otp_expiry`) VALUES
(1, 'codekey919@gmail.com', '$2y$10$HmxLZ4Bl4JwbNt2adKyNJ.KAJPAJojOCyNUFZNHtt8Fql3pulOn3W', NULL, NULL),
(2, 'admin@gmail.com', '$2y$10$v9OVnjgfVYoFTV4lL0./ieBNEj1VCp2xuo3gXzp5rhJpTp1ONnTgy', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `varriant`
--

CREATE TABLE `varriant` (
  `id` int(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `varriant`
--

INSERT INTO `varriant` (`id`, `name`, `color`, `size`, `status`) VALUES
(8, 'Oversized T-shirt', 'Red, Green, White, Black, Lavender', 'S,M,XL,XXL', 1),
(9, 'Classic T-shirt', 'Red, Green, White, Black, Lavender', 'S,M,XL,XXL', 1),
(10, 'Hoodies', 'Red, Green, White, Black, Lavender', 'S,M,XL,XXL', 1),
(11, 'Sweatshirts', 'Red, Green, White, Black, Lavender', 'S,M,XL,XXL', 1),
(12, 'Polo T-shirts', 'Red, Green, White, Black, Lavender', 'S,M,XL,XXL', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `information`
--
ALTER TABLE `information`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offer`
--
ALTER TABLE `offer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `varriant`
--
ALTER TABLE `varriant`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `information`
--
ALTER TABLE `information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `offer`
--
ALTER TABLE `offer`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `varriant`
--
ALTER TABLE `varriant`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
