<?php 
defined('IN_TS') or die('Access Denied.');
switch($ts){

	//我的相册/用户相册
	case "user":
		$userid = intval($_GET['userid']);
		if($userid == 0) header("Location: ".SITE_URL."index.php");
		$strUser = aac('user')->getUserForApp($userid);
		
		$page = isset($_GET['page']) ? $_GET['page'] : '1';
		
		$url = SITE_URL."index.php?app=photo&ac=album&ts=user&userid=".$userid."&page-";
		
		$lstart = $page*6-6;
		
		$arrAlbum = $db->fetch_all_assoc("select * from ".dbprefix."photo_album where userid='$userid' order by albumid desc limit $lstart,6");
		
		$albumNum = $db->once_num_rows("select * from ".dbprefix."photo_album where userid='$userid'");
		
		$pageUrl = pagination($albumNum, 6, $page, $url);
		
		if($TS_USER['user']['userid'] == $userid){
			$title = '我的相册';
		}else{
			$title = $strUser['username'].'的相册';
		}
		include template("album_user");
		break;
	
	//创建相册
	case "create":
	
		$userid = intval($TS_USER['user']['userid']);
		aac('user')->isUser($userid);
		
		$title = '创建相册';
		include template("album_create");
		break;
	
	//
	case "create_do":
		
		$userid = intval($TS_USER['user']['userid']);
		if($userid == 0) header("Location: ".SITE_URL."index.php");
		
		$albumname = t($_POST['albumname']);
		if($albumname == '') qiMsg("相册名称不能为空！");
		
		$albumdesc = h($_POST['albumdesc']);
		$addtime = time();
		$uptime = time();
		
		$db->query("insert into ".dbprefix."photo_album (`userid`,`albumname`,`albumdesc`,`addtime`,`uptime`) values ('$userid','$albumname','$albumdesc','$addtime','$uptime')");
		
		$albumid = $db->insert_id();
		
		header("Location: ".SITE_URL."index.php?app=photo&ac=upload&albumid=".$albumid);
		
		break;
		
	//相册下图片
	case "photo":
		
		$albumid = intval($_GET['albumid']);
		
		$isalbum = $db->once_num_rows("select * from ".dbprefix."photo_album where albumid = '$albumid'");
		if($albumid == 0) header("Location: ".SITE_URL."index.php");
		
		$page = isset($_GET['page']) ? $_GET['page'] : '1';
		
		$url = SITE_URL."index.php?app=photo&ac=album&ts=photo&albumid=".$albumid."&page=";
		
		$lstart = $page*20-20;
		
		$strAlbum = $db->once_fetch_assoc("select * from ".dbprefix."photo_album where albumid='$albumid'");
		
		$userid = $strAlbum['userid'];
		$strUser = aac('user')->getUserForApp($userid);
		
		$arrPhoto = $db->fetch_all_assoc("select * from ".dbprefix."photo where albumid='$albumid' order by photoid desc limit $lstart,20");
		
		$photoNum = $db->once_num_rows("select * from ".dbprefix."photo where albumid='$albumid'");
		
		$pageUrl = pagination($photoNum, 20, $page, $url);
		
		if($TS_USER['user']['userid'] == $userid){
			$title = '我的相册-'.$strAlbum['albumname'];
		}else{
			$title = $strUser['username'].'的相册-'.$strAlbum['albumname'];
		}
		
		include template("album_photo");
		$db->query("update ".dbprefix."photo_album set `count_view`=count_view+1 where albumid='$albumid'");
		break;
		
	//修改相册
	case "edit":
		$userid = intval($TS_USER['user']['userid']);
		aac('user')->isUser($userid);
		
		$albumid = $_GET['albumid'];
		
		$strAlbum = $db->once_fetch_assoc("select * from ".dbprefix."photo_album where albumid='$albumid'");
		
		if($strAlbum['userid'] != $userid) qiMsg("非法操作！");
		
		$title = '修改相册属性-'.$strAlbum['albumname'];
		include template("album_edit");
		break;
	case "edit_do":
		$albumid = $_POST['albumid'];
		$albumname = t($_POST['albumname']);
		if($albumname == '') qiMsg("相册名称不能为空！");
		
		$albumdesc = h($_POST['albumdesc']);
		
		$db->query("update ".dbprefix."photo_album set `albumname`='$albumname',`albumdesc`='$albumdesc' where `albumid`='$albumid'");
		
		header("Location: ".SITE_URL."index.php?app=photo&ac=album&ts=photo&albumid=".$albumid);
		
		break;
		
	//批量修改
	case "info":
	
		$userid = intval($TS_USER['user']['userid']);
		
		$albumid = $_GET['albumid'];
		$addtime = intval($_GET['addtime']);
		
		$strAlbum = $db->once_fetch_assoc("select * from ".dbprefix."photo_album where albumid='$albumid'");
		
		if($strAlbum['userid'] != $userid) qiMsg("非法操作！");
		
		//统计 
		$count_photo = $db->once_num_rows("select * from ".dbprefix."photo where albumid='$albumid'");
		$db->query("update ".dbprefix."photo_album set `count_photo`='$count_photo' where albumid='$albumid'");
		
		//添加相册封面
		if($strAlbum['albumface'] == ''){
			$strPhoto = $db->once_fetch_assoc("select * from ".dbprefix."photo where albumid='$albumid' and `userid`='$userid' and `addtime`>'$addtime' limit 1");
			
			$db->query("update ".dbprefix."photo_album set `path`='".$strPhoto['path']."',`albumface`='".$strPhoto['photourl']."' where `albumid`='$albumid'");
		}
		
		$arrPhoto = $db->fetch_all_assoc("select * from ".dbprefix."photo where albumid='$albumid' and  userid='$userid' and addtime>'$addtime'");
		
		
		
		//添加动态
		if($addtime > 0){
		
			$num = $db->once_fetch_assoc("select count(*) from ".dbprefix."photo where albumid='$albumid' and  userid='$userid' and addtime>'$addtime'");
		
			//feed开始
			$feed_template = '<span class="pl">上传了{photonum}张照片到<a  href="{albumlink}" target="_blank">{albumname}</a></span><div class="indentrec clearfix">';
			
			foreach($arrPhoto as $key=>$item){
				if($key < 4){
					$feed_template .= '<div class="timeline-album"><a href="{photolink'.$key.'}" title="myself" target="_blank"><img alt="myself" src="{photo'.$key.'}"></a></div>';
				}
			}
			
			$feed_template .= '</div>';
			
			$feed_data = array(
				'photonum' => $num['count(*)'],
				'albumlink'	=> SITE_URL.tsurl('photo','album',array('ts'=>'photo','albumid'=>$strAlbum['albumid'])),
				'albumname'	=> $strAlbum['albumname'],
			);
			
			foreach($arrPhoto as $key=>$item){
				if($key < 4){
					$feed_data['photolink'.$key] = SITE_URL.tsurl('photo','show',array('photoid'=>$item['photoid']));
					$feed_data['photo'.$key] = SITE_URL.miniimg($item['photourl'],'photo',100,100,$item['path']);
				}
			}
			
			aac('feed')->addFeed($userid,$feed_template,serialize($feed_data));
			//feed结束
			
		}
		
		
		$title = '批量修改-'.$strAlbum['albumname'];
		include template("album_info");
		break;
		
	//批量修改执行
	case "info_do":
	
		$albumid = $_POST['albumid'];
		
		$albumface = $_POST['albumface'];
		
		$arrPhotoId = $_POST['photoid'];
		$arrPhotoDesc = $_POST['photodesc'];
		
		foreach($arrPhotoDesc as $key=>$item){
			if($item){
				$photoid = $arrPhotoId[$key];
				$db->query("update ".dbprefix."photo set `photodesc`='".$item."' where photoid='$photoid'");
			}
		}
	
		//更新相册封面
		if($albumface){
			$db->query("update ".dbprefix."photo_album set `albumface`='$albumface' where `albumid`='$albumid'");
		}
		
		header("Location: ".SITE_URL.tsurl('photo','album',array('ts'=>'photo','albumid'=>$albumid)));
		
		break;
	
	//删除相册
	case "del":
		$userid = intval($TS_USER['user']['userid']);
		aac('user')->isUser($userid);
		$albumid = $_GET['albumid'];
		$strAlbum = $db->once_fetch_assoc("select * from ".dbprefix."photo_album where albumid='$albumid'");
		
		if($strAlbum['userid'] != $userid) qiMsg("非法操作！");
		
		$db->query("delete from ".dbprefix."photo_album where albumid='$albumid'");
		$arrPhoto = $db->fetch_all_assoc("select * from ".dbprefix."photo where albumid='$albumid'");
		foreach($arrPhoto as $item){
			unlink('uploadfile/photo/'.$item['photourl']);
		}
		
		$db->query("delete from ".dbprefix."photo where albumid='$albumid'");
		
		header("Location: ".SITE_URL."index.php?app=photo&ac=album&ts=user&userid=".$userid);
		
		break;
}