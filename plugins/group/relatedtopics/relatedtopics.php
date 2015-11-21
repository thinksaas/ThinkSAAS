<?php 
defined('IN_TS') or die('Access Denied.'); 
//相关帖子
function relatedtopics(){

	$arrTopic = aac('group')->findAll('group_topics',array(
		'isposts'=>1,
	),'uptime desc','topicid,title',12);
	
	include template('relatedtopics','relatedtopics');

}

function relatedtopics_css(){

	echo '<link href="'.SITE_URL.'plugins/group/relatedtopics/style.css" rel="stylesheet" type="text/css" />';

}

addAction('pub_header_top','relatedtopics_css');

addAction('group_topic_footer','relatedtopics');