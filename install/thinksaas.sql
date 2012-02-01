-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 02 月 01 日 10:18
-- 服务器版本: 5.5.8
-- PHP 版本: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `thinksaas`
--

-- --------------------------------------------------------

--
-- 表的结构 `ts_group`
--

CREATE TABLE IF NOT EXISTS `ts_group` (
  `groupid` int(11) NOT NULL AUTO_INCREMENT COMMENT '小组ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `groupname` char(32) NOT NULL DEFAULT '' COMMENT '群组名字',
  `groupname_en` char(32) NOT NULL DEFAULT '' COMMENT '小组英文名称',
  `groupdesc` text NOT NULL COMMENT '小组介绍',
  `path` char(32) NOT NULL DEFAULT '' COMMENT '图标路径',
  `groupicon` char(32) DEFAULT '' COMMENT '小组图标',
  `count_topic` int(11) NOT NULL DEFAULT '0' COMMENT '帖子统计',
  `count_topic_today` int(11) NOT NULL DEFAULT '0' COMMENT '统计今天发帖',
  `count_user` int(11) NOT NULL DEFAULT '0' COMMENT '小组成员数',
  `joinway` tinyint(1) NOT NULL DEFAULT '0' COMMENT '加入方式',
  `role_leader` char(32) NOT NULL DEFAULT '组长' COMMENT '组长角色名称',
  `role_admin` char(32) NOT NULL DEFAULT '管理员' COMMENT '管理员角色名称',
  `role_user` char(32) NOT NULL DEFAULT '成员' COMMENT '成员角色名称',
  `addtime` int(11) DEFAULT '0' COMMENT '创建时间',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `isopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否公开或者私密',
  `isaudit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核',
  `ispost` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否允许会员发帖',
  `isshow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  PRIMARY KEY (`groupid`),
  KEY `userid` (`userid`),
  KEY `isshow` (`isshow`),
  KEY `groupname` (`groupname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='小组' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `ts_group`
--

INSERT INTO `ts_group` (`groupid`, `userid`, `groupname`, `groupname_en`, `groupdesc`, `path`, `groupicon`, `count_topic`, `count_topic_today`, `count_user`, `joinway`, `role_leader`, `role_admin`, `role_user`, `addtime`, `isrecommend`, `isopen`, `isaudit`, `ispost`, `isshow`, `uptime`) VALUES
(1, 1, '默认小组', '', '默认小组', '', '', 1, 1, 1, 0, '组长', '管理员', '成员', 1321804548, 1, 0, 0, 0, 0, 1328089131);

-- --------------------------------------------------------

--
-- 表的结构 `ts_group_cates`
--

CREATE TABLE IF NOT EXISTS `ts_group_cates` (
  `cateid` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `catename` char(32) NOT NULL DEFAULT '' COMMENT '分类名字',
  `count_group` int(11) NOT NULL DEFAULT '0' COMMENT '群组个数',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  PRIMARY KEY (`cateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ts_group_cates`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_group_cates_index`
--

CREATE TABLE IF NOT EXISTS `ts_group_cates_index` (
  `groupid` int(11) NOT NULL DEFAULT '0' COMMENT '小组ID',
  `cateid` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  UNIQUE KEY `groupid_2` (`groupid`,`cateid`),
  KEY `groupid` (`groupid`),
  KEY `cateid` (`cateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='小组分类索引';

--
-- 转存表中的数据 `ts_group_cates_index`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_group_links`
--

CREATE TABLE IF NOT EXISTS `ts_group_links` (
  `groupid` int(11) NOT NULL DEFAULT '0',
  `linkid` int(11) NOT NULL DEFAULT '0',
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ts_group_links`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_group_options`
--

CREATE TABLE IF NOT EXISTS `ts_group_options` (
  `optionname` char(12) NOT NULL DEFAULT '' COMMENT '选项名字',
  `optionvalue` char(255) NOT NULL DEFAULT '' COMMENT '选项内容',
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='配置';

--
-- 转存表中的数据 `ts_group_options`
--

INSERT INTO `ts_group_options` (`optionname`, `optionvalue`) VALUES
('appname', '小组'),
('appdesc', 'ThinkSAAS小组'),
('isenable', '0'),
('iscreate', '0'),
('isaudit', '1'),
('iscate', '1'),
('ismode', '0');

-- --------------------------------------------------------

--
-- 表的结构 `ts_group_topics`
--

CREATE TABLE IF NOT EXISTS `ts_group_topics` (
  `topicid` int(11) NOT NULL AUTO_INCREMENT COMMENT '话题ID',
  `typeid` int(11) NOT NULL DEFAULT '0' COMMENT '帖子分类ID',
  `groupid` int(11) NOT NULL DEFAULT '0' COMMENT '小组ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` char(64) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `count_comment` int(11) NOT NULL DEFAULT '0' COMMENT '回复统计',
  `count_view` int(11) NOT NULL DEFAULT '0' COMMENT '帖子展示数',
  `count_attach` int(11) NOT NULL DEFAULT '0' COMMENT '统计附件',
  `istop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `isshow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示',
  `iscomment` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否允许评论',
  `isphoto` tinyint(1) NOT NULL DEFAULT '0',
  `isattach` tinyint(1) NOT NULL DEFAULT '0',
  `isnotice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否通知',
  `isposts` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否精华帖子',
  `addtime` int(11) DEFAULT '0' COMMENT '创建时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`topicid`),
  KEY `groupid` (`groupid`),
  KEY `userid` (`userid`),
  KEY `title` (`title`),
  KEY `groupid_2` (`groupid`,`isshow`),
  KEY `typeid` (`typeid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='小组话题' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `ts_group_topics`
--

INSERT INTO `ts_group_topics` (`topicid`, `typeid`, `groupid`, `userid`, `title`, `content`, `count_comment`, `count_view`, `count_attach`, `istop`, `isshow`, `iscomment`, `isphoto`, `isattach`, `isnotice`, `isposts`, `addtime`, `uptime`) VALUES
(1, 2, 1, 1, '我看可以啊', '我看可以啊', 1, 25, 0, 0, 0, 0, 0, 0, 0, 0, 1328089131, 1328091106);

-- --------------------------------------------------------

--
-- 表的结构 `ts_group_topics_collects`
--

CREATE TABLE IF NOT EXISTS `ts_group_topics_collects` (
  `userid` int(11) NOT NULL DEFAULT '0',
  `topicid` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '收藏时间',
  UNIQUE KEY `userid_2` (`userid`,`topicid`),
  KEY `userid` (`userid`),
  KEY `topicid` (`topicid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='帖子收藏';

--
-- 转存表中的数据 `ts_group_topics_collects`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_group_topics_comments`
--

CREATE TABLE IF NOT EXISTS `ts_group_topics_comments` (
  `commentid` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `referid` int(11) NOT NULL DEFAULT '0',
  `topicid` int(11) NOT NULL DEFAULT '0' COMMENT '话题ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `content` text NOT NULL COMMENT '回复内容',
  `addtime` int(11) DEFAULT '0' COMMENT '回复时间',
  PRIMARY KEY (`commentid`),
  KEY `topicid` (`topicid`),
  KEY `userid` (`userid`),
  KEY `referid` (`referid`,`topicid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='话题回复/评论' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `ts_group_topics_comments`
--

INSERT INTO `ts_group_topics_comments` (`commentid`, `referid`, `topicid`, `userid`, `content`, `addtime`) VALUES
(1, 0, 1, 1, '可以啊', 1328091106);

-- --------------------------------------------------------

--
-- 表的结构 `ts_group_topics_type`
--

CREATE TABLE IF NOT EXISTS `ts_group_topics_type` (
  `typeid` int(11) NOT NULL AUTO_INCREMENT COMMENT '帖子分类ID',
  `groupid` int(11) NOT NULL DEFAULT '0' COMMENT '小组ID',
  `typename` char(32) NOT NULL DEFAULT '' COMMENT '帖子分类名称',
  PRIMARY KEY (`typeid`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='帖子分类' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `ts_group_topics_type`
--

INSERT INTO `ts_group_topics_type` (`typeid`, `groupid`, `typename`) VALUES
(2, 1, '音乐');

-- --------------------------------------------------------

--
-- 表的结构 `ts_group_users`
--

CREATE TABLE IF NOT EXISTS `ts_group_users` (
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `groupid` int(11) NOT NULL DEFAULT '0' COMMENT '群组ID',
  `isadmin` int(11) NOT NULL DEFAULT '0' COMMENT '是否管理员',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '加入时间',
  UNIQUE KEY `userid_2` (`userid`,`groupid`),
  KEY `userid` (`userid`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='群组和用户对应关系';

--
-- 转存表中的数据 `ts_group_users`
--

INSERT INTO `ts_group_users` (`userid`, `groupid`, `isadmin`, `addtime`) VALUES
(1, 1, 0, 1321804548);

-- --------------------------------------------------------

--
-- 表的结构 `ts_home_info`
--

CREATE TABLE IF NOT EXISTS `ts_home_info` (
  `infoid` int(11) NOT NULL AUTO_INCREMENT,
  `infokey` char(32) NOT NULL DEFAULT '',
  `infocontent` text NOT NULL,
  PRIMARY KEY (`infoid`),
  UNIQUE KEY `infokey` (`infokey`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `ts_home_info`
--

INSERT INTO `ts_home_info` (`infoid`, `infokey`, `infocontent`) VALUES
(1, 'about', '<p>ThinkSAAS 简单有责任</p>\r\n\r\n<p>ThinkSAAS将不断完善社区系统的建设，以简单和高扩展的形式为用户提供各种不同功能的社区应用，ThinkSAAS将不断满足用户对社区建设和运营等方面的需求。</p>\r\n\r\n<p>ThinkSAAS保安装，保更新，保升级的三保服务（如果你在安装，更新，升级过程中出现问题，请随时联系我的QQ：1078700473）</p>\r\n<p>\r\nThinkSAAS每周六一个小更新，每四周更新一个版本，请在每个周末及时关注ThinkSAAS更新。\r\n</p>\r\n\r\n<p>官方网站：<a href="http://www.thinksaas.cn/">http://www.thinksaas.cn</a></p>'),
(2, 'contact', '<p>Email:thinksaas#qq.com(#换@)</p>\r\n<p>QQ:1078700473</p>\r\n<p>Location:北京</p>'),
(3, 'agreement', '<p>1、ThinkSAAS免费开源</p>\r\n<p>2、你可以免费使用ThinkSAAS</p>\r\n<p>3、你可以在ThinkSAAS基础上进行二次开发和修改</p>\r\n<p>4、你可以拿ThinkSAAS建设你的商业运营网站</p>\r\n\r\n<p>5、在ThinkSAAS未进行商业运作之前，ThinkSAAS(邱君)将拥有对ThinkSAAS的所有权，任何个人，公司和组织不得以任何形式和目的侵犯ThinkSAAS的版权和著作权</p>\r\n<p>6、ThinkSAAS拥有对此协议的修改和不断完善。</p>'),
(4, 'privacy', '<p>ThinkSAAS（thinksaas.cn）以此声明对本站用户隐私保护的许诺。ThinkSAAS的隐私声明正在不断改进中，随着本站服务范围的扩大，会随时更新隐私声明。我们欢迎你随时查看隐私声明。</p>');

-- --------------------------------------------------------

--
-- 表的结构 `ts_mail_options`
--

CREATE TABLE IF NOT EXISTS `ts_mail_options` (
  `optionid` int(11) NOT NULL AUTO_INCREMENT COMMENT '选项ID',
  `optionname` char(12) NOT NULL DEFAULT '' COMMENT '选项名字',
  `optionvalue` char(255) NOT NULL DEFAULT '' COMMENT '选项内容',
  PRIMARY KEY (`optionid`),
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='配置' AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `ts_mail_options`
--

INSERT INTO `ts_mail_options` (`optionid`, `optionname`, `optionvalue`) VALUES
(1, 'appname', '邮件'),
(2, 'appdesc', 'ThinkSAAS邮件'),
(3, 'isenable', '0'),
(4, 'mailhost', 'smtp.qq.com'),
(5, 'mailport', '25'),
(6, 'mailuser', 'user@qq.com'),
(7, 'mailpwd', '123456');

-- --------------------------------------------------------

--
-- 表的结构 `ts_message`
--

CREATE TABLE IF NOT EXISTS `ts_message` (
  `messageid` int(11) NOT NULL AUTO_INCREMENT COMMENT '消息ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '发送用户ID',
  `touserid` int(11) NOT NULL DEFAULT '0' COMMENT '接收消息的用户ID',
  `content` text NOT NULL COMMENT '内容',
  `isread` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已读',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`messageid`),
  KEY `touserid` (`touserid`,`isread`),
  KEY `userid` (`userid`,`touserid`,`isread`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='短消息表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `ts_message`
--

INSERT INTO `ts_message` (`messageid`, `userid`, `touserid`, `content`, `isread`, `addtime`) VALUES
(1, 0, 2, '亲爱的 thinksaas ：<br />您成功加入了 ThinkSAAS<br />在遵守本站的规定的同时，享受您的愉快之旅吧!', 1, 1328080799);

-- --------------------------------------------------------

--
-- 表的结构 `ts_system_options`
--

CREATE TABLE IF NOT EXISTS `ts_system_options` (
  `optionid` int(11) NOT NULL AUTO_INCREMENT COMMENT '选项ID',
  `optionname` char(32) NOT NULL DEFAULT '' COMMENT '选项名字',
  `optionvalue` char(255) NOT NULL DEFAULT '' COMMENT '选项内容',
  PRIMARY KEY (`optionid`),
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='系统管理配置' AUTO_INCREMENT=15 ;

--
-- 转存表中的数据 `ts_system_options`
--

INSERT INTO `ts_system_options` (`optionid`, `optionname`, `optionvalue`) VALUES
(1, 'site_title', 'ThinkSAAS'),
(2, 'site_subtitle', '又一个ThinkSAAS社区'),
(3, 'site_url', 'http://localhost/thinksaas/ThinkSAAS/'),
(4, 'site_email', 'admin@admin.com'),
(6, 'site_icp', '京ICP备09050100号'),
(7, 'isface', '0'),
(8, 'site_key', 'thinksaas'),
(9, 'site_desc', '又一个ThinkSAAS社区'),
(10, 'site_theme', 'miliao'),
(11, 'site_urltype', '1'),
(12, 'isgzip', '0'),
(13, 'timezone', 'Asia/Hong_Kong'),
(14, 'lang', 'zh_cn');

-- --------------------------------------------------------

--
-- 表的结构 `ts_user`
--

CREATE TABLE IF NOT EXISTS `ts_user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `pwd` char(32) NOT NULL DEFAULT '' COMMENT '用户密码',
  `salt` char(32) NOT NULL DEFAULT '' COMMENT '加点盐',
  `email` char(32) NOT NULL DEFAULT '' COMMENT '用户email',
  `resetpwd` char(32) NOT NULL DEFAULT '' COMMENT '重设密码',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `pwd` (`pwd`,`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `ts_user`
--

INSERT INTO `ts_user` (`userid`, `pwd`, `salt`, `email`, `resetpwd`) VALUES
(1, 'e10adc3949ba59abbe56e057f20f883e', '', 'admin@admin.com', ''),
(2, '3df36e2607b456ed3472c604245ece60', 'dd1f2ad3a1bf536bc3d7ea8628152941', 'thinksaas@qq.com', '');

-- --------------------------------------------------------

--
-- 表的结构 `ts_user_info`
--

CREATE TABLE IF NOT EXISTS `ts_user_info` (
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `fuserid` int(11) NOT NULL DEFAULT '0' COMMENT '来自邀请用户',
  `username` char(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `email` char(32) NOT NULL DEFAULT '',
  `path` char(32) NOT NULL DEFAULT '' COMMENT '头像路径',
  `face` char(64) NOT NULL DEFAULT '' COMMENT '会员头像',
  `blog` char(32) NOT NULL DEFAULT '' COMMENT '博客',
  `about` char(255) NOT NULL DEFAULT '' COMMENT '关于我',
  `qq_openid` char(32) NOT NULL DEFAULT '',
  `qq_token` char(32) NOT NULL DEFAULT '',
  `qq_secret` char(32) NOT NULL DEFAULT '',
  `isadmin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是管理员',
  `isenable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用：0启用1禁用',
  `isverify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未验证1验证',
  `verifycode` char(11) NOT NULL DEFAULT '' COMMENT '验证码',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `uptime` int(11) DEFAULT '0' COMMENT '登陆时间',
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `userid` (`userid`),
  UNIQUE KEY `username` (`username`),
  KEY `qq_openid` (`qq_openid`),
  KEY `fuserid` (`fuserid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户';

--
-- 转存表中的数据 `ts_user_info`
--

INSERT INTO `ts_user_info` (`userid`, `fuserid`, `username`, `email`, `path`, `face`, `blog`, `about`, `qq_openid`, `qq_token`, `qq_secret`, `isadmin`, `isenable`, `isverify`, `verifycode`, `addtime`, `uptime`) VALUES
(1, 0, 'admin', 'admin@admin.com', '', '', 'http://www.thinksaas.cn', 'I love ThinkSAAS', '', '', '', 1, 0, 0, '', 1324865594, 1328091099),
(2, 0, 'thinksaas', 'thinksaas@qq.com', '', '', '', '', '', '', '', 0, 0, 0, '', 1328080799, 1328080799);

-- --------------------------------------------------------

--
-- 表的结构 `ts_user_options`
--

CREATE TABLE IF NOT EXISTS `ts_user_options` (
  `optionid` int(11) NOT NULL AUTO_INCREMENT COMMENT '选项ID',
  `optionname` char(12) NOT NULL DEFAULT '' COMMENT '选项名字',
  `optionvalue` char(255) NOT NULL DEFAULT '' COMMENT '选项内容',
  PRIMARY KEY (`optionid`),
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='配置' AUTO_INCREMENT=9 ;

--
-- 转存表中的数据 `ts_user_options`
--

INSERT INTO `ts_user_options` (`optionid`, `optionname`, `optionvalue`) VALUES
(1, 'appname', '用户'),
(2, 'appdesc', '用户中心'),
(3, 'isenable', '0'),
(4, 'isregister', '0'),
(5, 'isvalidate', '0'),
(6, 'isrewrite', '0'),
(7, 'isauthcode', '0'),
(8, 'isgroup', '');
