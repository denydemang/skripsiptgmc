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

 Date: 24/06/2024 14:22:28
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories`  (
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `coa_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `updated_by` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`code`) USING BTREE,
  INDEX `categories_coa_code_foreign`(`coa_code`) USING BTREE,
  CONSTRAINT `categories_coa_code_foreign` FOREIGN KEY (`coa_code`) REFERENCES `coa` (`code`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES ('CTG001', 'Semen', '10.01.04.01', 'Admin', NULL, '2024-04-07 11:24:26', '2024-04-07 11:24:28');
INSERT INTO `categories` VALUES ('CTG002', 'Bata Ringan', '10.01.04.01', 'Admin', 'admin', '2024-04-07 11:29:07', '2024-06-23 19:44:19');
INSERT INTO `categories` VALUES ('CTG003', 'Aspal', '10.01.04.01', 'Admin', NULL, '2024-04-07 11:30:09', '2024-04-07 11:30:12');
INSERT INTO `categories` VALUES ('CTG004', 'Beton', '10.01.04.01', 'Admin', 'admin', '2024-04-07 11:31:12', '2024-06-23 19:44:01');
INSERT INTO `categories` VALUES ('CTG005', 'Besi Beton', '10.01.04.01', 'admin', NULL, '2024-06-23 19:44:40', '2024-06-23 19:44:40');
INSERT INTO `categories` VALUES ('CTG006', 'Balok Sloof', '10.01.04.01', 'admin', NULL, '2024-06-23 19:45:00', '2024-06-23 19:45:00');
INSERT INTO `categories` VALUES ('CTG007', 'Bekisting', '10.01.04.01', 'admin', NULL, '2024-06-23 19:45:30', '2024-06-23 19:45:30');
INSERT INTO `categories` VALUES ('CTG008', 'Aksesoris', '10.01.04.01', 'admin', NULL, '2024-06-23 19:45:50', '2024-06-23 19:45:50');
INSERT INTO `categories` VALUES ('CTG009', 'Kusen', '10.01.04.01', 'admin', NULL, '2024-06-23 19:46:23', '2024-06-23 19:46:23');
INSERT INTO `categories` VALUES ('CTG010', 'Pintu', '10.01.04.01', 'admin', NULL, '2024-06-23 19:46:38', '2024-06-23 19:46:38');
INSERT INTO `categories` VALUES ('CTG011', 'Jendela', '10.01.04.01', 'admin', NULL, '2024-06-23 19:46:50', '2024-06-23 19:46:50');
INSERT INTO `categories` VALUES ('CTG012', 'Tiang', '10.01.04.01', 'admin', NULL, '2024-06-23 19:47:06', '2024-06-23 19:47:06');
INSERT INTO `categories` VALUES ('CTG013', 'Plafond', '10.01.04.01', 'admin', NULL, '2024-06-23 19:47:21', '2024-06-23 19:47:21');
INSERT INTO `categories` VALUES ('CTG014', 'Pasir', '10.01.04.01', 'admin', NULL, '2024-06-23 19:47:34', '2024-06-23 19:47:34');
INSERT INTO `categories` VALUES ('CTG015', 'Keramik', '10.01.04.01', 'admin', NULL, '2024-06-23 19:47:49', '2024-06-23 19:47:49');
INSERT INTO `categories` VALUES ('CTG016', 'Cat', '10.01.04.01', 'admin', NULL, '2024-06-23 19:48:01', '2024-06-23 19:48:01');
INSERT INTO `categories` VALUES ('CTG017', 'Lampu', '10.01.04.01', 'admin', NULL, '2024-06-23 19:48:12', '2024-06-23 19:48:12');
INSERT INTO `categories` VALUES ('CTG018', 'Stop Kontak', '10.01.04.01', 'admin', NULL, '2024-06-23 19:48:27', '2024-06-23 19:48:27');
INSERT INTO `categories` VALUES ('CTG019', 'Pipa', '10.01.04.01', 'admin', NULL, '2024-06-23 19:48:40', '2024-06-23 19:48:40');
INSERT INTO `categories` VALUES ('CTG020', 'Kran Air', '10.01.04.01', 'admin', NULL, '2024-06-23 19:48:53', '2024-06-23 19:48:53');
INSERT INTO `categories` VALUES ('CTG021', 'Kloset', '10.01.04.01', 'admin', NULL, '2024-06-23 19:49:03', '2024-06-23 19:49:03');
INSERT INTO `categories` VALUES ('CTG022', 'Batu Kali', '10.01.04.01', 'admin', NULL, '2024-06-23 20:26:58', '2024-06-23 20:26:58');
INSERT INTO `categories` VALUES ('CTG023', 'Baja', '10.01.04.01', 'admin', NULL, '2024-06-23 20:30:40', '2024-06-23 20:30:40');
INSERT INTO `categories` VALUES ('CTG024', 'Atap', '10.01.04.01', 'admin', NULL, '2024-06-23 20:31:45', '2024-06-23 20:31:45');
INSERT INTO `categories` VALUES ('CTG025', 'Listplang', '10.01.04.01', 'admin', NULL, '2024-06-23 20:33:22', '2024-06-23 20:33:22');
INSERT INTO `categories` VALUES ('CTG026', 'Canopy', '10.01.04.01', 'admin', NULL, '2024-06-23 20:33:39', '2024-06-23 20:33:39');

SET FOREIGN_KEY_CHECKS = 1;
