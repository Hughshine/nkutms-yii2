/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100137
 Source Host           : localhost:3306
 Source Schema         : nm_ticketing

 Target Server Type    : MySQL
 Target Server Version : 100137
 File Encoding         : 65001

 Date: 12/02/2019 16:05:20
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tk_activity
-- ----------------------------
DROP TABLE IF EXISTS `tk_activity`;
CREATE TABLE `tk_activity` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8_unicode_ci DEFAULT 'default-name',
  `release_by` int(10) DEFAULT NULL COMMENT 'organizer/-id',
  `category` int(1) unsigned zerofill DEFAULT '0' COMMENT '标记活动类别 0-讲座 1-文艺 2-其他 3-未知',
  `status` int(1) DEFAULT '0' COMMENT '状态0-正常1-取消2-结束',
  `location` varchar(64) COLLATE utf8_unicode_ci DEFAULT '未知地点',
  `release_at` int(11) DEFAULT NULL,
  `ticketing_start_at` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ticketing_end_at` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `start_at` int(11) DEFAULT NULL,
  `end_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  `introduction` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'no introduction' COMMENT '介绍',
  `current_people` int(5) DEFAULT '0',
  `max_people` int(5) DEFAULT '0',
  `current_serial` int(10) DEFAULT '0' COMMENT '用于产生票务的序列号',
  `pic_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '暂不支持传入图片',
  PRIMARY KEY (`id`),
  KEY `activity_organizer` (`release_by`),
  CONSTRAINT `activity_organizer` FOREIGN KEY (`release_by`) REFERENCES `tk_organizer` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of tk_activity
-- ----------------------------
BEGIN;
INSERT INTO `tk_activity` VALUES (1, 'activity1', 1, 0, 1, NULL, 2147483647, NULL, '2247483647', 0, NULL, 2147483647, NULL, 1, 30, 10, NULL);
INSERT INTO `tk_activity` VALUES (9, 'default-name', 1, 0, 0, '未知地点', 0, NULL, NULL, 0, NULL, 2147483647, 'no introduction', 0, 0, 0, NULL);
INSERT INTO `tk_activity` VALUES (10, 'default-name', 1, 0, 0, '未知地点', 0, NULL, NULL, 0, NULL, 2147483647, 'no introduction', 0, 0, 0, NULL);
INSERT INTO `tk_activity` VALUES (11, 'act', NULL, 0, 0, '未知地点', 0, NULL, NULL, 0, NULL, 0, 'no introduction', 0, 0, 0, NULL);
INSERT INTO `tk_activity` VALUES (12, 'default-name', NULL, 0, 0, '未知地点', 0, NULL, NULL, 0, NULL, 0, 'no introduction', 0, 0, 0, NULL);
INSERT INTO `tk_activity` VALUES (13, 'default-name', NULL, 0, 0, '未知地点', NULL, NULL, '2247483647', 0, NULL, 0, 'no introduction', 1, 0, 3, NULL);
INSERT INTO `tk_activity` VALUES (14, 'default-name', NULL, 0, 0, '未知地点', 0, NULL, NULL, 0, NULL, 0, 'no introduction', 0, 0, 0, NULL);
INSERT INTO `tk_activity` VALUES (15, 'default-name', NULL, 0, 0, '未知地点', 0, NULL, NULL, 0, NULL, 0, 'no introduction', 0, 0, 0, NULL);
INSERT INTO `tk_activity` VALUES (16, 'default-name', NULL, 0, 0, '未知地点', 0, NULL, NULL, 0, NULL, 0, 'no introduction', 0, 0, 0, NULL);
INSERT INTO `tk_activity` VALUES (17, 'default-name', NULL, 0, 0, '未知地点', 0, NULL, NULL, 0, NULL, 0, 'no introduction', 0, 0, 0, NULL);
INSERT INTO `tk_activity` VALUES (18, 'default-name', NULL, 0, 0, '未知地点', 0, NULL, NULL, 0, NULL, 0, 'no introduction', 0, 0, 0, NULL);
INSERT INTO `tk_activity` VALUES (19, 'default-name', NULL, 0, 0, '未知地点', 0, NULL, NULL, 0, NULL, 0, 'no introduction', 0, 0, 0, NULL);
INSERT INTO `tk_activity` VALUES (20, 'default-name', NULL, 0, 0, '未知地点', 0, NULL, NULL, 0, NULL, 0, 'no introduction', 0, 0, 0, NULL);
INSERT INTO `tk_activity` VALUES (21, 'default-name', NULL, 0, 0, '未知地点', 0, NULL, NULL, 0, NULL, 0, 'no introduction', 0, 0, 0, NULL);
INSERT INTO `tk_activity` VALUES (22, 'myname', 1, 0, 0, '1', 0, '1', '1', 0, 0, 0, '1', 0, 1, 1, NULL);
INSERT INTO `tk_activity` VALUES (23, 'myname', 1, 0, 0, '1', 0, '1', '1', 0, 0, 0, '1', 0, 1, 1, NULL);
INSERT INTO `tk_activity` VALUES (24, 'myname', 1, 0, 0, '1', 0, '1', '1', 0, 0, 0, '1', 0, 1, 1, NULL);
INSERT INTO `tk_activity` VALUES (25, 'myname', 1, 0, 0, '1', 0, '1', '1', 0, 0, 0, '1', 0, 1, 1, NULL);
INSERT INTO `tk_activity` VALUES (26, 'myname', 1, 0, 0, '1', 0, '1', '1', 0, 0, 0, '1', 0, 1, 1, NULL);
INSERT INTO `tk_activity` VALUES (27, 'myname', 1, 0, 0, '1', 0, '1', '1', 0, 0, 0, '1', 0, 1, 1, NULL);
INSERT INTO `tk_activity` VALUES (28, 'myname2', 1, 0, 0, '11111', 0, '1', '1', 0, 0, 2147483647, '1', 0, 1, 1, NULL);
INSERT INTO `tk_activity` VALUES (29, '1location111111111', 2, 0, 1, 'locationticketing_start_at', 1549958101, '1111111111111111', '3111111111111111', 2147483647, 2147483647, NULL, '11111', 0, 5, 1, NULL);
COMMIT;

-- ----------------------------
-- Table structure for tk_activity_event
-- ----------------------------
DROP TABLE IF EXISTS `tk_activity_event`;
CREATE TABLE `tk_activity_event` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `organizer_id` int(10) DEFAULT NULL,
  `activity_id` int(10) DEFAULT NULL,
  `status` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '2' COMMENT '0-发布1-取消',
  `update_at` int(11) DEFAULT NULL,
  `operated_by_admin` int(10) NOT NULL DEFAULT '-1' COMMENT '-1时，非管理员操作',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `tkae_admin` (`operated_by_admin`),
  KEY `tkae_activity` (`activity_id`),
  KEY `tkae_organizer` (`organizer_id`),
  CONSTRAINT `tkae_activity` FOREIGN KEY (`activity_id`) REFERENCES `tk_activity` (`id`),
  CONSTRAINT `tkae_organizer` FOREIGN KEY (`organizer_id`) REFERENCES `tk_organizer` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of tk_activity_event
-- ----------------------------
BEGIN;
INSERT INTO `tk_activity_event` VALUES (5, 1, 1, '1', 0, -1);
INSERT INTO `tk_activity_event` VALUES (6, 1, 1, '1', 0, -1);
INSERT INTO `tk_activity_event` VALUES (7, 1, 1, '1', 1549873895, -1);
INSERT INTO `tk_activity_event` VALUES (8, 1, 23, '0', 1549876742, -1);
INSERT INTO `tk_activity_event` VALUES (9, 1, 24, '0', 1549876767, -1);
INSERT INTO `tk_activity_event` VALUES (10, 1, 25, '0', 1549876841, -1);
INSERT INTO `tk_activity_event` VALUES (11, 1, 26, '0', 1549876841, -1);
INSERT INTO `tk_activity_event` VALUES (12, 1, 27, '0', 1549876843, -1);
INSERT INTO `tk_activity_event` VALUES (13, 1, 28, '0', 1549877206, -1);
INSERT INTO `tk_activity_event` VALUES (14, 2, 29, '0', 1549958101, -1);
INSERT INTO `tk_activity_event` VALUES (15, 2, 29, '1', 1549958182, -1);
COMMIT;

-- ----------------------------
-- Table structure for tk_admin
-- ----------------------------
DROP TABLE IF EXISTS `tk_admin`;
CREATE TABLE `tk_admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `signup_at` datetime(6) DEFAULT NULL,
  `logged_at` datetime(6) DEFAULT NULL COMMENT '上次登入时间',
  PRIMARY KEY (`id`,`name`) USING BTREE,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for tk_organizer
-- ----------------------------
DROP TABLE IF EXISTS `tk_organizer`;
CREATE TABLE `tk_organizer` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8_unicode_ci DEFAULT '未知发布者' COMMENT '应必须填写',
  `credential` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '该用户类别下，他的证件号',
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '123456',
  `access_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `signup_at` datetime(6) DEFAULT '0000-00-00 00:00:00.000000',
  `logged_at` int(11) DEFAULT '0' COMMENT '使用int类型便于比较操作',
  `updated_at` int(11) DEFAULT '0',
  `expire_at` int(11) DEFAULT '0',
  `allowance` int(11) DEFAULT NULL COMMENT '用于限制访问频率',
  `allowance_updated_at` int(11) DEFAULT '0',
  `category` int(32) DEFAULT NULL COMMENT '标记用户类别 0-校级组织，1-学生社团',
  `wechat_id1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '一个社团最多有三个管理者，暂时不考虑一个人管理多个社团',
  `wechat_id2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wechat_id3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of tk_organizer
-- ----------------------------
BEGIN;
INSERT INTO `tk_organizer` VALUES (1, '未知发布者', '00004', '$2y$13$.sOEmStebEqFy1ykguxLGOywQt5ICY4X6jDOpiqR.x0rAuWVjz6WK', NULL, '0000-00-00 00:00:00.000000', 0, 0, 0, NULL, 0, NULL, NULL, NULL, NULL);
INSERT INTO `tk_organizer` VALUES (2, '未知发布者', '00002', '$2y$13$hh.86DubrqZhoRT0Y3pTz.Pb3yrLQ8K4uFhEQ8X/pOxO9AR3srhH.', NULL, '0000-00-00 00:00:00.000000', 0, 0, 0, NULL, 0, NULL, NULL, NULL, NULL);
INSERT INTO `tk_organizer` VALUES (3, '未知发布者', '00003', '$2y$13$hh.86DubrqZhoRT0Y3pTz.Pb3yrLQ8K4uFhEQ8X/pOxO9AR3srhH.', NULL, '0000-00-00 00:00:00.000000', 0, 0, 0, NULL, 0, NULL, NULL, NULL, NULL);
INSERT INTO `tk_organizer` VALUES (4, '未知发布者', '00004', '$2y$13$VJxIOOia9MTga5RbeMbav.Et9tukDkiGunj5U5kGjnwUQ3nocZLvO', 'gEsIz4b_fN2Lua4P4A_ehir4PqdbhwuY', '0000-00-00 00:00:00.000000', 1549872771, 0, 1549959171, 1, 1549958200, NULL, NULL, NULL, NULL);
INSERT INTO `tk_organizer` VALUES (5, '未知发布者', '1711111', '$2y$13$nrR7HucSscFGEfwybn9cgOsVjkyeTpz.ISl/nvSAHOZBP.hcjeexi', 'V3uQD09eC5C5b8bIGXcx9Fs91gEBsgCe', '0000-00-00 00:00:00.000000', 1549957704, 0, 1550044104, NULL, 0, NULL, NULL, NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for tk_ticket
-- ----------------------------
DROP TABLE IF EXISTS `tk_ticket`;
CREATE TABLE `tk_ticket` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT '1',
  `activity_id` int(10) DEFAULT '1',
  `created_at` int(11) DEFAULT NULL,
  `serial_number` int(10) unsigned DEFAULT '0',
  `status` int(1) unsigned zerofill DEFAULT '0' COMMENT ' 0-有效，1-已退回withdraw，2-过期, 3 - 未知',
  PRIMARY KEY (`id`),
  KEY `ticket_user` (`user_id`),
  KEY `ticket_activity` (`activity_id`),
  CONSTRAINT `ticket_activity` FOREIGN KEY (`activity_id`) REFERENCES `tk_activity` (`id`),
  CONSTRAINT `ticket_user` FOREIGN KEY (`user_id`) REFERENCES `tk_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of tk_ticket
-- ----------------------------
BEGIN;
INSERT INTO `tk_ticket` VALUES (7, 7, 1, NULL, 0, 1);
INSERT INTO `tk_ticket` VALUES (8, 7, 1, NULL, 0, 1);
INSERT INTO `tk_ticket` VALUES (9, 7, 1, NULL, 0, 0);
INSERT INTO `tk_ticket` VALUES (10, 7, 1, NULL, 0, 0);
INSERT INTO `tk_ticket` VALUES (11, 9, 1, 1549856395, 1, 0);
INSERT INTO `tk_ticket` VALUES (12, 1, 1, 1549856643, 2, 0);
INSERT INTO `tk_ticket` VALUES (13, 2, 1, 1549856759, 3, 0);
INSERT INTO `tk_ticket` VALUES (14, 3, 1, 1549856772, 4, 0);
INSERT INTO `tk_ticket` VALUES (15, 4, 1, 1549856791, 5, 0);
INSERT INTO `tk_ticket` VALUES (16, 5, 1, 1549856814, 6, 0);
INSERT INTO `tk_ticket` VALUES (17, 6, 1, 1549856825, 7, 0);
INSERT INTO `tk_ticket` VALUES (18, 1, 13, 1549956991, 0, 1);
INSERT INTO `tk_ticket` VALUES (19, 1, 13, 1549957265, 1, 1);
INSERT INTO `tk_ticket` VALUES (20, 1, 13, 1549957296, 2, 0);
INSERT INTO `tk_ticket` VALUES (21, 8, 1, 1549957878, 8, 1);
INSERT INTO `tk_ticket` VALUES (22, 8, 1, 1549957922, 9, 0);
COMMIT;

-- ----------------------------
-- Table structure for tk_ticket_event
-- ----------------------------
DROP TABLE IF EXISTS `tk_ticket_event`;
CREATE TABLE `tk_ticket_event` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(20) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `activity_id` int(10) DEFAULT NULL,
  `status` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '0-发布1-取消',
  `update_at` int(11) DEFAULT NULL,
  `operated_by_admin` int(10) NOT NULL DEFAULT '-1' COMMENT '-1时，非管理员操作',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `tke_user` (`user_id`),
  KEY `tke_ticket` (`ticket_id`),
  KEY `tke_admin` (`operated_by_admin`),
  KEY `tke_activity` (`activity_id`),
  CONSTRAINT `tke_activity` FOREIGN KEY (`activity_id`) REFERENCES `tk_activity` (`id`),
  CONSTRAINT `tke_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `tk_ticket` (`id`),
  CONSTRAINT `tke_user` FOREIGN KEY (`user_id`) REFERENCES `tk_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of tk_ticket_event
-- ----------------------------
BEGIN;
INSERT INTO `tk_ticket_event` VALUES (3, 8, 7, 1, '1', 1549810146, -1);
INSERT INTO `tk_ticket_event` VALUES (4, 7, 7, 1, '1', 1549810717, -1);
INSERT INTO `tk_ticket_event` VALUES (5, 17, 6, 1, '0', 1549856825, -1);
INSERT INTO `tk_ticket_event` VALUES (6, 18, 1, 13, '0', 1549956991, -1);
INSERT INTO `tk_ticket_event` VALUES (7, 18, 1, 13, '1', 1549957230, -1);
INSERT INTO `tk_ticket_event` VALUES (8, 19, 1, 13, '0', 1549957265, -1);
INSERT INTO `tk_ticket_event` VALUES (9, 19, 1, 13, '1', 1549957293, -1);
INSERT INTO `tk_ticket_event` VALUES (10, 20, 1, 13, '0', 1549957296, -1);
INSERT INTO `tk_ticket_event` VALUES (11, 21, 8, 1, '0', 1549957878, -1);
INSERT INTO `tk_ticket_event` VALUES (12, 21, 8, 1, '1', 1549957905, -1);
INSERT INTO `tk_ticket_event` VALUES (13, 22, 8, 1, '0', 1549957922, -1);
COMMIT;

-- ----------------------------
-- Table structure for tk_user
-- ----------------------------
DROP TABLE IF EXISTS `tk_user`;
CREATE TABLE `tk_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8_unicode_ci DEFAULT 'default-name',
  `wechat_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category` int(32) unsigned zerofill DEFAULT NULL COMMENT '标记用户类别0-学生1-教职员工2-其他',
  `credential` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '该用户类别下，他的证件号。web端使用此为账号进行登录',
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT '123456',
  `access_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `signup_at` int(11) DEFAULT NULL,
  `logged_at` int(11) DEFAULT '0' COMMENT '使用int类型便于比较操作',
  `expire_at` int(11) DEFAULT '0',
  `update_at` int(11) DEFAULT NULL,
  `allowance` int(11) DEFAULT '1' COMMENT '用于限制访问频率',
  `allowance_updated_at` int(11) DEFAULT '0',
  PRIMARY KEY (`id`,`wechat_id`) USING BTREE,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of tk_user
-- ----------------------------
BEGIN;
INSERT INTO `tk_user` VALUES (1, '1234', 'id1', NULL, '', '123456', NULL, 2147483647, 0, 0, 1549957363, 1, 0);
INSERT INTO `tk_user` VALUES (2, 'default-name', 'id2', NULL, '', '123456', NULL, 2147483647, 0, 0, 0, 1, 0);
INSERT INTO `tk_user` VALUES (3, 'default-name', 'id', NULL, NULL, '123456', NULL, 0, 0, 0, 0, 1, 0);
INSERT INTO `tk_user` VALUES (4, 'default-name', 'id111', 00000000000000000000000000000002, NULL, '123456', NULL, 2147483647, 0, 0, 0, 1, 0);
INSERT INTO `tk_user` VALUES (5, 'default-name', 'id1111', 00000000000000000000000000000001, NULL, '123456', NULL, 2147483647, 0, 0, 0, 1, 0);
INSERT INTO `tk_user` VALUES (6, 'default-name', 'id11111', 00000000000000000000000000000001, NULL, '123456', NULL, 2147483647, 0, 0, 2147483647, 1, 0);
INSERT INTO `tk_user` VALUES (7, 'default-name', 'id111111', 00000000000000000000000000000001, NULL, '123456', 'mo7Vm3TdQj-ILwr4Rmi6w65WkdBRj0nb', 2147483647, 0, 1549896603, 1549859054, 1, 1549859054);
INSERT INTO `tk_user` VALUES (8, 'default-name', '', NULL, NULL, '123456', 'Aq7_9Inr9SIiYRzGC17PY_jtM1TACIsq', NULL, 0, 1549893188, 1549806788, 1, 0);
INSERT INTO `tk_user` VALUES (9, 'default-name', 'test_id', 00000000000000000000000000000003, NULL, '123456', 'yS_mYVYL70Tx1Yljsz0XZ3a042ejQR_X', 1549810221, 0, 1549896878, 1549810478, 1, 0);
INSERT INTO `tk_user` VALUES (10, 'default-name', 'test_id2', 00000000000000000000000000000003, NULL, '123456', 'aGwLy66JxJkztTCEacZ-6GClFgJ3dFSW', 1549855292, 0, 1549953914, 1549945695, 1, 1549945695);
INSERT INTO `tk_user` VALUES (11, 'default-name', 'ididid', 00000000000000000000000000000003, NULL, '123456', 'BDUXha_hvFTkofX6DmFS5AdOJN6ZecVS', 1549945594, 0, 1550032263, 1549957922, 1, 1549957922);
INSERT INTO `tk_user` VALUES (12, 'default-name', 'idididid', 00000000000000000000000000000003, NULL, '123456', 'p4c7cQEveEhlHbqyPwdjoNrsn-WVmzwP', 1549945882, 0, 1550032323, 1549945923, 1, 0);
INSERT INTO `tk_user` VALUES (13, 'default-name', 'myididid', 00000000000000000000000000000003, NULL, '123456', 'peH1dhylo9okbejxwjYvAPbJ0XDx6RwR', 1549956842, 0, 1550043260, 1549956860, 1, 0);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
