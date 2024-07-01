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

 Date: 21/05/2024 20:02:57
*/

SET NAMES utf8mb4;

-- ----------------------------
-- Table structure for payment_terms
-- ----------------------------
-- ----------------------------
-- Records of payment_terms
-- ----------------------------
INSERT INTO `payment_terms` VALUES ('n/30', 'Net 30 Days', 'Max Payment 30 Days', 30, 0, 'admin', '', NULL, NULL);
INSERT INTO `payment_terms` VALUES ('n/60', 'Net 60 Days', 'Max Payment 60 Days', 60, 0, 'admin', NULL, NULL, NULL);
INSERT INTO `payment_terms` VALUES ('n/90', 'Net 90 Days', 'Max Payment 90 Days', 90, 0, 'admin', NULL, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
