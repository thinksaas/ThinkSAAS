<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	
	case "":
	
		//最新帖子	
		$arrNewTopics = aac('group')->findAll('group_topic',"`isaudit`='0'",'uptime desc',null,35);
		foreach($arrNewTopics as $key=>$item){
			$arrTopic[] = $item;
			$arrTopic[$key]['title']=htmlspecialchars($item['title']);
			$arrTopic[$key]['user'] = aac('user')->getOneUser($item['userid']);
		}

		$title = '帖子'.' - '.$TS_CF['info']['powered'];
		include template('topic');
	
		break;
		
		
	case "show":
	
		$topicid = intval($_GET['topicid']);
		$strTopic = $new['mobile']->find('group_topic',array(
			'topicid'=>$topicid,
		));
		
		$strTopic['content'] = str_replace('nowrap','',$strTopic['content']);
		
		$strTopic['user'] = aac('user')->getOneUser($strTopic['userid']);
		
		
		//评论
		$arrComment = $new['mobile']->findAll('group_topic_comment',array(
			'topicid'=>$strTopic['topicid'],
		),'addtime desc',null,10);
		foreach($arrComment as $key=>$item){
			$arrComment[$key]['user'] = aac('user')->getOneUser($item['userid']);
		}

		
		$arrHeadButton = array(
			'left'=>array(
				'title'=>'返回',
				'url'=>tsUrl('mobile','group',array('ts'=>'show','groupid'=>$strTopic['groupid'])),
			),
		);
		$title = $strTopic['title'].' - '.$TS_CF['info']['powered'];
		include template('topic_show');
	
		break;
		
	case "add":
		$userid = $new['mobile']->isLogin();
		$groupid = intval($_GET['groupid']);
		
		$isGroupUser = $new['mobile']->findCount('group_user',array(
			'userid'=>$userid,
			'groupid'=>$groupid,
		));
		
		if($isGroupUser==0){
			$new['mobile']->mNotice('你没有加入小组！');
		}
		
		$arrHeadButton = array(
			'left'=>array(
				'title'=>'返回',
				'url'=>tsUrl('mobile','group',array('ts'=>'show','groupid'=>$groupid)),
			),
		);
		
		$title = '发帖 - '.$TS_CF['info']['powered'];
		include template('topic_add');
		break;
	case "adddo":
	
		if($_POST['token'] != $_SESSION['token']) {
			$new['mobile']->mNotice('非法操作！');
		}
		
		$userid = $new['mobile']->isLogin();
		$groupid = intval($_POST['groupid']);
		$title = tsClean($_POST['title']);
		$content = tsClean($_POST['content']);
		
		$isGroupUser = $new['mobile']->findCount('group_user',array(
			'userid'=>$userid,
			'groupid'=>$groupid,
		));
		
		if($isGroupUser==0){
			$new['mobile']->mNotice('你没有加入小组！');
		}
		
		if($title && $content){
		
			$topicid = $new['mobile']->create('group_topic',array(
				'userid'=>$userid,
				'groupid'=>$groupid,
				'title'=>$title,
				'content'=>$content,
				'addtime'=>time(),
				'uptime'=>time(),
			));
			
			//统计
			$count_topic = $new['mobile']->findCount('group_topic',array(
				'groupid'=>$groupid,
			));
			
			$new['mobile']->update('group',array(
				'groupid'=>$groupid,
			),array(
				'count_topic'=>$count_topic,
			));
			
			header('Location: '.tsUrl('mobile','topic',array('ts'=>'show','topicid'=>$topicid)));
		
		}else{
		
			$new['mobile']->mNotice('标题和内容都不能为空！');
		
		}
		
		break;
	
}