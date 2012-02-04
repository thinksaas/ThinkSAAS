<?php
defined('IN_TS') or die('Access Denied.');

$discussid = intval($_GET['id']);

if($discussid == '0') header("Location: ".SITE_URL."index.php");

$strDiscuss = $new['discuss']->getOneDiscuss($discussid);

$strDiscuss['desc'] = stripslashes($strDiscuss['desc']);

$strDiscuss['user'] = aac('user')->getOneUser($strDiscuss['userid']);

//帖子分类
$arrTopicTypes = $new['discuss']->getTopicType($discussid);

foreach($arrTopicTypes as $item){
	$arrTopicType[$item['typeid']] = $item;
}

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$lstart = $page*30-30;

if($typeid == '0'){
	$andType = '';
	$url = SITE_URL.tsurl('discuss','show',array('id'=>$discussid,'page'=>''));
}else{
	$andType = "and typeid='$typeid'";
	$url = SITE_URL.tsurl('discuss','show',array('id'=>$discussid,'typeid'=>$typeid,'page'=>''));
}

$sql = "select topicid,typeid,discussid,userid,title,count_comment,count_view,istop,isposts,addtime,uptime from ".dbprefix."discuss_topics where discussid='$discussid' ".$andType." and isshow='0' order by istop desc,uptime desc limit $lstart,30";

$arrTopics = $db->fetch_all_assoc($sql);
if( is_array($arrTopics)){
	foreach($arrTopics as $key=>$item){
		$arrTopic[] = $item;
		$arrTopic[$key]['typename'] = $arrTopicType[$item['typeid']]['typename'];
		$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
	}
}

$topicNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."discuss_topics where discussid='$discussid' ".$andType." and isshow='0'");

$pageUrl = pagination($topicNum['count(*)'], 30, $page, $url);


$title = $strDiscuss['name'];

if($page > 1){
	$title = $strDiscuss['name'].' - 第'.$page.'页';
}

include template("show");