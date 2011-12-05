<?php
defined('IN_TS') or die('Access Denied.');

//活跃会员
$arrHotUser = aac('user')->getHotUser(16);

//最新会员
$arrNewUser = aac('user')->getNewUser(8);

//推荐小组列表
$arrRecommendGroup = $new['group']->getRecommendGroup(16);
//最新10个小组
$arrNewGroup = $new['group']->getNewGroup(10);


//最新帖子
$arrNewTopics = $db->fetch_all_assoc("select topicid,userid,groupid,title,count_comment,count_view,istop,isphoto,isattach,addtime,uptime from ".dbprefix."group_topics order by uptime desc limit 30");
foreach($arrNewTopics as $key=>$item){
	$arrTopic[] = $item;
	$arrTopic[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
	$arrTopic[$key]['group'] = aac('group')->getSimpleGroup($item['groupid']);
}


//最新标签
$arrTag = $db->fetch_all_assoc("select * from ".dbprefix."tag order by count_topic desc limit 30");

//社区精华帖
$arrPosts = $db->fetch_all_assoc("select topicid,title from ".dbprefix."group_topics where isposts='1' order by uptime desc limit 15");

$title = '随便看看';
include template("explore");