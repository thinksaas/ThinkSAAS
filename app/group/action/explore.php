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
$arrNewTopics = $new['group']->findAll('group_topic',array(
	'isaudit'=>0,
),'uptime desc',null,30);

foreach($arrNewTopics as $key=>$item){
	$arrTopic[] = $item;
	$arrTopic[$key]['title']=htmlspecialchars($item['title']);
	$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
	$arrTopic[$key]['group'] = aac('group')->getOneGroup($item['groupid']);
}

//最新标签
$arrTag = $new['group']->findAll('tag',null,'count_topic desc',null,30);

//社区精华帖
$arrPosts = $new['group']->findAll('group_topic',array(
	'isposts'=>1,
),'uptime desc',null,15);

$title = '随便看看';
include template("explore");