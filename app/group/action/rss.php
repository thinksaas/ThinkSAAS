<?php
defined('IN_TS') or die('Access Denied.');
//RSS输出
$groupid = intval($_GET['groupid']);
$strGroup = $new['group']->find('group',array(
	'groupid'=>$groupid,
));

$arrTopic = $new['group']->findAll('group_topic',array(
	'groupid'=>$groupid,
),'addtime desc',null,30);

foreach($arrTopic as $key=>$item){
	$arrTopic[$key]['title'] = htmlspecialchars($item['title']);
	$arrTopic[$key]['content'] = htmlspecialchars($item['content']);
}

$pubdate = time();
header( 'Content-Type:text/xml');
include template("rss");