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
	
	
	case "upload_do":
		$uptypes = array( 
			'image/jpg',
			'image/jpeg',
			'image/png',
			'image/pjpeg',
			'image/gif',
			'image/bmp',
			'image/x-png',
		);
	
		if($_FILES['photo']['name'][0] == '') qiMsg("上传图片不能为空！");
		
		//处理目录存储方式
		$menu = substr($userid,0,1);
		
		$date = date('Ymd');
		
		$dest_dir='uploadfile/photo/'.$menu.'/'.$date.'/'.$userid;
		createFolders($dest_dir);
		
		if(isset($_FILES['photo'])){
			$arrFileName = $_FILES['photo']['name'];
			
			foreach($arrFileName as $key=>$item){
				if($item != ''){
					$phototype = $_FILES['photo']['type'][$key];
					$photosize = $_FILES['photo']['size'][$key];
					if (!in_array($phototype,$uptypes)) {
						qiMsg("只支持图片上传！");
					}
					if($photosize>1024000){
						qiMsg("最大只支持1M的图片！");
					}
				}
			}
			
			foreach($arrFileName as $key=>$item){
				if($item != ''){
				
					$photoname = $item;
					$phototype = $_FILES['photo']['type'][$key];
					$photosize = $_FILES['photo']['size'][$key];
					
					$fileInfo=pathinfo($item);
					$extension=$fileInfo['extension'];
					
					//$dest=$dest_dir.'/'.$item;
					$dest=$dest_dir.'/'.date("YmdHis").mt_rand(10000,99999).'.'.$extension;
					move_uploaded_file($_FILES['photo']['tmp_name'][$key], mb_convert_encoding($dest,"gb2312","UTF-8"));
					chmod($dest, 0755);
					
					$arrData = array(
						'userid'	=> $userid,
						'photoname'	=> $photoname,
						'phototype'	=> $phototype,
						'photourl'		=> $dest,
						'photosize'		=> $photosize,
						'addtime'	=> time(),
					);
					
					$attachid = $db->insertArr($arrData,dbprefix.'photo');
					
				}
				
			}
		}
		
		header("Location: ".SITE_URL."index.php?app=photo&ac=ajax&ts=photo_my");
	
		break;
		
	//通过flash上传的接口 
	case "flash_do":
	
		$albumid = intval($_GET['albumid']);
		
		if (!empty($_FILES)) {
	
			$menu = substr($userid,0,1);
			$date = date('Ymd');
			
			$newdir = $menu.'/'.$date.'/'.$userid;
			
			$dest_dir='uploadfile/photo/'.$newdir;
			createFolders($dest_dir);
			
			$photoname = $_FILES["Filedata"]['name'];
			$phototype = $_FILES['Filedata']['type'];
			$photosize = $_FILES['Filedata']['size'];
			
			$fileInfo=pathinfo($photoname);
			$extension=$fileInfo['extension'];

			$newphotoname = date("YmdHis").mt_rand(10000,99999).'.'.$extension;
				
			$dest=$dest_dir.'/'.$newphotoname;
			move_uploaded_file($_FILES['Filedata']['tmp_name'], mb_convert_encoding($dest,"gb2312","UTF-8"));
			chmod($dest, 0755);
			
			$photourl = $newdir.'/'.$newphotoname;
			
			$arrData = array(
				'userid'	=> $userid,
				'albumid'	=> $albumid,
				'photoname'	=> $photoname,
				'phototype'	=> $phototype,
				'photourl'		=> $photourl,
				'photosize'		=> $photosize,
				'addtime'	=> time(),
			);
			
			$photoid = $db->insertArr($arrData,dbprefix.'photo');
			
			echo $photoid;
		
		}
		
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
