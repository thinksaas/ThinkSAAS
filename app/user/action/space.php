<?php
defined('IN_TS') or die('Access Denied.');

//用户空间
include 'userinfo.php';


//Feed
$arrFeeds = $db->fetch_all_assoc("select * from ".dbprefix."feed where `userid`='$userid' order by addtime desc limit 10");

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

//留言
$arrGuests = $new['user']->findAll('user_gb',array(
	'touserid'=>$strUser['userid'],
),'addtime desc',null,10);

foreach($arrGuests as $key=>$item){
	$arrGuest[] = $item;
	$arrGuest[$key]['user']=$new['user']->getOneUser($item['userid']);
}

$title = $strUser['username'].'的个人空间';
include template("space");
