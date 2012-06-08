<?php
//帖子标签
defined('IN_TS') or die('Access Denied.');

switch($ts){

	//标签
	case "":
	
		$name = urldecode(trim($_GET['name']));

		$tagid = aac('tag')->getTagId(t($name));

		$strTag = $db->once_fetch_assoc("select * from ".dbprefix."tag where tagid='$tagid'");

		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

		$url = SITE_URL.tsUrl('group','tag',array('name'=>urlencode($name),'page'=>''));

		$lstart = $page*30-30;

		$arrTagId = $db->fetch_all_assoc("select * from ".dbprefix."tag_topic_index where tagid='$tagid' limit $lstart,30");
		
		$topicNum = $new['group']->findCount('tag_topic_index',array(
			'tagid'=>$tagid,
		));

		$pageUrl = pagination($topicNum, 30, $page, $url);

		foreach($arrTagId as $item){

			$strTopic = $db->once_fetch_assoc("select * from ".dbprefix."group_topics where topicid = '".$item['topicid']."'");
			$arrTopics[] = $strTopic;
			
		}

		foreach($arrTopics as $key=>$item){
			$arrTopic[] = $item;
			$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
			$arrTopic[$key]['group'] = $new['group']->getOneGroup($item['groupid']);
		}

		//热门tag
		$arrTag = $db->fetch_all_assoc("select * from ".dbprefix."tag order by count_topic desc limit 30");

		$title = $strTag['tagname'].'热门话题列表';

		include template("tag");
	
		break;
	
	//标签列表
	case "list":
	
		$page = isset($_GET['page']) ? $_GET['page'] : '1';
		
		$url = SITE_URL.tsurl('group','tag',array('ts'=>'list','page'=>''));
		
		$lstart = $page*40-40;
		
		$arrTag = $new['group']->findAll('tag',"`count_topic`!=''",null,null,$lstart.',40');
		
		$tagNum = $new['group']->findCount('tag',"`count_topic`!=''");
		
		$pageUrl = pagination($tagNum, 40, $page, $url);
		
		$title = '标签';
		include template('tag_list');
	
		break;

}