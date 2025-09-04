-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2025 at 07:11 AM
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
-- Database: `absensi`
--

-- --------------------------------------------------------

--
-- Table structure for table `departemen`
--

CREATE TABLE `departemen` (
  `kode_dept` char(3) NOT NULL,
  `nama_dept` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departemen`
--

INSERT INTO `departemen` (`kode_dept`, `nama_dept`) VALUES
('HRD', 'Human Resource Development'),
('IT', 'Information Technology '),
('MKT', 'Marketing');

-- --------------------------------------------------------

--
-- Table structure for table `jam_kerja`
--

CREATE TABLE `jam_kerja` (
  `kode_jam_kerja` char(4) NOT NULL,
  `nama_jam_kerja` varchar(15) NOT NULL,
  `awal_jam_masuk` time NOT NULL,
  `jam_masuk` time NOT NULL,
  `akhir_jam_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jam_kerja`
--

INSERT INTO `jam_kerja` (`kode_jam_kerja`, `nama_jam_kerja`, `awal_jam_masuk`, `jam_masuk`, `akhir_jam_masuk`, `jam_pulang`) VALUES
('JK01', 'SHIFT 1', '07:00:00', '08:00:00', '22:00:00', '23:00:00'),
('JK02', 'SHIFT 2', '07:45:00', '08:00:00', '09:00:00', '12:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `nik` char(5) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `foto` varchar(30) NOT NULL,
  `kode_dept` char(3) NOT NULL,
  `kode_jam_kerja` char(4) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`nik`, `nama_lengkap`, `jabatan`, `no_hp`, `foto`, `kode_dept`, `kode_jam_kerja`, `password`, `remember_token`) VALUES
('12345', 'Lailatul Badriyyah', 'Karyawan', '0899999999999', '12345.jpeg', 'HRD', NULL, '$2y$12$kmoYOZprmV2TC1XBNetP4uNpdADmRegWPyvmVdBFjSGprufYpEjiy', 'nFWbkpq3dIu7iAtVl2PYNTTx0KdSdCWqfqmO0RajFsamT0OP1kUmhZKgWjea'),
('54321', 'Arringga Pranasiwi s.', 'Karyawan', '089506988345', '54321.jpeg', 'IT', NULL, '$2y$12$IgIUyzp0oDG5GzOSeu6QBeMm2SId1mZPj17WURxqCruGcvtn9Luza', 'JqfybXzKFsFikOsCWJ9l7B5SVTRMgbHyoG4Cxvqo6FqZljUYIQTxnw7Qt6YM');

-- --------------------------------------------------------

--
-- Table structure for table `konfigurasi_jamkerja`
--

CREATE TABLE `konfigurasi_jamkerja` (
  `nik` char(10) NOT NULL,
  `hari` varchar(10) NOT NULL,
  `kode_jam_kerja` char(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `konfigurasi_jamkerja`
--

INSERT INTO `konfigurasi_jamkerja` (`nik`, `hari`, `kode_jam_kerja`) VALUES
('54321', 'senin', 'JK01'),
('54321', 'selasa', 'JK01'),
('54321', 'rabu', 'JK01'),
('54321', 'kamis', 'JK01'),
('54321', 'jumat', 'JK01'),
('54321', 'sabtu', 'JK01'),
('54321', 'minggu', 'JK01');

-- --------------------------------------------------------

--
-- Table structure for table `konfigurasi_lokasi`
--

CREATE TABLE `konfigurasi_lokasi` (
  `id` int(11) NOT NULL,
  `lokasi_kantor` varchar(255) NOT NULL,
  `radius` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `konfigurasi_lokasi`
--

INSERT INTO `konfigurasi_lokasi` (`id`, `lokasi_kantor`, `radius`) VALUES
(1, '-8.132978, 112.563963', 30);

-- --------------------------------------------------------

--
-- Table structure for table `master_cuti`
--

CREATE TABLE `master_cuti` (
  `kode_cuti` char(3) NOT NULL,
  `nama_cuti` varchar(30) NOT NULL,
  `jml_hari` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_cuti`
--

INSERT INTO `master_cuti` (`kode_cuti`, `nama_cuti`, `jml_hari`) VALUES
('C01', 'Tahunan', 12),
('C02', 'Cuti Melahirkan', 90);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_izin`
--

CREATE TABLE `pengajuan_izin` (
  `kode_izin` char(9) NOT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `tgl_izin_dari` date DEFAULT NULL,
  `tgl_izin_sampai` date DEFAULT NULL,
  `status` char(10) DEFAULT NULL,
  `kode_cuti` char(3) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `doc_sid` varchar(255) DEFAULT NULL,
  `status_approved` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengajuan_izin`
--

INSERT INTO `pengajuan_izin` (`kode_izin`, `nik`, `tgl_izin_dari`, `tgl_izin_sampai`, `status`, `kode_cuti`, `keterangan`, `doc_sid`, `status_approved`, `created_at`, `updated_at`) VALUES
('IZ0825002', '54321', '2025-08-19', '2025-08-19', 's', NULL, 'sakit', 'IZ0825002.png', 0, NULL, NULL),
('IZ0825003', '54321', '2025-08-22', '2025-08-25', 'c', 'C01', 'Liburan', NULL, 0, NULL, NULL),
('IZ0925001', '54321', '2025-09-01', '2025-09-01', 'i', NULL, 'izin', NULL, NULL, NULL, NULL),
('IZ0925002', '54321', '2025-09-02', '2025-09-02', 'i', NULL, 'iziiinn', NULL, NULL, NULL, NULL),
('IZ0925003', '54321', '2025-09-03', '2025-09-03', 'i', NULL, 'izin lagi', NULL, NULL, NULL, NULL),
('IZ0925004', '54321', '2025-09-04', '2025-09-04', 'i', NULL, 'lagi', NULL, 0, NULL, NULL),
('IZ1125001', '54321', '2025-11-01', '2025-11-04', 'i', NULL, 'nikahan mantan', NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `presensi`
--

CREATE TABLE `presensi` (
  `id` int(11) NOT NULL,
  `nik` char(5) DEFAULT NULL,
  `tgl_presensi` date DEFAULT NULL,
  `jam_in` time DEFAULT NULL,
  `jam_out` time DEFAULT NULL,
  `foto_in` varchar(255) DEFAULT NULL,
  `foto_out` varchar(255) DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  `lokasi_in` text DEFAULT NULL,
  `lokasi_out` text DEFAULT NULL,
  `kode_jam_kerja` char(4) DEFAULT NULL,
  `kode_izin` char(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `presensi`
--

INSERT INTO `presensi` (`id`, `nik`, `tgl_presensi`, `jam_in`, `jam_out`, `foto_in`, `foto_out`, `status`, `lokasi_in`, `lokasi_out`, `kode_jam_kerja`, `kode_izin`) VALUES
(3, '54321', '2025-08-20', '10:58:54', NULL, '54321-2025-08-20-in.png', NULL, 'h', '-8.132978,112.563963', NULL, 'JK01', NULL),
(20, '54321', '2025-08-21', '13:54:43', NULL, '54321-2025-08-21-in.png', NULL, 'h', '-8.1329734,112.5639599', NULL, 'JK01', NULL),
(25, '54321', '2025-08-22', '18:29:13', NULL, '54321-2025-08-22-in.png', NULL, 'h', '-8.132978,112.563963', NULL, 'JK01', NULL),
(30, '54321', '2025-09-02', '10:57:09', NULL, '54321-2025-09-02-in.png', NULL, 'H', '-8.132978,112.563963', NULL, 'JK01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('viBVMOVvbHNwc8i1jNMXnZCVlOmyLZKHvim57ym4', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoicXo1ejlUaFVjU2JoNk5qN2hDMkpYUmZ1NUF6cHFxUHZWMVVpYmJyYSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wYW5lbC9kYXNoYm9hcmRhZG1pbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9lZGl0cHJvZmlsZSI7fXM6NTE6ImxvZ2luX3VzZXJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NTU6ImxvZ2luX2thcnlhd2FuXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTQzMjE7fQ==', 1756789637);

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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Bayu', 'bayu@gmail.com', NULL, '$2y$12$pzRyLs0olPcR6fApUcg8M.A8mzIA1yjzculZ5gXwKaXIMCxt.3XNe', NULL, NULL, NULL),
(2, 'Arringga', 'arringga@gmail.com', NULL, '$2y$12$0Y8uO9h07DPQ6LlOVCHtBu/VGjQYvlVAqUy2dinYVsvQTns9IjBtq', NULL, '2025-08-21 03:48:52', '2025-08-21 03:48:52'),
(4, 'Badriyyah', 'badriyyah@gmail.com', NULL, '$2y$12$iM8U/A2bSm8PhjZ0viPaD.UDtDbD5ZFd29LjNn3DrhPjA3ucbXVqC', NULL, '2025-08-21 03:50:17', '2025-08-21 03:50:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departemen`
--
ALTER TABLE `departemen`
  ADD PRIMARY KEY (`kode_dept`);

--
-- Indexes for table `jam_kerja`
--
ALTER TABLE `jam_kerja`
  ADD PRIMARY KEY (`kode_jam_kerja`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`nik`);

--
-- Indexes for table `konfigurasi_lokasi`
--
ALTER TABLE `konfigurasi_lokasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_cuti`
--
ALTER TABLE `master_cuti`
  ADD PRIMARY KEY (`kode_cuti`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengajuan_izin`
--
ALTER TABLE `pengajuan_izin`
  ADD PRIMARY KEY (`kode_izin`);

--
-- Indexes for table `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `konfigurasi_lokasi`
--
ALTER TABLE `konfigurasi_lokasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
