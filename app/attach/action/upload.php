<?php  
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "":
		$userid = aac('user')->isLogin();
		
		$albumid = intval($_GET['albumid']);
		
		$strAlbum = $new['attach']->find('attach_album',array(
			'albumid'=>$albumid,
		));
		
		if($strAlbum['userid']==$userid || $TS_USER['user']['isadmin']==1){
		
			$title = '上传资料';
			include template('upload');
		
		}else{
		
		
			tsNotice('非法操作！');
		
		}
	
		

		break;
		
	case "do":
		
		$userid = intval($_GET['userid']);
		$albumid = intval($_GET['albumid']);
		if($userid=='0' || $albumid == 0){
			echo '00000';
			exit;
		}
		
		$attachid = $new['attach']->create('attach',array(
			'userid'	=> $userid,
			'albumid'=>$albumid,
			'addtime'	=> date('Y-m-d H:i:s'),
		));
		
		//上传
		$arrUpload = tsUpload($_FILES['Filedata'],$attachid,'attach',array('pptx','docx','pdf','jpg','gif','png','rar','zip','doc','ppt','txt'));
		
		if($arrUpload){

			$new['attach']->update('attach',array(
				'attachid'=>$attachid,
			),array(
				'attachname'=>$arrUpload['name'],
				'attachtype'=>$arrUpload['type'],
				'attachurl'=>$arrUpload['url'],
				'attachsize'=>$arrUpload['size'],
			));
			
			
			//对积分进行处理
			aac('user')->doScore($app,$ac,$ts);
			
			
		}
		
		echo $attachid;
		
		break;

}