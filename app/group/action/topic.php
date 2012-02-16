<?php
defined('IN_TS') or die('Access Denied.');

/* 
 * 小组话题内容页
 */

$topicid = intval($_GET['id']);

if($topicid == 0){
	header("Location: ".SITE_URL);
}

$new['group']->isTopic($topicid);

$strTopic = $new['group']->getOneTopic($topicid);
	
//帖子分类
if($strTopic['typeid'] != '0'){
	$strTopic['type'] = $db->find("select * from ".dbprefix."group_topics_type where typeid='".$strTopic['typeid']."'");
}

//小组
$strGroup = $db->find("select * from ".dbprefix."group where groupid='".$strTopic['groupid']."'");

//判断会员是否加入该小组
$groupid = intval($strGroup['groupid']);
$userid = intval($TS_USER['user']['userid']);

$isGroupUser = $db->findCount("select * from ".dbprefix."group_users where userid='$userid' and groupid='$groupid'");

//浏览方式
if($strGroup['isopen']=='1' && $isGroupUser=='0'){
	
	$title = $strTopic['title'];
	include template("topic_isopen");
	
}else{
	
	
	//帖子标签
	$strTopic['tags'] = aac('tag')->getObjTagByObjid('topic','topicid',$topicid);
	$strTopic['content'] = editor2html($strTopic['content']);
	$strTopic['user']	= aac('user')->getOneUser($strTopic['userid']);
	$strTopic['user']['signed'] = hview($strTopic['user']['signed']);
	$title = $strTopic['title'];
	
	//评论列表开始
	$sc = isset($_GET['sc']) ? $_GET['sc'] : 'desc';
	
	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
	
	//倒序asc
	if($sc=='asc'){
		$url = SITE_URL.tsurl('group','topic',array('id'=>$topicid,'sc'=>$sc,'page'=>''));
	}else{
		$url = SITE_URL.tsurl('group','topic',array('id'=>$topicid,'page'=>''));
	}
	
	
	$lstart = $page*15-15;
	
	$arrComment = $db->findAll("select * from ".dbprefix."group_topics_comments where `topicid`='$topicid' order by addtime $sc limit $lstart,15");
	foreach($arrComment as $key=>$item){
		$arrTopicComment[] = $item;
		$arrTopicComment[$key]['user'] = aac('user')->getOneUser($item['userid']);
		$arrTopicComment[$key]['content'] = editor2html($item['content']);
		$arrTopicComment[$key]['recomment'] = $new['group']->recomment($item['referid']);
	}
	
	$commentNum = $db->find("select count(*) from ".dbprefix."group_topics_comments where `topicid`='$topicid'");
	
	$pageUrl = pagination($commentNum['count(*)'], 15, $page, $url);
	//评论列表结束
	
	
	//判断会员是否加入该小组
	$userid = intval($TS_USER['user']['userid']);
	$isGroupUser = $db->findCount("select * from ".dbprefix."group_users where userid='$userid' and groupid='".$strTopic['groupid']."'");

	
	$groupid = $strTopic['groupid'];
	
	//小组成员
	$strGroupUser = $db->find("select * from ".dbprefix."group_users where userid='$userid' and groupid='".$strTopic['groupid']."'");
	
	//最新帖子
	$newTopics = $db->findAll("select topicid,userid,title from ".dbprefix."group_topics where groupid='$groupid' and isshow='0' order by addtime desc limit 6");
	
	foreach($newTopics as $key=>$item){
		$newTopic[] = $item;
		$newTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
	}
	
	($page > 1) ? $titlepage = " - 第".$page."页" : $titlepage='';
	
	$title = $title.$titlepage.' - '.$strGroup['groupname'];
	include template('topic');

	$db->update('group_topics',array(
		'count_view'=>'count_view+1',
	),array(
		'topicid'=>$topicid,
	));
	
}