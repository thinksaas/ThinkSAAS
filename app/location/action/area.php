<?php 
defined('IN_TS') or die('Access Denied.');
$areaid = intval($_GET['areaid']);

$arrArea = $new['location']->getAreaForApp($areaid);

$strArea = $new['location']->getOneArea($areaid);

$referArea = $new['location']->getReferArea($areaid);

$title = $strArea['areaname'];


//城里的人
$arrUsers = $db->fetch_all_assoc("select userid from ".dbprefix."user_info where areaid = '$areaid' order by uptime desc limit 16");
foreach($arrUsers as $item){
	$arrUser[] = aac('user')->getOneUser($item['userid']);
}

//城里的活动
$arrEvents = $db->fetch_all_assoc("select eventid from ".dbprefix."event where areaid='$areaid' order by addtime desc limit 6");
foreach($arrEvents as $item){
	$arrEvent[] = aac('event')->getEventByEventid($item['eventid']);
}

//模板
include template("area");