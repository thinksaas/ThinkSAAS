<?php
defined('IN_TS') or die('Access Denied.');

include 'userinfo.php';

$page = tsIntval($_GET['page'],1);
$url = tsUrl('user','followed',array('id'=>$strUser['userid'],'page'=>''));
$lstart = $page*80-80;

//跟随他的用户
$arrUsers = $new['user']->findAll('user_follow',array(
	'touserid'=>$strUser['userid'],
),'addtime desc',null,$lstart.',80');

$userNum = $new['user']->findCount('user_follow',array(
	'touserid'=>$strUser['userid'],
));
$pageUrl = pagination($userNum, 80, $page, $url);

if(is_array($arrUsers)){
	foreach($arrUsers as $key=>$item){
		$arrUser[$key] =  $new['user']->getSimpleUser($item['userid']);
	}
}

$title =  $strUser['username'].'的粉丝';
include template('followed');