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

        $userid = aac('user')->isLogin();
		
		$albumid = intval($_POST['albumid']);

		$addtime = intval($_POST['addtime']);

		if($albumid==0){
		    getJson('非法操作1！');
        }

        if($addtime==0){
            getJson('上传时间有误！');
        }

		$strAlbum = $new['photo']->find('photo_album',array(
			'albumid'=>$albumid,
		));

		if($strAlbum==''){
            getJson('非法操作2！');
        }

        if($strAlbum['userid']!=$userid){
            getJson('非法操作3！');
        }
		
		$photoid = $new['photo']->create('photo',array(
			'albumid'=>$strAlbum['albumid'],
			'userid'=>$strAlbum['userid'],
			'locationid'=>aac('user')->getLocationId($strAlbum['userid']),
			'addtime'	=> date('Y-m-d H:i:s',$addtime),
		));
		
		//上传
		$arrUpload = tsUpload($_FILES['file'],$photoid,'photo',array('jpg','gif','png'));
		
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


			#统计相册图片数
            $count_photo = $new['photo']->findCount('photo',array(
                'albumid'=>$albumid,
            ));

            $new['photo']->update('photo_album',array(
                'albumid'=>$albumid,
            ),array(
                'count_photo'=>$count_photo
            ));

			//对积分进行出来
			aac('user')->doScore($GLOBALS['TS_URL']['app'], $GLOBALS['TS_URL']['ac'], $GLOBALS['TS_URL']['ts'],$strAlbum['userid']);
			
		}


		
		#echo $photoid;
        getJson('上传成功！');
	
		break;
	
}