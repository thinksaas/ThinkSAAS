<?php 
defined('IN_TS') or die('Access Denied.');

if($TS_SITE['site_urltype']==1){
	$name = urldecode(tsUrlCheck(urlencode($_GET['id'])));
}else{
	$name = urldecode(tsUrlCheck($_GET['id']));
}

//$name=mb_convert_encoding($name,'UTF-8', 'GB2312'); //针对IIS环境可能出现的问题请取消此行注释

$strTag = aac('tag')->getTagByName($name);

$strTag['tagname'] = htmlspecialchars($strTag['tagname']); 

$page = tsIntval($_GET['page'],1);

$url = tsUrl('topic','tag',array('id'=>urlencode($name),'page'=>''));

$lstart = $page*30-30;

$tagid = $strTag['tagid'];

$arrTagId = $new['topic']->findAll('tag_topic_index',array(
	'tagid'=>$tagid,
),null,null,$lstart.',30');

foreach($arrTagId as $item){
	$strTopic = $new['topic']->find('topic',array(
		'topicid'=>$item['topicid'],
	));
	if($strTopic==''){
		$new['topic']->delete('tag_topic_index',array(
			'topicid'=>$item['topicid'],
			'tagid'=>$item['tagid'],
		));
	}else{
		$arrTopic[] = $strTopic;
	}
}


aac('tag')->countObjTag('topic',$tagid);

$topicNum = $new['topic']->findCount('tag_topic_index',array(
	'tagid'=>$tagid,
));

$pageUrl = pagination($topicNum, 30, $page, $url);

foreach($arrTopic as $key=>$item){
	$arrTopic[$key]['title'] = htmlspecialchars($item['title']);
	$arrTopic[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
	$arrTopic[$key]['group'] = aac('group')->getOneGroup($item['groupid']);
}

//热门tag
$arrTag = $new['topic']->findAll('tag',"`count_topic`>'0' and `isaudit`=0",'uptime desc',null,30);

$sitekey = $strTag['tagname'];
$title = $strTag['tagname'];

include template("tag");