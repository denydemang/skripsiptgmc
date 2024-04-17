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

 Date: 17/04/2024 22:05:33
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Records of journal_types
-- ----------------------------
INSERT INTO `journal_types` VALUES ('JKK', 'Jurnal Kas Keluar', NULL, NULL, NULL, NULL);
INSERT INTO `journal_types` VALUES ('JKM', 'Jurnal Kas Masuk', NULL, NULL, NULL, NULL);
INSERT INTO `journal_types` VALUES ('JP', 'Jurnal Penyesuaian', NULL, NULL, NULL, NULL);
INSERT INTO `journal_types` VALUES ('JU', 'Jurnal Umum', NULL, NULL, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
