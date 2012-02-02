<?php
defined('IN_TS') or die('Access Denied.');

//推荐小组
$arrRecommendGroup = $new['group']->getRecommendGroup(4);

//最新发表和回复的帖子
$arrNewTopics = $db->fetch_all_assoc("select topicid,userid,groupid,title,count_comment,count_view,istop,isposts,addtime,uptime from ".dbprefix."group_topics where isshow='0' order by istop desc,uptime desc limit 30");
foreach($arrNewTopics as $key=>$item){
	$arrNewTopic[] = $item;
	$arrNewTopic[$key]['user'] = aac('user')->getUserForApp($item['userid']);
	$arrNewTopic[$key]['group'] = $new['group']->getOneGroup($item['groupid']);
}

//新成立小组
$arrNewGroup = $new['group']->getNewGroup(8);

$title = L::config_appname;

include template("index");