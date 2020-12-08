<?php
defined('IN_TS') or die('Access Denied.');
//搜索结果

$kw=urldecode(tsFilter($_GET['kw']));

if($kw==''){
	header("Location: ".tsUrl('search'));
	exit;
}

$kw = t($kw);

if(count_string_len($kw)<2) {
	header("Location: ".tsUrl('search'));
	exit;
};

if($ts=='') $ts = $TS_APP['ds'];

switch($ts){
	
	//小组 
	case "group":
		
		$page = isset($_GET['page']) ? tsIntval($_GET['page']) : 1;
		$url = tsUrl('search','s',array('ts'=>'group','kw'=>$kw,'page'=>''));
		$lstart = $page*10-10;
		
		$arrGroup = $db->fetch_all_assoc("select * from ".dbprefix."group WHERE `groupname` like '%$kw%' order by groupid desc limit $lstart,10");
		
		$group_num = $db->once_num_rows("select * from ".dbprefix."group WHERE groupname like '%$kw%'");
		
		$pageUrl = pagination($group_num, 10, $page, $url);
		
		$title = $kw.' - 小组搜索';

		include template("s_group");
		break;
	//帖子
	case "topic":
	
		$page = isset($_GET['page']) ? tsIntval($_GET['page']) : 1;
		$url = tsUrl('search','s',array('ts'=>'topic','kw'=>$kw,'page'=>''));
		$lstart = $page*10-10;
	
		$arrTopic = $db->fetch_all_assoc("select * from ".dbprefix."topic WHERE `title` like '%$kw%' order by topicid desc limit $lstart,10");
		
		$topic_num = $db->once_num_rows("select * from ".dbprefix."topic WHERE title like '%$kw%'");
		
		$pageUrl = pagination($topic_num, 10, $page, $url);
		
		$title = $kw.' - 帖子搜索';
		include template("s_topic");
		break;
		
	//用户
	case "user":
		
		$page = isset($_GET['page']) ? tsIntval($_GET['page']) : 1;
		$url = tsUrl('search','s',array('ts'=>'user','kw'=>$kw,'page'=>''));
		$lstart = $page*10-10;
	
		$arrUser = $db->fetch_all_assoc("select * from ".dbprefix."user_info WHERE `username` like '%$kw%' order by userid desc limit $lstart,10");
		
		$user_num = $db->once_num_rows("select * from ".dbprefix."user_info WHERE `username` like '%$kw%'");
		
		$pageUrl = pagination($user_num, 10, $page, $url);
		
		$title = $kw.' - 用户搜索';
		include template("s_user");
		
		break;
		
	case "article":
		
		$page = isset($_GET['page']) ? tsIntval($_GET['page']) : 1;
		$url = tsUrl('search','s',array('ts'=>'article','kw'=>$kw,'page'=>''));
		$lstart = $page*10-10;
	
		$arrArticle = $db->fetch_all_assoc("select * from ".dbprefix."article WHERE `title` like '%$kw%' and `isaudit`='0' order by addtime desc limit $lstart,10");
		
		$articleNum = $db->once_num_rows("select * from ".dbprefix."article WHERE `title` like '%$kw%' and `isaudit`='0'");
		
		$pageUrl = pagination($articleNum, 10, $page, $url);
		
		$title = $kw.' - 文章搜索';
		include template("s_article");
		
		break;
}