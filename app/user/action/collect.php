<?php 
defined('IN_TS') or die('Access Denied.');

include 'userinfo.php';

$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
$url = tsUrl('user','collect',array('id'=>$strUser['userid'],'page'=>''));
$lstart = $page*30-30;

$arrTopicLists = $new['user']->findAll('group_topic_collect',array(
	'userid'=>$strUser['userid'],
),'addtime desc',null,$lstart.',30');

foreach($arrTopicLists as $key=>$item){
	$arrTopicList[] = aac('group')->getOneTopic($item['topicid']);
}

$topicNum = $new['user']->findCount('group_topic_collect',array(
	'userid'=>$strUser['userid'],
));
$pageUrl = pagination($topicNum, 30, $page, $url);

$title = $strUser['username'].'的喜欢';
include template('collect');