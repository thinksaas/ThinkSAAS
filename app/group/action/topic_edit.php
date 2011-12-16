<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 小组话题编辑
 */

$topicid = intval($_GET['topicid']);

if($topicid == 0){
	header("Location: ".SITE_URL);
	exit;
}

$userid = intval($TS_USER['user']['userid']);

if($userid == 0){
	header("Location: ".SITE_URL);
	exit;
}

$topicNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."group_topics where `topicid`='$topicid'");

if($topicNum['count(*)']==0){
	header("Location: ".SITE_URL);
	exit;
}



$strTopic = $db->once_fetch_assoc("select * from ".dbprefix."group_topics where `topicid`='".$topicid."'");

$strGroup = $db->once_fetch_assoc("select * from ".dbprefix."group where `groupid`='".$strTopic['groupid']."'");


if($strTopic['userid'] == $userid || $strGroup['userid']==$userid || $TS_USER['user']['isadmin']==1){

	$strTopic['content'] = htmlspecialchars($strTopic['content']);

	//帖子类型
	$arrGroupType = $db->fetch_all_assoc("select * from ".dbprefix."group_topics_type where `groupid`='".$strGroup['groupid']."'");

	$title = '编辑帖子';

	include template("topic_edit");
	
}else{

	header("Location: ".SITE_URL);
	exit;

}