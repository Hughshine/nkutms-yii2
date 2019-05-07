/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100138
 Source Host           : localhost:3306
 Source Schema         : ticketing_02

 Target Server Type    : MySQL
 Target Server Version : 100138
 File Encoding         : 65001

 Date: 08/05/2019 01:17:15
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tk_activity
-- ----------------------------
DROP TABLE IF EXISTS `tk_activity`;
CREATE TABLE `tk_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_name` varchar(32) COLLATE utf8_unicode_ci DEFAULT 'default-name',
  `release_by` int(11) DEFAULT NULL COMMENT 'organizer/-id',
  `category` smallint(6) unsigned zerofill DEFAULT '000000' COMMENT '标记活动类别 0-讲座 1-文艺 2-其他 3-未知',
  `status` smallint(6) DEFAULT '0' COMMENT '状态0-正常1-取消2-结束',
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT '未知地点',
  `release_at` int(11) DEFAULT NULL,
  `ticketing_start_at` int(11) DEFAULT NULL,
  `ticketing_end_at` int(11) DEFAULT NULL,
  `start_at` int(11) DEFAULT NULL,
  `end_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `introduction` text COLLATE utf8_unicode_ci COMMENT '介绍',
  `current_people` int(11) DEFAULT '0',
  `max_people` int(11) DEFAULT '0',
  `current_serial` int(11) DEFAULT '0' COMMENT '用于产生票务的序列号',
  `pic_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '暂不支持传入图片',
  `summary` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_organizer` (`release_by`),
  CONSTRAINT `activity_organizer` FOREIGN KEY (`release_by`) REFERENCES `tk_organizer` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1113 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for tk_admin
-- ----------------------------
DROP TABLE IF EXISTS `tk_admin`;
CREATE TABLE `tk_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `signup_at` int(11) DEFAULT NULL,
  `logged_at` int(11) DEFAULT NULL COMMENT '上次登入时间',
  `updated_at` int(11) DEFAULT NULL COMMENT '更新时间（主要是上一次修改密码的时间）',
  `status` smallint(6) DEFAULT '10',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for tk_notice
-- ----------------------------
DROP TABLE IF EXISTS `tk_notice`;
CREATE TABLE `tk_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `summary` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `updated_at` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `status` int(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for tk_organizer
-- ----------------------------
DROP TABLE IF EXISTS `tk_organizer`;
CREATE TABLE `tk_organizer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `org_name` varchar(32) COLLATE utf8_unicode_ci DEFAULT '未知发布者' COMMENT '应必须填写',
  `credential` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '该用户类别下，他的证件号',
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '123456',
  `access_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `signup_at` int(11) DEFAULT NULL,
  `logged_at` int(11) DEFAULT '0' COMMENT '使用int类型便于比较操作',
  `updated_at` int(11) DEFAULT '0',
  `expire_at` int(11) DEFAULT '0',
  `allowance` int(11) DEFAULT NULL COMMENT '用于限制访问频率',
  `allowance_updated_at` int(11) DEFAULT '0',
  `category` smallint(6) DEFAULT NULL COMMENT '标记用户类别 0-校级组织，1-学生社团',
  `auth_key` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT '自动登录key',
  `status` smallint(6) DEFAULT '10' COMMENT '状态',
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for tk_ticket
-- ----------------------------
DROP TABLE IF EXISTS `tk_ticket`;
CREATE TABLE `tk_ticket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '1',
  `activity_id` int(11) DEFAULT '1',
  `created_at` int(11) DEFAULT NULL,
  `serial_number` int(11) unsigned DEFAULT '0',
  `status` smallint(1) unsigned zerofill DEFAULT '0' COMMENT ' 0-有效，1-已退回withdraw，2-过期, 3 - 未知',
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ticket_user` (`user_id`),
  KEY `ticket_activity` (`activity_id`),
  CONSTRAINT `ticket_activity` FOREIGN KEY (`activity_id`) REFERENCES `tk_activity` (`id`),
  CONSTRAINT `ticket_user` FOREIGN KEY (`user_id`) REFERENCES `tk_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for tk_user
-- ----------------------------
DROP TABLE IF EXISTS `tk_user`;
CREATE TABLE `tk_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(32) COLLATE utf8_unicode_ci DEFAULT 'default-name',
  `wechat_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category` smallint(6) unsigned zerofill DEFAULT NULL COMMENT '标记用户类别0-学生1-教职员工2-其他',
  `credential` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '该用户类别下，他的证件号。web端使用此为账号进行登录',
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT '123456',
  `access_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `signup_at` int(11) DEFAULT NULL,
  `logged_at` int(11) DEFAULT '0' COMMENT '使用int类型便于比较操作',
  `expire_at` int(11) DEFAULT '0',
  `updated_at` int(11) DEFAULT NULL,
  `allowance` int(11) DEFAULT '1' COMMENT '用于限制访问频率',
  `allowance_updated_at` int(11) DEFAULT '0',
  `auth_key` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT '自动登录key',
  `status` smallint(6) NOT NULL DEFAULT '10' COMMENT '状态',
  `created_at` int(11) DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `img_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`,`wechat_id`) USING BTREE,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- View structure for user_activity
-- ----------------------------
DROP VIEW IF EXISTS `user_activity`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `user_activity` AS select `tk_activity`.`id` AS `id`,`tk_activity`.`activity_name` AS `activity_name`,`tk_activity`.`category` AS `category`,`tk_organizer`.`org_name` AS `organizer_name`,`tk_activity`.`location` AS `location`,`tk_activity`.`start_at` AS `start_at`,`tk_activity`.`end_at` AS `end_at`,`tk_activity`.`current_people` AS `current_people`,`tk_activity`.`max_people` AS `max_people` from (`tk_activity` join `tk_organizer`) where ((`tk_activity`.`status` = 1) and (`tk_activity`.`release_by` = `tk_organizer`.`id`));

-- ----------------------------
-- Procedure structure for withdraw_ticket
-- ----------------------------
DROP PROCEDURE IF EXISTS `withdraw_ticket`;
delimiter ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `withdraw_ticket`(in ticket_id int, in in_user_id int)
begin
	declare ticket_user_id int;
	declare ticket_act_id int;
	declare ticket_status int;
	set ticket_user_id = (select user_id from tk_ticket where tk_ticket.id = ticket_id);
	set ticket_act_id = (select activity_id from tk_ticket where tk_ticket.id = ticket_id);
	set ticket_status = (select status from tk_ticket where tk_ticket.id = ticket_id);

	if (Not exists(select * from tk_ticket where tk_ticket.id = ticket_id))
	then
		  signal sqlstate '20000' set message_text = '不存在该票务'; end if; 
			
	if (ticket_user_id <> in_user_id)
	then
		  signal sqlstate '20000' set message_text = '用户操作不合法'; end if; 
			
	if(ticket_status <> 0)
	then 
			signal sqlstate '20000' set message_text = '不能重复退票'; end if; 
			


	update tk_ticket set status = 1 where tk_ticket.id = ticket_id;
	update tk_ticket set updated_at = (unix_timestamp());
	update tk_activity set current_people = current_people - 1 where tk_activity.id = ticket_act_id;
	
end;
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tk_ticket
-- ----------------------------
DROP TRIGGER IF EXISTS `insert_ticket`;
delimiter ;;
CREATE TRIGGER `insert_ticket` BEFORE INSERT ON `tk_ticket` FOR EACH ROW begin
	declare act_id INTEGER DEFAULT NULL;
	declare act_max_people INTEGER default null;
	declare act_current_people INTEGER default null;
	declare act_current_serial INTEGER default null;
	
	declare act_ticketing_start_at integer default null;
	declare act_ticketing_end_at integer default null;

	set act_id = New.activity_id;
	set act_max_people = (select max_people from tk_activity where tk_activity.id = act_id);
	set act_current_people = (select current_people from tk_activity where tk_activity.id = act_id);
	set act_current_serial = (select current_serial from tk_activity where tk_activity.id = act_id);

	set act_ticketing_start_at = (select ticketing_start_at from tk_activity where tk_activity.id = act_id);
	set act_ticketing_end_at = (select ticketing_end_at from tk_activity where tk_activity.id = act_id);
	
	if ISNULL(act_id)
	then 
	    signal sqlstate '20000' set message_text = '活动并不存在！';     
		  insert into New.id values(null); end if; -- mysql 没有办法显式结束insert
	
	if (EXISTS(select id from tk_ticket where ((activity_id = act_id) AND (user_id = New.user_id) AND (status = 0))))
	then 
		  signal sqlstate '20000' set message_text = '已经抢过票！';     
			insert into New.id values(null); end if; 
			
	if (unix_timestamp()<act_ticketing_start_at OR unix_timestamp() >act_ticketing_end_at)
	then
			signal sqlstate '20000' set message_text = '未在抢票时间！';     
			insert into New.id values(null); end if; 
			
	if(act_max_people <= act_current_people)
	then
		signal sqlstate '20000' set message_text = '已达到活动人数上限！';    
		insert into New.id values(null); 
	else
		update tk_activity set current_people = current_people + 1 where tk_activity.id = act_id;  
		update tk_activity set current_serial = current_serial + 1 where tk_activity.id = act_id;  
	end if; 
		
	if ISNULL(New.serial_number) 
	then
		insert into New.serial_number values(act_current_serial-1); end if;
		
	if (New.serial_number = 0)
	then
		set New.serial_number = act_current_serial; end if;
		
  if ISNULL(New.created_at)
	then
		insert into New.created_at values(unix_timestamp()); end if;
	
	if ISNULL(New.updated_at)
	then
		insert into New.updated_at values(unix_timestamp()); end if;

end
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
