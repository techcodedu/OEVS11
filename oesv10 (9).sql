-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2023 at 04:39 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oesv10`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessment_applications`
--

CREATE TABLE `assessment_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `school_training_center_company` varchar(255) NOT NULL,
  `assessment_title` varchar(255) NOT NULL,
  `application_type` enum('full_qualification','COC','renewal') NOT NULL,
  `client_type` enum('TVET_graduating_student','TVET_graduate','industry_worker','K12','OFW') NOT NULL,
  `surname` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `applicant_address` varchar(255) NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `civil_status` enum('single','married','divorced','widowed') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('pending','scheduled','completed','failed') NOT NULL DEFAULT 'pending',
  `application_number` varchar(255) DEFAULT NULL,
  `cancellation_status` varchar(255) DEFAULT NULL,
  `viewed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assessment_applications`
--

INSERT INTO `assessment_applications` (`id`, `user_id`, `course_id`, `school_training_center_company`, `assessment_title`, `application_type`, `client_type`, `surname`, `first_name`, `middle_name`, `applicant_address`, `gender`, `civil_status`, `created_at`, `updated_at`, `status`, `application_number`, `cancellation_status`, `viewed`) VALUES
(16, 12, 21, 'Algeadfadf', 'Computer System Servicing NCII', 'full_qualification', 'TVET_graduating_student', 'Median', 'Karen', 'ferr', '4234234', 'male', 'single', '2023-04-15 21:51:55', '2023-04-18 03:08:41', 'pending', 'COM-00001', 'Cancelled', 1);

-- --------------------------------------------------------

--
-- Table structure for table `assessment_schedules`
--

CREATE TABLE `assessment_schedules` (
  `id` int(10) UNSIGNED NOT NULL,
  `assessment_application_id` bigint(20) UNSIGNED NOT NULL,
  `scheduled_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assessment_schedules`
--

INSERT INTO `assessment_schedules` (`id`, `assessment_application_id`, `scheduled_date`, `created_at`, `updated_at`) VALUES
(10, 16, '2023-04-21', '2023-04-15 21:52:29', '2023-04-15 21:52:29');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `category_id`, `created_at`, `updated_at`) VALUES
(27, 'ICT', 'Information Communication Technology', NULL, '2023-04-06 09:14:05', '2023-04-06 09:14:05'),
(28, 'Culinary Arts', 'Culinary', NULL, '2023-04-06 09:16:23', '2023-04-06 09:16:23');

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `enrollment_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `instructor_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `training_hours` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `name`, `description`, `image`, `category_id`, `instructor_id`, `price`, `created_at`, `updated_at`, `training_hours`) VALUES
(21, 'Computer System Servicing NCII', 'The COMPUTER SYSTEMS SERVICING NC II Qualification consists of competenciesthat must possess to enable to install and configure computers systems, set-up computer networks and servers and to maintain and repair computer systems and networks.', 'courses/1680802233.png', 27, 15, '37000.00', '2023-04-06 09:15:28', '2023-04-06 09:30:33', 285),
(22, 'Cookery NC II', 'The COOKERY NC II Qualification consists of competencies that a personmust achieve to clean kitchen areas, prepare hot, cold meals and desserts for guests in various food and beverage service facilities', 'courses/1680802241.png', 28, 15, '12000.00', '2023-04-06 09:17:26', '2023-04-06 09:30:41', 316);

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `enrollment_type` enum('scholarship','regular_training','assessment') DEFAULT 'regular_training',
  `status` enum('inReview','inProgress','enrolled') NOT NULL DEFAULT 'inReview',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cancellation_status` varchar(255) DEFAULT NULL,
  `viewed` tinyint(1) DEFAULT 0,
  `scholarship_grant` varchar(255) DEFAULT NULL,
  `feedback` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `user_id`, `course_id`, `enrollment_type`, `status`, `created_at`, `updated_at`, `cancellation_status`, `viewed`, `scholarship_grant`, `feedback`) VALUES
(105, 10, 22, 'scholarship', 'enrolled', '2023-04-15 21:42:53', '2023-04-16 06:20:40', NULL, 1, 'STEP', NULL),
(106, 12, 22, 'scholarship', 'inReview', '2023-04-15 21:49:02', '2023-04-16 00:38:39', 'Cancelled', 1, 'PESFA', 'aadfadfadfadf'),
(107, 12, 22, 'regular_training', 'inReview', '2023-04-16 00:38:48', '2023-04-18 03:08:45', 'Cancelled', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `enrollment_documents`
--

CREATE TABLE `enrollment_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `enrollment_id` bigint(20) UNSIGNED NOT NULL,
  `document_type` enum('otr','birth_certificate','marriage_certificate') DEFAULT 'otr',
  `path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enrollment_documents`
--

INSERT INTO `enrollment_documents` (`id`, `name`, `enrollment_id`, `document_type`, `path`, `created_at`, `updated_at`) VALUES
(113, 'otr', 105, 'otr', 'enrollment/105/mobile-app.pdf', '2023-04-15 21:43:20', '2023-04-15 21:43:20'),
(114, 'birth_certificate', 105, 'birth_certificate', 'enrollment/105/Fixed ASAP before defense on MOnday.pdf', '2023-04-15 21:43:20', '2023-04-15 21:43:20'),
(115, 'marriage_certificate', 105, 'marriage_certificate', 'enrollment/105/Test3.pdf', '2023-04-15 21:43:20', '2023-04-15 21:43:20'),
(116, 'otr', 106, 'otr', 'enrollment/106/Fixed ASAP before defense on MOnday.pdf', '2023-04-15 21:49:30', '2023-04-15 21:49:30'),
(117, 'birth_certificate', 106, 'birth_certificate', 'enrollment/106/Fixed ASAP before defense on MOnday.pdf', '2023-04-15 21:49:30', '2023-04-15 21:49:30'),
(118, 'marriage_certificate', 106, 'marriage_certificate', 'enrollment/106/COE WEBDEV.pdf', '2023-04-15 21:49:30', '2023-04-15 21:49:30'),
(119, 'otr', 107, 'otr', 'enrollment/107/Fixed ASAP before defense on MOnday.pdf', '2023-04-16 00:39:28', '2023-04-16 00:39:28'),
(120, 'birth_certificate', 107, 'birth_certificate', 'enrollment/107/Fixed ASAP before defense on MOnday.pdf', '2023-04-16 00:39:28', '2023-04-16 00:39:28'),
(121, 'marriage_certificate', 107, 'marriage_certificate', 'enrollment/107/Fixed ASAP before defense on MOnday.pdf', '2023-04-16 00:39:28', '2023-04-16 00:39:28');

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `area_of_field` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`id`, `name`, `bio`, `image`, `created_at`, `updated_at`, `area_of_field`) VALUES
(15, 'Ian B. Galutira', 'Nothing to display here', 'instructor/642ef791025fe/642ef791025fe.jpg', '2023-04-06 08:47:13', '2023-04-06 08:48:00', 'Electronics');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `enrollment_id` bigint(20) UNSIGNED NOT NULL,
  `payment_method` enum('GCASH','over_the_counter','bank_transfer') NOT NULL,
  `payment_schedule` enum('weekly_installment','last_day_one_time') NOT NULL,
  `registration_is_paid` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `enrollment_id`, `payment_method`, `payment_schedule`, `registration_is_paid`, `created_at`, `updated_at`) VALUES
(3, 12, 107, 'over_the_counter', 'last_day_one_time', NULL, '2023-04-16 00:39:13', '2023-04-17 23:17:48'),
(4, 10, 105, 'GCASH', 'weekly_installment', NULL, '2023-04-16 01:23:23', '2023-04-16 01:23:23');

-- --------------------------------------------------------

--
-- Table structure for table `payment_histories`
--

CREATE TABLE `payment_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date_paid` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_histories`
--

INSERT INTO `payment_histories` (`id`, `payment_id`, `amount`, `date_paid`, `created_at`, `updated_at`) VALUES
(1, 3, '500.00', '2023-04-06 00:00:00', '2023-04-16 09:32:59', '2023-04-16 09:32:59'),
(2, 3, '600.00', '2023-04-20 17:53:00', '2023-04-16 09:49:59', '2023-04-16 09:49:59'),
(3, 3, '600.00', '2023-04-22 00:11:00', '2023-04-17 20:07:40', '2023-04-17 20:07:40'),
(4, 3, '8000.00', '2023-04-18 17:12:00', '2023-04-17 20:07:58', '2023-04-17 20:07:58');

-- --------------------------------------------------------

--
-- Table structure for table `personal_information`
--

CREATE TABLE `personal_information` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `enrollment_id` bigint(20) UNSIGNED NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `currently_schooling` enum('yes','no') DEFAULT NULL,
  `employment_status` enum('employed','unemployed','self-employed','student','retired','homemaker','other') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_information`
--

INSERT INTO `personal_information` (`id`, `enrollment_id`, `fullname`, `address`, `age`, `contact_number`, `facebook`, `currently_schooling`, `employment_status`, `created_at`, `updated_at`) VALUES
(90, 105, 'Ryan Rosario', '333 aDummy Address', 34, '0945795288', NULL, 'yes', 'unemployed', '2023-04-15 21:43:01', '2023-04-15 21:43:01'),
(91, 106, 'Karen Median', '3434 dummy', 23, '0945795288', 'afadf', 'no', 'unemployed', '2023-04-15 21:49:13', '2023-04-15 21:49:13'),
(92, 107, 'Karen Median', '344 Bonuan Boquig Dagupan', 34, '09157894456', 'NA', 'yes', 'unemployed', '2023-04-16 00:39:09', '2023-04-16 00:39:09');

-- --------------------------------------------------------

--
-- Table structure for table `qualifications`
--

CREATE TABLE `qualifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `instructor_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `qualifications`
--

INSERT INTO `qualifications` (`id`, `instructor_id`, `title`, `created_at`, `updated_at`) VALUES
(43, 15, 'Computer Systems Servicing NC II', '2023-04-06 08:48:00', '2023-04-06 08:48:00'),
(44, 15, 'Programming NC IV', '2023-04-06 08:48:00', '2023-04-06 08:48:00');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_assessments_schedule`
--

CREATE TABLE `student_assessments_schedule` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `enrollment_id` bigint(20) UNSIGNED NOT NULL,
  `schedule_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `remarks` enum('Competent','Not Competent') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_assessments_schedule`
--

INSERT INTO `student_assessments_schedule` (`id`, `enrollment_id`, `schedule_date`, `created_at`, `updated_at`, `remarks`) VALUES
(107, 105, '2023-04-22 00:00:00', '2023-04-17 03:46:49', '2023-04-17 06:11:35', 'Competent'),
(108, 107, '2023-04-22 00:00:00', '2023-04-17 03:46:49', '2023-04-17 20:08:16', 'Competent');

-- --------------------------------------------------------

--
-- Table structure for table `training_schedules`
--

CREATE TABLE `training_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `enrollment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `training_schedules`
--

INSERT INTO `training_schedules` (`id`, `enrollment_id`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(26, 105, '2023-04-14', '2023-04-28', '2023-04-16 18:12:35', '2023-04-16 18:12:35'),
(27, 107, '2023-04-14', '2023-04-28', '2023-04-16 18:12:35', '2023-04-16 18:12:35');

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
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','student','inactive_student') DEFAULT 'student',
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `avatar`) VALUES
(1, 'Administrator', 'admin@example.com', NULL, '$2y$10$foAPVN5PcTFKefSN0NUqC.n826Yt/Nvw0bknpSEVkgW3qMKyZ0046', NULL, NULL, '2023-04-16 22:37:37', 'admin', NULL),
(10, 'Ryan Rosario', 'kcbautista06@gmail.com', NULL, '$2y$10$I2MUl3.bFVlB.ZEEDK6DSOcyuyEL5JIcjojHFs0eb5w/l37aOYN.6', NULL, '2023-04-06 09:31:39', '2023-04-10 17:16:15', 'student', NULL),
(11, 'Brian Viloria', 'brian@mail.com', NULL, '$2y$10$sjPiF.QE/b8aA8O.7Em6COhC.axxz.5eybBXzGCWFXbh6XKspfKzS', NULL, '2023-04-10 04:59:30', '2023-04-10 23:41:36', 'student', NULL),
(12, 'Karen Median', 'techcode.edu@gmail.com', NULL, '$2y$10$OSxT2mCM/o0EK0LBsGYUVub4jLZrZQEsgGsSo7Yi3vqjRq7rsnutS', 'Rnais09npCd2Ebrale6FbasgRcSIp5WxGzhjpPzD4WNNgl4NhI7vXmt1Pfql', '2023-04-15 17:23:42', '2023-04-18 06:37:54', 'student', '12_1681828674.PNG'),
(13, 'chester', 'chester@gmail.com', NULL, '', NULL, NULL, NULL, 'admin', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessment_applications`
--
ALTER TABLE `assessment_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `assessment_schedules`
--
ALTER TABLE `assessment_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assessment_application_id` (`assessment_application_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `certificates_user_id_foreign` (`user_id`),
  ADD KEY `certificates_enrollment_id_foreign` (`enrollment_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courses_category_id_foreign` (`category_id`),
  ADD KEY `courses_instructor_id_foreign` (`instructor_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enrollments_user_id_foreign` (`user_id`),
  ADD KEY `enrollments_course_id_foreign` (`course_id`);

--
-- Indexes for table `enrollment_documents`
--
ALTER TABLE `enrollment_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enrollment_documents_enrollment_id_foreign` (`enrollment_id`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_user_id_foreign` (`user_id`),
  ADD KEY `payments_enrollment_id_foreign` (`enrollment_id`);

--
-- Indexes for table `payment_histories`
--
ALTER TABLE `payment_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_id` (`payment_id`);

--
-- Indexes for table `personal_information`
--
ALTER TABLE `personal_information`
  ADD PRIMARY KEY (`id`),
  ADD KEY `personal_information_enrollment_id_foreign` (`enrollment_id`);

--
-- Indexes for table `qualifications`
--
ALTER TABLE `qualifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qualifications_instructor_id_foreign` (`instructor_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_user_role_id_foreign` (`role_id`),
  ADD KEY `role_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `student_assessments_schedule`
--
ALTER TABLE `student_assessments_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enrollment_id` (`enrollment_id`);

--
-- Indexes for table `training_schedules`
--
ALTER TABLE `training_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enrollment_id` (`enrollment_id`);

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
-- AUTO_INCREMENT for table `assessment_applications`
--
ALTER TABLE `assessment_applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `assessment_schedules`
--
ALTER TABLE `assessment_schedules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `enrollment_documents`
--
ALTER TABLE `enrollment_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payment_histories`
--
ALTER TABLE `payment_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `personal_information`
--
ALTER TABLE `personal_information`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `qualifications`
--
ALTER TABLE `qualifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_assessments_schedule`
--
ALTER TABLE `student_assessments_schedule`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `training_schedules`
--
ALTER TABLE `training_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assessment_applications`
--
ALTER TABLE `assessment_applications`
  ADD CONSTRAINT `assessment_applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `assessment_applications_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Constraints for table `assessment_schedules`
--
ALTER TABLE `assessment_schedules`
  ADD CONSTRAINT `assessment_schedules_ibfk_1` FOREIGN KEY (`assessment_application_id`) REFERENCES `assessment_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`),
  ADD CONSTRAINT `certificates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `courses_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`);

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `enrollments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `enrollment_documents`
--
ALTER TABLE `enrollment_documents`
  ADD CONSTRAINT `enrollment_documents_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment_histories`
--
ALTER TABLE `payment_histories`
  ADD CONSTRAINT `payment_histories_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`);

--
-- Constraints for table `personal_information`
--
ALTER TABLE `personal_information`
  ADD CONSTRAINT `personal_information_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`);

--
-- Constraints for table `qualifications`
--
ALTER TABLE `qualifications`
  ADD CONSTRAINT `qualifications_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `student_assessments_schedule`
--
ALTER TABLE `student_assessments_schedule`
  ADD CONSTRAINT `student_assessments_schedule_ibfk_1` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`);

--
-- Constraints for table `training_schedules`
--
ALTER TABLE `training_schedules`
  ADD CONSTRAINT `training_schedules_ibfk_1` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
