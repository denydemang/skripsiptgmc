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

 Date: 15/04/2024 19:16:29
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Records of customers
-- ----------------------------
INSERT INTO `customers` VALUES ('CUST001', 'PT X Gumilar', 'Jakarta', '3323', '-', 'ptxgumilar@gmail.com', '9383839', '10.01.03.01', 'Admin', NULL, NULL, NULL);
INSERT INTO `customers` VALUES ('CUST002', 'PT Y Sekar Arum', 'Yogyakarta', '2323', '-', 'sekararum@gmail.com', '9383839', '10.01.03.01', 'Admin', NULL, NULL, NULL);
INSERT INTO `customers` VALUES ('CUST003', 'PT Sugeng Adella', 'Solo', '2323', '-', 'sugengaddeka@gmail.com', '9383839', '10.01.03.01', 'Admin', NULL, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
