<?php
defined('IN_TS') or die('Access Denied.');

$userid = intval($TS_USER['user']['userid']);
$areaid = intval($TS_USER['user']['areaid']);

if($userid =='0') header("Location: ".SITE_URL."index.php/user/login");

$groupid = isset($_GET['groupid']) ? $_GET['groupid'] : '0';

//活动类型
$arrType = $db->fetch_all_assoc("select * from ".dbprefix."event_type");

//获取常驻地
if($areaid != '0'){

	//调出省份数据
	$arrOne = $db->fetch_all_assoc("select * from ".dbprefix."area where referid='0'");

}

$title = '发布活动';
include template("add");