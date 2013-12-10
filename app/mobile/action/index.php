<?php
defined('IN_TS') or die('Access Denied.');

//推荐小组
$arrRecommendGroup = aac('group')->getRecommendGroup('12');

//最新帖子	
$arrNewTopics = aac('group')->findAll('group_topic',"`isaudit`='0'",'uptime desc',null,35);
foreach($arrNewTopics as $key=>$item){
	$arrTopic[] = $item;
	$arrTopic[$key]['title']=htmlspecialchars($item['title']);
	$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
}

$title = $TS_SITE['base']['site_title'].' - '.$TS_CF['info']['powered'];
include template('index');