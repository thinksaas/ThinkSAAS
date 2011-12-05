<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 小组话题编辑
 */

$topicid = intval($_GET['topicid']);

if($topicid == 0){
	qiMsg("非法操作！");
}

$userid = intval($TS_USER['user']['userid']);

if($userid == 0){
	qiMsg("非法操作！");
}

$strTopic = $db->once_fetch_assoc("select * from ".dbprefix."group_topics where topicid='".$topicid."'");
$strTopic['content'] = htmlspecialchars($strTopic['content']);

$strGroup = $db->once_fetch_assoc("select * from ".dbprefix."group where groupid='".$strTopic['groupid']."'");

$strGroupUser = $db->once_fetch_assoc("select * from ".dbprefix."group_users where userid='$userid' and groupid='".$strTopic['groupid']."'");

if($userid != $strTopic['userid'] && $userid!=$strGroup['userid'] && $strGroupUser =='' || $userid != $strTopic['userid'] && $userid!=$strGroup['userid'] && $strGroupUser !='' && $strGroupUser['isadmin']=='0'){
	qiMsg("机房重地，闲人免进！");
}

//帖子类型
$arrGroupType = $db->fetch_all_assoc("select * from ".dbprefix."group_topics_type where groupid='".$strGroup['groupid']."'");

$title = '编辑帖子';

include template("topic_edit");