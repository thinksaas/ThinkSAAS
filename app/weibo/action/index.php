<?php 
defined('IN_TS') or die('Access Denied.');

//微博

$page = isset($_GET['page']) ? intval($_GET['page']) : '1';

$url = tsUrl('weibo','index',array('page'=>''));

$lstart = $page*20-20;

$arrWeibos = $new['weibo']->findAll('weibo',array(
	'isaudit'=>0,
),'uptime desc','weiboid',$lstart.',20');

foreach($arrWeibos as $key=>$item){
	$arrWeibo[] = $new['weibo']->getOneWeibo($item['weiboid']);
}

$weiboNum = $new['weibo']->findCount('weibo');

$pageUrl = pagination($weiboNum, 20, $page, $url);


$title = '唠叨';

$sitekey = $TS_APP['options']['appkey'];
$sitedesc = $TS_APP['options']['appdesc'];

include template('index');