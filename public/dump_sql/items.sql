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

 Date: 15/04/2024 19:48:11
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for items
-- ----------------------------
DROP TABLE IF EXISTS `items`;
CREATE TABLE `items`  (
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_stock` decimal(50, 4) NOT NULL,
  `max_stock` decimal(50, 4) NOT NULL,
  `category_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `updated_by` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`code`) USING BTREE,
  INDEX `items_unit_code_foreign`(`unit_code`) USING BTREE,
  INDEX `items_category_code_foreign`(`category_code`) USING BTREE,
  CONSTRAINT `items_category_code_foreign` FOREIGN KEY (`category_code`) REFERENCES `categories` (`code`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `items_unit_code_foreign` FOREIGN KEY (`unit_code`) REFERENCES `units` (`code`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of items
-- ----------------------------
INSERT INTO `items` VALUES ('ITEM001', 'Semen Tiga Roda', 'PACK', 1.0000, 30.0000, 'CTG001', 'Admin', NULL, '2024-04-07 11:25:11', '2024-04-07 11:33:53');
INSERT INTO `items` VALUES ('ITEM002', 'Semen Padang', 'PACK', 1.0000, 1000.0000, 'CTG001', 'Admin', NULL, '2024-04-07 11:26:09', '2024-04-07 11:33:50');
INSERT INTO `items` VALUES ('ITEM003', 'Semen LeichtBric Premium AAC', 'PCS', 1.0000, 500000.0000, 'CTG002', 'Admin', NULL, '2024-04-07 11:33:42', '2024-04-07 11:33:46');
INSERT INTO `items` VALUES ('ITEM004', 'Bata Ringan CITICON', 'PCS', 1.0000, 500000.0000, 'CTG002', 'Admin', NULL, '2024-04-07 11:35:04', '2024-04-07 11:35:18');
INSERT INTO `items` VALUES ('ITEM005', 'Multicon Bata Ringan', 'PCS', 1.0000, 600000.0000, 'CTG002', 'Admin', NULL, '2024-04-07 11:36:32', '2024-04-07 11:36:35');
INSERT INTO `items` VALUES ('ITEM006', 'Bambu betung Merk X', 'PCS', 1.0000, 45000.0000, 'CTG004', 'Admin', NULL, '2024-04-07 11:38:21', '2024-04-07 11:38:25');
INSERT INTO `items` VALUES ('ITEM007', 'Bambu gombong Merk Y', 'PCS', 1.0000, 450000.0000, 'CTG004', 'Admin', NULL, '2024-04-07 11:39:21', '2024-04-07 11:39:24');
INSERT INTO `items` VALUES ('ITEM008', 'Pertamina Aspal XYZ', 'DRUM', 1.0000, 25999930.0000, 'CTG003', 'Admin', NULL, '2024-04-07 11:41:12', '2024-04-07 11:41:15');

SET FOREIGN_KEY_CHECKS = 1;
