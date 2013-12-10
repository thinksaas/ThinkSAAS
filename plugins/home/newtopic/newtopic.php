<?php 
defined('IN_TS') or die('Access Denied.'); 

function newtopic(){
	global $db;
	//最新帖子	
	$uptimes  = time()-(86400*5);
	
	$arrNewTopics = aac('group')->findAll('group_topic',"`isaudit`='0' and `uptime`>'$uptimes'",'uptime desc');
	//$arrNewTopics = aac('group')->findAll('group_topic',"`isaudit`='0' and `addtime`>'$uptimes'",'addtime desc');
	
	$uptime  = time()-(86400*30);
	$arrTopics = $db->fetch_all_assoc("select * from (select * from ".dbprefix."group_topic where `isaudit`='0' and`uptime`>'$uptime' order by uptime desc ) ".dbprefix."group_topic group by userid order by uptime desc limit 35");
	//$arrTopics = $db->fetch_all_assoc("select * from (select * from ".dbprefix."group_topic where `isaudit`='0' and`addtime`>'$uptime' order by addtime desc ) ".dbprefix."group_topic group by userid order by addtime desc limit 35");
	
	foreach($arrTopics as $key=>$item){
		foreach($arrNewTopics as $nkey=>$nitem){
			if($item['topicid']!=$nitem['topicid'] && $item['userid']==$nitem['userid']){
				if(count($arrTopics[$key]['topic'])<8){
					$arrTopics[$key]['topic'][] = $nitem;
				}
			}
		}
	}
	
	foreach($arrTopics as $key=>$item){
	
			$arrTopic[] = $item;
			$arrTopic[$key]['title']=htmlspecialchars($item['title']);
			$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
			$arrTopic[$key]['group'] = aac('group')->getOneGroup($item['groupid']);
		
	}
	
	
	
	include template('newtopic','newtopic');
	
}

function newtopic_css(){

	echo '<link href="'.SITE_URL.'plugins/home/newtopic/style.css" rel="stylesheet" type="text/css" />';

}

addAction('home_index_left','newtopic');
addAction('pub_header_top','newtopic_css');