<?php 

$userid = intval($TS_USER['user']['userid']);

switch($ts){
	
	//下载
	case "down":
		//话题附件
		$topicid = intval($_GET['topicid']);
		if($topicid){
			$strTopic = $new['group']->find('group_topics',array(
				'topicid'=>$topicid,
			));
			//扣分
			if($strTopic['attachscore'] && $userid > 0 && $userid != $strTopic['userid']){
				$db->query("update ".dbprefix."user_info set `count_score`='count_score-'".$strTopic['attachscore']." where `userid`='$userid'");
			}
			
			//跳转下载
			header("Location: ".SITE_URL."uploadfile/topic/".$strTopic['attach']);
		}
		
		break;
	
	
}