<?php
defined('IN_TS') or die('Access Denied.');
switch($ts){
	case "flash":
		
		$userid = intval($_GET['userid']);
		if($userid=='0'){
			echo '00000';
			exit;
		}
		
		$albumid = intval($_GET['albumid']);
		
		$photoid = $new['photo']->create('photo',array(
			'userid'	=> $userid,
			'albumid'	=> $albumid,
			'addtime'	=> date('Y-m-d H:i:s'),
		));
		
		//上传
		$arrUpload = tsUpload($_FILES['Filedata'],$photoid,'photo',array('jpg','gif','png'));
		
		if($arrUpload){

			$new['photo']->update('photo',array(
				'photoid'=>$photoid,
			),array(
				'photoname'=>tsClean($arrUpload['name']),
				'phototype'=>tsClean($arrUpload['type']),
				'path'=>tsClean($arrUpload['path']),
				'photourl'=>tsClean($arrUpload['url']),
				'photosize'=>tsClean($arrUpload['size']),
			));
			
		}
		
		echo $photoid;
		
		break;
		
	//删除照片
	case "photo_del":
	
		//用户是否登录
		$userid = aac('user')->isLogin();
		
		$photoid = intval($_GET['photoid']);
		
		$strPhoto = $new['photo']->find('photo',array(
			'photoid'=>$photoid,
		));
		
		if($strPhoto['userid']==$userid || $TS_USER['user']['isadmin']==1) {
		
		
			$albumid = $strPhoto['albumid'];
			
			unlink('uploadfile/photo/'.$strPhoto['photourl']);
			
			$new['photo']->delete('photo',array(
				'photoid'=>$photoid,
			));
			
			$count_photo = $new['photo']->findCount('photo',array(
				'albumid'=>$albumid,
			));
			
			$new['photo']->update('photo_album',array(
			
				'albumid'=>$albumid,
			
			),array(
			
				'count_photo'=>$count_photo,
			
			));

			tsNotice('照片删除成功！','点击返回',tsUrl('photo','album',array('id'=>$albumid)));
		
		
		}
		
		break;
	
	//添加评论
	case "comment_do":
	
		//用户是否登录
		$userid = aac('user')->isLogin();
	
		$photoid	= intval($_POST['photoid']);
		$content	= tsClean($_POST['content']);
		
		if($TS_USER['user']['isadmin']==0){
			//过滤内容开始
			aac('system')->antiWord($content);
			//过滤内容结束
		}
		
		$commentid = $new['photo']->create('photo_comment',array(
			'photoid'			=> $photoid,
			'userid'			=> $userid,
			'content'	=> $content,
			'addtime'		=> time(),
		));
		
		header("Location: ".tsUrl('photo','show',array('id'=>$photoid)));

		break;
		
	//删除评论
	case "delcomment":
	
		//用户是否登录
		$userid = aac('user')->isLogin();
	
		$commentid = intval($_GET['commentid']);
		
		$strComment = $new['photo']->find('photo_comment',array(
			'commentid'=>$commentid,
		));
		
		$strTopic = $new['photo']->find('photo',array(
		
			'photoid'=>$strComment['photoid'],
		
		));

		
		if($userid == $strPhoto['userid'] || $TS_USER['user']['isadmin']=='1'){
			
			$new['photo']->delete('photo_comment',array(
				'commentid'=>$commentid,
			));
			
			tsNotice("删除评论成功！");
			
		}else{
			tsNotice("非法操作！");
		}
	
		break;
}
