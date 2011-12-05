<?php
defined('IN_TS') or die('Access Denied.');

//小组首页

$groupid = intval($_GET['groupid']);

if($groupid == '0') header("Location: ".SITE_URL."index.php");

$new['group']->isGroup($groupid);

$typeid = intval($_GET['typeid']);

//小组信息
$strGroup = $new['group']->getOneGroup($groupid);
$strGroup['groupdesc'] = stripslashes($strGroup['groupdesc']);

if($strGroup == '') header("Location: ".SITE_URL."index.php");

$strGroup['recoverynum'] = $db->once_num_rows("select * from ".dbprefix."group_topics where groupid='$groupid' and isshow='1'");

$strGroup['groupdesc'] = editor2html($strGroup['groupdesc']);



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
	
	if($typeid == '0'){
		$andType = '';
		$url = SITE_URL.tsurl('group','group',array('groupid'=>$groupid,'page'=>''));
	}else{
		$andType = "and typeid='$typeid'";
		$url = SITE_URL.tsurl('group','group',array('groupid'=>$groupid,'typeid'=>$typeid,'page'=>''));
	}
	
	$sql = "select topicid,typeid,groupid,userid,title,count_comment,count_view,istop,isphoto,isattach,isposts,addtime,uptime from ".dbprefix."group_topics where groupid='$groupid' ".$andType." and isshow='0' order by istop desc,uptime desc limit $lstart,30";
	
	$arrTopics = $db->fetch_all_assoc($sql);
	if( is_array($arrTopics)){
		foreach($arrTopics as $key=>$item){
			$arrTopic[] = $item;
			$arrTopic[$key]['typename'] = $arrTopicType[$item['typeid']]['typename'];
			$arrTopic[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
			$arrTopic[$key]['group'] = aac('group')->getSimpleGroup($item['groupid']);
			$arrTopic[$key]['photo'] = $new['group']->getOnePhoto($item['topicid']);
		}
	}
	
	$topic_num = $db->once_fetch_assoc("select count(topicid) from ".dbprefix."group_topics where groupid='$groupid' ".$andType." and isshow='0'");
	
	$pageUrl = pagination($topic_num['count(topicid)'], 30, $page, $url);
	
	
	//小组会员
	$groupUser = $db->fetch_all_assoc("select userid from ".dbprefix."group_users where groupid='$groupid' order by addtime DESC limit 8");
	
	if(is_array($groupUser)){
		foreach($groupUser as $item){
			$arrGroupUser[] = aac('user')->getUserForApp($item['userid']);
		}
	}
	
	if($page > 1){
		$title = $strGroup['groupname'].' - 第'.$page.'页';
	}

	include template("group");

}