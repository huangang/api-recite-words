-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015-09-10 11:47:09
-- 服务器版本: 5.5.44-cll-lve
-- PHP 版本: 5.4.44

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 数据库: `app`
--

-- --------------------------------------------------------

--
-- 表的结构 `app_user`
--

# DROP TABLE IF EXISTS `app_user`;
CREATE TABLE IF NOT EXISTS `bysj_user` (
  `id` int(13) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `nickname` varchar(200) CHARACTER SET utf8mb4 NOT NULL DEFAULT '某某用户' COMMENT '用户昵称',
  `password` varchar(32) NOT NULL COMMENT '用户密码',
  `mobile` int(11) DEFAULT NULL COMMENT '手机号码',
  `openid` varchar(100) DEFAULT NULL COMMENT '微信openid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=1 ;

--
-- 数据库: `app`
--

-- --------------------------------------------------------

--
-- 表的结构 `app_dictionary`
--

# DROP TABLE IF EXISTS `app_dictionary`;
CREATE TABLE IF NOT EXISTS `bysj_dictionary` (
  `id` int(13) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `word` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='词典表' AUTO_INCREMENT=291619 ;