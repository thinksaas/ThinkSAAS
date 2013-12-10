<?php 
defined('IN_TS') or die('Access Denied.');
$userid=aac('user')->isLogin();

//微博

$page = isset($_GET['page']) ? intval($_GET['page']) : '1';

$url = tsUrl('weibo','my',array('page'=>''));

$lstart = $page*20-20;

$arrWeibos = $new['weibo']->findAll('weibo',array(
	'isaudit'=>0,'userid'=>$userid,
),'uptime desc','weiboid',$lstart.',20');

foreach($arrWeibos as $key=>$item){
	$arrWeibo[] = $new['weibo']->getOneWeibo($item['weiboid']);
}

$weiboNum = $new['weibo']->findCount('weibo');

$pageUrl = pagination($weiboNum, 20, $page, $url);


//社区精华帖
$arrPosts = $new['weibo']->findAll('group_topic',array(
	'isposts'=>1,
),'uptime desc',null,15);


$title = '微博';

include template('my');