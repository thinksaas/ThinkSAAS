<?php
defined('IN_TS') or die('Access Denied.');

//全部讨论组
$arrDiscuss = $new['discuss']->getArrDiscuss();

//最新发表和回复的帖子
$arrNewTopics = $db->fetch_all_assoc("select topicid,userid,groupid,title,count_comment,count_view,istop,isposts,addtime,uptime from ".dbprefix."discuss_topics where isshow='0' order by istop desc,uptime desc limit 30");
foreach($arrNewTopics as $key=>$item){
	$arrNewTopic[] = $item;
	$arrNewTopic[$key]['user'] = aac('user')->getUserForApp($item['userid']);
}

$title = L::config_appname;

include template("index");