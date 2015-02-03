<?php
defined('IN_TS') or die('Access Denied.');
//搜索结果

$kw=urldecode(tsFilter($_GET['kw']));

if($kw==''){
	header("Location: ".tsUrl('search'));
	exit;
}

$kw = t($kw);

if(count_string_len($kw)<2) {
	header("Location: ".tsUrl('search'));
	exit;
};

switch($ts){
	case "":
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = tsUrl('search','s',array('kw'=>$kw,'page'=>''));
		$lstart = $page*10-10;
		
		$arrAlls = $db->fetch_all_assoc("select groupid as id,'group' as type from ".dbprefix."group where `groupname` like '%$kw%' union select topicid as id,'topic' as type from ".dbprefix."group_topic WHERE `title` like '%$kw%' union select userid as id,'user' as type from ".dbprefix."user_info where username like '%$kw%' union select articleid as id,'article' as type from ".dbprefix."article where `title` like '%$kw%' limit $lstart,10 ");
		
		foreach($arrAlls as $item){
			if($item['type']=='group'){
				$arrGroup[] = $new['search']->find('group',array(
					'groupid'=>$item['id'],
				));
			}elseif($item['type']=='topic'){
				$arrTopic[] = $new['search']->find('group_topic',array(
					'topicid'=>$item['id'],
				));
			}elseif($item['type']=='user'){
				$arrUser[] = $new['search']->find('user_info',array(
					'userid'=>$item['id'],
				));
			}elseif($item['type']=='article'){
				$arrArticle[] = $new['search']->find('article',array(
					'articleid'=>$item['id'],
				));
			}
		}
		
		$all_num = $db->once_num_rows("select groupid as id,'group' as type from ".dbprefix."group where `groupname` like '%$kw%' union select topicid as id,'topic' as type from ".dbprefix."group_topic WHERE `title` like '%$kw%' union select userid as id,'user' as type from ".dbprefix."user_info where username like '%$kw%' union select articleid as id,'article' as type from ".dbprefix."article where `title` like '%$kw%'");
		
		$pageUrl = pagination($all_num, 10, $page, $url);
		
		$title = $kw.' - 全部搜索';
		
		include template("s_all");
		break;
	
	//小组 
	case "group":
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = tsUrl('search','s',array('ts'=>'group','kw'=>$kw,'page'=>''));
		$lstart = $page*10-10;
		
		$arrGroup = $db->fetch_all_assoc("select * from ".dbprefix."group WHERE `groupname` like '%$kw%' order by groupid desc limit $lstart,10");
		
		$group_num = $db->once_num_rows("select * from ".dbprefix."group WHERE groupname like '%$kw%'");
		
		$pageUrl = pagination($group_num, 10, $page, $url);
		
		$title = $kw.' - 小组搜索';

		include template("s_group");
		break;
	//帖子
	case "topic":
	
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = tsUrl('search','s',array('ts'=>'topic','kw'=>$kw,'page'=>''));
		$lstart = $page*10-10;
	
		$arrTopic = $db->fetch_all_assoc("select * from ".dbprefix."group_topic WHERE `title` like '%$kw%' order by topicid desc limit $lstart,10");
		
		$topic_num = $db->once_num_rows("select * from ".dbprefix."group_topic WHERE title like '%$kw%'");
		
		$pageUrl = pagination($topic_num, 10, $page, $url);
		
		$title = $kw.' - 帖子搜索';
		include template("s_topic");
		break;
		
	//用户
	case "user":
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = tsUrl('search','s',array('ts'=>'user','kw'=>$kw,'page'=>''));
		$lstart = $page*10-10;
	
		$arrUser = $db->fetch_all_assoc("select * from ".dbprefix."user_info WHERE `username` like '%$kw%' order by userid desc limit $lstart,10");
		
		$user_num = $db->once_num_rows("select * from ".dbprefix."user_info WHERE `username` like '%$kw%'");
		
		$pageUrl = pagination($user_num, 10, $page, $url);
		
		$title = $kw.' - 用户搜索';
		include template("s_user");
		
		break;
		
	case "article":
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = tsUrl('search','s',array('ts'=>'article','kw'=>$kw,'page'=>''));
		$lstart = $page*10-10;
	
		$arrArticle = $db->fetch_all_assoc("select * from ".dbprefix."article WHERE `title` like '%$kw%' order by addtime desc limit $lstart,10");
		
		$articleNum = $db->once_num_rows("select * from ".dbprefix."article WHERE `title` like '%$kw%'");
		
		$pageUrl = pagination($articleNum, 10, $page, $url);
		
		$title = $kw.' - 文章搜索';
		include template("s_article");
		
		break;
}