<?php
defined('IN_TS') or die('Access Denied.');
//1.6到1.7升级

//1.7

if($db->once_num_rows("DESCRIBE ".dbprefix."user_info qqt_oauth_token") > 0){
	$db->query("ALTER TABLE `".dbprefix."user_info`
  DROP `qqt_oauth_token`,
  DROP `qqt_oauth_token_secret`;");
}

if($db->once_num_rows("DESCRIBE ".dbprefix."user_info qq_token") > 0){
	$db->query("ALTER TABLE `".dbprefix."user_info`
  DROP `qq_token`,
  DROP `qq_secret`;");
}

if($db->once_num_rows("DESCRIBE ".dbprefix."user_info qq_access_token") == 0){
	$db->query("ALTER TABLE `".dbprefix."user_info` ADD `qq_access_token` CHAR( 32 ) NOT NULL DEFAULT '' COMMENT 'access_token' AFTER `qq_openid` ");
}

if($db->once_num_rows("DESCRIBE ".dbprefix."user salt")== 0){
	$db->query("ALTER TABLE `".dbprefix."user` ADD `salt` CHAR( 32 ) NOT NULL DEFAULT '' COMMENT '加点盐' AFTER `pwd` ;");
}

if($db->once_num_rows("DESCRIBE ".dbprefix."user_options optionid") > 0){
	$db->query("ALTER TABLE `".dbprefix."user_options` DROP `optionid` ;");
}

if($db->once_num_rows("DESCRIBE ".dbprefix."system_options optionid") > 0){
	$db->query("ALTER TABLE `".dbprefix."system_options` DROP `optionid` ;");
}

if($db->once_num_rows("select * from ".dbprefix."system_options where `optionname`='isinvite'") == 0){
	$db->query("INSERT INTO`".dbprefix."system_options` (`optionname`, `optionvalue`) VALUES ('isinvite', '0');");
}

if($db->once_num_rows("select * from ".dbprefix."user_options where `optionname`='isregister'") > 0){
	$db->query("DELETE FROM `".dbprefix."user_options` WHERE `optionname` = 'isregister';");
}

if($db->once_num_rows("select * from ".dbprefix."system_options where `optionname`='lang'") > 0){
	$db->query("DELETE FROM `".dbprefix."system_options` WHERE `optionname` = 'lang';");
}

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
header("Location: ".SITE_URL);