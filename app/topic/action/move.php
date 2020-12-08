<?php 
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();

switch($ts){

	//移动帖子
	case "":
	
		$topicid = tsIntval($_GET['topicid']);

		if($topicid == 0) tsNotice("非法操作！");

		$strTopic = $new['topic']->find('topic',array(
			'topicid'=>$topicid,
		));

		if($strTopic){

			$strGroup = $new['topic']->find('group',array(
				'groupid'=>$strTopic['groupid'],
			));
			
			if($strTopic['userid']==$userid || $strGroup['userid']==$userid || $TS_USER['isadmin']==1){
			

				if($TS_USER['isadmin']==1){
					#如果是系统管理员，就调出所有的群组
					$arrGroup = $new['topic']->findAll('group',array(
						'isaudit'=>0
					),'groupid desc','groupid,groupname');

				}else{
					$arrGroups = $new['topic']->findAll('group_user',array(
						'userid'=>$strTopic['userid'],
					));
					foreach($arrGroups as $item){
						if($item['groupid'] != $strGroup['groupid']){
						
							$arrGroup[] = $new['topic']->find('group',array(
								'groupid'=>$item['groupid'],
							));
						
						}
	
					}
				}


				
				$title = '移动帖子';
				include template("move");
			
			}else{
			
				tsNotice('非法操作！');
			
			}

			
		}else{

			tsNotice('非法操作！');

		}
	
		break;

	//执行移动
	case "do":
	
		$groupid = tsIntval($_POST['groupid']);
		$topicid = tsIntval($_POST['topicid']);
		
		$strTopic = $new['topic']->find('topic',array(
			'topicid'=>$topicid,
		));
		
		if($strTopicid['userid']==$userid || $TS_USER['isadmin']==1){

			$new['topic']->update('topic',array(
				'topicid'=>$topicid,
			),array(
				'groupid'=>$groupid,
				'typeid'=>'0',
			));
			
			
			header("Location: ".tsUrl('topic','show',array('id'=>$topicid)));
		}else{
			tsNotice('非法操作！');
		}
	
		break;

}