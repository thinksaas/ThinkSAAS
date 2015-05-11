<?php 
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();

switch($ts){

	//移动帖子
	case "":
	
		$topicid = intval($_GET['topicid']);

		if($topicid == 0) tsNotice("非法操作！");

		$strTopic = $new['group']->find('group_topic',array(
			'topicid'=>$topicid,
		));

		if($strTopic){

			$strGroup = $new['group']->find('group',array(
				'groupid'=>$strTopic['groupid'],
			));
			
			if($strTopic['userid']==$userid || $strGroup['userid']==$userid || $TS_USER['isadmin']==1){
			
				$arrGroups = $new['group']->findAll('group_user',array(
					'userid'=>$strTopic['userid'],
				));
				foreach($arrGroups as $item){
					if($item['groupid'] != $strGroup['groupid']){
					
						$arrGroup[] = $new['group']->find('group',array(
							'groupid'=>$item['groupid'],
						));
					
					}

				}
				
				$title = '移动帖子';
				include template("topic_move");
			
			}else{
			
				tsNotice('非法操作！');
			
			}

			
		}else{

			tsNotice('非法操作！');

		}
	
		break;

	//执行移动
	case "do":
	
		$groupid = intval($_POST['groupid']);
		$topicid = intval($_POST['topicid']);
		
		$strTopic = $new['group']->find('group_topic',array(
			'topicid'=>$topicid,
		));
		
		if($strTopicid['userid']==$userid || $TS_USER['isadmin']==1){

			$new['group']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'groupid'=>$groupid,
				'typeid'=>'0',
			));
			
			
			header("Location: ".tsUrl('group','topic',array('id'=>$topicid)));
		}else{
			tsNotice('非法操作！');
		}
	
		break;

}