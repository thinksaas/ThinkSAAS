<?php
defined('IN_TS') or die('Access Denied.');

$page = isset($_GET['page']) ? $_GET['page'] : '1';
$url = SITE_URL.tsurl('feed','index',array('page'=>''));
$lstart = $page*20-20;

$arrFeeds = $db->fetch_all_assoc("select * from ".dbprefix."feed order by addtime desc limit $lstart,20");

$feedNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."feed");
$pageUrl = pagination($feedNum['count(*)'], 20, $page, $url);

if($page > 1){
	$title = '社区动态 - 第'.$page.'页';
}else{
	$title = '社区动态';
}

foreach($arrFeeds as $key=>$item){
	$data = mb_unserialize($item['data']);
	
	if(is_array($data)){
		foreach($data as $key=>$itemTmp){
			$tmpkey = '{'.$key.'}';
			$tmpdata[$tmpkey] = $itemTmp;
		}
	}
	
	$arrFeed[] = array(
		'user'	=> aac('user')->getSimpleUser($item['userid']),
		'content' => strtr($item['template'],$tmpdata),
		'addtime' => $item['addtime'],
	);
	
}

//print_r($arrFeed);

include template('index');