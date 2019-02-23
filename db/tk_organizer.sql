-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2019-02-23 13:35:15
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tk_organizer`
--
ALTER TABLE `tk_organizer`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tk_organizer`
--
ALTER TABLE `tk_organizer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
