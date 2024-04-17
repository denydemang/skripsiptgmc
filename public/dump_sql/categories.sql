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

 Date: 15/04/2024 19:14:22
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES ('CTG001', 'Semen', '10.01.04.01', 'Admin', NULL, '2024-04-07 11:24:26', '2024-04-07 11:24:28');
INSERT INTO `categories` VALUES ('CTG002', 'Batu Bata', '10.01.04.01', 'Admin', NULL, '2024-04-07 11:29:07', '2024-04-07 11:29:11');
INSERT INTO `categories` VALUES ('CTG003', 'Aspal', '10.01.04.01', 'Admin', NULL, '2024-04-07 11:30:09', '2024-04-07 11:30:12');
INSERT INTO `categories` VALUES ('CTG004', 'Bambu', '10.01.04.01', 'Admin', NULL, '2024-04-07 11:31:12', '2024-04-07 11:31:16');

SET FOREIGN_KEY_CHECKS = 1;
