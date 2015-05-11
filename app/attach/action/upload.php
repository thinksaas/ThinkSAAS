<?php  
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "":
		$userid = aac('user')->isLogin();
		
		$albumid = intval($_GET['albumid']);
		
		$strAlbum = $new['attach']->find('attach_album',array(
			'albumid'=>$albumid,
		));
		
		$strAlbum['title'] = tsTitle($strAlbum['title']);
		$strAlbum['content'] = tsDecode($strAlbum['content']);
		
		if($strAlbum['userid']==$userid || $TS_USER['isadmin']==1){
		
			$title = '上传资料';
			include template('upload');
		
		}else{
		
		
			tsNotice('非法操作！');
		
		}
	
		

		break;
		
	case "do":
	
		$albumid = intval($_POST['albumid']);
		
		$verifyToken = md5('unique_salt' . $addtime);
		
		$strAlbum = $new['attach']->find('attach_album',array(
			'albumid'=>$albumid,
		));
		
		if($albumid==0 || $_POST['tokens'] != $verifyToken || $strAlbum==''){
			echo 000000;exit;
		}
		
		$attachid = $new['attach']->create('attach',array(
			'albumid'=>$strAlbum['albumid'],
			'userid'=>$strAlbum['userid'],
			'addtime'	=> date('Y-m-d H:i:s'),
		));
		
		//上传
		$arrUpload = tsUpload($_FILES['Filedata'],$attachid,'attach',array('xls','xlsx','pptx','docx','pdf','jpg','gif','png','rar','zip','doc','ppt','txt'));
		
		if($arrUpload){

			$new['attach']->update('attach',array(
				'attachid'=>$attachid,
			),array(
				'attachname'=>$arrUpload['name'],
				'attachtype'=>$arrUpload['type'],
				'attachurl'=>$arrUpload['url'],
				'attachsize'=>$arrUpload['size'],
			));
			
			
			//统计
			$count_attach = $new['attach']->findCount('attach',array(
				'albumid'=>$strAlbum['albumid'],
			));
			
			$new['attach']->update('attach_album',array(
				'albumid'=>$strAlbum['albumid'],
			),array(
				'count_attach'=>$count_attach,
			));
			
			//对积分进行处理
			aac('user')->doScore($TS_URL['app'], $TS_URL['ac'], $TS_URL['ts'],$strAlbum['userid']);
		
			
		}
		
		echo $attachid;
		
		break;

}