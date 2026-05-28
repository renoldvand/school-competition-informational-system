-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 28, 2026 at 04:48 AM
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
-- Database: `db_scis`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins_table`
--

CREATE TABLE `admins_table` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `created_on` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins_table`
--

INSERT INTO `admins_table` (`id`, `username`, `password`, `created_on`) VALUES
(1, 'admin', 'admin123', '2026-05-26 20:43:12');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` varchar(20) NOT NULL,
  `major` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_table`
--

CREATE TABLE `class_table` (
  `class_id` varchar(20) NOT NULL,
  `major_id` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_table`
--

INSERT INTO `class_table` (`class_id`, `major_id`) VALUES
('X DKV 1', 'DKV'),
('X DKV 2', 'DKV'),
('X RPL 1', 'RPL'),
('X RPL 2', 'RPL'),
('X TKJ 1', 'TKJ'),
('X TKJ 2', 'TKJ');

-- --------------------------------------------------------

--
-- Table structure for table `comps`
--

CREATE TABLE `comps` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `major` varchar(10) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `overseer` text DEFAULT NULL,
  `thumbnail_path` text DEFAULT NULL,
  `icon_path` text DEFAULT NULL,
  `created_on` datetime DEFAULT current_timestamp(),
  `starts_on` date NOT NULL,
  `ends_on` date NOT NULL,
  `is_open` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comps`
--

INSERT INTO `comps` (`id`, `title`, `major`, `description`, `overseer`, `thumbnail_path`, `icon_path`, `created_on`, `starts_on`, `ends_on`, `is_open`) VALUES
(1, 'Lomba Web Design Tingkat Kota', 'RPL', 'Lomba desain dan pengembangan website untuk siswa SMK se-Kota Denpasar. Peserta ditugaskan membuat website responsif dengan tema pendidikan. Penilaian meliputi aspek desain UI/UX, fungsi, aksesibilitas, dan kreativitas. Setiap sekolah boleh mengirimkan maksimal 3 tim, masing-masing terdiri dari 2 siswa.', 'Pak Wayan Gede', NULL, NULL, '2026-05-05 14:14:08', '2026-03-01', '2026-03-15', 1),
(2, 'Kompetisi Jaringan Komputer Provinsi', 'TKJ', 'Kompetisi konfigurasi dan troubleshooting jaringan komputer tingkat provinsi Bali. Peserta harus mampu melakukan konfigurasi router, switch, VLAN, dan menyelesaikan masalah jaringan dalam waktu 3 jam. Materi mencakup subnetting, routing protocol, dan keamanan jaringan dasar.', 'Bu Made Sari', NULL, NULL, '2026-05-05 14:14:08', '2026-02-20', '2026-02-28', 0),
(3, 'Festival Desain Grafis Digital', 'DKV', 'Festival desain grafis dengan tema \"Budaya Bali di Era Digital\". Peserta membuat poster digital dan identitas visual yang menggambarkan perpaduan budaya tradisional Bali dengan teknologi modern. Karya dinilai oleh dosen ISI Denpasar dan praktisi industri kreatif.', 'Pak Ketut Artana', NULL, NULL, '2026-05-05 14:14:08', '2026-04-01', '2026-04-20', 0),
(4, 'Hackathon Nasional Pemuda Kreatif', 'RPL', 'Kompetisi pemrograman selama 24 jam untuk membuat solusi digital terhadap permasalahan pendidikan di Indonesia. Tim terdiri dari 3 siswa dengan 1 guru pembimbing. Teknologi yang digunakan bebas, namun harus open-source. Hadiah utama berupa beasiswa dan kesempatan magang.', 'Pak Nyoman Surya', NULL, NULL, '2026-05-05 14:14:08', '2026-05-10', '2026-05-12', 1),
(5, 'Olimpiade Teknologi Informasi', 'TKJ', 'Olimpiade pengetahuan umum di bidang TI meliputi jaringan komputer, keamanan siber, cloud computing, dan sistem operasi. Terbuka untuk semua jurusan. Seleksi dilakukan secara bertahap: tingkat sekolah, kota, provinsi, hingga nasional.', 'Bu Putu Dewi', NULL, NULL, '2026-05-05 14:14:08', '2026-03-20', '2026-03-22', 1),
(6, 'Kompetisi UI/UX Design Challenge', 'DKV', 'Tantangan desain antarmuka pengguna dengan studi kasus dari industri nyata. Peserta harus melakukan user research, membuat wireframe, high-fidelity prototype, dan melakukan usability testing. Menggunakan tools Figma atau Adobe XD.', 'Pak Made Sujana', NULL, NULL, '2026-05-05 14:14:08', '2026-06-01', '2026-06-10', 1);

-- --------------------------------------------------------

--
-- Table structure for table `comp_external_links`
--

CREATE TABLE `comp_external_links` (
  `id` int(11) NOT NULL,
  `address` text NOT NULL,
  `title` text NOT NULL,
  `linked_comp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comp_external_links`
--

INSERT INTO `comp_external_links` (`id`, `address`, `title`, `linked_comp`) VALUES
(1, 'https://example.com/web-design-guide', 'Panduan Peserta Web Design', 1),
(2, 'https://example.com/web-design-daftar', 'Formulir Pendaftaran Web Design', 1),
(5, 'https://example.com/hackathon-rules', 'Peraturan & Ketentuan Hackathon', 4),
(6, 'https://example.com/hackathon-register', 'Registrasi Tim Hackathon', 4),
(7, 'https://example.com/olimpiade-ti-syllabus', 'Silabus Materi Olimpiade TI', 5),
(13, 'https://example.com/festival-dkv-gallery', 'Galeri Karya Peserta Tahun Lalu', 3);

-- --------------------------------------------------------

--
-- Table structure for table `comp_registrations`
--

CREATE TABLE `comp_registrations` (
  `id` int(11) NOT NULL,
  `student_nis` varchar(10) NOT NULL,
  `comp_id` int(11) NOT NULL,
  `registered_on` datetime DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `majors`
--

CREATE TABLE `majors` (
  `id` varchar(10) NOT NULL,
  `name` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `major_table`
--

CREATE TABLE `major_table` (
  `major_id` varchar(10) NOT NULL,
  `major_name` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `major_table`
--

INSERT INTO `major_table` (`major_id`, `major_name`) VALUES
('DKV', 'Desain Komunikasi Visual'),
('RPL', 'Rekayasa Perangkat Lunak'),
('TKJ', 'Teknik Komputer dan Jaringan');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `target_nis` varchar(10) DEFAULT NULL,
  `target_comp_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `type` varchar(30) NOT NULL DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT 0,
  `created_on` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students_table`
--

CREATE TABLE `students_table` (
  `nis` varchar(10) NOT NULL,
  `full_name` text DEFAULT NULL,
  `att_number` int(11) DEFAULT NULL,
  `class` varchar(20) DEFAULT NULL,
  `acc_password` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `profile_pic_path` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_on` datetime DEFAULT current_timestamp(),
  `last_logged_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students_table`
--

INSERT INTO `students_table` (`nis`, `full_name`, `att_number`, `class`, `acc_password`, `email`, `profile_pic_path`, `description`, `created_on`, `last_logged_on`) VALUES
('530', 'Hugh Treeson III', 1, 'X RPL 1', '12345678', NULL, NULL, 'Siswa berprestasi di bidang pemrograman web dan database. Aktif dalam kegiatan ekstrakurikuler IT Club.', '2026-05-05 14:14:07', '2026-05-28 10:45:17'),
('531', 'Gout Dat Drip', 2, 'X RPL 1', '12345678', NULL, NULL, '', '2026-05-05 14:14:07', NULL),
('532', 'Zorro Almond', 3, 'X RPL 1', '12345678', NULL, NULL, '', '2026-05-05 14:14:07', NULL),
('533', 'Troy Fuller', 4, 'X RPL 1', '12345678', NULL, NULL, '', '2026-05-05 14:14:07', NULL),
('534', 'Monty Python', 5, 'X RPL 1', '12345678', NULL, NULL, '', '2026-05-05 14:14:07', NULL),
('535', 'Barry Pacifica', 6, 'X RPL 1', '12345678', NULL, NULL, '', '2026-05-05 14:14:07', NULL),
('536', 'Mick Wilson', 7, 'X RPL 1', '12345678', NULL, NULL, '', '2026-05-05 14:14:07', NULL),
('537', 'Ray Wilson', 8, 'X RPL 1', '12345678', NULL, NULL, '', '2026-05-05 14:14:07', NULL),
('538', 'Vinie Wilson', 9, 'X RPL 1', '12345678', NULL, NULL, '', '2026-05-05 14:14:07', NULL),
('539', 'Breemoi Wilson', 10, 'X RPL 1', '12345678', NULL, NULL, '', '2026-05-05 14:14:07', NULL),
('540', 'Jack Wilson', 11, 'X RPL 1', '12345678', NULL, NULL, '', '2026-05-05 14:14:07', NULL),
('541', 'Quaker Wilson', 12, 'X RPL 1', '12345678', NULL, NULL, '', '2026-05-05 14:14:07', NULL),
('542', 'Tony Pluey', 13, 'X RPL 1', '12345678', NULL, NULL, '', '2026-05-05 14:14:07', NULL),
('543', 'John Doe', 14, 'X RPL 1', '12345678', NULL, NULL, '', '2026-05-05 14:14:07', NULL),
('544', 'Rodger Alebrek', 15, 'X DKV 1', '12345678', NULL, NULL, 'Siswa DKV dengan minat di bidang ilustrasi digital dan motion graphics.', '2026-05-05 14:14:07', NULL),
('545', 'Ni Made Ayu', 1, 'X DKV 1', '12345678', NULL, NULL, '', '2026-05-05 14:14:07', NULL),
('546', 'Komang Putra', 2, 'X TKJ 1', '12345678', NULL, NULL, 'Memiliki sertifikasi Cisco CCNA dan berpengalaman dalam konfigurasi jaringan.', '2026-05-05 14:14:07', NULL),
('547', 'Wayan Eka', 3, 'X TKJ 1', '12345678', NULL, NULL, '', '2026-05-05 14:14:07', NULL),
('999', 'aa', 100, 'X TKJ 2', '12345678', NULL, NULL, NULL, '2026-05-27 03:51:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_achievements`
--

CREATE TABLE `student_achievements` (
  `id` int(11) NOT NULL,
  `student_nis` varchar(10) NOT NULL,
  `comp_id` int(11) DEFAULT NULL,
  `achievement_title` varchar(100) NOT NULL,
  `result` varchar(30) NOT NULL DEFAULT 'peserta',
  `notes` text DEFAULT NULL,
  `created_on` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins_table`
--
ALTER TABLE `admins_table`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_admin_username` (`username`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `major` (`major`);

--
-- Indexes for table `class_table`
--
ALTER TABLE `class_table`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `fk_class_major` (`major_id`);

--
-- Indexes for table `comps`
--
ALTER TABLE `comps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_comp_major` (`major`);

--
-- Indexes for table `comp_external_links`
--
ALTER TABLE `comp_external_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ext_link_comp` (`linked_comp`);

--
-- Indexes for table `comp_registrations`
--
ALTER TABLE `comp_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_student_comp` (`student_nis`,`comp_id`),
  ADD KEY `fk_reg_student` (`student_nis`),
  ADD KEY `fk_reg_comp` (`comp_id`);

--
-- Indexes for table `majors`
--
ALTER TABLE `majors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `major_table`
--
ALTER TABLE `major_table`
  ADD PRIMARY KEY (`major_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notif_student` (`target_nis`),
  ADD KEY `fk_notif_comp` (`target_comp_id`);

--
-- Indexes for table `students_table`
--
ALTER TABLE `students_table`
  ADD PRIMARY KEY (`nis`),
  ADD KEY `fk_student_class` (`class`);

--
-- Indexes for table `student_achievements`
--
ALTER TABLE `student_achievements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ach_student` (`student_nis`),
  ADD KEY `fk_ach_comp` (`comp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins_table`
--
ALTER TABLE `admins_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comps`
--
ALTER TABLE `comps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `comp_external_links`
--
ALTER TABLE `comp_external_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `comp_registrations`
--
ALTER TABLE `comp_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `student_achievements`
--
ALTER TABLE `student_achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`major`) REFERENCES `majors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `class_table`
--
ALTER TABLE `class_table`
  ADD CONSTRAINT `fk_class_major` FOREIGN KEY (`major_id`) REFERENCES `major_table` (`major_id`);

--
-- Constraints for table `comps`
--
ALTER TABLE `comps`
  ADD CONSTRAINT `fk_comp_major` FOREIGN KEY (`major`) REFERENCES `major_table` (`major_id`);

--
-- Constraints for table `comp_external_links`
--
ALTER TABLE `comp_external_links`
  ADD CONSTRAINT `fk_ext_link_comp` FOREIGN KEY (`linked_comp`) REFERENCES `comps` (`id`);

--
-- Constraints for table `comp_registrations`
--
ALTER TABLE `comp_registrations`
  ADD CONSTRAINT `fk_reg_comp` FOREIGN KEY (`comp_id`) REFERENCES `comps` (`id`),
  ADD CONSTRAINT `fk_reg_student` FOREIGN KEY (`student_nis`) REFERENCES `students_table` (`nis`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notif_comp` FOREIGN KEY (`target_comp_id`) REFERENCES `comps` (`id`),
  ADD CONSTRAINT `fk_notif_student` FOREIGN KEY (`target_nis`) REFERENCES `students_table` (`nis`);

--
-- Constraints for table `students_table`
--
ALTER TABLE `students_table`
  ADD CONSTRAINT `fk_student_class` FOREIGN KEY (`class`) REFERENCES `class_table` (`class_id`);

--
-- Constraints for table `student_achievements`
--
ALTER TABLE `student_achievements`
  ADD CONSTRAINT `fk_ach_comp` FOREIGN KEY (`comp_id`) REFERENCES `comps` (`id`),
  ADD CONSTRAINT `fk_ach_student` FOREIGN KEY (`student_nis`) REFERENCES `students_table` (`nis`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
