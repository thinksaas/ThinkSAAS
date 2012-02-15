<?php 
defined('IN_TS') or die('Access Denied.');

$userid = intval($TS_USER['user']['userid']);

if($userid == 0){
	header("Location: ".SITE_URL);
	exit;
}

switch($ts){

	//我的小组发言
	case "topic":
	
		$arrTopics = $db->findAll("select topicid,groupid,userid,title,count_comment,addtime,uptime from ".dbprefix."group_topics where userid='".$TS_USER['user']['userid']."' order by addtime desc limit 30");
		foreach($arrTopics as $key=>$item){
			$arrTopic[] = $item;
			$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
			$arrTopic[$key]['group'] = aac('group')->getOneGroup($item['groupid']);
		}

		$title = '我的小组发言';

		include template("my_topic");
	
		break;
		
	//我回复的帖子 
	case "reply":
		
		$myTopics = $db->findAll("select topicid from ".dbprefix."group_topics_comments where userid='".$TS_USER['user']['userid']."' group by topicid order by addtime desc limit 30");


		foreach($myTopics as $item){

			$strTopic = $db->find("select topicid,userid,groupid,title,count_comment,count_view,isphoto,isattach,addtime,uptime from ".dbprefix."group_topics where topicid = '".$item['topicid']."'");
			$arrTopics[] = $strTopic;
			
		}

		foreach($arrTopics as $key=>$item){
			$arrTopic[] = $item;
			$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
			$arrTopic[$key]['group'] = aac('group')->getOneGroup($item['groupid']);
		}

		$title='我最近回应的话题';

		include template("my_reply");
		
		break;
		
	//我收藏的帖子 
	case "collect":
		
		$arrCollect = $db->findAll("select * from ".dbprefix."group_topics_collects where userid='".$userid."' order by addtime desc limit 30");

		foreach($arrCollect as $item){

			$strTopic = $db->find("select topicid,userid,groupid,title,count_comment,count_view,isphoto,isattach,addtime,uptime from ".dbprefix."group_topics where topicid = '".$item['topicid']."'");
			$arrTopics[] = $strTopic;
			
		}

		foreach($arrTopics as $key=>$item){
			$arrTopic[] = $item;
			$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
			$arrTopic[$key]['group'] = aac('group')->getOneGroup($item['groupid']);
		}

		$title = '我收藏的话题';

		include template("my_collect");
		
		break;
}