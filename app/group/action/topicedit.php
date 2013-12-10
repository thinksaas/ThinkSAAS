<?php
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();

switch($ts){
	
	//编辑帖子
	case "":
		$topicid = intval($_GET['topicid']);

		if($topicid == 0){
			header("Location: ".SITE_URL);
			exit;
		}
		
		$topicNum = $new['group']->findCount('group_topic',array(
			'topicid'=>$topicid,
		));

		if($topicNum==0){
			header("Location: ".SITE_URL);
			exit;
		}
		
		$strTopic = $new['group']->find('group_topic',array(
			'topicid'=>$topicid,
		));
		$strTopic['title'] = htmlspecialchars($strTopic['title']);
		
		//判断是否有修改的内容
		$strTopicEdit =  $new['group']->find('group_topic_edit',array(
			'topicid'=>$topicid,
		));
		
		if($strTopicEdit){
			$strTopic['title']=htmlspecialchars($strTopicEdit['title']);
			$strTopic['content']=$strTopicEdit['content'];
		}
		
		$strGroup = $new['group']->find('group',array(
			'groupid'=>$strTopic['groupid'],
		));
		
		$strGroupUser = $new['group']->find('group_user',array(
			'userid'=>$userid,
			'groupid'=>$strTopic['groupid'],
		));
		
		//print_r($strGroupUser);exit;

		if($strTopic['userid'] == $userid || $strGroup['userid']==$userid || $TS_USER['user']['isadmin']==1 || $strGroupUser['isadmin']==1){
			$arrGroupType = $new['group']->findAll('group_topic_type',array(
				'groupid'=>$strGroup['groupid'],
			));
			
			//找出TAG
			$arrTags = aac('tag')->getObjTagByObjid('topic', 'topicid', $topicid);
			foreach($arrTags as $key=>$item){
				$arrTag[] = $item['tagname'];
			}
			$strTopic['tag'] = array_to_str($arrTag);
			
			$title = '编辑帖子';
			include template("topic_edit");
			
		}else{

			header("Location: ".SITE_URL);
			exit;

		}
		break;
		
	//编辑帖子执行	
	case "do":
	
		if($_POST['token'] != $_SESSION['token']) {
			tsNotice('非法操作！');
		}
		
		$topicid = intval($_POST['topicid']);
		$title = trim($_POST['title']);
		$typeid = intval($_POST['typeid']);
		$content = cleanJs($_POST['content']);
		
		$iscomment = intval($_POST['iscomment']);
		
		if($topicid == '' || $title=='' || $content=='') tsNotice("都不能为空的哦!");
		
		
		if($TS_USER['user']['isadmin']==0){
		
			//过滤内容开始
			aac('system')->antiWord($title);
			aac('system')->antiWord($content);
			//过滤内容结束
		
		}
		
		$strTopic = $new['group']->find('group_topic',array(
			'topicid'=>$topicid,
		));
		
		$strGroup = $new['group']->find('group',array(
			'groupid'=>$strTopic['groupid'],
		));
		
		$strGroupUser = $new['group']->find('group_user',array(
			'userid'=>$userid,
			'groupid'=>$strTopic['groupid'],
		));
		
		if($strTopic['userid']==$userid || $strGroup['userid']==$userid || $TS_USER['user']['isadmin']==1 || $strGroupUser['isadmin']==1){

			$new['group']->update('group_topic',array(
				'topicid'=>$topicid,
			),array(
				'typeid' => $typeid,
				'iscomment' => $iscomment,
			));
			
			//更新编辑
			$new['group']->replace('group_topic_edit',array(
				'topicid'=>$topicid,
			),array(
				'topicid'=>$topicid,
				'title'=>$title,
				'content'=>$content,
				'isupdate'=>'0',
				'addtime'=>date('Y-m-d H:i:s'),
			));
			
			//处理标签
			$tag = trim($_POST['tag']);
			if($tag){
				aac('tag')->delIndextag('topic','topicid',$topicid);
				aac('tag') -> addTag('topic', 'topicid', $topicid, $tag); 
			}
			
			header("Location: ".tsUrl('group','topic',array('id'=>$topicid)));
			
		}else{
			header("Location: ".SITE_URL);
			exit;
		}
		break;

}