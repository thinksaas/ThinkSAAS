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

		$topicNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."group_topics where `topicid`='$topicid'");

		if($topicNum['count(*)']==0){
			header("Location: ".SITE_URL);
			exit;
		}



		$strTopic = $db->once_fetch_assoc("select * from ".dbprefix."group_topics where `topicid`='".$topicid."'");

		$strGroup = $db->once_fetch_assoc("select * from ".dbprefix."group where `groupid`='".$strTopic['groupid']."'");

		if($strTopic['userid'] == $userid || $strGroup['userid']==$userid || $TS_USER['user']['isadmin']==1){

			$strTopic['content'] = htmlspecialchars($strTopic['content']);

			//帖子类型
			$arrGroupType = $db->fetch_all_assoc("select * from ".dbprefix."group_topics_type where `groupid`='".$strGroup['groupid']."'");

			$title = '编辑帖子';

			include template("topic_edit");
			
		}else{

			header("Location: ".SITE_URL);
			exit;

		}
		break;
		
	//编辑帖子执行	
	case "do":
	
		$topicid = $_POST['topicid'];
		$title = trim($_POST['title']);
		$typeid = empty($_POST['typeid'])?0:$_POST['typeid'];
		$content = trim($_POST['content']);
		
		$iscomment = $_POST['iscomment'];
		
		if($topicid == '' || $title=='' || $content=='') tsNotice("都不能为空的哦!");
		
		//编辑帖子标签
		doAction('group_topic_edit',$title,$content);
		
		$strTopic = $db->once_fetch_assoc("select * from ".dbprefix."group_topics where topicid='".$topicid."'");
		
		$strGroup = $db->once_fetch_assoc("select userid from ".dbprefix."group where groupid='".$strTopic['groupid']."'");
		
		if($strTopic['userid']==$userid || $strGroup['userid']==$userid || $TS_USER['user']['isadmin']==1){
		
			$arrData = array(
				'typeid' => $typeid,
				'title' => $title,
				'content' => $content,
				'iscomment' => $iscomment,
			);

			$new['group']->update('group_topics',array(
				'topicid'=>$topicid,
			),$arrData);
			
			//上传帖子图片开始
			$arrUpload = tsUpload($_FILES['picfile'],$topicid,'topic',array('jpg','gif','png'));
			if($arrUpload){

				$new['group']->update('group_topics',array(
					'topicid'=>$topicid,
				),array(
					'path'=>$arrUpload['path'],
					'photo'=>$arrUpload['url'],
				));
				
				//删除旧数据
				if($strTopic['photo'] != $arrUpload['url']){
					unlink('uploadfile/topic/'.$strTopic['photo']);
				}
				
			}
			//上传帖子图片结束
			
			//上传附件开始
			$attUpload = tsUpload($_FILES['attfile'],$topicid,'topic',array('zip','rar','doc','txt','pdf','ppt','docx','xls','xlsx'));
			if($attUpload){

				$new['group']->update('group_topics',array(
					'topicid'=>$topicid,
				),array(
					'path'=>$attUpload['path'],
					'attach'=>$attUpload['url'],
					'attachname'=>$attUpload['name'],
				));
				
				//删除旧数据
				if($strTopic['attach'] != $attUpload['url']){
					unlink('uploadfile/topic/'.$strTopic['attach']);
				}
				
			}
			//上传附件结束
			
			//处理视频
			$video = trim($_POST['video']);
			$arrVideoType = explode('.',strtolower($video)); //转小写一下
			$videoType = array_pop($arrVideoType);
			if(in_array($videoType,array('swf'))){
				$header = get_headers($video);
				if($header[0] == 'HTTP/1.0 200 OK' || $header[0] == 'HTTP/1.1 200 OK' || $header[0] == 'HTTP/1.0 302 Found' || $header[0] == 'HTTP/1.1 302 Found' || $header[0] == 'HTTP/1.0 302 Moved Temporarily' || $header[0] == 'HTTP/1.1 302 Moved Temporarily'){
					$new['group']->update('group_topics',array(
						'topicid'=>$topicid,
					),array(
						'video'=>$video,
					));
				}
			}

			header("Location: ".SITE_URL.tsUrl('group','topic',array('id'=>$topicid)));
			
		}else{
			header("Location: ".SITE_URL);
			exit;
		}
		break;

}