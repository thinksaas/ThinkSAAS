<?php
defined('IN_TS') or die('Access Denied.');

$userid = intval($TS_USER['user']['userid']);
if($userid == 0) header("Location: ".SITE_URL."index.php");
$strUser = aac('user')->isUser($userid);
 
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
		
		$albumid = $_GET['albumid'];
		$addtime = time();
		
		include template("ajax/flash");
		break;
	
	//相册
	case "album":
		$arrAlbum = $db->fetch_all_assoc("select * from ".dbprefix."photo_album where userid='$userid'");
		
		include template("ajax/album");
		
		break;
	//图片 
	case "photo":
		
		$albumid = intval($_GET['albumid']);
		$strAlbum = $db->once_fetch_assoc("select * from ".dbprefix."photo_album where albumid='$albumid'");
		
		$page = isset($_GET['page']) ? $_GET['page'] : '1';
		$url = SITE_URL."index.php?app=photo&ac=ajax&ts=photo&albumid=".$albumid."&page=";
		$lstart = $page*6-6;
		$arrPhoto = $db->fetch_all_assoc("select * from ".dbprefix."photo where albumid='$albumid' order by photoid desc limit $lstart ,6");
		$photoNum = $db->once_num_rows("select * from ".dbprefix."photo where albumid='$albumid'");
		
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
		
		$db->query("insert into ".dbprefix."photo_album (`userid`,`albumname`,`albumdesc`,`addtime`,`uptime`) values ('$userid','$albumname','$albumdesc','$addtime','$uptime')");
		
		$albumid = $db->insert_id();
		
		header("Location: ".SITE_URL."index.php?app=photo&ac=ajax&ts=flash&albumid=".$albumid);
		break;
	
	//
	case "info":
		$albumid = $_GET['albumid'];
		$addtime = $_GET['addtime'];
		
		$strAlbum = $db->once_fetch_assoc("select * from ".dbprefix."photo_album where albumid='$albumid'");
		
		if($strAlbum['userid'] != $userid) qiMsg("非法操作！");
		
		//统计 
		$count_photo = $db->once_num_rows("select * from ".dbprefix."photo where albumid='$albumid'");
		$db->query("update ".dbprefix."photo_album set `count_photo`='$count_photo' where albumid='$albumid'");
		
		//添加相册封面
		if($strAlbum['albumface'] == ''){
			$strPhoto = $db->once_fetch_assoc("select * from ".dbprefix."photo where albumid='$albumid' and userid='$userid' and addtime>'$addtime'");
			$db->query("update ".dbprefix."photo_album set `albumface`='".$strPhoto['photourl']."' where albumid='$albumid'");
		}
		
		$arrPhoto = $db->fetch_all_assoc("select * from ".dbprefix."photo where albumid='$albumid' and  userid='$userid' and addtime>'$addtime'");
		
		include template("ajax/info");
		break;
}