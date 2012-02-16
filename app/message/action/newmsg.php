<?php
defined('IN_TS') or die('Access Denied.');

// 如果用户id为0或非数字，则直接返回0
$userid = intval($_GET['userid']);
if(!$userid) {
	echo '0';
}

$newMsgNum = $db->findCount('message',array(
	'touserid'=>$userid,
	'isread'=>0,
));

if($newMsgNum == '0'){
	echo '0';
}else{
	echo '1';
}