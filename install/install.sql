-- --------------------------------------------------------
-- 主机:                           127.0.0.1
-- Server version:               5.5.53 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL 版本:                  9.5.0.5380
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table thinksaas-dev.ts_anti_email
DROP TABLE IF EXISTS `ts_anti_email`;
CREATE TABLE IF NOT EXISTS `ts_anti_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `email` varchar(64) NOT NULL DEFAULT '' COMMENT 'Email',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='过滤Email';

-- Dumping data for table thinksaas-dev.ts_anti_email: 0 rows
DELETE FROM `ts_anti_email`;
/*!40000 ALTER TABLE `ts_anti_email` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_anti_email` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_anti_ip
DROP TABLE IF EXISTS `ts_anti_ip`;
CREATE TABLE IF NOT EXISTS `ts_anti_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `ip` varchar(64) NOT NULL DEFAULT '' COMMENT 'IP地址',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='垃圾IP';

-- Dumping data for table thinksaas-dev.ts_anti_ip: 0 rows
DELETE FROM `ts_anti_ip`;
/*!40000 ALTER TABLE `ts_anti_ip` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_anti_ip` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_anti_report
DROP TABLE IF EXISTS `ts_anti_report`;
CREATE TABLE IF NOT EXISTS `ts_anti_report` (
  `reportid` int(11) NOT NULL AUTO_INCREMENT COMMENT '举报ID',
  `url` varchar(128) NOT NULL DEFAULT '' COMMENT '举报链接',
  `content` varchar(512) NOT NULL DEFAULT '' COMMENT '举报内容',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '时间',
  PRIMARY KEY (`reportid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='内容举报';

-- Dumping data for table thinksaas-dev.ts_anti_report: 0 rows
DELETE FROM `ts_anti_report`;
/*!40000 ALTER TABLE `ts_anti_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_anti_report` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_anti_user
DROP TABLE IF EXISTS `ts_anti_user`;
CREATE TABLE IF NOT EXISTS `ts_anti_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='过滤用户';

-- Dumping data for table thinksaas-dev.ts_anti_user: 0 rows
DELETE FROM `ts_anti_user`;
/*!40000 ALTER TABLE `ts_anti_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_anti_user` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_anti_word
DROP TABLE IF EXISTS `ts_anti_word`;
CREATE TABLE IF NOT EXISTS `ts_anti_word` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(64) NOT NULL DEFAULT '' COMMENT '敏感词',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=535 DEFAULT CHARSET=utf8mb4 COMMENT='敏感词';

-- Dumping data for table thinksaas-dev.ts_anti_word: 534 rows
DELETE FROM `ts_anti_word`;
/*!40000 ALTER TABLE `ts_anti_word` DISABLE KEYS */;
INSERT INTO `ts_anti_word` (`id`, `word`, `addtime`) VALUES
	(1, '国民党', '2012-08-28 15:23:10'),
	(2, '邓小平', '2012-08-28 15:23:10'),
	(3, '江泽民', '2012-08-28 15:23:10'),
	(4, '胡锦涛', '2012-08-28 15:23:10'),
	(5, '共产党', '2012-08-28 15:23:10'),
	(6, '毛主席', '2012-08-28 15:23:10'),
	(7, '毛泽东', '2012-08-28 15:23:10'),
	(8, '中共', '2012-08-28 15:23:10'),
	(9, '中国共产党', '2012-08-28 15:23:10'),
	(10, '枪', '2012-08-28 15:23:10'),
	(11, '弹药', '2012-08-28 15:23:10'),
	(12, '枪支', '2012-08-28 15:23:10'),
	(13, '氣槍', '2012-08-28 15:23:10'),
	(14, '猎槍', '2012-08-28 15:23:10'),
	(15, '来福', '2012-08-28 15:23:10'),
	(16, '雷鸣登', '2012-08-28 15:23:10'),
	(17, '五连发', '2012-08-28 15:23:10'),
	(18, '平式双管', '2012-08-28 15:23:10'),
	(19, '立式双管', '2012-08-28 15:23:10'),
	(20, '麻醉', '2012-08-28 15:23:10'),
	(21, '军用', '2012-08-28 15:23:10'),
	(22, '进口', '2012-08-28 15:23:10'),
	(23, '录入员', '2012-08-28 15:23:10'),
	(24, '招聘兼职', '2012-08-28 15:23:10'),
	(25, '1332566258', '2012-08-28 15:23:10'),
	(26, '直流电阻测试仪', '2012-08-28 15:23:10'),
	(27, '继电保护测试仪', '2012-08-28 15:23:10'),
	(28, '串联谐振', '2012-08-28 15:23:10'),
	(29, '分压器', '2012-08-28 15:23:10'),
	(30, 'www.hbyhdl.com', '2012-08-28 15:23:10'),
	(31, 'www.bd-seo.net', '2012-08-28 15:23:10'),
	(32, '武汉网站优化', '2012-08-28 15:23:10'),
	(33, '武汉网络推广', '2012-08-28 15:23:10'),
	(34, '武汉网络营销', '2012-08-28 15:23:10'),
	(35, '武汉SEO', '2012-08-28 15:23:10'),
	(36, '大脚骨矫正器', '2012-08-28 15:23:10'),
	(37, '379056061', '2012-08-28 15:23:10'),
	(38, '拇外翻', '2012-08-28 15:23:10'),
	(39, '小姐', '2012-08-28 15:23:10'),
	(40, '习近平', '2012-08-28 15:23:10'),
	(41, '王立军', '2012-08-28 15:23:10'),
	(42, '两会', '2012-08-28 15:23:10'),
	(43, '薄熙来', '2012-08-28 15:23:10'),
	(44, '谷开来', '2012-08-28 15:23:10'),
	(45, '唱红打黑', '2012-08-28 15:23:10'),
	(46, '听党指挥', '2012-08-28 15:23:10'),
	(47, '薄一波', '2012-08-28 15:23:10'),
	(48, '李长春', '2012-08-28 15:23:10'),
	(49, '周永康', '2012-08-28 15:23:10'),
	(50, '政府', '2012-08-28 15:23:10'),
	(51, 'zhenfu', '2012-08-28 15:23:10'),
	(52, '傻逼', '2012-08-28 15:23:10'),
	(53, 'zhengfu', '2012-08-28 15:23:10'),
	(54, '他杀', '2012-08-28 15:23:10'),
	(55, '枪杀', '2012-08-28 15:23:10'),
	(56, '谋杀', '2012-08-28 15:23:10'),
	(57, '起义', '2012-08-28 15:23:10'),
	(58, '就义', '2012-08-28 15:23:10'),
	(59, '法轮功', '2012-08-28 15:23:10'),
	(60, '邪教', '2012-08-28 15:23:10'),
	(61, 'GCD', '2012-08-28 15:23:10'),
	(62, 'hege123', '2012-08-28 15:23:10'),
	(63, '菲律宾', '2012-08-28 15:23:10'),
	(64, '薄熙莱', '2012-08-28 15:23:10'),
	(65, '小姐上门', '2012-08-28 15:23:10'),
	(66, '一夜情', '2012-08-28 15:23:10'),
	(67, '性爱', '2012-08-28 15:23:10'),
	(68, '性息', '2012-08-28 15:23:10'),
	(69, '武汉婚纱摄影', '2012-08-28 15:23:10'),
	(70, '武汉婚纱照', '2012-08-28 15:23:10'),
	(71, '武汉艺术照', '2012-08-28 15:23:10'),
	(72, '武汉婚纱摄影工作室', '2012-08-28 15:23:10'),
	(73, 'www.yilongphoto.com', '2012-08-28 15:23:10'),
	(74, 'www.windyx.com', '2012-08-28 15:23:10'),
	(75, 'www.ruile.net', '2012-08-28 15:23:10'),
	(76, 'www.wo-niu.com.cn', '2012-08-28 15:23:10'),
	(77, 'www.wlzb518.com', '2012-08-28 15:23:10'),
	(78, 'www.wufangzhai-zongzi.com', '2012-08-28 15:23:10'),
	(79, 'www.hege123.com', '2012-08-28 15:23:10'),
	(80, '按摩', '2012-08-28 15:23:10'),
	(81, '按摩服务', '2012-08-28 15:23:10'),
	(82, 'cwsurf.de', '2012-08-28 15:23:10'),
	(83, '出台', '2012-08-28 15:23:10'),
	(84, '包夜', '2012-08-28 15:23:10'),
	(85, 'www.maizongzi.com', '2012-08-28 15:23:10'),
	(86, '代刷', '2012-08-28 15:23:10'),
	(87, '微笑网络', '2012-08-28 15:23:10'),
	(88, '刷信誉', '2012-08-28 15:23:10'),
	(89, '53530.cn', '2012-08-28 15:23:10'),
	(90, 'waimaodao.com', '2012-08-28 15:23:10'),
	(91, '小 妹', '2012-08-28 15:23:10'),
	(92, '上 门 服 务', '2012-08-28 15:23:10'),
	(93, '小 妹 上 门', '2012-08-28 15:23:10'),
	(94, '王哥', '2012-08-28 15:23:10'),
	(95, '花姐', '2012-08-28 15:23:10'),
	(96, '学妹', '2012-08-28 15:23:10'),
	(97, '姓ˊ感ˊ少ˊ妇', '2012-08-28 15:23:10'),
	(98, '丰ˊ韵ˊ熟ˊ妇', '2012-08-28 15:23:10'),
	(99, '丽人岛休闲会所', '2012-08-28 15:23:10'),
	(100, '越ˊ南ˊ妹', '2012-08-28 15:23:10'),
	(101, '77057', '2012-08-28 15:23:10'),
	(102, '饥ˊ渴ˊ熟ˊ女', '2012-08-28 15:23:10'),
	(103, '饥渴熟女', '2012-08-28 15:23:10'),
	(104, '性感人妻', '2012-08-28 15:23:10'),
	(105, '姓感少妇', '2012-08-28 15:23:10'),
	(106, '51gouku.com', '2012-08-28 15:23:10'),
	(107, '51够酷', '2012-08-28 15:23:10'),
	(108, '585.cc', '2012-08-28 15:23:10'),
	(109, '腋臭', '2012-08-28 15:23:10'),
	(110, '狐臭', '2012-08-28 15:23:10'),
	(111, '兼职学生妹', '2012-08-28 15:23:10'),
	(112, '湘湘', '2012-08-28 15:23:10'),
	(113, '小唐', '2012-08-28 15:23:10'),
	(114, '学生妹', '2012-08-28 15:23:10'),
	(115, 'wufangzhai-zongzi.com', '2012-08-28 15:23:10'),
	(116, '冰毒', '2012-08-28 15:23:10'),
	(117, '海洛因', '2012-08-28 15:23:10'),
	(118, '毒品', '2012-08-28 15:23:10'),
	(119, '吸毒', '2012-08-28 15:23:10'),
	(120, '66dao.com', '2012-08-28 15:23:10'),
	(121, '办证', '2012-08-28 15:23:10'),
	(122, '赵小姐', '2012-08-28 15:23:10'),
	(123, 'douyapai.com', '2012-08-28 15:23:10'),
	(124, 'ntxz.cc', '2012-08-28 15:23:10'),
	(125, '豆芽派', '2012-08-28 15:23:10'),
	(126, 'tdjyedu', '2012-08-28 15:23:10'),
	(127, '自考招生', '2012-08-28 15:23:10'),
	(128, 'sinoest', '2012-08-28 15:23:10'),
	(129, '身份證买卖', '2012-08-28 15:23:10'),
	(130, '身份证买卖', '2012-08-28 15:23:10'),
	(131, '895316992', '2012-08-28 15:23:10'),
	(132, '爱游中国', '2012-08-28 15:23:10'),
	(133, '上門服务', '2012-08-28 15:23:10'),
	(134, '客人隐私', '2012-08-28 15:23:10'),
	(135, 'suoniao.com', '2012-08-28 15:23:10'),
	(136, '男科医院', '2012-08-28 15:23:10'),
	(137, '男性医院', '2012-08-28 15:23:10'),
	(138, '包皮', '2012-08-28 15:23:10'),
	(139, '包茎', '2012-08-28 15:23:10'),
	(140, '男科', '2012-08-28 15:23:10'),
	(141, '做爱', '2012-08-28 15:23:10'),
	(142, '小妹上门', '2012-08-28 15:23:10'),
	(143, '小妹服务', '2012-08-28 15:23:10'),
	(144, 'sm', '2012-08-28 15:23:10'),
	(145, 'sm女王', '2012-08-28 15:23:10'),
	(146, '成熟少妇', '2012-08-28 15:23:10'),
	(147, '包吹', '2012-08-28 15:23:10'),
	(148, '极品校花', '2012-08-28 15:23:10'),
	(149, 'sinoest.com', '2012-08-28 15:23:10'),
	(150, '尖锐湿疣', '2012-08-28 15:23:10'),
	(151, '耳鼻喉医院', '2012-08-28 15:23:10'),
	(152, '过敏性鼻炎', '2012-08-28 15:23:10'),
	(153, 'ibuonline.com', '2012-08-28 15:23:10'),
	(154, '福彩', '2012-08-28 15:23:10'),
	(155, '福彩3d', '2012-08-28 15:23:10'),
	(156, 'totutu.com', '2012-08-28 15:23:10'),
	(157, '去黑头', '2012-08-28 15:23:10'),
	(158, '东方软峰', '2012-08-28 15:23:10'),
	(159, 'yileee.com', '2012-08-28 15:23:10'),
	(160, '新特药', '2012-08-28 15:23:10'),
	(161, 'fgt120.com', '2012-08-28 15:23:10'),
	(162, '99spcar.com', '2012-08-28 15:23:10'),
	(163, 'meiti520.com', '2012-08-28 15:23:10'),
	(164, 'bbswuhan.com', '2012-08-28 15:23:10'),
	(165, '18611314446', '2012-11-08 22:51:12'),
	(166, '丰韵熟妇', '2012-11-08 22:51:12'),
	(167, '越南妹', '2012-11-08 22:51:12'),
	(168, 'maizongzi.com', '2012-11-08 22:51:12'),
	(169, '上门服务', '2012-11-08 22:51:12'),
	(170, '小妹', '2012-11-08 22:51:12'),
	(171, 'windyx.com', '2012-11-08 22:51:12'),
	(172, 'wlzb518.com', '2012-11-08 22:51:12'),
	(173, 'wo-niu.com.cn', '2012-11-08 22:51:12'),
	(174, 'ruile.net', '2012-11-08 22:51:12'),
	(175, 'bd-seo.net', '2012-11-08 22:51:12'),
	(176, 'hbyhdl.com', '2012-11-08 22:51:12'),
	(177, 'yilongphoto.com', '2012-11-08 22:51:12'),
	(178, 'hege123.com', '2012-11-08 22:51:12'),
	(179, '發票', '2012-11-08 22:51:13'),
	(180, '开票电', '2012-11-08 22:51:13'),
	(181, '开票', '2012-11-08 22:51:13'),
	(182, '发票', '2012-11-08 22:51:13'),
	(183, '代开发票', '2012-11-08 22:51:13'),
	(184, '私募', '2012-11-08 22:51:13'),
	(185, '走私车', '2012-11-08 22:51:13'),
	(186, '资本运作', '2012-11-08 22:51:13'),
	(187, '真人视频', '2012-11-08 22:51:13'),
	(188, '造价通', '2012-11-08 22:51:13'),
	(189, '移民网', '2012-11-08 22:51:13'),
	(190, '药商', '2012-11-08 22:51:13'),
	(191, '亚布力', '2012-11-08 22:51:13'),
	(192, '雅思', '2012-11-08 22:51:13'),
	(193, '新皇宝', '2012-11-08 22:51:13'),
	(194, '校花聊天室', '2012-11-08 22:51:13'),
	(195, '消费投资合法', '2012-11-08 22:51:13'),
	(196, '西安卖肾', '2012-11-08 22:51:13'),
	(197, '同城女', '2012-11-08 22:51:13'),
	(198, '丝袜交友', '2012-11-08 22:51:13'),
	(199, '兼职服务', '2012-11-08 22:51:13'),
	(200, '草榴社区', '2012-11-08 22:51:13'),
	(201, '搬家公司', '2012-11-08 22:51:13'),
	(202, '代開', '2012-11-08 22:51:13'),
	(203, '代开', '2012-11-08 22:51:13'),
	(204, '醱票', '2012-11-08 22:51:13'),
	(205, '開瞟', '2012-11-08 22:51:13'),
	(206, '瞟据', '2012-11-08 22:51:13'),
	(207, '瞟务', '2012-11-08 22:51:13'),
	(208, '酒店住宿', '2012-11-08 22:51:13'),
	(209, '13826544598', '2012-11-08 22:51:13'),
	(210, '2645989872', '2012-11-08 22:51:13'),
	(211, '18312006833', '2012-11-08 22:51:13'),
	(212, '费发', '2012-11-08 22:51:13'),
	(213, '314721888', '2012-11-08 22:51:13'),
	(214, '办理假证件', '2012-11-08 22:51:13'),
	(215, '394057341', '2012-11-08 22:51:13'),
	(216, '费發', '2012-11-08 22:51:13'),
	(217, '13533391062', '2012-11-08 22:51:13'),
	(218, '13544261868', '2012-11-08 22:51:13'),
	(219, '13828442144', '2012-11-08 22:51:13'),
	(220, '13728999976', '2012-11-08 22:51:13'),
	(221, '13662622538', '2012-11-08 22:51:13'),
	(222, '897839088', '2012-11-08 22:51:13'),
	(223, 'vpswolf.com', '2012-11-08 22:51:13'),
	(224, 'vanshen.com', '2012-11-08 22:51:13'),
	(225, 'yapai.cc', '2012-11-08 22:51:13'),
	(226, 'daqiaogw.com', '2012-11-08 22:51:13'),
	(227, 'hfkszdm.com', '2012-11-08 22:51:13'),
	(228, 'jinqiaohc.com', '2012-11-08 22:51:13'),
	(229, '0553rl.com', '2012-11-08 22:51:13'),
	(230, 'ln580.cn', '2012-11-08 22:51:13'),
	(231, 'qifanweb.com', '2012-11-08 22:51:13'),
	(232, 'qifanseo.com', '2012-11-08 22:51:13'),
	(233, 'qifanit.com', '2012-11-08 22:51:13'),
	(234, '028zfyy.com', '2012-11-08 22:51:13'),
	(235, 'aitecentury.com', '2012-11-08 22:51:13'),
	(236, 'aite55.com', '2012-11-08 22:51:13'),
	(237, 'shentongkang.com', '2012-11-08 22:51:13'),
	(238, 'dss.so', '2012-11-08 22:51:13'),
	(239, '3ja.net', '2012-11-08 22:51:13'),
	(240, 'sin55.com', '2012-11-08 22:51:13'),
	(241, '2008ns.com', '2012-11-08 22:51:13'),
	(242, '203529769', '2012-11-08 22:51:13'),
	(243, 'binhaijincheng.com', '2012-11-08 22:51:13'),
	(244, '美女服务', '2012-11-08 22:51:13'),
	(245, '18611325651', '2012-11-08 22:51:13'),
	(246, 'caihua.cc', '2012-11-08 22:51:13'),
	(247, '51mm.com.cn', '2012-11-08 22:51:13'),
	(248, 'tbwtmall.net', '2012-11-08 22:51:13'),
	(249, 'lubaolin.com', '2012-11-08 22:51:13'),
	(250, '糖尿病治疗仪', '2012-11-08 22:51:13'),
	(251, 'tangniaobingok.com', '2012-11-08 22:51:13'),
	(252, '糖尿病', '2012-11-08 22:51:13'),
	(253, 'chtip.org', '2012-11-08 22:51:13'),
	(254, '56156.com', '2012-11-08 22:51:13'),
	(255, '07uuu.com', '2012-11-08 22:51:13'),
	(256, 'haoyouren.com', '2012-11-08 22:51:13'),
	(257, '便秘', '2012-11-08 22:51:13'),
	(258, 'haoyouren', '2012-11-08 22:51:13'),
	(259, 'xxcun.com', '2012-11-08 22:51:13'),
	(260, 'iisp.com', '2012-11-08 22:51:13'),
	(261, 'gmwhy.com', '2012-11-08 22:51:13'),
	(262, 'feelyz.com', '2012-11-08 22:51:13'),
	(263, '369in.com', '2012-11-08 22:51:13'),
	(264, 'cdtarena.com', '2012-11-08 22:51:13'),
	(265, '肝硬化', '2012-11-08 22:51:13'),
	(266, 'youbian.com', '2012-11-08 22:51:13'),
	(267, '162net.com', '2012-11-08 22:51:13'),
	(268, 'comnetcnn.com', '2012-11-08 22:51:13'),
	(269, '2233.cn', '2012-11-08 22:51:13'),
	(270, '鸡巴', '2012-11-08 22:51:13'),
	(271, '119tx.com', '2012-11-08 22:51:13'),
	(272, '0377521.com', '2012-11-08 22:51:13'),
	(273, '028zuanji.com', '2013-07-27 14:54:52'),
	(274, 'dzwan.net', '2013-07-27 14:54:52'),
	(275, 'dodomo.net', '2013-07-27 14:54:52'),
	(276, 'sina.com', '2013-07-27 14:54:52'),
	(277, 'fobshanghai.com', '2013-07-27 14:54:52'),
	(278, '芬恩', '2013-07-27 14:54:52'),
	(279, '2659477099', '2013-07-27 14:54:52'),
	(280, '58692026', '2013-07-27 14:54:52'),
	(281, '65111117', '2013-07-27 14:54:52'),
	(282, '英文SEO', '2013-07-27 14:54:52'),
	(283, '仿牌SEO', '2013-07-27 14:54:52'),
	(284, '外贸SEO', '2013-07-27 14:54:52'),
	(285, '1550957342', '2013-07-27 14:54:52'),
	(286, 'pingan.com', '2013-07-27 14:54:52'),
	(287, '痔疮', '2013-07-27 14:54:52'),
	(288, '86889299', '2013-07-27 14:54:52'),
	(289, '800002356', '2013-07-27 14:54:52'),
	(290, 'cqddgc.cn', '2013-07-27 14:54:52'),
	(291, '股票', '2013-07-27 14:54:52'),
	(292, 'nyimei.com', '2013-07-27 14:54:52'),
	(293, '天衣坊', '2013-07-27 14:54:52'),
	(294, '宏天景秀', '2013-07-27 14:54:52'),
	(295, 'sugon.com', '2013-07-27 14:54:52'),
	(296, '微博008', '2013-07-27 14:54:52'),
	(297, 'qqbct.com', '2013-07-27 14:54:52'),
	(298, 'qqsuncity.com', '2013-07-27 14:54:52'),
	(299, '99txzq.com', '2013-07-27 14:54:52'),
	(300, '88txzq.com', '2013-07-27 14:54:52'),
	(301, 'sdebh.cn', '2013-07-27 14:54:52'),
	(302, '9501317248463', '2013-07-27 14:54:52'),
	(303, '248463', '2013-07-27 14:54:52'),
	(304, '飞机票', '2013-07-27 14:54:52'),
	(305, '网银', '2013-07-27 14:54:52'),
	(306, '火车票', '2013-07-27 14:54:52'),
	(307, '无卡存款', '2013-07-27 14:54:52'),
	(308, '66667959', '2013-07-27 14:54:52'),
	(309, '订票', '2013-07-27 14:54:52'),
	(310, '火车站', '2013-07-27 14:54:52'),
	(311, '57071215', '2013-07-27 14:54:52'),
	(312, '889584017', '2013-07-27 14:54:52'),
	(313, '获奖查询', '2013-07-27 14:54:52'),
	(314, '穫獎查詢', '2013-07-27 14:54:52'),
	(315, '82425', '2013-07-27 14:54:52'),
	(316, '5782', '2013-07-27 14:54:52'),
	(317, '中獎', '2013-07-27 14:54:52'),
	(318, '熱線', '2013-07-27 14:54:52'),
	(319, '1931033', '2013-07-27 14:54:52'),
	(320, '代售点', '2013-07-27 14:54:52'),
	(321, 'w1a2.icoc.cc', '2013-07-27 14:54:52'),
	(322, '1317241334', '2013-07-27 14:54:52'),
	(323, '车票改签', '2013-07-27 14:54:52'),
	(324, '嘿咻', '2013-07-27 14:54:52'),
	(325, '加盟', '2013-07-27 14:54:52'),
	(326, '湿疹', '2013-07-27 14:54:52'),
	(327, '塑胶', '2013-07-27 14:54:52'),
	(328, '1817001212', '2013-07-27 14:54:52'),
	(329, '4000318885', '2013-07-27 14:54:52'),
	(330, '800007699', '2013-07-27 14:54:52'),
	(331, '4008521119', '2013-07-27 14:54:52'),
	(332, '花月婷', '2013-07-27 14:54:52'),
	(333, 'xpjin.com', '2013-07-27 14:54:52'),
	(334, '新葡京娱乐城', '2013-07-27 14:54:52'),
	(335, 'eshibo68.com', '2013-07-27 14:54:52'),
	(336, 'tt5201314.com', '2013-07-27 14:54:52'),
	(337, 'TT娱乐城', '2013-07-27 14:54:52'),
	(338, '娱乐城', '2013-07-27 14:54:52'),
	(339, 'qinzi5.com', '2013-07-27 14:54:52'),
	(340, '21202', '2013-07-27 14:54:52'),
	(341, '火車票', '2013-07-27 14:54:52'),
	(342, '089', '2013-07-27 14:54:52'),
	(343, '8369', '2013-07-27 14:54:52'),
	(344, '退票', '2013-07-27 14:54:52'),
	(345, '改簽', '2013-07-27 14:54:52'),
	(346, '預訂', '2013-07-27 14:54:52'),
	(347, 'ruijintc.com', '2013-07-27 14:54:52'),
	(348, 'ruijintc.net', '2013-07-27 14:54:52'),
	(349, '草榴', '2013-07-27 14:54:52'),
	(350, 'caoliu', '2013-07-27 14:54:52'),
	(351, '5177game.com', '2013-07-27 14:54:52'),
	(352, 'cao', '2013-07-27 14:54:52'),
	(353, 'wgb320330.com', '2013-07-27 14:54:52'),
	(354, '公關', '2013-07-27 14:54:52'),
	(355, '公关', '2013-07-27 14:54:52'),
	(356, '夜总会', '2013-07-27 14:54:52'),
	(357, '兼职', '2013-07-27 14:54:52'),
	(358, '同性恋', '2013-07-27 14:54:52'),
	(359, '丝足', '2013-07-27 14:54:52'),
	(360, '同志', '2013-07-27 14:54:52'),
	(361, '女王', '2013-07-27 14:54:52'),
	(362, '鸭子', '2013-07-27 14:54:52'),
	(363, '调教', '2013-07-27 14:54:52'),
	(364, 'KTV', '2013-07-27 14:54:52'),
	(365, '夜场', '2013-07-27 14:54:52'),
	(366, '娱乐场', '2013-07-27 14:54:52'),
	(367, '陪护', '2013-07-27 14:54:52'),
	(368, '情感陪护', '2013-07-27 14:54:52'),
	(369, '公主', '2013-07-27 14:54:52'),
	(370, 'LES', '2013-07-27 14:54:52'),
	(371, 'GAY', '2013-07-27 14:54:52'),
	(372, '兼職', '2013-07-27 14:54:52'),
	(373, '18611102232', '2013-07-27 14:54:52'),
	(374, '男妓', '2013-07-27 14:54:52'),
	(375, '妓男', '2013-07-27 14:54:52'),
	(376, '女妓', '2013-07-27 14:54:52'),
	(377, 'cs12388.com', '2013-07-27 14:54:52'),
	(378, 'liketuan.com', '2013-07-27 14:54:52'),
	(379, 'xiunvfang.com', '2013-07-27 14:54:52'),
	(380, 'tmall.com', '2013-07-27 14:54:52'),
	(381, 'rekuai.com', '2013-07-27 14:54:52'),
	(382, 'gyouz.com', '2013-07-27 14:54:52'),
	(383, 'u95.cc', '2013-07-27 14:54:52'),
	(384, 'ikphp.com', '2013-07-27 14:54:52'),
	(385, '12ik.com', '2013-07-27 14:54:52'),
	(386, '7lo.cn', '2013-07-27 14:54:52'),
	(387, 'hufuin.com', '2013-07-27 14:54:52'),
	(388, 'fa66.com', '2013-07-27 14:54:52'),
	(389, 'itcpa.cn', '2013-07-27 14:54:52'),
	(390, '72jz.com', '2013-07-27 14:54:52'),
	(391, '网赚', '2013-07-27 14:54:52'),
	(392, 'ikphp', '2013-07-27 14:54:52'),
	(393, '12ik', '2013-07-27 14:54:52'),
	(394, 'weadge.com', '2013-07-27 14:54:52'),
	(395, 'mfkds.com', '2013-07-27 14:54:52'),
	(396, 'svs123.com', '2013-07-27 14:54:52'),
	(397, 'dlnmd.com', '2013-07-27 14:54:52'),
	(398, 'nicenic.com', '2013-07-27 14:54:52'),
	(399, '13311372110', '2013-07-27 14:54:52'),
	(400, '58464602', '2013-07-27 14:54:52'),
	(401, 'bf3.cn', '2014-04-16 23:19:33'),
	(402, '你妹啊', '2014-04-16 23:19:33'),
	(403, '33md.co', '2014-04-16 23:19:33'),
	(404, '梅毒', '2014-04-16 23:19:33'),
	(405, '你妈', '2014-04-16 23:19:33'),
	(406, 'hywww.net', '2014-04-16 23:19:33'),
	(407, '2015230140', '2014-04-16 23:19:33'),
	(408, '淋病', '2014-04-16 23:19:33'),
	(409, '非淋病', '2014-04-16 23:19:33'),
	(410, 'damazha.com', '2014-04-16 23:19:33'),
	(411, 'mitang.pw', '2014-04-16 23:19:33'),
	(412, '六合彩', '2014-04-16 23:19:33'),
	(413, '赛马', '2014-04-16 23:19:33'),
	(414, '三陪', '2014-04-16 23:19:33'),
	(415, '51shuaige.com', '2014-04-16 23:19:33'),
	(416, '369tong.com', '2014-04-16 23:19:33'),
	(417, 'holde.cn', '2014-04-16 23:19:33'),
	(418, '18xi.com', '2014-04-16 23:19:33'),
	(419, '信息代发', '2014-04-16 23:19:33'),
	(420, '推广软件', '2014-04-16 23:19:33'),
	(421, '营销软件', '2014-04-16 23:19:33'),
	(422, '网络推广', '2014-04-16 23:19:33'),
	(423, '营销人', '2014-04-16 23:19:33'),
	(424, '91renren.com', '2014-04-16 23:19:33'),
	(425, 'kukud.net', '2014-04-16 23:19:33'),
	(426, 'chuntu.cc', '2014-04-16 23:19:33'),
	(427, 'jinhusns.com', '2014-04-16 23:19:33'),
	(428, '鼻炎', '2014-04-16 23:19:33'),
	(429, '医院', '2014-04-16 23:19:33'),
	(430, '153075777', '2014-04-16 23:19:33'),
	(431, 'hqbsns.com', '2014-04-16 23:19:33'),
	(432, 'jjyulecheng77g.com', '2014-04-16 23:19:33'),
	(433, 'senlang.net', '2014-04-16 23:19:33'),
	(434, 'xiyingmenyulecheng8a.com', '2014-04-16 23:19:33'),
	(435, 'osforce.cn', '2014-04-16 23:19:33'),
	(436, '51neixun.com', '2014-04-16 23:19:33'),
	(437, '你妈逼', '2014-04-16 23:19:33'),
	(438, '杀人', '2014-04-16 23:19:33'),
	(439, '操你妈', '2014-04-16 23:19:33'),
	(440, '草泥马', '2014-04-16 23:19:33'),
	(441, '税票', '2014-04-16 23:19:33'),
	(442, 'wanlidq.com', '2014-04-16 23:19:33'),
	(443, '醫院', '2014-04-16 23:19:33'),
	(444, 'mrdodo.net', '2014-04-16 23:19:33'),
	(445, '188123581', '2014-04-16 23:19:33'),
	(446, 'zhengma.com', '2014-04-16 23:19:33'),
	(447, '4008166005', '2015-04-26 17:18:08'),
	(448, '400', '2015-04-26 17:18:08'),
	(449, '800', '2015-04-26 17:18:08'),
	(450, '化妆', '2015-04-26 17:18:08'),
	(451, '陈派', '2015-04-26 17:18:08'),
	(452, '税务', '2015-04-26 17:18:08'),
	(453, '整形', '2015-04-26 17:18:08'),
	(454, '美容', '2015-04-26 17:18:08'),
	(455, '隆胸', '2015-04-26 17:18:08'),
	(456, 'jinyuanbao.cn', '2015-04-26 17:18:08'),
	(457, 'xkqmj.org', '2015-04-26 17:18:08'),
	(458, 'baishiheyuan.com', '2015-04-26 17:18:08'),
	(459, '057160989861', '2015-04-26 17:18:08'),
	(460, 'hiici.com', '2015-04-26 17:18:08'),
	(461, '518202.com', '2015-04-26 17:18:08'),
	(462, '微店', '2015-04-26 17:18:08'),
	(463, '60989861', '2015-04-26 17:18:08'),
	(464, '1433607382', '2015-04-26 17:18:08'),
	(465, '18921182443', '2015-04-26 17:18:08'),
	(466, '82835166', '2015-04-26 17:18:08'),
	(467, '官方推荐', '2015-04-26 17:18:08'),
	(468, '百度认证', '2015-04-26 17:18:08'),
	(469, 'sh419x.net', '2015-04-26 17:18:08'),
	(470, 'hhy021.com', '2015-04-26 17:18:08'),
	(471, 'ehuanka.com', '2015-04-26 17:18:08'),
	(472, '柘荣太子参', '2015-04-26 17:18:08'),
	(473, 'buluocc.com', '2015-04-26 17:18:08'),
	(474, 'buluocc', '2015-04-26 17:18:08'),
	(475, 'szprovence.com', '2015-04-26 17:18:08'),
	(476, '上海龙凤', '2015-04-26 17:18:08'),
	(477, 'shlf9.net', '2015-04-26 17:18:08'),
	(478, 'shlf9.com', '2015-04-26 17:18:08'),
	(479, 'shlf99.net', '2015-04-26 17:18:08'),
	(480, 'shlf99.com', '2015-04-26 17:18:08'),
	(481, 'shlf999.net', '2015-04-26 17:18:08'),
	(482, 'shlf999.com', '2015-04-26 17:18:08'),
	(483, 'aishiso.com', '2015-04-26 17:18:08'),
	(484, 'qhf021.com', '2015-04-26 17:18:08'),
	(485, 'cfg021.com', '2015-04-26 17:18:08'),
	(486, 'uoko.com', '2015-04-26 17:18:08'),
	(487, 'guizubbx.com', '2015-04-26 17:18:08'),
	(488, 'esosn.com', '2015-04-26 17:18:08'),
	(489, 'win7zhijia.cn', '2015-04-26 17:18:08'),
	(490, 'xtcheng.net', '2015-04-26 17:18:08'),
	(491, '867590759', '2015-04-26 17:18:08'),
	(492, '888xitong.com', '2015-04-26 17:18:08'),
	(493, '1378206455', '2015-04-26 17:18:08'),
	(494, '宜人贷', '2015-04-26 17:18:08'),
	(495, 'win7qjb.com', '2015-04-26 17:18:08'),
	(496, 'xitongcheng', '2015-04-26 17:18:08'),
	(497, 'onerare.com', '2015-04-26 17:18:08'),
	(498, 'xitong1.com', '2015-04-26 17:18:08'),
	(499, 'ghostxpsp3.net', '2015-04-26 17:18:08'),
	(500, 'windows114.com', '2015-04-26 17:18:08'),
	(501, 'djie.net', '2015-04-26 17:18:08'),
	(502, '999ghost.com', '2015-04-26 17:18:08'),
	(503, 'xitongcity.com', '2015-04-26 17:18:08'),
	(504, '51rgb.com', '2015-04-26 17:18:08'),
	(505, 'mingtaov.com', '2015-04-26 17:18:08'),
	(506, '228224.com', '2015-04-26 17:18:08'),
	(507, 'hb7526.com', '2015-04-26 17:18:08'),
	(508, 'jx8091.com', '2015-04-26 17:18:08'),
	(509, 'drf8953.com', '2015-04-26 17:18:08'),
	(510, 'xpjgj9186.com', '2015-04-26 17:18:08'),
	(511, 'hjjb9981.com', '2015-04-26 17:18:08'),
	(512, 'dwj5266.com', '2015-04-26 17:18:08'),
	(513, 'xq81365.com', '2015-04-26 17:18:08'),
	(514, 'nc2787.com', '2015-04-26 17:18:08'),
	(515, 'wfgj7656.com', '2015-04-26 17:18:08'),
	(516, 'seowhy.com', '2015-04-26 17:18:08'),
	(517, 'ceo2351.com', '2015-04-26 17:18:08'),
	(518, 'fc5517.com', '2015-04-26 17:18:08'),
	(519, 'bg6261.com', '2015-04-26 17:18:08'),
	(520, 'fbgj8620.com', '2015-04-26 17:18:08'),
	(521, 'ylgj2523.com', '2015-04-26 17:18:08'),
	(522, 'zibenzaixian.com', '2015-04-26 17:18:08'),
	(523, 'chetips.com', '2015-04-26 17:18:08'),
	(524, '51zhaoji.com', '2015-04-26 17:18:08'),
	(525, '白癜风', '2015-04-26 17:18:08'),
	(526, 'kanshijian.com', '2015-04-26 17:18:08'),
	(527, 'wudan100.com', '2015-04-26 17:18:08'),
	(528, '治疗', '2015-04-26 17:18:08'),
	(529, '痘', '2015-04-26 17:18:08'),
	(530, '南通', '2015-04-26 17:18:08'),
	(531, '深圳', '2015-04-26 17:18:08'),
	(532, 'kongweizhi.com', '2015-04-26 17:18:08'),
	(533, 'foxiang86.net', '2015-04-26 17:18:08'),
	(534, 'taobaobaobao.com', '2015-04-26 17:18:08');
/*!40000 ALTER TABLE `ts_anti_word` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_article
DROP TABLE IF EXISTS `ts_article`;
CREATE TABLE IF NOT EXISTS `ts_article` (
  `articleid` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `locationid` int(11) NOT NULL DEFAULT '0' COMMENT '同城ID',
  `cateid` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '标题',
  `content` longtext NOT NULL COMMENT '内容',
  `tags` varchar(128) NOT NULL DEFAULT '' COMMENT '标签',
  `gaiyao` varchar(128) NOT NULL DEFAULT '' COMMENT '内容概要',
  `path` char(32) NOT NULL DEFAULT '' COMMENT '路径',
  `photo` char(32) NOT NULL DEFAULT '' COMMENT '图片路径',
  `isaudit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `count_comment` int(11) NOT NULL DEFAULT '0' COMMENT '统计评论数',
  `count_recommend` int(11) NOT NULL DEFAULT '0' COMMENT '统计推荐次数',
  `count_view` int(11) NOT NULL DEFAULT '0' COMMENT '统计查看',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '时间',
  PRIMARY KEY (`articleid`),
  UNIQUE KEY `title_2` (`title`),
  KEY `addtime` (`addtime`),
  KEY `cateid` (`cateid`),
  KEY `isrecommend` (`isrecommend`),
  KEY `count_recommend` (`count_recommend`,`addtime`),
  KEY `title` (`title`),
  KEY `count_view` (`count_view`),
  KEY `count_view_2` (`count_view`,`addtime`),
  KEY `locationid` (`locationid`),
  KEY `tags` (`tags`,`isaudit`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='文章';

-- Dumping data for table thinksaas-dev.ts_article: 0 rows
DELETE FROM `ts_article`;
/*!40000 ALTER TABLE `ts_article` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_article` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_article_cate
DROP TABLE IF EXISTS `ts_article_cate`;
CREATE TABLE IF NOT EXISTS `ts_article_cate` (
  `cateid` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `referid` int(11) NOT NULL DEFAULT '0' COMMENT '上级ID',
  `catename` varchar(64) NOT NULL DEFAULT '' COMMENT '分类名称',
  `orderid` int(11) NOT NULL DEFAULT '0' COMMENT '排序ID',
  PRIMARY KEY (`cateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='文章分类';

-- Dumping data for table thinksaas-dev.ts_article_cate: 0 rows
DELETE FROM `ts_article_cate`;
/*!40000 ALTER TABLE `ts_article_cate` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_article_cate` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_article_comment
DROP TABLE IF EXISTS `ts_article_comment`;
CREATE TABLE IF NOT EXISTS `ts_article_comment` (
  `commentid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增评论ID',
  `articleid` int(11) NOT NULL DEFAULT '0' COMMENT '文章ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `content` text NOT NULL COMMENT '评论内容',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '评论时间',
  PRIMARY KEY (`commentid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='文章评论';

-- Dumping data for table thinksaas-dev.ts_article_comment: 0 rows
DELETE FROM `ts_article_comment`;
/*!40000 ALTER TABLE `ts_article_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_article_comment` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_article_options
DROP TABLE IF EXISTS `ts_article_options`;
CREATE TABLE IF NOT EXISTS `ts_article_options` (
  `optionname` varchar(32) NOT NULL DEFAULT '' COMMENT '选项名字',
  `optionvalue` varchar(512) NOT NULL DEFAULT '' COMMENT '选项内容',
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='文章配置';

-- Dumping data for table thinksaas-dev.ts_article_options: 5 rows
DELETE FROM `ts_article_options`;
/*!40000 ALTER TABLE `ts_article_options` DISABLE KEYS */;
INSERT INTO `ts_article_options` (`optionname`, `optionvalue`) VALUES
	('appname', '文章'),
	('appdesc', '文章'),
	('appkey', '文章'),
	('allowpost', '1'),
	('isaudit', '0');
/*!40000 ALTER TABLE `ts_article_options` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_article_recommend
DROP TABLE IF EXISTS `ts_article_recommend`;
CREATE TABLE IF NOT EXISTS `ts_article_recommend` (
  `articleid` int(11) NOT NULL DEFAULT '0' COMMENT '文章ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  UNIQUE KEY `articleid` (`articleid`,`userid`),
  KEY `articleid_2` (`articleid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='文章推荐';

-- Dumping data for table thinksaas-dev.ts_article_recommend: 0 rows
DELETE FROM `ts_article_recommend`;
/*!40000 ALTER TABLE `ts_article_recommend` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_article_recommend` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_cache
DROP TABLE IF EXISTS `ts_cache`;
CREATE TABLE IF NOT EXISTS `ts_cache` (
  `cacheid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增缓存ID',
  `cachename` varchar(64) NOT NULL DEFAULT '' COMMENT '缓存名字',
  `cachevalue` text NOT NULL COMMENT '缓存内容',
  PRIMARY KEY (`cacheid`),
  UNIQUE KEY `cachename` (`cachename`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COMMENT='缓存';

-- Dumping data for table thinksaas-dev.ts_cache: 19 rows
DELETE FROM `ts_cache`;
/*!40000 ALTER TABLE `ts_cache` DISABLE KEYS */;
INSERT INTO `ts_cache` (`cacheid`, `cachename`, `cachevalue`) VALUES
	(1, 'pubs_plugins', '1532102706a:16:{i:9;s:10:"floatlayer";i:19;s:8:"customer";i:20;s:7:"counter";i:21;s:6:"douban";i:22;s:8:"feedback";i:24;s:7:"gonggao";i:25;s:5:"gotop";i:26;s:4:"navs";i:27;s:2:"qq";i:29;s:5:"weibo";i:30;s:6:"wordad";i:31;s:9:"footertip";i:32;s:8:"leftuser";i:33;s:7:"ueditor";i:34;s:5:"gobad";i:35;s:10:"wangeditor";}'),
	(2, 'home_plugins', '1406904279a:13:{i:11;s:9:"newtopics";i:12;s:5:"slide";i:13;s:8:"signuser";i:14;s:14:"recommendgroup";i:15;s:3:"tag";i:16;s:8:"newtopic";i:17;s:5:"login";i:18;s:5:"weibo";i:19;s:8:"newgroup";i:20;s:7:"article";i:21;s:8:"hottopic";i:22;s:5:"photo";i:23;s:5:"links";}'),
	(3, 'system_options', '1540469862a:29:{s:10:"site_title";s:9:"ThinkSAAS";s:13:"site_subtitle";s:24:"又一个ThinkSAAS社区";s:8:"site_key";s:9:"thinksaas";s:9:"site_desc";s:9:"thinksaas";s:8:"site_url";s:17:"http://127.0.0.1/";s:8:"link_url";s:17:"http://127.0.0.1/";s:9:"site_pkey";s:32:"1649f854581e9c03bc2c4e06023c5b99";s:10:"site_email";s:15:"admin@admin.com";s:8:"site_icp";s:20:"豫ICP备00000000号";s:6:"isface";s:1:"0";s:8:"isinvite";s:1:"0";s:8:"isverify";s:1:"0";s:6:"istomy";s:1:"0";s:10:"isauthcode";s:1:"0";s:7:"istoken";s:1:"0";s:6:"isgzip";s:1:"0";s:8:"timezone";s:14:"Asia/Hong_Kong";s:7:"visitor";s:1:"0";s:9:"publisher";s:1:"0";s:11:"isallowedit";s:1:"0";s:13:"isallowdelete";s:1:"0";s:10:"site_theme";s:6:"sample";s:12:"site_urltype";s:1:"1";s:10:"photo_size";s:1:"2";s:10:"photo_type";s:16:"jpg,gif,png,jpeg";s:11:"attach_size";s:1:"2";s:11:"attach_type";s:19:"zip,rar,doc,txt,ppt";s:11:"dayscoretop";s:2:"10";s:4:"logo";s:8:"logo.png";}'),
	(4, 'system_appnav', '1532102633a:9:{s:4:"home";s:6:"首页";s:5:"group";s:6:"小组";s:7:"article";s:6:"文章";s:5:"photo";s:6:"相册";s:5:"weibo";s:6:"唠叨";s:4:"user";s:6:"用户";s:6:"search";s:6:"搜索";s:8:"location";s:6:"同城";s:2:"my";s:12:"我的社区";}'),
	(5, 'system_anti_word', '1430039888s:5733:"国民党|邓小平|江泽民|胡锦涛|共产党|毛主席|毛泽东|中共|中国共产党|枪|弹药|枪支|氣槍|猎槍|来福|雷鸣登|五连发|平式双管|立式双管|麻醉|军用|进口|录入员|招聘兼职|1332566258|直流电阻测试仪|继电保护测试仪|串联谐振|分压器|www.hbyhdl.com|www.bd-seo.net|武汉网站优化|武汉网络推广|武汉网络营销|武汉SEO|大脚骨矫正器|379056061|拇外翻|小姐|习近平|王立军|两会|薄熙来|谷开来|唱红打黑|听党指挥|薄一波|李长春|周永康|政府|zhenfu|傻逼|zhengfu|他杀|枪杀|谋杀|起义|就义|法轮功|邪教|GCD|hege123|菲律宾|薄熙莱|小姐上门|一夜情|性爱|性息|武汉婚纱摄影|武汉婚纱照|武汉艺术照|武汉婚纱摄影工作室|www.yilongphoto.com|www.windyx.com|www.ruile.net|www.wo-niu.com.cn|www.wlzb518.com|www.wufangzhai-zongzi.com|www.hege123.com|按摩|按摩服务|cwsurf.de|出台|包夜|www.maizongzi.com|代刷|微笑网络|刷信誉|53530.cn|waimaodao.com|小 妹|上 门 服 务|小 妹 上 门|王哥|花姐|学妹|姓ˊ感ˊ少ˊ妇|丰ˊ韵ˊ熟ˊ妇|丽人岛休闲会所|越ˊ南ˊ妹|77057|饥ˊ渴ˊ熟ˊ女|饥渴熟女|性感人妻|姓感少妇|51gouku.com|51够酷|585.cc|腋臭|狐臭|兼职学生妹|湘湘|小唐|学生妹|wufangzhai-zongzi.com|冰毒|海洛因|毒品|吸毒|66dao.com|办证|赵小姐|douyapai.com|ntxz.cc|豆芽派|tdjyedu|自考招生|sinoest|身份證买卖|身份证买卖|895316992|爱游中国|上門服务|客人隐私|suoniao.com|男科医院|男性医院|包皮|包茎|男科|做爱|小妹上门|小妹服务|sm|sm女王|成熟少妇|包吹|极品校花|sinoest.com|尖锐湿疣|耳鼻喉医院|过敏性鼻炎|ibuonline.com|福彩|福彩3d|totutu.com|去黑头|东方软峰|yileee.com|新特药|fgt120.com|99spcar.com|meiti520.com|bbswuhan.com|18611314446|丰韵熟妇|越南妹|maizongzi.com|上门服务|小妹|windyx.com|wlzb518.com|wo-niu.com.cn|ruile.net|bd-seo.net|hbyhdl.com|yilongphoto.com|hege123.com|發票|开票电|开票|发票|代开发票|私募|走私车|资本运作|真人视频|造价通|移民网|药商|亚布力|雅思|新皇宝|校花聊天室|消费投资合法|西安卖肾|同城女|丝袜交友|兼职服务|草榴社区|搬家公司|代開|代开|醱票|開瞟|瞟据|瞟务|酒店住宿|13826544598|2645989872|18312006833|费发|314721888|办理假证件|394057341|费發|13533391062|13544261868|13828442144|13728999976|13662622538|897839088|vpswolf.com|vanshen.com|yapai.cc|daqiaogw.com|hfkszdm.com|jinqiaohc.com|0553rl.com|ln580.cn|qifanweb.com|qifanseo.com|qifanit.com|028zfyy.com|aitecentury.com|aite55.com|shentongkang.com|dss.so|3ja.net|sin55.com|2008ns.com|203529769|binhaijincheng.com|美女服务|18611325651|caihua.cc|51mm.com.cn|tbwtmall.net|lubaolin.com|糖尿病治疗仪|tangniaobingok.com|糖尿病|chtip.org|56156.com|07uuu.com|haoyouren.com|便秘|haoyouren|xxcun.com|iisp.com|gmwhy.com|feelyz.com|369in.com|cdtarena.com|肝硬化|youbian.com|162net.com|comnetcnn.com|2233.cn|鸡巴|119tx.com|0377521.com|028zuanji.com|dzwan.net|dodomo.net|sina.com|fobshanghai.com|芬恩|2659477099|58692026|65111117|英文SEO|仿牌SEO|外贸SEO|1550957342|pingan.com|痔疮|86889299|800002356|cqddgc.cn|股票|nyimei.com|天衣坊|宏天景秀|sugon.com|微博008|qqbct.com|qqsuncity.com|99txzq.com|88txzq.com|sdebh.cn|9501317248463|248463|飞机票|网银|火车票|无卡存款|66667959|订票|火车站|57071215|889584017|获奖查询|穫獎查詢|82425|5782|中獎|熱線|1931033|代售点|w1a2.icoc.cc|1317241334|车票改签|嘿咻|加盟|湿疹|塑胶|1817001212|4000318885|800007699|4008521119|花月婷|xpjin.com|新葡京娱乐城|eshibo68.com|tt5201314.com|TT娱乐城|娱乐城|qinzi5.com|21202|火車票|089|8369|退票|改簽|預訂|ruijintc.com|ruijintc.net|草榴|caoliu|5177game.com|cao|wgb320330.com|公關|公关|夜总会|兼职|同性恋|丝足|同志|女王|鸭子|调教|KTV|夜场|娱乐场|陪护|情感陪护|公主|LES|GAY|兼職|18611102232|男妓|妓男|女妓|cs12388.com|liketuan.com|xiunvfang.com|tmall.com|rekuai.com|gyouz.com|u95.cc|ikphp.com|12ik.com|7lo.cn|hufuin.com|fa66.com|itcpa.cn|72jz.com|网赚|ikphp|12ik|weadge.com|mfkds.com|svs123.com|dlnmd.com|nicenic.com|13311372110|58464602|bf3.cn|你妹啊|33md.co|梅毒|你妈|hywww.net|2015230140|淋病|非淋病|damazha.com|mitang.pw|六合彩|赛马|三陪|51shuaige.com|369tong.com|holde.cn|18xi.com|信息代发|推广软件|营销软件|网络推广|营销人|91renren.com|kukud.net|chuntu.cc|jinhusns.com|鼻炎|医院|153075777|hqbsns.com|jjyulecheng77g.com|senlang.net|xiyingmenyulecheng8a.com|osforce.cn|51neixun.com|你妈逼|杀人|操你妈|草泥马|税票|wanlidq.com|醫院|mrdodo.net|188123581|zhengma.com|4008166005|400|800|化妆|陈派|税务|整形|美容|隆胸|jinyuanbao.cn|xkqmj.org|baishiheyuan.com|057160989861|hiici.com|518202.com|微店|60989861|1433607382|18921182443|82835166|官方推荐|百度认证|sh419x.net|hhy021.com|ehuanka.com|柘荣太子参|buluocc.com|buluocc|szprovence.com|上海龙凤|shlf9.net|shlf9.com|shlf99.net|shlf99.com|shlf999.net|shlf999.com|aishiso.com|qhf021.com|cfg021.com|uoko.com|guizubbx.com|esosn.com|win7zhijia.cn|xtcheng.net|867590759|888xitong.com|1378206455|宜人贷|win7qjb.com|xitongcheng|onerare.com|xitong1.com|ghostxpsp3.net|windows114.com|djie.net|999ghost.com|xitongcity.com|51rgb.com|mingtaov.com|228224.com|hb7526.com|jx8091.com|drf8953.com|xpjgj9186.com|hjjb9981.com|dwj5266.com|xq81365.com|nc2787.com|wfgj7656.com|seowhy.com|ceo2351.com|fc5517.com|bg6261.com|fbgj8620.com|ylgj2523.com|zibenzaixian.com|chetips.com|51zhaoji.com|白癜风|kanshijian.com|wudan100.com|治疗|痘|南通|深圳|kongweizhi.com|foxiang86.net|taobaobaobao.com";'),
	(6, 'user_options', '1532102646a:6:{s:7:"appname";s:6:"用户";s:7:"appdesc";s:12:"用户中心";s:6:"appkey";s:6:"用户";s:8:"isenable";s:1:"0";s:7:"isgroup";s:0:"";s:7:"banuser";s:25:"官方用户|官方团队";}'),
	(7, 'mail_options', '1532102620a:8:{s:7:"appname";s:6:"邮件";s:7:"appdesc";s:15:"ThinkSAAS邮件";s:8:"isenable";s:1:"0";s:8:"mailhost";s:18:"smtp.exmail.qq.com";s:3:"ssl";s:1:"1";s:8:"mailport";s:3:"587";s:8:"mailuser";s:23:"postmaster@thinksaas.cn";s:7:"mailpwd";s:0:"";}'),
	(8, 'article_options', '1532095494a:5:{s:7:"appname";s:6:"文章";s:7:"appdesc";s:6:"文章";s:6:"appkey";s:6:"文章";s:9:"allowpost";s:1:"1";s:7:"isaudit";s:1:"0";}'),
	(9, 'group_options', '1532102579a:9:{s:7:"appname";s:6:"小组";s:7:"appdesc";s:15:"ThinkSAAS小组";s:6:"appkey";s:6:"小组";s:8:"iscreate";s:1:"0";s:7:"isaudit";s:1:"0";s:7:"joinnum";s:2:"20";s:11:"isallowpost";s:1:"0";s:13:"istopicattach";s:1:"0";s:9:"ispayjoin";s:1:"0";}'),
	(10, 'photo_options', '1532102633a:4:{s:7:"appname";s:6:"相册";s:7:"appdesc";s:6:"相册";s:6:"appkey";s:6:"相册";s:7:"isaudit";s:1:"0";}'),
	(11, 'weibo_options', '1532102652a:3:{s:7:"appname";s:6:"唠叨";s:7:"appdesc";s:6:"唠叨";s:6:"appkey";s:6:"唠叨";}'),
	(12, 'plugins_pubs_wordad', '1400602928a:4:{i:0;a:2:{s:5:"title";s:22:"ThinkSAAS文字广告1";s:3:"url";s:23:"https://www.thinksaas.cn";}i:1;a:2:{s:5:"title";s:22:"ThinkSAAS文字广告2";s:3:"url";s:23:"https://www.thinksaas.cn";}i:2;a:2:{s:5:"title";s:22:"ThinkSAAS文字广告3";s:3:"url";s:23:"https://www.thinksaas.cn";}i:3;a:2:{s:5:"title";s:22:"ThinkSAAS文字广告4";s:3:"url";s:23:"https://www.thinksaas.cn";}}'),
	(13, 'user_role', '1400602955a:17:{i:0;a:3:{s:8:"rolename";s:6:"列兵";s:11:"score_start";s:1:"0";s:9:"score_end";s:4:"5000";}i:1;a:3:{s:8:"rolename";s:6:"下士";s:11:"score_start";s:4:"5000";s:9:"score_end";s:5:"20000";}i:2;a:3:{s:8:"rolename";s:6:"中士";s:11:"score_start";s:5:"20000";s:9:"score_end";s:5:"40000";}i:3;a:3:{s:8:"rolename";s:6:"上士";s:11:"score_start";s:5:"40000";s:9:"score_end";s:5:"80000";}i:4;a:3:{s:8:"rolename";s:12:"三级准尉";s:11:"score_start";s:5:"80000";s:9:"score_end";s:6:"160000";}i:5;a:3:{s:8:"rolename";s:12:"二级准尉";s:11:"score_start";s:6:"160000";s:9:"score_end";s:6:"320000";}i:6;a:3:{s:8:"rolename";s:12:"一级准尉";s:11:"score_start";s:6:"320000";s:9:"score_end";s:6:"640000";}i:7;a:3:{s:8:"rolename";s:6:"少尉";s:11:"score_start";s:6:"640000";s:9:"score_end";s:7:"1280000";}i:8;a:3:{s:8:"rolename";s:6:"中尉";s:11:"score_start";s:7:"1280000";s:9:"score_end";s:7:"2560000";}i:9;a:3:{s:8:"rolename";s:6:"上尉";s:11:"score_start";s:7:"2560000";s:9:"score_end";s:7:"5120000";}i:10;a:3:{s:8:"rolename";s:6:"少校";s:11:"score_start";s:7:"5120000";s:9:"score_end";s:8:"10240000";}i:11;a:3:{s:8:"rolename";s:6:"中校";s:11:"score_start";s:8:"10240000";s:9:"score_end";s:8:"20480000";}i:12;a:3:{s:8:"rolename";s:6:"上校";s:11:"score_start";s:8:"20480000";s:9:"score_end";s:8:"40960000";}i:13;a:3:{s:8:"rolename";s:6:"准将";s:11:"score_start";s:8:"40960000";s:9:"score_end";s:8:"81920000";}i:14;a:3:{s:8:"rolename";s:6:"少将";s:11:"score_start";s:8:"81920000";s:9:"score_end";s:9:"123840000";}i:15;a:3:{s:8:"rolename";s:6:"中将";s:11:"score_start";s:9:"123840000";s:9:"score_end";s:9:"327680000";}i:16;a:3:{s:8:"rolename";s:6:"上将";s:11:"score_start";s:9:"327680000";s:9:"score_end";s:1:"0";}}'),
	(14, 'plugins_pubs_gobad', '1400603098a:3:{i:300;s:20:"宽度300px广告位";i:468;s:20:"宽度468px广告位";i:960;s:20:"宽度960px广告位";}'),
	(15, 'plugins_pubs_feedback', '1406109222s:52:"<a href=\\"https://www.thinksaas.cn\\">意见反馈</a>";'),
	(16, 'plugins_pubs_counter', '1540470205s:113:"<!--统计代码-->";'),
	(18, 'plugins_home_links', '1540469938a:2:{i:0;a:2:{s:8:"linkname";s:9:"ThinkSAAS";s:7:"linkurl";s:25:"https://www.thinksaas.cn/";}i:1;a:2:{s:8:"linkname";s:12:"开源社区";s:7:"linkurl";s:25:"https://www.thinksaas.cn/";}}'),
	(17, 'plugins_pubs_navs', '1532088590a:2:{i:0;a:2:{s:7:"navname";s:15:"ThinkSAAS官网";s:6:"navurl";s:25:"https://www.thinksaas.cn/";}i:1;a:2:{s:7:"navname";s:21:"付费商业版授权";s:6:"navurl";s:38:"https://www.thinksaas.cn/service/down/";}}'),
	(24, 'system_mynav', '1531722577a:5:{s:5:"group";s:6:"小组";s:7:"article";s:6:"文章";s:5:"weibo";s:6:"唠叨";s:5:"photo";s:6:"相册";s:8:"location";s:6:"同城";}');
/*!40000 ALTER TABLE `ts_cache` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_draft
DROP TABLE IF EXISTS `ts_draft`;
CREATE TABLE IF NOT EXISTS `ts_draft` (
  `draftid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `types` varchar(50) NOT NULL DEFAULT '' COMMENT '类型：比如帖子topic',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '标题',
  `content` longtext NOT NULL COMMENT '内容',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`draftid`),
  UNIQUE KEY `userid_types` (`userid`,`types`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='草稿箱';

-- Dumping data for table thinksaas-dev.ts_draft: 0 rows
DELETE FROM `ts_draft`;
/*!40000 ALTER TABLE `ts_draft` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_draft` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_editor
DROP TABLE IF EXISTS `ts_editor`;
CREATE TABLE IF NOT EXISTS `ts_editor` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `type` char(32) NOT NULL DEFAULT 'photo' COMMENT '类型photo,file',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '标题',
  `path` char(32) NOT NULL DEFAULT '' COMMENT '路径',
  `url` char(32) NOT NULL DEFAULT '' COMMENT '图片或者文件',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='编辑器上传图片和文件';

-- Dumping data for table thinksaas-dev.ts_editor: 0 rows
DELETE FROM `ts_editor`;
/*!40000 ALTER TABLE `ts_editor` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_editor` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group
DROP TABLE IF EXISTS `ts_group`;
CREATE TABLE IF NOT EXISTS `ts_group` (
  `groupid` int(11) NOT NULL AUTO_INCREMENT COMMENT '小组ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `cateid` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `cateid2` int(11) NOT NULL DEFAULT '0' COMMENT '二级分类ID',
  `cateid3` int(11) NOT NULL DEFAULT '0' COMMENT '三级分类ID',
  `orderid` int(11) NOT NULL DEFAULT '0' COMMENT '排序ID',
  `groupname` varchar(32) NOT NULL DEFAULT '' COMMENT '群组名字',
  `groupdesc` text NOT NULL COMMENT '小组介绍',
  `path` char(32) NOT NULL DEFAULT '' COMMENT '图标路径',
  `photo` char(32) NOT NULL DEFAULT '' COMMENT '小组图标',
  `bgphoto` char(32) NOT NULL DEFAULT '' COMMENT '背景图片',
  `count_topic` int(11) NOT NULL DEFAULT '0' COMMENT '帖子统计',
  `count_topic_today` int(11) NOT NULL DEFAULT '0' COMMENT '统计今天发帖',
  `count_user` int(11) NOT NULL DEFAULT '0' COMMENT '小组成员数',
  `count_topic_audit` int(11) NOT NULL DEFAULT '0' COMMENT '统计未审核帖子数',
  `joinway` tinyint(1) NOT NULL DEFAULT '0' COMMENT '加入方式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '加入支付金币',
  `role_leader` char(32) NOT NULL DEFAULT '组长' COMMENT '组长角色名称',
  `role_admin` char(32) NOT NULL DEFAULT '管理员' COMMENT '管理员角色名称',
  `role_user` char(32) NOT NULL DEFAULT '成员' COMMENT '成员角色名称',
  `addtime` int(11) DEFAULT '0' COMMENT '创建时间',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `isopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否公开或者私密',
  `isaudit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核',
  `ispost` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否允许会员发帖',
  `isshow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示',
  `ispostaudit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发帖审核',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  PRIMARY KEY (`groupid`),
  KEY `userid` (`userid`),
  KEY `isshow` (`isshow`),
  KEY `groupname` (`groupname`),
  KEY `cateid` (`cateid`),
  KEY `isaudit` (`isaudit`),
  KEY `addtime` (`addtime`),
  KEY `isrecommend` (`isrecommend`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='群组(小组)';

-- Dumping data for table thinksaas-dev.ts_group: 0 rows
DELETE FROM `ts_group`;
/*!40000 ALTER TABLE `ts_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_album
DROP TABLE IF EXISTS `ts_group_album`;
CREATE TABLE IF NOT EXISTS `ts_group_album` (
  `albumid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增专辑ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `groupid` int(11) NOT NULL DEFAULT '0' COMMENT '小组ID',
  `albumname` varchar(64) NOT NULL DEFAULT '' COMMENT '专辑名字',
  `albumdesc` text NOT NULL COMMENT '专辑介绍',
  `count_topic` int(11) NOT NULL DEFAULT '0' COMMENT '统计帖子',
  `isaudit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '审核',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '添加时间',
  PRIMARY KEY (`albumid`),
  KEY `userid` (`userid`),
  KEY `count_topic` (`count_topic`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='小组帖子专辑';

-- Dumping data for table thinksaas-dev.ts_group_album: 0 rows
DELETE FROM `ts_group_album`;
/*!40000 ALTER TABLE `ts_group_album` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_album` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_album_topic
DROP TABLE IF EXISTS `ts_group_album_topic`;
CREATE TABLE IF NOT EXISTS `ts_group_album_topic` (
  `albumid` int(11) NOT NULL DEFAULT '0' COMMENT '专辑ID',
  `topicid` int(11) NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '时间',
  UNIQUE KEY `albumid_2` (`albumid`,`topicid`),
  KEY `albumid` (`albumid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='小组专辑帖子关联';

-- Dumping data for table thinksaas-dev.ts_group_album_topic: 0 rows
DELETE FROM `ts_group_album_topic`;
/*!40000 ALTER TABLE `ts_group_album_topic` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_album_topic` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_cate
DROP TABLE IF EXISTS `ts_group_cate`;
CREATE TABLE IF NOT EXISTS `ts_group_cate` (
  `cateid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增分类ID',
  `catename` varchar(64) NOT NULL DEFAULT '' COMMENT '分类名字',
  `referid` int(11) NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `count_group` int(11) NOT NULL DEFAULT '0' COMMENT '群组个数',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  PRIMARY KEY (`cateid`),
  KEY `referid` (`referid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='小组分类';

-- Dumping data for table thinksaas-dev.ts_group_cate: 0 rows
DELETE FROM `ts_group_cate`;
/*!40000 ALTER TABLE `ts_group_cate` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_cate` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_options
DROP TABLE IF EXISTS `ts_group_options`;
CREATE TABLE IF NOT EXISTS `ts_group_options` (
  `optionname` varchar(32) NOT NULL DEFAULT '' COMMENT '选项名字',
  `optionvalue` varchar(512) NOT NULL DEFAULT '' COMMENT '选项内容',
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='小组配置';

-- Dumping data for table thinksaas-dev.ts_group_options: 9 rows
DELETE FROM `ts_group_options`;
/*!40000 ALTER TABLE `ts_group_options` DISABLE KEYS */;
INSERT INTO `ts_group_options` (`optionname`, `optionvalue`) VALUES
	('appname', '小组'),
	('appdesc', 'ThinkSAAS小组'),
	('appkey', '小组'),
	('iscreate', '0'),
	('isaudit', '0'),
	('joinnum', '20'),
	('isallowpost', '0'),
	('istopicattach', '0'),
	('ispayjoin', '0');
/*!40000 ALTER TABLE `ts_group_options` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_topic
DROP TABLE IF EXISTS `ts_group_topic`;
CREATE TABLE IF NOT EXISTS `ts_group_topic` (
  `topicid` int(11) NOT NULL AUTO_INCREMENT COMMENT '话题ID',
  `typeid` int(11) NOT NULL DEFAULT '0' COMMENT '帖子分类ID',
  `groupid` int(11) NOT NULL DEFAULT '0' COMMENT '小组ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `locationid` int(11) NOT NULL DEFAULT '0' COMMENT '同城ID',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '帖子标题',
  `label` varchar(64) NOT NULL DEFAULT '' COMMENT '快速标注',
  `content` longtext NOT NULL COMMENT '帖子内容',
  `gaiyao` varchar(256) NOT NULL DEFAULT '' COMMENT '内容概要',
  `count_comment` int(11) NOT NULL DEFAULT '0' COMMENT '回复统计',
  `count_view` int(11) NOT NULL DEFAULT '0' COMMENT '帖子展示数',
  `count_love` int(11) NOT NULL DEFAULT '0' COMMENT '喜欢数',
  `istop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `isclose` int(4) NOT NULL DEFAULT '0' COMMENT '是否关闭帖子',
  `iscomment` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否允许评论',
  `iscommentshow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否评论后显示内容',
  `isposts` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否精华帖子',
  `isaudit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0不审核1审核',
  `isdelete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0不删除1删除',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1为推荐',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`topicid`),
  KEY `groupid` (`groupid`),
  KEY `userid` (`userid`),
  KEY `title` (`title`),
  KEY `groupid_2` (`groupid`),
  KEY `typeid` (`typeid`),
  KEY `addtime` (`addtime`),
  KEY `count_comment` (`count_comment`),
  KEY `count_view` (`count_view`),
  KEY `count_love` (`count_love`),
  KEY `count_view_2` (`count_view`,`addtime`),
  KEY `isshow` (`isaudit`,`uptime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='小组话题(小组帖子)';

-- Dumping data for table thinksaas-dev.ts_group_topic: 0 rows
DELETE FROM `ts_group_topic`;
/*!40000 ALTER TABLE `ts_group_topic` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_topic` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_topic_collect
DROP TABLE IF EXISTS `ts_group_topic_collect`;
CREATE TABLE IF NOT EXISTS `ts_group_topic_collect` (
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `username` varchar(64) NOT NULL DEFAULT '' COMMENT '用户名',
  `topicid` int(11) NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '收藏时间',
  UNIQUE KEY `userid_2` (`userid`,`topicid`),
  KEY `userid` (`userid`),
  KEY `topicid` (`topicid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='帖子收藏';

-- Dumping data for table thinksaas-dev.ts_group_topic_collect: 0 rows
DELETE FROM `ts_group_topic_collect`;
/*!40000 ALTER TABLE `ts_group_topic_collect` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_topic_collect` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_topic_comment
DROP TABLE IF EXISTS `ts_group_topic_comment`;
CREATE TABLE IF NOT EXISTS `ts_group_topic_comment` (
  `commentid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增评论ID',
  `referid` int(11) NOT NULL DEFAULT '0' COMMENT '上级评论ID',
  `topicid` int(11) NOT NULL DEFAULT '0' COMMENT '话题ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `content` text NOT NULL COMMENT '回复内容',
  `ispublic` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0公开1不公开（仅自己和发帖者可看）',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '回复时间',
  PRIMARY KEY (`commentid`),
  KEY `topicid` (`topicid`),
  KEY `userid` (`userid`),
  KEY `referid` (`referid`,`topicid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='话题回复/评论';

-- Dumping data for table thinksaas-dev.ts_group_topic_comment: 0 rows
DELETE FROM `ts_group_topic_comment`;
/*!40000 ALTER TABLE `ts_group_topic_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_topic_comment` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_topic_edit
DROP TABLE IF EXISTS `ts_group_topic_edit`;
CREATE TABLE IF NOT EXISTS `ts_group_topic_edit` (
  `editid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增编辑ID',
  `topicid` int(11) NOT NULL DEFAULT '0' COMMENT '话题ID',
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `isupdate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未更新1更新',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '修改时间',
  PRIMARY KEY (`editid`),
  UNIQUE KEY `topicid` (`topicid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='话题编辑';

-- Dumping data for table thinksaas-dev.ts_group_topic_edit: 0 rows
DELETE FROM `ts_group_topic_edit`;
/*!40000 ALTER TABLE `ts_group_topic_edit` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_topic_edit` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_topic_love
DROP TABLE IF EXISTS `ts_group_topic_love`;
CREATE TABLE IF NOT EXISTS `ts_group_topic_love` (
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `topicid` int(11) NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  UNIQUE KEY `userid_topicid` (`userid`,`topicid`),
  KEY `topicid` (`topicid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4mb4 COMMENT='帖子点赞喜欢';

-- Dumping data for table thinksaas-dev.ts_group_topic_love: 0 rows
DELETE FROM `ts_group_topic_love`;
/*!40000 ALTER TABLE `ts_group_topic_love` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_topic_love` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_topic_photo
DROP TABLE IF EXISTS `ts_group_topic_photo`;
CREATE TABLE IF NOT EXISTS `ts_group_topic_photo` (
  `photoid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `topicid` int(11) NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `path` varchar(50) NOT NULL DEFAULT '' COMMENT '路径',
  `photo` varchar(50) NOT NULL DEFAULT '' COMMENT '图片',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`photoid`),
  UNIQUE KEY `photoid` (`photoid`),
  KEY `topicid` (`topicid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='帖子图片表';

-- Dumping data for table thinksaas-dev.ts_group_topic_photo: 0 rows
DELETE FROM `ts_group_topic_photo`;
/*!40000 ALTER TABLE `ts_group_topic_photo` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_topic_photo` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_topic_type
DROP TABLE IF EXISTS `ts_group_topic_type`;
CREATE TABLE IF NOT EXISTS `ts_group_topic_type` (
  `typeid` int(11) NOT NULL AUTO_INCREMENT COMMENT '帖子分类ID',
  `groupid` int(11) NOT NULL DEFAULT '0' COMMENT '小组ID',
  `typename` varchar(64) NOT NULL DEFAULT '' COMMENT '帖子分类名称',
  `count_topic` int(11) NOT NULL DEFAULT '0' COMMENT '统计帖子',
  PRIMARY KEY (`typeid`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='帖子分类';

-- Dumping data for table thinksaas-dev.ts_group_topic_type: 0 rows
DELETE FROM `ts_group_topic_type`;
/*!40000 ALTER TABLE `ts_group_topic_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_topic_type` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_user
DROP TABLE IF EXISTS `ts_group_user`;
CREATE TABLE IF NOT EXISTS `ts_group_user` (
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `groupid` int(11) NOT NULL DEFAULT '0' COMMENT '群组ID',
  `isadmin` int(11) NOT NULL DEFAULT '0' COMMENT '是否管理员',
  `isfounder` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否创始人',
  `endtime` date NOT NULL DEFAULT '1970-01-01' COMMENT '到期时间',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '加入时间',
  UNIQUE KEY `userid_2` (`userid`,`groupid`),
  KEY `userid` (`userid`),
  KEY `groupid` (`groupid`),
  KEY `groupid_2` (`groupid`,`isadmin`,`isfounder`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='群组和用户对应关系';

-- Dumping data for table thinksaas-dev.ts_group_user: 0 rows
DELETE FROM `ts_group_user`;
/*!40000 ALTER TABLE `ts_group_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_user` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_group_user_isaudit
DROP TABLE IF EXISTS `ts_group_user_isaudit`;
CREATE TABLE IF NOT EXISTS `ts_group_user_isaudit` (
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `groupid` int(11) NOT NULL DEFAULT '0' COMMENT '小组ID',
  UNIQUE KEY `userid` (`userid`,`groupid`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='用于申请加入小组的成员审核';

-- Dumping data for table thinksaas-dev.ts_group_user_isaudit: 0 rows
DELETE FROM `ts_group_user_isaudit`;
/*!40000 ALTER TABLE `ts_group_user_isaudit` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_user_isaudit` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_home_info
DROP TABLE IF EXISTS `ts_home_info`;
CREATE TABLE IF NOT EXISTS `ts_home_info` (
  `infoid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `orderid` int(11) NOT NULL DEFAULT '0' COMMENT '排序ID',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`infoid`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='关于我们等信息';

-- Dumping data for table thinksaas-dev.ts_home_info: 5 rows
DELETE FROM `ts_home_info`;
/*!40000 ALTER TABLE `ts_home_info` DISABLE KEYS */;
INSERT INTO `ts_home_info` (`infoid`, `orderid`, `title`, `content`) VALUES
	(1, 0, '关于我们', '\n&lt;p&gt;关于我们&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;\n'),
	(2, 0, '联系我们', '\n&lt;p&gt;联系我们&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;\n'),
	(3, 0, '用户条款', '\n&lt;p&gt;本协议适用ThinkSAAS发布的所有程序版本和代码，所有版本都将按照最新发布的【用户条款】执行。&lt;br&gt;1、ThinkSAAS官方指：ThinkSAAS社区、thinksaas.cn和ThinkSAAS社区系统开发者邱君。&lt;br&gt;2、ThinkSAAS禁止用户在使用中触犯中国法律范围内的任何法律条文。&lt;br&gt;3、ThinkSAAS、及其创始人邱君拥有对ThinkSAAS的所有权，任何个人，公司和组织不得以任何形式和目的侵犯ThinkSAAS的版权和著作权。&lt;br&gt;4、ThinkSAAS官方拥有对ThinkSAAS社区软件绝对的版权和著作权。&lt;br&gt;5、ThinkSAAS程序代码完全开源，不做任何加密处理。ThinkSAAS允许【自身运营】用户对程序代码进行二次开发，但必须遵循本条款第6、7、8和9条规定执行。&lt;br&gt;6、所有使用ThinkSAAS的用户在保留底部Powered by ThinkSAAS 文字链接或者标识的情况下，可以免费使用ThinkSAAS。&lt;br&gt;7、用户在购买ThinkSAAS商业授权后才可以去除底部Powered by ThinkSAAS 文字链接或者标识。&lt;br&gt;8、ThinkSAAS不会监控用户网站信息，但有权通过邮件或者其他联系方式获悉用户使用情况，有权拿用户网站用作案例展示。&lt;br&gt;9、在未经ThinkSAAS官方书面允许的情况下，除【自身运营】外，任何个人、公司和组织不能单方面发布和出售以ThinkSAAS为基础开发的任何互联网软件或者产品，否则将视为侵权行为，将依照中华人民共和国法律追究其法律责任。&lt;br&gt;10、公司企业等组织机构使用ThinkSAAS软件必须购买ThinkSAAS商业授权协议。&lt;br&gt;11、ThinkSAAS官方拥有对此协议的修改和不断完善。&lt;br&gt;&lt;br&gt;【自身运营】解释：即用户在使用ThinkSAAS中，不通过出售任何以ThinkSAAS为基础开发的产品，仅用作自身学习和自身商业运营的网站。&lt;br&gt;&lt;br&gt;【用户条款】网址：https://www.thinksaas.cn/home/info/key/agreement/&lt;br&gt;【官方网站】网址：https://www.thinksaas.cn/&lt;br&gt;【演示网站】网址：https://demo.thinksaas.cn/&lt;/p&gt;\n'),
	(4, 0, '隐私声明', '\n&lt;p&gt;隐私声明&lt;/p&gt;\n'),
	(5, 0, '加入我们', '\n&lt;p&gt;加入我们&lt;/p&gt;\n');
/*!40000 ALTER TABLE `ts_home_info` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_location
DROP TABLE IF EXISTS `ts_location`;
CREATE TABLE IF NOT EXISTS `ts_location` (
  `locationid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增同城ID',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '同城内容介绍',
  `path` char(32) NOT NULL DEFAULT '' COMMENT '路径',
  `photo` char(32) NOT NULL DEFAULT '' COMMENT '图片',
  `orderid` int(11) NOT NULL DEFAULT '0' COMMENT '排序ID',
  PRIMARY KEY (`locationid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='同城';

-- Dumping data for table thinksaas-dev.ts_location: 0 rows
DELETE FROM `ts_location`;
/*!40000 ALTER TABLE `ts_location` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_location` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_mail_options
DROP TABLE IF EXISTS `ts_mail_options`;
CREATE TABLE IF NOT EXISTS `ts_mail_options` (
  `optionid` int(11) NOT NULL AUTO_INCREMENT COMMENT '选项ID',
  `optionname` varchar(32) NOT NULL DEFAULT '' COMMENT '选项名字',
  `optionvalue` varchar(512) NOT NULL DEFAULT '' COMMENT '选项内容',
  PRIMARY KEY (`optionid`),
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='发信邮件配置';

-- Dumping data for table thinksaas-dev.ts_mail_options: 8 rows
DELETE FROM `ts_mail_options`;
/*!40000 ALTER TABLE `ts_mail_options` DISABLE KEYS */;
INSERT INTO `ts_mail_options` (`optionid`, `optionname`, `optionvalue`) VALUES
	(1, 'appname', '邮件'),
	(2, 'appdesc', 'ThinkSAAS邮件'),
	(3, 'isenable', '0'),
	(4, 'mailhost', 'smtp.exmail.qq.com'),
	(5, 'ssl', '1'),
	(6, 'mailport', '587'),
	(7, 'mailuser', 'postmaster@thinksaas.cn'),
	(8, 'mailpwd', '');
/*!40000 ALTER TABLE `ts_mail_options` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_message
DROP TABLE IF EXISTS `ts_message`;
CREATE TABLE IF NOT EXISTS `ts_message` (
  `messageid` int(11) NOT NULL AUTO_INCREMENT COMMENT '消息ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '发送用户ID',
  `touserid` int(11) NOT NULL DEFAULT '0' COMMENT '接收消息的用户ID',
  `content` text NOT NULL COMMENT '内容',
  `tourl` varchar(255) NOT NULL DEFAULT '' COMMENT '消息跳转地址',
  `extend` varchar(255) NOT NULL DEFAULT '' COMMENT '消息扩展',
  `isread` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已读',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`messageid`),
  KEY `touserid` (`touserid`,`isread`),
  KEY `userid` (`userid`,`touserid`,`isread`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='短消息表';

-- Dumping data for table thinksaas-dev.ts_message: 0 rows
DELETE FROM `ts_message`;
/*!40000 ALTER TABLE `ts_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_message` ENABLE KEYS */;

-- Dumping structure for table demo_thinksaas.ts_phone_code
DROP TABLE IF EXISTS `ts_phone_code`;
CREATE TABLE IF NOT EXISTS `ts_phone_code` (
  `phone` varchar(32) CHARACTER SET utf8 NOT NULL COMMENT '手机号',
  `code` varchar(32) CHARACTER SET utf8 NOT NULL COMMENT '验证码',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '时间',
  UNIQUE KEY `phone` (`phone`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='手机号验证码';

-- Dumping structure for table thinksaas-dev.ts_photo
DROP TABLE IF EXISTS `ts_photo`;
CREATE TABLE IF NOT EXISTS `ts_photo` (
  `photoid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增图片ID',
  `albumid` int(11) NOT NULL DEFAULT '0' COMMENT '相册ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `locationid` int(11) NOT NULL DEFAULT '0' COMMENT '同城iD',
  `photoname` varchar(64) NOT NULL DEFAULT '' COMMENT '图片名称',
  `phototype` char(32) NOT NULL DEFAULT '' COMMENT '图片类型',
  `path` char(32) NOT NULL DEFAULT '' COMMENT '图片路径',
  `photourl` varchar(64) NOT NULL DEFAULT '' COMMENT '图片地址',
  `photosize` char(32) NOT NULL DEFAULT '' COMMENT '图片大小',
  `photodesc` char(120) NOT NULL DEFAULT '' COMMENT '图片介绍',
  `count_view` int(11) NOT NULL DEFAULT '0' COMMENT '统计浏览量',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0不推荐1推荐',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '添加时间',
  PRIMARY KEY (`photoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='相册图片';

-- Dumping data for table thinksaas-dev.ts_photo: 0 rows
DELETE FROM `ts_photo`;
/*!40000 ALTER TABLE `ts_photo` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_photo` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_photo_album
DROP TABLE IF EXISTS `ts_photo_album`;
CREATE TABLE IF NOT EXISTS `ts_photo_album` (
  `albumid` int(11) NOT NULL AUTO_INCREMENT COMMENT '相册ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `path` char(32) NOT NULL DEFAULT '' COMMENT '相册路径',
  `albumface` varchar(64) NOT NULL DEFAULT '' COMMENT '相册封面',
  `albumname` varchar(64) NOT NULL DEFAULT '' COMMENT '相册名称',
  `albumdesc` varchar(512) NOT NULL DEFAULT '' COMMENT '相册介绍',
  `count_photo` int(11) NOT NULL DEFAULT '0' COMMENT '统计图片数',
  `count_view` int(11) NOT NULL DEFAULT '0' COMMENT '统计浏览量',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `isaudit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0审核1未审核',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '添加时间',
  `uptime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '更新时间',
  PRIMARY KEY (`albumid`),
  KEY `userid` (`userid`),
  KEY `isrecommend` (`isrecommend`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='相册';

-- Dumping data for table thinksaas-dev.ts_photo_album: 0 rows
DELETE FROM `ts_photo_album`;
/*!40000 ALTER TABLE `ts_photo_album` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_photo_album` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_photo_comment
DROP TABLE IF EXISTS `ts_photo_comment`;
CREATE TABLE IF NOT EXISTS `ts_photo_comment` (
  `commentid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增评论ID',
  `referid` int(11) NOT NULL DEFAULT '0' COMMENT '上级评论ID',
  `photoid` int(11) NOT NULL DEFAULT '0' COMMENT '相册ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `content` varchar(512) NOT NULL DEFAULT '' COMMENT '回复内容',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '回复时间',
  PRIMARY KEY (`commentid`),
  KEY `userid` (`userid`),
  KEY `referid` (`referid`,`photoid`),
  KEY `photoid` (`photoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='图片回复/评论';

-- Dumping data for table thinksaas-dev.ts_photo_comment: 0 rows
DELETE FROM `ts_photo_comment`;
/*!40000 ALTER TABLE `ts_photo_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_photo_comment` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_photo_options
DROP TABLE IF EXISTS `ts_photo_options`;
CREATE TABLE IF NOT EXISTS `ts_photo_options` (
  `optionid` int(11) NOT NULL AUTO_INCREMENT COMMENT '选项ID',
  `optionname` varchar(32) NOT NULL DEFAULT '' COMMENT '选项名字',
  `optionvalue` varchar(512) NOT NULL DEFAULT '' COMMENT '选项内容',
  PRIMARY KEY (`optionid`),
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='配置';

-- Dumping data for table thinksaas-dev.ts_photo_options: 4 rows
DELETE FROM `ts_photo_options`;
/*!40000 ALTER TABLE `ts_photo_options` DISABLE KEYS */;
INSERT INTO `ts_photo_options` (`optionid`, `optionname`, `optionvalue`) VALUES
	(1, 'appname', '相册'),
	(2, 'appdesc', '相册'),
	(3, 'appkey', '相册'),
	(4, 'isaudit', '0');
/*!40000 ALTER TABLE `ts_photo_options` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_session
DROP TABLE IF EXISTS `ts_session`;
CREATE TABLE IF NOT EXISTS `ts_session` (
  `session` varchar(64) NOT NULL DEFAULT '' COMMENT 'SESSIONID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `session_expires` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  `ip` char(32) NOT NULL DEFAULT '' COMMENT 'IP',
  `session_data` varchar(512) NOT NULL DEFAULT '' COMMENT 'SESSION数据',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  UNIQUE KEY `session` (`session`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='SESSION';

-- Dumping data for table thinksaas-dev.ts_session: 0 rows
DELETE FROM `ts_session`;
/*!40000 ALTER TABLE `ts_session` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_session` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_slide
DROP TABLE IF EXISTS `ts_slide`;
CREATE TABLE IF NOT EXISTS `ts_slide` (
  `slideid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `typeid` int(11) NOT NULL DEFAULT '0' COMMENT '类型ID默认0为web端轮播',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '标题',
  `info` varchar(128) NOT NULL DEFAULT '' COMMENT '介绍',
  `url` varchar(64) NOT NULL DEFAULT '' COMMENT '链接',
  `path` char(32) NOT NULL DEFAULT '' COMMENT '路径',
  `photo` char(32) NOT NULL DEFAULT '' COMMENT '图片',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`slideid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='轮播图';

-- Dumping data for table thinksaas-dev.ts_slide: 1 rows
DELETE FROM `ts_slide`;
/*!40000 ALTER TABLE `ts_slide` DISABLE KEYS */;
INSERT INTO `ts_slide` (`slideid`, `typeid`, `title`, `info`, `url`, `path`, `photo`, `addtime`) VALUES
	(1, 0, 'ThinkSAAS开源社区', '关注官方微信，时刻获取最新版本更新通知', 'https://www.thinksaas.cn', '0/0', '0/0/1.jpg', 1416533676);
/*!40000 ALTER TABLE `ts_slide` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_system_options
DROP TABLE IF EXISTS `ts_system_options`;
CREATE TABLE IF NOT EXISTS `ts_system_options` (
  `optionname` varchar(32) NOT NULL DEFAULT '' COMMENT '选项名字',
  `optionvalue` varchar(512) NOT NULL DEFAULT '' COMMENT '选项内容',
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='系统管理配置';

-- Dumping data for table thinksaas-dev.ts_system_options: 29 rows
DELETE FROM `ts_system_options`;
/*!40000 ALTER TABLE `ts_system_options` DISABLE KEYS */;
INSERT INTO `ts_system_options` (`optionname`, `optionvalue`) VALUES
	('site_title', 'ThinkSAAS'),
	('site_subtitle', '又一个ThinkSAAS社区'),
	('site_key', 'thinksaas'),
	('site_desc', 'thinksaas'),
	('site_url', 'http://dev.thinksaas.cn/'),
	('link_url', 'http://dev.thinksaas.cn/'),
	('site_pkey', '1282aeb75058638dae3e1565f7c1f51a'),
	('site_email', 'admin@admin.com'),
	('site_icp', '豫ICP备00000000号'),
	('isface', '0'),
	('isinvite', '0'),
	('isverify', '0'),
	('istomy', '0'),
	('isauthcode', '0'),
	('istoken', '0'),
	('isgzip', '0'),
	('timezone', 'Asia/Hong_Kong'),
	('visitor', '0'),
	('publisher', '0'),
	('isallowedit', '0'),
	('isallowdelete', '0'),
	('site_theme', 'sample'),
	('site_urltype', '1'),
	('photo_size', '2'),
	('photo_type', 'jpg,gif,png,jpeg'),
	('attach_size', '2'),
	('attach_type', 'zip,rar,doc,txt,ppt'),
	('dayscoretop', '10'),
	('logo', 'logo.png');
/*!40000 ALTER TABLE `ts_system_options` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_tag
DROP TABLE IF EXISTS `ts_tag`;
CREATE TABLE IF NOT EXISTS `ts_tag` (
  `tagid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `tagname` varchar(32) NOT NULL DEFAULT '' COMMENT '标签名称',
  `count_user` int(11) NOT NULL DEFAULT '0' COMMENT '统计用户标签',
  `count_group` int(11) NOT NULL DEFAULT '0' COMMENT '统计小组标签',
  `count_topic` int(11) NOT NULL DEFAULT '0' COMMENT '统计帖子标签',
  `count_article` int(11) NOT NULL DEFAULT '0' COMMENT '统计文章标签',
  `count_photo` int(11) NOT NULL DEFAULT '0' COMMENT '统计图片使用数',
  `isenable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否可用',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`tagid`),
  UNIQUE KEY `tagname` (`tagname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='标签表';

-- Dumping data for table thinksaas-dev.ts_tag: 0 rows
DELETE FROM `ts_tag`;
/*!40000 ALTER TABLE `ts_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_tag` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_tag_article_index
DROP TABLE IF EXISTS `ts_tag_article_index`;
CREATE TABLE IF NOT EXISTS `ts_tag_article_index` (
  `articleid` int(11) NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `tagid` int(11) NOT NULL DEFAULT '0' COMMENT '标签ID',
  UNIQUE KEY `articleid_2` (`articleid`,`tagid`),
  KEY `articleid` (`articleid`),
  KEY `tagid` (`tagid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='文章标签关联';

-- Dumping data for table thinksaas-dev.ts_tag_article_index: 0 rows
DELETE FROM `ts_tag_article_index`;
/*!40000 ALTER TABLE `ts_tag_article_index` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_tag_article_index` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_tag_group_index
DROP TABLE IF EXISTS `ts_tag_group_index`;
CREATE TABLE IF NOT EXISTS `ts_tag_group_index` (
  `groupid` int(11) NOT NULL DEFAULT '0' COMMENT '小组ID',
  `tagid` int(11) NOT NULL DEFAULT '0' COMMENT '标签ID',
  UNIQUE KEY `groupid_2` (`groupid`,`tagid`),
  KEY `groupid` (`groupid`),
  KEY `tagid` (`tagid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='小组标签关联';

-- Dumping data for table thinksaas-dev.ts_tag_group_index: 0 rows
DELETE FROM `ts_tag_group_index`;
/*!40000 ALTER TABLE `ts_tag_group_index` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_tag_group_index` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_tag_photo_index
DROP TABLE IF EXISTS `ts_tag_photo_index`;
CREATE TABLE IF NOT EXISTS `ts_tag_photo_index` (
  `photoid` int(11) NOT NULL DEFAULT '0' COMMENT '图片ID',
  `tagid` int(11) NOT NULL DEFAULT '0' COMMENT '标签ID',
  UNIQUE KEY `photoid_2` (`photoid`,`tagid`),
  KEY `tagid` (`tagid`),
  KEY `photoid` (`photoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='相册图片标签关联';

-- Dumping data for table thinksaas-dev.ts_tag_photo_index: 0 rows
DELETE FROM `ts_tag_photo_index`;
/*!40000 ALTER TABLE `ts_tag_photo_index` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_tag_photo_index` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_tag_topic_index
DROP TABLE IF EXISTS `ts_tag_topic_index`;
CREATE TABLE IF NOT EXISTS `ts_tag_topic_index` (
  `topicid` int(11) NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `tagid` int(11) NOT NULL DEFAULT '0' COMMENT '标签ID',
  UNIQUE KEY `topicid_2` (`topicid`,`tagid`),
  KEY `topicid` (`topicid`),
  KEY `tagid` (`tagid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='帖子标签关联';

-- Dumping data for table thinksaas-dev.ts_tag_topic_index: 0 rows
DELETE FROM `ts_tag_topic_index`;
/*!40000 ALTER TABLE `ts_tag_topic_index` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_tag_topic_index` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_tag_user_index
DROP TABLE IF EXISTS `ts_tag_user_index`;
CREATE TABLE IF NOT EXISTS `ts_tag_user_index` (
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `tagid` int(11) NOT NULL DEFAULT '0' COMMENT '标签ID',
  UNIQUE KEY `userid_2` (`userid`,`tagid`),
  KEY `userid` (`userid`),
  KEY `tagid` (`tagid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='用户标签关联';

-- Dumping data for table thinksaas-dev.ts_tag_user_index: 0 rows
DELETE FROM `ts_tag_user_index`;
/*!40000 ALTER TABLE `ts_tag_user_index` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_tag_user_index` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_task
DROP TABLE IF EXISTS `ts_task`;
CREATE TABLE IF NOT EXISTS `ts_task` (
  `taskid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增任务ID',
  `taskkey` char(32) NOT NULL DEFAULT '' COMMENT '任务标识',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '任务标题',
  `content` text NOT NULL COMMENT '任务介绍',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '时间',
  PRIMARY KEY (`taskid`),
  UNIQUE KEY `taskkey` (`taskkey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='任务';

-- Dumping data for table thinksaas-dev.ts_task: 0 rows
DELETE FROM `ts_task`;
/*!40000 ALTER TABLE `ts_task` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_task` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_task_user
DROP TABLE IF EXISTS `ts_task_user`;
CREATE TABLE IF NOT EXISTS `ts_task_user` (
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `taskkey` char(32) NOT NULL DEFAULT '' COMMENT '任务key',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '时间',
  UNIQUE KEY `userid` (`userid`,`taskkey`),
  KEY `taskkey` (`taskkey`),
  KEY `userid_2` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='用户任务关联';

-- Dumping data for table thinksaas-dev.ts_task_user: 0 rows
DELETE FROM `ts_task_user`;
/*!40000 ALTER TABLE `ts_task_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_task_user` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user
DROP TABLE IF EXISTS `ts_user`;
CREATE TABLE IF NOT EXISTS `ts_user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `pwd` char(32) NOT NULL DEFAULT '' COMMENT '用户密码',
  `salt` char(32) NOT NULL DEFAULT '' COMMENT '加点盐',
  `email` varchar(64) NOT NULL DEFAULT '' COMMENT '用户email',
  `resetpwd` char(32) NOT NULL DEFAULT '' COMMENT '重设密码',
  `code` char(32) NOT NULL DEFAULT '' COMMENT '邮箱验证码',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `pwd` (`pwd`,`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='用户';

-- Dumping data for table thinksaas-dev.ts_user: 0 rows
DELETE FROM `ts_user`;
/*!40000 ALTER TABLE `ts_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user_follow
DROP TABLE IF EXISTS `ts_user_follow`;
CREATE TABLE IF NOT EXISTS `ts_user_follow` (
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `userid_follow` int(11) NOT NULL DEFAULT '0' COMMENT '被关注的用户ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  UNIQUE KEY `userid_2` (`userid`,`userid_follow`),
  KEY `userid` (`userid`),
  KEY `userid_follow` (`userid_follow`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='用户关注跟随';

-- Dumping data for table thinksaas-dev.ts_user_follow: 0 rows
DELETE FROM `ts_user_follow`;
/*!40000 ALTER TABLE `ts_user_follow` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user_follow` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user_gb
DROP TABLE IF EXISTS `ts_user_gb`;
CREATE TABLE IF NOT EXISTS `ts_user_gb` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增留言ID',
  `reid` int(11) NOT NULL DEFAULT '0' COMMENT '回复留言ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '留言用户ID',
  `touserid` int(11) NOT NULL DEFAULT '0' COMMENT '被留言用户ID',
  `content` text NOT NULL COMMENT '内容',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `touserid` (`touserid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='留言表';

-- Dumping data for table thinksaas-dev.ts_user_gb: 0 rows
DELETE FROM `ts_user_gb`;
/*!40000 ALTER TABLE `ts_user_gb` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user_gb` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user_group
DROP TABLE IF EXISTS `ts_user_group`;
CREATE TABLE IF NOT EXISTS `ts_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增用户组ID',
  `groupname` char(32) NOT NULL DEFAULT '' COMMENT '用户组名字',
  `view` tinyint(1) NOT NULL DEFAULT '0' COMMENT '查看权限0有1没有',
  `delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除权限',
  `edit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '修改权限',
  `create` tinyint(1) NOT NULL DEFAULT '0' COMMENT '写入权限',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '积分挂钩',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='用户组';

-- Dumping data for table thinksaas-dev.ts_user_group: 0 rows
DELETE FROM `ts_user_group`;
/*!40000 ALTER TABLE `ts_user_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user_group` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user_info
DROP TABLE IF EXISTS `ts_user_info`;
CREATE TABLE IF NOT EXISTS `ts_user_info` (
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `locationid` int(11) NOT NULL DEFAULT '0' COMMENT '同城ID',
  `fuserid` int(11) NOT NULL DEFAULT '0' COMMENT '来自邀请用户',
  `username` char(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `email` varchar(64) NOT NULL DEFAULT '' COMMENT 'Email邮箱',
  `sex` char(32) NOT NULL DEFAULT '女' COMMENT '性别',
  `phone` char(16) NOT NULL DEFAULT '' COMMENT '电话号码',
  `roleid` int(11) NOT NULL DEFAULT '1' COMMENT '角色ID',
  `province` varchar(64) NOT NULL DEFAULT '' COMMENT '省/直辖市/自治区',
  `city` varchar(64) NOT NULL DEFAULT '' COMMENT '市县区',
  `district` varchar(64) NOT NULL DEFAULT '' COMMENT '区域',
  `path` char(32) NOT NULL DEFAULT '' COMMENT '头像路径',
  `face` char(64) NOT NULL DEFAULT '' COMMENT '会员头像',
  `signed` varchar(64) NOT NULL DEFAULT '' COMMENT '签名',
  `blog` char(32) NOT NULL DEFAULT '' COMMENT '博客',
  `about` varchar(255) NOT NULL DEFAULT '' COMMENT '关于我',
  `ip` char(32) NOT NULL DEFAULT '' COMMENT '登陆IP',
  `address` varchar(64) NOT NULL DEFAULT '' COMMENT '地址',
  `comefrom` tinyint(1) NOT NULL DEFAULT '0' COMMENT '注册来自0web1手机客户端',
  `allscore` int(11) NOT NULL DEFAULT '0' COMMENT '所有获得的总积分',
  `count_score` int(11) NOT NULL DEFAULT '0' COMMENT '统计积分',
  `count_follow` int(11) NOT NULL DEFAULT '0' COMMENT '统计用户跟随的',
  `count_followed` int(11) NOT NULL DEFAULT '0' COMMENT '统计用户被跟随的',
  `count_group` int(11) NOT NULL DEFAULT '0' COMMENT '统计小组数',
  `count_topic` int(11) NOT NULL DEFAULT '0' COMMENT '统计帖子',
  `isadmin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是管理员',
  `isenable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用：0启用1禁用',
  `isverify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未验证1验证',
  `isrenzheng` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否认证0未认证1认证',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `verifycode` char(11) NOT NULL DEFAULT '' COMMENT '验证码',
  `autologin` char(128) NOT NULL DEFAULT '' COMMENT '自动登陆',
  `signin` int(11) NOT NULL DEFAULT '0' COMMENT '签到时间',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '登陆时间',
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `userid` (`userid`),
  UNIQUE KEY `email_2` (`email`,`autologin`),
  KEY `fuserid` (`fuserid`),
  KEY `isrecommend` (`isrecommend`),
  KEY `isrenzheng` (`isrenzheng`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='用户';

-- Dumping data for table thinksaas-dev.ts_user_info: 0 rows
DELETE FROM `ts_user_info`;
/*!40000 ALTER TABLE `ts_user_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user_info` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user_invites
DROP TABLE IF EXISTS `ts_user_invites`;
CREATE TABLE IF NOT EXISTS `ts_user_invites` (
  `inviteid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增邀请ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `invitecode` char(32) NOT NULL DEFAULT '' COMMENT '邀请码',
  `isused` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否使用',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`inviteid`),
  UNIQUE KEY `invitecode` (`invitecode`),
  KEY `isused` (`isused`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='用户邀请码';

-- Dumping data for table thinksaas-dev.ts_user_invites: 0 rows
DELETE FROM `ts_user_invites`;
/*!40000 ALTER TABLE `ts_user_invites` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user_invites` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user_open
DROP TABLE IF EXISTS `ts_user_open`;
CREATE TABLE IF NOT EXISTS `ts_user_open` (
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `sitename` varchar(64) NOT NULL DEFAULT '' COMMENT '连接网站名称',
  `openid` varchar(64) NOT NULL DEFAULT '' COMMENT 'openid',
  `access_token` varchar(128) NOT NULL DEFAULT '' COMMENT 'access_token',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  UNIQUE KEY `userid_2` (`userid`,`sitename`),
  UNIQUE KEY `sitename` (`sitename`,`openid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='第三方连接登录';

-- Dumping data for table thinksaas-dev.ts_user_open: 0 rows
DELETE FROM `ts_user_open`;
/*!40000 ALTER TABLE `ts_user_open` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user_open` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user_options
DROP TABLE IF EXISTS `ts_user_options`;
CREATE TABLE IF NOT EXISTS `ts_user_options` (
  `optionname` varchar(32) NOT NULL DEFAULT '' COMMENT '选项名字',
  `optionvalue` varchar(512) NOT NULL DEFAULT '' COMMENT '选项内容',
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='配置';

-- Dumping data for table thinksaas-dev.ts_user_options: 6 rows
DELETE FROM `ts_user_options`;
/*!40000 ALTER TABLE `ts_user_options` DISABLE KEYS */;
INSERT INTO `ts_user_options` (`optionname`, `optionvalue`) VALUES
	('appname', '用户'),
	('appdesc', '用户中心'),
	('appkey', '用户'),
	('isenable', '0'),
	('isgroup', ''),
	('banuser', '官方用户|官方团队');
/*!40000 ALTER TABLE `ts_user_options` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user_role
DROP TABLE IF EXISTS `ts_user_role`;
CREATE TABLE IF NOT EXISTS `ts_user_role` (
  `roleid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增角色ID',
  `rolename` char(32) NOT NULL DEFAULT '' COMMENT '角色名称',
  `score_start` int(11) NOT NULL DEFAULT '0' COMMENT '积分开始',
  `score_end` int(11) NOT NULL DEFAULT '0' COMMENT '积分结束',
  PRIMARY KEY (`roleid`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COMMENT='角色';

-- Dumping data for table thinksaas-dev.ts_user_role: 17 rows
DELETE FROM `ts_user_role`;
/*!40000 ALTER TABLE `ts_user_role` DISABLE KEYS */;
INSERT INTO `ts_user_role` (`roleid`, `rolename`, `score_start`, `score_end`) VALUES
	(1, '列兵', 0, 5000),
	(2, '下士', 5000, 20000),
	(3, '中士', 20000, 40000),
	(4, '上士', 40000, 80000),
	(5, '三级准尉', 80000, 160000),
	(6, '二级准尉', 160000, 320000),
	(7, '一级准尉', 320000, 640000),
	(8, '少尉', 640000, 1280000),
	(9, '中尉', 1280000, 2560000),
	(10, '上尉', 2560000, 5120000),
	(11, '少校', 5120000, 10240000),
	(12, '中校', 10240000, 20480000),
	(13, '上校', 20480000, 40960000),
	(14, '准将', 40960000, 81920000),
	(15, '少将', 81920000, 123840000),
	(16, '中将', 123840000, 327680000),
	(17, '上将', 327680000, 0);
/*!40000 ALTER TABLE `ts_user_role` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user_score
DROP TABLE IF EXISTS `ts_user_score`;
CREATE TABLE IF NOT EXISTS `ts_user_score` (
  `scoreid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增积分ID',
  `scorekey` varchar(64) NOT NULL DEFAULT '' COMMENT '积分key',
  `scorename` varchar(64) NOT NULL DEFAULT '' COMMENT '积分名称',
  `app` char(32) NOT NULL DEFAULT '' COMMENT 'APP',
  `action` char(32) NOT NULL DEFAULT '' COMMENT 'ACTION',
  `mg` char(32) NOT NULL DEFAULT '' COMMENT 'MG',
  `ts` char(32) NOT NULL DEFAULT '' COMMENT 'TS',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '积分数',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0加积分1减积分',
  PRIMARY KEY (`scoreid`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COMMENT='用户积分设置表';

-- Dumping data for table thinksaas-dev.ts_user_score: 16 rows
DELETE FROM `ts_user_score`;
/*!40000 ALTER TABLE `ts_user_score` DISABLE KEYS */;
INSERT INTO `ts_user_score` (`scoreid`, `scorekey`, `scorename`, `app`, `action`, `mg`, `ts`, `score`, `status`) VALUES
	(1, 'user_register', '用户注册', 'user', 'register', '', 'do', 10, 0),
	(2, 'user_login', '用户登陆', 'user', 'login', '', 'do', 5, 0),
	(3, 'group_topic_add', '用户小组发帖', 'group', 'add', '', 'do', 10, 0),
	(4, 'group_topic_comment', '用户小组帖子评论', 'group', 'comment', '', 'do', 5, 0),
	(5, 'attach_upload', '资料上传', 'attach', 'upload', '', 'do', 10, 0),
	(6, 'user_signin', '用户签到', 'user', 'signin', '', '', 5, 0),
	(7, 'group_topic_delete', '删除帖子', 'group', 'do', '', 'deltopic', 5, 1),
	(8, 'article_add', '发布文章', 'article', 'add', '', 'do', 5, 0),
	(9, 'article_delete', '删除文章', 'article', 'delete', '', '', 5, 1),
	(11, 'article_admin_post_isaudit0', '后台文章审核通过', 'article', 'admin', 'post', 'isaudit0', 5, 0),
	(12, 'article_admin_post_isaudit1', '后台文章审核不通过', 'article', 'admin', 'post', 'isaudit1', 5, 1),
	(13, 'ask_admin_topic_isaudit0', '后台问题审核通过', 'ask', 'admin', 'topic', 'isaudit0', 5, 0),
	(14, 'ask_admin_topic_isaudit1', '后台问题审核不通过', 'ask', 'admin', 'topic', 'isaudit1', 5, 1),
	(15, 'ask_new_do', '前台发布问题', 'ask', 'new', '', 'do', 5, 0),
	(16, 'ask_ajax_ask2commentid', '前台问题采纳', 'ask', 'ajax', '', 'ask2commentid', 5, 0),
	(17, 'article_admin_post_delete', '后台文章删除', 'article', 'admin', 'post', 'delete', 5, 1);
/*!40000 ALTER TABLE `ts_user_score` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_user_score_log
DROP TABLE IF EXISTS `ts_user_score_log`;
CREATE TABLE IF NOT EXISTS `ts_user_score_log` (
  `logid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增积分记录ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `scorename` varchar(64) NOT NULL DEFAULT '' COMMENT '积分说明',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '得分',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0增加1减少',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '积分时间',
  PRIMARY KEY (`logid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='用户积分记录';

-- Dumping data for table thinksaas-dev.ts_user_score_log: 0 rows
DELETE FROM `ts_user_score_log`;
/*!40000 ALTER TABLE `ts_user_score_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user_score_log` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_weibo
DROP TABLE IF EXISTS `ts_weibo`;
CREATE TABLE IF NOT EXISTS `ts_weibo` (
  `weiboid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增唠叨ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `locationid` int(11) NOT NULL DEFAULT '0' COMMENT '同城ID',
  `content` text NOT NULL COMMENT '内容',
  `count_comment` int(11) NOT NULL DEFAULT '0' COMMENT '统计评论数',
  `path` char(32) NOT NULL DEFAULT '' COMMENT '路径',
  `photo` char(32) NOT NULL DEFAULT '' COMMENT '图片',
  `isaudit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '添加时间',
  `uptime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '更新时间',
  PRIMARY KEY (`weiboid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='唠叨';

-- Dumping data for table thinksaas-dev.ts_weibo: 0 rows
DELETE FROM `ts_weibo`;
/*!40000 ALTER TABLE `ts_weibo` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_weibo` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_weibo_comment
DROP TABLE IF EXISTS `ts_weibo_comment`;
CREATE TABLE IF NOT EXISTS `ts_weibo_comment` (
  `commentid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增评论ID',
  `weiboid` int(11) NOT NULL DEFAULT '0' COMMENT '唠叨ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `touserid` int(11) NOT NULL DEFAULT '0' COMMENT '回复用户ID',
  `isread` tinyint(1) NOT NULL DEFAULT '0',
  `content` text NOT NULL COMMENT '内容',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '添加时间',
  PRIMARY KEY (`commentid`),
  KEY `touserid` (`touserid`,`isread`),
  KEY `noteid` (`weiboid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='唠叨回复';

-- Dumping data for table thinksaas-dev.ts_weibo_comment: 0 rows
DELETE FROM `ts_weibo_comment`;
/*!40000 ALTER TABLE `ts_weibo_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_weibo_comment` ENABLE KEYS */;

-- Dumping structure for table thinksaas-dev.ts_weibo_options
DROP TABLE IF EXISTS `ts_weibo_options`;
CREATE TABLE IF NOT EXISTS `ts_weibo_options` (
  `optionname` varchar(32) NOT NULL DEFAULT '' COMMENT '选项名字',
  `optionvalue` varchar(512) NOT NULL DEFAULT '' COMMENT '选项内容',
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='唠叨配置';

-- Dumping data for table thinksaas-dev.ts_weibo_options: 3 rows
DELETE FROM `ts_weibo_options`;
/*!40000 ALTER TABLE `ts_weibo_options` DISABLE KEYS */;
INSERT INTO `ts_weibo_options` (`optionname`, `optionvalue`) VALUES
	('appname', '唠叨'),
	('appdesc', '唠叨'),
	('appkey', '唠叨');
/*!40000 ALTER TABLE `ts_weibo_options` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;