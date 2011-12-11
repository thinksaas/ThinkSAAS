<?php
defined('IN_TS') or die('Access Denied.');
//1.5-1203到1.5-1210升级


if($db->once_num_rows("DESCRIBE ".dbprefix."photo_album isrecommend")== '0'){
	$db->query("ALTER TABLE `".dbprefix."photo_album` ADD `isrecommend` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT '是否推荐' AFTER `count_view`;");
	$db->query("ALTER TABLE `ts_photo_album` ADD INDEX ( `isrecommend` );");
}

$db->query("ALTER TABLE `".dbprefix."feed` CHANGE `template` `template` VARCHAR( 1024 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '动态模板'");

$db->query("ALTER TABLE `".dbprefix."feed` CHANGE `data` `data` VARCHAR( 1024 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '动态数据'");

//////////////////////////////////此处向下不用修改////////////////////////////////////

//更新系统缓存信息
$arrOptions = $db->fetch_all_assoc("select optionname,optionvalue from ".dbprefix."system_options");
foreach($arrOptions as $item){
		$arrOption[$item['optionname']] = $item['optionvalue'];
}

fileWrite('system_options.php','data',$arrOption);

//删除data/up.php文件
unlink('data/up.php');

//删除模板缓存
delDirFile('cache/template');

//升级完成后跳转到首页
header("Location: ".SITE_URL.'index.php');