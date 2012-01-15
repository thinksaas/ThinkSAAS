<?php
defined('IN_TS') or die('Access Denied.');

if(intval($TS_USER['user']['userid']) == 0){

	//所有小组
	$page = isset($_GET['page']) ? $_GET['page'] : '1';
	$url = SITE_URL.tsurl('group','all',array('page'=>''));
	$lstart = $page*20-20;
	$arrGroups = $db->fetch_all_assoc("select groupid from ".dbprefix."group order by isrecommend desc limit $lstart,20");
	foreach($arrGroups as $key=>$item){
		$arrData[] = $new['group']->getOneGroup($item['groupid']);
	}
	foreach($arrData as $key=>$item){
		$arrRecommendGroup[] =  $item;
		$arrRecommendGroup[$key]['groupdesc'] = getsubstrutf8(t($item['groupdesc']),0,35);
	}
	$groupNum = $db->once_fetch_assoc("select count(groupid) from ".dbprefix."group");
	$pageUrl = pagination($groupNum['count(groupid)'], 20, $page, $url);


	//最新10个小组
	$arrNewGroup = $new['group']->getNewGroup('10');
	
	//热门帖子
	$arrTopic = $db->fetch_all_assoc("select topicid,title,count_comment from ".dbprefix."group_topics order by count_comment desc limit 10");
	
	//小组分类
	$arrCate = $new['group']->getCates();
	
	$title = L::config_appname;

	include template("index");
	
}else{

	$userid = intval($TS_USER['user']['userid']);
	//未登录的情况下
	if($userid == '0'){
		header("Location: ".SITE_URL."index.php");
		exit;
	}
	
	//小组模式的跳转
	if(intval($TS_APP['options']['ismode'])=='1'){
		header("Location: ".SITE_URL.tsurl('group','group',array('id'=>'1')));
		exit;
	}
	
	header("Location: ".SITE_URL.tsurl('group','user',array('id'=>$userid)));
	
}