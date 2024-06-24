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

 Date: 24/06/2024 14:03:02
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for roles
-- ----------------------------
-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'admin', 1, NULL, NULL, '2024-04-05 00:04:27', '2024-04-05 00:04:27');
INSERT INTO `roles` VALUES (2, 'keuangan', 1, NULL, NULL, '2024-06-23 21:51:25', '2024-06-23 21:51:25');
INSERT INTO `roles` VALUES (3, 'logistik', 1, NULL, NULL, '2024-06-23 21:51:32', '2024-06-23 21:51:32');
INSERT INTO `roles` VALUES (4, 'direktur', 1, NULL, NULL, '2024-06-23 21:51:38', '2024-06-23 21:51:38');

SET FOREIGN_KEY_CHECKS = 1;
