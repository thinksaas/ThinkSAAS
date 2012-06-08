<?php 
defined('IN_TS') or die('Access Denied.');

include 'userinfo.php';

$page = isset($_GET['page']) ? $_GET['page'] : '1';
$url = SITE_URL.tsUrl('user','topic',array('id'=>$strUser['userid'],'page'=>''));
$lstart = $page*30-30;

$arrTopic = $new['user']->findAll('group_topics',array(
	'userid'=>$strUser['userid'],
),'addtime desc',null,$lstart.',30');

$topicNum = $new['user']->findCount('group_topics',array(
	'userid'=>$strUser['userid'],
));
$pageUrl = pagination($topicNum, 30, $page, $url);

$title = $strUser['username'].'的帖子';
include template('topic');