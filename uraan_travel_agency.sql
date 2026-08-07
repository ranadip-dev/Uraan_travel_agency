-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2026 at 06:47 AM
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
-- Database: `uraan_travel_agency`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `package_id` int(10) UNSIGNED NOT NULL,
  `travel_date` date NOT NULL,
  `persons` int(10) UNSIGNED NOT NULL,
  `special_request` text DEFAULT NULL,
  `booking_status` enum('Pending','Confirmed','Cancelled') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `package_id`, `travel_date`, `persons`, `special_request`, `booking_status`, `created_at`) VALUES
(3, 1, 9, '2026-08-26', 5, 'nothing', 'Pending', '2026-08-05 19:39:41');

-- --------------------------------------------------------

--
-- Table structure for table `enquiries`
--

CREATE TABLE `enquiries` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `status` enum('New','Read','Closed') DEFAULT 'New',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enquiries`
--

INSERT INTO `enquiries` (`id`, `full_name`, `email`, `phone`, `subject`, `message`, `status`, `created_at`) VALUES
(1, 'Ranadip Das', 'ranadipdas75@gmail.com', '7584032775', 'check', 'hii,', 'New', '2026-07-29 15:40:04'),
(2, 'Ranadip Das', 'ranadipdas75@gmail.com', '7584032775', 'check', 'hii,', 'Read', '2026-07-29 15:41:47');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `location` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_days` tinyint(3) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `title`, `location`, `description`, `price`, `duration_days`, `image`, `status`, `created_at`, `updated_at`) VALUES
(5, 'Darjeeling Himalayan Escape', 'Darjeeling, West Bengal', 'Experience the charm of Darjeeling with beautiful tea gardens, Himalayan views, local sightseeing, and a peaceful mountain atmosphere.', 8999.00, 4, 'darjeeling.jpg', 'active', '2026-08-05 18:55:45', '2026-08-05 18:55:45'),
(6, 'Sikkim Mountain Adventure', 'Gangtok, Sikkim', 'Discover Gangtok and the breathtaking landscapes of Sikkim with mountain viewpoints, monasteries, lakes, and memorable sightseeing experiences.', 12999.00, 5, 'sikkim.jpg', 'active', '2026-08-05 18:55:45', '2026-08-05 18:55:45'),
(7, 'Goa Beach Holiday', 'Goa', 'Enjoy a relaxing Goa vacation featuring beautiful beaches, coastal sightseeing, vibrant markets, local attractions, and plenty of leisure time.', 11999.00, 5, 'goa.jpg', 'active', '2026-08-05 18:55:45', '2026-08-05 18:55:45'),
(8, 'Kashmir Paradise Tour', 'Srinagar, Jammu and Kashmir', 'Explore the scenic beauty of Kashmir with Srinagar, peaceful lakes, magnificent valleys, mountain landscapes, and unforgettable sightseeing.', 18999.00, 6, 'kashmir.jpg', 'active', '2026-08-05 18:55:45', '2026-08-05 18:55:45'),
(9, 'Manali Adventure Getaway', 'Manali, Himachal Pradesh', 'Escape to Manali for spectacular mountain scenery, local attractions, adventure activities, peaceful valleys, and refreshing Himalayan weather.', 13999.00, 5, 'manali.jpg', 'active', '2026-08-05 18:55:45', '2026-08-05 18:55:45'),
(10, 'Kerala Backwater Retreat', 'Alappuzha, Kerala', 'Experience Kerala with peaceful backwaters, lush greenery, traditional culture, scenic landscapes, and a relaxing stay surrounded by nature.', 15999.00, 6, 'kerala.jpg', 'active', '2026-08-05 18:55:45', '2026-08-05 18:55:45'),
(11, 'Rajasthan Heritage Journey', 'Jaipur, Rajasthan', 'Discover Rajasthan through historic forts, magnificent palaces, colorful markets, traditional architecture, and the rich cultural heritage of Jaipur.', 14999.00, 5, 'rajasthan.jpg', 'active', '2026-08-05 18:55:45', '2026-08-05 18:55:45'),
(12, 'Andaman Island Escape', 'Port Blair, Andaman and Nicobar Islands', 'Enjoy turquoise waters, tropical beaches, island sightseeing, beautiful coastlines, and a relaxing tropical holiday in the Andaman Islands.', 22999.00, 6, 'andaman.jpg', 'active', '2026-08-05 18:55:45', '2026-08-05 18:55:45'),
(13, 'Ladakh Explorer', 'Leh, Ladakh', 'Experience the dramatic landscapes of Ladakh with high-altitude mountains, scenic roads, monasteries, valleys, and spectacular natural surroundings.', 24999.00, 7, 'ladakh.jpg', 'active', '2026-08-05 18:55:45', '2026-08-05 18:55:45'),
(14, 'Meghalaya Nature Trail', 'Shillong, Meghalaya', 'Explore Meghalaya through green hills, waterfalls, caves, scenic roads, living root bridges, and the refreshing natural beauty of Northeast India.', 16999.00, 6, 'meghalaya.jpg', 'active', '2026-08-05 18:55:45', '2026-08-05 18:55:45'),
(15, 'Varanasi Spiritual Journey', 'Varanasi, Uttar Pradesh', 'Discover the spiritual atmosphere of Varanasi with ancient ghats, temples, cultural landmarks, evening ceremonies, and the timeless beauty of the Ganges.', 7999.00, 4, 'varanasi.jpg', 'active', '2026-08-05 18:55:45', '2026-08-05 18:55:45'),
(16, 'Agra Heritage Escape', 'Agra, Uttar Pradesh', 'Visit the iconic Taj Mahal and explore the historic architecture, monuments, local culture, and fascinating heritage of Agra.', 6999.00, 3, 'agra.jpg', 'active', '2026-08-05 18:55:45', '2026-08-05 18:55:45'),
(17, 'Rishikesh Adventure Tour', 'Rishikesh, Uttarakhand', 'Combine adventure and relaxation in Rishikesh with river activities, mountain surroundings, spiritual landmarks, scenic viewpoints, and peaceful evenings.', 9999.00, 4, 'rishikesh.jpg', 'active', '2026-08-05 18:55:45', '2026-08-05 18:55:45'),
(18, 'Ooty Hill Station Retreat', 'Ooty, Tamil Nadu', 'Relax among the green hills of Ooty with beautiful gardens, lakes, viewpoints, tea plantations, and the refreshing climate of the Nilgiri Hills.', 10999.00, 4, 'ooty.jpg', 'active', '2026-08-05 18:55:45', '2026-08-05 18:55:45'),
(19, 'Golden Triangle Tour', 'Delhi, Agra and Jaipur', 'Explore three of India\'s most famous destinations in one journey, covering Delhi landmarks, the Taj Mahal in Agra, and the royal heritage of Jaipur.', 17999.00, 6, 'golden-triangle.jpg', 'active', '2026-08-05 18:55:45', '2026-08-05 18:55:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Ranadip Das1', 'ranadipdas75@gmail.com', '+917584032775', '$2y$10$HyxhRmj4Ny1O0G74fyy5AON.kVrW/cyyinv8d/U36XXCgkd9YhnZK', 'user', 'active', '2026-07-25 11:32:08', '2026-08-02 17:39:54'),
(3, 'Ranadip Das', 'abc@gmail.com', '7584032775', '$2y$10$GWbCjxMiepuy5UTJz4rfzeSOsgsKIm6j33vdobFAvL9fPGs1IAbwu', 'user', 'active', '2026-07-25 11:35:45', '2026-07-25 11:35:45'),
(4, 'Ranadip Das', 'xyz@gmail.com', '7584032775', '$2y$10$CeAgxKgnh5JndWA0X1PmKOYf5hM0HcOvvxmgk13zkPoXmDVqvpa9G', 'user', 'active', '2026-07-25 11:42:03', '2026-07-25 11:42:03'),
(5, 'Roni', 'admin@gmail.com', '7584032775', '$2y$10$.sh0W0UWbaadoWcGKpuefeytWctA/FF7HSETx6cAIs1W2nbIG9wG.', 'admin', 'active', '2026-08-01 07:02:27', '2026-08-01 15:42:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_booking_user` (`user_id`),
  ADD KEY `fk_booking_package` (`package_id`);

--
-- Indexes for table `enquiries`
--
ALTER TABLE `enquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `enquiries`
--
ALTER TABLE `enquiries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_booking_package` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_booking_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
