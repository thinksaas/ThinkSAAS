<?php
defined('IN_TS') or die('Access Denied.');
	/* 
	 * 话题附件
	 */
	switch($ts){
	
		//话题附件列表
		case "topic_attach_list":
		
			$topicid =$_GET['topicid'];
			
			$arrAttach = $db->fetch_all_assoc("select * from ".dbprefix."group_topics_attachs where topicid='".$topicid."'");
			
			//用户是否评论
			$userid = $TS_USER['user']['userid'];
			
			$userCommentNum = $db->once_num_rows("select * from ".dbprefix."group_topics_comments where userid='$userid' and topicid='$topicid'");
			
		
			include template("topic_attach_list");
			
			break;
		
		//添加话题附件
		case "topic_attach_add":
			
			$topicid = intval($_GET['topicid']);
			
			if($topicid == '') qiMsg("请打道回府吧！");
			
			if($TS_USER['user'] =='') qiMsg("机房重地，闲人免进^_^");
			
			$strTopic = $db->once_fetch_assoc("select * from ".dbprefix."group_topics where topicid='".$topicid."'");
			
			if($TS_USER['user']['userid']!=$strTopic['userid']) qiMsg("机房重地，闲人免进^_^");
			
			if($TS_USER['user']['count_score'] < '1000') qiMsg("您还没有到1000积分，请积累够积分后再上传附件！");
			
			$title = '添加附件';
			
			include template("topic_attach_add");
			
			break;
		
		//下载话题附件 
		
		case "topic_attach_down":
			$attachid = intval($_GET['attachid']);
			$strAttach = $db->once_fetch_assoc("select * from ".dbprefix."group_topics_attachs where attachid='$attachid'");
			
			if($strAttach['score'] == '0'){
				header("Location:".$strAttach['attachurl']);
			}if($TS_USER['user']['userid'] == $strAttach['userid']){
				header("Location:".$strAttach['attachurl']);
			}else{
				$userid = $TS_USER['user']['userid'];
				if($userid == '') qiMsg("请登录后再下载！");
				$strUser = $db->once_fetch_assoc("select count_score from ".dbprefix."user_info where userid='$userid'");
				$count_score = $strUser['count_score'];
				$count_score = $count_score-$strAttach['score'];
				$db->query("update ".dbprefix."user_info set `count_score`='$count_score' where userid='$userid'");
				header("Location:".$strAttach['attachurl']);
			}
			
			break;
	}