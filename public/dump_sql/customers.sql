/*
 Navicat Premium Data Transfer

 Source Server         : skripsi
 Source Server Type    : MariaDB
 Source Server Version : 110302 (11.3.2-MariaDB-log)
 Source Host           : localhost:3306
 Source Schema         : testgmj

 Target Server Type    : MariaDB
 Target Server Version : 110302 (11.3.2-MariaDB-log)
 File Encoding         : 65001

 Date: 30/06/2024 18:14:32
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for customers
-- ----------------------------

-- ----------------------------
-- Records of customers
-- ----------------------------
INSERT INTO `customers` VALUES ('CUST001', 'PT Hutama Karya', 'HK Tower, Jl Letjen MT Haryono Kav. 8, Cawang, East Jakarta City, Jakarta', '13340', '-', 'pthk@hutamakarya.com', '021-8193708', '10.01.03.01', 'admin', NULL, '2024-06-23 21:50:18', '2024-06-23 21:50:18');
INSERT INTO `customers` VALUES ('CUST002', 'PT Waskita Karya (Waskita)', 'Gedung Dafam Teraskita Lantai 6, Jalan MT. Haryono Kav. 10 A, Jakarta', '13340', '-', 'waskita@waskita.co.id', '021-8508510', '10.01.03.01', 'admin', 'admin', '2024-06-30 18:09:53', '2024-06-30 18:13:57');
INSERT INTO `customers` VALUES ('CUST003', 'PT Wijaya Karya', 'Jl. D.I. Panjaitan Kav. 9, Jakarta', '13340', '-', 'adiwijaya@wika.co.id', '021-8192808', '10.01.03.01', 'admin', NULL, '2024-06-30 18:12:07', '2024-06-30 18:12:07');
INSERT INTO `customers` VALUES ('CUST004', 'PT PP', 'Plaza PP - Wisma Subiyanto, Jalan TB Simatupang No. 57, Pasar Rebo, Jakarta', '13760', '-', 'corsec@ptpp.co.id', '021-87784137', '10.01.03.01', 'admin', NULL, '2024-06-30 18:13:41', '2024-06-30 18:13:41');

SET FOREIGN_KEY_CHECKS = 1;
