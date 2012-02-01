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

		//小组会员
		$isGroupUser = $db->once_fetch_assoc("select count(*) from ".dbprefix."group_users where userid='$userid' and groupid='$groupid'");

		$strGroup = $db->once_fetch_assoc("select * from ".dbprefix."group where groupid='$groupid'");

		//允许小组成员发帖
		if($strGroup['ispost']==0 && $isGroupUser['count(*)'] == 0 && $userid != $strGroup['userid']){
			
			tsNotice("本小组只允许小组成员发贴，请加入小组后再发帖！");
			
		}

		//不允许小组成员发帖
		if($strGroup['ispost'] == 1 && $userid != $strGroup['userid']){
			tsNotice("本小组只允许小组组长发帖！");
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
		$uptime = time();
		
		$arrData = array(
			'groupid'				=> $groupid,
			'typeid'	=> $typeid,
			'userid'				=> $TS_USER['user']['userid'],
			'title'				=> $title,
			'content'		=> $content,
			'iscomment'		=> $iscomment,
			'addtime'			=> time(),
			'uptime'	=> $uptime,
		);
		
		$topicid = $db->insertArr($arrData,dbprefix.'group_topics');
		
		//统计小组下帖子数并更新
		$count_topic = $db->once_num_rows("select * from ".dbprefix."group_topics where groupid='$groupid'");
		
		//统计今天发布帖子数
		$today_start = strtotime(date('Y-m-d 00:00:00'));
		$today_end = strtotime(date('Y-m-d 23:59:59'));
		
		$count_topic_today = $db->once_num_rows("select * from ".dbprefix."group_topics where groupid='$groupid' and addtime > '$today_start'");
		
		$db->query("update ".dbprefix."group set count_topic='$count_topic',count_topic_today='$count_topic_today',uptime='$uptime' where groupid='$groupid'");
		
		header("Location: ".SITE_URL.tsurl('group','topic',array('id'=>$topicid)));
	
		break;
}