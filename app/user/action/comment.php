<?php 
defined('IN_TS') or die('Access Denied.');

include 'userinfo.php';

$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
$url = tsUrl('user','comment',array('id'=>$strUser['userid'],'page'=>''));
$lstart = $page*20-20;

$arrComments = $new['user']->findAll('group_topic_comment',array(
	'userid'=>$strUser['userid'],
),'addtime desc',null,$lstart.',20');

foreach($arrComments as $key=>$item){
	$arrComment[] = $item;
	$arrComment[$key]['topic']=aac('group')->getOneTopic($item['topicid']);
}

$commentNum = $new['user']->findCount('group_topic_comment',array(
	'userid'=>$strUser['userid'],
));

$pageUrl = pagination($commentNum, 20, $page, $url);

$title = $strUser['username'].'的评论';

//SEO
$sitekey = '';
$sitedesc = '';



include template('comment');