<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 系统消息盒子
 */
$userid = '0';
$touserid= $_GET['userid'];

$arrMessages = $db->fetch_all_assoc("select * from ".dbprefix."message where userid='0' and touserid='$touserid' order by addtime desc limit 10");

$pattern='/(http:\/\/|https:\/\/|ftp:\/\/)([\w:\/\.\?=&-_]+)/is';

if(is_array($arrMessages)){
	foreach($arrMessages as $key=>$item){
		$arrMessage[] = $item;
		$arrMessage[$key]['content'] = nl2br(preg_replace($pattern, '<a href="\1\2">\1\2</a>', $item['content']));
	}
	
	$arrMessage = array_reverse($arrMessage);
}



//isread设为已读
$db->query("update ".dbprefix."message set `isread`='1' where userid='0' and touserid='$touserid' and `isread`='0'");

$title = '系统消息盒子';

include template("systembox");