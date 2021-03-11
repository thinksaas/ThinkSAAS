<?php 
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();

$groupid = tsIntval($_GET['groupid']);

$strGroup = $new['group']->find('group',array(
	'groupid'=>$groupid,
));

if($strGroup['userid']==$userid || $TS_USER['isadmin']==1){

	switch($ts){

		case "":

			$arrTopic = $new['group']->findAll('topic',array(
				'groupid'=>$groupid,
				'isaudit'=>1,
			));

			$title = '审核帖子';
			include template('audit');
		
			break;
			
		//执行审核
		case "do":
			
			$topicid = tsIntval($_GET['topicid']);
			
			$new['group']->update('topic',array(
				'topicid'=>$topicid,
			),array(
				'isaudit'=>'0',
			));
			
			//统计需要审核的帖子
			$count_topic_audit = $new['group']->findCount('topic',array(
				'groupid'=>$groupid,
				'isaudit'=>'1',
			));
			
			// 统计小组下帖子数并更新
			$count_topic = $new['group']->findCount('topic',array(
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

			$topicid = tsIntval($_GET['topicid']);

			$strTopic = aac('topic')->getOneTopic($topicid);

			if($strGroup['groupid']!=$groupid){
				tsNotice('非法操作！');
			}
			
			aac('topic')->deleteTopic($strTopic);
			
			//统计需要审核的帖子
			$count_topic_audit = $new['group']->findCount('topic',array(
				'groupid'=>$groupid,
				'isaudit'=>'1',
			));
			
			// 统计小组下帖子数并更新
			$count_topic = $new['group']->findCount('topic',array(
				'groupid'=>$groupid,
			));
			
			$new['group']->update('group',array(
				'groupid'=>$groupid,
			),array(
				'count_topic'=>$count_topic,
				'count_topic_audit'=>$count_topic_audit,
			));
			
			tsNotice('删除成功！');
			
			break;

	}
}else{
	tsNotice('非法操作！');
}