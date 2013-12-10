<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
	//帖子审核
	case "topicaudit":
		
		$topicid = intval($_POST['topicid']);
		
		$strTopic = $new['group']->find('group_topic',array(
			'topicid'=>$topicid,
		));
		
		if($strTopic['isaudit']==0){
			$new['group']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'isaudit'=>1,
			));
			
			echo 0;exit;
			
		}
		
		if($strTopic['isaudit']==1){
			$new['group']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'isaudit'=>0,
			));
			
			echo 1;exit;
			
		}
		
		break;
}