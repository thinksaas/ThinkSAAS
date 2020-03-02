<?php
defined('IN_TS') or die('Access Denied.');
switch($ts){
		
	//删除照片
	case "photo_del":
	
		//用户是否登录
		$userid = aac('user')->isLogin();
		
		$photoid = intval($_GET['photoid']);
		
		$strPhoto = $new['photo']->find('photo',array(
			'photoid'=>$photoid,
		));
		
		if($strPhoto['userid']==$userid || $TS_USER['isadmin']==1) {
		
		
			$albumid = $strPhoto['albumid'];
			
			unlink('uploadfile/photo/'.$strPhoto['photourl']);
			
			$new['photo']->delete('photo',array(
				'photoid'=>$photoid,
			));


			#删除评论
            $new ['photo']->delete ( 'comment', array (
                'ptable'=>'photo',
                'pkey'=>'photoid',
                'pid'=>$photoid,
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

}
