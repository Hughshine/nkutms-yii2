-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2019-04-03 07:51:24
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
  `introduction` text COLLATE utf8_unicode_ci COMMENT '介绍',
  `current_people` int(11) DEFAULT '0',
  `max_people` int(11) DEFAULT '0',
  `current_serial` int(11) DEFAULT '0' COMMENT '用于产生票务的序列号',
  `pic_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '暂不支持传入图片',
  `summary` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `tk_activity`
--

INSERT INTO `tk_activity` (`id`, `activity_name`, `release_by`, `category`, `status`, `location`, `release_at`, `ticketing_start_at`, `ticketing_end_at`, `start_at`, `end_at`, `updated_at`, `introduction`, `current_people`, `max_people`, `current_serial`, `pic_url`, `summary`, `created_at`) VALUES
(39, '瑞峰', 10, 000000, 3, '学活A103', 1553466620, 1553809500, 1553986200, 1554065100, 1554985800, 1553441517, '<p>啦啦啦啦啦<sup>jioadsfj</sup></p><p><sup><br/></sup></p>', 0, 20, 1, '/upload_files/activity/2019-03-24/19-03-24//201903241553438837176018.png', '啦啦啦啦啦jioadsfj', 1553441420),
(40, '瑞峰22222', 10, 000000, 0, '学活A103', 1553467295, 1553809500, 1553986200, 1554065100, 1554985800, 1553442767, '<p>啦啦啦啦啦<sup>jioadsfj</sup></p><p><sup>$newDir.</sup></p><p><br/></p><p class="p1" style="white-space: normal;">(/Applications/XAMPP/xamppfiles/htdocs/ticketing/frontend/web/upload_files/activity/2019-03-24//201903241553438837176018.png</p><p style="white-space: normal;"><sup><br/></sup><span style="color: rgb(255, 255, 255); font-family: &quot;Source Sans Pro&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14px; background-color: rgb(221, 75, 57);">/Applications/XAMPP/xamppfiles/htdocs/ticketing/frontend/web/upload_files/activity/2019-03-24//201903241553438837176018.png</span></p><p class="p1">$newDir.</p>', 0, 20, 1, '/upload_files/activity/2019-03-24//201903241553442091817459.png', '啦啦啦啦啦jioadsfj$newDir.(/Applications/XAMPP/xamppfiles/htdocs/ticketing/frontend/web/upload_', 1553442095),
(41, '瑞峰2222', 10, 000000, 0, '学活A103', 1553468545, 1553809500, 1553986200, 1554065100, 1554985800, 1553443345, '<p>啦啦啦啦啦<sup>jioadsfj</sup></p><p><sup>$newDir.</sup></p><p><br/></p><p class="p1" style="white-space: normal;">(/Applications/XAMPP/xamppfiles/htdocs/ticketing/frontend/web/upload_files/activity/2019-03-24//201903241553438837176018.png</p><p style="white-space: normal;"><sup><br/></sup><span style="color: rgb(255, 255, 255); font-family: &quot;Source Sans Pro&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14px; background-color: rgb(221, 75, 57);">/Applications/XAMPP/xamppfiles/htdocs/ticketing/frontend/web/upload_files/activity/2019-03-24//201903241553438837176018.png</span></p><p class="p1">$newDir.</p>', 0, 20, 1, '/upload_files/activity/2019-03-25//201903241553443021985110.png', '啦啦啦啦啦jioadsfj$newDir.(/Applications/XAMPP/xamppfiles/htdocs/ticketing/frontend/web/upload_', 1553443345),
(42, '瑞峰2222', 10, 000000, 0, '学活A103', 1553468563, 1553809500, 1553986200, 1554065100, 1554985800, 1553443363, '<p>啦啦啦啦啦<sup>jioadsfj</sup></p><p><sup>$newDir.</sup></p><p><br/></p><p class="p1" style="white-space: normal;">(/Applications/XAMPP/xamppfiles/htdocs/ticketing/frontend/web/upload_files/activity/2019-03-24//201903241553438837176018.png</p><p style="white-space: normal;"><sup><br/></sup><span style="color: rgb(255, 255, 255); font-family: &quot;Source Sans Pro&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14px; background-color: rgb(221, 75, 57);">/Applications/XAMPP/xamppfiles/htdocs/ticketing/frontend/web/upload_files/activity/2019-03-24//201903241553438837176018.png</span></p><p class="p1">$newDir.</p>', 0, 20, 1, '/upload_files/activity/2019-03-25//201903241553442902475332.png', '啦啦啦啦啦jioadsfj$newDir.(/Applications/XAMPP/xamppfiles/htdocs/ticketing/frontend/web/upload_', 1553443363),
(43, 'test1', 10, 000000, 0, 'test', 1553513045, 1553809500, 1553986200, 1554065100, 1554985800, 1553487845, '<p>test</p>', 0, 10, 1, NULL, 'test', 1553487845),
(44, '瑞峰2222', 10, 000000, 0, '111', 1553513144, 1553809500, 1553986200, 1554065100, 1554985800, 1553487944, '<p>ttt</p>', 0, 111, 1, NULL, 'ttt', 1553487944),
(45, '1111', 10, 000000, 0, '1', 1553513229, 1553809500, 1553986200, 1554065100, 1554985800, 1553488029, '<p>111</p>', 0, 11, 1, '/upload_files/activity/2019-03-25/1553487973792260.png', '111', 1553488029),
(46, '11', 10, 000000, 0, '1', 1553513253, 1553809500, 1553986200, 1554065100, 1554985800, 1553488053, '<p>11</p>', 0, 1, 1, '/upload_files/activity/2019-03-25/1553488045437582.png', '11', 1553488053),
(47, '11', 10, 000000, 0, '1', 1553513429, 1553809500, 1553986200, 1554065100, 1554985800, 1553488229, '<p>11</p>', 0, 1, 1, NULL, '11', 1553488229),
(48, '11', 10, 000000, 0, '1', 1553513635, 1553809500, 1553986200, 1554065100, 1554985800, 1553488435, '<p>11</p>', 0, 1, 1, '/upload_files/activity/2019-03-25/51553488434414590.png', '11', 1553488435),
(49, '111', 10, 000000, 0, '1', 1553513657, 1553809500, 1553986200, 1554065100, 1554985800, 1553488457, '<p>1</p>', 0, 1, 1, NULL, '1', 1553488457),
(50, '101', 10, 000000, 0, '1', 1553513828, 1553809500, 1553986200, 1554065100, 1554985800, 1553488628, '<p>1</p>', 0, 1, 1, NULL, '1', 1553488628),
(51, '101', 10, 000000, 0, '1', 1553513840, 1553809500, 1553986200, 1554065100, 1554985800, 1553488640, '<p>1</p>', 0, 1, 1, '/upload_files/activity/2019-03-25//201903251553488638990599.png', '1', 1553488640),
(52, '103', 10, 000000, 0, '1', 1553513905, 1553809500, 1553986200, 1554065100, 1554985800, 1553488793, '<p>1</p>', 0, 1, 1, '/upload_files/activity/2019-03-25//201903251553488791568250.png', '1', 1553488705),
(53, '104', 10, 000002, 1, '1', 1553514045, 1553809500, 1553986200, 1554065100, 1554985800, 1553512269, '<p>11</p>', 0, 1, 1, '/upload_files/activity/2019-03-25//201903251553488831950079.png', '11', 1553488845),
(54, '105', 10, 000000, 1, '1', 1553514116, 0, 1553986200, 1554065100, 1554985800, 1553489363, '<p>111111111</p>', 1, 1, 2, '/upload_files/activity/2019-03-25//201903251553488888901076.png', '111111111', 1553488916);

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
-- 表的结构 `tk_notice`
--

CREATE TABLE IF NOT EXISTS `tk_notice` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `summary` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `updated_at` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `tk_notice`
--

INSERT INTO `tk_notice` (`id`, `title`, `summary`, `content`, `updated_at`, `created_at`) VALUES
(1, '1111', 'adfsiojf', '<p>adfsiojf</p>', 1553489200, 1553489200),
(2, 'test_on_server', 'nothing', '<p>nothing</p>', 1553512288, 1553512288);

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
(10, '1号', '0001', '$2y$13$O0kh/hiGgvzdNkefpEoPVe0MJg.ZgMNFvxw48w9Fya6qoUXGdXahG', NULL, NULL, 0, 1553512872, 0, NULL, 0, 0, 'h-aXKIrbvIHHmoL_G7-Q_cjXg7lMbE7C', 10, NULL, NULL),
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
  `status` smallint(1) unsigned zerofill DEFAULT '0' COMMENT ' 0-有效，1-已退回withdraw，2-过期, 3 - 未知',
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `tk_ticket`
--

INSERT INTO `tk_ticket` (`id`, `user_id`, `activity_id`, `created_at`, `serial_number`, `status`, `updated_at`) VALUES
(38, 20, 54, 1553489363, 1, 0, 1553489363);

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
  `created_at` int(11) DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `img_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `tk_user`
--

INSERT INTO `tk_user` (`id`, `user_name`, `wechat_id`, `category`, `credential`, `password`, `access_token`, `signup_at`, `logged_at`, `expire_at`, `updated_at`, `allowance`, `allowance_updated_at`, `auth_key`, `status`, `created_at`, `email`, `img_url`, `password_reset_token`) VALUES
(20, 'user111', '', 000000, '1711352', '$2y$13$1tLUeTvs/ZakJ.s9QOj5IeHt8sxAuZWVtgBTKq1bC5uLaSzaRi2rW', '', NULL, 0, 0, 1553437856, 2, 0, '2QtuDGoiG5akoGF0YS0oE6eK91Vh83wi', 10, 1553436777, '1015545250@qq.com', '/upload_files/user/1711352//201903241553437341575892.png', NULL);

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
-- Indexes for table `tk_admin`
--
ALTER TABLE `tk_admin`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `id` (`id`);

--
-- Indexes for table `tk_notice`
--
ALTER TABLE `tk_notice`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT for table `tk_admin`
--
ALTER TABLE `tk_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tk_notice`
--
ALTER TABLE `tk_notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tk_organizer`
--
ALTER TABLE `tk_organizer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `tk_ticket`
--
ALTER TABLE `tk_ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `tk_user`
--
ALTER TABLE `tk_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- 限制导出的表
--

--
-- 限制表 `tk_activity`
--
ALTER TABLE `tk_activity`
  ADD CONSTRAINT `activity_organizer` FOREIGN KEY (`release_by`) REFERENCES `tk_organizer` (`id`);

--
-- 限制表 `tk_ticket`
--
ALTER TABLE `tk_ticket`
  ADD CONSTRAINT `ticket_activity` FOREIGN KEY (`activity_id`) REFERENCES `tk_activity` (`id`),
  ADD CONSTRAINT `ticket_user` FOREIGN KEY (`user_id`) REFERENCES `tk_user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
