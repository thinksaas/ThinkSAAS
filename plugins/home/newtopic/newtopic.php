<?php 
defined('IN_TS') or die('Access Denied.'); 

function newtopic(){
	global $db;
	//最新帖子	
	$arrTopic = aac('group')->findAll('group_topic',array(
		'isaudit'=>0,
	),'uptime desc','topicid,userid,groupid,title,gaiyao,label,count_comment,uptime',35);
	
	foreach($arrTopic as $key=>$item){
			$arrTopic[$key]['title']=tsTitle($item['title']);
			$arrTopic[$key]['gaiyao']=tsTitle($item['gaiyao']);
			$arrTopic[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
			$arrTopic[$key]['group'] = aac('group')->getOneGroup($item['groupid']);
			$arrTopic[$key]['photos'] = aac('group')->getTopicPhoto($item['topicid'],3);
	}

	include template('newtopic','newtopic');
	
}

function newtopic_css(){

	echo '<link href="'.SITE_URL.'plugins/home/newtopic/style.css?v=201812251839" rel="stylesheet" type="text/css" />';

}

addAction('home_index_left','newtopic');
addAction('pub_header_top','newtopic_css');