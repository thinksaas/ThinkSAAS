<?php 
defined('IN_TS') or die('Access Denied.');



$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
$url = tsUrl('my','score',array('page'=>''));
$lstart = $page*50-50;

$arrScore = $new['my']->findAll('user_score_log',array(
	'userid'=>$strUser['userid'],
),'addtime desc',null,$lstart.',50');

$scoreNum = $new['my']->findCount('user_score_log',array(
	'userid'=>$strUser['userid'],
));
$pageUrl = pagination($scoreNum, 50, $page, $url);

$title = '我的积分';
include template('score');