<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 我的小组发言
 */

$arrTopics = $db->fetch_all_assoc("select topicid,groupid,userid,title,count_comment,addtime,uptime from ".dbprefix."group_topics where userid='".$TS_USER['user']['userid']."' order by addtime desc limit 30");
foreach($arrTopics as $key=>$item){
	$arrTopic[] = $item;
	$arrTopic[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
	$arrTopic[$key]['group'] = aac('group')->getSimpleGroup($item['groupid']);
}

$title = '我的小组发言';

include template("my_topic");