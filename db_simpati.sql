-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2022 at 05:31 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.4.20

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_simpati`
--
CREATE DATABASE IF NOT EXISTS `db_simpati` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_simpati`;

-- --------------------------------------------------------

--
-- Table structure for table `acl_role_menu`
--

DROP TABLE IF EXISTS `acl_role_menu`;
CREATE TABLE `acl_role_menu` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `acl_role_menu`
--

INSERT INTO `acl_role_menu` (`id`, `role_id`, `menu_id`, `created_at`) VALUES
(157, 1, 40, '2022-05-07 16:15:34'),
(158, 1, 41, '2022-05-07 16:15:34'),
(159, 1, 32, '2022-05-07 16:15:34'),
(160, 1, 48, '2022-05-07 16:15:34'),
(161, 1, 33, '2022-05-07 16:15:34'),
(162, 1, 31, '2022-05-07 16:15:34'),
(163, 1, 47, '2022-05-07 16:15:34'),
(164, 1, 23, '2022-05-07 16:15:34'),
(165, 1, 28, '2022-05-07 16:15:34'),
(166, 1, 30, '2022-05-07 16:15:34'),
(167, 1, 26, '2022-05-07 16:15:34'),
(168, 1, 35, '2022-05-07 16:15:34'),
(169, 1, 36, '2022-05-07 16:15:34'),
(170, 1, 42, '2022-05-07 16:15:34'),
(171, 1, 50, '2022-05-07 16:15:34'),
(172, 1, 49, '2022-05-07 16:15:34'),
(173, 1, 17, '2022-05-07 16:15:34'),
(174, 1, 38, '2022-05-07 16:15:34'),
(175, 1, 44, '2022-05-07 16:15:34'),
(176, 1, 45, '2022-05-07 16:15:34');

-- --------------------------------------------------------

--
-- Table structure for table `mst_brand`
--

DROP TABLE IF EXISTS `mst_brand`;
CREATE TABLE `mst_brand` (
  `id` int(11) NOT NULL,
  `nama` varchar(500) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `user_modified` varchar(200) DEFAULT NULL,
  `is_deleted` int(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mst_brand`
--

INSERT INTO `mst_brand` (`id`, `nama`, `description`, `is_active`, `user_modified`, `is_deleted`, `created_at`, `updated_at`) VALUES
(2, 'General', 'Merek general atau umum yang sedikit jumlah nya atau tidak ada yang bertanggung jawab khusus terhadap merk tersebut', 1, 'Admin', 0, '2022-05-01 07:07:17', '2022-05-06 05:23:27'),
(3, 'La Tulipe', 'Merek La tulipe silahkan edit untuk melengkapi deskripsi', 1, 'SYSTEM', 0, '2022-05-01 07:08:20', NULL),
(4, 'Wardah', 'Merek Wardah silahkan edit data untuk melengkapi deskripsi', 1, 'SYSTEM', 0, '2022-05-01 07:08:42', NULL),
(5, 'Inez', 'Merek Inez silahkan edit data untuk melengkapi deskripsi', 1, 'SYSTEM', 0, '2022-05-01 07:08:51', NULL),
(6, 'Testing', 'untuk keperluan testing ggggg', 1, 'SYSTEM', 1, '2022-05-01 07:09:11', '2022-05-01 07:11:23');

-- --------------------------------------------------------

--
-- Table structure for table `mst_menu`
--

DROP TABLE IF EXISTS `mst_menu`;
CREATE TABLE `mst_menu` (
  `id` int(11) NOT NULL,
  `nama` varchar(500) NOT NULL,
  `icon` varchar(500) DEFAULT NULL,
  `link` varchar(500) NOT NULL,
  `parent_id` int(2) NOT NULL,
  `ordinal` int(2) NOT NULL DEFAULT 1,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `is_deleted` int(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_modified` varchar(200) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mst_menu`
--

INSERT INTO `mst_menu` (`id`, `nama`, `icon`, `link`, `parent_id`, `ordinal`, `is_active`, `is_deleted`, `created_at`, `user_modified`, `updated_at`) VALUES
(0, 'MAIN', '#', '#', 0, 0, 1, 0, '2022-04-10 15:13:09', NULL, NULL),
(14, 'Pengaturan Aplikasi', 'ni ni-single-copy-04 text-orange', 'setting', 0, 2, 1, 0, '2022-04-15 17:43:30', NULL, NULL),
(15, 'test', 'ni ni-single-copy-04 text-orange', 'approve', 14, 1, 1, 1, '2022-04-15 17:43:46', NULL, NULL),
(16, 'asd', 'ni ni-single-copy-04 text-orange', 'approve', 14, 1, 1, 1, '2022-04-15 17:44:02', NULL, NULL),
(17, 'Menu Aplikasi', 'fa fa-check-double text-primary', 'menu', 14, 1, 1, 0, '2022-04-15 17:44:39', NULL, NULL),
(18, 'test', 'ni ni-single-copy-04 text-orange', 'te', 17, 1, 1, 1, '2022-04-23 07:33:55', NULL, NULL),
(19, 'test', 'ni ni-single-copy-04 text-orange', 'approve', 18, 1, 1, 1, '2022-04-23 07:34:32', NULL, NULL),
(20, 'test', 'ni ni-single-copy-04 text-orange', 'setting', 0, 1, 1, 1, '2022-04-23 07:55:20', NULL, NULL),
(21, 'Hak Akses', 'fa fa-check-double text-primary', 'accessControl', 14, 2, 1, 0, '2022-04-23 08:20:22', NULL, NULL),
(22, 'Master Data', 'ni ni-tablet-button text-primary', 'master', 0, 2, 1, 1, '2022-04-23 08:21:59', NULL, NULL),
(23, 'Dashboard', 'ni ni-tablet-button text-primary', '/', 0, 1, 1, 0, '2022-04-23 08:25:57', NULL, NULL),
(24, 'Barang', 'ni ni-single-copy-04 text-orange', 'barang', 0, 3, 1, 0, '2022-04-23 08:26:49', NULL, NULL),
(25, 'Pelanggan', 'fa fa-users text-info', 'customer', 34, 2, 1, 0, '2022-04-23 08:28:26', 'Admin', '2022-05-07 16:16:10'),
(26, 'Supplier', 'fa fa-users text-info', 'supplier', 34, 1, 1, 0, '2022-04-23 08:28:49', NULL, NULL),
(27, 'Penjualan', 'ni ni-single-copy-04 text-orange', 'penjualan', 0, 6, 1, 0, '2022-04-23 08:30:29', NULL, NULL),
(28, 'Pembelian', 'ni ni-single-copy-04 text-orange', 'pembelian', 0, 7, 1, 0, '2022-04-23 08:33:55', NULL, NULL),
(29, 'Point Of Sales', 'ni ni-single-copy-04 text-orange', 'pos', 0, 8, 0, 0, '2022-04-23 08:50:38', 'Admin', '2022-05-06 05:24:20'),
(30, 'Laporan', 'ni ni-single-copy-04 text-orange', 'report', 0, 9, 1, 0, '2022-04-23 08:51:40', NULL, NULL),
(31, 'Hak Akses Menu', 'fa fa-check-double text-primary', 'menuAccess', 21, 2, 1, 0, '2022-04-29 07:10:13', NULL, NULL),
(32, 'Daftar Barang', 'ni ni-tablet-button text-primary', 'listbarang', 24, 1, 1, 0, '2022-04-29 07:11:47', NULL, NULL),
(33, 'Daftar Stok', 'fa fa-check-double text-primary', 'liststock', 24, 2, 1, 0, '2022-04-29 07:12:38', NULL, NULL),
(34, 'Master Data', 'ni ni-single-copy-04 text-orange', 'master', 0, 7, 1, 0, '2022-04-29 07:16:58', NULL, NULL),
(35, 'Merk (Brand)', 'ni ni-single-copy-04 text-orange', 'brand', 34, 3, 1, 0, '2022-04-29 07:18:24', NULL, NULL),
(36, 'Kategori', 'ni ni-single-copy-04 text-orange', 'category', 34, 4, 1, 0, '2022-04-29 07:19:22', NULL, NULL),
(37, 'Penjualan Langsung (POS)', 'ni ni-single-copy-04 text-orange', 'penjualanPos', 27, 1, 0, 0, '2022-04-29 07:29:39', 'Admin', '2022-05-06 05:23:53'),
(38, 'Penjualan dari Pesanan', 'ni ni-single-copy-04 text-orange', 'penjualanPesanan', 27, 2, 1, 0, '2022-04-29 07:31:03', NULL, NULL),
(39, 'Audit', 'ni ni-single-copy-04 text-orange', 'audit', 0, 8, 1, 0, '2022-04-29 07:32:52', NULL, NULL),
(40, 'Stock Opname', 'ni ni-single-copy-04 text-orange', 'StockOpname', 39, 1, 1, 0, '2022-04-29 07:34:08', NULL, NULL),
(41, 'Penyesuaian Stok', 'ni ni-single-copy-04 text-orange', 'stockAdjusment', 39, 1, 1, 0, '2022-04-29 07:34:39', NULL, NULL),
(42, 'Satuan Kemasan', 'fa fa-check-double text-primary', 'packing', 34, 4, 1, 0, '2022-04-29 07:47:19', NULL, NULL),
(43, 'User Aplikasi', 'fa fa-users text-info', 'user', 34, 3, 1, 0, '2022-04-29 17:55:36', NULL, NULL),
(44, 'Daftar User', 'fa fa-users text-info', 'users', 43, 1, 1, 0, '2022-04-29 18:03:26', NULL, NULL),
(45, 'Daftar Role User', 'fa fa-users text-info', 'role', 43, 2, 1, 0, '2022-04-29 18:03:56', NULL, NULL),
(46, 'test', 'ni ni-single-copy-04 text-orange', 'approve', 31, 1, 1, 1, '2022-04-30 05:51:27', NULL, NULL),
(47, 'Hak Akses Data', 'fa fa-check-double text-primary', 'dataAccess', 21, 2, 1, 0, '2022-04-30 12:44:54', 'SYSTEM', '2022-05-01 16:51:19'),
(48, 'Tambah Barang Baru', 'ni ni-single-copy-04 text-orange', 'add', 24, 1, 1, 0, '2022-05-06 14:59:36', 'Admin', NULL),
(49, 'Grup Pelanggan', 'fa fa-check-double text-primary', 'customergroup', 25, 2, 1, 0, '2022-05-07 16:13:26', 'Admin', '2022-05-07 16:15:26'),
(50, 'Daftar Pelanggan', 'fa fa-users text-info', 'customer', 25, 1, 1, 0, '2022-05-07 16:15:11', 'Admin', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mst_packing`
--

DROP TABLE IF EXISTS `mst_packing`;
CREATE TABLE `mst_packing` (
  `id` int(11) NOT NULL,
  `nama` varchar(500) NOT NULL,
  `unit` varchar(200) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT 1,
  `description` varchar(500) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `user_modified` varchar(200) DEFAULT NULL,
  `is_deleted` int(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mst_packing`
--

INSERT INTO `mst_packing` (`id`, `nama`, `unit`, `amount`, `description`, `parent_id`, `is_active`, `user_modified`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Dus', 'dus20', 20, 'Dus besar', 0, 1, 'Admin', 0, '2022-05-06 04:33:58', '2022-05-06 05:19:00'),
(2, 'Pack', 'pack10', 10, 'pack besar', 1, 1, 'Admin', 0, '2022-05-06 04:38:56', '2022-05-06 05:19:00'),
(3, 'Bks', 'bungkus12', 12, 'bungnkus kecil', 2, 1, 'Admin', 0, '2022-05-06 04:44:32', '2022-05-06 05:19:15'),
(4, 'batang', 'btg', 1, 'per batang tang tang', 3, 1, 'Admin', 0, '2022-05-06 04:45:15', '2022-05-06 05:19:15');

-- --------------------------------------------------------

--
-- Table structure for table `mst_role`
--

DROP TABLE IF EXISTS `mst_role`;
CREATE TABLE `mst_role` (
  `id` int(11) NOT NULL,
  `nama` varchar(500) NOT NULL,
  `description` varchar(500) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `user_modified` varchar(200) DEFAULT NULL,
  `is_deleted` int(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mst_role`
--

INSERT INTO `mst_role` (`id`, `nama`, `description`, `is_active`, `user_modified`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'IT System Administrator', 'administrator untuk pemeliharaan aplikasi', 1, NULL, 0, '2022-04-30 12:26:38', NULL),
(2, 'Administrator', 'Administrator untuk akses semua menu', 1, NULL, 0, '2022-04-30 12:29:52', NULL),
(3, 'Kasir', 'User untuk akses menu kasir', 1, NULL, 0, '2022-04-30 12:30:28', NULL),
(4, 'Beauty Advisor', 'User untuk manajemen barang serta pembelian dari merek tertentu', 1, 'Admin', 0, '2022-04-30 12:31:22', '2022-05-06 05:22:45'),
(5, 'Manajemen', 'User untuk akses Laporan dan monitoring penjualan, pembelian serta barang', 1, 'SYSTEM', 0, '2022-04-30 12:33:01', '2022-05-01 16:39:51'),
(6, 'Admin Barang', 'User untuk manajemen semua barang', 1, 'SYSTEM', 0, '2022-04-30 12:34:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mst_users`
--

DROP TABLE IF EXISTS `mst_users`;
CREATE TABLE `mst_users` (
  `id` int(11) NOT NULL,
  `nama` varchar(500) NOT NULL,
  `username` varchar(500) NOT NULL,
  `password` varchar(500) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `user_modified` varchar(200) DEFAULT NULL,
  `is_deleted` int(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mst_users`
--

INSERT INTO `mst_users` (`id`, `nama`, `username`, `password`, `is_active`, `user_modified`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'IT Administrator', 'Admin', '$2y$05$0Lun2sBMihMidViJJYGCJeERtqVmAGnqU2XQHGxkeSKwKCdlmzx4W', 1, 'SYSTEM', 0, '2022-05-01 12:26:47', NULL),
(2, 'Testing Purpose', 'test', '$2y$05$r/8L63jIhUapZzs0HMlChej8qVtjWwMtdJYI4c7WLrEua1Y4M4Yfi', 1, 'SYSTEM', 0, '2022-05-01 12:27:38', '2022-05-01 16:37:03'),
(3, 'test', 'test laggi', '$2y$05$7fUqFmboFVG7w3pRiejLNe1/QrDPZC5VnXqp.a/PYNcypwZSQkTTW', 1, 'Admin', 0, '2022-05-01 12:48:24', '2022-05-06 05:20:46'),
(4, 'Lukman', 'Test  Input', '$2y$05$53/ciRcvpVbUIun6iHL29.AX2LSSZkLU/UL8TW8JZAQ0qEo0bGG2.', 1, 'SYSTEM', 0, '2022-05-01 13:49:13', '2022-05-01 16:36:34'),
(5, 'tetetet', 'tetetet', '$2y$05$73NlY60EJCg/9XLHU8GhvevItRsuqTA0LmDyCU3//5MDrLQfM.ZfK', 1, 'Admin', 0, '2022-05-01 13:52:11', '2022-05-05 17:20:48');

-- --------------------------------------------------------

--
-- Table structure for table `penggunaan_mesin`
--

DROP TABLE IF EXISTS `penggunaan_mesin`;
CREATE TABLE `penggunaan_mesin` (
  `id` int(11) NOT NULL,
  `id_mesin` int(11) DEFAULT NULL,
  `remark` varchar(100) NOT NULL,
  `shift` char(10) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `time_stamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `penggunaan_mesin`
--

INSERT INTO `penggunaan_mesin` (`id`, `id_mesin`, `remark`, `shift`, `tanggal`, `time_stamp`) VALUES
(1, 1, 'OKE', '1', '2022-03-01', '2022-04-23 09:16:13'),
(2, 1, 'OLI HABIS', '2', '2022-03-01', '2022-04-23 09:16:13'),
(3, 1, 'OperatorTidur', '1', '2022-03-02', '2022-04-23 09:16:13'),
(4, 1, '', '2', '2022-03-03', '2022-04-23 09:16:13'),
(5, 2, '', '1', '2022-03-01', '2022-04-23 09:16:13'),
(6, 2, '', '1', '2022-03-03', '2022-04-23 09:16:13'),
(7, 3, '', '1', '2022-03-02', '2022-04-23 09:16:13'),
(8, 1, '', '1', '2022-03-04', '2022-04-23 09:16:13'),
(9, 4, '', '1', '2022-03-01', '2022-04-23 09:16:13'),
(10, 4, '', '1', '2022-03-02', '2022-04-23 09:16:13'),
(11, 4, '', '1', '2022-03-03', '2022-04-23 09:16:13');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `nama` varchar(500) NOT NULL,
  `product_code` varchar(200) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `def_packing_id` int(11) NOT NULL,
  `def_sale_packing_id` int(11) NOT NULL,
  `def_buy_packing_id` int(11) NOT NULL,
  `margin` int(10) NOT NULL,
  `img_path` varchar(500) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `user_modified` varchar(200) DEFAULT NULL,
  `is_deleted` int(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `product_stocks`
--

DROP TABLE IF EXISTS `product_stocks`;
CREATE TABLE `product_stocks` (
  `id` int(11) NOT NULL,
  `product_variant_id` int(11) NOT NULL,
  `total_stock` int(11) NOT NULL,
  `buy_price` decimal(15,2) NOT NULL,
  `expired_date` date DEFAULT '2100-12-31'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `product_variant`
--

DROP TABLE IF EXISTS `product_variant`;
CREATE TABLE `product_variant` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `nama` varchar(500) NOT NULL,
  `variant_code` varchar(500) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `limit_reminder` int(5) DEFAULT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `user_modified` varchar(200) DEFAULT NULL,
  `is_deleted` int(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ref_table`
--

DROP TABLE IF EXISTS `ref_table`;
CREATE TABLE `ref_table` (
  `id` int(11) NOT NULL,
  `nama` varchar(500) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `user_modified` varchar(200) DEFAULT NULL,
  `is_deleted` int(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_brand`
--

DROP TABLE IF EXISTS `user_brand`;
CREATE TABLE `user_brand` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_brand`
--

INSERT INTO `user_brand` (`id`, `user_id`, `brand_id`, `created_at`) VALUES
(1, 2, 2, '2022-05-01 12:27:38'),
(2, 2, 4, '2022-05-01 12:27:38'),
(11, 5, 2, '2022-05-01 13:52:11'),
(12, 5, 3, '2022-05-01 13:52:11'),
(13, 5, 4, '2022-05-01 13:52:11'),
(14, 5, 5, '2022-05-01 13:52:11');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `user_id`, `role_id`, `created_at`) VALUES
(1, 1, 1, '2022-05-01 12:26:47'),
(2, 2, 3, '2022-05-01 12:27:38'),
(3, 2, 4, '2022-05-01 12:27:38'),
(14, 5, 1, '2022-05-01 13:52:11'),
(15, 5, 2, '2022-05-01 13:52:11'),
(16, 5, 3, '2022-05-01 13:52:11'),
(17, 5, 4, '2022-05-01 13:52:11'),
(18, 5, 6, '2022-05-01 13:52:11'),
(40, 4, 1, '2022-05-01 16:36:34'),
(41, 4, 2, '2022-05-01 16:36:34'),
(42, 4, 3, '2022-05-01 16:36:34'),
(43, 4, 6, '2022-05-01 16:36:34'),
(44, 3, 1, '2022-05-06 05:20:43'),
(45, 3, 2, '2022-05-06 05:20:43'),
(46, 3, 3, '2022-05-06 05:20:43'),
(47, 3, 6, '2022-05-06 05:20:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acl_role_menu`
--
ALTER TABLE `acl_role_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mst_brand`
--
ALTER TABLE `mst_brand`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mst_menu`
--
ALTER TABLE `mst_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mst_packing`
--
ALTER TABLE `mst_packing`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unit` (`unit`);

--
-- Indexes for table `mst_role`
--
ALTER TABLE `mst_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mst_users`
--
ALTER TABLE `mst_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq` (`username`);

--
-- Indexes for table `penggunaan_mesin`
--
ALTER TABLE `penggunaan_mesin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_code` (`product_code`);

--
-- Indexes for table `product_stocks`
--
ALTER TABLE `product_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variant_id` (`product_variant_id`);

--
-- Indexes for table `product_variant`
--
ALTER TABLE `product_variant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `ref_table`
--
ALTER TABLE `ref_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_brand`
--
ALTER TABLE `user_brand`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acl_role_menu`
--
ALTER TABLE `acl_role_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT for table `mst_brand`
--
ALTER TABLE `mst_brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `mst_menu`
--
ALTER TABLE `mst_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `mst_packing`
--
ALTER TABLE `mst_packing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mst_role`
--
ALTER TABLE `mst_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `mst_users`
--
ALTER TABLE `mst_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `penggunaan_mesin`
--
ALTER TABLE `penggunaan_mesin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_stocks`
--
ALTER TABLE `product_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_variant`
--
ALTER TABLE `product_variant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ref_table`
--
ALTER TABLE `ref_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_brand`
--
ALTER TABLE `user_brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product_stocks`
--
ALTER TABLE `product_stocks`
  ADD CONSTRAINT `product_stocks_ibfk_1` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `product_variant`
--
ALTER TABLE `product_variant`
  ADD CONSTRAINT `product_variant_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
