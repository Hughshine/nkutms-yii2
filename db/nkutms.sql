-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2019-02-23 13:38:39
-- 服务器版本： 5.7.25
-- PHP Version: 7.1.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ticketing-test-1`
--

-- --------------------------------------------------------

--
-- 表的结构 `tk_activity`
--

CREATE TABLE IF NOT EXISTS `tk_activity` (
  `id` int(11) NOT NULL,
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
  `introduction` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'no introduction' COMMENT '介绍',
  `current_people` int(11) DEFAULT '0',
  `max_people` int(11) DEFAULT '0',
  `current_serial` int(11) DEFAULT '0' COMMENT '用于产生票务的序列号',
  `pic_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '暂不支持传入图片'
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `tk_activity_event`
--

CREATE TABLE IF NOT EXISTS `tk_activity_event` (
  `id` int(11) NOT NULL,
  `organizer_id` int(11) DEFAULT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `status` smallint(1) NOT NULL DEFAULT '2' COMMENT '0-发布1-取消',
  `update_at` int(11) DEFAULT NULL,
  `operated_by_admin` int(11) NOT NULL DEFAULT '-1' COMMENT '-1时，非管理员操作'
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `tk_admin`
--

CREATE TABLE IF NOT EXISTS `tk_admin` (
  `id` int(11) NOT NULL,
  `admin_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `signup_at` int(11) DEFAULT NULL,
  `logged_at` int(11) DEFAULT NULL COMMENT '上次登入时间',
  `updated_at` int(11) DEFAULT NULL COMMENT '更新时间（主要是上一次修改密码的时间）',
  `status` smallint(6) DEFAULT '10'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `tk_admin`
--

INSERT INTO `tk_admin` (`id`, `admin_name`, `password`, `signup_at`, `logged_at`, `updated_at`, `status`) VALUES
(0, 'LYL232', '$2y$13$70SOVxoQOBbB0MrwVw8N6.Fdm.HPe6IY8MER.MuvJoPsklXHAISzO', 0, 1550952234, 1550927034, 10),
(1, 'lxy', '$2y$13$70SOVxoQOBbB0MrwVw8N6.Fdm.HPe6IY8MER.MuvJoPsklXHAISzO', 0, 0, NULL, 10),
(2, 'lxy', '$2y$13$70SOVxoQOBbB0MrwVw8N6.Fdm.HPe6IY8MER.MuvJoPsklXHAISzO', 0, 0, NULL, 10),
(3, 'lxy', '$2y$13$70SOVxoQOBbB0MrwVw8N6.Fdm.HPe6IY8MER.MuvJoPsklXHAISzO', 0, 0, NULL, 10);

-- --------------------------------------------------------

--
-- 表的结构 `tk_organizer`
--

CREATE TABLE IF NOT EXISTS `tk_organizer` (
  `id` int(11) NOT NULL,
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
  `wechat_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `tk_organizer`
--

INSERT INTO `tk_organizer` (`id`, `org_name`, `credential`, `password`, `access_token`, `signup_at`, `logged_at`, `updated_at`, `expire_at`, `allowance`, `allowance_updated_at`, `category`, `auth_key`, `status`, `wechat_id`, `created_at`) VALUES
(10, '1号', '0001', '$2y$13$zdWjzhOUucl/iKI2FqLJP.zXIJUxP46UZbTEynsqtUHQMSWa6BgfO', NULL, NULL, 0, 0, 0, NULL, 0, 0, 'h-aXKIrbvIHHmoL_G7-Q_cjXg7lMbE7C', 10, NULL, NULL),
(11, '2号', '0002', '$2y$13$LD2LV5QXqUzpr4BeJFjB8enbAqqGKl7Y./bMtzr/GvcdpZr6gn6vO', NULL, NULL, 0, 0, 0, NULL, 0, 0, 'BRkmQ2DaDlgBhEsPeJ17a2pxoH9zzCuH', 10, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `tk_ticket`
--

CREATE TABLE IF NOT EXISTS `tk_ticket` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT '1',
  `activity_id` int(11) DEFAULT '1',
  `created_at` int(11) DEFAULT NULL,
  `serial_number` int(11) unsigned DEFAULT '0',
  `status` smallint(1) unsigned zerofill DEFAULT '0' COMMENT ' 0-有效，1-已退回withdraw，2-过期, 3 - 未知'
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `tk_ticket_event`
--

CREATE TABLE IF NOT EXISTS `tk_ticket_event` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `status` smallint(1) NOT NULL COMMENT '0-发布1-取消',
  `update_at` int(11) DEFAULT NULL,
  `operated_by_admin` int(11) NOT NULL DEFAULT '-1' COMMENT '-1时，非管理员操作'
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `tk_user`
--

CREATE TABLE IF NOT EXISTS `tk_user` (
  `id` int(11) NOT NULL,
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
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tk_activity`
--
ALTER TABLE `tk_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_organizer` (`release_by`);

--
-- Indexes for table `tk_activity_event`
--
ALTER TABLE `tk_activity_event`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `tkae_admin` (`operated_by_admin`),
  ADD KEY `tkae_activity` (`activity_id`),
  ADD KEY `tkae_organizer` (`organizer_id`);

--
-- Indexes for table `tk_admin`
--
ALTER TABLE `tk_admin`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `id` (`id`);

--
-- Indexes for table `tk_organizer`
--
ALTER TABLE `tk_organizer`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `id` (`id`);

--
-- Indexes for table `tk_ticket`
--
ALTER TABLE `tk_ticket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_user` (`user_id`),
  ADD KEY `ticket_activity` (`activity_id`);

--
-- Indexes for table `tk_ticket_event`
--
ALTER TABLE `tk_ticket_event`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `tke_user` (`user_id`),
  ADD KEY `tke_ticket` (`ticket_id`),
  ADD KEY `tke_admin` (`operated_by_admin`),
  ADD KEY `tke_activity` (`activity_id`);

--
-- Indexes for table `tk_user`
--
ALTER TABLE `tk_user`
  ADD PRIMARY KEY (`id`,`wechat_id`) USING BTREE,
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tk_activity`
--
ALTER TABLE `tk_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `tk_activity_event`
--
ALTER TABLE `tk_activity_event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `tk_admin`
--
ALTER TABLE `tk_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tk_organizer`
--
ALTER TABLE `tk_organizer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `tk_ticket`
--
ALTER TABLE `tk_ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `tk_ticket_event`
--
ALTER TABLE `tk_ticket_event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `tk_user`
--
ALTER TABLE `tk_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- 限制导出的表
--

--
-- 限制表 `tk_activity`
--
ALTER TABLE `tk_activity`
  ADD CONSTRAINT `activity_organizer` FOREIGN KEY (`release_by`) REFERENCES `tk_organizer` (`id`);

--
-- 限制表 `tk_activity_event`
--
ALTER TABLE `tk_activity_event`
  ADD CONSTRAINT `tkae_activity` FOREIGN KEY (`activity_id`) REFERENCES `tk_activity` (`id`),
  ADD CONSTRAINT `tkae_organizer` FOREIGN KEY (`organizer_id`) REFERENCES `tk_organizer` (`id`);

--
-- 限制表 `tk_ticket`
--
ALTER TABLE `tk_ticket`
  ADD CONSTRAINT `ticket_activity` FOREIGN KEY (`activity_id`) REFERENCES `tk_activity` (`id`),
  ADD CONSTRAINT `ticket_user` FOREIGN KEY (`user_id`) REFERENCES `tk_user` (`id`);

--
-- 限制表 `tk_ticket_event`
--
ALTER TABLE `tk_ticket_event`
  ADD CONSTRAINT `tke_activity` FOREIGN KEY (`activity_id`) REFERENCES `tk_activity` (`id`),
  ADD CONSTRAINT `tke_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `tk_ticket` (`id`),
  ADD CONSTRAINT `tke_user` FOREIGN KEY (`user_id`) REFERENCES `tk_user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
