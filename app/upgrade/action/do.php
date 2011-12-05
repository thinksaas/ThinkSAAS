<?php
defined('IN_TS') or die('Access Denied.');
//1.5-1126到1.5-1203升级


if($db->once_num_rows("DESCRIBE ".dbprefix."tag count_article")== '0'){
	$db->query("ALTER TABLE `".dbprefix."tag` ADD `count_article` INT( 11 ) NOT NULL DEFAULT '0' COMMENT 
'统计文章' AFTER `count_topic`;");
}

//添加几个表
$db->query("DROP TABLE IF EXISTS `".dbprefix."article`, `".dbprefix."article_cate`,`".dbprefix."article_comment`,`".dbprefix."feed`,`".dbprefix."tag_article_index`");

//添加ts_article
$db->query("CREATE TABLE IF NOT EXISTS `".dbprefix."article` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

$db->query("CREATE TABLE IF NOT EXISTS `".dbprefix."article_cate` (
  `cateid` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `catename` char(16) NOT NULL DEFAULT '' COMMENT '分类名称',
  `orderid` int(11) NOT NULL DEFAULT '0' COMMENT '排序ID',
  PRIMARY KEY (`cateid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

$db->query("CREATE TABLE IF NOT EXISTS `".dbprefix."article_comment` (
  `commentid` int(11) NOT NULL AUTO_INCREMENT,
  `articleid` int(11) NOT NULL DEFAULT '0' COMMENT '文章ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `content` text NOT NULL COMMENT '评论内容',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`commentid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章评论' AUTO_INCREMENT=1 ;");

$db->query("CREATE TABLE IF NOT EXISTS `".dbprefix."feed` (
  `feedid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `template` varchar(300) NOT NULL DEFAULT '' COMMENT '动态模板',
  `data` varchar(600) NOT NULL DEFAULT '' COMMENT '动态数据',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`feedid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='全站动态' AUTO_INCREMENT=1 ;");

$db->query("CREATE TABLE IF NOT EXISTS `".dbprefix."tag_article_index` (
  `articleid` int(11) NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `tagid` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `articleid_2` (`articleid`,`tagid`),
  KEY `articleid` (`articleid`),
  KEY `tagid` (`tagid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");


//////////////////////////////////此处向下不用修改////////////////////////////////////

//更新系统缓存信息
$arrOptions = $db->fetch_all_assoc("select optionname,optionvalue from ".dbprefix."system_options");
foreach($arrOptions as $item){
		$arrOption[$item['optionname']] = $item['optionvalue'];
}
AppCacheWrite($arrOption,'system','options.php');

//删除data/up.php文件
unlink('data/up.php');

//删除模板缓存
delDirFile('cache/template');

//升级完成后跳转到首页
header("Location: ".SITE_URL.'index.php');