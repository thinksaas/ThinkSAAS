<?php
defined('IN_TS') or die('Access Denied.');

include 'userinfo.php';

$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
$url = tsUrl('user','followed',array('id'=>$strUser['userid'],'page'=>''));
$lstart = $page*80-80;

//跟随他的用户
$arrUsers = $new['user']->findAll('user_follow',array(
	'userid_follow'=>$strUser['userid'],
),'addtime desc',null,$lstart.',80');

$userNum = $new['user']->findCount('user_follow',array(
	'userid_follow'=>$strUser['userid'],
));
$pageUrl = pagination($userNum, 80, $page, $url);

if(is_array($arrUsers)){
	foreach($arrUsers as $item){
		$arrUser[] =  $new['user']->getOneUser($item['userid']);
	}
}

$title =  $strUser['username'].'的粉丝';
include template('followed');