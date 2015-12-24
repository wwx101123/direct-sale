-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- 主机: w.rdc.sae.sina.com.cn:3307
-- 生成日期: 2014 年 12 月 01 日 15:26
-- 服务器版本: 5.5.23
-- PHP 版本: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `app_mcontroller`
--

-- --------------------------------------------------------

--
-- 表的结构 `ds_express`
--

CREATE TABLE IF NOT EXISTS `ds_express` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

--
-- 转存表中的数据 `ds_express`
--

INSERT INTO `ds_express` (`id`, `name`, `code`) VALUES
(1, '德邦', 'debangwuliu'),
(2, '天地华宇', 'tiandihuayu'),
(3, '速尔物流', 'suer'),
(4, '优速快递', 'youshuwuliu'),
(5, '远成物流', 'yuanchengwuliu'),
(6, '佳怡物流', 'jiayiwuliu'),
(7, '申通物流', 'shentong'),
(8, '龙邦速递', 'longbanwuliu'),
(9, '传喜物流', 'chuanxiwuliu'),
(10, '安迅物流', 'anxl'),
(11, '安能物流', 'annengwuliu'),
(12, '大田物流', 'datianwuliu'),
(13, '飞康达', 'feikangda'),
(14, 'GLS', 'gls'),
(15, '共速达', 'gongsuda'),
(16, '恒路物流', 'hengluwuliu'),
(17, '华夏龙', 'huaxialongwuliu'),
(18, '昊盛物流', 'haoshengwuliu'),
(19, '户通物流', 'hutongwuliu'),
(20, '佳吉物流', 'jiajiwuliu'),
(21, '急先达', 'jixianda'),
(22, '金大物流', 'jindawuliu'),
(23, '捷特物流', 'jietekuaidi'),
(24, '冀运亚通', 'jiyunyatong'),
(25, '康力物流', 'kangliwuliu'),
(26, '跨越物流', 'kuayue'),
(27, '联昊通', 'lianhaowuliu'),
(28, '明亮物流', 'mingliangwuliu'),
(29, '陪行物流', 'peixingwuliu'),
(30, '南京晟邦', 'nanjingshengbang'),
(31, '日昱物流', 'riyuwuliu'),
(32, '盛辉物流', 'shenghuiwuliu'),
(33, '上大物流', 'shangda'),
(34, '申通E物流', 'shentong'),
(35, '圣安物流', 'shenganwuliu'),
(36, '穗佳物流', 'suijiawuliu'),
(37, '万家物流', 'wanjiawuliu'),
(38, '新邦物流', 'xinbangwuliu'),
(39, '信丰物流', 'xinfengwuliu'),
(40, '邮政国内', 'youzhengguonei'),
(41, '邮政国际', 'youzhengguoji'),
(42, '越丰物流', 'yuefengwuliu'),
(43, '原飞航', 'yuanfeihangwuliu'),
(44, '宇鑫物流', 'yuxinwuliu'),
(45, '煜嘉物流', 'yujiawuliu'),
(46, '中铁物流', 'ztky'),
(47, '中邮物流', 'zhongyouwuliu');
