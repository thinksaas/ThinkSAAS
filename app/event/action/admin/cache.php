<?php 
//更新统计活动分类缓存
$arrTypess = $db->fetch_all_assoc("select * from ".dbprefix."event_type");
foreach($arrTypess as $key=>$item){
	$event = $db->once_fetch_assoc("select count(eventid) from ".dbprefix."event where typeid='".$item['typeid']."'");
	$arrTypes['list'][] = array(
		'typeid'	=> $item['typeid'],
		'typename'	=> $item['typename'],
		'count_event'	=> $event['count(eventid)'],
	);
}

$eventNum = $db->once_fetch_assoc("select count(eventid) from ".dbprefix."event");
$arrTypes['count'] = $eventNum['count(eventid)'];

//生成缓存文件
fileWrite('event_types.php','data',$arrTypes);

qiMsg("更新成功！");