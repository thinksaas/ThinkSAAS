ALTER TABLE  `ts_article` ADD  `locationid` INT( 11 ) NOT NULL DEFAULT  '0' COMMENT  '同城ID' AFTER  `userid` ;
ALTER TABLE  `ts_attach` ADD  `locationid` INT( 11 ) NOT NULL DEFAULT  '0' COMMENT  '同城ID' AFTER  `userid` ;
ALTER TABLE  `ts_group_topic` ADD  `locationid` INT( 11 ) NOT NULL DEFAULT  '0' COMMENT  '同城ID' AFTER  `userid` ;
ALTER TABLE  `ts_photo` ADD  `locationid` INT( 11 ) NOT NULL DEFAULT  '0' COMMENT  '同城iD' AFTER  `userid` ;
ALTER TABLE  `ts_user_info` ADD  `locationid` INT( 11 ) NOT NULL DEFAULT  '0' COMMENT  '同城ID' AFTER  `userid` ;
ALTER TABLE  `ts_weibo` ADD  `locationid` INT( 11 ) NOT NULL DEFAULT  '0' COMMENT  '同城ID' AFTER  `userid` ;

CREATE TABLE IF NOT EXISTS `ts_location` (
  `locationid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `title` char(64) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容介绍',
  `path` char(32) NOT NULL DEFAULT '' COMMENT '路径',
  `photo` char(32) NOT NULL DEFAULT '' COMMENT '图片',
  `orderid` int(11) NOT NULL DEFAULT '0' COMMENT '排序ID',
  PRIMARY KEY (`locationid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='同城' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `ts_attach_options` (
  `optionname` char(12) NOT NULL DEFAULT '' COMMENT '选项名字',
  `optionvalue` char(255) NOT NULL DEFAULT '' COMMENT '选项内容',
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='配置';

CREATE TABLE IF NOT EXISTS `ts_feed_options` (
  `optionname` char(12) NOT NULL DEFAULT '' COMMENT '选项名字',
  `optionvalue` char(255) NOT NULL DEFAULT '' COMMENT '选项内容',
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='配置';

CREATE TABLE IF NOT EXISTS `ts_redeem_options` (
  `optionname` char(12) NOT NULL DEFAULT '' COMMENT '选项名字',
  `optionvalue` char(255) NOT NULL DEFAULT '' COMMENT '选项内容',
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='配置';

CREATE TABLE IF NOT EXISTS `ts_weibo_options` (
  `optionname` char(12) NOT NULL DEFAULT '' COMMENT '选项名字',
  `optionvalue` char(255) NOT NULL DEFAULT '' COMMENT '选项内容',
  UNIQUE KEY `optionname` (`optionname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='配置';


ALTER TABLE  `ts_article` ADD INDEX (  `locationid` ) ;
ALTER TABLE  `ts_attach` ADD INDEX (  `locationid` ) ;
ALTER TABLE  `ts_group_topic` ADD INDEX (  `locationid` ) ;
ALTER TABLE  `ts_photo` ADD INDEX (  `locationid` ) ;
ALTER TABLE  `ts_user_info` ADD INDEX (  `locationid` ) ;
ALTER TABLE  `ts_weibo` ADD INDEX (  `locationid` ) ;


CREATE TABLE IF NOT EXISTS `ts_slide` (
  `slideid` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(128) NOT NULL DEFAULT '',
  `url` char(128) NOT NULL DEFAULT '',
  `path` char(32) NOT NULL DEFAULT '',
  `photo` char(32) NOT NULL DEFAULT '',
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`slideid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='轮播' AUTO_INCREMENT=4 ;

INSERT INTO `ts_slide` (`slideid`, `title`, `url`, `path`, `photo`, `addtime`) VALUES
(1, 'ThinkSAAS开源社区', 'http://www.thinksaas.cn', '0/0', '0/0/1.gif', 1392396528),
(2, 'ThinkSAAS开源社区', 'http://www.thinksaas.cn', '0/0', '0/0/2.gif', 1392396573),
(3, 'ThinkSAAS开源社区', 'http://www.thinksaas.cn', '0/0', '0/0/3.gif', 1392396587);