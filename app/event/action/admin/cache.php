<?php 
//更新统计活动分类缓存
$arrTypess = $db->findAll("select * from ".dbprefix."event_type");
foreach($arrTypess as $key=>$item){
	$event = $db->find("select count(eventid) from ".dbprefix."event where typeid='".$item['typeid']."'");
	$arrTypes['list'][] = array(
		'typeid'	=> $item['typeid'],
		'typename'	=> $item['typename'],
		'count_event'	=> $event['count(eventid)'],
	);
}

$eventNum = $db->find("select count(eventid) from ".dbprefix."event");
$arrTypes['count'] = $eventNum['count(eventid)'];

//生成缓存文件
fileWrite('event_types.php','data',$arrTypes);

qiMsg("更新成功！");