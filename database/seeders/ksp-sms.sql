-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2025 at 07:00 AM
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
-- Database: `ksp-sms`
--

-- --------------------------------------------------------

--
-- Table structure for table `tmangsuran`
--

CREATE TABLE `tmangsuran` (
  `id_angsuran` int(11) NOT NULL,
  `id_pinjaman` int(11) NOT NULL,
  `tanggal_bayar` date DEFAULT curdate(),
  `jumlah_bayar` decimal(15,2) NOT NULL DEFAULT 0.00,
  `denda` decimal(15,2) DEFAULT 0.00,
  `sisa_pinjaman` decimal(15,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmpinjaman`
--

CREATE TABLE `tmpinjaman` (
  `id_pinjaman` int(11) NOT NULL,
  `id_nasabah` int(11) NOT NULL,
  `nama_program` varchar(100) NOT NULL,
  `tanggal_pinjam` date DEFAULT curdate(),
  `jumlah_pinjaman` decimal(15,2) NOT NULL DEFAULT 0.00,
  `bunga` decimal(5,2) NOT NULL DEFAULT 0.00,
  `lama_angsuran` int(11) NOT NULL DEFAULT 12,
  `status` enum('berjalan','lunas','gagal') DEFAULT 'berjalan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmrekening`
--

CREATE TABLE `tmrekening` (
  `id_rekening` int(11) NOT NULL,
  `id_nasabah` int(11) NOT NULL,
  `no_rekening` varchar(20) NOT NULL,
  `no_tabungan` varchar(20) NOT NULL,
  `jenis_rekening` enum('Tabungan','Deposito','Pinjaman','') NOT NULL,
  `id_bunga` int(11) NOT NULL,
  `kode_insentif` varchar(50) NOT NULL,
  `kode_resort` varchar(100) NOT NULL,
  `tabungan_wajib` int(10) NOT NULL,
  `tabungan_rutin` int(10) NOT NULL,
  `id_entry` int(11) NOT NULL,
  `updated_at` date DEFAULT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tmrekening`
--

INSERT INTO `tmrekening` (`id_rekening`, `id_nasabah`, `no_rekening`, `no_tabungan`, `jenis_rekening`, `id_bunga`, `kode_insentif`, `kode_resort`, `tabungan_wajib`, `tabungan_rutin`, `id_entry`, `updated_at`, `created_at`) VALUES
(2, 42, '12500042', '12500042', 'Tabungan', 1, '0', '1', 100000, 0, 1, '2025-10-30', '2025-10-30'),
(3, 42, '22500042', '22500042', 'Deposito', 2, '0', '2', 10000000, 500000, 1, '2025-10-30', '2025-10-30'),
(4, 43, '12500043', '12500043', 'Tabungan', 1, '0', '1', 10000000, 0, 1, '2025-11-03', '2025-11-03');

-- --------------------------------------------------------

--
-- Table structure for table `tmsimpanan`
--

CREATE TABLE `tmsimpanan` (
  `id_simpanan` int(11) NOT NULL,
  `id_rekening` int(11) NOT NULL,
  `id_akun` int(11) NOT NULL,
  `tanggal` date DEFAULT curdate(),
  `jenis` enum('pokok','wajib','sukarela') NOT NULL,
  `v_debit` decimal(15,2) DEFAULT 0.00,
  `v_kredit` decimal(15,2) DEFAULT 0.00,
  `keterangan` text DEFAULT NULL,
  `id_entry` int(11) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tmsimpanan`
--

INSERT INTO `tmsimpanan` (`id_simpanan`, `id_rekening`, `id_akun`, `tanggal`, `jenis`, `v_debit`, `v_kredit`, `keterangan`, `id_entry`, `created_at`, `updated_at`) VALUES
(1, 2, 4, '2025-11-03', 'wajib', '0.00', '100000.00', '-', 1, '2025-11-03', '2025-11-03'),
(2, 2, 5, '2025-11-03', 'wajib', '0.00', '50000.00', '-', 1, '2025-11-03', '2025-11-03');

-- --------------------------------------------------------

--
-- Table structure for table `tmtransaksi`
--

CREATE TABLE `tmtransaksi` (
  `id_transaksi` int(11) NOT NULL,
  `tanggal` date DEFAULT curdate(),
  `jenis_transaksi` enum('simpanan','pinjaman','angsuran','pengeluaran','lainnya') NOT NULL,
  `id_ref` int(11) DEFAULT NULL,
  `debit` decimal(15,2) DEFAULT 0.00,
  `kredit` decimal(15,2) DEFAULT 0.00,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trakun`
--

CREATE TABLE `trakun` (
  `id_akun` int(11) NOT NULL,
  `id_parent` int(11) DEFAULT NULL,
  `kode_akun` varchar(5) NOT NULL,
  `nama_akun` varchar(100) NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trakun`
--

INSERT INTO `trakun` (`id_akun`, `id_parent`, `kode_akun`, `nama_akun`, `status`) VALUES
(1, NULL, '100', 'AKTIVA / ASET', 'aktif'),
(2, 1, '101', 'Kas', 'aktif'),
(3, 1, '102', 'Giro di Bank', 'aktif'),
(4, 1, '103', 'Simpanan Tabungan Anggota', 'aktif'),
(5, 1, '104', 'Simpanan Deposito Anggota', 'aktif'),
(6, 1, '105', 'Pinjaman yang Diberikan', 'aktif'),
(7, 1, '106', 'Cadangan Kerugian Pinjaman', 'aktif'),
(9, 1, '107', 'Aktiva Tetap', 'aktif'),
(10, 1, '108', 'Akumulasi Penyusutan Aktiva Tetap', 'aktif'),
(11, 1, '109', 'Inventaris', 'aktif'),
(12, 1, '110', 'Antar Kantor Aktiva', 'aktif'),
(13, 1, '111', 'Aset Lain-lain', 'aktif'),
(14, NULL, '200', 'KEWAJIBAN (LIABILITAS)', 'aktif'),
(15, 14, '201', 'Hutang Bank', 'aktif'),
(16, 14, '202', 'Hutang Usaha', 'aktif'),
(17, 14, '203', 'Hutang Bunga', 'aktif'),
(18, 14, '204', 'Taksiran Pajak Penghasilan', 'aktif'),
(19, 14, '205', 'Hutang Lain-lain', 'aktif'),
(20, 14, '206', 'Antar Kantor Pasiva', 'aktif'),
(21, NULL, '300', 'EKUITAS / MODAL', 'aktif'),
(22, 21, '301', 'Modal Simpanan Pokok', 'aktif'),
(23, 21, '302', 'Modal Simpanan Wajib', 'aktif'),
(24, 21, '303', 'Dana Cadangan Umum', 'aktif'),
(25, 21, '304', 'Sisa Hasil Usaha (SHU)', 'aktif'),
(26, 21, '305', 'Modal Penyertaan', 'aktif'),
(27, NULL, '400', 'PENDAPATAN', 'aktif'),
(28, 27, '401', 'Pendapatan Bunga Pinjaman', 'aktif'),
(29, 27, '402', 'Pendapatan Jasa Administrasi', 'aktif'),
(30, 27, '403', 'Pendapatan Bunga Deposito di Bank', 'aktif'),
(31, 27, '404', 'Pendapatan Lainnya', 'aktif'),
(32, 27, '405', 'Pendapatan Non-Operasional', 'aktif'),
(33, NULL, '500', 'BEBAN / BIAYA', 'aktif'),
(34, 33, '501', 'Biaya Bunga Simpanan', 'aktif'),
(35, 33, '502', 'Biaya Operasional', 'aktif'),
(36, 33, '503', 'Biaya Penyusutan', 'aktif'),
(37, 33, '504', 'Biaya Pemeliharaan', 'aktif'),
(38, 33, '505', 'Biaya Tenaga Kerja', 'aktif'),
(39, 33, '506', 'Biaya Umum & Administrasi', 'aktif'),
(40, 33, '507', 'Biaya Sewa', 'aktif'),
(41, 33, '508', 'Biaya Non-Operasional', 'aktif'),
(42, 33, '509', 'Pajak-pajak (kecuali PPh Badan)', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `trbunga`
--

CREATE TABLE `trbunga` (
  `id_bunga` int(11) NOT NULL,
  `kode_bunga` varchar(20) NOT NULL,
  `jenis_bunga` varchar(50) NOT NULL,
  `nama_bunga` varchar(100) NOT NULL,
  `tipe_bunga` varchar(20) NOT NULL,
  `termin` int(2) NOT NULL,
  `suku_bunga1` float NOT NULL,
  `suku_bunga2` float NOT NULL,
  `suku_bunga3` float NOT NULL,
  `id_entry` int(11) NOT NULL,
  `updated_at` date DEFAULT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trbunga`
--

INSERT INTO `trbunga` (`id_bunga`, `kode_bunga`, `jenis_bunga`, `nama_bunga`, `tipe_bunga`, `termin`, `suku_bunga1`, `suku_bunga2`, `suku_bunga3`, `id_entry`, `updated_at`, `created_at`) VALUES
(1, 'S01', 'Simpanan', 'Simpanan tabungan', 'flat', 0, 3, 0, 0, 0, NULL, '2025-10-29'),
(2, 'S02', 'Simpanan', 'Simpanan Deposito', 'flat', 0, 6.5, 0, 0, 0, NULL, '2025-10-29');

-- --------------------------------------------------------

--
-- Table structure for table `trnasabah`
--

CREATE TABLE `trnasabah` (
  `id_nasabah` int(11) NOT NULL,
  `nik` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `tgl_lahir` date NOT NULL,
  `pekerjaan` varchar(100) NOT NULL,
  `nama_suami_istri` varchar(100) NOT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `sektor_ekonomi` varchar(100) NOT NULL,
  `id_entry` varchar(20) NOT NULL,
  `tanggal_gabung` date DEFAULT curdate(),
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `updated_at` date DEFAULT NULL,
  `created_at` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trnasabah`
--

INSERT INTO `trnasabah` (`id_nasabah`, `nik`, `nama`, `alamat`, `tgl_lahir`, `pekerjaan`, `nama_suami_istri`, `no_telp`, `sektor_ekonomi`, `id_entry`, `tanggal_gabung`, `status`, `updated_at`, `created_at`) VALUES
(42, '321708080809', 'Akbar Hilman', 'asdasdasdads', '1999-07-07', 'karyawan', '-', '08123546789', 'Pertanian', '1', '2025-10-30', 'aktif', '2025-10-30', '2025-10-30'),
(43, '321708080808', 'as', 'asdasdasdads', '2025-11-03', 'karyawan', '-', '08123546789', 'Pertanian', '1', '2025-11-03', 'aktif', '2025-11-03', '2025-11-03');

-- --------------------------------------------------------

--
-- Table structure for table `trprogram`
--

CREATE TABLE `trprogram` (
  `id_program` int(11) NOT NULL,
  `id_bunga` int(11) NOT NULL,
  `nama_program` varchar(100) NOT NULL,
  `plafond` int(15) NOT NULL,
  `tenor` int(5) NOT NULL,
  `id_entry` int(11) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','bendahara','anggota') DEFAULT 'anggota',
  `id_nasabah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `id_nasabah`) VALUES
(1, 'admin', '$2y$12$8T0Tgti.jsm72fNpx8lS2OyGVeEAze54JtRAHP2BEXZ3li/ZfxJ0O', 'admin', NULL),
(2, 'budi', '9c5fa085ce256c7c598f6710584ab25d', 'anggota', NULL),
(3, 'siti', '5c2e4a2563f9f4427955422fe1402762', 'anggota', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tmangsuran`
--
ALTER TABLE `tmangsuran`
  ADD PRIMARY KEY (`id_angsuran`),
  ADD KEY `id_pinjaman` (`id_pinjaman`);

--
-- Indexes for table `tmpinjaman`
--
ALTER TABLE `tmpinjaman`
  ADD PRIMARY KEY (`id_pinjaman`),
  ADD KEY `id_nasabah` (`id_nasabah`);

--
-- Indexes for table `tmrekening`
--
ALTER TABLE `tmrekening`
  ADD PRIMARY KEY (`id_rekening`),
  ADD KEY `tmrekening_ibfk_1` (`id_nasabah`);

--
-- Indexes for table `tmsimpanan`
--
ALTER TABLE `tmsimpanan`
  ADD PRIMARY KEY (`id_simpanan`);

--
-- Indexes for table `tmtransaksi`
--
ALTER TABLE `tmtransaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indexes for table `trakun`
--
ALTER TABLE `trakun`
  ADD PRIMARY KEY (`id_akun`);

--
-- Indexes for table `trbunga`
--
ALTER TABLE `trbunga`
  ADD PRIMARY KEY (`id_bunga`);

--
-- Indexes for table `trnasabah`
--
ALTER TABLE `trnasabah`
  ADD PRIMARY KEY (`id_nasabah`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- Indexes for table `trprogram`
--
ALTER TABLE `trprogram`
  ADD PRIMARY KEY (`id_program`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_nasabah` (`id_nasabah`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tmangsuran`
--
ALTER TABLE `tmangsuran`
  MODIFY `id_angsuran` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tmpinjaman`
--
ALTER TABLE `tmpinjaman`
  MODIFY `id_pinjaman` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tmrekening`
--
ALTER TABLE `tmrekening`
  MODIFY `id_rekening` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tmsimpanan`
--
ALTER TABLE `tmsimpanan`
  MODIFY `id_simpanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tmtransaksi`
--
ALTER TABLE `tmtransaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trakun`
--
ALTER TABLE `trakun`
  MODIFY `id_akun` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `trbunga`
--
ALTER TABLE `trbunga`
  MODIFY `id_bunga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `trnasabah`
--
ALTER TABLE `trnasabah`
  MODIFY `id_nasabah` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `trprogram`
--
ALTER TABLE `trprogram`
  MODIFY `id_program` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tmangsuran`
--
ALTER TABLE `tmangsuran`
  ADD CONSTRAINT `tmangsuran_ibfk_1` FOREIGN KEY (`id_pinjaman`) REFERENCES `tmpinjaman` (`id_pinjaman`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tmpinjaman`
--
ALTER TABLE `tmpinjaman`
  ADD CONSTRAINT `tmpinjaman_ibfk_1` FOREIGN KEY (`id_nasabah`) REFERENCES `trnasabah` (`id_nasabah`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tmrekening`
--
ALTER TABLE `tmrekening`
  ADD CONSTRAINT `tmrekening_ibfk_1` FOREIGN KEY (`id_nasabah`) REFERENCES `trnasabah` (`id_nasabah`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tmsimpanan`
--
ALTER TABLE `tmsimpanan`
  ADD CONSTRAINT `tmsimpanan_ibfk_1` FOREIGN KEY (`id_rekening`) REFERENCES `trnasabah` (`id_nasabah`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_nasabah`) REFERENCES `trnasabah` (`id_nasabah`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
