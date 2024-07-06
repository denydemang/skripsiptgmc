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

 Date: 05/07/2024 23:48:23
*/



-- ----------------------------
-- Records of items
-- ----------------------------
INSERT INTO `items` VALUES ('ITEM001', 'Beton K-200', 'm³', 20.0000, 50.0000, 'CTG004', 'admin', 'admin', '2024-06-23 20:07:12', '2024-07-05 15:23:04');
INSERT INTO `items` VALUES ('ITEM002', 'Beton K-175', 'm³', 20.0000, 30.0000, 'CTG004', 'admin', 'admin', '2024-06-23 20:21:29', '2024-07-05 15:23:16');
INSERT INTO `items` VALUES ('ITEM003', 'Besi Beton', 'ton', 1.0000, 5.0000, 'CTG005', 'admin', NULL, '2024-06-23 20:23:00', '2024-06-23 20:23:00');
INSERT INTO `items` VALUES ('ITEM004', 'Bekisting Sloof', 'lbr', 50.0000, 200.0000, 'CTG007', 'admin', 'admin', '2024-06-23 20:23:40', '2024-07-05 15:23:34');
INSERT INTO `items` VALUES ('ITEM005', 'Bekisting Kolom', 'lbr', 50.0000, 200.0000, 'CTG007', 'admin', NULL, '2024-06-23 20:24:22', '2024-06-23 20:24:22');
INSERT INTO `items` VALUES ('ITEM006', 'Bekisting Balok', 'lbr', 200.0000, 400.0000, 'CTG007', 'admin', 'admin', '2024-06-23 20:24:51', '2024-07-05 15:23:53');
INSERT INTO `items` VALUES ('ITEM007', 'Bata Ringan', 'm³', 3000.0000, 5000.0000, 'CTG002', 'admin', 'admin', '2024-06-23 20:25:30', '2024-07-05 15:24:11');
INSERT INTO `items` VALUES ('ITEM008', 'Batu Kali', 'm³', 100.0000, 300.0000, 'CTG022', 'admin', 'admin', '2024-06-23 20:27:51', '2024-07-05 15:24:22');
INSERT INTO `items` VALUES ('ITEM009', 'Kusen', 'pcs', 20.0000, 300.0000, 'CTG009', 'admin', 'admin', '2024-06-23 20:28:56', '2024-07-05 15:25:04');
INSERT INTO `items` VALUES ('ITEM010', 'Pintu', 'pcs', 20.0000, 300.0000, 'CTG010', 'admin', 'admin', '2024-06-23 20:29:18', '2024-07-05 15:25:31');
INSERT INTO `items` VALUES ('ITEM011', 'Jendela', 'pcs', 20.0000, 300.0000, 'CTG011', 'admin', 'admin', '2024-06-23 20:29:43', '2024-07-05 15:25:50');
INSERT INTO `items` VALUES ('ITEM012', 'Aksesoris', 'set', 100.0000, 800.0000, 'CTG008', 'admin', 'admin', '2024-06-23 20:30:08', '2024-07-05 15:26:09');
INSERT INTO `items` VALUES ('ITEM013', 'Baja Ringan', 'btg', 2000.0000, 4000.0000, 'CTG023', 'admin', 'admin', '2024-06-23 20:31:08', '2024-07-05 15:26:26');
INSERT INTO `items` VALUES ('ITEM014', 'Penutup Atap Spandek', 'm²', 2000.0000, 4000.0000, 'CTG024', 'admin', 'admin', '2024-06-23 20:32:09', '2024-07-05 15:26:41');
INSERT INTO `items` VALUES ('ITEM015', 'Listplang GRC', 'lbr', 1000.0000, 2000.0000, 'CTG025', 'admin', 'admin', '2024-06-23 20:34:36', '2024-07-05 15:26:56');
INSERT INTO `items` VALUES ('ITEM016', 'Flashing Canopy', 'lbr', 50.0000, 200.0000, 'CTG026', 'admin', 'admin', '2024-06-23 20:35:09', '2024-07-05 15:27:16');
INSERT INTO `items` VALUES ('ITEM017', 'Tiang Canopy', 'btg', 200.0000, 500.0000, 'CTG012', 'admin', 'admin', '2024-06-23 20:35:56', '2024-07-05 15:27:32');
INSERT INTO `items` VALUES ('ITEM018', 'Beton Canopy (K-175)', 'm³', 10.0000, 20.0000, 'CTG004', 'admin', 'admin', '2024-06-23 20:36:22', '2024-07-05 15:27:48');
INSERT INTO `items` VALUES ('ITEM019', 'Plafond Hollow 60x60', 'lbr', 1000.0000, 3000.0000, 'CTG013', 'admin', 'admin', '2024-06-23 20:36:51', '2024-07-05 15:28:05');
INSERT INTO `items` VALUES ('ITEM020', 'Plafond Gypsumboard', 'lbr', 1000.0000, 2000.0000, 'CTG013', 'admin', 'admin', '2024-06-23 20:37:16', '2024-07-05 15:28:27');
INSERT INTO `items` VALUES ('ITEM021', 'Plafond Kalsiboard', 'lbr', 1000.0000, 2000.0000, 'CTG013', 'admin', 'admin', '2024-06-23 20:37:39', '2024-07-05 15:28:46');
INSERT INTO `items` VALUES ('ITEM022', 'Pasir', 'm³', 100.0000, 500.0000, 'CTG014', 'admin', 'admin', '2024-06-23 20:38:02', '2024-07-05 15:29:08');
INSERT INTO `items` VALUES ('ITEM023', 'Keramik Lantai 30x30', 'm²', 1000.0000, 5000.0000, 'CTG015', 'admin', 'admin', '2024-06-23 20:38:36', '2024-07-05 15:29:21');
INSERT INTO `items` VALUES ('ITEM024', 'Keramik Lantai 20x20', 'm²', 500.0000, 1000.0000, 'CTG015', 'admin', 'admin', '2024-06-23 20:39:03', '2024-07-05 15:29:38');
INSERT INTO `items` VALUES ('ITEM025', 'Dinding Keramik 20x25', 'm²', 500.0000, 2000.0000, 'CTG015', 'admin', 'admin', '2024-06-23 20:39:43', '2024-07-05 15:29:52');
INSERT INTO `items` VALUES ('ITEM026', 'Cat', 'gln', 20.0000, 100.0000, 'CTG016', 'admin', 'admin', '2024-06-23 20:46:46', '2024-07-05 15:31:22');
INSERT INTO `items` VALUES ('ITEM027', 'Lampu', 'pcs', 100.0000, 500.0000, 'CTG017', 'admin', 'admin', '2024-06-23 20:47:10', '2024-07-05 15:31:40');
INSERT INTO `items` VALUES ('ITEM028', 'Stop Kontak', 'pcs', 100.0000, 500.0000, 'CTG018', 'admin', 'admin', '2024-06-23 20:47:45', '2024-07-05 15:31:56');
INSERT INTO `items` VALUES ('ITEM029', 'Pipa PVC', 'btg', 1000.0000, 2000.0000, 'CTG019', 'admin', 'admin', '2024-06-23 20:48:08', '2024-07-05 15:32:16');
INSERT INTO `items` VALUES ('ITEM030', 'Kran Air', 'pcs', 50.0000, 200.0000, 'CTG020', 'admin', 'admin', '2024-06-23 20:48:35', '2024-07-05 15:32:34');
INSERT INTO `items` VALUES ('ITEM031', 'Floor Drain Stainless', 'pcs', 50.0000, 200.0000, 'CTG008', 'admin', 'admin', '2024-06-23 20:49:37', '2024-07-05 15:32:57');
INSERT INTO `items` VALUES ('ITEM032', 'Closet Jongkok', 'pcs', 50.0000, 200.0000, 'CTG021', 'admin', 'admin', '2024-06-23 20:50:32', '2024-07-05 17:08:31');
INSERT INTO `items` VALUES ('ITEM033', 'Closet Duduk', 'pcs', 10.0000, 50.0000, 'CTG021', 'admin', 'admin', '2024-06-23 20:51:13', '2024-07-05 17:09:04');
INSERT INTO `items` VALUES ('ITEM034', 'Batu Bata Merah', 'pcs', 500.0000, 4000.0000, 'CTG002', 'admin', NULL, '2024-06-28 20:25:30', '2024-06-28 20:25:30');
INSERT INTO `items` VALUES ('ITEM035', 'Split Beton', 'm³', 100.0000, 300.0000, 'CTG004', 'admin', NULL, '2024-06-28 20:26:33', '2024-06-28 20:26:33');
INSERT INTO `items` VALUES ('ITEM036', 'Besi Beton Polos 8mm', 'btg', 100.0000, 200.0000, 'CTG005', 'admin', NULL, '2024-06-28 20:27:16', '2024-06-28 20:27:16');
INSERT INTO `items` VALUES ('ITEM037', 'Besi Beton Ulir D 10 mm', 'btg', 100.0000, 700.0000, 'CTG005', 'admin', 'admin', '2024-06-28 20:27:57', '2024-07-05 17:11:01');
INSERT INTO `items` VALUES ('ITEM038', 'Weirmesh', 'm²', 1000.0000, 2000.0000, 'CTG029', 'admin', NULL, '2024-06-28 20:28:58', '2024-06-28 20:28:58');
INSERT INTO `items` VALUES ('ITEM039', 'Kawat Beton', 'kg', 100.0000, 500.0000, 'CTG031', 'admin', 'admin', '2024-06-28 20:42:06', '2024-07-05 17:11:34');
INSERT INTO `items` VALUES ('ITEM040', 'Kawat Las', 'kg', 100.0000, 700.0000, 'CTG031', 'admin', 'admin', '2024-06-28 20:43:20', '2024-07-05 17:11:51');
INSERT INTO `items` VALUES ('ITEM041', 'Paku biasa', 'kg', 100.0000, 700.0000, 'CTG032', 'admin', 'admin', '2024-06-28 20:43:50', '2024-07-05 17:12:07');
INSERT INTO `items` VALUES ('ITEM042', 'Pasir Beton', 'm³', 100.0000, 500.0000, 'CTG014', 'admin', 'admin', '2024-06-28 20:45:10', '2024-07-05 17:12:25');
INSERT INTO `items` VALUES ('ITEM043', 'Pasir Urug', 'm³', 10.0000, 50.0000, 'CTG014', 'admin', NULL, '2024-06-28 20:45:36', '2024-06-28 20:45:36');
INSERT INTO `items` VALUES ('ITEM044', 'Semen Tiga Roda 50 kg', 'pcs', 2000.0000, 3000.0000, 'CTG001', 'admin', 'admin', '2024-06-28 20:46:26', '2024-07-05 20:02:11');
INSERT INTO `items` VALUES ('ITEM045', 'Kayu Balok', 'm³', 30.0000, 50.0000, 'CTG030', 'admin', NULL, '2024-06-28 20:49:03', '2024-06-28 20:49:03');
INSERT INTO `items` VALUES ('ITEM046', 'Kunci Tanam', 'pcs', 20.0000, 50.0000, 'CTG036', 'admin', NULL, '2024-06-28 20:52:50', '2024-06-28 20:52:50');
INSERT INTO `items` VALUES ('ITEM047', 'Engsel Jendela', 'pcs', 20.0000, 50.0000, 'CTG037', 'admin', NULL, '2024-06-28 20:53:22', '2024-06-28 20:53:22');
INSERT INTO `items` VALUES ('ITEM048', 'Handle Pintu', 'pcs', 10.0000, 20.0000, 'CTG010', 'admin', NULL, '2024-06-28 20:53:53', '2024-06-28 20:53:53');
INSERT INTO `items` VALUES ('ITEM049', 'Aluminum Foil', 'm²', 100.0000, 500.0000, 'CTG024', 'admin', NULL, '2024-06-28 20:55:28', '2024-06-28 20:55:28');
INSERT INTO `items` VALUES ('ITEM050', 'Amplas', 'lbr', 1000.0000, 10000.0000, 'CTG016', 'admin', NULL, '2024-06-28 20:56:09', '2024-06-28 20:56:09');
INSERT INTO `items` VALUES ('ITEM051', 'Plint Granite tile 10 x 100', 'm²', 10.0000, 50.0000, 'CTG015', 'admin', NULL, '2024-06-28 20:58:23', '2024-06-28 20:58:23');
INSERT INTO `items` VALUES ('ITEM052', 'Besi Hollow', 'btg', 20.0000, 200.0000, 'CTG029', 'admin', 'admin', '2024-06-28 20:59:10', '2024-07-05 20:02:33');
INSERT INTO `items` VALUES ('ITEM053', 'Besi Baja', 'm', 50.0000, 100.0000, 'CTG029', 'admin', NULL, '2024-06-28 20:59:45', '2024-06-28 20:59:45');
INSERT INTO `items` VALUES ('ITEM054', 'Kabel NYM', 'm', 500.0000, 2000.0000, 'CTG017', 'admin', 'admin', '2024-06-28 21:00:35', '2024-07-05 20:02:59');
INSERT INTO `items` VALUES ('ITEM055', 'Wastafel', 'pcs', 10.0000, 50.0000, 'CTG020', 'admin', 'admin', '2024-06-28 21:01:42', '2024-07-05 17:13:10');
INSERT INTO `items` VALUES ('ITEM056', 'Shower Box', 'pcs', 10.0000, 50.0000, 'CTG020', 'admin', 'admin', '2024-06-28 21:02:53', '2024-07-05 17:13:26');

SET FOREIGN_KEY_CHECKS = 1;
