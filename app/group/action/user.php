<?php
defined('IN_TS') or die('Access Denied.');
$userid = intval($_GET['userid']);
aac('user')->isUser($userid);
$strUser = aac('user')->getSimpleUser($userid);

switch($ts){

	//用户小组
	case "":
	
		//我的小组
		$myGroup = $db->fetch_all_assoc("select * from ".dbprefix."group_users where userid='$userid'");
		
		if($myGroup != ''){
		
			//我加入的小组
			$myGroups = $db->fetch_all_assoc("select * from ".dbprefix."group_users where userid='$userid' limit 30");
			
			if(is_array($myGroups)){
				foreach($myGroups as $key=>$item){
					$arrMyGroup[] = $new['group']->getOneGroup($item['groupid']);
				}
			}
			
			//我加入的所有小组的话题
			
			if(is_array($myGroup)){
				foreach($myGroup as $item){
					$arrGroup[] = $item['groupid'];
				}
			}
			
			//@bug fixed by anythink
			$strGroup = implode(',',$arrGroup);
			if($strGroup){
				$arrTopics = $db->fetch_all_assoc("select topicid,userid,groupid,title,count_comment,count_view,istop,isphoto,isattach,isposts,addtime,uptime from ".dbprefix."group_topics where groupid in ($strGroup) and isshow='0' order by uptime desc limit 50");
				foreach($arrTopics as $key=>$item){
					$arrTopic[] = $item;
					$arrTopic[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
					$arrTopic[$key]['group'] = aac('group')->getSimpleGroup($item['groupid']);
					$arrTopic[$key]['photo'] = $new['group']->getOnePhoto($item['topicid']);
				}
			}
		
		}
		
		$title = $strUser['username'];
		
		include template("user");
	
		break;

	//my groups
	case "group":
		$myGroup = $db->fetch_all_assoc("select * from ".dbprefix."group_users where userid='$userid'");
		//我加入的小组
		if(is_array($myGroup)){
			foreach($myGroup as $key=>$item){
				$arrMyGroup[] = $new[group]->getOneGroup($item['groupid']);
			}
		}
		
		$myCreateGroup = $db->fetch_all_assoc("select * from ".dbprefix."group where userid='$userid'");
		//我管理的小组
		if(is_array($myCreateGroup)){
			foreach($myCreateGroup as $key=>$item){
				
				$arrMyAdminGroup[] = $new[group]->getOneGroup($item['groupid']);
				
			}
		}
		
		$title = $strUser['username'].L::user_sgroup;
		
		include template("user_group");
		
		break;
		
	//my topics
	case "topic":
	
		$arrTopics = $db->fetch_all_assoc("select topicid,groupid,userid,title,count_comment,addtime,uptime from ".dbprefix."group_topics where userid='".$userid."' order by addtime desc limit 30");
		foreach($arrTopics as $key=>$item){
			$arrTopic[] = $item;
			$arrTopic[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
			$arrTopic[$key]['group'] = aac('group')->getSimpleGroup($item['groupid']);
		}

		$title = $strUser['username'].' '.L::user_publishedpost;

		include template("user_topic");

		break;
		
	//my collect 
	case "collect":

		$arrCollect = $db->fetch_all_assoc("select * from ".dbprefix."group_topics_collects where userid='".$userid."' order by addtime desc limit 30");

		foreach($arrCollect as $item){

			$strTopic = $db->once_fetch_assoc("select topicid,userid,groupid,title,count_comment,count_view,isphoto,isattach,addtime,uptime from ".dbprefix."group_topics where topicid = '".$item['topicid']."'");
			$arrTopics[] = $strTopic;
			
		}

		foreach($arrTopics as $key=>$item){
			$arrTopic[] = $item;
			$arrTopic[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
			$arrTopic[$key]['group'] = aac('group')->getSimpleGroup($item['groupid']);
		}

		$title = $strUser['username'].' Collection of post';

		include template("user_collect");

		break;
	//my reply
	case "reply":
		$myTopics = $db->fetch_all_assoc("select topicid from ".dbprefix."group_topics_comments where userid='".$userid."' group by topicid order by addtime desc limit 30");
		foreach($myTopics as $item){
			$strTopic = $db->once_fetch_assoc("select topicid,userid,groupid,title,count_comment,count_view,isphoto,isattach,addtime,uptime from ".dbprefix."group_topics where topicid = '".$item['topicid']."'");
			$arrTopics[] = $strTopic;
		}

		foreach($arrTopics as $key=>$item){
			$arrTopic[] = $item;
			$arrTopic[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
			$arrTopic[$key]['group'] = aac('group')->getSimpleGroup($item['groupid']);
		}

		$title=$strUser['username'].' '.L::user_postreply;

		include template("user_reply");
		break;
}