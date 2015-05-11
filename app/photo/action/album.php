<?php 
defined('IN_TS') or die('Access Denied.');
switch($ts){

	//某一个相册
	case "":
		$albumid = intval($_GET['id']);
		$strAlbum = $new['photo']->find('photo_album',array(
			'albumid'=>$albumid,
		));
		//404
		if($strAlbum==''){
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			$title = '404';
			include pubTemplate("404");
			exit;
		}
		
		if($strAlbum['isaudit']==1){
			tsNotice('内容审核中...');
		}
		
		$strAlbum['albumname'] = tsTitle($strAlbum['albumname']);
		$strAlbum['albumdesc'] = tsDecode($strAlbum['albumdesc']);
		
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
		
		$url = tsUrl('photo','album',array('id'=>$albumid,'page'=>''));
		
		$lstart = $page*20-20;
		
		$strUser = aac('user')->getOneUser($strAlbum['userid']);
		
		$arrPhoto = $new['photo']->findAll('photo',array(
			'albumid'=>$albumid,
		),'photoid desc',null,$lstart.',20');
		
		foreach($arrPhoto as $key=>$item){
			$arrPhoto[$key]['photodesc'] = stripslashes($item['photodesc']);
		}
		
		$photoNum = $new['photo']->findCount('photo',array(
			'albumid'=>$albumid,
		));
		
		$pageUrl = pagination($photoNum, 20, $page, $url);
		
		if($TS_USER['userid'] == $strAlbum['userid']){
			$title = '我的相册-'.$strAlbum['albumname'];
		}else{
			$title = $strUser['username'].'的相册-'.$strAlbum['albumname'];
		}
		
		include template("album");
		
		$new['photo']->update('photo_album',array(
			'albumid'=>$strAlbum['albumid'],
		),array(
			'count_view'=>$strAlbum['count_view']+1,
		));
		
		
		break;

	//我的相册/用户相册
	case "user":
		$userid = intval($_GET['userid']);
		
		if($userid == 0) header("Location: ".SITE_URL."index.php");
		
		$strUser = aac('user')->getOneUser($userid);
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
		
		$url = tsUrl('photo','album',array('ts'=>'user','userid'=>$userid,'page'=>''));
		
		$lstart = $page*6-6;
		
		$arrAlbum = $new['photo']->findAll('photo_album',array(
			'userid'=>$userid,
		),'albumid desc',null,$lstart.',6');
		
		foreach($arrAlbum as $key=>$item){
			$arrAlbum[$key]['albumname'] = stripslashes($item['albumname']);
			$arrAlbum[$key]['albumdesc'] = stripslashes($item['albumdesc']);
		}
		
		$albumNum = $new['photo']->findCount('photo_album',array(
		
			'userid'=>$userid,
		
		));
		
		$pageUrl = pagination($albumNum, 6, $page, $url);
		
		if($TS_USER['userid'] == $userid){
			$title = '我的相册';
		}else{
			$title = $strUser['username'].'的相册';
		}
		include template("album_user");
		break;

		
	//修改相册
	case "edit":
	
		//用户是否登录
		$userid = aac('user')->isLogin();
		
		$albumid = intval($_GET['albumid']);
		
		$strAlbum = $new['photo']->find('photo_album',array(
			'albumid'=>$albumid,
		));
		
		$strAlbum['albumname'] = stripslashes($strAlbum['albumname']);
		$strAlbum['albumdesc'] = stripslashes($strAlbum['albumdesc']);
		
		if($strAlbum['userid'] == $userid || $TS_USER['isadmin']==1) {
		
			$title = '修改相册属性-'.$strAlbum['albumname'];
			include template("album_edit");
		
		}else{
		
			tsNotice('非法操作！');
		
		}
		
		break;
	
	case "edit_do":
	
		if($_POST['token'] != $_SESSION['token']) {
			tsNotice('非法操作！');
		}
	
		//用户是否登录
		$userid = aac('user')->isLogin();
	
		$albumid = intval($_POST['albumid']);
		
		$strAlbum = $new['photo']->find('photo_album',array(
			'albumid'=>$albumid,
		));
		
		if($strAlbum['userid']==$userid || $TS_USER['isadmin']==1){
		
			$albumname = tsClean($_POST['albumname']);
			if($albumname == '') qiMsg("相册名称不能为空！");
			
			$albumdesc = tsClean($_POST['albumdesc']);
			
			
			if($TS_USER['isadmin']==0){
				//过滤内容开始
				aac('system')->antiWord($albumname);
				aac('system')->antiWord($albumdesc);
				//过滤内容结束
			}
			
			$new['photo']->update('photo_album',array(
				'userid'=>$strAlbum['userid'],
				'albumid'=>$strAlbum['albumid'],
			),array(
				'albumname'=>$albumname,
				'albumdesc'=>$albumdesc,
			));

			header("Location: ".tsUrl('photo','album',array('id'=>$albumid)));
		}else{
			tsNotice('非法操作！');
		}
		break;
		
	//批量修改
	case "info":
	
		//用户是否登录
		$userid = aac('user')->isLogin();
		
		$albumid = intval($_GET['albumid']);
		$addtime = intval($_GET['addtime']);
		
		$strAlbum = $new['photo']->find('photo_album',array(
			'albumid'=>$albumid,
		));
		
		$strAlbum['albumname'] = stripslashes($strAlbum['albumname']);
		$strAlbum['albumdesc'] = stripslashes($strAlbum['albumdesc']);
		
		if($strAlbum['userid'] != $userid) {
		
			tsNotice('非法操作');
		
		}
		
		//统计 
		$count_photo = $new['photo']->findCount('photo',array(
			'albumid'=>$albumid,
		));
		
		$new['photo']->update('photo_album',array(
			'albumid'=>$albumid,
		),array(
			'count_photo'=>$count_photo,
		));
		
		//添加相册封面
		if($strAlbum['albumface'] == ''){
			$strPhoto = $new['photo']->find('photo',array(
				'albumid'=>$strAlbum['albumid'],
			));
			
			$new['photo']->update('photo_album',array(
				'albumid'=>$albumid,
			),array(
				'path'=>$strPhoto['path'],
				'albumface'=>$strPhoto['photourl'],
			));
		}
		
		if($addtime){
			$arr = array(
				'albumid'=>$albumid,
				'addtime'=>date('Y-m-d H:i:s',$addtime),
			);
		}else{
			$arr = array(
				'albumid'=>$albumid,
			);
		}
		
		$arrPhoto = $new['photo']->findAll('photo',$arr);
		
		foreach($arrPhoto as $key=>$item){
			$arrPhoto[$key]['photodesc'] = stripslashes($item['photodesc']);
		}
		
		
		$title = '批量修改-'.$strAlbum['albumname'];
		include template("album_info");
		break;
		
	//批量修改执行
	case "info_do":
	
		//用户是否登录
		$userid = aac('user')->isLogin();
	
		$albumid = intval($_POST['albumid']);
		
		$albumface = trim($_POST['albumface']);
		
		$arrPhotoId = $_POST['photoid'];
		$arrPhotoDesc = $_POST['photodesc'];
		
		if($TS_USER['isadmin']==0){
		
			foreach($arrPhotoDesc as $key=>$item){
			
				//过滤内容开始
				aac('system')->antiWord($item);
				//过滤内容结束
			
			}

		}
		
		foreach($arrPhotoDesc as $key=>$item){
			if($item){
				$photoid = intval($arrPhotoId[$key]);
				
				$new['photo']->update('photo',array(
					'photoid'=>$photoid,
				),array(
				
					'photodesc'=>tsClean($item),
				
				));
			
			}
		}
	
		//更新相册封面
		if($albumface){
			$new['photo']->update('photo_album',array(
				'userid'=>$userid,
				'albumid'=>$albumid,
			),array(
				'albumface'=>$albumface,
			));
		}
		
		header("Location: ".tsUrl('photo','album',array('id'=>$albumid)));
		
		break;
	
	//删除相册
	case "del":

		//用户是否登录
		$userid = aac('user')->isLogin();
	
		$albumid = intval($_GET['albumid']);
		
		$strAlbum = $new['photo']->find('photo_album',array(
			'albumid'=>$albumid,
		));
		
		if($strAlbum['userid'] == $userid || $TS_USER['isadmin'] == 1) {
		
			$new['photo']->deletePhotoAlbum($strAlbum['albumid']);
		
		}

		
		header("Location: ".tsUrl('photo'));
		
		break;
}