<?php 
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();

switch($ts){
	
	//添加补贴
	case "":
		
		$topicid = intval($_GET['topicid']);
		
		if($new['group']->isTopic($topicid)){
			$strTopic = $new['group']->find('group_topic',array('topicid'=>$topicid),'userid,topicid,title');
			
			if($strTopic['userid'] == $userid || $TS_USER['user']['isadmin']==1){
			
				$strTopic['title'] = htmlspecialchars($strTopic['title']);
				
				$title = '补贴';
				include template('after_add');
				
			}
			
		}
		
		break;
		
	//执行补贴
	case "ado":
	
		if($_POST['token'] != $_SESSION['token']) {
			tsNotice('非法操作！');
		}
		
		$topicid = intval($_POST['topicid']);
		$content = tsClean($_POST['content']);
		$title = tsClean($_POST['title']);
		
		//过滤内容开始
		aac('system')->antiWord($title);
		aac('system')->antiWord($content);
		//过滤内容结束
		
		$afterid = $new['group']->create('group_topic_add',array(
		
			'topicid'=>$topicid,
			'userid'=>$userid,
			'title'=>$title,
			'content'=>$content,
			'addtime'=>time(),
			'uptime'=>time(),
		
		));
		
		//上传帖子图片开始
		$arrUpload = tsUpload($_FILES['picfile'],$afterid,'after',array('jpg','gif','png'));
		if($arrUpload){

			$new['group']->update('group_topic_add',array(
				'id'=>$afterid,
			),array(
				'path'=>$arrUpload['path'],
				'photo'=>$arrUpload['url'],
			));
		}
		//上传帖子图片结束
		
		//上传附件开始
		$attUpload = tsUpload($_FILES['attfile'],$afterid,'after',array('zip','rar','doc','txt','pdf','ppt','docx','xls','xlsx'));
		if($attUpload){

			$new['group']->update('group_topic_add',array(
				'id'=>$afterid,
			),array(
				'path'=>$attUpload['path'],
				'attach'=>$attUpload['url'],
				'attachname'=>$attUpload['name'],
			));
		}
		//上传附件结束
		
		header("Location: ".tsUrl('group','topic',array('id'=>$topicid)));
		
		break;
	
	//编辑
	case "edit":
	
		$aid = intval($_GET['aid']);
		$strAfter = $new['group']->find('group_topic_add',array(
			'id'=>$aid,
		));
		$strAfter['content'] = $strAfter['content'];
		
		if($strAfter['userid'] == $userid || $TS_USER['user']['isadmin']==1){
		
			$title = '修改补贴';
			include template('after_edit');
		
		}
	
		break;
		
	//编辑执行
	case "edo":
	
		if($_POST['token'] != $_SESSION['token']) {
			tsNotice('非法操作！');
		}
	
		$afterid = intval($_POST['afterid']);
		
		$strAfter = $new['group']->find('group_topic_add',array(
			'id'=>$afterid,
		));
		
		if($strAfter['userid'] == $userid || $TS_USER['user']['isadmin']==1){
		
			$content = tsClean($_POST['content']);
			$title = tsClean($_POST['title']);
			
			//过滤内容开始
			aac('system')->antiWord($title);
			aac('system')->antiWord($content);
			//过滤内容结束
			
			$new['group']->update('group_topic_add',array(
				'id'=>$afterid,
			),array(
				'title'=>$title,
				'content'=>$content,
				'uptime'=>time(),
			));
			
			//上传帖子图片开始
			$arrUpload = tsUpload($_FILES['picfile'],$afterid,'after',array('jpg','gif','png'));
			if($arrUpload){

				$new['group']->update('group_topic_add',array(
					'id'=>$afterid,
				),array(
					'path'=>$arrUpload['path'],
					'photo'=>$arrUpload['url'],
				));
				
				//删除旧数据
				if($strAfter['photo'] != $arrUpload['url']){
					unlink('uploadfile/after/'.$strAfter['photo']);
				}
				
			}
			//上传帖子图片结束
			
			//上传附件开始
			$attUpload = tsUpload($_FILES['attfile'],$afterid,'after',array('zip','rar','doc','txt','pdf','ppt','docx','xls','xlsx'));
			if($attUpload){

				$new['group']->update('group_topic_add',array(
					'id'=>$afterid,
				),array(
					'path'=>$attUpload['path'],
					'attach'=>$attUpload['url'],
					'attachname'=>$attUpload['name'],
				));
				
				//删除旧数据
				if($strAfter['attach'] != $attUpload['url']){
					unlink('uploadfile/after/'.$strAfter['attach']);
				}
				
			}
			//上传附件结束
			
			header("Location: ".tsUrl('group','topic',array('id'=>$strAfter['topicid'])));
		
		}
		
		break;
	
	//删除
	case "delete":
		
		$id = intval($_GET['aid']);
		$strAfter = $new['group']->find('group_topic_add',array(
			'id'=>$id,
		));
		
		if($strAfter['userid'] == $userid || $TS_USER['user']['isadmin'] == 1){
			$new['group']->delAfter($id);
		}
		
		header("Location: ".tsUrl('group','topic',array('id'=>$strAfter['topicid'])));
		
		break;
		
	//上移
	case "up":
		$id = intval($_GET['afterid']);
		$strAfter = $new['group']->find('group_topic_add',array(
			'id'=>$id,
		));
		
		//重新排序
		if($strAfter['orderid']==0){
			$arrAfters = $new['group']->findAll('group_topic_add',array(
				'topicid'=>$strAfter['topicid'],
			));
			
			foreach($arrAfters as $key=>$item){
				$new['group']->update('group_topic_add',array(
					'id'=>$item['id'],
				),array(
					'orderid'=>$key,
				));
			}
			
		}
		
		
		header('Location: '.tsUrl('group','topic',array('id'=>$strAfter['topicid'])));
		
		break;
	
	//下移
	case "down":
		$id = intval($_GET['afterid']);
		$strAfter = $new['group']->find('group_topic_add',array(
			'id'=>$id,
		));
		
		//重新排序
		if($strAfter['orderid']==0){
			$arrAfters = $new['group']->findAll('group_topic_add',array(
				'topicid'=>$strAfter['topicid'],
			));
			
			foreach($arrAfters as $key=>$item){
				$new['group']->update('group_topic_add',array(
					'id'=>$item['id'],
				),array(
					'orderid'=>$key,
				));
			}
			
		}
		
		header('Location: '.tsUrl('group','topic',array('id'=>$strAfter['topicid'])));
		
		break;
}