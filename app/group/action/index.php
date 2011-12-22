<?php
defined('IN_TS') or die('Access Denied.');

//小组首页

if($TS_USER['user'] == ''){

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
	
	$title = '小组';

	include template("index");
	
}else{

	$userid = intval($TS_USER['user']['userid']);
	if($userid == '0') header("Location: ".SITE_URL."index.php");
	
	//小组模式的跳转
	if(intval($TS_APP['options']['ismode'])=='1'){
		header("Location: ".SITE_URL.tsurl('group','group',array('groupid'=>'1')));
		exit;
	}
	
	//我的小组
	$myGroup = $db->fetch_all_assoc("select * from ".dbprefix."group_users where userid='$userid'");
	
	if($myGroup != ''){
	
		//我加入的小组
		$myGroups = $db->fetch_all_assoc("select * from ".dbprefix."group_users where userid='$userid' limit 30");
		
		if(is_array($myGroups)){
			foreach($myGroups as $key=>$item){
				$arrMyGroup[] = $new['group']->getOneGroup($item['groupid']);
			}
		}
		
		//我加入的所有小组的话题
		
		if(is_array($myGroup)){
			foreach($myGroup as $item){
				$arrGroup[] = $item['groupid'];
			}
		}
		
		//@bug fixed by anythink
		$strGroup = implode(',',$arrGroup);
		if($strGroup){
			$arrTopics = $db->fetch_all_assoc("select topicid,userid,groupid,title,count_comment,count_view,istop,isphoto,isattach,isposts,addtime,uptime from ".dbprefix."group_topics where groupid in ($strGroup) and isshow='0' order by uptime desc limit 50");
			foreach($arrTopics as $key=>$item){
				$arrTopic[] = $item;
				$arrTopic[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
				$arrTopic[$key]['group'] = aac('group')->getSimpleGroup($item['groupid']);
				$arrTopic[$key]['photo'] = $new['group']->getOnePhoto($item['topicid']);
			}
		}
	
	}
	
	$title = '我的首页';
	
	include template("my");
	
}