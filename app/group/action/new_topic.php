<?php
defined('IN_TS') or die('Access Denied.');

$userid = intval($TS_USER['user']['userid']);

//用户信息

$groupid = intval($_GET['groupid']);

//小组数目
$groupNum = $db->once_num_rows("select * from ".dbprefix."group where groupid='$groupid'");

//小组会员
$isGroupUser = $db->once_num_rows("select * from ".dbprefix."group_users where userid='$userid' and groupid='$groupid'");

$strGroup = $db->once_fetch_assoc("select * from ".dbprefix."group where groupid='$groupid'");

if($userid == '0'){
	echo '0';return false; //未登陆不能发帖子^_^
}elseif($groupNum == '0'){
	echo '1';return false; //坏蛋加笨蛋的你是永远不能得逞滴^_^
}elseif($isGroupUser == '0'){
	echo '2';return false; //不是本小组成员不能发帖子
}elseif($strGroup['ispost'] == '1' && $strGroup['userid'] != $userid){
	echo '3';return false; //判断是否允许会员发帖子
}

//帖子类型
$arrGroupType = $db->fetch_all_assoc("select * from ".dbprefix."group_topics_type where groupid='".$strGroup['groupid']."'");

$title = '发布帖子';

//包含模版
include template("new_topic");