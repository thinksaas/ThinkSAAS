<?php
defined('IN_TS') or die('Access Denied.');
//搜索结果
$userid = intval($TS_USER['user']['userid']);

/*
$strKey = $db->once_fetch_assoc("select * from ".dbprefix."search_key where userid='$userid'");
if($strKey){
	$nowTime = time();
	$addtime = $strKey['addtime'];
	$forTime = $nowTime - $addtime;
	if($forTime < 30000) qiMsg("请等待30秒后再搜索！",'点击返回','index.php?app=search');
}
*/

$kw=urldecode(t($_GET['kw']));

if($kw==''){
	header("Location: ".SITE_URL.tsurl('search'));
	exit;
}


if(strlen($kw)<2) {
	header("Location: ".SITE_URL.tsurl('search'));
	exit;
};

//$db->query("insert into ".dbprefix."search_key (`userid`,`keyword`,`addtime`) values ('$userid','$kw','".time()."')");

switch($ts){
	case "":
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = "index.php?app=search&ac=s&kw=".$kw."&page=";
		$lstart = $page*10-10;
		
		$arrAlls = $db->fetch_all_assoc("select groupid as id,'group' as type from ".dbprefix."group where groupname like '%$kw%' or groupdesc like '%$kw%'  union select topicid as id,'topic' as type from ".dbprefix."group_topics WHERE title like '%$kw%' union select userid as id,'user' as type from ".dbprefix."user_info where username like '%$kw%' limit $lstart,10");
		
		foreach($arrAlls as $item){
			if($item['type']=='group'){
				$arrGroup[] = aac('group')->getOneGroup($item['id']);
			}elseif($item['type']=='topic'){
				$arrTopics[] = aac('group')->getOneTopic($item['id']);
			}elseif($item['type']=='user'){
				$arrUser[] = aac('user')->getUserForApp($item['id']);
			}
		}
	
		foreach($arrTopics as $key=>$titem){
			$arrTopic[] = $titem;
			$arrTopic[$key]['user'] = aac('user')->getUserForApp($titem['userid']);
		}
		
		$all_num = $db->once_num_rows("select groupid as id,'group' as type from ".dbprefix."group where groupname like '%$kw%' or groupdesc like '%$kw%'  union select topicid as id,'topic' as type from ".dbprefix."group_topics WHERE title like '%$kw%'");
		
		$pageUrl = pagination($all_num, 10, $page, $url);
		
		$title = $kw.' - 全部搜索';
		
		include template("s_all");
		break;
	
	//小组 
	case "group":
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = "index.php?app=search&ac=s&ts=group&kw=".$kw."&page=";
		$lstart = $page*10-10;
		
		$arrGroups = $db->fetch_all_assoc("select groupid from ".dbprefix."group WHERE groupname like '%$kw%' or groupdesc like '%$kw%' order by groupid desc limit $lstart,10");
		
		$group_num = $db->once_num_rows("select groupid from ".dbprefix."group WHERE groupname like '%$kw%' or groupdesc like '%$kw%'");
		
		if(is_array($arrGroups)){
			foreach($arrGroups as $item){
				$arrGroup[] = aac('group')->getOneGroup($item['groupid']);
			}
		}
		
		$pageUrl = pagination($group_num, 10, $page, $url);
		
		$title = $kw.' - 小组搜索';

		include template("s_group");
		break;
	//帖子
	case "topic":
	
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = "index.php?app=search&ac=s&ts=topic&kw=".$kw."&page=";
		$lstart = $page*10-10;
	
		$arrTopics = $db->fetch_all_assoc("select * from ".dbprefix."group_topics WHERE title like '%$kw%' order by topicid desc limit $lstart,10");
		
		foreach($arrTopics as $key=>$item){
			$arrTopic[] = $item;
			$arrTopic[$key]['user'] = aac('user')->getUserForApp($item['userid']);
		}
		
		$topic_num = $db->once_num_rows("select * from ".dbprefix."group_topics WHERE title like '%$kw%'");
		
		$pageUrl = pagination($topic_num, 10, $page, $url);
		
		$title = $kw.' - 帖子搜索';
		include template("s_topic");
		break;
		
	//用户
	case "user":
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = "index.php?app=search&ac=s&ts=user&kw=".$kw."&page=";
		$lstart = $page*10-10;
	
		$arrUsers = $db->fetch_all_assoc("select userid from ".dbprefix."user_info WHERE username like '%$kw%' order by userid desc limit $lstart,10");
		
		foreach($arrUsers as $item){
			$arrUser[] = aac('user')->getUserForApp($item['userid']);
		}
		
		$user_num = $db->once_num_rows("select userid from ".dbprefix."user_info WHERE username like '%$kw%'");
		
		$pageUrl = pagination($user_num, 10, $page, $url);
		
		$title = $kw.' - 用户搜索';
		include template("s_user");
		
		break;
}