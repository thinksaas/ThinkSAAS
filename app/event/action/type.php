<?php 
defined('IN_TS') or die('Access Denied.');
//活动类型

$typeid = $_GET['typeid'];
//调出活动类型
$arrEventType = fileRead('types.php','data','event');


if($typeid == 0){
	$arrEvents = $db->fetch_all_assoc("select eventid from ".dbprefix."event order by addtime desc");
	if(is_array($arrEvents)){
		foreach($arrEvents as $item){
			$arrEvent[] = $new['event']->getEventByEventid($item['eventid']);
		}
	}
	$title = '所有类型';
}else{
	$strType = $db->once_fetch_assoc("select * from ".dbprefix."event_type where typeid='$typeid'");
	//调出类型下面的活动 
	$arrEvents = $db->fetch_all_assoc("select eventid from ".dbprefix."event where typeid='$typeid' order by addtime desc");
	if(is_array($arrEvents)){
		foreach($arrEvents as $item){
			$arrEvent[] = $new['event']->getEventByEventid($item['eventid']);
		}
	}
	
	$title = $strType['typename'];
}

include template("type");