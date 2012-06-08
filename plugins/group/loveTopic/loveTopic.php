<?php
defined('IN_TS') or die('Access Denied.');
//js绑定事件
function love(){
	global $db;
	global $app;
	$topicid=intval($_GET['id']);
	$data = $db->once_fetch_assoc("select count(*) as count_love from ".dbprefix."group_topics_collects where topicid='$topicid'");
	
	include template("love",'loveTopic');
}

addAction('topic_right','love');