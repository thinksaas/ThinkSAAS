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
		
		$strPhoto['photoname'] = stripslashes($strPhoto['photoname']);
		$strPhoto['photodesc'] = stripslashes($strPhoto['photodesc']);

		if($strPhoto['userid']==$userid || $TS_USER['isadmin']==1){

			$title = '修改图片信息';
			include template('photo_edit');

		}else{


			tsNotice('非法操作!');

		}
	
		break;
		
		
	case "do":
	
		if($_POST['token'] != $_SESSION['token']) {
			tsNotice('非法操作！');
		}
	
		$photoid = intval($_POST['photoid']);
		$photoname = tsClean($_POST['photoname']);
		$photodesc = tsClean($_POST['photodesc']);
		
		$new['photo']->update('photo',array(
			'photoid'=>$photoid,
		),array(
			'photoname'=>$photoname,
			'photodesc'=>$photodesc,
		));
		
		header('Location: '.tsUrl('photo','show',array('id'=>$photoid)));
	
		break;

}