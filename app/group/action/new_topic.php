<?php
defined('IN_TS') or die('Access Denied.');

$userid = intval($TS_USER['user']['userid']);

if($userid == 0){
	header("Location: ".SITE_URL.tsurl('user','login'));
	exit;
}

$groupid = intval($_GET['groupid']);

//小组数目
$groupNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."group where groupid='$groupid'");

if($groupNum['count(*)'] == 0){
	header("Location: ".SITE_URL);
	exit;
}

//小组会员
$isGroupUser = $db->once_fetch_assoc("select count(*) from ".dbprefix."group_users where userid='$userid' and groupid='$groupid'");

$strGroup = $db->once_fetch_assoc("select * from ".dbprefix."group where groupid='$groupid'");

//允许小组成员发帖
if($strGroup['ispost']==0 && $isGroupUser['count(*)'] == 0 && $userid != $strGroup['userid']){
	
	qiMsg("本小组只允许小组成员发贴，请加入小组后再发帖！");
	
}

//不允许小组成员发帖
if($strGroup['ispost'] == 1 && $userid != $strGroup['userid']){
	qiMsg("本小组只允许小组组长发帖！");
}

//帖子类型
$arrGroupType = $db->fetch_all_assoc("select * from ".dbprefix."group_topics_type where groupid='".$strGroup['groupid']."'");

$title = '发布帖子';

//包含模版
include template("new_topic");