-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2023 at 02:10 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sitibel`
--

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `id_jabatan` int(11) NOT NULL,
  `nama_jabatan` varchar(20) NOT NULL,
  `id_unitkerja` int(11) NOT NULL,
  `id_tamplate` int(11) DEFAULT NULL,
  `hanya_satu` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`id_jabatan`, `nama_jabatan`, `id_unitkerja`, `id_tamplate`, `hanya_satu`) VALUES
(1, 'Dosen', 16, 2, 0),
(2, 'Dekan', 16, NULL, 1),
(3, 'Kepala Bagian', 13, NULL, 1),
(5, 'Jabatan Baru', 16, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_cuti`
--

CREATE TABLE `jenis_cuti` (
  `id_jeniscuti` int(11) NOT NULL,
  `nama_jeniscuti` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jenis_cuti`
--

INSERT INTO `jenis_cuti` (`id_jeniscuti`, `nama_jeniscuti`) VALUES
(1, 'Cuti Tahunan'),
(2, 'Cuti Sakit'),
(3, 'Cuti Besar'),
(4, 'Cuti Karena Alasan Penting'),
(5, 'Cuti Melahirkan'),
(6, 'Cuti Diluar Tanggungan Negara');

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` int(11) NOT NULL,
  `nama_pegawai` varchar(50) NOT NULL,
  `id_jabatan` int(11) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `mulai_kerja` date DEFAULT NULL,
  `no_hp` varchar(16) DEFAULT NULL,
  `status` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id_pegawai`, `nama_pegawai`, `id_jabatan`, `nip`, `nik`, `mulai_kerja`, `no_hp`, `status`) VALUES
(4, 'Harits', 2, '1234567890', '3314082011990005', NULL, NULL, 'PNS'),
(5, 'Joko Asmoro', 1, '', '3312', '2014-11-06', NULL, 'TEKON'),
(6, 'Kepala', 1, '1234', '1234', '2023-11-02', NULL, 'PNS'),
(7, 'BUK', 3, '12345', '12345', '2023-11-08', NULL, 'PNS');

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_cuti`
--

CREATE TABLE `pengajuan_cuti` (
  `id_pengajuan` int(11) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `id_jeniscuti` int(11) NOT NULL,
  `lama_cuti` int(11) NOT NULL,
  `alasan` text NOT NULL,
  `mulai_cuti` date NOT NULL,
  `selesai_cuti` date DEFAULT NULL,
  `catatan_cuti` text NOT NULL,
  `alamat_cuti` text NOT NULL,
  `no_hp` varchar(16) NOT NULL,
  `ttd_pertama` date DEFAULT NULL,
  `ttd_kedua` date DEFAULT NULL,
  `tanggal_modifikasi` date NOT NULL DEFAULT current_timestamp(),
  `status_pengajuan` varchar(20) NOT NULL DEFAULT 'Proses',
  `dokumen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pengajuan_cuti`
--

INSERT INTO `pengajuan_cuti` (`id_pengajuan`, `id_pegawai`, `id_jeniscuti`, `lama_cuti`, `alasan`, `mulai_cuti`, `selesai_cuti`, `catatan_cuti`, `alamat_cuti`, `no_hp`, `ttd_pertama`, `ttd_kedua`, `tanggal_modifikasi`, `status_pengajuan`, `dokumen`) VALUES
(10, 5, 1, 1, 'asdadasd', '2023-12-05', '2023-12-05', '', 'asdasdasdad', '', NULL, NULL, '2023-11-25', 'Proses', '');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL,
  `id_pegawai` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `username`, `password`, `role`, `id_pegawai`) VALUES
(1, 'admin', '$2y$10$F4WmVCcDJCbzmLGECWe.mOEvgyY4rw6VPcLT0lXA8EVBFRpNurqni', '0', NULL),
(3, '1234567890', '$2y$10$B6w7.IhyuTi4upeux1rZ0OqUHix7swJgAuRawmzhD9.u9nqYsJrEC', '2', 4),
(4, '3312', '$2y$10$/0MBBSnHcD5VZlKZ8VTllukK4mYp3fVIC6PhMySWHQ73HPFFcqCFm', '2', 5),
(5, '1234', '$2y$10$cIxjkp7BfYUcdyNAzLJvsuKIUtTUDfwA/kbFc5.dpZ80wTEjbeNWG', '2', 6),
(6, '12345', '$2y$10$hm.A5kRNs9cbZBcx7.fhJ.yMilPoTBzrVXcrD2uqR3B/NLTrOH.qu', '2', 7);

-- --------------------------------------------------------

--
-- Table structure for table `tamplate_persetujuan`
--

CREATE TABLE `tamplate_persetujuan` (
  `id_tamplate` int(11) NOT NULL,
  `nama_tamplate` varchar(20) NOT NULL,
  `persetujuan_pertama` int(11) NOT NULL,
  `persetujuan_kedua` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tamplate_persetujuan`
--

INSERT INTO `tamplate_persetujuan` (`id_tamplate`, `nama_tamplate`, `persetujuan_pertama`, `persetujuan_kedua`) VALUES
(2, 'Pegawai Teknik', 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `unit_kerja`
--

CREATE TABLE `unit_kerja` (
  `id_unitkerja` int(11) NOT NULL,
  `nama_unitkerja` varchar(50) NOT NULL,
  `id_induk` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `unit_kerja`
--

INSERT INTO `unit_kerja` (`id_unitkerja`, `nama_unitkerja`, `id_induk`) VALUES
(11, 'Rektorat', NULL),
(12, 'BAKP', 11),
(13, 'BUK', 11),
(16, 'Fakultas Teknik', NULL),
(17, 'Fakultas Pertanian', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id_jabatan`),
  ADD KEY `id_unitkerja` (`id_unitkerja`),
  ADD KEY `id_tamplate` (`id_tamplate`);

--
-- Indexes for table `jenis_cuti`
--
ALTER TABLE `jenis_cuti`
  ADD PRIMARY KEY (`id_jeniscuti`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id_pegawai`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD KEY `id_jabatan` (`id_jabatan`);

--
-- Indexes for table `pengajuan_cuti`
--
ALTER TABLE `pengajuan_cuti`
  ADD PRIMARY KEY (`id_pengajuan`),
  ADD KEY `id_pegawai` (`id_pegawai`),
  ADD KEY `id_jeniscuti` (`id_jeniscuti`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- Indexes for table `tamplate_persetujuan`
--
ALTER TABLE `tamplate_persetujuan`
  ADD PRIMARY KEY (`id_tamplate`),
  ADD KEY `persetujuan_pertama` (`persetujuan_pertama`),
  ADD KEY `persetujuan_kedua` (`persetujuan_kedua`);

--
-- Indexes for table `unit_kerja`
--
ALTER TABLE `unit_kerja`
  ADD PRIMARY KEY (`id_unitkerja`),
  ADD KEY `id_induk` (`id_induk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id_jabatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jenis_cuti`
--
ALTER TABLE `jenis_cuti`
  MODIFY `id_jeniscuti` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id_pegawai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pengajuan_cuti`
--
ALTER TABLE `pengajuan_cuti`
  MODIFY `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tamplate_persetujuan`
--
ALTER TABLE `tamplate_persetujuan`
  MODIFY `id_tamplate` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `unit_kerja`
--
ALTER TABLE `unit_kerja`
  MODIFY `id_unitkerja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD CONSTRAINT `jabatan_ibfk_1` FOREIGN KEY (`id_unitkerja`) REFERENCES `unit_kerja` (`id_unitkerja`),
  ADD CONSTRAINT `jabatan_ibfk_2` FOREIGN KEY (`id_tamplate`) REFERENCES `tamplate_persetujuan` (`id_tamplate`);

--
-- Constraints for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD CONSTRAINT `pegawai_ibfk_1` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id_jabatan`);

--
-- Constraints for table `pengajuan_cuti`
--
ALTER TABLE `pengajuan_cuti`
  ADD CONSTRAINT `pengajuan_cuti_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`),
  ADD CONSTRAINT `pengajuan_cuti_ibfk_2` FOREIGN KEY (`id_jeniscuti`) REFERENCES `jenis_cuti` (`id_jeniscuti`);

--
-- Constraints for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD CONSTRAINT `pengguna_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`);

--
-- Constraints for table `tamplate_persetujuan`
--
ALTER TABLE `tamplate_persetujuan`
  ADD CONSTRAINT `tamplate_persetujuan_ibfk_1` FOREIGN KEY (`persetujuan_pertama`) REFERENCES `jabatan` (`id_jabatan`),
  ADD CONSTRAINT `tamplate_persetujuan_ibfk_2` FOREIGN KEY (`persetujuan_kedua`) REFERENCES `jabatan` (`id_jabatan`);

--
-- Constraints for table `unit_kerja`
--
ALTER TABLE `unit_kerja`
  ADD CONSTRAINT `unit_kerja_ibfk_1` FOREIGN KEY (`id_induk`) REFERENCES `unit_kerja` (`id_unitkerja`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
