<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
	
	case "list":
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=group&ac=admin&mg=topic&ts=list&page=';
		$lstart = $page*10-10;
		
		$arrTopic = $new['group']->findAll('group_topic',null,'addtime desc',null,$lstart.',10');
		
		$topicNum = $new['group']->findCount('group_topic');
		
		$pageUrl = pagination($topicNum, 10, $page, $url);

		include template("admin/topic_list");
		
		break;
		
	case "delete":
		$topicid = intval($_GET['topicid']);
		
		$new['group']->delTopic($topicid);

		qiMsg('删除成功');
		break;
	
	//帖子审核
	case "isaudit":
	
		$topicid = intval($_GET['topicid']);
		
		$strTopic = $new['group']->find('group_topic',array(
			'topicid'=>$topicid,
		));
		
		if($strTopic['isaudit']==0){
			$new['group']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'isaudit'=>1,
			));
		}
		
		if($strTopic['isaudit']==1){
			$new['group']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'isaudit'=>0,
			));
		}
		
		qiMsg('操作成功！');
	
		break;
		
	//删除的帖子
	case "deletetopic":
	
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=group&ac=admin&mg=topic&ts=deletetopic&page=';
		$lstart = $page*10-10;
		
		$arrTopic = $new['group']->findAll('group_topic',array('isdelete'=>'1'),'addtime desc',null,$lstart.',10');
		
		$topicNum = $new['group']->findCount('group_topic',array(
			'isdelete'=>'1',
		));
		
		$pageUrl = pagination($topicNum, 10, $page, $url);

		include template("admin/topic_delete");
	
		break;
		
	//编辑的帖子
	case "edittopic":
	
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=group&ac=admin&mg=topic&ts=edittopic&page=';
		$lstart = $page*10-10;
		
		$arrTopic = $new['group']->findAll('group_topic_edit',null,'addtime desc',null,$lstart.',10');
		
		$topicNum = $new['group']->findCount('group_topic_edit');
		
		$pageUrl = pagination($topicNum, 10, $page, $url);

		include template("admin/topic_edit");
	
		break;
		
	//执行更新帖子
	case "update":
	
		$topicid = intval($_GET['topicid']);
		
		$strTopic = $new['group']->find('group_topic_edit',array(
			'topicid'=>$topicid,
		));
		
		$new['group']->update('group_topic',array(
			'topicid'=>$topicid,
		),array(
			'title'=>$strTopic['title'],
			'content'=>$strTopic['content'],
		));
		
		$new['group']->update('group_topic_edit',array(
			'topicid'=>$topicid,
		),array(
			'isupdate'=>1,
		));
		
		qiMsg('更新成功！');
	
		break;
		
	//查看单独某个修改的帖子
	case "editview":
		$topicid = intval($_GET['topicid']);
		
		$strTopic = $new['group']->find('group_topic_edit',array(
			'topicid'=>$topicid,
		));
		
		include template('admin/topic_edit_view');
		break;
		
}