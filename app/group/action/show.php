<?php
defined('IN_TS') or die('Access Denied.');

//小组首页

$groupid = intval($_GET['id']);

$typeid = intval($_GET['typeid']);

$isshow = intval($_GET['isshow']);

//小组信息
$strGroup = $new['group']->getOneGroup($groupid);

if($strGroup == '') {
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	$title = '404';
	include pubTemplate("404");
	exit;
}

$strGroup['recoverynum'] = $db->once_num_rows("select * from ".dbprefix."group_topics where groupid='$groupid' and isshow='1'");

$title = $strGroup['groupname'];

//小组帖子分类
$arrTopicTypes = $db->fetch_all_assoc("select * from ".dbprefix."group_topics_type where groupid='$groupid'");
if(is_array($arrTopicTypes)){
	foreach($arrTopicTypes as $item){
		$arrTopicType[$item['typeid']] = $item;
	}
}

//组长信息
$leaderId = $strGroup['userid'];

$strLeader = $db->once_fetch_assoc("select * from ".dbprefix."user_info where userid='$leaderId'");

//判断会员是否加入该小组
$userid = $TS_USER['user']['userid'];
$strUser = aac('user')->getOneUser($userid);
$isGroupUser = $db->once_num_rows("select * from ".dbprefix."group_users where userid='$userid' and groupid='$groupid'");


//小组是否需要审核
if($strGroup['isaudit']=='1'){
	//推荐小组
	$arrRecommendGroup = $new['group']->getRecommendGroup('7');
	include template("group_isaudit");
	
}elseif($strGroup['isopen']=='1' && $isGroupUser=='0'){
	//是否开放访问
	include template("group_isopen");
}else{

	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
	
	$lstart = $page*30-30;
	
	if($typeid > 0){
		$andType = " and `typeid`='$typeid'";
		$andShow = " and `isshow`='0'";
		$url = SITE_URL.tsUrl('group','show',array('id'=>$groupid,'page'=>''));
	}elseif($isshow==1){
		$andType = '';
		$andShow = " and `isshow`='1'";
		$url = SITE_URL.tsUrl('group','show',array('id'=>$groupid,'isshow'=>'1','page'=>''));
	}else{
		$andType = '';
		$andShow = " and `isshow`='0'";
		$url = SITE_URL.tsUrl('group','show',array('id'=>$groupid,'typeid'=>$typeid,'page'=>''));
	}
	
	$sql = "select * from ".dbprefix."group_topics where groupid='$groupid' ".$andType.$andShow." order by istop desc,uptime desc limit $lstart,30";
	
	$arrTopics = $db->fetch_all_assoc($sql);
	if( is_array($arrTopics)){
		foreach($arrTopics as $key=>$item){
			$arrTopic[] = $item;
			$arrTopic[$key]['title'] = htmlspecialchars($item['title']);
			$arrTopic[$key]['typename'] = $arrTopicType[$item['typeid']]['typename'];
			$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
			$arrTopic[$key]['group'] = aac('group')->getOneGroup($item['groupid']);
		}
	}
	
	$topic_num = $db->once_fetch_assoc("select count(topicid) from ".dbprefix."group_topics where groupid='$groupid' ".$andType.$andShow);
	
	$pageUrl = pagination($topic_num['count(topicid)'], 30, $page, $url);
	
	
	//小组会员
	$groupUser = $db->fetch_all_assoc("select userid from ".dbprefix."group_users where groupid='$groupid' order by addtime DESC limit 8");
	
	if(is_array($groupUser)){
		foreach($groupUser as $item){
			$arrGroupUser[] = aac('user')->getOneUser($item['userid']);
		}
	}
	
	if($page > 1){
		$title = $strGroup['groupname'].' - 第'.$page.'页';
	}
	
	$pageKey = $strGroup['groupname'].',小组';
	$pageDesc = $strGroup['groupdesc'];

	include template("show");

}