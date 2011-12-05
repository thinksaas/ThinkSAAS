<?php
defined('IN_TS') or die('Access Denied.');

//消息盒子

$userid = intval($TS_USER['user']['userid']);
$touserid= intval($_GET['userid']);

if($userid == 0 || $touserid == 0) qiMsg("非法操作！");

$msgCount = $db->once_fetch_assoc("select count(messageid) from ".dbprefix."message where (userid='$userid' and touserid='$touserid') or (userid='$touserid' and touserid='$userid') ");

if($msgCount['count(messageid)'] ==0) qiMsg("非法操作");

$sql = "select * from ".dbprefix."message where (userid='$userid' and touserid='$touserid') or (userid='$touserid' and touserid='$userid') order by addtime desc LIMIT 0 , 10";

$arrMessages = $db->fetch_all_assoc($sql);

if(is_array($arrMessages)){
	foreach($arrMessages as $key=>$item){
		$arrMessage[] = $item;
		$arrMessage[$key]['user'] = aac('user')->getUserForApp($item['userid']);
	}
	
	$arrMessage = array_reverse($arrMessage);
}

//isread设为已读
$db->query("update ".dbprefix."message set `isread`='1' where userid='$touserid' and touserid='$userid' and `isread`='0'");

$title = '消息盒子';

include template("msgbox");