/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- 导出  表 d_thinksaas.ts_anti_email 结构
DROP TABLE IF EXISTS `ts_anti_email`;
CREATE TABLE IF NOT EXISTS `ts_anti_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `email` varchar(64) NOT NULL DEFAULT '' COMMENT 'Email',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='过滤Email';

-- 正在导出表  d_thinksaas.ts_anti_email 的数据：0 rows
DELETE FROM `ts_anti_email`;
/*!40000 ALTER TABLE `ts_anti_email` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_anti_email` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_anti_ip 结构
DROP TABLE IF EXISTS `ts_anti_ip`;
CREATE TABLE IF NOT EXISTS `ts_anti_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `ip` varchar(64) NOT NULL DEFAULT '' COMMENT 'IP地址',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='垃圾IP';

-- 正在导出表  d_thinksaas.ts_anti_ip 的数据：0 rows
DELETE FROM `ts_anti_ip`;
/*!40000 ALTER TABLE `ts_anti_ip` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_anti_ip` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_anti_phone 结构
DROP TABLE IF EXISTS `ts_anti_phone`;
CREATE TABLE IF NOT EXISTS `ts_anti_phone` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `phone` varchar(64) NOT NULL DEFAULT '' COMMENT '手机号',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `phone` (`phone`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='过滤手机号';

-- 正在导出表  d_thinksaas.ts_anti_phone 的数据：0 rows
DELETE FROM `ts_anti_phone`;
/*!40000 ALTER TABLE `ts_anti_phone` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_anti_phone` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_anti_report 结构
DROP TABLE IF EXISTS `ts_anti_report`;
CREATE TABLE IF NOT EXISTS `ts_anti_report` (
  `reportid` int(11) NOT NULL AUTO_INCREMENT COMMENT '举报ID',
  `url` varchar(128) NOT NULL DEFAULT '' COMMENT '举报链接',
  `content` varchar(512) NOT NULL DEFAULT '' COMMENT '举报内容',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '时间',
  PRIMARY KEY (`reportid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='内容举报';

-- 正在导出表  d_thinksaas.ts_anti_report 的数据：0 rows
DELETE FROM `ts_anti_report`;
/*!40000 ALTER TABLE `ts_anti_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_anti_report` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_anti_user 结构
DROP TABLE IF EXISTS `ts_anti_user`;
CREATE TABLE IF NOT EXISTS `ts_anti_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='过滤用户';

-- 正在导出表  d_thinksaas.ts_anti_user 的数据：0 rows
DELETE FROM `ts_anti_user`;
/*!40000 ALTER TABLE `ts_anti_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_anti_user` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_anti_word 结构
DROP TABLE IF EXISTS `ts_anti_word`;
CREATE TABLE IF NOT EXISTS `ts_anti_word` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(64) NOT NULL DEFAULT '' COMMENT '敏感词',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='敏感词';

-- 正在导出表  d_thinksaas.ts_anti_word 的数据：1 rows
DELETE FROM `ts_anti_word`;
/*!40000 ALTER TABLE `ts_anti_word` DISABLE KEYS */;
INSERT INTO `ts_anti_word` (`id`, `word`, `addtime`) VALUES
	(1, '迷药', '2021-02-24 10:19:05');
/*!40000 ALTER TABLE `ts_anti_word` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_article 结构
DROP TABLE IF EXISTS `ts_article`;
CREATE TABLE IF NOT EXISTS `ts_article` (
  `articleid` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `cateid` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '标题',
  `content` longtext NOT NULL COMMENT '内容',
  `tags` varchar(128) NOT NULL DEFAULT '' COMMENT '标签',
  `gaiyao` varchar(128) NOT NULL DEFAULT '' COMMENT '内容概要',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '阅读文章所需积分',
  `path` char(32) NOT NULL DEFAULT '' COMMENT '路径',
  `photo` char(32) NOT NULL DEFAULT '' COMMENT '图片路径',
  `isaudit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `count_comment` int(11) NOT NULL DEFAULT '0' COMMENT '统计评论数',
  `count_love` int(11) NOT NULL DEFAULT '0' COMMENT '统计点赞喜欢',
  `count_view` int(11) NOT NULL DEFAULT '0' COMMENT '统计查看',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`articleid`),
  KEY `addtime` (`addtime`),
  KEY `cateid` (`cateid`),
  KEY `isrecommend` (`isrecommend`),
  KEY `count_recommend` (`count_love`,`addtime`),
  KEY `title` (`title`),
  KEY `count_view` (`count_view`),
  KEY `count_view_2` (`count_view`,`addtime`),
  KEY `tags` (`tags`,`isaudit`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='文章';

-- 正在导出表  d_thinksaas.ts_article 的数据：0 rows
DELETE FROM `ts_article`;
/*!40000 ALTER TABLE `ts_article` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_article` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_article_cate 结构
DROP TABLE IF EXISTS `ts_article_cate`;
CREATE TABLE IF NOT EXISTS `ts_article_cate` (
  `cateid` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `referid` int(11) NOT NULL DEFAULT '0' COMMENT '上级ID',
  `catename` varchar(64) NOT NULL DEFAULT '' COMMENT '分类名称',
  `cateinfo` varchar(2000) NOT NULL DEFAULT '' COMMENT '分类介绍',
  `orderid` int(11) NOT NULL DEFAULT '0' COMMENT '排序ID',
  PRIMARY KEY (`cateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='文章分类';

-- 正在导出表  d_thinksaas.ts_article_cate 的数据：0 rows
DELETE FROM `ts_article_cate`;
/*!40000 ALTER TABLE `ts_article_cate` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_article_cate` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_article_user 结构
DROP TABLE IF EXISTS `ts_article_user`;
CREATE TABLE IF NOT EXISTS `ts_article_user` (
  `articleid` int(11) NOT NULL DEFAULT '0' COMMENT '文章ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  UNIQUE KEY `articleid_userid` (`articleid`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='积分文章用户关联表';

-- 正在导出表  d_thinksaas.ts_article_user 的数据：0 rows
DELETE FROM `ts_article_user`;
/*!40000 ALTER TABLE `ts_article_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_article_user` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_cache 结构
DROP TABLE IF EXISTS `ts_cache`;
CREATE TABLE IF NOT EXISTS `ts_cache` (
  `cacheid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增缓存ID',
  `cachename` varchar(64) NOT NULL DEFAULT '' COMMENT '缓存名字',
  `cachevalue` text NOT NULL COMMENT '缓存内容',
  PRIMARY KEY (`cacheid`),
  UNIQUE KEY `cachename` (`cachename`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COMMENT='缓存';

-- 正在导出表  d_thinksaas.ts_cache 的数据：26 rows
DELETE FROM `ts_cache`;
/*!40000 ALTER TABLE `ts_cache` DISABLE KEYS */;
INSERT INTO `ts_cache` (`cacheid`, `cachename`, `cachevalue`) VALUES
	(1, 'pubs_plugins', '1613877203a:18:{i:9;s:10:"floatlayer";i:19;s:8:"customer";i:20;s:7:"counter";i:21;s:6:"douban";i:22;s:8:"feedback";i:24;s:7:"gonggao";i:25;s:5:"gotop";i:26;s:4:"navs";i:27;s:2:"qq";i:29;s:5:"weibo";i:30;s:6:"wordad";i:31;s:9:"footertip";i:32;s:8:"leftuser";i:33;s:7:"ueditor";i:35;s:10:"wangeditor";i:36;s:10:"summernote";i:37;s:7:"morenav";i:38;s:5:"gobad";}'),
	(2, 'home_plugins', '1587443014a:14:{i:11;s:9:"newtopics";i:12;s:5:"slide";i:13;s:8:"signuser";i:14;s:14:"recommendgroup";i:15;s:3:"tag";i:17;s:5:"login";i:18;s:5:"weibo";i:19;s:8:"newgroup";i:20;s:7:"article";i:22;s:5:"photo";i:23;s:5:"links";i:24;s:16:"recommendarticle";i:25;s:14:"recommendtopic";i:26;s:5:"topic";}'),
	(3, 'system_options', '1613377405a:45:{s:10:"site_title";s:9:"ThinkSAAS";s:13:"site_subtitle";s:24:"又一个ThinkSAAS社区";s:8:"site_key";s:9:"thinksaas";s:9:"site_desc";s:9:"thinksaas";s:8:"site_url";s:22:"http://d.thinksaas.cn/";s:8:"link_url";s:22:"http://d.thinksaas.cn/";s:9:"site_pkey";s:32:"b8d7d9362951887721f8892f15bdc8d4";s:10:"site_email";s:15:"admin@admin.com";s:8:"site_icp";s:20:"豫ICP备00000000号";s:6:"isface";s:1:"0";s:8:"isinvite";s:1:"0";s:7:"regtype";s:1:"0";s:8:"isverify";s:1:"0";s:13:"isverifyphone";s:1:"0";s:6:"istomy";s:1:"0";s:10:"isauthcode";s:1:"0";s:7:"istoken";s:1:"0";s:9:"is_weixin";s:1:"0";s:12:"weixin_appid";s:0:"";s:16:"weixin_appsecret";s:0:"";s:10:"is_vaptcha";s:1:"0";s:11:"vaptcha_vid";s:0:"";s:11:"vaptcha_key";s:0:"";s:8:"timezone";s:14:"Asia/Hong_Kong";s:7:"visitor";s:1:"0";s:9:"publisher";s:1:"0";s:7:"pubtime";s:0:"";s:11:"isallowedit";s:1:"0";s:13:"isallowdelete";s:1:"0";s:10:"site_theme";s:6:"sample";s:12:"site_urltype";s:1:"1";s:16:"file_upload_type";s:1:"0";s:19:"alioss_accesskey_id";s:0:"";s:23:"alioss_accesskey_secret";s:0:"";s:13:"alioss_bucket";s:0:"";s:15:"alioss_endpoint";s:0:"";s:17:"alioss_bucket_url";s:0:"";s:10:"photo_size";s:2:"10";s:10:"photo_type";s:16:"jpg,gif,png,jpeg";s:11:"photo_check";s:1:"0";s:12:"photo_driver";s:2:"gd";s:11:"attach_size";s:2:"10";s:11:"attach_type";s:19:"zip,rar,doc,txt,ppt";s:11:"dayscoretop";s:2:"10";s:4:"logo";s:8:"logo.png";}'),
	(4, 'system_appnav', '1597542226a:9:{s:4:"home";s:6:"首页";s:5:"group";s:6:"小组";s:5:"topic";s:6:"话题";s:7:"article";s:6:"文章";s:5:"photo";s:6:"相册";s:4:"user";s:6:"用户";s:5:"weibo";s:6:"唠叨";s:6:"search";s:6:"搜索";s:2:"my";s:12:"我的社区";}'),
	(5, 'system_anti_word', '1614133155s:6:"迷药";'),
	(6, 'user_options', '1586315688a:6:{s:7:"appname";s:6:"用户";s:7:"appdesc";s:12:"用户中心";s:6:"appkey";s:6:"用户";s:8:"isenable";s:1:"0";s:7:"isgroup";s:0:"";s:7:"banuser";s:25:"官方用户|官方团队";}'),
	(7, 'mail_options', '1586315631a:8:{s:7:"appname";s:0:"";s:7:"appdesc";s:0:"";s:8:"isenable";s:0:"";s:8:"mailhost";s:18:"smtp.exmail.qq.com";s:3:"ssl";s:1:"1";s:8:"mailport";s:3:"587";s:8:"mailuser";s:23:"postmaster@thinksaas.cn";s:7:"mailpwd";s:6:"123456";}'),
	(8, 'article_options', '1586315585a:5:{s:7:"appname";s:6:"文章";s:7:"appdesc";s:6:"文章";s:6:"appkey";s:6:"文章";s:9:"allowpost";s:1:"1";s:7:"isaudit";s:1:"0";}'),
	(9, 'group_options', '1586315590a:9:{s:7:"appname";s:6:"小组";s:7:"appdesc";s:15:"ThinkSAAS小组";s:6:"appkey";s:6:"小组";s:8:"iscreate";s:1:"0";s:7:"isaudit";s:1:"0";s:7:"joinnum";s:2:"20";s:11:"isallowpost";s:1:"0";s:12:"topicisaudit";s:1:"0";s:9:"ispayjoin";s:1:"0";}'),
	(10, 'photo_options', '1586315662a:4:{s:7:"appname";s:6:"相册";s:7:"appdesc";s:6:"相册";s:6:"appkey";s:6:"相册";s:7:"isaudit";s:1:"0";}'),
	(11, 'weibo_options', '1586315695a:3:{s:7:"appname";s:6:"唠叨";s:7:"appdesc";s:6:"唠叨";s:6:"appkey";s:6:"唠叨";}'),
	(12, 'plugins_pubs_wordad', '1400602928a:4:{i:0;a:2:{s:5:"title";s:22:"ThinkSAAS文字广告1";s:3:"url";s:23:"https://www.thinksaas.cn";}i:1;a:2:{s:5:"title";s:22:"ThinkSAAS文字广告2";s:3:"url";s:23:"https://www.thinksaas.cn";}i:2;a:2:{s:5:"title";s:22:"ThinkSAAS文字广告3";s:3:"url";s:23:"https://www.thinksaas.cn";}i:3;a:2:{s:5:"title";s:22:"ThinkSAAS文字广告4";s:3:"url";s:23:"https://www.thinksaas.cn";}}'),
	(13, 'user_role', '1400602955a:17:{i:0;a:3:{s:8:"rolename";s:6:"列兵";s:11:"score_start";s:1:"0";s:9:"score_end";s:4:"5000";}i:1;a:3:{s:8:"rolename";s:6:"下士";s:11:"score_start";s:4:"5000";s:9:"score_end";s:5:"20000";}i:2;a:3:{s:8:"rolename";s:6:"中士";s:11:"score_start";s:5:"20000";s:9:"score_end";s:5:"40000";}i:3;a:3:{s:8:"rolename";s:6:"上士";s:11:"score_start";s:5:"40000";s:9:"score_end";s:5:"80000";}i:4;a:3:{s:8:"rolename";s:12:"三级准尉";s:11:"score_start";s:5:"80000";s:9:"score_end";s:6:"160000";}i:5;a:3:{s:8:"rolename";s:12:"二级准尉";s:11:"score_start";s:6:"160000";s:9:"score_end";s:6:"320000";}i:6;a:3:{s:8:"rolename";s:12:"一级准尉";s:11:"score_start";s:6:"320000";s:9:"score_end";s:6:"640000";}i:7;a:3:{s:8:"rolename";s:6:"少尉";s:11:"score_start";s:6:"640000";s:9:"score_end";s:7:"1280000";}i:8;a:3:{s:8:"rolename";s:6:"中尉";s:11:"score_start";s:7:"1280000";s:9:"score_end";s:7:"2560000";}i:9;a:3:{s:8:"rolename";s:6:"上尉";s:11:"score_start";s:7:"2560000";s:9:"score_end";s:7:"5120000";}i:10;a:3:{s:8:"rolename";s:6:"少校";s:11:"score_start";s:7:"5120000";s:9:"score_end";s:8:"10240000";}i:11;a:3:{s:8:"rolename";s:6:"中校";s:11:"score_start";s:8:"10240000";s:9:"score_end";s:8:"20480000";}i:12;a:3:{s:8:"rolename";s:6:"上校";s:11:"score_start";s:8:"20480000";s:9:"score_end";s:8:"40960000";}i:13;a:3:{s:8:"rolename";s:6:"准将";s:11:"score_start";s:8:"40960000";s:9:"score_end";s:8:"81920000";}i:14;a:3:{s:8:"rolename";s:6:"少将";s:11:"score_start";s:8:"81920000";s:9:"score_end";s:9:"123840000";}i:15;a:3:{s:8:"rolename";s:6:"中将";s:11:"score_start";s:9:"123840000";s:9:"score_end";s:9:"327680000";}i:16;a:3:{s:8:"rolename";s:6:"上将";s:11:"score_start";s:9:"327680000";s:9:"score_end";s:1:"0";}}'),
	(14, 'plugins_pubs_gobad', '1597542108a:10:{s:10:"pub_header";s:0:"";i:300;s:20:"宽度300px广告位";i:468;s:20:"宽度468px广告位";i:640;s:0:"";i:960;s:21:"通栏1110px广告位";s:15:"topic_right_top";s:0:"";s:11:"home_left_1";s:0:"";s:11:"content_top";s:0:"";s:11:"home_left_2";s:0:"";s:10:"pub_footer";s:0:"";}'),
	(15, 'plugins_pubs_feedback', '1406109222s:52:"<a href=\\"https://www.thinksaas.cn\\">意见反馈</a>";'),
	(16, 'plugins_pubs_counter', '1585042191s:19:"<!--统计代码-->";'),
	(18, 'plugins_home_links', '1540469938a:2:{i:0;a:2:{s:8:"linkname";s:9:"ThinkSAAS";s:7:"linkurl";s:25:"https://www.thinksaas.cn/";}i:1;a:2:{s:8:"linkname";s:12:"开源社区";s:7:"linkurl";s:25:"https://www.thinksaas.cn/";}}'),
	(17, 'plugins_pubs_navs', '1532088590a:2:{i:0;a:2:{s:7:"navname";s:15:"ThinkSAAS官网";s:6:"navurl";s:25:"https://www.thinksaas.cn/";}i:1;a:2:{s:7:"navname";s:21:"付费商业版授权";s:6:"navurl";s:38:"https://www.thinksaas.cn/service/down/";}}'),
	(24, 'system_mynav', '1597542226a:5:{s:5:"group";s:6:"小组";s:5:"topic";s:6:"话题";s:7:"article";s:6:"文章";s:5:"photo";s:6:"相册";s:5:"weibo";s:6:"唠叨";}'),
	(25, 'pubs_options', '1586315671a:1:{s:20:"phone_code_send_time";s:1:"1";}'),
	(26, 'search_options', '1586315564a:8:{s:7:"appname";s:6:"搜索";s:7:"appdesc";s:6:"搜索";s:6:"appkey";s:6:"搜索";s:2:"ds";s:5:"topic";s:5:"group";s:1:"1";s:5:"topic";s:1:"1";s:4:"user";s:1:"1";s:7:"article";s:1:"1";}'),
	(27, 'sms_options', '1605233458a:5:{s:10:"sms_server";s:6:"aliyun";s:9:"sms_appid";s:1:"1";s:10:"sms_appkey";s:1:"2";s:8:"sms_tpid";s:1:"3";s:8:"sms_sign";s:1:"4";}'),
	(28, 'plugins_home_recommendarticle', '1589619069a:1:{s:11:"isrecommend";i:0;}'),
	(29, 'plugins_home_recommendtopic', '1589619075a:1:{s:11:"isrecommend";i:0;}'),
	(30, 'plugins_pubs_morenav', '1589619544a:1:{i:0;a:3:{s:7:"navname";s:9:"ThinkSAAS";s:6:"navurl";s:24:"https://www.thinksaas.cn";s:7:"newpage";s:0:"";}}'),
	(31, 'topic_options', '1597542226a:3:{s:7:"appname";s:6:"话题";s:7:"appdesc";s:12:"话题帖子";s:6:"appkey";s:6:"话题";}');
/*!40000 ALTER TABLE `ts_cache` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_comment 结构
DROP TABLE IF EXISTS `ts_comment`;
CREATE TABLE IF NOT EXISTS `ts_comment` (
  `commentid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增评论ID',
  `ptable` varchar(64) NOT NULL DEFAULT '' COMMENT '应用表名称',
  `pkey` varchar(64) NOT NULL DEFAULT '' COMMENT '应用表字段',
  `pid` varchar(64) NOT NULL DEFAULT '' COMMENT '应用表字段值',
  `referid` int(11) NOT NULL DEFAULT '0' COMMENT '上级评论ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `touserid` int(11) NOT NULL DEFAULT '0' COMMENT '回复用户ID',
  `content` text NOT NULL COMMENT '回复内容',
  `ispublic` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0公开1不公开（仅自己和发帖者可看）',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '回复时间',
  PRIMARY KEY (`commentid`),
  KEY `ptable_pkey_pid_referid` (`ptable`,`pkey`,`pid`,`referid`),
  KEY `ptable_pkey_pid` (`ptable`,`pkey`,`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='回复/评论';

-- 正在导出表  d_thinksaas.ts_comment 的数据：0 rows
DELETE FROM `ts_comment`;
/*!40000 ALTER TABLE `ts_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_comment` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_draft 结构
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='草稿箱';

-- 正在导出表  d_thinksaas.ts_draft 的数据：0 rows
DELETE FROM `ts_draft`;
/*!40000 ALTER TABLE `ts_draft` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_draft` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_editor 结构
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='编辑器上传图片和文件';

-- 正在导出表  d_thinksaas.ts_editor 的数据：1 rows
DELETE FROM `ts_editor`;
/*!40000 ALTER TABLE `ts_editor` DISABLE KEYS */;
INSERT INTO `ts_editor` (`id`, `userid`, `type`, `title`, `path`, `url`, `addtime`) VALUES
	(1, 1, 'photo', '4106d6112c28886e183a1e600f92723e.jpg', '0/0', '0/0/1.jpg', 1563675585);
/*!40000 ALTER TABLE `ts_editor` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_group 结构
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
  `price` int(11) NOT NULL DEFAULT '0' COMMENT '加入支付金币',
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

-- 正在导出表  d_thinksaas.ts_group 的数据：0 rows
DELETE FROM `ts_group`;
/*!40000 ALTER TABLE `ts_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_group_cate 结构
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

-- 正在导出表  d_thinksaas.ts_group_cate 的数据：0 rows
DELETE FROM `ts_group_cate`;
/*!40000 ALTER TABLE `ts_group_cate` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_cate` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_group_user 结构
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

-- 正在导出表  d_thinksaas.ts_group_user 的数据：0 rows
DELETE FROM `ts_group_user`;
/*!40000 ALTER TABLE `ts_group_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_user` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_group_user_isaudit 结构
DROP TABLE IF EXISTS `ts_group_user_isaudit`;
CREATE TABLE IF NOT EXISTS `ts_group_user_isaudit` (
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `groupid` int(11) NOT NULL DEFAULT '0' COMMENT '小组ID',
  UNIQUE KEY `userid` (`userid`,`groupid`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='用于申请加入小组的成员审核';

-- 正在导出表  d_thinksaas.ts_group_user_isaudit 的数据：0 rows
DELETE FROM `ts_group_user_isaudit`;
/*!40000 ALTER TABLE `ts_group_user_isaudit` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_group_user_isaudit` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_home_info 结构
DROP TABLE IF EXISTS `ts_home_info`;
CREATE TABLE IF NOT EXISTS `ts_home_info` (
  `infoid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `orderid` int(11) NOT NULL DEFAULT '0' COMMENT '排序ID',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`infoid`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='关于我们等信息';

-- 正在导出表  d_thinksaas.ts_home_info 的数据：5 rows
DELETE FROM `ts_home_info`;
/*!40000 ALTER TABLE `ts_home_info` DISABLE KEYS */;
INSERT INTO `ts_home_info` (`infoid`, `orderid`, `title`, `content`) VALUES
	(1, 0, '关于我们', '\n&lt;p&gt;关于我们&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;\n'),
	(2, 0, '联系我们', '\n&lt;p&gt;联系我们&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;\n'),
	(3, 0, '用户条款', '\n&lt;p&gt;本协议适用ThinkSAAS发布的所有程序版本和代码，所有版本都将按照最新发布的【用户条款】执行。&lt;br&gt;1、ThinkSAAS官方指：ThinkSAAS社区、thinksaas.cn和ThinkSAAS社区系统开发者邱君。&lt;br&gt;2、ThinkSAAS禁止用户在使用中触犯中国法律范围内的任何法律条文。&lt;br&gt;3、ThinkSAAS、及其创始人邱君拥有对ThinkSAAS的所有权，任何个人，公司和组织不得以任何形式和目的侵犯ThinkSAAS的版权和著作权。&lt;br&gt;4、ThinkSAAS官方拥有对ThinkSAAS社区软件绝对的版权和著作权。&lt;br&gt;5、ThinkSAAS程序代码完全开源，不做任何加密处理。ThinkSAAS允许【自身运营】用户对程序代码进行二次开发，但必须遵循本条款第6、7、8和9条规定执行。&lt;br&gt;6、所有使用ThinkSAAS的用户在保留底部Powered by ThinkSAAS 文字链接或者标识的情况下，可以免费使用ThinkSAAS。&lt;br&gt;7、用户在购买ThinkSAAS商业授权后才可以去除底部Powered by ThinkSAAS 文字链接或者标识。&lt;br&gt;8、ThinkSAAS不会监控用户网站信息，但有权通过邮件或者其他联系方式获悉用户使用情况，有权拿用户网站用作案例展示。&lt;br&gt;9、在未经ThinkSAAS官方书面允许的情况下，除【自身运营】外，任何个人、公司和组织不能单方面发布和出售以ThinkSAAS为基础开发的任何互联网软件或者产品，否则将视为侵权行为，将依照中华人民共和国法律追究其法律责任。&lt;br&gt;10、公司企业等组织机构使用ThinkSAAS软件必须购买ThinkSAAS商业授权协议。&lt;br&gt;11、ThinkSAAS官方拥有对此协议的修改和不断完善。&lt;br&gt;&lt;br&gt;【自身运营】解释：即用户在使用ThinkSAAS中，不通过出售任何以ThinkSAAS为基础开发的产品，仅用作自身学习和自身商业运营的网站。&lt;br&gt;&lt;br&gt;【用户条款】网址：https://www.thinksaas.cn/home/info/key/agreement/&lt;br&gt;【官方网站】网址：https://www.thinksaas.cn/&lt;br&gt;【演示网站】网址：https://demo.thinksaas.cn/&lt;/p&gt;\n'),
	(4, 0, '隐私声明', '\n&lt;p&gt;隐私声明&lt;/p&gt;\n'),
	(5, 0, '加入我们', '\n&lt;p&gt;加入我们&lt;/p&gt;\n');
/*!40000 ALTER TABLE `ts_home_info` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_logs 结构
DROP TABLE IF EXISTS `ts_logs`;
CREATE TABLE IF NOT EXISTS `ts_logs` (
  `logid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `ptable` varchar(50) NOT NULL DEFAULT '0' COMMENT '应用表名称',
  `pkey` varchar(50) NOT NULL DEFAULT '0' COMMENT '应用表字段',
  `pid` varchar(50) NOT NULL DEFAULT '0' COMMENT '应用表字段值',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '标题',
  `content` longtext NOT NULL COMMENT '内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0添加1修改2删除',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`logid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='用户操作记录表';

-- 正在导出表  d_thinksaas.ts_logs 的数据：0 rows
DELETE FROM `ts_logs`;
/*!40000 ALTER TABLE `ts_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_logs` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_love 结构
DROP TABLE IF EXISTS `ts_love`;
CREATE TABLE IF NOT EXISTS `ts_love` (
  `loveid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `ptable` varchar(64) NOT NULL DEFAULT '' COMMENT '应用表名称',
  `pkey` varchar(64) NOT NULL DEFAULT '' COMMENT '应用表字段',
  `pid` varchar(64) NOT NULL DEFAULT '' COMMENT '应用表字段值',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`loveid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='点赞/喜欢表';

-- 正在导出表  d_thinksaas.ts_love 的数据：0 rows
DELETE FROM `ts_love`;
/*!40000 ALTER TABLE `ts_love` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_love` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_mail_options 结构
DROP TABLE IF EXISTS `ts_mail_options`;
CREATE TABLE IF NOT EXISTS `ts_mail_options` (
  `optionid` int(11) NOT NULL AUTO_INCREMENT COMMENT '选项ID',
  `optionname` varchar(32) NOT NULL DEFAULT '' COMMENT '选项名字',
  `optionvalue` varchar(512) NOT NULL DEFAULT '' COMMENT '选项内容',
  PRIMARY KEY (`optionid`),
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='发信邮件配置';

-- 正在导出表  d_thinksaas.ts_mail_options 的数据：8 rows
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

-- 导出  表 d_thinksaas.ts_message 结构
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

-- 正在导出表  d_thinksaas.ts_message 的数据：0 rows
DELETE FROM `ts_message`;
/*!40000 ALTER TABLE `ts_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_message` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_phone_code 结构
DROP TABLE IF EXISTS `ts_phone_code`;
CREATE TABLE IF NOT EXISTS `ts_phone_code` (
  `phone` varchar(32) CHARACTER SET utf8 NOT NULL COMMENT '手机号',
  `code` varchar(32) CHARACTER SET utf8 NOT NULL COMMENT '验证码',
  `nums` int(11) NOT NULL DEFAULT '0' COMMENT '错误次数',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '时间',
  UNIQUE KEY `phone` (`phone`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='手机号验证码';

-- 正在导出表  d_thinksaas.ts_phone_code 的数据：0 rows
DELETE FROM `ts_phone_code`;
/*!40000 ALTER TABLE `ts_phone_code` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_phone_code` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_photo 结构
DROP TABLE IF EXISTS `ts_photo`;
CREATE TABLE IF NOT EXISTS `ts_photo` (
  `photoid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增图片ID',
  `albumid` int(11) NOT NULL DEFAULT '0' COMMENT '相册ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '图片名称',
  `phototype` char(32) NOT NULL DEFAULT '' COMMENT '图片类型',
  `path` char(32) NOT NULL DEFAULT '' COMMENT '图片路径',
  `photourl` varchar(64) NOT NULL DEFAULT '' COMMENT '图片地址',
  `photosize` char(32) NOT NULL DEFAULT '' COMMENT '图片大小',
  `photodesc` char(120) NOT NULL DEFAULT '' COMMENT '图片介绍',
  `count_view` int(11) NOT NULL DEFAULT '0' COMMENT '统计浏览量',
  `count_comment` int(11) NOT NULL DEFAULT '0' COMMENT '统计评论数',
  `count_love` int(11) NOT NULL DEFAULT '0' COMMENT '统计点赞喜欢数',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0不推荐1推荐',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '添加时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`photoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='相册图片';

-- 正在导出表  d_thinksaas.ts_photo 的数据：0 rows
DELETE FROM `ts_photo`;
/*!40000 ALTER TABLE `ts_photo` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_photo` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_photo_album 结构
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

-- 正在导出表  d_thinksaas.ts_photo_album 的数据：0 rows
DELETE FROM `ts_photo_album`;
/*!40000 ALTER TABLE `ts_photo_album` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_photo_album` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_slide 结构
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

-- 正在导出表  d_thinksaas.ts_slide 的数据：1 rows
DELETE FROM `ts_slide`;
/*!40000 ALTER TABLE `ts_slide` DISABLE KEYS */;
INSERT INTO `ts_slide` (`slideid`, `typeid`, `title`, `info`, `url`, `path`, `photo`, `addtime`) VALUES
	(1, 0, 'ThinkSAAS开源社区', '关注官方微信，时刻获取最新版本更新通知', 'https://www.thinksaas.cn', '0/0', '0/0/1.jpg', 1416533676);
/*!40000 ALTER TABLE `ts_slide` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_system_options 结构
DROP TABLE IF EXISTS `ts_system_options`;
CREATE TABLE IF NOT EXISTS `ts_system_options` (
  `optionname` varchar(64) NOT NULL DEFAULT '' COMMENT '选项名字',
  `optionvalue` varchar(512) NOT NULL DEFAULT '' COMMENT '选项内容',
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='系统管理配置';

-- 正在导出表  dev_thinksaas.ts_system_options 的数据：45 rows
DELETE FROM `ts_system_options`;
/*!40000 ALTER TABLE `ts_system_options` DISABLE KEYS */;
INSERT INTO `ts_system_options` (`optionname`, `optionvalue`) VALUES
	('site_title', 'ThinkSAAS'),
	('site_subtitle', '又一个ThinkSAAS社区'),
	('site_key', 'thinksaas'),
	('site_desc', 'thinksaas'),
	('site_url', 'http://dev.thinksaas.org/'),
	('link_url', 'http://dev.thinksaas.org/'),
	('site_pkey', '77bb8fd9e22e5d951bc2fd19bfd8bd1f'),
	('site_email', 'admin@admin.com'),
	('site_icp', '豫ICP备00000000号'),
	('isface', '0'),
	('isinvite', '0'),
	('regtype', '0'),
	('isverify', '0'),
	('isverifyphone', '0'),
	('istomy', '0'),
	('isauthcode', '0'),
	('istoken', '0'),
	('is_weixin', '0'),
	('weixin_appid', ''),
	('weixin_appsecret', ''),
	('is_vaptcha', '0'),
	('vaptcha_vid', ''),
	('vaptcha_key', ''),
	('timezone', 'Asia/Hong_Kong'),
	('visitor', '0'),
	('publisher', '0'),
	('pubtime', ''),
	('isallowedit', '0'),
	('isallowdelete', '0'),
	('site_theme', 'sample'),
	('site_urltype', '1'),
	('file_upload_type', '0'),
	('alioss_accesskey_id', ''),
	('alioss_accesskey_secret', ''),
	('alioss_bucket', ''),
	('alioss_endpoint', ''),
	('alioss_bucket_url', ''),
	('photo_size', '10'),
	('photo_type', 'jpg,gif,png,jpeg'),
	('photo_check', '0'),
	('photo_driver', 'gd'),
	('attach_size', '10'),
	('attach_type', 'zip,rar,doc,txt,ppt'),
	('dayscoretop', '10'),
	('logo', 'logo.png');
/*!40000 ALTER TABLE `ts_system_options` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_tag 结构
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

-- 正在导出表  d_thinksaas.ts_tag 的数据：0 rows
DELETE FROM `ts_tag`;
/*!40000 ALTER TABLE `ts_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_tag` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_tag_article_index 结构
DROP TABLE IF EXISTS `ts_tag_article_index`;
CREATE TABLE IF NOT EXISTS `ts_tag_article_index` (
  `articleid` int(11) NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `tagid` int(11) NOT NULL DEFAULT '0' COMMENT '标签ID',
  UNIQUE KEY `articleid_2` (`articleid`,`tagid`),
  KEY `articleid` (`articleid`),
  KEY `tagid` (`tagid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='文章标签关联';

-- 正在导出表  d_thinksaas.ts_tag_article_index 的数据：0 rows
DELETE FROM `ts_tag_article_index`;
/*!40000 ALTER TABLE `ts_tag_article_index` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_tag_article_index` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_tag_group_index 结构
DROP TABLE IF EXISTS `ts_tag_group_index`;
CREATE TABLE IF NOT EXISTS `ts_tag_group_index` (
  `groupid` int(11) NOT NULL DEFAULT '0' COMMENT '小组ID',
  `tagid` int(11) NOT NULL DEFAULT '0' COMMENT '标签ID',
  UNIQUE KEY `groupid_2` (`groupid`,`tagid`),
  KEY `groupid` (`groupid`),
  KEY `tagid` (`tagid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='小组标签关联';

-- 正在导出表  d_thinksaas.ts_tag_group_index 的数据：0 rows
DELETE FROM `ts_tag_group_index`;
/*!40000 ALTER TABLE `ts_tag_group_index` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_tag_group_index` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_tag_photo_index 结构
DROP TABLE IF EXISTS `ts_tag_photo_index`;
CREATE TABLE IF NOT EXISTS `ts_tag_photo_index` (
  `photoid` int(11) NOT NULL DEFAULT '0' COMMENT '图片ID',
  `tagid` int(11) NOT NULL DEFAULT '0' COMMENT '标签ID',
  UNIQUE KEY `photoid_2` (`photoid`,`tagid`),
  KEY `tagid` (`tagid`),
  KEY `photoid` (`photoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='相册图片标签关联';

-- 正在导出表  d_thinksaas.ts_tag_photo_index 的数据：0 rows
DELETE FROM `ts_tag_photo_index`;
/*!40000 ALTER TABLE `ts_tag_photo_index` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_tag_photo_index` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_tag_topic_index 结构
DROP TABLE IF EXISTS `ts_tag_topic_index`;
CREATE TABLE IF NOT EXISTS `ts_tag_topic_index` (
  `topicid` int(11) NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `tagid` int(11) NOT NULL DEFAULT '0' COMMENT '标签ID',
  UNIQUE KEY `topicid_2` (`topicid`,`tagid`),
  KEY `topicid` (`topicid`),
  KEY `tagid` (`tagid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='帖子标签关联';

-- 正在导出表  d_thinksaas.ts_tag_topic_index 的数据：0 rows
DELETE FROM `ts_tag_topic_index`;
/*!40000 ALTER TABLE `ts_tag_topic_index` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_tag_topic_index` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_tag_user_index 结构
DROP TABLE IF EXISTS `ts_tag_user_index`;
CREATE TABLE IF NOT EXISTS `ts_tag_user_index` (
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `tagid` int(11) NOT NULL DEFAULT '0' COMMENT '标签ID',
  UNIQUE KEY `userid_2` (`userid`,`tagid`),
  KEY `userid` (`userid`),
  KEY `tagid` (`tagid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='用户标签关联';

-- 正在导出表  d_thinksaas.ts_tag_user_index 的数据：0 rows
DELETE FROM `ts_tag_user_index`;
/*!40000 ALTER TABLE `ts_tag_user_index` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_tag_user_index` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_task 结构
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

-- 正在导出表  d_thinksaas.ts_task 的数据：0 rows
DELETE FROM `ts_task`;
/*!40000 ALTER TABLE `ts_task` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_task` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_task_user 结构
DROP TABLE IF EXISTS `ts_task_user`;
CREATE TABLE IF NOT EXISTS `ts_task_user` (
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `taskkey` char(32) NOT NULL DEFAULT '' COMMENT '任务key',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '时间',
  UNIQUE KEY `userid` (`userid`,`taskkey`),
  KEY `taskkey` (`taskkey`),
  KEY `userid_2` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='用户任务关联';

-- 正在导出表  d_thinksaas.ts_task_user 的数据：0 rows
DELETE FROM `ts_task_user`;
/*!40000 ALTER TABLE `ts_task_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_task_user` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_topic 结构
DROP TABLE IF EXISTS `ts_topic`;
CREATE TABLE IF NOT EXISTS `ts_topic` (
  `topicid` int(11) NOT NULL AUTO_INCREMENT COMMENT '话题ID',
  `ptable` varchar(64) NOT NULL DEFAULT '' COMMENT '应用表名称',
  `pkey` varchar(64) NOT NULL DEFAULT '' COMMENT '应用表字段',
  `pid` varchar(64) NOT NULL DEFAULT '' COMMENT '应用表字段值',
  `pjson` varchar(512) NOT NULL DEFAULT '' COMMENT '应用json数据',
  `typeid` int(11) NOT NULL DEFAULT '0' COMMENT '帖子分类ID',
  `groupid` int(11) NOT NULL DEFAULT '0' COMMENT '小组ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '帖子标题',
  `label` varchar(64) NOT NULL DEFAULT '' COMMENT '快速标注',
  `content` longtext NOT NULL COMMENT '帖子内容',
  `gaiyao` varchar(256) NOT NULL DEFAULT '' COMMENT '内容概要',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '查看需要积分',
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='话题(帖子)';

-- 正在导出表  d_thinksaas.ts_topic 的数据：0 rows
DELETE FROM `ts_topic`;
/*!40000 ALTER TABLE `ts_topic` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_topic` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_topic_edit 结构
DROP TABLE IF EXISTS `ts_topic_edit`;
CREATE TABLE IF NOT EXISTS `ts_topic_edit` (
  `editid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增编辑ID',
  `topicid` int(11) NOT NULL DEFAULT '0' COMMENT '话题ID',
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `isupdate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未更新1更新',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '修改时间',
  PRIMARY KEY (`editid`),
  UNIQUE KEY `topicid` (`topicid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='话题编辑';

-- 正在导出表  d_thinksaas.ts_topic_edit 的数据：0 rows
DELETE FROM `ts_topic_edit`;
/*!40000 ALTER TABLE `ts_topic_edit` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_topic_edit` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_topic_photo 结构
DROP TABLE IF EXISTS `ts_topic_photo`;
CREATE TABLE IF NOT EXISTS `ts_topic_photo` (
  `photoid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `topicid` int(11) NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `orderid` int(11) NOT NULL DEFAULT '0' COMMENT '排序ID',
  `path` varchar(50) NOT NULL DEFAULT '' COMMENT '路径',
  `photo` varchar(50) NOT NULL DEFAULT '' COMMENT '图片',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`photoid`),
  UNIQUE KEY `photoid` (`photoid`),
  KEY `topicid` (`topicid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='帖子图片表';

-- 正在导出表  d_thinksaas.ts_topic_photo 的数据：0 rows
DELETE FROM `ts_topic_photo`;
/*!40000 ALTER TABLE `ts_topic_photo` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_topic_photo` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_topic_type 结构
DROP TABLE IF EXISTS `ts_topic_type`;
CREATE TABLE IF NOT EXISTS `ts_topic_type` (
  `typeid` int(11) NOT NULL AUTO_INCREMENT COMMENT '帖子分类ID',
  `groupid` int(11) NOT NULL DEFAULT '0' COMMENT '小组ID',
  `typename` varchar(64) NOT NULL DEFAULT '' COMMENT '帖子分类名称',
  `count_topic` int(11) NOT NULL DEFAULT '0' COMMENT '统计帖子',
  PRIMARY KEY (`typeid`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='帖子分类';

-- 正在导出表  d_thinksaas.ts_topic_type 的数据：0 rows
DELETE FROM `ts_topic_type`;
/*!40000 ALTER TABLE `ts_topic_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_topic_type` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_topic_user 结构
DROP TABLE IF EXISTS `ts_topic_user`;
CREATE TABLE IF NOT EXISTS `ts_topic_user` (
  `topicid` int(11) NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  UNIQUE KEY `topicid_userid` (`topicid`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='积分帖子用户关联表';

-- 正在导出表  d_thinksaas.ts_topic_user 的数据：0 rows
DELETE FROM `ts_topic_user`;
/*!40000 ALTER TABLE `ts_topic_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_topic_user` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_upload 结构
DROP TABLE IF EXISTS `ts_upload`;
CREATE TABLE IF NOT EXISTS `ts_upload` (
  `upid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `fileurl` varchar(50) NOT NULL DEFAULT '' COMMENT '文件存储地址',
  `filename` varchar(50) NOT NULL DEFAULT '' COMMENT '文件名称',
  `filesize` varchar(50) NOT NULL DEFAULT '' COMMENT '文件大小',
  `filetype` varchar(50) NOT NULL DEFAULT '' COMMENT '文件类型',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '添加时间',
  PRIMARY KEY (`upid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='分块上传临时存储';

-- 正在导出表  d_thinksaas.ts_upload 的数据：0 rows
DELETE FROM `ts_upload`;
/*!40000 ALTER TABLE `ts_upload` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_upload` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_user 结构
DROP TABLE IF EXISTS `ts_user`;
CREATE TABLE IF NOT EXISTS `ts_user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `pwd` char(32) NOT NULL DEFAULT '' COMMENT '用户密码',
  `salt` char(32) NOT NULL DEFAULT '' COMMENT '加点盐',
  `email` varchar(64) NOT NULL DEFAULT '' COMMENT '用户email',
  `phone` varchar(64) NOT NULL DEFAULT '' COMMENT '手机号',
  `resetpwd` char(32) NOT NULL DEFAULT '' COMMENT '重设密码',
  `code` char(32) NOT NULL DEFAULT '' COMMENT '邮箱验证码',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='用户';

-- 正在导出表  d_thinksaas.ts_user 的数据：0 rows
DELETE FROM `ts_user`;
/*!40000 ALTER TABLE `ts_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_user_follow 结构
DROP TABLE IF EXISTS `ts_user_follow`;
CREATE TABLE IF NOT EXISTS `ts_user_follow` (
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `userid_follow` int(11) NOT NULL DEFAULT '0' COMMENT '被关注的用户ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  UNIQUE KEY `userid_2` (`userid`,`userid_follow`),
  KEY `userid` (`userid`),
  KEY `userid_follow` (`userid_follow`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='用户关注跟随';

-- 正在导出表  d_thinksaas.ts_user_follow 的数据：0 rows
DELETE FROM `ts_user_follow`;
/*!40000 ALTER TABLE `ts_user_follow` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user_follow` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_user_gb 结构
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

-- 正在导出表  d_thinksaas.ts_user_gb 的数据：0 rows
DELETE FROM `ts_user_gb`;
/*!40000 ALTER TABLE `ts_user_gb` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user_gb` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_user_group 结构
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

-- 正在导出表  d_thinksaas.ts_user_group 的数据：0 rows
DELETE FROM `ts_user_group`;
/*!40000 ALTER TABLE `ts_user_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user_group` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_user_info 结构
DROP TABLE IF EXISTS `ts_user_info`;
CREATE TABLE IF NOT EXISTS `ts_user_info` (
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `fuserid` int(11) NOT NULL DEFAULT '0' COMMENT '来自邀请用户',
  `username` char(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `email` varchar(64) NOT NULL DEFAULT '' COMMENT 'Email邮箱',
  `sex` char(32) NOT NULL DEFAULT '女' COMMENT '性别',
  `phone` varchar(64) NOT NULL DEFAULT '' COMMENT '电话号码',
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
  `isverifyphone` tinyint(1) NOT NULL DEFAULT '0' COMMENT '手机号验证0未验证1验证',
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

-- 正在导出表  d_thinksaas.ts_user_info 的数据：0 rows
DELETE FROM `ts_user_info`;
/*!40000 ALTER TABLE `ts_user_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user_info` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_user_invites 结构
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

-- 正在导出表  d_thinksaas.ts_user_invites 的数据：0 rows
DELETE FROM `ts_user_invites`;
/*!40000 ALTER TABLE `ts_user_invites` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user_invites` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_user_open 结构
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

-- 正在导出表  d_thinksaas.ts_user_open 的数据：0 rows
DELETE FROM `ts_user_open`;
/*!40000 ALTER TABLE `ts_user_open` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user_open` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_user_role 结构
DROP TABLE IF EXISTS `ts_user_role`;
CREATE TABLE IF NOT EXISTS `ts_user_role` (
  `roleid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增角色ID',
  `rolename` char(32) NOT NULL DEFAULT '' COMMENT '角色名称',
  `score_start` int(11) NOT NULL DEFAULT '0' COMMENT '积分开始',
  `score_end` int(11) NOT NULL DEFAULT '0' COMMENT '积分结束',
  PRIMARY KEY (`roleid`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COMMENT='角色';

-- 正在导出表  d_thinksaas.ts_user_role 的数据：17 rows
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

-- 导出  表 d_thinksaas.ts_user_score 结构
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
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COMMENT='用户积分设置表';

-- 正在导出表  d_thinksaas.ts_user_score 的数据：16 rows
DELETE FROM `ts_user_score`;
/*!40000 ALTER TABLE `ts_user_score` DISABLE KEYS */;
INSERT INTO `ts_user_score` (`scoreid`, `scorekey`, `scorename`, `app`, `action`, `mg`, `ts`, `score`, `status`) VALUES
	(1, 'user_register', '用户注册', 'user', 'register', '', 'do', 10, 0),
	(2, 'user_login', '用户登陆', 'user', 'login', '', 'do', 5, 0),
	(3, 'topic_add', '发帖', 'topic', 'add', '', 'do', 10, 0),
	(4, 'pubs_comment', '评论', 'pubs', 'comment', '', 'do', 5, 0),
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
	(17, 'article_admin_post_delete', '后台文章删除', 'article', 'admin', 'post', 'delete', 5, 1),
	(18, 'user_phone_do', '手机号注册', 'user', 'phone', '', 'do', 10, 0);
/*!40000 ALTER TABLE `ts_user_score` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_user_score_log 结构
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

-- 正在导出表  d_thinksaas.ts_user_score_log 的数据：0 rows
DELETE FROM `ts_user_score_log`;
/*!40000 ALTER TABLE `ts_user_score_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_user_score_log` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_weibo 结构
DROP TABLE IF EXISTS `ts_weibo`;
CREATE TABLE IF NOT EXISTS `ts_weibo` (
  `weiboid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增唠叨ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` varchar(256) NOT NULL DEFAULT '' COMMENT '一句话内容',
  `count_comment` int(11) NOT NULL DEFAULT '0' COMMENT '统计评论数',
  `count_view` int(11) NOT NULL DEFAULT '0' COMMENT '统计阅读数',
  `count_love` int(11) NOT NULL DEFAULT '0' COMMENT '统计点赞数',
  `path` char(32) NOT NULL DEFAULT '' COMMENT '路径',
  `photo` char(32) NOT NULL DEFAULT '' COMMENT '图片',
  `video` char(32) NOT NULL DEFAULT '' COMMENT '视频',
  `audio` char(32) NOT NULL DEFAULT '' COMMENT '音频',
  `isaudit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核',
  `addtime` datetime NOT NULL DEFAULT '1970-01-01 00:00:01' COMMENT '添加时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`weiboid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='唠叨';

-- 正在导出表  d_thinksaas.ts_weibo 的数据：0 rows
DELETE FROM `ts_weibo`;
/*!40000 ALTER TABLE `ts_weibo` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_weibo` ENABLE KEYS */;

-- 导出  表 d_thinksaas.ts_weibo_photo 结构
DROP TABLE IF EXISTS `ts_weibo_photo`;
CREATE TABLE IF NOT EXISTS `ts_weibo_photo` (
  `photoid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `weiboid` int(11) NOT NULL DEFAULT '0' COMMENT '微博ID',
  `orderid` int(11) NOT NULL DEFAULT '0' COMMENT '排序ID',
  `path` varchar(50) NOT NULL DEFAULT '' COMMENT '路径',
  `photo` varchar(50) NOT NULL DEFAULT '' COMMENT '图片',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`photoid`) USING BTREE,
  UNIQUE KEY `photoid` (`photoid`) USING BTREE,
  KEY `weiboid` (`weiboid`) USING BTREE,
  KEY `userid` (`userid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='微博图片表';

-- 正在导出表  d_thinksaas.ts_weibo_photo 的数据：0 rows
DELETE FROM `ts_weibo_photo`;
/*!40000 ALTER TABLE `ts_weibo_photo` DISABLE KEYS */;
/*!40000 ALTER TABLE `ts_weibo_photo` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;