<?php
defined('IN_TS') or die('Access Denied.');

//用户空间
include 'userinfo.php';

//加入的小组
$arrGroupUser = $new['user']->findAll('group_user',array(
	'userid'=>$userid,
));

if(is_array($arrGroupUser)){
	foreach($arrGroupUser as $key=>$item){
		$arrGroup[] = aac('group')->getOneGroup($item['groupid']);
	}
}


//留言
$arrGuest = $new['user']->findAll('user_gb',array(
	'touserid'=>$strUser['userid'],
),'addtime desc',null,10);

foreach($arrGuest as $key=>$item){
	$arrGuest[$key]['content'] = tsDecode($item['content']);
	$arrGuest[$key]['user']=$new['user']->getSimpleUser($item['userid']);
}

$title = $strUser['username'];
include template("space");
