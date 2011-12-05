<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 小组内所有帖子
 */
$groupid = intval($_GET['groupid']);

//判断是否存在这个群组
$isGroup = $db->once_num_rows("select * from ".dbprefix."group where groupid='$groupid'");

if($isGroup == '0') qiMsg("你是坏蛋吗？不是请返回！");

$strGroup = $db->once_fetch_assoc("select * from ".dbprefix."group where groupid='$groupid'");

//小组组长信息
$leaderId = $strGroup['userid'];

$strLeader = aac('user')->getUserForApp($leaderId);

//管理员信息
$strAdmin = $db->fetch_all_assoc("select userid from ".dbprefix."group_users where groupid='$groupid' and isadmin='1'");

if(is_array($strAdmin)){
	foreach($strAdmin as $item){
		$arrAdmin[] = aac('user')->getUserForApp($item['userid']);
	}
}

//小组会员分页

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$url = SITE_URL.tsurl('group','group_user',array('groupid'=>$groupid,'page'=>''));


$lstart = $page*40-40;

$groupUserNum = $db->once_num_rows("select userid from ".dbprefix."group_users where groupid='$groupid'");

$groupUser = $db->fetch_all_assoc("select userid,isadmin from ".dbprefix."group_users where groupid='$groupid' order by userid desc limit $lstart,40");

if(is_array($groupUser)){
	foreach($groupUser as $key=>$item){
		$arrGroupUser[] = aac('user')->getUserForApp($item['userid']);
		$arrGroupUser[$key]['isadmin'] = $item['isadmin'];
	}
}

$pageUrl = pagination($groupUserNum, 40, $page, $url,$TS_URL['suffix']);

if($page > '1'){
	$titlepage = " - 第".$page."页";
}else{
	$titlepage='';
}

$title = $strGroup['groupname'].'小组成员'.$titlepage;

include template("group_user");