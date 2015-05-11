<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
	
	case "":

		//用户是否登录
		$userid = aac('user')->isLogin();

		$albumid = intval($_GET['albumid']);

		$strAlbum = $new['photo']->find('photo_album',array(
			'albumid'=>$albumid,
		));
		
		$strAlbum['albumname'] = stripslashes($strAlbum['albumname']);
		$strAlbum['albumdesc'] = stripslashes($strAlbum['albumdesc']);

		if($userid != $strAlbum['userid']) {

			tsNotice('非法操作！');

		}

		$addtime = time();

		$title = '上传照片';
		include template("upload");	

		break;
		
	case "do":
		
		$addtime = intval($_POST['addtime']);
		
		$albumid = intval($_POST['albumid']);
		
		$verifyToken = md5('unique_salt' . $addtime);
		
		$strAlbum = $new['photo']->find('photo_album',array(
			'albumid'=>$albumid,
		));
		
		if($albumid==0 || $addtime==0 || $_POST['tokens'] != $verifyToken || $strAlbum==''){
			echo 000000;exit;
		}
		
		$photoid = $new['photo']->create('photo',array(
			'albumid'=>$strAlbum['albumid'],
			'userid'=>$strAlbum['userid'],
			'locationid'=>aac('user')->getLocationId($strAlbum['userid']),
			'addtime'	=> date('Y-m-d H:i:s',$addtime),
		));
		
		//上传
		$arrUpload = tsUpload($_FILES['Filedata'],$photoid,'photo',array('jpg','gif','png'));
		
		if($arrUpload){

			$new['photo']->update('photo',array(
				'photoid'=>$photoid,
			),array(
				'photoname'=>$arrUpload['name'],
				'phototype'=>$arrUpload['type'],
				'path'=>$arrUpload['path'],
				'photourl'=>$arrUpload['url'],
				'photosize'=>$arrUpload['size'],
			));
			
			//对积分进行出来
			aac('user')->doScore($TS_URL['app'], $TS_URL['ac'], $TS_URL['ts'],$strAlbum['userid']);
			
		}
		
		echo $photoid;
	
		break;
	
}