-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 21, 2023 at 04:42 AM
-- Server version: 10.5.19-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u370369030_sitibel`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`id_jabatan`, `nama_jabatan`, `id_unitkerja`, `id_tamplate`, `hanya_satu`) VALUES
(1, 'Dosen', 16, 2, 0),
(2, 'Dekan', 16, NULL, 1),
(3, 'Kepala Bagian', 13, NULL, 1),
(5, 'Jabatan Baru', 16, NULL, 0),
(7, 'Pegawai TI', 16, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_cuti`
--

CREATE TABLE `jenis_cuti` (
  `id_jeniscuti` int(11) NOT NULL,
  `nama_jeniscuti` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis_cuti`
--

INSERT INTO `jenis_cuti` (`id_jeniscuti`, `nama_jeniscuti`) VALUES
(1, 'Cuti Tahunan'),
(2, 'Cuti Besar'),
(3, 'Cuti Sakit'),
(4, 'Cuti Melahirkan'),
(5, 'Cuti Karena Alasan Penting'),
(6, 'Cuti Diluar Tanggungan Negara');

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` int(11) NOT NULL,
  `nama_pegawai` varchar(50) NOT NULL,
  `id_jabatan` int(11) NOT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `mulai_kerja` date DEFAULT NULL,
  `no_hp` varchar(16) DEFAULT NULL,
  `status` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id_pegawai`, `nama_pegawai`, `id_jabatan`, `nip`, `nik`, `mulai_kerja`, `no_hp`, `status`) VALUES
(4, 'John Doe', 2, '1234567890', '3314082011990005', '2014-11-06', NULL, 'PNS'),
(5, 'Joko Asmoro', 1, NULL, '3312', '2014-11-06', NULL, 'TEKON'),
(6, 'Jane Doe', 1, '1234', '1234', '2023-11-02', NULL, 'PNS'),
(7, 'Lorem Ipsum', 3, '12345', '12345', '2023-11-08', NULL, 'PNS'),
(8, 'Patricia Elena Putri', 1, NULL, '671008', '2020-08-23', NULL, 'TEKON'),
(13, 'Kyle Wyze', 1, NULL, '234234', '2023-11-15', NULL, 'TEKON'),
(14, 'Myez Gon', 5, '23456789', '9087654321', '2016-07-18', NULL, 'PNS'),
(15, 'times roman', 7, NULL, '98765432123456789', '2020-08-23', NULL, 'TEKON'),
(16, 'Nova', 1, NULL, '123459876', '2020-08-23', NULL, 'TEKON'),
(17, 'desember', 1, '4321', '4321', '2020-01-02', NULL, 'PNS'),
(18, 'januari', 1, NULL, '654321', '2021-01-20', NULL, 'TEKON'),
(19, 'april', 1, '876545678', NULL, '2022-01-02', NULL, 'PNS'),
(20, 'Mei', 1, '17052001', NULL, '2001-05-17', NULL, 'PNS');

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
  `ttd_pertama` date DEFAULT NULL,
  `ttd_kedua` date DEFAULT NULL,
  `tanggal_modifikasi` date NOT NULL DEFAULT current_timestamp(),
  `status_pengajuan` varchar(20) NOT NULL DEFAULT 'Proses',
  `dokumen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengajuan_cuti`
--

INSERT INTO `pengajuan_cuti` (`id_pengajuan`, `id_pegawai`, `id_jeniscuti`, `lama_cuti`, `alasan`, `mulai_cuti`, `selesai_cuti`, `catatan_cuti`, `alamat_cuti`, `ttd_pertama`, `ttd_kedua`, `tanggal_modifikasi`, `status_pengajuan`, `dokumen`) VALUES
(10, 5, 1, 1, 'asdadasd', '2023-12-05', '2023-12-05', 'adasdaadasddd', 'asdasdasdad', '2023-11-01', '2023-11-01', '2023-11-25', 'Disetujui', ''),
(11, 8, 1, 1, 'ibadah keluarga', '2023-12-11', '2023-12-11', 'kurang berkas', 'jl buki rata', NULL, NULL, '2023-11-30', 'Ditangguhkan', ''),
(12, 8, 1, 13, 'ibadah keluarga', '2023-12-11', '2023-12-27', '', 'jl Bukit Raya', '2023-11-30', '2023-11-30', '2023-11-30', 'Disetujui', ''),
(13, 15, 1, 12, 'test', '2023-12-14', '2023-12-29', 'test', 'testing', NULL, NULL, '2023-12-04', 'Tidak Disetujui', ''),
(14, 15, 1, 15, 'test', '2023-12-13', '2024-01-02', 'tes', 'test', NULL, NULL, '2023-12-04', 'Tidak Disetujui', ''),
(15, 15, 1, 2, 'keluar kota', '2023-12-18', '2023-12-19', '', 'test', '2023-12-05', '2023-12-05', '2023-12-05', 'Disetujui', ''),
(16, 16, 4, 90, 'cuti melahirkan', '2024-01-01', '2024-05-03', '', 'rumah sakit', NULL, NULL, '2023-12-05', 'Proses', ''),
(17, 15, 1, 13, 'test', '2023-12-29', '2024-01-16', '', 'test', '2023-12-20', '2023-12-20', '2023-12-19', 'Disetujui', ''),
(18, 13, 1, 2, 'test', '2024-01-03', '2024-01-04', '', 'test', '2023-12-20', '2023-12-20', '2023-12-20', 'Disetujui', ''),
(19, 17, 1, 2, 'test', '2024-01-10', '2024-01-11', '', 'test', '2023-12-20', '2023-12-20', '2023-12-20', 'Disetujui', ''),
(20, 18, 3, 5, 'test', '2024-01-01', '2024-01-05', '', 'test', '2023-12-20', '2023-12-20', '2023-12-20', 'Disetujui', ''),
(21, 19, 1, 1, 'test', '2024-01-01', '2024-01-01', '', 'test', '2023-12-21', '2023-12-21', '2023-12-21', 'Disetujui', '');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL,
  `id_pegawai` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `username`, `password`, `role`, `id_pegawai`) VALUES
(1, 'admin', '$2y$10$YJ7oFDPEpjHW0WDoB8IYDucb8aVV1ciVoR5jkpEcyHEPW5ZHce3N.', '0', NULL),
(3, '1234567890', '$2y$10$GhyVySIxR943eLiLQanuDugRijdL.MWrvI7vlVRmBB.M.QBLpT6I2', '2', 4),
(4, '3312', '$2y$10$/0MBBSnHcD5VZlKZ8VTllukK4mYp3fVIC6PhMySWHQ73HPFFcqCFm', '2', 5),
(5, '1234', '$2y$10$cIxjkp7BfYUcdyNAzLJvsuKIUtTUDfwA/kbFc5.dpZ80wTEjbeNWG', '2', 6),
(6, '12345', '$2y$10$hm.A5kRNs9cbZBcx7.fhJ.yMilPoTBzrVXcrD2uqR3B/NLTrOH.qu', '2', 7),
(7, '671008', '$2y$10$bD9RGkrc1SxP.LWYRK29.eIZvIdCJusVtkaIpi3qf0RWklyuEYE4u', '2', 8),
(8, '234234', '$2y$10$V7iozsR0lt5KqQtV8rjCourqnIIqph/IUrJIJBOtHukA76d7mK6LO', '2', 13),
(10, 'admin-rektorat', '$2y$10$octf7jAqH9BVd8HuKCc2uOtS5HC3a24qT.bCgt.Z4osTnJECzSIiO', '1', NULL),
(11, 'admin-bakp', '$2y$10$zS6ubEIe2ZCBNR2njQIEVeajvB2lVwtj8rdHCyV5twu1YF/KScfj.', '1', NULL),
(12, 'admin-buk', '$2y$10$JA3/rnF3r2CU5hxIfuFACeufUva6KedZx27qhzD631cyskBYG7vCq', '1', NULL),
(13, 'admin-fakultas-teknik', '$2y$10$32ufCmjVrmBFkuEEtbW7JObEt7CzUNFcIqvM6.ZYzOQugYOFgfdDm', '1', NULL),
(14, 'admin-fakultas-pertanian', '$2y$10$gHZcIZNfF5Cj2JY2kB17nOSDNa6vEsJDhhfw3DfyERSY8F5x2Lhx6', '1', NULL),
(16, '23456789', '$2y$10$KKMaeDsGoowhK5akBpTTD.819olfV9hcMcIiTyc43HR1EcbG07axe', '2', 14),
(18, '98765432123456789', '$2y$10$9OvocA91/l9bU7ZEQOhoKO4UfrmihQh6OHeaHtNA//GKCl8cRZm9u', '2', 15),
(19, 'admin-pegawai-faperta', '$2y$10$rmcTAZygdjiGu/3VgLhK3.zHD7jKFVf8VGlTiKCwjkRB1IJwuXZI2', '1', NULL),
(20, '123459876', '$2y$10$lxuFb4APHoKuMwPnK/jcg.dBz.RaBX22pB.GF1wb1Hv.yjFY7mH7m', '2', 16),
(21, '4321', '$2y$10$d/ZvwR84Jng8cJNskO1J7ulny/pmy1Xeb7twKP8eXVQnZbpP4gkfC', '2', 17),
(22, '654321', '$2y$10$jrTpb/VY/q81hgRXpGa60.D/vXN9eLG58LbNkWtyl2QxMl2p9dxNi', '2', 18),
(23, '876545678', '$2y$10$NYUMFN2jdcpYnTaVB.mmge6znjxwpkU3e1CfWS.6ZUF.QbeVxxVJm', '2', 19),
(24, '17052001', '$2y$10$XHVKrduGQpiwDFZAUyMnX.Q6.SqM/JJv6AIl0y0E0iiiqXIqf4NEK', '2', 20);

-- --------------------------------------------------------

--
-- Table structure for table `tamplate_persetujuan`
--

CREATE TABLE `tamplate_persetujuan` (
  `id_tamplate` int(11) NOT NULL,
  `nama_tamplate` varchar(20) NOT NULL,
  `persetujuan_pertama` int(11) NOT NULL,
  `persetujuan_kedua` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unit_kerja`
--

INSERT INTO `unit_kerja` (`id_unitkerja`, `nama_unitkerja`, `id_induk`) VALUES
(11, 'Rektorat', NULL),
(12, 'BAKP', 11),
(13, 'BUK', 11),
(16, 'Fakultas Teknik', NULL),
(17, 'Fakultas Pertanian', NULL),
(27, 'Pegawai Faperta', 17);

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
  ADD UNIQUE KEY `nik` (`nik`),
  ADD UNIQUE KEY `nip` (`nip`),
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
  MODIFY `id_jabatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jenis_cuti`
--
ALTER TABLE `jenis_cuti`
  MODIFY `id_jeniscuti` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id_pegawai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `pengajuan_cuti`
--
ALTER TABLE `pengajuan_cuti`
  MODIFY `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tamplate_persetujuan`
--
ALTER TABLE `tamplate_persetujuan`
  MODIFY `id_tamplate` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `unit_kerja`
--
ALTER TABLE `unit_kerja`
  MODIFY `id_unitkerja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

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
