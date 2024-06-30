/*
 Navicat Premium Data Transfer

 Source Server         : mariadblocal
 Source Server Type    : MariaDB
 Source Server Version : 110300 (11.3.0-MariaDB-log)
 Source Host           : localhost:3306
 Source Schema         : skripsiptgmc4

 Target Server Type    : MariaDB
 Target Server Version : 110300 (11.3.0-MariaDB-log)
 File Encoding         : 65001

 Date: 30/06/2024 17:01:01
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES ('CTG001', 'Semen', '10.01.04.01', 'Admin', NULL, '2024-04-07 11:24:26', '2024-04-07 11:24:28');
INSERT INTO `categories` VALUES ('CTG002', 'Bata', '10.01.04.01', 'Admin', 'admin', '2024-04-07 11:29:07', '2024-06-28 20:24:55');
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
INSERT INTO `categories` VALUES ('CTG027', 'Batu', '10.01.04.01', 'admin', NULL, '2024-06-28 14:18:10', '2024-06-28 14:18:10');
INSERT INTO `categories` VALUES ('CTG028', 'Minyak', '10.01.04.01', 'admin', NULL, '2024-06-28 14:20:15', '2024-06-28 14:20:15');
INSERT INTO `categories` VALUES ('CTG029', 'Besi', '10.01.04.01', 'admin', NULL, '2024-06-28 14:20:48', '2024-06-28 14:20:48');
INSERT INTO `categories` VALUES ('CTG030', 'Kayu', '10.01.04.01', 'admin', NULL, '2024-06-28 14:21:18', '2024-06-28 14:21:18');
INSERT INTO `categories` VALUES ('CTG031', 'Kawat', '10.01.04.01', 'admin', NULL, '2024-06-28 14:22:51', '2024-06-28 14:22:51');
INSERT INTO `categories` VALUES ('CTG032', 'Paku', '10.01.04.01', 'admin', NULL, '2024-06-28 14:23:03', '2024-06-28 14:23:03');
INSERT INTO `categories` VALUES ('CTG033', 'Seng', '10.01.04.01', 'admin', NULL, '2024-06-28 14:23:19', '2024-06-28 14:23:19');
INSERT INTO `categories` VALUES ('CTG034', 'Tanah', '10.01.04.01', 'admin', NULL, '2024-06-28 14:23:43', '2024-06-28 14:23:43');
INSERT INTO `categories` VALUES ('CTG035', 'Kerikil', '10.01.04.01', 'admin', NULL, '2024-06-28 14:24:03', '2024-06-28 14:24:03');
INSERT INTO `categories` VALUES ('CTG036', 'Kunci', '10.01.04.01', 'admin', NULL, '2024-06-28 14:24:47', '2024-06-28 14:24:47');
INSERT INTO `categories` VALUES ('CTG037', 'Engsel', '10.01.04.01', 'admin', NULL, '2024-06-28 14:25:00', '2024-06-28 14:25:00');
INSERT INTO `categories` VALUES ('CTG038', 'Kaca', '10.01.04.01', 'admin', NULL, '2024-06-28 14:25:39', '2024-06-28 14:25:39');

SET FOREIGN_KEY_CHECKS = 1;
