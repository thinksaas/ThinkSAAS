<?php 
defined('IN_TS') or die('Access Denied.');

$name = urldecode(tsFilter($_GET['id']));

//$name=mb_convert_encoding($name,'UTF-8', 'GB2312'); 

$tagid = aac('tag')->getTagId(t($name));

if($tagid==0){
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	$title = '404';
	include pubTemplate("404");
	exit;
}

$strTag = $new['group']->find('tag',array(
	'tagid'=>$tagid,
));

$strTag['tagname'] = htmlspecialchars($strTag['tagname']); 


//小组
$arrGroupTagId = $new['group']->findAll('tag_group_index',array(
	'tagid'=>$tagid,
));
if($arrGroupTagId){
	foreach($arrGroupTagId as $key=>$item){
		$arrGroup[] = aac('group')->getOneGroup($item['groupid']);
	}
}


$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$url = tsUrl('group','tag',array('id'=>urlencode($name),'page'=>''));

$lstart = $page*30-30;

$arrTagId = $new['group']->findAll('tag_topic_index',array(
	'tagid'=>$tagid,
),null,null,$lstart.',30');

foreach($arrTagId as $item){
	$strTopic = $new['group']->find('group_topic',array(
		'topicid'=>$item['topicid'],
	));
	if($strTopic==''){
		$new['group']->delete('tag_topic_index',array(
			'topicid'=>$item['topicid'],
			'tagid'=>$item['tagid'],
		));
	}
	
	if($strTopic){
		$arrTopics[] = $strTopic;
	}
}

$arrTagIds = $new['group']->findAll('tag_topic_index',array(
	'tagid'=>$tagid,
));
foreach($arrTagIds as $item){
	$strTopic = $new['group']->find('group_topic',array(
		'topicid'=>$item['topicid'],
	));
	if($strTopic==''){
		$new['group']->delete('tag_topic_index',array(
			'topicid'=>$item['topicid'],
			'tagid'=>$item['tagid'],
		));
	}
}

aac('tag')->countObjTag('topic',$tagid);

$topicNum = $new['group']->findCount('tag_topic_index',array(
	'tagid'=>$tagid,
));

$pageUrl = pagination($topicNum, 30, $page, $url);

foreach($arrTopics as $key=>$item){
	$arrTopic[] = $item;
	$arrTopic[$key]['title'] = htmlspecialchars($item['title']);
	$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
	$arrTopic[$key]['group'] = $new['group']->getOneGroup($item['groupid']);
}

//热门tag
$arrTag = $new['group']->findAll('tag',"`count_topic`!=''",'uptime desc',null,30);

$sitekey = $strTag['tagname'];
$title = $strTag['tagname'].'热门话题列表';

include template("tag");