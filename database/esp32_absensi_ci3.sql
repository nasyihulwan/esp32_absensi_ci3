-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 25, 2024 at 06:54 AM
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
-- Database: `esp32_absensi_ci3`
--

-- --------------------------------------------------------

--
-- Table structure for table `absen_matakuliah`
--

CREATE TABLE `absen_matakuliah` (
  `id` int(11) NOT NULL,
  `id_fingerprint` int(11) NOT NULL,
  `id_absen_matkul` int(11) DEFAULT NULL,
  `absen_masuk` datetime NOT NULL,
  `ket_absen_masuk` enum('Tepat Waktu','Terlambat') NOT NULL,
  `absen_keluar` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `absen_matakuliah`
--

INSERT INTO `absen_matakuliah` (`id`, `id_fingerprint`, `id_absen_matkul`, `absen_masuk`, `ket_absen_masuk`, `absen_keluar`) VALUES
(14, 1, 1, '2024-12-24 21:16:04', 'Terlambat', NULL),
(16, 1, 4, '2024-12-25 12:10:12', 'Terlambat', NULL),
(17, 4, 4, '2024-12-25 12:42:53', 'Terlambat', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `alat`
--

CREATE TABLE `alat` (
  `id` int(11) NOT NULL,
  `kode_alat` varchar(255) NOT NULL,
  `nama_alat` varchar(255) NOT NULL,
  `ssid` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `last_heartbeat` datetime DEFAULT NULL,
  `is_connected` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alat`
--

INSERT INTO `alat` (`id`, `kode_alat`, `nama_alat`, `ssid`, `ip_address`, `created_at`, `updated_at`, `last_heartbeat`, `is_connected`) VALUES
(2, 'ESP32_ABS_BY_N', 'ESP32_ABSENSI_BY_NASYIH', 'AAEEN', '192.168.1.6', '2024-12-25 09:17:56', '2024-12-25 12:37:48', '2024-12-25 12:54:08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_absen_matakuliah`
--

CREATE TABLE `jadwal_absen_matakuliah` (
  `id` int(11) NOT NULL,
  `id_dosen` int(11) NOT NULL,
  `prodi` enum('TEKKOM','RPL','PMM','PGSD','PGPAUD') NOT NULL,
  `nama_matakuliah` varchar(255) NOT NULL,
  `pertemuan` enum('1','2','3','4','5','6','7','8','9','10','10','12','13','14','15','16') NOT NULL,
  `semester` enum('1','2','3','4','5','6','7','8') NOT NULL,
  `kelas` enum('A','B','C','D','E','F') NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_keluar` time NOT NULL,
  `buka_absen_masuk` time NOT NULL,
  `buka_absen_keluar` time NOT NULL,
  `status` enum('Dibuka','Ditutup') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_absen_matakuliah`
--

INSERT INTO `jadwal_absen_matakuliah` (`id`, `id_dosen`, `prodi`, `nama_matakuliah`, `pertemuan`, `semester`, `kelas`, `tanggal`, `jam_masuk`, `jam_keluar`, `buka_absen_masuk`, `buka_absen_keluar`, `status`) VALUES
(1, 41, 'TEKKOM', 'Sistem Berbasis Komputer', '1', '3', 'A', '2024-12-24', '18:00:00', '21:00:00', '00:15:00', '00:10:00', 'Ditutup'),
(4, 41, 'TEKKOM', 'Struktur Data &amp; Algoritma', '1', '3', 'A', '2024-12-25', '11:50:00', '12:30:00', '00:15:00', '00:15:00', 'Dibuka');

-- --------------------------------------------------------

--
-- Table structure for table `keterangan_absen`
--

CREATE TABLE `keterangan_absen` (
  `id` int(11) NOT NULL,
  `keterangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keterangan_absen`
--

INSERT INTO `keterangan_absen` (`id`, `keterangan`) VALUES
(1, 'Tepat Waktu'),
(2, 'Terlambat');

-- --------------------------------------------------------

--
-- Table structure for table `prodi`
--

CREATE TABLE `prodi` (
  `id` int(11) NOT NULL,
  `prodi` varchar(255) NOT NULL,
  `singkatan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prodi`
--

INSERT INTO `prodi` (`id`, `prodi`, `singkatan`) VALUES
(1, 'Teknik Komputer', 'TEKKOM');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `id_fingerprint` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','dosen','mahasiswa','') NOT NULL,
  `prodi` enum('-','TEKKOM') NOT NULL,
  `semester` enum('-','1','2','3','4','5','6','7','8') NOT NULL,
  `kelas` enum('-','A','B','C','D') NOT NULL,
  `image` varchar(255) NOT NULL,
  `password_absen` int(11) DEFAULT NULL,
  `is_active` int(11) NOT NULL,
  `date_created` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `id_fingerprint`, `username`, `password`, `role`, `prodi`, `semester`, `kelas`, `image`, `password_absen`, `is_active`, `date_created`) VALUES
(1, 'アナス', NULL, 'anas', '$2y$10$i//2nLvxPrJK5zaHb5M0UeDcwog2rI4uR.z/XEf6TVzj87EIZh4q6', 'admin', '', '-', '-', 'default.png', NULL, 1, '1251414124'),
(41, 'Gojou Satoru', NULL, 'gojo', '$2y$10$HOkEwdQ0jw56bpEr4m0ISeadaTym9YxYxGXXp1/IW./fxOR.pCfHC', 'dosen', 'TEKKOM', '-', '-', 'default.png', NULL, 1, '1668341765'),
(42, 'Nasyih', 1, 'nasyih', '$2y$10$i//2nLvxPrJK5zaHb5M0UeDcwog2rI4uR.z/XEf6TVzj87EIZh4q6', 'mahasiswa', 'TEKKOM', '3', 'A', 'denji.png', 212121, 1, '1668343389'),
(58, 'Saint', 2, 'admin', '$2y$10$e3WF3NAGEbukQUbSLhqoQuH1ufJqgXLO/czcQI8F0rPjC0Dqiorr6', 'mahasiswa', 'TEKKOM', '3', 'A', 'default.png', 232323, 1, '1735035436'),
(59, 'Anasu', 3, 'qwe', '$2y$10$TYpqNsO4hgUN8GXT3/25hO11eqakatuX1mCiTH77tXLSpRhy59.Ne', 'mahasiswa', 'TEKKOM', '3', 'A', 'default.png', 321321, 1, '1735096717'),
(60, 'Kojima', 4, 'zxc', '$2y$10$rh4EBnbHaac9ilQJ8sgmK./.1NEN1Y.SfpI/ULv6XkdDVRSxQBMmS', 'mahasiswa', 'TEKKOM', '3', 'A', 'default.png', 123123, 1, '1735105300');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absen_matakuliah`
--
ALTER TABLE `absen_matakuliah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `alat`
--
ALTER TABLE `alat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwal_absen_matakuliah`
--
ALTER TABLE `jadwal_absen_matakuliah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keterangan_absen`
--
ALTER TABLE `keterangan_absen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prodi`
--
ALTER TABLE `prodi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`id_fingerprint`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absen_matakuliah`
--
ALTER TABLE `absen_matakuliah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `alat`
--
ALTER TABLE `alat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jadwal_absen_matakuliah`
--
ALTER TABLE `jadwal_absen_matakuliah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
