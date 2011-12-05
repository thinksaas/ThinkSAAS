<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 我收藏的话题
 */
 
$userid = $TS_USER['user']['userid'];
 
$arrCollect = $db->fetch_all_assoc("select * from ".dbprefix."group_topics_collects where userid='".$userid."' order by addtime desc limit 30");

foreach($arrCollect as $item){

	$strTopic = $db->once_fetch_assoc("select topicid,userid,groupid,title,count_comment,count_view,isphoto,isattach,addtime,uptime from ".dbprefix."group_topics where topicid = '".$item['topicid']."'");
	$arrTopics[] = $strTopic;
	
}

foreach($arrTopics as $key=>$item){
	$arrTopic[] = $item;
	$arrTopic[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
	$arrTopic[$key]['group'] = aac('group')->getSimpleGroup($item['groupid']);
}

$title = '我收藏的话题';

include template("my_collect");