<?php
//帖子标签
defined('IN_TS') or die('Access Denied.');

$tagname = urldecode(trim($_GET['tagname']));

$tagid = aac('tag')->getTagId(t($tagname));

$strTag = $db->once_fetch_assoc("select * from ".dbprefix."tag where tagid='$tagid'");

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$url = SITE_URL.tsurl('group','topic_tag',array('tagid'=>$tagid,'page'=>''));

$lstart = $page*30-30;

$arrTagId = $db->fetch_all_assoc("select * from ".dbprefix."tag_topic_index where tagid='$tagid' limit $lstart,30");

$topic_num = $db->once_fetch_assoc("select count(topicid) from ".dbprefix."tag_topic_index where tagid='$tagid'");

$pageUrl = pagination($topic_num['count(topicid)'], 30, $page, $url);

foreach($arrTagId as $item){

	$strTopic = $db->once_fetch_assoc("select topicid,userid,groupid,title,count_comment,count_view,isphoto,isattach,addtime,uptime from ".dbprefix."group_topics where topicid = '".$item['topicid']."'");
	$arrTopics[] = $strTopic;
	
}

foreach($arrTopics as $key=>$item){
	$arrTopic[] = $item;
	$arrTopic[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
	$arrTopic[$key]['group'] = $new['group']->getSimpleGroup($item['groupid']);
}

//热门tag
$arrTag = $db->fetch_all_assoc("select * from ".dbprefix."tag order by count_topic desc limit 30");

$title = $strTag['tagname'].'热门话题列表';

include template("topic_tag");