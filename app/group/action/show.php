<?php
defined('IN_TS') or die('Access Denied.');

//小组首页

$groupid = intval($_GET['id']);

$typeid = intval($_GET['typeid']);

$isshow = intval($_GET['isshow']);

$groupNum = $new['group']->findCount('group',array(
	'groupid'=>$groupid,
));

if($groupNum == 0) {
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	$title = '404';
	include pubTemplate("404");
	exit;
}

//小组信息

$strGroup = $new['group']->getOneGroup($groupid);

$strGroup['recoverynum']  = $new['group']->findCount('group_topic',array(
	'groupid'=>$groupid,
	'isshow'=>1,
));

$title = $strGroup['groupname'];

//小组帖子分类
$arrTopicTypes = $new['group']->findAll('group_topic_type',array(
	'groupid'=>$groupid,
));

if(is_array($arrTopicTypes)){
	foreach($arrTopicTypes as $item){
		$arrTopicType[$item['typeid']] = $item;
	}
}

//组长信息
$strLeader = aac('user')->getOneUser($strGroup['userid']);

//判断会员是否加入该小组
$isGroupUser = 0;
if(intval($TS_USER['user']['userid'])){
	$strUser = aac('user')->getOneUser(intval($TS_USER['user']['userid']));
	$isGroupUser = $new['group']->findCount('group_user',array(
		'userid'=>intval($TS_USER['user']['userid']),
		'groupid'=>$groupid,
	));
}

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
		$url = tsUrl('group','show',array('id'=>$groupid,'typeid'=>$typeid,'page'=>''));
	}else{
		$andType = '';
		$url = tsUrl('group','show',array('id'=>$groupid,'page'=>''));
	}
	
	$arrTopics = $new['group']->findAll('group_topic',"`groupid`='$groupid' ".$andType." and `isaudit`='0'",'istop desc,uptime desc',null,$lstart.',30');
	
	if( is_array($arrTopics)){
		foreach($arrTopics as $key=>$item){
			$arrTopic[] = $item;
			$arrTopic[$key]['title'] = htmlspecialchars($item['title']);
			$arrTopic[$key]['typename'] = $arrTopicType[$item['typeid']]['typename'];
			$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
			$arrTopic[$key]['group'] = aac('group')->getOneGroup($item['groupid']);
		}
	}
	
	$topicNum = $new['group']->findCount('group_topic',"`groupid`='$groupid' ".$andType);
	
	$pageUrl = pagination($topicNum, 30, $page, $url);
	
	
	//小组会员
	$groupUser = $new['group']->findAll('group_user',array(
		'groupid'=>$groupid,
	),'addtime desc',null,8);
	
	if(is_array($groupUser)){
		foreach($groupUser as $item){
			$strUser = aac('user')->getOneUser($item['userid']);
			if($strUser){
				$arrGroupUser[] = $strUser;
			}else{
				$new['group']->delete('group_user',array(
					'userid'=>$item['userid'],
					'groupid'=>$groupid,
				));
			}
		}
	}
	
	if($page > 1){
		$title = $strGroup['groupname'].' - 第'.$page.'页';
	}

	$sitekey = $strGroup['groupname'];
	
	if($TS_CF['mobile']) $sitemb = tsUrl('moblie','group',array('ts'=>'show','groupid'=>$strGroup['groupid']));
		
	include template("show");

}