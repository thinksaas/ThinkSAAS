<?php
defined('IN_TS') or die('Access Denied.');

//小组首页

if($TS_USER['user'] == ''){

	//推荐小组列表
	$arrRecommendGroups = aac('group')->getRecommendGroup('12');
	foreach($arrRecommendGroups as $key=>$item){
		$arrRecommendGroup[] = $item;
		$arrRecommendGroup[$key]['groupdesc'] = getsubstrutf8(t($item['groupdesc']),0,35);
	}
	//最新10个小组
	$arrNewGroup = aac('group')->getNewGroup('10');
	
	$title = $TS_SITE['base']['site_subtitle'];

	include template("index");
	
}else{
	
	header("Location: ".SITE_URL.tsurl('group'));
	
}