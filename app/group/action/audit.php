<?php 

defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();

$groupid = intval($_GET['groupid']);

$strGroup = $new['group']->find('group',array(
	'groupid'=>$groupid,
));

if($strGroup['userid']==$userid || $TS_USER['isadmin']==1){

	switch($ts){

		case "":

			$arrTopic = $new['group']->findAll('group_topic',array(
				'groupid'=>$groupid,
				'isaudit'=>1,
			));

			$title = '审核帖子';
			include template('audit');
		
			break;
			
		//执行审核
		case "do":
			
			$topicid = intval($_GET['topicid']);
			
			$new['group']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'isaudit'=>'0',
			));
			
			//统计需要审核的帖子
			$count_topic_audit = $new['group']->findCount('group_topic',array(
				'groupid'=>$groupid,
				'isaudit'=>'1',
			));
			
			// 统计小组下帖子数并更新
			$count_topic = $new['group']->findCount('group_topic',array(
				'groupid'=>$groupid,
			));
			
			$new['group']->update('group',array(
				'groupid'=>$groupid,
			),array(
				'count_topic'=>$count_topic,
				'count_topic_audit'=>$count_topic_audit,
			));
			
			tsNotice('审核成功！');
			
			break;
			
		//删除审核
		case "delete":
			$topicid = intval($_GET['topicid']);
			
			$new['group']->delTopic($topicid);
			
			//统计需要审核的帖子
			$count_topic_audit = $new['group']->findCount('group_topic',array(
				'groupid'=>$groupid,
				'isaudit'=>'1',
			));
			
			// 统计小组下帖子数并更新
			$count_topic = $new['group']->findCount('group_topic',array(
				'groupid'=>$groupid,
			));
			
			$new['group']->update('group',array(
				'groupid'=>$groupid,
			),array(
				'count_topic'=>$count_topic,
				'count_topic_audit'=>$count_topic_audit,
			));
			
			tsNotice('审核成功！');
			
			tsNotice('删除成功！');
			
			break;

	}
}else{
	tsNotice('非法操作！');
}