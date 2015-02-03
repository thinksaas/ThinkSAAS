<?php 
defined ( 'IN_TS' ) or die ( 'Access Denied.' );


$page = isset($_GET['page']) ? intval($_GET['page']) : '1';

$url = tsUrl('weibo','my',array('my'=>'index','page'=>''));

$lstart = $page*20-20;

$arrWeibos = $new['weibo']->findAll('weibo',array(
	'userid'=>$strUser['userid'],
),'uptime desc','weiboid',$lstart.',20');

foreach($arrWeibos as $key=>$item){
	$arrWeibo[] = $new['weibo']->getOneWeibo($item['weiboid']);
}

$weiboNum = $new['weibo']->findCount('weibo',array(
	'userid'=>$strUser['userid'],
));

$pageUrl = pagination($weiboNum, 20, $page, $url);

$title = '我的唠叨';
include template('my/index');