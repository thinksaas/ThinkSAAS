<?php 

switch($ts){
	case "do":
	
		//用户是否登录
		$userid = intval($TS_USER['user']['userid']);
		if($userid == 0){
			header("Location: ".SITE_URL.tsurl('user','login'));
			exit;
		}
		
		$topicid	= intval($_POST['topicid']);
		$groupid = intval($_POST['groupid']);
		$content	= trim($_POST['content']);
		
		//添加评论标签
		doAction('group_comment_add','',$content,'');
		
		if($content==''){
			tsNotice('没有任何内容是不允许你通过滴^_^');
		}else{
			$arrData	= array(
				'topicid'			=> $topicid,
				'groupid'	=> $groupid,
				'userid'			=> $userid,
				'content'	=> $content,
				'addtime'		=> date('Y-m-d H:i:s'),
			);
			
			$commentid = $db->insertArr($arrData,dbprefix.'group_topics_comments');
			
			//统计帖子评论数
			$countTopicComment = $db->once_fetch_assoc("select count(*) from ".dbprefix."discuss_topics_comments where `topicid`='$topicid'");

			$uptime = date('Y-m-d H:i:s');			
			$db->query("update ".dbprefix."discuss_topics set uptime='$uptime',count_comment='".$countTopicComment['count(*)']."' where topicid='$topicid'");
			
			//统计小组下所有帖子总评论数
			$countGroupComment = $db->once_fetch_assoc("select count(*) from ".dbprefix."discuss_topics_comments where `groupid`='$groupid'");
			
			//今日总评论数
			$today_start = date('Y-m-d 00:00:00');
			$countGroupCommentToday = $db->once_fetch_assoc("select count(*) from ".dbprefix."discuss_topics_comments where groupid='$groupid' and addtime > '$today_start'");
			
			$db->query("update ".dbprefix."discuss set `count_comment`='".$countGroupComment['count(*)']."',`count_comment_today`='".$countGroupCommentToday['count(*)']."' where `groupid`='$groupid'");
			
			//发送系统消息
			$strTopic = $db->once_fetch_assoc("select * from ".dbprefix."discuss_topics where topicid='$topicid'");
			if($strTopic['userid'] != $TS_USER['user']['userid']){
			
				$msg_userid = '0';
				$msg_touserid = $strTopic['userid'];
				$msg_content = '你的帖子：《'.$strTopic['title'].'》新增一条评论，快去看看给个回复吧^_^ <br />'
											.SITE_URL.tsurl('discuss','topic',array('id'=>$topicid));
				aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
				
			}
			
			header("Location: ".SITE_URL.tsurl('discuss','topic',array('id'=>$topicid)));
		}	
	
		break;
}