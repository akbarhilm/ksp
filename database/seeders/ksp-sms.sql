-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2025 at 10:16 AM
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
-- Table structure for table `tmjurnal`
--

CREATE TABLE `tmjurnal` (
  `id_jurnal` int(11) NOT NULL,
  `id_akun` int(11) NOT NULL,
  `id_simpanan` int(11) DEFAULT NULL,
  `id_pinjaman` int(11) DEFAULT NULL,
  `tanggal_transaksi` date NOT NULL DEFAULT current_timestamp(),
  `keterangan` varchar(100) NOT NULL,
  `v_debet` decimal(10,0) NOT NULL,
  `v_kredit` decimal(10,0) NOT NULL,
  `id_entry` int(11) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tmjurnal`
--

INSERT INTO `tmjurnal` (`id_jurnal`, `id_akun`, `id_simpanan`, `id_pinjaman`, `tanggal_transaksi`, `keterangan`, `v_debet`, `v_kredit`, `id_entry`, `created_at`, `updated_at`) VALUES
(7, 1, 11, NULL, '2025-11-12', 'kas', '1000000', '0', 1, '2025-11-15', '2025-11-15'),
(8, 14, 11, NULL, '2025-11-12', 'Tabungan wajib anggota 00042', '0', '1000000', 1, '2025-11-15', '2025-11-15'),
(9, 1, 14, NULL, '2025-11-13', 'kas', '25000000', '0', 1, '2025-11-15', '2025-11-15'),
(10, 16, 14, NULL, '2025-11-13', 'Deposito anggota 00042', '0', '25000000', 1, '2025-11-15', '2025-11-15'),
(13, 1, 19, NULL, '2025-11-14', 'kas', '1000000', '0', 1, '2025-11-15', '2025-11-15'),
(14, 14, 19, NULL, '2025-11-14', 'Tabungan wajib anggota 00043', '0', '1000000', 1, '2025-11-15', '2025-11-15'),
(15, 5, NULL, 1, '2025-11-15', 'Piutang Pinjaman Anggota 00043', '3000000', '0', 1, '2025-11-15', '2025-11-15'),
(16, 1, NULL, 1, '2025-11-15', 'Kas', '0', '3000000', 1, '2025-11-15', '2025-11-15'),
(19, 5, NULL, 3, '2025-11-17', 'Piutang Pinjaman Anggota 00042', '3000000', '0', 1, '2025-11-17', '2025-11-17'),
(20, 1, NULL, 3, '2025-11-17', 'Kas', '0', '3000000', 1, '2025-11-17', '2025-11-17'),
(21, 1, NULL, 3, '2025-11-17', 'Pembayaran angsuran pinjaman 00042', '530000', '0', 1, '2025-11-17', NULL),
(22, 5, NULL, 3, '2025-11-17', 'Pengurangan pokok pinjaman 00042', '0', '500000', 1, '2025-11-17', NULL),
(23, 26, NULL, 3, '2025-11-17', 'Pendapatan bunga pinjaman 00042', '0', '30000', 1, '2025-11-17', NULL),
(27, 1, NULL, NULL, '2025-11-17', 'test', '1000000', '0', 1, '2025-11-17', '2025-11-17'),
(28, 15, NULL, NULL, '2025-11-17', 'test', '0', '1000000', 1, '2025-11-17', '2025-11-17'),
(29, 5, NULL, 4, '2025-11-20', 'Piutang Pinjaman Anggota 00050', '6000000', '0', 1, '2025-11-20', '2025-11-20'),
(30, 1, NULL, 4, '2025-11-20', 'Kas', '0', '6000000', 1, '2025-11-20', '2025-11-20'),
(39, 3, NULL, 4, '2025-11-21', 'Pembayaran angsuran pinjaman 00050', '1140000', '0', 1, '2025-11-21', NULL),
(40, 26, NULL, 4, '2025-11-21', 'Pendapatan bunga pinjaman 00050', '0', '120000', 1, '2025-11-21', NULL),
(41, 29, NULL, 4, '2025-11-21', 'Pendapatan bunga pinjaman 00050', '0', '6000', 1, '2025-11-21', NULL),
(42, 13, NULL, NULL, '2025-11-21', 'Simpanan pokok 00050', '0', '14000', 1, '2025-11-21', NULL),
(43, 5, NULL, 4, '2025-11-21', 'Piutang pinjaman 00050', '0', '1000000', 1, '2025-11-21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tmpembayaran`
--

CREATE TABLE `tmpembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_pinjaman` int(11) NOT NULL,
  `tanggal` date NOT NULL DEFAULT current_timestamp(),
  `total_bayar` decimal(10,0) NOT NULL,
  `bayar_bunga` decimal(10,0) NOT NULL,
  `bayar_pokok` decimal(10,0) NOT NULL,
  `bayar_denda` decimal(10,0) NOT NULL DEFAULT 0,
  `simpanan` decimal(10,0) NOT NULL,
  `metode` enum('ATM','Auto Debit','Cash') NOT NULL,
  `cicilan_ke` int(11) NOT NULL,
  `id_entry` int(11) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tmpembayaran`
--

INSERT INTO `tmpembayaran` (`id_pembayaran`, `id_pinjaman`, `tanggal`, `total_bayar`, `bayar_bunga`, `bayar_pokok`, `bayar_denda`, `simpanan`, `metode`, `cicilan_ke`, `id_entry`, `created_at`, `updated_at`) VALUES
(2, 3, '2025-11-08', '530000', '30000', '500000', '0', '0', 'ATM', 1, 1, '2025-11-17', '2025-11-17'),
(5, 4, '2025-11-21', '1140000', '120000', '1000000', '6000', '14000', 'ATM', 1, 1, '2025-11-21', '2025-11-21');

-- --------------------------------------------------------

--
-- Table structure for table `tmpengajuan`
--

CREATE TABLE `tmpengajuan` (
  `id_pengajuan` int(11) NOT NULL,
  `id_rekening` int(11) NOT NULL,
  `bunga` float NOT NULL,
  `tenor` int(3) NOT NULL,
  `tanggal_pengajuan` date DEFAULT current_timestamp(),
  `tanggal_approval` date DEFAULT NULL,
  `tanggal_pencairan` date DEFAULT NULL,
  `jumlah_pengajuan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `jumlah_pencairan` decimal(15,2) DEFAULT NULL,
  `status` enum('approv','cair','tolak','pengajuan','cancel') NOT NULL DEFAULT 'pengajuan',
  `simpanan_wajib` decimal(10,0) NOT NULL,
  `admin` decimal(10,0) NOT NULL,
  `asuransi` decimal(10,0) NOT NULL,
  `id_entry` int(11) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tmpengajuan`
--

INSERT INTO `tmpengajuan` (`id_pengajuan`, `id_rekening`, `bunga`, `tenor`, `tanggal_pengajuan`, `tanggal_approval`, `tanggal_pencairan`, `jumlah_pengajuan`, `jumlah_pencairan`, `status`, `simpanan_wajib`, `admin`, `asuransi`, `id_entry`, `created_at`, `updated_at`) VALUES
(3, 6, 0, 0, '2025-11-15', '2025-11-15', '2025-11-15', '3000000.00', '3000000.00', 'cair', '0', '0', '0', 1, '2025-11-15', '2025-11-15'),
(4, 5, 0, 0, '2025-11-17', '2025-11-17', '2025-11-17', '3000000.00', '3000000.00', 'cair', '0', '0', '0', 1, '2025-11-17', '2025-11-17'),
(13, 19, 2, 6, '2025-11-20', '2025-11-20', '2025-10-20', '6000000.00', '6000000.00', 'cair', '100000', '100000', '0', 1, '2025-11-20', '2025-11-20');

-- --------------------------------------------------------

--
-- Table structure for table `tmpengajuandetail`
--

CREATE TABLE `tmpengajuandetail` (
  `id_pengajuandetail` int(11) NOT NULL,
  `id_pengajuan` int(11) NOT NULL,
  `jenis_jaminan` varchar(100) NOT NULL,
  `keterangan` varchar(200) NOT NULL,
  `id_entry` int(11) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tmpengajuandetail`
--

INSERT INTO `tmpengajuandetail` (`id_pengajuandetail`, `id_pengajuan`, `jenis_jaminan`, `keterangan`, `id_entry`, `created_at`, `updated_at`) VALUES
(3, 12, 'ATM', 'BCA 5621322485', 1, '2025-11-19', '2025-11-19'),
(4, 12, 'PIN ATM', '1234567', 1, '2025-11-19', '2025-11-19'),
(5, 12, 'JAMSOSTEK', '987456124', 1, '2025-11-19', '2025-11-19'),
(6, 13, 'ATM', 'BCA 5621322485', 1, '2025-11-20', '2025-11-20'),
(7, 13, 'PIN ATM', '1234567', 1, '2025-11-20', '2025-11-20');

-- --------------------------------------------------------

--
-- Table structure for table `tmpinjaman`
--

CREATE TABLE `tmpinjaman` (
  `id_pinjaman` int(11) NOT NULL,
  `id_pengajuan` int(11) NOT NULL,
  `id_nasabah` int(11) NOT NULL,
  `total_pinjaman` decimal(10,0) NOT NULL,
  `sisa_pokok` decimal(10,0) NOT NULL,
  `sisa_bunga` decimal(10,0) NOT NULL,
  `status` enum('aktif','lunas') NOT NULL DEFAULT 'aktif',
  `id_entry` int(11) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tmpinjaman`
--

INSERT INTO `tmpinjaman` (`id_pinjaman`, `id_pengajuan`, `id_nasabah`, `total_pinjaman`, `sisa_pokok`, `sisa_bunga`, `status`, `id_entry`, `created_at`, `updated_at`) VALUES
(1, 3, 43, '3000000', '3000000', '180000', 'aktif', 1, '2025-11-15', NULL),
(3, 4, 42, '3000000', '2500000', '150000', 'aktif', 1, '2025-11-17', '2025-11-17'),
(4, 13, 50, '6000000', '5000000', '600000', 'aktif', 1, '2025-11-20', '2025-11-21');

-- --------------------------------------------------------

--
-- Table structure for table `tmrekening`
--

CREATE TABLE `tmrekening` (
  `id_rekening` int(11) NOT NULL,
  `id_nasabah` int(11) NOT NULL,
  `no_rekening` varchar(20) NOT NULL,
  `jenis_rekening` enum('Tabungan','Deposito','Pinjaman','') NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'nonaktif',
  `id_entry` int(11) NOT NULL,
  `updated_at` date DEFAULT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tmrekening`
--

INSERT INTO `tmrekening` (`id_rekening`, `id_nasabah`, `no_rekening`, `jenis_rekening`, `status`, `id_entry`, `updated_at`, `created_at`) VALUES
(2, 42, '12500042', 'Tabungan', 'nonaktif', 1, '2025-10-30', '2025-10-30'),
(3, 42, '22500042', 'Deposito', 'nonaktif', 1, '2025-10-30', '2025-10-30'),
(4, 43, '12500043', 'Tabungan', 'nonaktif', 1, '2025-11-03', '2025-11-03'),
(5, 42, '32500042', 'Pinjaman', 'nonaktif', 1, '2025-11-05', '2025-11-05'),
(6, 43, '32500043', 'Pinjaman', 'nonaktif', 1, '2025-11-15', '2025-11-15'),
(7, 45, '12500045', 'Tabungan', 'nonaktif', 1, '2025-11-16', '2025-11-16'),
(17, 50, '12500050', 'Tabungan', 'aktif', 1, '2025-11-19', '2025-11-19'),
(18, 50, '22500050', 'Deposito', 'nonaktif', 1, '2025-11-19', '2025-11-19'),
(19, 50, '32500050', 'Pinjaman', 'aktif', 1, '2025-11-19', '2025-11-19');

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
(11, 2, 14, '2025-11-15', 'wajib', '0.00', '1000000.00', 'tabungan', 1, '2025-11-15', '2025-11-15'),
(14, 3, 16, '2025-11-15', 'wajib', '0.00', '25000000.00', 'deposit', 1, '2025-11-15', '2025-11-15'),
(19, 4, 14, '2025-11-15', 'wajib', '0.00', '1000000.00', 'tabungan', 1, '2025-11-15', '2025-11-15'),
(22, 17, 13, '2025-11-21', 'pokok', '0.00', '14000.00', 'Simpanan dari angsuran', 1, '2025-11-21', '2025-11-21');

-- --------------------------------------------------------

--
-- Table structure for table `tmtransaksi`
--

CREATE TABLE `tmtransaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_akun` int(11) NOT NULL,
  `id_rekening` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT curdate(),
  `jenis_transaksi` enum('simpanan','pinjaman','angsuran','pengeluaran','lainnya') NOT NULL,
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
  `kode_akun` varchar(5) NOT NULL,
  `nama_akun` varchar(100) NOT NULL,
  `tipe_akun` enum('Aset','Kewajiban','Modal','Pendapatan','Beban') NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `id_entry` int(11) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trakun`
--

INSERT INTO `trakun` (`id_akun`, `kode_akun`, `nama_akun`, `tipe_akun`, `status`, `id_entry`, `created_at`, `updated_at`) VALUES
(1, '101', 'Kas', 'Aset', 'aktif', 0, '2025-11-21', NULL),
(2, '102', 'Kas Kecil', 'Aset', 'aktif', 0, '2025-11-21', NULL),
(3, '103', 'Bank', 'Aset', 'aktif', 0, '2025-11-21', NULL),
(4, '104', 'Setoran Pada Bank', 'Aset', 'aktif', 0, '2025-11-21', NULL),
(5, '105', 'Piutang Pinjaman Anggota', 'Aset', 'aktif', 0, '2025-11-21', NULL),
(6, '106', 'Piutang Bunga', 'Aset', 'aktif', 0, '2025-11-21', NULL),
(7, '107', 'Piutang Lain-lain', 'Aset', 'aktif', 0, '2025-11-21', NULL),
(8, '108', 'Pendapatan Diterima Dimuka', 'Aset', 'aktif', 0, '2025-11-21', NULL),
(9, '121', 'Peralatan Kantor', 'Aset', 'aktif', 0, '2025-11-21', NULL),
(10, '122', 'Akumulasi Penyusutan Peralatan', 'Aset', 'aktif', 0, '2025-11-21', NULL),
(11, '123', 'Inventaris Kantor', 'Aset', 'aktif', 0, '2025-11-21', NULL),
(12, '124', 'Akumulasi Penyusutan Inventaris', 'Aset', 'aktif', 0, '2025-11-21', NULL),
(13, '201', 'Simpanan Pokok Anggota', 'Kewajiban', 'aktif', 0, '2025-11-21', NULL),
(14, '202', 'Simpanan Wajib Anggota', 'Kewajiban', 'aktif', 0, '2025-11-21', NULL),
(15, '203', 'Tabungan / Simpanan Sukarela Anggota', 'Kewajiban', 'aktif', 0, '2025-11-21', NULL),
(16, '204', 'Deposito Berjangka Anggota', 'Kewajiban', 'aktif', 0, '2025-11-21', NULL),
(17, '205', 'Simpanan Khusus', 'Kewajiban', 'aktif', 0, '2025-11-21', NULL),
(18, '221', 'Hutang Bunga', 'Kewajiban', 'aktif', 0, '2025-11-21', NULL),
(19, '222', 'Hutang Jangka Pendek', 'Kewajiban', 'aktif', 0, '2025-11-21', NULL),
(20, '223', 'Hutang Lain-lain', 'Kewajiban', 'aktif', 0, '2025-11-21', NULL),
(21, '301', 'Modal Awal', 'Modal', 'aktif', 0, '2025-11-21', NULL),
(22, '302', 'Donasi dan Hibah', 'Modal', 'aktif', 0, '2025-11-21', NULL),
(23, '303', 'Cadangan Umum', 'Modal', 'aktif', 0, '2025-11-21', NULL),
(24, '304', 'SHU Tahun Berjalan', 'Modal', 'aktif', 0, '2025-11-21', NULL),
(25, '305', 'SHU Ditahan', 'Modal', 'aktif', 0, '2025-11-21', NULL),
(26, '401', 'Pendapatan Bunga Pinjaman', 'Pendapatan', 'aktif', 0, '2025-11-21', NULL),
(27, '402', 'Pendapatan Administrasi', 'Pendapatan', 'aktif', 0, '2025-11-21', NULL),
(28, '403', 'Pendapatan Provisi', 'Pendapatan', 'aktif', 0, '2025-11-21', NULL),
(29, '404', 'Pendapatan Denda', 'Pendapatan', 'aktif', 0, '2025-11-21', NULL),
(30, '405', 'Pendapatan Lain-lain', 'Pendapatan', 'aktif', 0, '2025-11-21', NULL),
(31, '501', 'Beban Bunga Simpanan', 'Beban', 'aktif', 0, '2025-11-21', NULL),
(32, '502', 'Beban Administrasi Bank', 'Beban', 'aktif', 0, '2025-11-21', NULL),
(33, '503', 'Beban Listrik', 'Beban', 'aktif', 0, '2025-11-21', NULL),
(34, '504', 'Beban ATK', 'Beban', 'aktif', 0, '2025-11-21', NULL),
(35, '505', 'Beban Penyusutan', 'Beban', 'aktif', 0, '2025-11-21', NULL),
(36, '506', 'Beban Transportasi', 'Beban', 'aktif', 0, '2025-11-21', NULL),
(37, '507', 'Beban Gaji dan Honor', 'Beban', 'aktif', 0, '2025-11-21', NULL),
(38, '508', 'Beban Operasional Lain', 'Beban', 'aktif', 0, '2025-11-21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `trbunga`
--

CREATE TABLE `trbunga` (
  `id_bunga` int(11) NOT NULL,
  `jenis_bunga` varchar(50) NOT NULL,
  `nama_bunga` varchar(100) NOT NULL,
  `persentase` float NOT NULL,
  `threshold` int(11) NOT NULL,
  `persentase2` float NOT NULL,
  `threshold2` int(11) NOT NULL,
  `id_entry` int(11) NOT NULL,
  `updated_at` date DEFAULT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trbunga`
--

INSERT INTO `trbunga` (`id_bunga`, `jenis_bunga`, `nama_bunga`, `persentase`, `threshold`, `persentase2`, `threshold2`, `id_entry`, `updated_at`, `created_at`) VALUES
(1, 'Simpanan', 'Simpanan tabungan', 0.3, 500000, 0, 0, 0, NULL, '2025-10-29'),
(2, 'Simpanan', 'Simpanan Deposito', 3, 0, 0, 0, 0, NULL, '2025-10-29'),
(4, 'Denda', 'denda test', 0.1, 1, 1, 30, 1, '2025-11-20', '2025-11-20');

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
(43, '321708080808', 'as', 'asdasdasdads', '2025-11-03', 'karyawan', '-', '08123546789', 'Pertanian', '1', '2025-11-03', 'aktif', '2025-11-03', '2025-11-03'),
(45, '1234', 'tanpa pinjaman', 'kkkk', '2025-11-16', 'karyawan', '-', '0987', 'Lainnya', '1', '2025-11-16', 'aktif', '2025-11-16', '2025-11-16'),
(50, '3217081902940007', 'update', 'asd', '1994-02-19', 'karyawan', '-', '08123546789', 'PNS', '1', '2025-11-19', 'aktif', '2025-11-19', '2025-11-19');

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

--
-- Dumping data for table `trprogram`
--

INSERT INTO `trprogram` (`id_program`, `id_bunga`, `nama_program`, `plafond`, `tenor`, `id_entry`, `created_at`, `updated_at`) VALUES
(1, 3, 'Urgent', 3000000, 6, 1, '2025-11-05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','bendahara','anggota') DEFAULT 'anggota',
  `nama` varchar(200) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `id_nasabah` int(11) DEFAULT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `nama`, `jabatan`, `id_nasabah`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$12$8T0Tgti.jsm72fNpx8lS2OyGVeEAze54JtRAHP2BEXZ3li/ZfxJ0O', 'admin', '', '', NULL, '2025-11-17', NULL),
(4, 'test', '$2y$12$Q0giK.OuJh5kvZAoJfoLduthlOSYU8KAe2jEtgz8zmNMcsmJ1ma2u', 'bendahara', 'test', '', NULL, '2025-11-17', '2025-11-17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tmjurnal`
--
ALTER TABLE `tmjurnal`
  ADD PRIMARY KEY (`id_jurnal`);

--
-- Indexes for table `tmpembayaran`
--
ALTER TABLE `tmpembayaran`
  ADD PRIMARY KEY (`id_pembayaran`);

--
-- Indexes for table `tmpengajuan`
--
ALTER TABLE `tmpengajuan`
  ADD PRIMARY KEY (`id_pengajuan`);

--
-- Indexes for table `tmpengajuandetail`
--
ALTER TABLE `tmpengajuandetail`
  ADD PRIMARY KEY (`id_pengajuandetail`);

--
-- Indexes for table `tmpinjaman`
--
ALTER TABLE `tmpinjaman`
  ADD PRIMARY KEY (`id_pinjaman`);

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
-- AUTO_INCREMENT for table `tmjurnal`
--
ALTER TABLE `tmjurnal`
  MODIFY `id_jurnal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `tmpembayaran`
--
ALTER TABLE `tmpembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tmpengajuan`
--
ALTER TABLE `tmpengajuan`
  MODIFY `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tmpengajuandetail`
--
ALTER TABLE `tmpengajuandetail`
  MODIFY `id_pengajuandetail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tmpinjaman`
--
ALTER TABLE `tmpinjaman`
  MODIFY `id_pinjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tmrekening`
--
ALTER TABLE `tmrekening`
  MODIFY `id_rekening` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tmsimpanan`
--
ALTER TABLE `tmsimpanan`
  MODIFY `id_simpanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tmtransaksi`
--
ALTER TABLE `tmtransaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trakun`
--
ALTER TABLE `trakun`
  MODIFY `id_akun` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `trbunga`
--
ALTER TABLE `trbunga`
  MODIFY `id_bunga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `trnasabah`
--
ALTER TABLE `trnasabah`
  MODIFY `id_nasabah` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `trprogram`
--
ALTER TABLE `trprogram`
  MODIFY `id_program` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tmrekening`
--
ALTER TABLE `tmrekening`
  ADD CONSTRAINT `tmrekening_ibfk_1` FOREIGN KEY (`id_nasabah`) REFERENCES `trnasabah` (`id_nasabah`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
