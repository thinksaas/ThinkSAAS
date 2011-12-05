<?php
defined('IN_TS') or die('Access Denied.');

//小组内所有的帖子

$groupid = intval($_GET['groupid']);

$strGroup = $new['group']->getOneGroup($groupid);

$title = $strGroup['groupname'];

/*
 *获取小组全部内容列表
 */
 
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

//URL
if($TS_APP['options']['isrewrite']=='0'){
	$url = $TS_URL['group_topic'].$groupid.'&page=';
}else{
	$url = $TS_URL['group_topic'].$groupid.'-';
}


$arrGroupContent = $new['group']->getGroupContent($page,20,$groupid);

$groupContentNum = $new['group']->getGroupContentNum('groupid',$groupid);

$pageUrl = pagination($groupContentNum, 20, $page, $url,$TS_URL['suffix']);

if($page > '1'){
	$titlepage = " - 第".$page."页";
}else{
	$titlepage='';
}

$title =$title.'小组所有的话题'.$titlepage;

include template("group_topic");