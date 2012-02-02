<?php
defined('IN_TS') or die('Access Denied.');

$userid = intval($TS_USER['user']['userid']);

if($userid == 0){
	header("Location: ".SITE_URL.tsurl('user','login'));
	exit;
}

switch($ts){
	case "":
		$groupid = intval($_GET['groupid']);
		//小组数目
		$groupNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."group where groupid='$groupid'");

		if($groupNum['count(*)'] == 0){
			header("Location: ".SITE_URL);
			exit;
		}


		$strGroup = $db->once_fetch_assoc("select * from ".dbprefix."group where groupid='$groupid'");

		//是否允许发帖
		if($strGroup['ispost'] == 1){
			tsNotice("本讨论组只不允许发帖");
		}

		//帖子类型
		$arrGroupType = $db->fetch_all_assoc("select * from ".dbprefix."group_topics_type where groupid='".$strGroup['groupid']."'");

		$title = L::addtopic_newthread;

		//包含模版
		include template("addtopic");
		break;
	
	//执行发布帖子
	case "do":
	
		$groupid	= intval($_POST['groupid']);
		
		$title	= trim($_POST['title']);
		$content	= trim($_POST['content']);
		
		//发布帖子标签
		doAction('group_topic_add',$title,$content);
		
		$typeid = intval($_POST['typeid']);
		
		$iscomment = $_POST['iscomment'];
		
		if($title == '' || $content == '') tsNotice('标题和内容都不能为空！');
		
		if(mb_strlen($title,'utf8')>64) tsNotice('标题最长32个字符！');
		
		//开始插入数据
		
		$arrData = array(
			'groupid'				=> $groupid,
			'typeid'	=> $typeid,
			'userid'				=> $TS_USER['user']['userid'],
			'title'				=> $title,
			'content'		=> $content,
			'iscomment'		=> $iscomment,
			'addtime'			=> date('Y-m-d H:i:s'),
			'uptime'	=> date('Y-m-d H:i:s'),
		);
		
		$topicid = $db->insertArr($arrData,dbprefix.'group_topics');
		
		//统计小组下帖子数并更新
		$countGroupTopic = $db->once_fetch_assoc("select count(*) from ".dbprefix."group_topics where groupid='$groupid'");
		
		//统计今天发布帖子数
		$todayStart = date('Y-m-d 00:00:00');
		$countGroupTopicToday = $db->once_fetch_assoc("select count(*) from ".dbprefix."group_topics where groupid='$groupid' and addtime > '$todayStart'");
		
		$db->query("update ".dbprefix."group set `count_topic`='".$countGroupTopic['count(*)']."',`count_topic_today`='".$countGroupTopicToday['count(*)']."',`uptime`='".date('Y-m-d H:i:s')."' where `groupid`='$groupid'");
		
		header("Location: ".SITE_URL.tsurl('group','topic',array('id'=>$topicid)));
	
		break;
}