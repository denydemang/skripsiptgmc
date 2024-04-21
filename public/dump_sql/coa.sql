/*
 Navicat Premium Data Transfer

 Source Server         : mariadblocal
 Source Server Type    : MariaDB
 Source Server Version : 110300 (11.3.0-MariaDB-log)
 Source Host           : localhost:3306
 Source Schema         : skripsiptgmc

 Target Server Type    : MariaDB
 Target Server Version : 110300 (11.3.0-MariaDB-log)
 File Encoding         : 65001

 Date: 21/04/2024 01:21:15
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

INSERT INTO `coa` VALUES ('10', 'Aktiva', 'Aktiva', '0', 'D', 'Header', 0.0000, 'Admin', NULL, '2024-03-29 14:14:07', '2024-03-29 14:14:07');
INSERT INTO `coa` VALUES ('10.01', 'Aktiva Lancar', 'Aktiva', '1', 'D', 'Header', 0.0000, 'Admin', NULL, '2024-03-29 14:14:07', '2024-03-29 14:14:07');
INSERT INTO `coa` VALUES ('10.01.01', 'Kas', 'Aktiva', '2', 'D', 'Header', 0.0000, 'Admin', NULL, '2024-03-29 14:14:07', '2024-03-29 14:14:07');
INSERT INTO `coa` VALUES ('10.01.01.01', 'Kas Di Tangan', 'Aktiva', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-03-29 14:14:07', '2024-03-29 14:14:07');
INSERT INTO `coa` VALUES ('10.01.02', 'Bank', 'Aktiva', '2', 'D', 'Header', 0.0000, 'Admin', NULL, '2024-03-29 14:14:07', '2024-03-29 14:14:07');
INSERT INTO `coa` VALUES ('10.01.02.01', 'Bank Mandiri', 'Aktiva', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-03-29 14:14:07', '2024-03-29 14:14:07');
INSERT INTO `coa` VALUES ('10.01.02.02', 'Bank BNI', 'Aktiva', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-03-29 14:14:07', '2024-03-29 14:14:07');
INSERT INTO `coa` VALUES ('10.01.02.03', 'Bank BCA', 'Aktiva', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-03-29 14:14:07', '2024-03-29 14:14:07');
INSERT INTO `coa` VALUES ('10.01.03', 'Piutang', 'Aktiva', '2', 'D', 'Header', 0.0000, 'Admin', NULL, '2024-03-29 14:14:07', '2024-03-29 14:14:07');
INSERT INTO `coa` VALUES ('10.01.03.01', 'Piutang Usaha', 'Aktiva', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-03-29 14:14:07', '2024-03-29 14:14:07');
INSERT INTO `coa` VALUES ('10.01.03.02', 'Cadangan Kerugian Piutang', 'Aktiva', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-03-29 14:14:07', '2024-03-29 14:14:07');
INSERT INTO `coa` VALUES ('10.01.03.03', 'PPN Masukan', 'Aktiva', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-03-29 14:14:07', '2024-03-29 14:14:07');
INSERT INTO `coa` VALUES ('10.01.04', 'Persediaan', 'Aktiva', '2', 'D', 'Header', 0.0000, 'Admin', NULL, '2024-03-29 14:14:07', '2024-03-29 14:14:07');
INSERT INTO `coa` VALUES ('10.01.04.01', 'Persediaan Bahan Baku Material', 'Aktiva', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-03-29 14:14:07', '2024-03-29 14:14:07');
INSERT INTO `coa` VALUES ('10.01.04.02', 'Proyek Dalam Proses', 'Aktiva', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-15 19:09:38', '2024-04-15 19:09:42');
INSERT INTO `coa` VALUES ('10.01.05', 'Perlengkapan', 'Aktiva', '2', 'D', 'Header', 0.0000, 'Admin', NULL, '2024-03-29 14:14:07', '2024-03-29 14:14:07');
INSERT INTO `coa` VALUES ('10.01.05.01', 'Perlengkapan Kantor', 'Aktiva', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-03-29 14:14:07', '2024-03-29 14:14:07');
INSERT INTO `coa` VALUES ('10.01.06', 'Biaya Dibayar Dimuka', 'Aktiva', '2', 'D', 'Header', 0.0000, 'Admin', NULL, '2024-04-02 11:21:09', '2024-04-02 11:21:18');
INSERT INTO `coa` VALUES ('10.01.06.01', 'Sewa Dibayar Dimuka', 'Aktiva', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-02 11:21:22', '2024-04-02 11:21:29');
INSERT INTO `coa` VALUES ('10.01.06.02', 'Asuransi Dibayar Dimuka', 'Aktiva', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-02 11:21:56', '2024-04-02 11:22:05');
INSERT INTO `coa` VALUES ('10.01.06.03', 'Gaji Dibayar Dimuka', 'Aktiva', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-02 11:22:07', '2024-04-02 11:22:12');
INSERT INTO `coa` VALUES ('10.02', 'Aktiva Tetap', 'Aktiva', '1', 'D', 'Header', 0.0000, 'Admin', NULL, '2024-04-02 11:22:27', '2024-04-02 11:22:33');
INSERT INTO `coa` VALUES ('10.02.01', 'Harga Perolehan Aktiva Tetap', 'Aktiva', '2', 'D', 'Header', 0.0000, 'Admin', NULL, '2024-04-02 11:22:43', '2024-04-02 11:22:38');
INSERT INTO `coa` VALUES ('10.02.01.01', 'Tanah ', 'Aktiva', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-02 11:24:21', '2024-04-02 11:24:26');
INSERT INTO `coa` VALUES ('10.02.01.02', 'Bangunan', 'Aktiva', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-02 11:24:36', '2024-04-02 11:24:40');
INSERT INTO `coa` VALUES ('10.02.01.03', 'Inventaris Kantor', 'Aktiva', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-02 11:25:56', '2024-04-02 11:26:02');
INSERT INTO `coa` VALUES ('10.02.01.04', 'Kendaraan', 'Aktiva', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-02 11:27:14', '2024-04-02 11:27:07');
INSERT INTO `coa` VALUES ('10.02.02', 'Akumulasi penyusutan', 'Aktiva', '2', 'K', 'Header', 0.0000, 'Admin', NULL, '2024-04-02 11:29:48', '2024-04-02 11:30:00');
INSERT INTO `coa` VALUES ('10.02.02.01', 'Akm. Penyusutan Bangunan', 'Aktiva', '3', 'K', 'Detail', 0.0000, 'Admin', NULL, '2024-04-02 11:31:11', '2024-04-02 11:31:13');
INSERT INTO `coa` VALUES ('10.02.02.02', 'Akm. Penyusutan Inventaris Kantor', 'Aktiva', '3', 'K', 'Detail', 0.0000, 'Admin', NULL, '2024-04-02 11:31:18', '2024-04-02 11:31:22');
INSERT INTO `coa` VALUES ('20', 'Kewajiban', 'Passiva', '0', 'K', 'Header', 0.0000, 'Admin', NULL, '2024-04-02 11:36:00', '2024-04-02 11:36:05');
INSERT INTO `coa` VALUES ('20.01', 'Utang Lancar', 'Passiva', '1', 'K', 'Header', 0.0000, 'Admin', NULL, '2024-04-02 11:37:29', '2024-04-02 11:37:34');
INSERT INTO `coa` VALUES ('20.01.01', 'Kewajiban Segera', 'Passiva', '2', 'K', 'Header', 0.0000, 'Admin', NULL, '2024-04-02 11:40:03', '2024-04-02 02:40:06');
INSERT INTO `coa` VALUES ('20.01.01.01', 'Utang Usaha', 'Passiva', '3', 'K', 'Detail', 0.0000, 'Admin', NULL, '2024-04-02 11:41:39', '2024-04-02 11:41:44');
INSERT INTO `coa` VALUES ('20.01.01.02', 'Utang Pajak', 'Passiva', '3', 'K', 'Detail', 0.0000, 'Admin', NULL, '2024-04-02 11:42:46', '2024-04-02 11:42:50');
INSERT INTO `coa` VALUES ('20.01.01.03', 'Utang Gaji', 'Passiva', '3', 'K', 'Detail', 0.0000, 'Admin', NULL, '2024-04-02 11:45:18', '2024-04-02 11:45:24');
INSERT INTO `coa` VALUES ('20.01.02', 'Kewajiban Lain', 'Passiva', '2', 'K', 'Header', 0.0000, 'Admin', NULL, '2024-04-02 11:47:10', '2024-04-02 11:47:15');
INSERT INTO `coa` VALUES ('20.01.02.01', 'Uang Muka Penjualan', 'Passiva', '3', 'K', 'Detail', 0.0000, 'Admin', NULL, '2024-04-02 11:48:52', '2024-04-02 11:48:56');
INSERT INTO `coa` VALUES ('20.01.02.02', 'PPN Keluaran', 'Passiva', '3', 'K', 'Detail', 0.0000, 'Admin', NULL, '2024-04-02 11:50:34', '2024-04-02 11:50:39');
INSERT INTO `coa` VALUES ('20.01.02.03', 'Utang Lain Lain', 'Passiva', '3', 'K', 'Detail', 0.0000, 'Admin', NULL, '2024-04-02 11:51:30', '2024-04-02 11:51:34');
INSERT INTO `coa` VALUES ('20.02', 'Utang Jangka Panjang', 'Passiva', '1', 'K', 'Header', 0.0000, 'Admin', NULL, '2024-04-02 11:52:53', '2024-04-02 11:52:56');
INSERT INTO `coa` VALUES ('20.02.01', 'Utang Bank', 'Passiva', '2', 'K', 'Detail', 0.0000, 'Admin', NULL, '2024-04-02 11:53:51', '2024-04-02 11:53:56');
INSERT INTO `coa` VALUES ('30', 'Equity', 'Modal', '0', 'K', 'Header', 0.0000, 'Admin', NULL, '2024-04-02 11:56:03', '2024-04-02 11:56:06');
INSERT INTO `coa` VALUES ('30.01', 'Modal', 'Modal', '1', 'K', 'Header', 0.0000, 'Admin', NULL, '2024-04-02 11:58:38', '2024-04-02 11:58:42');
INSERT INTO `coa` VALUES ('30.01.01', 'Modal Disetor', 'Modal', '2', 'K', 'Detail', 0.0000, 'Admin', NULL, '2024-04-02 11:58:55', '2024-04-02 11:58:57');
INSERT INTO `coa` VALUES ('30.02', 'Rugi Laba', 'Rugi Laba', '1', 'K', 'Header', 0.0000, 'Admin', NULL, '2024-04-03 10:02:15', '2024-04-03 10:02:19');
INSERT INTO `coa` VALUES ('30.02.01', 'Laba Ditahan', 'Rugi Laba', '2', 'K', 'Detail', 0.0000, 'Admin', NULL, '2024-04-03 10:03:05', '2024-04-03 10:03:10');
INSERT INTO `coa` VALUES ('30.02.02', 'Laba s/d Bulan Ini', 'Rugi Laba', '2', 'K', 'Detail', 0.0000, 'Admin', NULL, '2024-04-03 10:03:16', '2024-04-03 10:03:19');
INSERT INTO `coa` VALUES ('30.02.03', 'Laba Bulan Ini', 'Rugi Laba', '2', 'K', 'Detail', 0.0000, 'Admin', NULL, '2024-04-03 10:03:24', '2024-04-03 10:03:27');
INSERT INTO `coa` VALUES ('40', 'Pendapatan', 'Pendapatan', '1', 'K', 'Header', 0.0000, 'Admin', NULL, '2024-04-03 10:03:30', '2024-04-03 10:03:50');
INSERT INTO `coa` VALUES ('40.01', 'Pendapatan Usaha', 'Pendapatan', '2', 'K', 'Detail', 0.0000, 'Admin', NULL, '2024-04-03 10:03:54', '2024-04-03 10:03:59');
INSERT INTO `coa` VALUES ('50', 'Beban Pokok Penjualan', 'HPP', '1', 'D', 'Header', 0.0000, 'Admin', NULL, '2024-04-03 10:04:02', '2024-04-03 10:04:05');
INSERT INTO `coa` VALUES ('50.01', 'Beban Material', 'HPP', '2', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-03 10:04:08', '2024-04-03 10:04:12');
INSERT INTO `coa` VALUES ('50.02', 'Beban TKL', 'HPP', '2', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-03 10:04:15', '2024-04-03 10:04:19');
INSERT INTO `coa` VALUES ('50.03', 'Beban Operasional Lapangan', 'HPP', '2', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-03 10:04:37', '2024-04-03 10:04:40');
INSERT INTO `coa` VALUES ('60', 'Beban', 'Beban', '1', 'D', 'Header', 0.0000, 'Admin', NULL, '2024-04-03 10:04:22', '2024-04-03 10:04:25');
INSERT INTO `coa` VALUES ('60.01', 'Beban Usaha', 'Beban', '2', 'D', 'Header', 0.0000, 'Admin', NULL, '2024-04-03 10:04:44', '2024-04-03 10:04:48');
INSERT INTO `coa` VALUES ('60.01.01', 'Beban Gaji Pegawai', 'Beban', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-03 10:04:53', '2024-04-03 10:04:57');
INSERT INTO `coa` VALUES ('60.01.02', 'Beban Perlengkapan Kantor', 'Beban', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-03 10:05:02', '2024-04-03 10:05:05');
INSERT INTO `coa` VALUES ('60.01.03', 'Beban Telepon dan Internet', 'Beban', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-03 10:05:09', '2024-04-03 10:05:12');
INSERT INTO `coa` VALUES ('60.01.04', 'Beban Listrik dan Air', 'Beban', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-03 10:05:16', '2024-04-03 10:05:19');
INSERT INTO `coa` VALUES ('60.02', 'Beban Penyusutan', 'Beban', '2', 'D', 'Header', 0.0000, 'Admin', NULL, '2024-04-03 10:05:22', '2024-04-03 10:05:25');
INSERT INTO `coa` VALUES ('60.02.01', 'Beban Penyusutan Bangunan', 'Beban', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-03 10:05:30', '2024-04-03 10:05:30');
INSERT INTO `coa` VALUES ('60.02.02', 'Beban Penyusutan Inv. Kantor', 'Beban', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-03 10:05:30', '2024-04-03 10:05:30');
INSERT INTO `coa` VALUES ('60.02.03', 'Beban Penyusutan Kendaraan', 'Beban', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-03 10:05:30', '2024-04-03 10:05:30');
INSERT INTO `coa` VALUES ('60.03', 'Beban Kerugian', 'Beban', '2', 'D', 'Header', 0.0000, 'Admin', NULL, '2024-04-03 10:05:30', '2024-04-03 10:05:30');
INSERT INTO `coa` VALUES ('60.03.01', 'Beban Kerugian Piutang', 'Beban', '3', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-03 10:05:30', '2024-04-03 10:05:30');
INSERT INTO `coa` VALUES ('60.04', 'Beban Lain-lain', 'Beban', '2', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-03 10:05:30', '2024-04-03 10:05:30');
INSERT INTO `coa` VALUES ('80', 'Pendapatan Non Operasional', 'Pendapatan Non Operasional', '1', 'K', 'Header', 0.0000, 'Admin', NULL, '2024-04-03 10:05:30', '2024-04-03 10:05:30');
INSERT INTO `coa` VALUES ('80.01', 'Pendapatan Jasa Giro', 'Pendapatan Non Operasional', '2', 'K', 'Detail', 0.0000, 'Admin', NULL, '2024-04-03 10:05:30', '2024-04-03 10:05:30');
INSERT INTO `coa` VALUES ('90', 'Beban Non Operasional', 'Pendapatan Non Operasional', '1', 'D', 'Header', 0.0000, 'Admin', NULL, '2024-04-03 10:05:30', '2024-04-03 10:05:30');
INSERT INTO `coa` VALUES ('90.01', 'Beban Administrasi Bank', 'Beban Non Operasional', '2', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-03 10:05:30', '2024-04-03 10:05:30');
INSERT INTO `coa` VALUES ('90.02', 'Beban Pajak', 'Beban Non Operasional', '2', 'D', 'Detail', 0.0000, 'Admin', NULL, '2024-04-03 10:06:00', '2024-04-03 10:05:30');

SET FOREIGN_KEY_CHECKS = 1;
