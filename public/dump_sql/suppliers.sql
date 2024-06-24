/*
 Navicat Premium Data Transfer

 Source Server         : mariadblocal
 Source Server Type    : MariaDB
 Source Server Version : 110300 (11.3.0-MariaDB-log)
 Source Host           : localhost:3306
 Source Schema         : skripsiptgmc2

 Target Server Type    : MariaDB
 Target Server Version : 110300 (11.3.0-MariaDB-log)
 File Encoding         : 65001

 Date: 24/06/2024 14:18:53
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for suppliers
-- ----------------------------
-- ----------------------------
-- Records of suppliers
-- ----------------------------
INSERT INTO `suppliers` VALUES ('SUPP001', 'Toko SBM', 'Jalan Pancasila - Sumber Mujur - Candipuro', '67373', '01.234.567.8-901', 'sbm@email.com', '081330207775', '20.01.01.01', 'admin', NULL, '2024-06-23 19:31:41', '2024-06-23 19:31:41');
INSERT INTO `suppliers` VALUES ('SUPP002', 'Toko Bangunan Ragil Jaya', 'Jalan Raya Stand Pasar Penanggal Candipuro', '67373', '02.345.678.9-012', 'ragiljaya@email.com', '081232747650', '20.01.01.01', 'admin', NULL, '2024-06-23 19:32:44', '2024-06-23 19:32:44');
INSERT INTO `suppliers` VALUES ('SUPP003', 'CV. Building Material Contruction', 'Jalan Raya Sukomanunggal 5, Surabaya', '60188', '03.456.789.0-123', 'cvbmc@email.com', '031-7319881', '20.01.01.01', 'admin', NULL, '2024-06-23 19:35:55', '2024-06-23 19:35:55');
INSERT INTO `suppliers` VALUES ('SUPP004', 'Mandiri Joyo', 'Jalan Anjani 146', '60261', '04.567.890.1-234', 'mandirijoyo@email.com', '082330993023', '20.01.01.01', 'admin', NULL, '2024-06-23 19:36:52', '2024-06-23 19:36:52');
INSERT INTO `suppliers` VALUES ('SUPP005', 'Toko Esabiyan', 'Jalan Raya Penanggal', '60234', '05.678.901.2-345', 'esabiyan@email.com', '085746724109', '20.01.01.01', 'admin', NULL, '2024-06-23 19:37:49', '2024-06-23 19:37:49');
INSERT INTO `suppliers` VALUES ('SUPP006', 'PT. Sinar Indogreen Kencana', 'Sidoarjo', '61211', '06.789.012.3-456', 'sinarindogreen@email.com', '081234567890', '20.01.01.01', 'admin', NULL, '2024-06-23 19:39:10', '2024-06-23 19:39:10');
INSERT INTO `suppliers` VALUES ('SUPP007', 'PT. Sawunggaling Karya Abadi', 'Sidoarjo', '61211', '07.890.123.4-567', 'sawunggaling@email.com', '081234567891', '20.01.01.01', 'admin', NULL, '2024-06-23 19:40:17', '2024-06-23 19:40:17');
INSERT INTO `suppliers` VALUES ('SUPP008', 'Singa Merah', 'Jalan Raya Stand Pasar Penanggal', '67373', '08.901.234.5-678', 'singamerah@email.com', '081250428434', '20.01.01.01', 'admin', NULL, '2024-06-23 19:41:07', '2024-06-23 19:41:07');
INSERT INTO `suppliers` VALUES ('SUPP009', 'Wijaya Variasi', 'Jalan KH. Wahid Hasyim No 1 Pagedangan - Turen', '65175', '09.012.345.6-789', 'wijayavariasi@email.com', '08123470226', '20.01.01.01', 'admin', NULL, '2024-06-23 19:42:00', '2024-06-23 19:42:00');

SET FOREIGN_KEY_CHECKS = 1;
