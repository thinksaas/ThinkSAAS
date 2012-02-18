DROP TABLE IF EXISTS `ts_apple`, `ts_apple_comment`, `ts_apple_index`, `ts_apple_model`, `ts_apple_options`, `ts_apple_review`, `ts_apple_score`, `ts_apple_virtue`, `ts_area`, `ts_article`, `ts_article_cate`, `ts_article_comment`, `ts_attach`, `ts_event`, `ts_event_comment`, `ts_event_group_index`, `ts_event_type`, `ts_event_users`, `ts_feed`, `ts_group`, `ts_group_cates`, `ts_group_cates_index`, `ts_group_links`, `ts_group_options`, `ts_group_topics`, `ts_group_topics_collects`, `ts_group_topics_comments`, `ts_group_topics_type`, `ts_group_users`, `ts_home_info`, `ts_mail_options`, `ts_message`, `ts_photo`, `ts_photo_album`, `ts_photo_comment`, `ts_photo_options`, `ts_search_key`, `ts_system_options`, `ts_tag`, `ts_tag_article_index`, `ts_tag_group_index`, `ts_tag_topic_index`, `ts_tag_user_index`, `ts_user`, `ts_user_follow`, `ts_user_info`, `ts_user_invites`, `ts_user_options`, `ts_user_role`, `ts_user_scores`;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `thinksaas`
--

-- --------------------------------------------------------

--
-- 表的结构 `ts_apple`
--

CREATE TABLE IF NOT EXISTS `ts_apple` (
  `appleid` int(11) NOT NULL AUTO_INCREMENT COMMENT '苹果机编号',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '苹果机制造者',
  `title` char(64) NOT NULL DEFAULT '' COMMENT '苹果机名称',
  `content` text NOT NULL COMMENT '苹果机介绍',
  `path` char(32) NOT NULL DEFAULT '' COMMENT '图片路劲',
  `photo` char(32) NOT NULL DEFAULT '' COMMENT '图片地址',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '苹果机创建时间',
  PRIMARY KEY (`appleid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='苹果机' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ts_apple`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_apple_comment`
--

CREATE TABLE IF NOT EXISTS `ts_apple_comment` (
  `commentid` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `reviewid` int(11) NOT NULL DEFAULT '0' COMMENT '点评ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `content` text NOT NULL COMMENT '内容',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`commentid`),
  KEY `reviewid` (`reviewid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ts_apple_comment`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_apple_index`
--

CREATE TABLE IF NOT EXISTS `ts_apple_index` (
  `appleid` int(11) NOT NULL DEFAULT '0' COMMENT '苹果机编号',
  `modelid` int(11) NOT NULL DEFAULT '0' COMMENT '模型ID',
  `virtueid` int(11) NOT NULL DEFAULT '0' COMMENT '模型属性名称',
  `parameter` char(64) NOT NULL DEFAULT '' COMMENT '苹果属性参数',
  KEY `appleid` (`appleid`),
  KEY `modelid` (`modelid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模型属性索引';

--
-- 转存表中的数据 `ts_apple_index`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_apple_model`
--

CREATE TABLE IF NOT EXISTS `ts_apple_model` (
  `modelid` int(11) NOT NULL AUTO_INCREMENT,
  `modelkey` char(32) NOT NULL DEFAULT '' COMMENT '模型key',
  `modelname` char(32) NOT NULL DEFAULT '' COMMENT '模型名称',
  PRIMARY KEY (`modelid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='苹果模型' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `ts_apple_model`
--

INSERT INTO `ts_apple_model` (`modelid`, `modelkey`, `modelname`) VALUES
(1, 'book', '书籍'),
(2, 'movie', '电影');

-- --------------------------------------------------------

--
-- 表的结构 `ts_apple_options`
--

CREATE TABLE IF NOT EXISTS `ts_apple_options` (
  `optionname` char(12) NOT NULL DEFAULT '' COMMENT '选项名字',
  `optionvalue` char(255) NOT NULL DEFAULT '' COMMENT '选项内容',
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='配置';

--
-- 转存表中的数据 `ts_apple_options`
--

INSERT INTO `ts_apple_options` (`optionname`, `optionvalue`) VALUES
('appname', '苹果机'),
('appdesc', '苹果机，点评模块'),
('isenable', '0'),
('modelid', '1');

-- --------------------------------------------------------

--
-- 表的结构 `ts_apple_review`
--

CREATE TABLE IF NOT EXISTS `ts_apple_review` (
  `reviewid` int(11) NOT NULL AUTO_INCREMENT,
  `appleid` int(11) NOT NULL DEFAULT '0' COMMENT '苹果机ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` char(64) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`reviewid`),
  KEY `appleid` (`appleid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='苹果机点评' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ts_apple_review`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_apple_score`
--

CREATE TABLE IF NOT EXISTS `ts_apple_score` (
  `appleid` int(11) NOT NULL DEFAULT '0' COMMENT '苹果机ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '分数',
  UNIQUE KEY `appleid` (`appleid`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='苹果机用户打分';

--
-- 转存表中的数据 `ts_apple_score`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_apple_virtue`
--

CREATE TABLE IF NOT EXISTS `ts_apple_virtue` (
  `virtueid` int(11) NOT NULL AUTO_INCREMENT COMMENT '模型属性ID',
  `modelid` int(11) NOT NULL DEFAULT '0' COMMENT '模型ID',
  `virtuename` char(32) NOT NULL DEFAULT '' COMMENT '模型属性名称',
  PRIMARY KEY (`virtueid`),
  KEY `modelid` (`modelid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- 转存表中的数据 `ts_apple_virtue`
--

INSERT INTO `ts_apple_virtue` (`virtueid`, `modelid`, `virtuename`) VALUES
(1, 1, '作者'),
(2, 1, '出版社'),
(3, 1, '出版年'),
(4, 1, '页数'),
(5, 1, '定价'),
(6, 1, '装帧'),
(7, 1, 'ISBN'),
(8, 2, '导演'),
(9, 2, '编剧'),
(10, 2, '主演'),
(11, 2, '类型'),
(12, 2, '制片国家/地区'),
(13, 2, '语言'),
(14, 2, '上映日期'),
(15, 2, '片长');

-- --------------------------------------------------------

--
-- 表的结构 `ts_area`
--

CREATE TABLE IF NOT EXISTS `ts_area` (
  `areaid` int(11) NOT NULL AUTO_INCREMENT,
  `areaname` varchar(32) NOT NULL DEFAULT '',
  `zm` char(1) NOT NULL DEFAULT '' COMMENT '首字母',
  `referid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`areaid`),
  KEY `referid` (`referid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='本地化' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ts_area`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_article`
--

CREATE TABLE IF NOT EXISTS `ts_article` (
  `articleid` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `cateid` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` char(64) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `isphoto` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有图片',
  `isattach` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有附件',
  `isaudit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`articleid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ts_article`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_article_cate`
--

CREATE TABLE IF NOT EXISTS `ts_article_cate` (
  `cateid` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `catename` char(16) NOT NULL DEFAULT '' COMMENT '分类名称',
  `orderid` int(11) NOT NULL DEFAULT '0' COMMENT '排序ID',
  PRIMARY KEY (`cateid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `ts_article_cate`
--

INSERT INTO `ts_article_cate` (`cateid`, `catename`, `orderid`) VALUES
(1, '默认分类', 0);

-- --------------------------------------------------------

--
-- 表的结构 `ts_article_comment`
--

CREATE TABLE IF NOT EXISTS `ts_article_comment` (
  `commentid` int(11) NOT NULL AUTO_INCREMENT,
  `articleid` int(11) NOT NULL DEFAULT '0' COMMENT '文章ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `content` text NOT NULL COMMENT '评论内容',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`commentid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章评论' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ts_article_comment`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_attach`
--

CREATE TABLE IF NOT EXISTS `ts_attach` (
  `attachid` int(11) NOT NULL AUTO_INCREMENT COMMENT '附件ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `attachname` char(64) NOT NULL COMMENT '附件名字',
  `attachtype` char(32) NOT NULL DEFAULT '' COMMENT '附件类型',
  `attachurl` char(64) NOT NULL DEFAULT '' COMMENT '附件url',
  `attachsize` char(32) NOT NULL DEFAULT '' COMMENT '附件大小',
  `isshow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`attachid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ts_attach`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_event`
--

CREATE TABLE IF NOT EXISTS `ts_event` (
  `eventid` int(11) NOT NULL AUTO_INCREMENT COMMENT '活动ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `groupid` int(11) NOT NULL DEFAULT '0' COMMENT '小组ID',
  `typeid` int(11) NOT NULL DEFAULT '0' COMMENT '活动类型ID',
  `title` char(120) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `time_start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '开始时间',
  `time_end` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结束时间',
  `path` char(32) NOT NULL DEFAULT '' COMMENT '图片路劲',
  `poster` char(16) NOT NULL DEFAULT '' COMMENT '海报图片',
  `areaid` int(11) NOT NULL DEFAULT '0' COMMENT '县区ID',
  `address` char(120) NOT NULL DEFAULT '' COMMENT '详细地址',
  `count_userdo` int(11) NOT NULL DEFAULT '0' COMMENT '统计参加的',
  `count_userwish` int(11) NOT NULL DEFAULT '0' COMMENT '统计感兴趣的',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐0默认1推荐',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`eventid`),
  KEY `areaid` (`areaid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活动' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ts_event`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_event_comment`
--

CREATE TABLE IF NOT EXISTS `ts_event_comment` (
  `commentid` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `referid` int(11) NOT NULL DEFAULT '0',
  `eventid` int(11) NOT NULL DEFAULT '0' COMMENT '活动ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `content` text NOT NULL COMMENT '回复内容',
  `addtime` int(11) DEFAULT '0' COMMENT '回复时间',
  PRIMARY KEY (`commentid`),
  KEY `eventid` (`eventid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='话题回复/评论' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ts_event_comment`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_event_group_index`
--

CREATE TABLE IF NOT EXISTS `ts_event_group_index` (
  `eventid` int(11) NOT NULL DEFAULT '0' COMMENT '活动ID',
  `groupid` int(11) NOT NULL DEFAULT '0' COMMENT '小组ID',
  UNIQUE KEY `eventid_2` (`eventid`,`groupid`),
  KEY `eventid` (`eventid`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活动小组索引表';

--
-- 转存表中的数据 `ts_event_group_index`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_event_type`
--

CREATE TABLE IF NOT EXISTS `ts_event_type` (
  `typeid` int(11) NOT NULL AUTO_INCREMENT COMMENT '类型ID',
  `typename` varchar(64) NOT NULL DEFAULT '' COMMENT '类型名称',
  PRIMARY KEY (`typeid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='话题类型' AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `ts_event_type`
--

INSERT INTO `ts_event_type` (`typeid`, `typename`) VALUES
(1, '音乐/演出'),
(2, '展览'),
(3, '电影'),
(4, '讲座/沙龙'),
(5, '戏剧/曲艺'),
(6, '生活/聚会'),
(7, '体育'),
(8, '旅行'),
(9, '公益'),
(10, '其他');

-- --------------------------------------------------------

--
-- 表的结构 `ts_event_users`
--

CREATE TABLE IF NOT EXISTS `ts_event_users` (
  `eventid` int(11) NOT NULL DEFAULT '0' COMMENT '活动ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0加入，1感兴趣',
  `isorganizer` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是组织者:0不是1是',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  KEY `eventid` (`eventid`,`status`),
  KEY `userid` (`userid`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活动用户';

--
-- 转存表中的数据 `ts_event_users`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_feed`
--

CREATE TABLE IF NOT EXISTS `ts_feed` (
  `feedid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `template` varchar(1024) NOT NULL DEFAULT '' COMMENT '动态模板',
  `data` varchar(1024) NOT NULL DEFAULT '' COMMENT '动态数据',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`feedid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全站动态' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ts_feed`
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='小组' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ts_group`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_group_cates`
--

CREATE TABLE IF NOT EXISTS `ts_group_cates` (
  `cateid` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `catename` char(32) NOT NULL DEFAULT '' COMMENT '分类名字',
  `catereferid` int(11) NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `count_group` int(11) NOT NULL DEFAULT '0' COMMENT '群组个数',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  PRIMARY KEY (`cateid`),
  KEY `referid` (`catereferid`)
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
(1, 0, 1, 1, 'fsfsd', 'fsffsd<br />', 1, 3, 0, 0, 0, 0, 0, 0, 0, 0, 1329490342, 1329490345);

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='话题回复/评论' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ts_group_topics_comments`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_group_topics_type`
--

CREATE TABLE IF NOT EXISTS `ts_group_topics_type` (
  `typeid` int(11) NOT NULL AUTO_INCREMENT COMMENT '帖子分类ID',
  `groupid` int(11) NOT NULL DEFAULT '0' COMMENT '小组ID',
  `typename` char(32) NOT NULL DEFAULT '' COMMENT '帖子分类名称',
  `count_topic` int(11) NOT NULL DEFAULT '0' COMMENT '统计帖子',
  PRIMARY KEY (`typeid`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='帖子分类' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ts_group_topics_type`
--


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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='短消息表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `ts_message`
--

INSERT INTO `ts_message` (`messageid`, `userid`, `touserid`, `content`, `isread`, `addtime`) VALUES
(1, 0, 2, '亲爱的 thinksaas ：<br />您成功加入了 ThinkSAAS<br />在遵守本站的规定的同时，享受您的愉快之旅吧!', 1, 1329483501),
(2, 0, 3, '亲爱的 tsadmin ：<br />您成功加入了 ThinkSAAS<br />在遵守本站的规定的同时，享受您的愉快之旅吧!', 1, 1329570055),
(3, 0, 2, '亲爱的 thinksaas ：<br />您成功加入了 ThinkSAAS<br />在遵守本站的规定的同时，享受您的愉快之旅吧!', 1, 1329570669);

-- --------------------------------------------------------

--
-- 表的结构 `ts_photo`
--

CREATE TABLE IF NOT EXISTS `ts_photo` (
  `photoid` int(11) NOT NULL AUTO_INCREMENT,
  `albumid` int(11) NOT NULL DEFAULT '0' COMMENT '相册ID',
  `userid` int(11) NOT NULL DEFAULT '0',
  `photoname` char(64) NOT NULL DEFAULT '',
  `phototype` char(32) NOT NULL DEFAULT '',
  `path` char(32) NOT NULL DEFAULT '' COMMENT '图片路径',
  `photourl` char(120) NOT NULL DEFAULT '',
  `photosize` char(32) NOT NULL DEFAULT '',
  `photodesc` char(120) NOT NULL DEFAULT '',
  `count_view` int(11) NOT NULL DEFAULT '0',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0不推荐1推荐',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`photoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ts_photo`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_photo_album`
--

CREATE TABLE IF NOT EXISTS `ts_photo_album` (
  `albumid` int(11) NOT NULL AUTO_INCREMENT COMMENT '相册ID',
  `userid` int(11) NOT NULL DEFAULT '0',
  `path` char(32) NOT NULL DEFAULT '' COMMENT '相册路径',
  `albumface` char(64) NOT NULL DEFAULT '' COMMENT '相册封面',
  `albumname` char(64) NOT NULL DEFAULT '',
  `albumdesc` varchar(400) NOT NULL DEFAULT '' COMMENT '相册介绍',
  `count_photo` int(11) NOT NULL DEFAULT '0',
  `count_view` int(11) NOT NULL DEFAULT '0',
  `isrecommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`albumid`),
  KEY `userid` (`userid`),
  KEY `isrecommend` (`isrecommend`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='相册' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ts_photo_album`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_photo_comment`
--

CREATE TABLE IF NOT EXISTS `ts_photo_comment` (
  `commentid` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `referid` int(11) NOT NULL DEFAULT '0',
  `photoid` int(11) NOT NULL DEFAULT '0' COMMENT '相册ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `content` char(255) NOT NULL DEFAULT '' COMMENT '回复内容',
  `addtime` int(11) DEFAULT '0' COMMENT '回复时间',
  PRIMARY KEY (`commentid`),
  KEY `userid` (`userid`),
  KEY `referid` (`referid`,`photoid`),
  KEY `photoid` (`photoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='图片回复/评论' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ts_photo_comment`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_photo_options`
--

CREATE TABLE IF NOT EXISTS `ts_photo_options` (
  `optionid` int(11) NOT NULL AUTO_INCREMENT COMMENT '选项ID',
  `optionname` char(16) NOT NULL DEFAULT '' COMMENT '选项名字',
  `optionvalue` char(255) NOT NULL DEFAULT '' COMMENT '选项内容',
  PRIMARY KEY (`optionid`),
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='配置' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `ts_photo_options`
--

INSERT INTO `ts_photo_options` (`optionid`, `optionname`, `optionvalue`) VALUES
(1, 'appname', '相册'),
(2, 'appdesc', '相册APP');

-- --------------------------------------------------------

--
-- 表的结构 `ts_search_key`
--

CREATE TABLE IF NOT EXISTS `ts_search_key` (
  `keyid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `keyword` char(32) NOT NULL DEFAULT '',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`keyid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='搜索关键词' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ts_search_key`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_system_options`
--

CREATE TABLE IF NOT EXISTS `ts_system_options` (
  `optionname` char(32) NOT NULL DEFAULT '' COMMENT '选项名字',
  `optionvalue` char(255) NOT NULL DEFAULT '' COMMENT '选项内容',
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统管理配置';

--
-- 转存表中的数据 `ts_system_options`
--

INSERT INTO `ts_system_options` (`optionname`, `optionvalue`) VALUES
('site_title', 'ThinkSAAS'),
('site_subtitle', '又一个ThinkSAAS社区'),
('site_url', 'http://localhost/thinksaas/git/ThinkSAAS/'),
('site_email', 'admin@admin.com'),
('site_icp', '京ICP备09050100号'),
('isface', '0'),
('site_key', 'thinksaas'),
('site_desc', '又一个ThinkSAAS社区'),
('site_theme', 'black'),
('site_urltype', '1'),
('isgzip', '0'),
('timezone', 'Asia/Hong_Kong'),
('isinvite', '0');

-- --------------------------------------------------------

--
-- 表的结构 `ts_tag`
--

CREATE TABLE IF NOT EXISTS `ts_tag` (
  `tagid` int(11) NOT NULL AUTO_INCREMENT,
  `tagname` char(16) NOT NULL DEFAULT '',
  `count_user` int(11) NOT NULL DEFAULT '0',
  `count_group` int(11) NOT NULL DEFAULT '0',
  `count_topic` int(11) NOT NULL DEFAULT '0',
  `count_bang` int(11) NOT NULL DEFAULT '0',
  `count_article` int(11) NOT NULL DEFAULT '0',
  `isenable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否可用',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`tagid`),
  UNIQUE KEY `tagname` (`tagname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ts_tag`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_tag_article_index`
--

CREATE TABLE IF NOT EXISTS `ts_tag_article_index` (
  `articleid` int(11) NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `tagid` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `articleid_2` (`articleid`,`tagid`),
  KEY `articleid` (`articleid`),
  KEY `tagid` (`tagid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ts_tag_article_index`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_tag_group_index`
--

CREATE TABLE IF NOT EXISTS `ts_tag_group_index` (
  `groupid` int(11) NOT NULL DEFAULT '0',
  `tagid` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `groupid_2` (`groupid`,`tagid`),
  KEY `groupid` (`groupid`),
  KEY `tagid` (`tagid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ts_tag_group_index`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_tag_topic_index`
--

CREATE TABLE IF NOT EXISTS `ts_tag_topic_index` (
  `topicid` int(11) NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `tagid` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `topicid_2` (`topicid`,`tagid`),
  KEY `topicid` (`topicid`),
  KEY `tagid` (`tagid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ts_tag_topic_index`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_tag_user_index`
--

CREATE TABLE IF NOT EXISTS `ts_tag_user_index` (
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `tagid` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `userid_2` (`userid`,`tagid`),
  KEY `userid` (`userid`),
  KEY `tagid` (`tagid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ts_tag_user_index`
--


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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ts_user`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_user_follow`
--

CREATE TABLE IF NOT EXISTS `ts_user_follow` (
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `userid_follow` int(11) NOT NULL DEFAULT '0' COMMENT '被关注的用户ID',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  UNIQUE KEY `userid_2` (`userid`,`userid_follow`),
  KEY `userid` (`userid`),
  KEY `userid_follow` (`userid_follow`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户关注跟随';

--
-- 转存表中的数据 `ts_user_follow`
--


-- --------------------------------------------------------

--
-- 表的结构 `ts_user_info`
--

CREATE TABLE IF NOT EXISTS `ts_user_info` (
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `fuserid` int(11) NOT NULL DEFAULT '0' COMMENT '来自邀请用户',
  `username` char(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `email` char(32) NOT NULL DEFAULT '',
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别',
  `phone` char(16) NOT NULL DEFAULT '' COMMENT '电话号码',
  `roleid` int(11) NOT NULL DEFAULT '1' COMMENT '角色ID',
  `areaid` int(11) NOT NULL DEFAULT '0' COMMENT '区县ID',
  `path` char(32) NOT NULL DEFAULT '' COMMENT '头像路径',
  `face` char(64) NOT NULL DEFAULT '' COMMENT '会员头像',
  `signed` char(64) NOT NULL DEFAULT '' COMMENT '签名',
  `blog` char(32) NOT NULL DEFAULT '' COMMENT '博客',
  `about` char(255) NOT NULL DEFAULT '' COMMENT '关于我',
  `ip` varchar(16) NOT NULL DEFAULT '' COMMENT '登陆IP',
  `address` char(64) NOT NULL DEFAULT '',
  `qq_openid` char(32) NOT NULL DEFAULT '',
  `qq_token` char(32) NOT NULL DEFAULT '',
  `qq_secret` char(32) NOT NULL DEFAULT '',
  `count_score` int(11) NOT NULL DEFAULT '0' COMMENT '统计积分',
  `count_follow` int(11) NOT NULL DEFAULT '0' COMMENT '统计用户跟随的',
  `count_followed` int(11) NOT NULL DEFAULT '0' COMMENT '统计用户被跟随的',
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


-- --------------------------------------------------------

--
-- 表的结构 `ts_user_invites`
--

CREATE TABLE IF NOT EXISTS `ts_user_invites` (
  `inviteid` int(11) NOT NULL AUTO_INCREMENT,
  `invitecode` char(32) NOT NULL DEFAULT '' COMMENT '邀请码',
  `isused` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否使用',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`inviteid`),
  KEY `isused` (`isused`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户邀请码' AUTO_INCREMENT=51 ;

--
-- 转存表中的数据 `ts_user_invites`
--

INSERT INTO `ts_user_invites` (`inviteid`, `invitecode`, `isused`, `addtime`) VALUES
(1, 'c9JiDwCiJJDvJyxXsw', 0, 1329570629),
(2, '3Uii75pFpSzUQ00mfc', 0, 1329570629),
(3, '6BaACtC1DcbDCpA6q2', 0, 1329570629),
(4, 'IqOXxB6NCK6i0ohcQQ', 0, 1329570629),
(5, 'A363RMDs74jz2JMYe2', 0, 1329570629),
(6, 'Qr4ztlFuBlMLBgBoSo', 0, 1329570629),
(7, 'slth30He7UvFZed0zS', 0, 1329570629),
(8, 'TGVGvKteC0KekXVtKK', 0, 1329570629),
(9, '3yChyP3NPwT0LyTniW', 0, 1329570629),
(10, '7d57L9bf7HKFd9ol77', 0, 1329570629),
(11, 'UIXgPLiGwXzGZk7WPl', 0, 1329570629),
(12, 'mJSJsQJXcDnIQq164i', 0, 1329570629),
(13, 'MQdJMMXnX1jOmI5qni', 0, 1329570629),
(14, '3ge770R0Fw3fXw10FF', 0, 1329570629),
(15, 'IJfZZmgFxiT1F1zXLR', 0, 1329570629),
(16, 'KzHKCrhSmUjmOak8Mr', 0, 1329570629),
(17, 'di4119B18Jgc1CNTN6', 0, 1329570629),
(18, 'S4ciNsSVcFcc9L4L6p', 0, 1329570629),
(19, '0a5C7VXJwl7t7P2yw5', 0, 1329570629),
(20, 'G1CX5MMHZ5MQvXh5oc', 0, 1329570629),
(21, 'iWiKDEMtkdBhCztMz3', 0, 1329570629),
(22, 'tXxsiI00a88LRrLl8s', 0, 1329570629),
(23, 'Eq07gD0ZRrE3HhGYZ7', 0, 1329570629),
(24, 'NVquzNY5NzN8ynYnyo', 0, 1329570629),
(25, '84c2O25IGioI221Se2', 0, 1329570629),
(26, 'O8UU444Nm6LN1Q1o4O', 0, 1329570629),
(27, '67GbbbIlLLXS9r7lHb', 0, 1329570629),
(28, '2f1m2nfS9GtbnK227b', 0, 1329570629),
(29, '4qShfU8KQzmu4bBOBz', 0, 1329570629),
(30, 'NOZsbq5OGNq56N6o06', 0, 1329570629),
(31, 'DHd925eeK5z0tKD5T0', 0, 1329570629),
(32, 'tD769yTFruc5QACdaA', 0, 1329570629),
(33, 'ZXYYVMslcm43I3QrR4', 0, 1329570629),
(34, 'UT77d5S7NVmIuNuI5i', 0, 1329570629),
(35, 'pwZ42L2l23GCZ8xu4w', 0, 1329570629),
(36, 'Pa7JWR7w52F7imwisp', 0, 1329570629),
(37, 'f5CmT55FeDsRYA7cRd', 0, 1329570629),
(38, 'qK3UgdTqYydoPuyOyx', 0, 1329570629),
(39, '5bp8BIywpIv8kKoDTD', 0, 1329570629),
(40, 'qaq7i7Sq61I1cZo7cA', 0, 1329570629),
(41, 'l2H3u0tt2Ii2MLGI2p', 0, 1329570629),
(42, 'K1KKk5dqSF251GSmM1', 0, 1329570629),
(43, '77Qus1Ufsw7K1xeE6F', 0, 1329570629),
(44, '4hR33KN3kN00BHk7lh', 0, 1329570629),
(45, '8p0WEYOmgolsoY2KP2', 0, 1329570629),
(46, 'nnofTNsNdftujv3hho', 0, 1329570629),
(47, 'Lm18kD067K19x018nN', 0, 1329570629),
(48, 'y7oUhgUuRCgdu1d1Dc', 0, 1329570629),
(49, 'TiB7OGDycg9G19oTPu', 0, 1329570629),
(50, 'Q46Hr2JnwKHN554Nw2', 0, 1329570629);

-- --------------------------------------------------------

--
-- 表的结构 `ts_user_options`
--

CREATE TABLE IF NOT EXISTS `ts_user_options` (
  `optionname` char(12) NOT NULL DEFAULT '' COMMENT '选项名字',
  `optionvalue` char(255) NOT NULL DEFAULT '' COMMENT '选项内容',
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='配置';

--
-- 转存表中的数据 `ts_user_options`
--

INSERT INTO `ts_user_options` (`optionname`, `optionvalue`) VALUES
('appname', '用户'),
('appdesc', '用户中心'),
('isenable', '0'),
('isvalidate', '0'),
('isrewrite', '0'),
('isauthcode', '0'),
('isgroup', '');

-- --------------------------------------------------------

--
-- 表的结构 `ts_user_role`
--

CREATE TABLE IF NOT EXISTS `ts_user_role` (
  `roleid` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `rolename` char(32) NOT NULL DEFAULT '' COMMENT '角色名称',
  PRIMARY KEY (`roleid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='角色' AUTO_INCREMENT=18 ;

--
-- 转存表中的数据 `ts_user_role`
--

INSERT INTO `ts_user_role` (`roleid`, `rolename`) VALUES
(1, '列兵'),
(2, '下士'),
(3, '中士'),
(4, '上士'),
(5, '三级准尉'),
(6, '二级准尉'),
(7, '一级准尉'),
(8, '少尉'),
(9, '中尉'),
(10, '上尉'),
(11, '少校'),
(12, '中校'),
(13, '上校'),
(14, '准将'),
(15, '少将'),
(16, '中将'),
(17, '上将');

-- --------------------------------------------------------

--
-- 表的结构 `ts_user_scores`
--

CREATE TABLE IF NOT EXISTS `ts_user_scores` (
  `scoreid` int(11) NOT NULL AUTO_INCREMENT COMMENT '积分ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `scorename` char(64) NOT NULL DEFAULT '' COMMENT '积分说明',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '得分',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '积分时间',
  PRIMARY KEY (`scoreid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户积分' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ts_user_scores`
--

