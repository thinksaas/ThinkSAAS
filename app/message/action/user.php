<?php 
defined('IN_TS') or die('Access Denied.');
//消息盒子
$userid = aac('user')->isLogin();

$touserid= intval($_GET['touserid']);


$strTouser = aac('user')->getOneUser($touserid);


$msgCount = $new['message']->findCount('message',"(userid='$userid' and touserid='$touserid') or (userid='$touserid' and touserid='$userid')");

$arrMessages = $new['message']->findAll('message',"(userid='$userid' and touserid='$touserid') or (userid='$touserid' and touserid='$userid')",'addtime desc',null,10);

if(is_array($arrMessages)){
	foreach($arrMessages as $key=>$item){
		$arrMessage[] = $item;
		$arrMessage[$key]['user'] = aac('user')->getOneUser($item['userid']);
		$arrMessage[$key]['content'] = urlcontent($item['content']);
	}
	
	$arrMessage = array_reverse($arrMessage);
}

//isread设为已读
$new['message']->update('message',array(
	'userid'=>$touserid,
	'touserid'=>$userid,
	'isread'=>0,
),array(
	'isread'=>1,
));

$title = '消息盒子';

include template("user");