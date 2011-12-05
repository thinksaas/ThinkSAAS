<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 我的小组发言
 */
 
$myTopics = $db->fetch_all_assoc("select topicid from ".dbprefix."group_topics_comments where userid='".$TS_USER['user']['userid']."' group by topicid order by addtime desc limit 30");


foreach($myTopics as $item){

	$strTopic = $db->once_fetch_assoc("select topicid,userid,groupid,title,count_comment,count_view,isphoto,isattach,addtime,uptime from ".dbprefix."group_topics where topicid = '".$item['topicid']."'");
	$arrTopics[] = $strTopic;
	
}

foreach($arrTopics as $key=>$item){
	$arrTopic[] = $item;
	$arrTopic[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
	$arrTopic[$key]['group'] = aac('group')->getSimpleGroup($item['groupid']);
}

$title='我最近回应的话题';

include template("my_reply");