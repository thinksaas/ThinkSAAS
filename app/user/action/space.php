<?php
defined('IN_TS') or die('Access Denied.');

//用户空间
include 'userinfo.php';


//动态
$arrFeeds = $new['user']->findAll('feed',array(
	'userid'=>$strUser['userid'],
),'addtime desc',null,'20');

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


//留言
$arrGuests = $new['user']->findAll('user_gb',array(
	'touserid'=>$strUser['userid'],
),'addtime desc',null,10);

foreach($arrGuests as $key=>$item){
	$arrGuest[] = $item;
	$arrGuest[$key]['user']=$new['user']->getOneUser($item['userid']);
}

$title = $strUser['username'];
include template("space");
