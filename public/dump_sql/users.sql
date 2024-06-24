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

 Date: 24/06/2024 14:04:56
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;


-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('admin', 'kiana', '$2y$12$pkcRlvCdIfmFUQ8YzkgqZegG0AIzX0GiF3af7arLaSUG2xM8eFb62', NULL, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `users` VALUES ('direktur', 'farah', '$2y$12$4sbE8zDDV5tGHhkjAMoDw.9TqP9oLInshSckbZWzKWtHnpKitM6bS', NULL, 4, 1, 'admin', 'admin', '2024-06-23 22:01:33', '2024-06-23 22:01:33');
INSERT INTO `users` VALUES ('keuangan', 'farah', '$2y$12$OLWYwIZ01w6WKABFZ3lyWeeuV43FEzUVXcLDXrMBm01CzwIUkB0IO', NULL, 2, 1, 'admin', 'admin', '2024-06-23 22:00:41', '2024-06-23 22:00:41');
INSERT INTO `users` VALUES ('logistik', 'kiana', '$2y$12$2yhS6sScW2vW5Vi4y1E9aOIjgpk9lsPO8CZq27MLQh5JQG7jI8bnq', NULL, 3, 1, 'admin', 'admin', '2024-06-23 22:01:09', '2024-06-23 22:01:09');

SET FOREIGN_KEY_CHECKS = 1;
