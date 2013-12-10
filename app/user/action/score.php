<?php 
defined('IN_TS') or die('Access Denied.');

//用户空间
include 'userinfo.php';

$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
$url = tsUrl('user','score',array('id'=>$strUser['userid'],'page'=>''));
$lstart = $page*50-50;

$arrScore = $new['user']->findAll('user_score_log',array(
	'userid'=>$strUser['userid'],
),'addtime desc',null,$lstart.',50');

$scoreNum = $new['user']->findCount('user_score_log',array(
	'userid'=>$strUser['userid'],
));
$pageUrl = pagination($scoreNum, 50, $page, $url);

$title = $strUser['username'].'的积分';
include template('score');