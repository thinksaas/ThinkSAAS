<?php
defined('IN_TS') or die('Access Denied.');


$arrGroupUser = $new['my']->findAll('group_user',array(
	'userid'=>$strUser['userid'],
));
foreach($arrGroupUser as $key=>$item){
	$arrGroupId[] = $item['groupid'];
}

$strGroupId = arr2str($arrGroupId);

if($strGroupId){
	$arrTopic = $new['my']->findAll('group_topic',"`groupid` in ($strGroupId)",'addtime desc',null,30);
	foreach($arrTopic as $key=>$item){
		$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
		$arrTopic[$key]['group'] = aac('group')->getOneGroup($item['groupid']);
	}
}

include template("index");