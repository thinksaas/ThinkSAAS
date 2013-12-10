<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "do":
	
		if($_POST['token'] != $_SESSION['token']) {
			$new['mobile']->mNotice('非法操作！');
		}
		
		$userid = $new['mobile']->isLogin();
		
		$topicid = intval($_POST['topicid']);
		
		$content = tsClean($_POST['content']);
		
		if($topicid && $content){
			$new['mobile']->create('group_topic_comment',array(
				'topicid'=>$topicid,
				'userid'=>$userid,
				'content'=>$content,
				'addtime'=>time(),
			));
			
			//统计
			$count_comment = $new['mobile']->findCount('group_topic_comment',array(
				'topicid'=>$topicid,
			));
			
			$new['mobile']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'count_comment'=>$count_comment,
			));
			
			header('Location: '.tsUrl('mobile','topic',array('ts'=>'show','topicid'=>$topicid)));
		}else{
			$new['mobile']->mNotice('评论内容不能为空！');
		}
		
		break;
}