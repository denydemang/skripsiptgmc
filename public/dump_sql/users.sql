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

 Date: 15/04/2024 19:12:35
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('admin', 'Super Admin', '$2y$12$pkcRlvCdIfmFUQ8YzkgqZegG0AIzX0GiF3af7arLaSUG2xM8eFb62', NULL, 1, 1, 'admin', NULL, '2024-04-05 00:04:27', '2024-04-05 00:04:27');

SET FOREIGN_KEY_CHECKS = 1;
