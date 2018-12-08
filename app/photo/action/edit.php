<?php 
defined('IN_TS') or die('Access Denied.');
//修改单个图片信息

$userid = aac('user')->isLogin();

switch($ts){

	case "":
	
		$photoid = intval($_GET['photoid']);

		$strPhoto = $new['photo']->find('photo',array(
			'photoid'=>$photoid,
		));
		
		$strPhoto['photoname'] = tsTitle($strPhoto['photoname']);
		$strPhoto['photodesc'] = tsTitle($strPhoto['photodesc']);

		if($strPhoto['userid']==$userid || $TS_USER['isadmin']==1){

			$title = '修改图片信息';
			include template('photo_edit');

		}else{


			tsNotice('非法操作!');

		}
	
		break;
		
		
	case "do":
	
		$photoid = intval($_POST['photoid']);
		$photoname = trim($_POST['photoname']);
		$photodesc = trim($_POST['photodesc']);
		
		$new['photo']->update('photo',array(
			'photoid'=>$photoid,
		),array(
			'photoname'=>$photoname,
			'photodesc'=>$photodesc,
		));
		
		header('Location: '.tsUrl('photo','show',array('id'=>$photoid)));
	
		break;

}