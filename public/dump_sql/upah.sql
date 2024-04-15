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

 Date: 15/04/2024 20:00:36
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for upah
-- ----------------------------
DROP TABLE IF EXISTS `upah`;
CREATE TABLE `upah`  (
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `job` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(65, 4) NOT NULL,
  `coa_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `updated_by` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`code`) USING BTREE,
  INDEX `upah_coa_code_foreign`(`coa_code`) USING BTREE,
  CONSTRAINT `upah_coa_code_foreign` FOREIGN KEY (`coa_code`) REFERENCES `coa` (`code`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of upah
-- ----------------------------
INSERT INTO `upah` VALUES ('UPAH001', 'Upah Pekerjaan Ngecor', 'Upah Pengerjaan Ngecor', 'Hour', 2555000.0000, '50.02', 'Admin', NULL, '2024-04-07 11:45:21', '2024-04-07 11:45:27');
INSERT INTO `upah` VALUES ('UPAH002', 'Upah Pekerjaan Nyemen', 'upah Pekerjaan Nyemen', 'Hour', 350000.0000, '50.02', 'Admin', NULL, '2024-04-07 11:46:08', '2024-04-07 11:46:11');

SET FOREIGN_KEY_CHECKS = 1;
