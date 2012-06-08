<?php
defined('IN_TS') or die('Access Denied.');

switch ($ts)
{
	case "":
	
		if(!$_GET['id']){
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			$title = '404';
			include pubTemplate("404");
			exit;
		}
		
		$topicid = intval($_GET['id']);

		$strTopic = $new['group']->getOneTopic($topicid);

		if($strTopic == false){
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			$title = '404';
			include pubTemplate("404");
			exit;
		}
		
		// 帖子分类
		if ($strTopic['typeid'] != '0')
		{
			$strTopic['type'] = $new['group']->find('group_topics_type', array('typeid' => $strTopic['typeid'],
					));
		} 
		// 小组
		$strGroup = $new['group']->find('group', array('groupid' => $strTopic['groupid'],
				));
		
		$strTopic['content'] = preg_replace("/\[@(.*)\:(.*)]/U","<a href='".SITE_URL.tsUrl('group','user',array('id'=>'$2'))." ' rel=\"face\" uid=\"$2\"'>@$1</a>",$strTopic['content']);
		
		// 补贴列表
		$arrAfter = $new['group']->topicAfter($strTopic['topicid']);
        //高亮
		for($i=1;$i<10;$i++){
		 $color[]=color($i);	
		 
		}
		foreach($arrAfter as $key => $item)
		{
			$strTopic['after'][] = $item;
			$strTopic['after'][$key]['content'] = $item['content'];
		} 
		// 判断会员是否加入该小组
		$groupid = intval($strGroup['groupid']);
		$userid = intval($TS_USER['user']['userid']);

		$isGroupUser = $new['group']->findCount('group_users', array('userid' => $userid,
				'groupid' => $groupid,
				)); 
		// 判断用户是否回复帖子
		$isComment = $new['group']->findCount('group_topics_comments', array('userid' => $userid,
				'topicid' => $strTopic['topicid'],
				)); 
		// 最新帖子
		$newTopic = group::newTopic($strTopic['groupid'], 10); 
		// 浏览方式
		if ($strGroup['isopen'] == '1' && $isGroupUser == '0')
		{
			$title = $strTopic['title'];
			include template("topic_isopen");
		}
		else
		{ 
			// 上一篇帖子
			$upTopic = $new['group']->find('group_topics', 'topicid<' . $topicid . ' and groupid=' . $groupid, 'topicid,title'); 
			// 下一篇帖子
			$downTopic = $new['group']->find('group_topics', 'topicid>' . $topicid . ' and groupid=' . $groupid, 'topicid,title'); 
			// 帖子标签
			$strTopic['tags'] = aac('tag')->getObjTagByObjid('topic', 'topicid', $topicid);
			$strTopic['user'] = aac('user')->getOneUser($strTopic['userid']);
			$strTopic['user']['signed'] = nl2br(htmlspecialchars($strTopic['user']['signed']));
			foreach($strTopic['tags'] as $g_like){
			 $tag[]=$g_like['tagid'];
			}
			
			$sql="select topicid from ".dbprefix."tag_topic_index where tagid IN('".implode(',',$tag)."')";
			$g_list = $db->fetch_all_assoc("select * from ".dbprefix."group_topics where topicid IN($sql) AND topicid!=' $topicid' limit 10");
			foreach($g_list as $key=>$value){
			 $g_lists[]=$value;
			}
			$title = $strTopic['title']; 
			// 评论列表开始
			$sc = isset($_GET['sc']) ? $_GET['sc'] : 'asc';

			$page = isset($_GET['page']) ? intval($_GET['page']) : 1; 
			// 倒序asc
			if ($sc == 'desc')
			{
				$url = SITE_URL . tsUrl('group', 'topic', array('id' => $topicid, 'sc' => $sc, 'page' => ''));
			}
			else
			{
				$url = SITE_URL . tsUrl('group', 'topic', array('id' => $topicid, 'page' => ''));
			}

			$lstart = $page * 15-15;

			$arrComment = $db->fetch_all_assoc("select * from " . dbprefix . "group_topics_comments where `topicid`='$topicid' order by addtime $sc limit $lstart,15");
			foreach($arrComment as $key => $item)
			{
				$arrTopicComment[] = $item;
				$arrTopicComment[$key]['l'] = (($page-1) * 15) + $key + 1;
				$arrTopicComment[$key]['user'] = aac('user')->getOneUser($item['userid']);

				$arrTopicComment[$key]['content'] = BBCode2Html($item['content']);
				$arrTopicComment[$key]['content'] = preg_replace("/\[@(.*)\:(.*)]/U","<a href='".SITE_URL.tsUrl('group','user',array('id'=>'$2'))." ' rel=\"face\" uid=\"$2\"'>@$1</a>",$arrTopicComment[$key]['content']);	
				
				$arrTopicComment[$key]['recomment'] = $new['group']->recomment($item['referid']);
			}

			$commentNum = $db->once_fetch_assoc("select count(*) from " . dbprefix . "group_topics_comments where `topicid`='$topicid'");

			$pageUrl = pagination($commentNum['count(*)'], 15, $page, $url); 
			// 评论列表结束
			// 判断会员是否加入该小组
			$userid = intval($TS_USER['user']['userid']);
			$isGroupUser = $db->once_num_rows("select * from " . dbprefix . "group_users where userid='$userid' and groupid='" . $strTopic['groupid'] . "'");

			$groupid = $strTopic['groupid']; 
			// 小组成员
			$strGroupUser = $db->once_fetch_assoc("select * from " . dbprefix . "group_users where userid='$userid' and groupid='" . $strTopic['groupid'] . "'");

			($page > 1) ? $titlepage = " - 第" . $page . "页" : $titlepage = '';

			$title = $title . $titlepage . ' - ' . $strGroup['groupname'];

			$pageKey = $strTopic['title'];
			$pageDesc = getsubstrutf8(t($strTopic['content']), 0, 200);
			
			//主题帖子载入
            if($strTopic['thread_type']){
              include THINKROOT.'/app/group/thread/'.$strTopic['thread_type'].'/topic.php';
			}else{
			  include template('topic'); 
			}
			
			// 增加浏览次数
			$new['group']->update('group_topics', array('topicid' => $topicid,
					), array('count_view' => $strTopic['count_view'] + 1,
					));
		}
		break; 
	// 全部帖子
	case "list":
		$page = isset($_GET['page']) ? $_GET['page'] : '1';
		$url = SITE_URL . tsUrl('group', 'topic', array('ts' => 'list', 'page' => ''));
		$lstart = $page * 20-20;

		$arrTopics = $new['group']->findAll('group_topics', null, 'topicid desc', null, $lstart . ',20');

		foreach($arrTopics as $key => $item)
		{
			$arrTopic[] = $item;
			$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
			$arrTopic[$key]['group'] = $new['group']->getOneGroup($item['groupid']);
			$arrTopic[$key]['title'] = htmlspecialchars($item['title']);
			$arrTopic[$key]['content'] = getsubstrutf8(BBCode2Html($item['content']), 0, 200);
		}

		$topicNum = $new['group']->findCount('group_topics');

		$pageUrl = pagination($topicNum, 20, $page, $url); 
		// 热门帖子
		$arrHotTopics = $new['group']->findAll('group_topics', null, 'count_comment desc', 'groupid,topicid,title,count_comment', 10);
		foreach($arrHotTopics as $key => $item)
		{
			$arrHotTopic[] = $item;
			$arrHotTopic[$key]['group'] = $new['group']->getOneGroup($item['groupid']);
		}

		if ($page > 1)
		{
			$title = '最新帖子 - 第' . $page . '页';
		}
		else
		{
			$title = '最新帖子';
		}

		include template('topic_list');
		break;
}
