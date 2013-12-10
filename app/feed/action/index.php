<?php
defined('IN_TS') or die('Access Denied.');

$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
$url = tsurl('feed','index',array('page'=>''));
$lstart = $page*20-20;

$arrFeeds = $new['feed']->findAll('feed',null,'addtime desc',null,$lstart.',20');

$feedNum = $new['feed']->findCount("feed");
$pageUrl = pagination($feedNum, 20, $page, $url);

if($page > 1){
	$title = '社区动态 - 第'.$page.'页';
}else{
	$title = '社区动态';
}

foreach($arrFeeds as $key=>$item){
	$data = json_decode($item['data'],true);
	
	if(is_array($data)){
		foreach($data as $key=>$itemTmp){
			$tmpkey = '{'.$key.'}';
			$tmpdata[$tmpkey] = urldecode($itemTmp);
		}
	}
	$arrFeed[] = array(
		'user'	=> aac('user')->getOneUser($item['userid']),
		'content' => strtr($item['template'],$tmpdata),
		'addtime' => $item['addtime'],
	);
}

include template('index');