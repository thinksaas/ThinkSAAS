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
		$content	= trim($_POST['content']);
		
		//添加评论标签
		doAction('group_comment_add','',$content,'');
		
		if($content==''){
			tsNotice('没有任何内容是不允许你通过滴^_^');
		}else{
			$arrData	= array(
				'topicid'			=> $topicid,
				'userid'			=> $userid,
				'content'	=> $content,
				'addtime'		=> time(),
			);
			
			$commentid = $db->insertArr($arrData,dbprefix.'group_topics_comments');
			
			//统计评论数
			$count_comment = $db->once_num_rows("select * from ".dbprefix."group_topics_comments where topicid='$topicid'");
			
			//更新帖子最后回应时间和评论数
			$uptime = time();
			
			$db->query("update ".dbprefix."group_topics set uptime='$uptime',count_comment='$count_comment' where topicid='$topicid'");
			
			//发送系统消息
			$strTopic = $db->once_fetch_assoc("select * from ".dbprefix."group_topics where topicid='$topicid'");
			if($strTopic['userid'] != $TS_USER['user']['userid']){
			
				$msg_userid = '0';
				$msg_touserid = $strTopic['userid'];
				$msg_content = '你的帖子：《'.$strTopic['title'].'》新增一条评论，快去看看给个回复吧^_^ <br />'
											.SITE_URL.tsurl('group','topic',array('id'=>$topicid));
				aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
				
			}
			
			header("Location: ".SITE_URL.tsurl('group','topic',array('id'=>$topicid)));
		}	
	
		break;
}