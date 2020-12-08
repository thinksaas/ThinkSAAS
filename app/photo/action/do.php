<?php
defined('IN_TS') or die('Access Denied.');
switch($ts){
		
	//删除照片
	case "photo_del":
	
		//用户是否登录
		$userid = aac('user')->isLogin();
		
		$photoid = tsIntval($_GET['photoid']);
		
		$strPhoto = $new['photo']->find('photo',array(
			'photoid'=>$photoid,
		));
		
		if($strPhoto['userid']==$userid || $TS_USER['isadmin']==1) {
		
		
			$albumid = $strPhoto['albumid'];
			
			
			$new['photo']->deletePhoto($strPhoto);

			
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

}
