<?php
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();

$myGroup = $db->fetch_all_assoc("select * from ".dbprefix."group_users where userid='$userid'");


//我加入的小组
if(is_array($myGroup)){
	foreach($myGroup as $key=>$item){
		$arrMyGroup[] = $new[group]->getOneGroup($item['groupid']);
	}
}

$myCreateGroup = $db->fetch_all_assoc("select * from ".dbprefix."group where userid='$userid'");
//我管理的小组
if(is_array($myCreateGroup)){
	foreach($myCreateGroup as $key=>$item){
		
		$arrMyAdminGroup[] = $new[group]->getOneGroup($item['groupid']);
		
	}
}

$title = '我的小组';

include template("my_group");