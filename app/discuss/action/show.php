<?php
defined('IN_TS') or die('Access Denied.');

$groupid = intval($_GET['id']);
if($groupid == '0') header("Location: ".SITE_URL."index.php");
$strGroup = $new['group']->getOneGroup($groupid);
$strGroup['groupdesc'] = stripslashes($strGroup['groupdesc']);

$typeid = intval($_GET['typeid']);

$strGroup['recoverynum'] = $db->once_num_rows("select * from ".dbprefix."discuss_topics where groupid='$groupid' and isshow='1'");

$title = $strGroup['groupname'];

//小组帖子分类
$arrTopicTypes = $db->fetch_all_assoc("select * from ".dbprefix."discuss_topics_type where groupid='$groupid'");
if(is_array($arrTopicTypes)){
	foreach($arrTopicTypes as $item){
		$arrTopicType[$item['typeid']] = $item;
	}
}

//管理员信息
$leaderId = $strGroup['userid'];

$strLeader = $db->once_fetch_assoc("select * from ".dbprefix."user_info where userid='$leaderId'");

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$lstart = $page*30-30;

if($typeid == '0'){
	$andType = '';
	$url = SITE_URL.tsurl('discuss','show',array('id'=>$groupid,'page'=>''));
}else{
	$andType = "and typeid='$typeid'";
	$url = SITE_URL.tsurl('discuss','show',array('id'=>$groupid,'typeid'=>$typeid,'page'=>''));
}

$sql = "select topicid,typeid,groupid,userid,title,count_comment,count_view,istop,isposts,addtime,uptime from ".dbprefix."discuss_topics where groupid='$groupid' ".$andType." and isshow='0' order by istop desc,uptime desc limit $lstart,30";

$arrTopics = $db->fetch_all_assoc($sql);
if( is_array($arrTopics)){
	foreach($arrTopics as $key=>$item){
		$arrTopic[] = $item;
		$arrTopic[$key]['typename'] = $arrTopicType[$item['typeid']]['typename'];
		$arrTopic[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
		$arrTopic[$key]['group'] = aac('group')->getSimpleGroup($item['groupid']);
	}
}

$topic_num = $db->once_fetch_assoc("select count(topicid) from ".dbprefix."discuss_topics where groupid='$groupid' ".$andType." and isshow='0'");

$pageUrl = pagination($topic_num['count(topicid)'], 30, $page, $url);


if($page > 1){
	$title = $strGroup['groupname'].' - 第'.$page.'页';
}

include template("show");