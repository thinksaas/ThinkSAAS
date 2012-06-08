<?php
defined('IN_TS') or die('Access Denied.');

//首页

if($TS_USER['user'] == ''){

	if($_GET){
		header('Location: '.SITE_URL);
	}

	//推荐小组列表
	$arrRecommendGroups = aac('group')->getRecommendGroup('12');
	foreach($arrRecommendGroups as $key=>$item){
		$arrRecommendGroup[] = $item;
		$arrRecommendGroup[$key]['groupdesc'] = getsubstrutf8(t($item['groupdesc']),0,35);
	}
	//最新10个小组
	$arrNewGroup = aac('group')->getNewGroup('10');
	
	//热门帖子
	$arrHotTopics = aac('group')->hotTopics(1);
	
	//推荐喜欢的帖子
	$strLikeTopic = aac('group')->loveTopic(1,8);
	
	$title = $TS_SITE['base']['site_subtitle'];

	include template("index");
	
}else{
	
	header("Location: ".SITE_URL.tsUrl('group','user',array('id'=>$TS_USER['user']['userid'])));
	
}