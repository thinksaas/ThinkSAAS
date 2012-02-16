<?php
defined('IN_TS') or die('Access Denied.');

//我的消息盒子

if($TS_USER['user']['userid']=='') header("Location: ".SITE_URL."index.php");

$userid = $TS_USER['user']['userid'];

$arrToUsers = $db->findAll("select userid from ".dbprefix."message where userid > '0' and touserid='$userid' group by userid");

if(is_array($arrToUsers)){
	foreach($arrToUsers as $key=>$item){
		$arrToUser[] = $item;
		$arrToUser[$key]['user'] = aac('user')->getOneUser($item['userid']);

		$arrToUser[$key]['count'] = $db->findCount('message',array(
			'touserid'=>$userid,
			'userid'=>$item['userid'],
			'isread'=>0,
		));
	}
}

//统计系统消息
$systemNum = $db->findCount('message',array(
	'userid'=>0,
	'touserid'=>$userid,
	'isread'=>0,
));

$title = '我的消息盒子';

include template("my");