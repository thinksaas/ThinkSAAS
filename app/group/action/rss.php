<?php
defined('IN_TS') or die('Access Denied.');
//RSS输出
$groupid = $_GET['groupid'];
$strGroup = $db->once_fetch_assoc("select * from ".dbprefix."group where groupid='$groupid'");
$arrTopics = $db->fetch_all_assoc("select * from ".dbprefix."group_topics where groupid='$groupid' order by addtime desc limit 30");

foreach($arrTopics as $key=>$item){
	$arrTopic[] = $item;
	$arrTopic[$key]['content'] = editor2html($item['content']);
}

$pubdate = time();
header( 'Content-Type:text/xml');
include template("rss");