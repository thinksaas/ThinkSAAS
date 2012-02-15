<?php
defined('IN_TS') or die('Access Denied.');
//所有小组
$page = isset($_GET['page']) ? $_GET['page'] : '1';
$url = SITE_URL.tsurl('group','all',array('page'=>''));
$lstart = $page*20-20;
$arrGroups = $db->findAll("select groupid from ".dbprefix."group order by isrecommend desc limit $lstart,20");
foreach($arrGroups as $key=>$item){
	$arrData[] = $new['group']->getOneGroup($item['groupid']);
}
foreach($arrData as $key=>$item){
	$arrGroup[] =  $item;
	$arrGroup[$key]['groupdesc'] = getsubstrutf8(t($item['groupdesc']),0,35);
}
$groupNum = $db->find("select count(groupid) from ".dbprefix."group");
$pageUrl = pagination($groupNum['count(groupid)'], 20, $page, $url);
if($page > 1){
	$title = '全部小组 - 第'.$page.'页';
}else{
	$title = '全部小组';
}


//热门帖子
$arrTopic = $db->findAll("select topicid,title,count_comment from ".dbprefix."group_topics order by count_comment desc limit 10");

//最新10个小组
$arrNewGroup = $new['group']->getNewGroup('10');

include template('all');