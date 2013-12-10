<?php 
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();

switch($ts){

	//我的小组发言
	case "topic":
	
		$arrTopics = $db->fetch_all_assoc("select * from ".dbprefix."group_topic where userid='".$TS_USER['user']['userid']."' order by addtime desc limit 30");
		foreach($arrTopics as $key=>$item){
			$arrTopic[] = $item;
			$arrTopic[$key]['title'] = htmlspecialchars($item['title']);
			$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
			$arrTopic[$key]['group'] = aac('group')->getOneGroup($item['groupid']);
		}

		$title = '我的小组发言';

		include template("my_topic");
	
		break;
		
	//我回复的帖子 
	case "reply":
		
		$myTopics = $db->fetch_all_assoc("select topicid from ".dbprefix."group_topic_comment where userid='".$TS_USER['user']['userid']."' group by topicid order by addtime desc limit 30");


		foreach($myTopics as $item){

			$strTopic = $db->once_fetch_assoc("select * from ".dbprefix."group_topic where topicid = '".$item['topicid']."'");
			$arrTopics[] = $strTopic;
			
		}

		foreach($arrTopics as $key=>$item){
			$arrTopic[] = $item;
			$arrTopic[$key]['title'] = htmlspecialchars($item['title']);
			$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
			$arrTopic[$key]['group'] = aac('group')->getOneGroup($item['groupid']);
		}

		$title='我最近回应的话题';

		include template("my_reply");
		
		break;
		
	//我收藏的帖子 
	case "collect":
		
		$arrCollect = $db->fetch_all_assoc("select * from ".dbprefix."group_topic_collect where userid='".$userid."' order by addtime desc limit 30");

		foreach($arrCollect as $item){

			$strTopic = $db->once_fetch_assoc("select * from ".dbprefix."group_topic where topicid = '".$item['topicid']."'");
			$arrTopics[] = $strTopic;
			
		}

		foreach($arrTopics as $key=>$item){
			$arrTopic[] = $item;
			$arrTopic[$key]['title'] = htmlspecialchars($item['title']);
			$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
			$arrTopic[$key]['group'] = aac('group')->getOneGroup($item['groupid']);
		}

		$title = '我收藏的话题';

		include template("my_collect");
		
		break;
}