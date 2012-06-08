<?php
defined('IN_TS') or die('Access Denied.');

include 'userinfo.php';

$page = isset($_GET['page']) ? $_GET['page'] : '1';
$url = SITE_URL.tsUrl('user','feed',array('id'=>$strUser['userid'],'page'=>''));
$lstart = $page*20-20;

$arrFeeds = $new['user']->findAll('feed',array(
	'userid'=>$userid,
),'addtime desc',null,$lstart.',20');

$feedNum = $new['user']->findCount('feed',array(
	'userid'=>$strUser['userid'],
));
$pageUrl = pagination($feedNum, 20, $page, $url);

foreach($arrFeeds as $key=>$item){

	$data = unserialize(stripslashes($item['data']));
	
	if(is_array($data)){
		foreach($data as $key=>$itemTmp){
			$tmpkey = '{'.$key.'}';
			$tmpdata[$tmpkey] = $itemTmp;
		}
	}
	
	$arrFeed[] = array(
		'user'	=> aac('user')->getOneUser($item['userid']),
		'content' => strtr($item['template'],$tmpdata),
		'addtime' => $item['addtime'],
	);
	
}

if($page > 1){
	$title = $strUser['username'].'的动态 - 第'.$page.'页';
}else{
	$title = $strUser['username'].'的动态';
}

include template('feed');