-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2026 at 08:10 AM
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
-- Database: `hotel_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`) VALUES
(1, 'admin@gmail.com', 'Admin@123');

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`id`, `name`) VALUES
(1, 'Wi-Fi'),
(2, 'AC'),
(3, 'Breakfast'),
(4, 'TV'),
(5, 'Swimming Pool'),
(6, 'Refrigerator'),
(7, 'Gym'),
(8, 'Parking'),
(9, 'Hot Water'),
(10, 'Room Service'),
(11, 'Air Conditioner'),
(12, 'Laundry Service'),
(13, 'Mini Bar'),
(14, 'Balcony'),
(15, 'Sea View');

-- --------------------------------------------------------

--
-- Table structure for table `ashy_images`
--

CREATE TABLE `ashy_images` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `image_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ashy_images`
--

INSERT INTO `ashy_images` (`id`, `hotel_id`, `image_name`) VALUES
(1, 12, 'mumbai.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `booking_no` varchar(20) DEFAULT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `hotel_name` varchar(255) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `checkin_date` date DEFAULT NULL,
  `checkout_date` date DEFAULT NULL,
  `rooms` int(11) DEFAULT NULL,
  `room_type` varchar(50) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `status` enum('Pending','Approved','Cancelled','Paid') NOT NULL DEFAULT 'Pending',
  `cancel_reason` text DEFAULT NULL,
  `guest_data` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `booking_no`, `hotel_id`, `hotel_name`, `user_name`, `user_email`, `checkin_date`, `checkout_date`, `rooms`, `room_type`, `price`, `status`, `cancel_reason`, `guest_data`, `created_at`, `user_id`) VALUES
(32, 'BK60376', 13, 'Grand Hyatt Goa', 'Ruhi diyora', 'ruhi@gmail.com', '2026-03-27', '2026-04-02', 1, NULL, 43500, 'Pending', NULL, '[{\"room\":\"Twin Room with Balcony and Garden View\",\"qty\":1,\"guests\":1,\"price\":43500}]', '2026-03-25 05:00:03', 13),
(33, 'BK66677', 13, 'Grand Hyatt Goa', 'shifa khan', 'shifa@gmail.com', '2026-03-26', '2026-04-01', 3, NULL, 130500, 'Cancelled', 'personal reason', '[{\"room\":\"Twin Room with Balcony and Garden View\",\"qty\":3,\"guests\":1,\"price\":43500}]', '2026-03-25 05:19:57', 13),
(34, 'BK91097', 16, 'Taj Skyline Ahmedabad', 'ved Diyora', 'ved1@gmail.com', '2026-03-27', '2026-04-01', 2, NULL, 54200, 'Paid', NULL, '[{\"room\":\"Deluxe Room Twin Bed\",\"qty\":2,\"guests\":1,\"price\":27100}]', '2026-03-25 05:24:45', 14),
(35, 'BK91717', 12, 'Hotel Ashyana - Near To Grant Road Station Mumbai', 'ved Diyora', 'ved1@gmail.com', '2026-03-28', '2026-04-01', 1, NULL, 18060, 'Paid', NULL, '[{\"room\":\"Superior Double Room\",\"qty\":1,\"guests\":1,\"price\":18060}]', '2026-03-25 05:35:38', 14),
(36, 'BK63750', 13, 'Grand Hyatt Goa', 'ved Diyora', 'ved1@gmail.com', '2026-03-29', '2026-04-01', 1, NULL, 43500, 'Paid', NULL, '[{\"room\":\"Twin Room with Balcony and Garden View\",\"qty\":1,\"guests\":1,\"price\":43500}]', '2026-03-25 05:42:39', 14),
(37, 'BK30989', 13, 'Grand Hyatt Goa', 'yasvi diyora', 'yasvi@gmail.com', '2026-03-27', '2026-04-05', 1, NULL, 43500, 'Cancelled', 'personal reason', '[{\"room\":\"Twin Room with Balcony and Garden View\",\"qty\":1,\"guests\":1,\"price\":43500}]', '2026-03-25 05:46:24', 14),
(38, 'BK13223', 15, 'Regenta Place Vasco Goa', 'ved', 'ved1@gmail.com', '2026-03-28', '2026-04-01', 3, NULL, 50019, 'Paid', NULL, '[{\"room\":\"Double Room\",\"qty\":3,\"guests\":2,\"price\":16673}]', '2026-03-25 07:06:54', 14);

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `total_amount` int(11) NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_name` varchar(100) DEFAULT NULL,
  `user_email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_rooms`
--

CREATE TABLE `booking_rooms` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `quantity` varchar(2) NOT NULL,
  `price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `created_at`) VALUES
(2, 'Super Deluxe', '2026-02-26 08:47:15'),
(3, 'Suite', '2026-02-26 08:47:15'),
(4, 'AC Room', '2026-02-26 08:47:54'),
(5, 'Non-AC Room', '2026-02-26 08:47:54');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `city_name` varchar(100) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `state_id`, `city_name`, `status`) VALUES
(1, 1, 'Mumbai', 'active'),
(2, 1, 'Pune', 'active'),
(3, 1, 'Nagpur', 'active'),
(4, 1, 'Nashik', 'active'),
(5, 2, 'Ahmedabad', 'active'),
(6, 2, 'Vadodara', 'active'),
(7, 2, 'Surat', 'active'),
(8, 2, 'Kutch', 'active'),
(9, 2, 'Gir National Park', 'active'),
(10, 3, 'Jammu City', 'active'),
(11, 3, 'Srinagar', 'active'),
(12, 4, 'Shimla', 'active'),
(13, 4, 'Manali', 'active'),
(14, 4, 'Dharamshala', 'active'),
(15, 5, 'Thiruvananthapuram', 'active'),
(16, 5, 'Kochi', 'active'),
(17, 5, 'Kozhikode', 'active'),
(18, 5, 'Munnar', 'active'),
(19, 6, 'Amritsar', 'active'),
(20, 6, 'Ludhiana', 'active'),
(21, 6, 'Chandigarh', 'active'),
(22, 6, 'Patiala', 'active'),
(23, 7, 'Jaipur', 'active'),
(24, 7, 'Udaipur', 'active'),
(25, 7, 'Jodhpur', 'active'),
(26, 7, 'Jaisalmer', 'active'),
(27, 8, 'Dehradun', 'active'),
(28, 8, 'Nainital', 'active'),
(29, 8, 'Haridwar', 'active'),
(30, 8, 'Rishikesh', 'active'),
(31, 8, 'Massuri', 'active'),
(32, 9, 'Panaji', 'active'),
(33, 9, 'Margao', 'active'),
(34, 9, 'Vasco da Gama', 'active'),
(35, 10, 'Leh', 'active'),
(36, 10, 'Kargil', 'active'),
(37, 2, 'Gandhinagar', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `hotel_id`, `name`, `email`, `message`, `created_at`) VALUES
(2, 19, 'Maitrik Diyora', 'maitrik@gmail.com', 'hotel information', '2026-03-23 00:42:38'),
(3, 12, 'yasvi diyora', 'yasvi@gmail.com', 'hotel more details info.', '2026-03-23 00:47:31'),
(4, 25, 'yasvi diyora', 'yasvi@gmail.com', 'more Information', '2026-03-23 11:14:58');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `reply` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `hotel_id`, `user_id`, `name`, `email`, `message`, `rating`, `created_at`) VALUES
(1, 17, NULL, 'Maitrik Diyora', 'maitrik@gmail.com', 'best hotel & servces', 4, '2026-03-22 22:31:27'),
(3, 13, NULL, 'Maitrik Diyora', 'maitrik@gmail.com', 'asdefrgthj', 5, '2026-03-22 22:41:07'),
(4, 12, NULL, 'yasvi diyora', 'yasvi@gmail.com', 'best hotel and best services', 5, '2026-03-23 00:47:57');

-- --------------------------------------------------------

--
-- Table structure for table `haridwar_reviews`
--

CREATE TABLE `haridwar_reviews` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `review` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotel`
--

CREATE TABLE `hotel` (
  `id` int(11) NOT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `hotel_name` varchar(200) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `hotel_address` text DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT NULL,
  `status` enum('approved','pending','rejected') DEFAULT 'approved',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel`
--

INSERT INTO `hotel` (`id`, `manager_id`, `hotel_name`, `image`, `hotel_address`, `state_id`, `city_id`, `pincode`, `rating`, `status`, `created_at`) VALUES
(12, 19, 'Hotel Ashyana - Near To Grant Road Station Mumbai', '1773936762_mAshyana.webp', 'GROUND 1ST AND SECOND, PLOT315/315A, FORTUNE AURA, Mumbai City, Mumbai, India', 1, 1, '400007', 7.7, 'approved', '2026-03-19 16:12:42'),
(13, 20, 'Grand Hyatt Goa', '1773937560_gGrand.jpg', '\r\n\r\nP.O.Goa University, Panaji, India', 9, 32, '403206 ', 4.9, 'approved', '2026-03-19 16:26:00'),
(14, 21, 'Fairfield by Marriott Goa Benaulim', '1773937995_gfair.jpg', 'Benaulim Beach, South Goa, Benaulim, India\r\n', 9, 33, '403716 ', 4.5, 'approved', '2026-03-19 16:33:15'),
(15, 22, 'Regenta Place Vasco Goa', '1773938363_gRegenta.jpg', 'Regenta Place Vasco Plot no.135, Near Vaddem Lake Vaddem Vasco , India', 9, 34, '403802 ', 4.0, 'approved', '2026-03-19 16:39:23'),
(16, 23, 'Taj Skyline Ahmedabad', '1773939066_Ataj.jpg', 'Sankalp Square III, Opp. Saket3, Nr. Nilkanth Green Sindhubhavan Road, Shilaj , Ahmedabad, Thaltej, Ahmedabad, India', 2, 5, '380059 ', 4.9, 'approved', '2026-03-19 16:51:06'),
(17, 24, 'Fairfield by Marriott Vadodara', '1773939707_vFair.jpg', 'RC Dutt Road, Alkapuri, Vadodara, India\r\n', 2, 6, '390007 ', 4.1, 'approved', '2026-03-19 17:01:47'),
(18, 26, 'Welcomhotel by ITC Hotels, Shimla', '1773940520_Shimla.jpg', 'Village Patengali, Tarapur, Naldhera Golf Course Road, Mashobra, Shimla, India', 4, 12, '171007 ', 4.8, 'approved', '2026-03-19 17:15:20'),
(19, 27, 'Namah Nainital, a member of Radisson Individuals Retreats', '1773941952_Nnamah.jpg', 'GRASSMERE ESTATE, MALLITAL, Nainital, India\r\n', 8, 28, '263001 ', 4.9, 'approved', '2026-03-19 17:39:12'),
(20, 28, 'The Leela Gandhinagar', '1774068616_leela.jpg', 'Airspace above Gandhinagar Railway station   Sector -14, K Road, 382014 Gandhinagar, India', 2, 37, '382014', 4.9, 'approved', '2026-03-21 04:50:16'),
(21, 29, 'The Fern Gir Forest Resort', '1774093446_gFern.jpg', 'Sasan Junagadh Road, 362135 Sasan Gir, India\r\n', 2, 9, '362135 ', 4.9, 'approved', '2026-03-21 11:44:06'),
(22, 30, 'Ramada Plaza by Wyndham Jammu Vijaypur', '1774093807_Ramada.jpg', 'JAMMU PATHANKOT HIGHWAY NH-1A THANDI KHUI,ADJOINING RADHA SOAMI SATSANG BEAS,DISTRICT SAMBA, 184120 Jammu, India', 3, 10, '', 4.9, 'approved', '2026-03-21 11:50:07'),
(23, 31, 'Storii By ITC Hotels Urvashis Retreat, Manali', '1774094366_Manali.jpg', 'Shanag Road, Near Nehrukund, Village Shanag, P.O. Bahang, Tehsil Manali, District Kullu, 175103 Manali, India\r\n', 4, 13, '175103', 4.8, 'approved', '2026-03-21 11:59:26'),
(24, 32, 'Moustache Select Mcleodganj', '1774094884_Mount.jpg', 'Mcleodganj, 176215 Dharamshala, India', 4, 14, '176215 ', 4.5, 'approved', '2026-03-21 12:08:04'),
(25, 33, 'Hotel Gyalpo Residency', '1774095449_leh.jpg', 'Skara Road Near zorawar fort leh, 194101 Leh, India\r\n', 10, 35, '194101 ', 4.2, 'approved', '2026-03-21 12:17:29'),
(26, 34, 'Zoe Cozy Escape with Big Projector', '1774237522_Zoe.jpg', 'Amritsar Dream City', 6, 19, '143001', 4.0, 'approved', '2026-03-23 03:45:22'),
(27, 35, 'Hotel Searock', '1774241213_Kerala.jpg', 'Hawa Beach Road Hotel Searock , 695527 , Trivandrum , india', 5, 15, '695527', 4.2, 'approved', '2026-03-23 04:46:53'),
(28, 36, 'taj', '1774252917_aranyaka.webp', 'mumbai , india', 9, 32, '989876', 4.5, 'approved', '2026-03-23 08:01:57');

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL,
  `hotel_name` varchar(150) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `hotel_address` text DEFAULT NULL,
  `state_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT 0.0,
  `status` enum('pending','approved','inactive') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotel_images`
--

CREATE TABLE `hotel_images` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `city_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `city_name` varchar(100) NOT NULL,
  `total_hotels` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manager`
--

CREATE TABLE `manager` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `id` int(11) NOT NULL,
  `hotel_name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `state_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`id`, `hotel_name`, `email`, `password`, `phone`, `state_id`, `city_id`, `pincode`, `created_at`) VALUES
(19, 'Hotel Ashyana - Near To Grant Road Station Mumbai', 'ashyana12@gmail.com', '$2y$10$pHzWwvOL408OZfKVjdTNoOAraJjOr.sTuGP0badh/G4fnLNS2S.9m', '9898765432', 1, 1, '400007', '2026-03-19 16:11:50'),
(20, 'Grand Hyatt Goa', 'grand@gmail.com', '$2y$10$WXp5gW3mjVm1ze..MccKeeOPfIO9L.cWDaXZHR2PFGCKYfYqCdibm', '9898765432', 9, 32, '403206', '2026-03-19 16:25:07'),
(21, 'Fairfield by Marriott Goa Benaulim', 'fairfield@gmail.com', '$2y$10$jBxCFrZxgdYmqM9j5VRPielO6o068yI96ZtgEg/JPi.tu5ZLbUKAa', '9876567867', 9, 33, '403516', '2026-03-19 16:32:15'),
(22, 'Regenta Place Vasco Goa', 'regenta@gmail.com', '$2y$10$vpj2c3jE3Q57ubbxqT0Bguml.KHh7o/x9Y3k/RBXW2I6VaUePA5x6', '9876897899', 9, 34, '403802', '2026-03-19 16:37:50'),
(23, 'Taj Skyline Ahmedabad', 'tasky@gmail.com', '$2y$10$g6UX6Fskmri/1Z1WEHahxu1gG.8TIfd7c3o1Km3D33PJPlNJRNtpe', '9898765432', 2, 5, '380059', '2026-03-19 16:50:29'),
(24, 'Fairfield by Marriott Vadodara', 'fairfield1@gmail.com', '$2y$10$SqajAcUOT3d/QtY/gVyiPub5DuOf/WinpsZE0vtwqCZoDDCxlBWzy', '9898765498', 2, 6, '390007', '2026-03-19 17:00:40'),
(26, 'Welcomhotel by ITC Hotels, Shimla', 'welcomhotel@gmail.com', '$2y$10$Wf7z/sY0FZ.iNvLzwuui0ODauZQu1LcCYIp.RpAyPM9d2UiJLeWAe', '9898787899', 4, 12, '171007', '2026-03-19 17:14:37'),
(27, 'Namah Nainital, a member of Radisson Individuals Retreats', 'namah@gmail.com', '$2y$10$XhF2GAr0R1Xm0rEQ1QMOkeqgK/NsbbQs8JEtLClmi9U0ZQg1uCC6q', '9898787899', 8, 28, '263001', '2026-03-19 17:37:46'),
(28, 'The Leela Gandhinagar', 'leela@gmail.com', '$2y$10$jB5t/fClAJ6mcsoHNn/eqO2Y6ZbYaL0SFN/XQ1Dfqgfor4W1AzByi', '9898765432', 2, 37, '382014', '2026-03-21 04:48:58'),
(29, 'The Fern Gir Forest Resort', 'fern@gmail.com', '$2y$10$coG8i9nsOuL6VjChWYQnMO.T/Yfh2RUkfO/t8xS/P4gc6dvoAOhIu', '9898769898', 2, 9, '362135', '2026-03-21 11:43:21'),
(30, 'Ramada Plaza by Wyndham Jammu Vijaypur', 'ramada@gmail.com', '$2y$10$OKdmX/y4paSioh5RjqdWYO0xd4ySD5q/jLuKo6xTYzE2iO9NSbZOS', '9898898967', 3, 10, '184120', '2026-03-21 11:49:10'),
(31, 'Storii By ITC Hotels Urvashis Retreat, Manali', 'storii@gmail.com', '$2y$10$5gWW3b.HLCzGGO4vL.M7fu7MC/mFdDmRcWdUe62uA8y1NHYyP7ThS', '8787989833', 4, 13, '175103', '2026-03-21 11:58:27'),
(32, 'Moustache Select Mcleodganj', 'moustache@gmail.com', '$2y$10$Wc18lg73TnrIczlYg2L58uU97bhNtbKDR5qGQhVbKaqroDpDQZA0i', '8989543256', 4, 14, '176215', '2026-03-21 12:07:02'),
(33, 'Hotel Gyalpo Residency', 'gyalpo@gmail.com', '$2y$10$1jq/ML03lTvc7YTlav/XDOLkbAWJiwhXjcAY4WurtzCZxxBDpeiUe', '9698654321', 10, 35, '194101', '2026-03-21 12:16:39'),
(34, 'Zoe Cozy Escape with Big Projector', 'zoe@gmail.com', '$2y$10$vCXQhAPxmAKXyAfQJAnxcOJQqmhCtTR0ztaTm3XWJ7sDUdumyorlC', '9023287131', 6, 19, '143001', '2026-03-23 03:40:24'),
(35, 'Hotel Searock', 'searock@gmaul.com', '$2y$10$GcTZBVAcY.lkpfbVVM5aYuUox2fo6g8EXvRgy7NWWnxV9qZ8VPnmy', '7786654332', 5, 15, '695527', '2026-03-23 04:45:24'),
(36, 'taj', 'taj@gmail.com', '$2y$10$OTjBEhl0ynQlTVsGSIMZPee4mKQICan5upEQE3fUjL9cH73BIliHy', '9898989899', 9, 32, '989876', '2026-03-23 07:59:44');

-- --------------------------------------------------------

--
-- Table structure for table `manager_details`
--

CREATE TABLE `manager_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `package_name` varchar(200) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `features` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `room_id`, `package_name`, `price`, `features`, `description`) VALUES
(7, 13, 'Standard', 6450, NULL, 'Good breakfast included\r\n\r\n'),
(8, 13, 'Breckfast', 7450, NULL, 'Good breakfast included\r\n'),
(9, 13, 'Dinner', 8800, NULL, 'Breakfast + Dinner'),
(10, 14, ' Breakfast ', 9000, NULL, 'Good breakfast included'),
(11, 11, ' Breakfast ', 10000, NULL, 'Breakfast + Dinner'),
(13, 23, ' Breakfast ', 39585, NULL, 'Fabulous breakfast included'),
(14, 23, 'Includes food/drink', 40040, NULL, 'Includes food/drink'),
(15, 24, 'Breckfast', 44000, NULL, 'Fabulous breakfast included'),
(16, 25, 'Breckfast ', 26000, NULL, 'Fabulous breakfast included'),
(18, 27, 'Good breakfast included', 18060, NULL, 'Good breakfast included'),
(19, 28, ' Breakfast + Dinner', 19350, NULL, 'Good breakfast + Dinner included'),
(20, 29, ' Breakfast ', 21930, NULL, 'Good breakfast included'),
(21, 30, ' Breakfast ', 43500, NULL, 'Fabulous breakfast included'),
(22, 30, ' Breakfast + Dinner', 50000, NULL, 'Fabulous breakfast + Dinner included'),
(23, 31, ' Breakfast ', 44000, NULL, 'Fabulous breakfast included'),
(24, 32, ' Breakfast ', 21762, NULL, 'Fabulous breakfast included'),
(25, 33, ' Breakfast & Lunch', 16673, NULL, 'Breakfast & lunch included'),
(26, 33, ' Dinner', 11545, NULL, 'Good Diiner included'),
(27, 34, ' Breakfast ', 27100, NULL, 'Fabulous breakfast included + services '),
(28, 35, 'Good breakfast included', 28600, NULL, 'Breakfast & dinner included'),
(29, 38, ' Breakfast ', 28898, NULL, 'Fabulous breakfast included'),
(30, 37, ' Breakfast ', 23948, NULL, 'Fabulous breakfast included'),
(31, 37, ' Breakfast + Dinner', 28598, NULL, 'Breakfast & dinner included'),
(32, 36, ' Breakfast ', 21098, NULL, 'Fabulous breakfast included'),
(33, 36, ' Breakfast + Dinner', 25598, NULL, 'Breakfast & dinner included'),
(34, 39, ' Breakfast ', 12900, NULL, 'Very good breakfast included'),
(35, 39, ' Breakfast + Dinner', 17700, NULL, 'Breakfast & dinner included'),
(36, 40, ' Breakfast ', 15300, NULL, 'Very good breakfast included'),
(37, 40, ' Breakfast + Dinner', 19200, NULL, 'Breakfast & dinner included'),
(38, 44, ' Breakfast ', 77500, NULL, 'Fabulous breakfast included'),
(39, 42, ' Breakfast ', 61000, NULL, 'Fabulous breakfast included'),
(40, 42, ' Breakfast + Dinner', 79176, NULL, 'Breakfast & dinner included'),
(41, 41, ' Breakfast ', 55000, NULL, 'Fabulous breakfast included'),
(42, 41, 'Breakfast & dinner ', 73176, NULL, 'Breakfast & dinner included'),
(43, 47, ' Breakfast ', 135000, NULL, 'Fabulous breakfast included'),
(44, 47, ' Breakfast + Dinner', 141000, NULL, 'Breakfast & dinner included\r\nIncludes services + late check-out'),
(45, 46, ' Breakfast ', 600000, NULL, 'Fabulous breakfast included'),
(46, 46, ' Breakfast + Dinner', 606000, NULL, 'Breakfast & dinner included'),
(47, 45, ' Breakfast ', 31000, NULL, 'Fabulous breakfast included'),
(48, 65, ' Breakfast ', 12000, NULL, 'good Breakfast'),
(49, 66, ' Breakfast + Dinner', 18000, NULL, 'Good Breakfast & Dinner'),
(50, 48, ' Breakfast + Dinner', 18000, NULL, 'Good Breakfast & Dinner'),
(51, 48, ' Breakfast ', 16000, NULL, 'Good Breakfast'),
(52, 49, ' Breakfast ', 17000, NULL, ' Fabulous breakfast included'),
(53, 50, ' Breakfast ', 20000, NULL, 'Fabulous breakfast included'),
(54, 51, ' Breakfast ', 22000, NULL, ' Fabulous breakfast included'),
(55, 51, ' Breakfast + Dinner', 25000, NULL, 'Breakfast & dinner included '),
(56, 52, ' Breakfast ', 28000, NULL, 'Breakfast included '),
(57, 53, ' Breakfast ', 26500, NULL, 'Breakfast  Includes'),
(58, 54, ' Breakfast ', 25000, NULL, 'Good Breakfast'),
(59, 55, ' Breakfast ', 28000, NULL, 'Good Breakfast'),
(60, 56, ' Breakfast ', 30000, NULL, 'Good Breakfast'),
(61, 60, ' Breakfast ', 35000, NULL, 'Fabulous breakfast included'),
(62, 59, ' Breakfast ', 32000, NULL, 'Fabulous breakfast included'),
(63, 63, ' Breakfast ', 40000, NULL, 'Fabulous breakfast included'),
(64, 64, ' Breakfast ', 43000, NULL, 'Fabulous breakfast included');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `booking_id`, `payment_method`, `amount`, `payment_status`, `created_at`) VALUES
(1, 43, 'Cash', 69300, 'Completed', '2026-03-22 05:34:17'),
(2, 28, 'Debit Card', 43524, 'Completed', '2026-03-25 04:33:28'),
(3, 28, 'Credit Card', 43524, 'Completed', '2026-03-25 04:33:56'),
(4, 28, 'Credit Card', 43524, 'Completed', '2026-03-25 04:40:26'),
(5, 30, 'Debit Card', 43500, 'Completed', '2026-03-25 04:44:16'),
(6, 30, 'Debit Card', 43500, 'Completed', '2026-03-25 04:45:41'),
(7, 31, 'Debit Card', 18060, 'Completed', '2026-03-25 04:54:39'),
(8, 33, 'Credit Card', 130500, 'Completed', '2026-03-25 05:21:12'),
(9, 34, 'Debit Card', 54200, 'Completed', '2026-03-25 05:25:42'),
(10, 35, 'Debit Card', 18060, 'Completed', '2026-03-25 05:38:11'),
(11, 36, 'Debit Card', 43500, 'Completed', '2026-03-25 05:43:22'),
(12, 38, 'Credit Card', 50019, 'Completed', '2026-03-25 07:08:07');

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `city_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `rating` float DEFAULT 0,
  `facility` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT 'Hotel',
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `room_type` varchar(100) DEFAULT NULL,
  `bed_type` varchar(100) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `breakfast_price` int(11) DEFAULT NULL,
  `room_size` varchar(20) DEFAULT NULL,
  `view_type` varchar(50) DEFAULT NULL,
  `available_rooms` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `facilities` text DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`id`, `hotel_id`, `room_type`, `bed_type`, `price`, `breakfast_price`, `room_size`, `view_type`, `available_rooms`, `image`, `facilities`, `description`) VALUES
(5, 5, 'Delexe', 'Family Room', 10000, 2500, '24', 'City View', 3, 'jkarishna.avif', 'TV,AC', NULL),
(8, 2, 'Premier Queen Room with Two Queen Beds', '2 large double beds', 16100, 1000, '42', 'Pool View', 9, 'msea.avif', 'Air conditioning,Ensuite bathroom,Flat-screen TV,Minibar,Free WiFi,Iron,Wake-up service,Elevator access', NULL),
(10, 2, 'Premier Queen Room with Two Queen Beds', '2 large double beds', 16100, 1000, '42', 'Pool View', 9, 'msea.avif', 'AC,WiFi,TV,Bathroom,Balcony', NULL),
(11, 1, 'Premier Queen Room with Two Queen Beds', '2 large double beds', 16100, 1000, '42', 'City View', 9, 'aranyaka.webp', 'AC,WiFi,Smart TV,Netflix,Balcony,Seating Area', ''),
(13, 6, 'Deluxe Double Room', '1 large double bed ', 6450, 500, '11', 'City View', 5, 'aDelex.jpg', 'AC,WiFi,TV,Bathroom,Balcony,Room Service,Coffee Maker', NULL),
(14, 6, 'Superior Double Room', '1 large double bed', 6020, 500, '11', 'City View', 6, 'aSuperior.jpg', 'AC,WiFi,TV,Bathroom,Balcony,Room Service,Breakfast Included,Air Purifier,Coffee Maker', NULL),
(15, 6, 'Triple Room', '1 single bed , 1 double bed ', 7310, 500, '14', 'City View', 8, 'aTriple.jpg', 'AC,WiFi,TV,Bathroom,Balcony,Room Service,Breakfast Included,Air Purifier', NULL),
(16, 7, 'Deluxe King Room City View', '1 extra-large double bed', 27608, 500, '37', 'Pool View', 10, 'lDelex.jpg', 'AC,WiFi,TV,Bathroom,Balcony,Room Service,Swimming Pool,Gym,Breakfast Included,Air Purifier,Coffee Maker,Coffee Maker', NULL),
(17, 7, 'Deluxe Twin Room City View', '2 double beds', 28648, 500, '37', 'Garden View', 8, 'lTwin.jpg', 'AC,WiFi,TV,Bathroom,Balcony,Mini Bar,Room Service,Swimming Pool,Gym,Laundry,Breakfast Included,Dinner Included,Air Purifier,Coffee Maker,Coffee Maker', NULL),
(18, 7, 'Superior Corner King Room with City View', '1 extra-large double bed', 32210, 740, '45', 'City View', 6, 'lCorner.jpg', 'AC,WiFi,TV,Bathroom,Balcony,Room Service,Gym,Laundry,Breakfast Included,Dinner Included,Air Purifier,Coffee Maker', NULL),
(19, 8, 'Superior room with Hill View', '1 King Bed or 2 Single Bed(s)', 17500, 600, '36', 'Hill View', 14, 'RSuperior.avif', 'AC,WiFi,Smart TV,Netflix,Balcony,Seating Area,Bathroom,Bathtub,Room Service,Gym,Spa,Safe Locker,No Smoking', NULL),
(20, 8, 'Deluxe room with Balcony', ' 1 King Bed or 2 Single Bed(s)', 21000, 450, '36', 'Hill View', 8, 'RDelex.avif', 'AC,WiFi,Netflix,Balcony,Seating Area,Bathroom,Bathtub,Room Service,Spa,Safe Locker,No Smoking', NULL),
(23, 9, 'Twin Room with Balcony and Garden View', '2 single beds', NULL, NULL, '50', 'Garden View', 8, 'gTwin.jpg', 'AC,WiFi,Smart TV,Balcony,Seating Area,Coffee Maker,Bathroom,Room Service,Gym,Spa,Parking,No Smoking', 'The spacious triple room offers air conditioning, a minibar, as well as a private bathroom boasting a walk-in shower and a bath. This triple room has a tea and coffee maker, a wardrobe, a flat-screen TV with cable channels and a balcony. The unit has 2 beds.'),
(24, 9, 'King Room with Balcony and Garden View', '1 extra-large double bed', NULL, NULL, '50', 'Garden View', 10, 'gKing.jpg', 'AC,WiFi,Netflix,Balcony,Seating Area,Bathroom,Bathtub,Room Service,Swimming Pool,Gym,Spa,Parking,Safe Locker,No Smoking', 'The spacious triple room features air conditioning, a minibar, as well as a private bathroom boasting a walk-in shower and a bath. This triple room has a tea and coffee maker, a wardrobe, a flat-screen TV with cable channels and a balcony. The unit has 1 bed.'),
(25, 10, 'Fairfield Deluxe Room with Balcony', '1 double bed', NULL, NULL, '23', 'City View', 15, 'gDelex.jpg', 'AC,WiFi,Netflix,Balcony,Seating Area,Bathroom,Bathtub,Laundry,Swimming Pool,Spa,Parking,Safe Locker,No Smoking', 'This air-conditioned double room includes a flat-screen TV with satellite channels, a private bathroom as well as a balcony. The unit offers 1 bed.'),
(26, 11, 'standard', '1 Double Room', NULL, NULL, '42', 'City View', 7, 'aranyaka.webp', 'AC,Balcony,Bathroom,Swimming Pool', 'wsedrfgth'),
(27, 12, 'Superior Double Room', '1 large double bed', NULL, NULL, '11', 'City View', 7, 'aSuperior.jpg', 'AC,WiFi,Smart TV,Balcony,Seating Area,Bathtub,Room Service,Gym,Parking,Safe Locker,No Smoking', 'This air-conditioned double room is comprised of a flat-screen TV with cable channels and a private bathroom. The unit has 1 bed.'),
(28, 12, 'Deluxe Double Room', '1 large double bed', NULL, NULL, '11', 'City View', 5, 'aDelex.jpg', 'AC,WiFi,Seating Area,Room Service,Laundry,Swimming Pool,Gym,Parking,No Smoking', 'This air-conditioned double room is comprised of a flat-screen TV with cable channels and a private bathroom. The unit has 1 bed.\r\n\r\n\r\n'),
(29, 12, 'Triple Room', '1 single bed  and 1 double bed', NULL, NULL, '14', 'City View', 10, 'aTriple.jpg', 'AC,WiFi,Smart TV,Balcony,Seating Area,Coffee Maker,Bathroom,Room Service,Laundry,Gym,Spa,Parking,No Smoking', 'This air-conditioned triple room is comprised of a flat-screen TV with cable channels and a private bathroom. The unit has 2 beds.\r\n\r\n\r\n'),
(30, 13, 'Twin Room with Balcony and Garden View', '2 single beds', NULL, NULL, '50', 'Garden View', 8, 'gTwin.jpg', 'AC,WiFi,Netflix,Seating Area,Bathroom,Parking,Safe Locker,No Smoking', 'The spacious triple room offers air conditioning, a minibar, as well as a private bathroom boasting a walk-in shower and a bath. This triple room has a tea and coffee maker, a wardrobe, a flat-screen TV with cable channels and a balcony. The unit has 2 beds.'),
(31, 13, 'King Room with Balcony and Garden View', '1 extra-large double bed', NULL, NULL, '50', 'Garden View', 15, 'gKing.jpg', 'AC,WiFi,Smart TV,Balcony,Seating Area,Coffee Maker,Bathtub,Room Service,Laundry,Gym,Spa,Parking,No Smoking', 'The spacious triple room features air conditioning, a minibar, as well as a private bathroom boasting a walk-in shower and a bath. This triple room has a tea and coffee maker, a wardrobe, a flat-screen TV with cable channels and a balcony. The unit has 1 bed.'),
(32, 14, 'Premium Queen Room with patio and pool view', '1 double bed', NULL, NULL, '23', 'Pool View', 11, 'fPremium.jpg', 'AC,Netflix,Balcony,Seating Area,Room Service,Gym,Spa,Parking,No Smoking', 'This double room features a pool with a view. This air-conditioned double room is comprised of a flat-screen TV with satellite channels, a private bathroom as well as a balcony with pool views. The unit has 1 bed.'),
(33, 15, 'Double Room', '1 double bed', NULL, NULL, '28', 'City View', 13, 'Rdouble.jpg', 'AC,WiFi,Smart TV,Seating Area,Mini Bar,Room Service,Laundry,Parking,No Smoking', 'Guests will have a special experience as the double room offers a fireplace. Providing free toiletries, this double room includes a private bathroom with a shower, a hairdryer and slippers. The spacious air-conditioned double room provides a flat-screen TV with cable channels, a minibar, a tea and coffee maker, a wardrobe as well as lake views. The unit offers 1 bed.'),
(34, 16, 'Deluxe Room Twin Bed', '1 single bed', NULL, NULL, '36', 'City View', 15, 'ALuxary.jpg', 'AC,WiFi,Smart TV,Balcony,Seating Area,Bathtub,Room Service,Gym,Spa,Parking,No Smoking', 'The spacious twin room provides air conditioning, a minibar, as well as a private bathroom featuring a shower. This twin room features a tea and coffee maker, a wardrobe, a TV and city views. The unit offers 1 bed.'),
(35, 16, 'Deluxe Room King Bed', '1 extra-large double bed', NULL, NULL, '36', 'City View', 20, 'TDelex.jpg', 'AC,WiFi,Smart TV,Balcony,Seating Area,Coffee Maker,Bathroom,Bathtub,Room Service,Laundry,Gym,Spa,Parking,Safe Locker,No Smoking', 'The spacious triple room offers air conditioning, a minibar, as well as a private bathroom featuring a shower. This triple room features a tea and coffee maker, a wardrobe, a TV and city views. The unit offers 1 bed.'),
(36, 17, 'Superior Twin Room with City View ', '2 single beds', NULL, NULL, '24', 'City View', 20, 'VSuperior.jpg', 'AC,WiFi,Smart TV,Balcony,Seating Area,Bathroom,Bathtub,Room Service,Laundry,Swimming Pool,Gym,Spa,Parking,Safe Locker,No Smoking', 'Providing free toiletries, this twin room includes a private bathroom with a bath, a shower and a hairdryer. The air-conditioned twin room provides a flat-screen TV with cable channels, a tea and coffee maker, a seating area, a wardrobe as well as city views. The unit offers 2 beds.'),
(37, 17, 'Premium Twin Room On Higher Floors With Welcome Amenities', '2 single beds', NULL, NULL, '25', 'City View', 15, 'VPrimum.jpg', 'AC,WiFi,Smart TV,Balcony,Bathtub,Room Service,Spa,No Smoking', 'Providing free toiletries, this twin room includes a private bathroom with a bath, a shower and a hairdryer. The air-conditioned twin room provides a flat-screen TV with streaming services, a tea and coffee maker, a seating area, a wardrobe as well as city views. The unit offers 2 beds.'),
(38, 17, 'Studio King Room with City View and Balcony', '1 extra-large double bed', NULL, NULL, '35', 'City View', 10, 'VStudio.jpg', 'AC,Netflix,Balcony,Seating Area,Mini Bar,Room Service,Laundry,Swimming Pool,Gym,Spa,Parking,Safe Locker,No Smoking', 'The spacious double room provides air conditioning, a tea and coffee maker and a balcony. The unit offers 1 bed.'),
(39, 18, 'Deluxe Double Room', '1 double bed', NULL, NULL, '31', 'City View', 14, 'SDouble.jpg', 'AC,WiFi,Netflix,Balcony,Seating Area,Mini Bar,Bathtub,Room Service,Laundry,Swimming Pool,Gym,Spa,Parking,No Smoking', 'Featuring free toiletries, this double room includes a private bathroom with a shower, a hairdryer and slippers. The double room features air conditioning, soundproof walls, a dining area, a wardrobe and a flat-screen TV with cable channels. The unit has 1 bed.'),
(40, 18, 'Large Double or Twin Room', '1 double bed and1 futon bed', NULL, NULL, '17', 'City View', 12, 'SLarge.jpg', 'AC,WiFi,Smart TV,Seating Area,Bathroom,Bathtub,Room Service,Gym,Spa', 'Providing free toiletries, this twin/double room includes a private bathroom with a shower, a hairdryer and slippers. The twin/double room provides air conditioning, soundproof walls, a tea and coffee maker, a dining area and a flat-screen TV with cable channels. The unit offers 1 bed and 1 futon.'),
(41, 19, 'Standard Room', 'Comfy bed', NULL, NULL, '20', 'City View', 20, 'NStandard.jpg', 'AC,WiFi,Smart TV,Balcony,Mini Bar,Bathtub,Room Service,Laundry,Swimming Pool,Gym,Spa,Parking,No Smoking', 'Providing free toiletries and bathrobes, this triple room includes a private bathroom with a bath, a hairdryer and slippers. The air-conditioned triple room provides a flat-screen TV with cable channels, a minibar, a tea and coffee maker and a wardrobe'),
(42, 19, 'Superior Room - Valley View', 'Comfy bed', NULL, NULL, '20', 'Garden View', 20, 'Nvally.jpg', 'AC,WiFi,Netflix,Seating Area,Bathroom,Room Service,Laundry,Gym,Parking,Safe Locker,No Smoking', 'Providing free toiletries and bathrobes, this triple room includes a private bathroom with a bath, a hairdryer and slippers. The air-conditioned triple room provides a flat-screen TV with cable channels, a minibar, a tea and coffee maker, a wardrobe as well as garden views.'),
(43, 19, 'Premium Valley View Room - Balcony ', '1 extra-large double bed', NULL, NULL, '26', 'Garden View', 20, 'NPremium.jpg', 'AC,WiFi,Netflix,Seating Area,Coffee Maker,Bathroom,Room Service,Laundry,Swimming Pool,Gym,Spa,Safe Locker,No Smoking', 'This air-conditioned triple room includes a flat-screen TV with cable channels, a private bathroom as well as a balcony with garden views. The unit offers 1 bed.'),
(44, 19, 'Family Room - Valley View', '1 extra-large double bed', NULL, NULL, '26', 'Hill View', 15, 'NFamily.jpg', 'AC,WiFi,Netflix,Balcony,Mini Bar,Coffee Maker,Bathtub,Room Service,Laundry,Swimming Pool,Gym,Spa,Parking,Safe Locker,No Smoking', 'Featuring free toiletries and bathrobes, this quadruple room includes a private bathroom with a bath, a hairdryer and slippers. The air-conditioned quadruple room features a flat-screen TV with cable channels, a minibar, a tea and coffee maker, a wardrobe as well as garden views. The unit has 1 bed.'),
(45, 20, 'Luxury Garden View', '1 extra-large double bed', NULL, NULL, '37', 'Garden View', 14, 'lLuxury.jpg', 'WiFi,Smart TV,Coffee Maker,Bathroom,Bathtub,Spa,Safe', NULL),
(46, 20, 'Presidential Suite with two way airport transfer', '1 extra-large double bed and 2 sofa beds', NULL, NULL, '110', 'Airport View', 14, 'lPresidential.jpg', 'AC,WiFi,Smart TV,Mini Bar,Coffee Maker,Room Service,Spa,Safe,No Smoking', NULL),
(47, 20, 'Royal Suite with two way airport transfer', '1 extra-large double bed', NULL, NULL, '55', 'Airport View', 12, 'lRoyel.jpg', 'AC,WiFi,Smart TV,Mini Bar,Bathroom,Room Service,Laundry,Gym,Parking,Safe,No Smoking', NULL),
(48, 21, 'Winter Green Cottage', '1 large double bed', NULL, NULL, '32', 'Garden View', 14, 'fWinter.jpg', 'AC,WiFi,Smart TV,Seating Area,Bathroom,Swimming Pool,Gym,Safe,No Smoking', NULL),
(49, 21, 'Fern Club Villa', '1 large double bed', NULL, NULL, '44', 'Garden View', 15, 'fFern.jpg', 'AC,WiFi,Netflix,Balcony,Seating Area,Coffee Maker,Bathroom,Room Service,Swimming Pool,Gym,No Smoking', NULL),
(50, 22, 'Superior Room', '1 extra-large double bed', NULL, NULL, '20', 'Garden View', 10, 'jSupirior.jpg', 'AC,WiFi,Netflix,Balcony,Seating Area,Bathroom,Swimming Pool,Gym,Spa,Parking,Safe,No Smoking', NULL),
(51, 22, 'Deluxe Room', '1 extra-large double bed', NULL, NULL, '31', 'Garden View', 25, 'rDelex.jpg', 'AC,WiFi,Balcony,Room Service,Laundry,Swimming Pool,Gym,Parking,Safe,No Smoking', NULL),
(52, 22, 'Super Deluxe Room', '1 extra-large double bed', NULL, NULL, '40', 'Garden View', 20, 'rSuper.jpg', 'AC,WiFi,Balcony,Seating Area,Coffee Maker,Bathroom,Bathtub,Room Service,Gym,Safe,No Smoking', NULL),
(53, 22, 'Junior Suite', 'Comfy beds', NULL, NULL, '45', 'Garden View', 18, 'rJunior.jpg', 'AC,WiFi,Netflix,Seating Area,Coffee Maker,Bathroom,Bathtub,Room Service,Laundry,Swimming Pool,Safe,No Smoking', NULL),
(54, 23, 'Deluxe- Garden View Double', '1 large double bed', NULL, NULL, '27', 'Hill View', 15, 'MDele.jpg', 'AC,WiFi,Balcony,Seating Area,Coffee Maker,Bathroom,Room Service,Swimming Pool,Safe,No Smoking', NULL),
(55, 23, 'Superior - Brookside Double', '1 large double bed', NULL, NULL, '34', 'Garden View', 19, 'MStories.jpg', 'AC,WiFi,Netflix,Balcony,Seating Area,Coffee Maker,Bathroom,Room Service,Swimming Pool,Parking,Safe,No Smoking', NULL),
(56, 23, 'Family Room with Balcony', '1 large double bed', NULL, NULL, '20', 'Garden View', 16, 'Mfamily.jpg', 'AC,WiFi,Netflix,Balcony,Seating Area,Coffee Maker,Bathroom,Swimming Pool,Spa,Parking,No Smoking', NULL),
(57, 23, 'Premium - Rohtang Duplex Twin', '2 single beds', NULL, NULL, '50', 'Hill View', 13, 'MPrimum.jpg', 'AC,Balcony,Bathroom,Bathtub,Room Service,Swimming Pool,Gym,Parking,Safe,No Smoking', NULL),
(58, 23, 'Himalayan Suite', '1 large double bed ', NULL, NULL, '44', 'Hill View', 25, 'MHimaliya.jpg', 'AC,WiFi,Balcony,Seating Area,Bathroom,Room Service,Swimming Pool,Gym,Parking,Safe,No Smoking', NULL),
(59, 24, 'Deluxe Room with Balcony', '1 extra-large double bed', NULL, NULL, '19', 'Hill View', 12, 'MDelexe.jpg', 'AC,WiFi,Balcony,Coffee Maker,Bathroom,Room Service,Swimming Pool,Parking,No Smoking', NULL),
(60, 24, 'Superior Room with Balcony', '1 extra-large double bed', NULL, NULL, '20', 'Garden View', 20, 'MSupirior.jpg', 'AC,WiFi,Netflix,Balcony,Seating Area,Coffee Maker,Bathroom,Room Service,Swimming Pool,Spa,No Smoking', NULL),
(61, 24, 'King Room with Balcony', '1 extra-large double bed', NULL, NULL, '21', 'Hill View', 20, 'MKIng.jpg', 'AC,WiFi,Smart TV,Balcony,Seating Area,Coffee Maker,Bathroom,Room Service,Swimming Pool,Parking,Safe,No Smoking', NULL),
(62, 24, 'King Quad Attic with Balcony & Jacuzzi', '2 extra-large double bed', NULL, NULL, '23', 'Hill View', 15, 'MQuter.jpg', 'AC,WiFi,Smart TV,Balcony,Seating Area,Coffee Maker,Bathroom,Room Service,Swimming Pool,Parking,Safe,No Smoking', NULL),
(63, 25, 'King Room with Mountain View', '2 single beds', NULL, NULL, '34', 'Hill View', 20, 'LKing.jpg', 'AC,WiFi,Smart TV,Balcony,Seating Area,Coffee Maker,Bathroom,Room Service,Swimming Pool,Safe,No Smoking', NULL),
(64, 25, 'Suite with Balcony', '1 large double bed', NULL, NULL, '45', 'Hill View', 12, 'LSuit.jpg', 'Netflix,Balcony,Seating Area,Coffee Maker,Bathroom,Room Service,Gym,Safe,No Smoking', NULL),
(65, 26, 'One-Bedroom Apartment', '1 double bed', NULL, NULL, '125', 'Hill View', 15, 'Azoe.jpg', 'AC,WiFi,Netflix,Balcony,Seating Area,Coffee Maker,Laundry,Swimming Pool,Safe,No Smoking', NULL),
(66, 27, 'Deluxe Double Room', '1 double bed', NULL, NULL, '320', 'Sea View', 10, 'K.jpg', 'AC,Smart TV,Balcony,Seating Area,Bathroom,No Smoking', NULL),
(67, 28, 'family room', '2 double rom', NULL, NULL, '23', 'Hill View', 13, 'aDelex.jpg', 'AC,Room Service,No Smoking', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `room_type` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `total_rooms` int(11) NOT NULL,
  `available_rooms` int(11) NOT NULL,
  `status` enum('available','unavailable') DEFAULT 'available',
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_amenities`
--

CREATE TABLE `room_amenities` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `amenity_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_images`
--

CREATE TABLE `room_images` (
  `id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_packages`
--

CREATE TABLE `room_packages` (
  `id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `package_name` varchar(100) DEFAULT NULL,
  `extra_price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `icon`, `status`, `created_at`) VALUES
(1, 'qwasde', '', 'Inactive', '2026-02-26 09:22:57'),
(2, 'qwasde', '', 'Active', '2026-02-26 09:24:17');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `state_name` varchar(100) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `state_name`, `status`) VALUES
(1, 'Maharashtra', 'active'),
(2, 'Gujarat', 'active'),
(3, 'Jammu', 'active'),
(4, 'Himachal', 'active'),
(5, 'Kerala', 'active'),
(6, 'Punjab', 'active'),
(7, 'Rajasthan', 'active'),
(8, 'Uttarakhand', 'active'),
(9, 'Goa', 'active'),
(10, 'Ladakh', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','manager','user') NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(1, 'Admin', 'admin@gmail.com', '$2y$10$VjcvwXJvD6M7x5LmeqY4J.P/tOkaestXs.opTUGRsC9v6nqz8G7lW', 'admin', 'active', '2026-03-08 11:09:29'),
(2, 'Yasvi', 'yasvi@gmail.com', '$2y$10$U1gfbVYOFd.F7BXTzcOpJeOhLZ0FG6qdnFM87DENB1Y.kAb4g.T5a', 'user', 'active', '2026-03-08 11:21:07'),
(3, 'yashvi', 'yashvi@gmail.com', '$2y$10$2Ti/.D4iE0TpaLX4m0HY2ejU9uTaQ38hRaRyrggsjw1aosJCzYqJa', 'user', 'active', '2026-03-08 11:25:37'),
(4, 'yasvi', 'yashv1@gmail.com', '$2y$10$.VEPNgQIQ04Gg7/CzyE.dOHx9oN3EU1EDCWs0vsrtAArMXBS7szZC', 'user', 'active', '2026-03-10 16:54:28'),
(7, 'mansvi', 'manasvi@gmail.com', '$2y$10$jCKi.3VKzq3nu9.oW4hS7O7Pr6AV21Sbxwf27AnUWrCnWHFAXecTu', 'user', 'active', '2026-03-19 11:45:35'),
(8, 'Tisha', 'tisha@gmail.com', '$2y$10$0vZnk9AjKTHFcxG9WCMrueO/kzl4Vn6UwTi28rNKpYGYDEyXRkuia', 'user', 'active', '2026-03-20 05:30:57'),
(9, 'Maitrik', 'maitrik@gmail.com', '$2y$10$gtJURxSPmQjId9VGFu8c5.i6CsgBblUhV.6TXNck4oZmENvaSMkEC', 'user', 'active', '2026-03-22 15:19:38'),
(10, 'Blesi Diyora', 'blesi@gmail.com', '$2y$10$gMB30Hv8OZBq8zTsTs/FJ.AuKuto79kKH05n0MS6iLBi0vMIIX7ty', 'user', 'active', '2026-03-24 08:36:26'),
(11, 'Panchi', 'panchi@gmail.com', '$2y$10$PC16pyxYpt9poUeB5ZTbHOsXTwSFcaudzM9aNR/WuBKqBL9q1CFAO', 'user', 'active', '2026-03-25 00:52:18'),
(12, 'Kritika', 'kritika@gmail.com', '$2y$10$hGtqPdUmSYbzkKBfdeKQdO9LOp3dcDy.CfF/otmskFqyAGghz0iaC', 'user', 'active', '2026-03-25 03:30:59'),
(13, 'Ruhi', 'ruhi@gmail.com', '$2y$10$3pISaFKXBUtbgNCuJWrhXOrz1PlCAwa5SWxjtF43RBI4C6XMA7QYC', 'user', 'active', '2026-03-25 04:32:03'),
(14, 'Ved Diyora', 'ved1@gmail.com', '$2y$10$/eaJLgOXYkLKy07x1Xw3D.shq.Qd5plgCDACWqtjJ4oRtiNRHK49m', 'user', 'active', '2026-03-25 05:24:01');

-- --------------------------------------------------------

--
-- Table structure for table `user_rooms`
--

CREATE TABLE `user_rooms` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `facility` text DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ashy_images`
--
ALTER TABLE `ashy_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `state_id` (`state_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `haridwar_reviews`
--
ALTER TABLE `haridwar_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotel`
--
ALTER TABLE `hotel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_state` (`state_id`),
  ADD KEY `fk_city` (`city_id`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `manager_id` (`manager_id`),
  ADD KEY `state_id` (`state_id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `hotel_images`
--
ALTER TABLE `hotel_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manager`
--
ALTER TABLE `manager`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `state_id` (`state_id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `manager_details`
--
ALTER TABLE `manager_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `state_id` (`state_id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `room_amenities`
--
ALTER TABLE `room_amenities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `amenity_id` (`amenity_id`);

--
-- Indexes for table `room_images`
--
ALTER TABLE `room_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room_packages`
--
ALTER TABLE `room_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_state_name` (`state_name`);

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
-- AUTO_INCREMENT for table `amenities`
--
ALTER TABLE `amenities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `bill`
--
ALTER TABLE `bill`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `haridwar_reviews`
--
ALTER TABLE `haridwar_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hotel`
--
ALTER TABLE `hotel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `hotel_images`
--
ALTER TABLE `hotel_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `manager`
--
ALTER TABLE `manager`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `manager_details`
--
ALTER TABLE `manager_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `room_amenities`
--
ALTER TABLE `room_amenities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `room_images`
--
ALTER TABLE `room_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `room_packages`
--
ALTER TABLE `room_packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`);

--
-- Constraints for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  ADD CONSTRAINT `booking_rooms_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_rooms_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_city_state` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`);

--
-- Constraints for table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotel` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`hotel_id`) REFERENCES `hotel` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel`
--
ALTER TABLE `hotel`
  ADD CONSTRAINT `fk_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  ADD CONSTRAINT `fk_state` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`);

--
-- Constraints for table `hotels`
--
ALTER TABLE `hotels`
  ADD CONSTRAINT `hotels_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hotels_ibfk_2` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`),
  ADD CONSTRAINT `hotels_ibfk_3` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`);

--
-- Constraints for table `managers`
--
ALTER TABLE `managers`
  ADD CONSTRAINT `managers_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`),
  ADD CONSTRAINT `managers_ibfk_2` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`);

--
-- Constraints for table `manager_details`
--
ALTER TABLE `manager_details`
  ADD CONSTRAINT `manager_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `manager_details_ibfk_2` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`),
  ADD CONSTRAINT `manager_details_ibfk_3` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotel` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `room_amenities`
--
ALTER TABLE `room_amenities`
  ADD CONSTRAINT `room_amenities_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_amenities_ibfk_2` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `room_packages`
--
ALTER TABLE `room_packages`
  ADD CONSTRAINT `room_packages_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
