<?php 
defined('IN_TS') or die('Access Denied.');

$userid = intval($TS_USER['userid']);

if($userid==0){
	return false;
	exit;
}
 
switch($ts){

	//普通上传
	case "upload":
		
		//包含模版
		include template("ajax/upload");
		
		break;
		
	//
	case "net":
		include template("ajax/net");
		break;
		
	//Flash上传
	case "flash":
		
		$albumid = intval($_GET['albumid']);
		$addtime = time();
		
		include template("ajax/flash");
		break;
	
	//相册
	case "album":
		
		$isAlbum = $new['photo']->findCount('photo_album',array(
		
			'userid'=>$userid,
		
		));
		
		if($isAlbum == 0){
		
			$new['photo']->create('photo_album',array(
			
				'userid'=>$userid,
				'albumname'=>'默认相册',
				'albumdesc'=>'默认相册',
				'addtime'=>time(),
				'uptime'=>time(),
			
			
			));
		
		}
		
		$arrAlbum = $new['photo']->findAll('photo_album',array(
			'userid'=>$userid,
		));

		
		include template("ajax/album");
		
		break;
	//图片 
	case "photo":
		
		$albumid = intval($_GET['albumid']);
		$strAlbum = $new['photo']->find('photo_album',array(
			'albumid'=>$albumid,
		));
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
		$url = SITE_URL."index.php?app=photo&ac=ajax&ts=photo&albumid=".$albumid."&page=";
		$lstart = $page*6-6;
		
		$arrPhoto = $new['photo']->findAll('photo',array(
			'albumid'=>$albumid,
		),'photoid desc',null,$lstart.',6');
		
		$photoNum = $new['photo']->findCount('photo',array(
			'albumid'=>$albumid,
		));
		
		$pageUrl = pagination($photoNum, 6, $page, $url);
		
		include template("ajax/photo");
		break;
	//创建相册 
	case "create":
		include template("ajax/create");
		break;
		
	case "create_do":
		$albumname = t($_POST['albumname']);
		
		if($albumname == '') qiMsg("相册名称不能为空！");
		
		$albumdesc = h($_POST['albumdesc']);
		$addtime = time();
		$uptime = time();
		
		$albumid = $new['photo']->create('photo_album',array(
		
			'userid'=>$userid,
			'albumname'=>$albumname,
			'albumdesc'=>$albumdesc,
			'addtime'=>time(),
			'uptime'=>time(),
			
		));
		
		header("Location: ".SITE_URL."index.php?app=photo&ac=ajax&ts=flash&albumid=".$albumid);
		break;
	
	//
	case "info":
		$albumid = intval($_GET['albumid']);
		$addtime = intval($_GET['addtime']);
		
		$strAlbum = $new['photo']->find('photo_album',array(
			'albumid'=>$albumid,
		));
		
		if($strAlbum['userid'] != $userid) qiMsg("非法操作！");
		
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
			
			$strPhoto = $new['photo']->find('photo',"`albumid`='$albumid' and `userid`='$userid' and `addtime`>'$addtime'");
			
			$new['photo']->update('photo_album',array(
				'albumid'=>$albumid,
			),array(
				'albumface'=>$strPhoto['photourl'],
			));
			
		}
		
		$arrPhoto = $new['photo']->findAll('photo',"`albumid`='$albumid' and  `userid`='$userid' and `addtime`>'$addtime'");
		
		include template("ajax/info");
		break;
}