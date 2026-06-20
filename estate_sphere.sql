-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 20, 2026 at 04:15 PM
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
-- Database: `estate_sphere`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `prop_id` int(11) DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `prop_id`, `added_at`) VALUES
(11, 4, 2, '2026-06-16 17:30:48'),
(14, 6, 56, '2026-06-17 12:55:17'),
(15, 6, 53, '2026-06-17 12:55:30');

-- --------------------------------------------------------

--
-- Table structure for table `favourites`
--

CREATE TABLE `favourites` (
  `fav_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `prop_id` int(11) DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favourites`
--

INSERT INTO `favourites` (`fav_id`, `user_id`, `prop_id`, `added_at`) VALUES
(1, 4, 1, '2026-06-16 16:04:55'),
(2, 5, 57, '2026-06-17 16:46:18');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `msg_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(20) DEFAULT 'Unread',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`msg_id`, `name`, `email`, `phone`, `message`, `status`, `created_at`) VALUES
(1, 'Shahid Faizal', 'shahiddd.faizaal@gmail.com', '', 'qdQ', 'Resolved', '2026-06-17 00:32:43'),
(2, 'Shahid Faizal', 'shahiddd.faizaal@gmail.com', '', 'Pls help ', 'Resolved', '2026-06-17 14:05:49'),
(3, 'Shahid Faizal', 'shahiddd.faizaal@gmail.com', '', 'sdasfE', 'Resolved', '2026-06-17 22:17:09');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `prop_id` int(11) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `amount` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `prop_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 1,
  `price` decimal(15,2) NOT NULL,
  `bedrooms` int(11) NOT NULL,
  `bathrooms` int(11) NOT NULL,
  `area_sqft` int(11) NOT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  `map_link` text DEFAULT NULL,
  `category` enum('Residential','Commercial','Industrial','Land') DEFAULT 'Residential',
  `status` enum('available','sold') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `verification_doc` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`prop_id`, `title`, `location`, `description`, `image_path`, `is_verified`, `price`, `bedrooms`, `bathrooms`, `area_sqft`, `image_name`, `map_link`, `category`, `status`, `created_at`, `verification_doc`) VALUES
(1, 'Colombo Luxury Suite', 'Colombo 03, Sri Lanka', 'Experience unparalleled minimalist luxury in the heart of Colombo with panoramic ocean views.', 'Images/download (5).jpeg', 1, 45000000.00, 3, 2, 1800, NULL, NULL, 'Residential', 'available', '2026-06-16 09:10:36', ''),
(2, 'Kandy Hillside Villa', 'Primrose Hill, Kandy', 'A premium hillside retreat offering absolute privacy and breathtaking mountain vistas.', 'Images/images (9).jpeg', 1, 32000000.00, 4, 3, 2500, NULL, NULL, 'Residential', 'available', '2026-06-16 09:10:36', ''),
(3, 'Galle Beachfront Estate', 'Unawatuna, Galle', 'High-end beachfront property with exclusive access to the Indian Ocean.', 'Images/thumb.jpeg', 1, 18500000.00, 2, 2, 1500, NULL, NULL, 'Residential', 'available', '2026-06-16 09:10:36', ''),
(31, 'Modern City Apartment', 'Colombo 03', 'Luxury 3-bedroom apartment with ocean views and smart home integration.', 'uploads/default1.jpg', 1, 45000000.00, 3, 2, 1500, 'default1.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', 'uploads/docs/mock_deed.pdf'),
(32, 'Heritage Hillside Villa', 'Kandy', 'Beautiful colonial-style villa overlooking the Kandy lake with a private garden.', 'uploads/default2.jpg', 1, 85000000.00, 5, 4, 4500, 'default2.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', 'uploads/docs/mock_deed.pdf'),
(33, 'Beachfront Paradise', 'Galle', 'Direct beach access, infinity pool, and modern minimalist architecture.', 'uploads/default3.jpg', 1, 120000000.00, 4, 4, 3200, 'default3.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', 'uploads/docs/mock_deed.pdf'),
(34, 'Cozy Suburb Family Home', 'Malabe', 'Perfect starter home for a family, close to leading IT universities and schools.', 'uploads/default4.jpg', 0, 22000000.00, 3, 2, 1800, 'default4.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', ''),
(35, 'Commercial Office Space', 'Colombo 07', 'Premium office space in the heart of the commercial district. Fully furnished.', 'uploads/default5.jpg', 1, 150000000.00, 0, 4, 6000, 'default5.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', 'uploads/docs/mock_deed.pdf'),
(36, 'Mountain Retreat Cabin', 'Nuwara Eliya', 'Cozy wooden cabin surrounded by tea estates. Perfect for holiday rentals.', 'uploads/default6.jpg', 0, 35000000.00, 2, 1, 1200, 'default6.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', ''),
(37, 'Luxury Penthouse', 'Colombo 01', 'Ultra-luxury penthouse with private elevator, rooftop pool, and 360 city views.', 'uploads/default7.jpg', 1, 250000000.00, 4, 5, 5500, 'default7.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', 'uploads/docs/mock_deed.pdf'),
(38, 'Riverside Estate', 'Bentota', 'Spacious property with private boat dock and lush tropical gardens.', 'uploads/default8.jpg', 0, 65000000.00, 4, 3, 3800, 'default8.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', ''),
(39, 'Eco-Friendly Villa', 'Kurunegala', 'Solar-powered home with rainwater harvesting and open-concept design.', 'uploads/default9.jpg', 1, 42000000.00, 3, 2, 2200, 'default9.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', 'uploads/docs/mock_deed.pdf'),
(40, 'Urban Studio Loft', 'Rajagiriya', 'Trendy loft apartment ideal for young professionals. Close to all amenities.', 'uploads/default10.jpg', 0, 18000000.00, 1, 1, 850, 'default10.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', ''),
(41, 'Classic Bungalow', 'Matale', 'Well-maintained classic bungalow with large courtyard and spice garden.', 'uploads/default11.jpg', 1, 55000000.00, 4, 2, 2800, 'default11.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', 'uploads/docs/mock_deed.pdf'),
(42, 'Oceanview Condo', 'Mount Lavinia', 'Premium condominium offering stunning sunset views over the Indian Ocean.', 'uploads/default12.jpg', 1, 78000000.00, 3, 3, 1750, 'default12.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', 'uploads/docs/mock_deed.pdf'),
(43, 'Sprawling Farmhouse', 'Dambulla', 'Large farmhouse sitting on 2 acres of fertile land. Great agricultural investment.', 'uploads/default13.jpg', 0, 30000000.00, 3, 2, 2500, 'default13.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', ''),
(44, 'Executive Residence', 'Battaramulla', 'Highly secure, premium neighborhood. Features a private gym and home theater.', 'uploads/default14.jpg', 1, 95000000.00, 5, 5, 4800, 'default14.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', 'uploads/docs/mock_deed.pdf'),
(45, 'Coastal Land Plot', 'Mirissa', 'Prime bare land ready for boutique hotel or private villa construction.', 'uploads/default15.jpg', 0, 15000000.00, 0, 0, 0, 'default15.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', ''),
(46, 'Suburban Duplex', 'Maharagama', 'Modern two-story duplex with separate entrances, ideal for rental income.', 'uploads/default16.jpg', 1, 28000000.00, 4, 3, 2400, 'default16.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', 'uploads/docs/mock_deed.pdf'),
(47, 'Historical Manor', 'Galle Fort', 'Restored 18th-century Dutch manor located inside the iconic Galle Fort.', 'uploads/default17.jpg', 1, 110000000.00, 4, 4, 3500, 'default17.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', 'uploads/docs/mock_deed.pdf'),
(48, 'Tea Estate Bungalow', 'Hatton', 'Active tea estate property featuring a colonial bungalow and worker quarters.', 'uploads/default18.jpg', 0, 72000000.00, 5, 3, 4000, 'default18.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', ''),
(49, 'Minimalist Townhouse', 'Nugegoda', 'Sleek, modern townhouse with smart lighting and space-saving architecture.', 'uploads/default19.jpg', 1, 38000000.00, 3, 2, 1900, 'default19.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', 'uploads/docs/mock_deed.pdf'),
(50, 'Lagoon Front Villa', 'Negombo', 'Gorgeous property facing the Negombo lagoon. Includes private pool.', 'uploads/default20.jpg', 1, 88000000.00, 4, 4, 3600, 'default20.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', 'uploads/docs/mock_deed.pdf'),
(51, 'Boutique Hotel Property', 'Ella', 'Fully functional 10-room boutique hotel with restaurant and stunning mountain views.', 'uploads/default21.jpg', 1, 180000000.00, 10, 10, 8000, 'default21.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', 'uploads/docs/mock_deed.pdf'),
(52, 'Gated Community Home', 'Thalawathugoda', 'Secure community with shared pool, clubhouse, and 24/7 security.', 'uploads/default22.jpg', 1, 48000000.00, 4, 3, 2600, 'default22.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', 'uploads/docs/mock_deed.pdf'),
(53, 'Student Boarding Complex', 'Kelaniya', 'High ROI property with 15 rooms, currently rented to university students.', 'uploads/default23.jpg', 0, 45000000.00, 15, 8, 4500, 'default23.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', ''),
(54, 'Luxury Golf Villa', 'Hambantota', 'Exclusive villa located on a premier 18-hole golf course.', 'uploads/default24.jpg', 1, 135000000.00, 4, 5, 4200, 'default24.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', 'uploads/docs/mock_deed.pdf'),
(55, 'Downtown Retail Space', 'Colombo 04', 'High foot-traffic retail shop space on the ground floor of a busy complex.', 'uploads/default25.jpg', 1, 60000000.00, 0, 1, 1200, 'default25.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', 'uploads/docs/mock_deed.pdf'),
(56, 'Tranquil Lakehouse', 'Bolgoda', 'Weekend getaway home right on the water with a massive wooden deck.', 'uploads/default26.jpg', 0, 52000000.00, 3, 2, 2100, 'default26.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', ''),
(57, 'Premium Residential Plot', 'Colombo 05', 'Highly sought-after 10 perch bare land in a prestigious neighborhood.', 'uploads/default27.jpg', 1, 25000000.00, 0, 0, 0, 'default27.jpg', '', 'Residential', 'available', '2026-06-16 18:18:06', 'uploads/docs/mock_deed.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `property_images`
--

CREATE TABLE `property_images` (
  `img_id` int(11) NOT NULL,
  `prop_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property_images`
--

INSERT INTO `property_images` (`img_id`, `prop_id`, `image_path`) VALUES
(1, 1, 'uploads/default2.jpg'),
(2, 1, 'uploads/default3.jpg'),
(3, 1, 'uploads/default4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `res_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `prop_id` int(11) NOT NULL,
  `res_date` datetime DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending Agent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`res_id`, `user_id`, `prop_id`, `res_date`, `status`) VALUES
(1, 5, 56, '2026-06-17 14:05:22', 'Pending Agent'),
(2, 5, 57, '2026-06-17 22:16:38', 'Pending Agent');

-- --------------------------------------------------------

--
-- Table structure for table `tracking`
--

CREATE TABLE `tracking` (
  `track_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `step_number` int(11) DEFAULT 1,
  `status_desc` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user','super_admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'MFM Shahid', 'mfmshahid2006@gmail.com', '$2y$10$YDFSZn4mBCz2GQFScEbZq.HQA08NR6QC6G65K9Wzw3lpZe.hz2Z8C', 'user', '2026-04-06 04:01:38'),
(2, 'Shahid Faizal', 'root@gmail.com', '$2y$10$saKDW/.dDEbnqi/2rdvshOqY4yA2s8B47VXcCErhLZdOBnrWtsP8i', 'user', '2026-04-06 04:04:38'),
(3, 'Shahid', 'sha.99@gmail.com', '$2y$10$gJfrl4j.F./e.hkheKhj/uhYrn1oxRo1YIP2JdOf6fHp8vN2wY.Aq', 'admin', '2026-06-16 09:40:51'),
(4, 'ahamed', 'mhd44@gmail.com', '$2y$10$usWG/tBwM6Kh.ZimVysGv.JwSuzNlk2F4eUJrvdsZWHAxTESkdcWO', 'admin', '2026-06-16 14:31:51'),
(5, 'FAFA', 'sha12345@gmail.com', '$2y$10$4sxxkqega2Ln/WhS3BOEVOv2pUOSK3FaH9uxUG2qrsnmEtVetVpG6', 'super_admin', '2026-06-17 08:34:04'),
(6, 'Mohamed Shahid', 'mohamedshahid001@gmail.com', '$2y$10$EbT18cljKgJCBHxc6meNU.nh00D7IRibsV52zxiZDIB2icmd454dG', '', '2026-06-17 12:54:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `favourites`
--
ALTER TABLE `favourites`
  ADD PRIMARY KEY (`fav_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `prop_id` (`prop_id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `prop_id` (`prop_id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`prop_id`);

--
-- Indexes for table `property_images`
--
ALTER TABLE `property_images`
  ADD PRIMARY KEY (`img_id`),
  ADD KEY `prop_id` (`prop_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`res_id`);

--
-- Indexes for table `tracking`
--
ALTER TABLE `tracking`
  ADD PRIMARY KEY (`track_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `favourites`
--
ALTER TABLE `favourites`
  MODIFY `fav_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `prop_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `property_images`
--
ALTER TABLE `property_images`
  MODIFY `img_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `res_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tracking`
--
ALTER TABLE `tracking`
  MODIFY `track_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favourites`
--
ALTER TABLE `favourites`
  ADD CONSTRAINT `favourites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favourites_ibfk_2` FOREIGN KEY (`prop_id`) REFERENCES `properties` (`prop_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`prop_id`) REFERENCES `properties` (`prop_id`);

--
-- Constraints for table `property_images`
--
ALTER TABLE `property_images`
  ADD CONSTRAINT `property_images_ibfk_1` FOREIGN KEY (`prop_id`) REFERENCES `properties` (`prop_id`) ON DELETE CASCADE;

--
-- Constraints for table `tracking`
--
ALTER TABLE `tracking`
  ADD CONSTRAINT `tracking_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
