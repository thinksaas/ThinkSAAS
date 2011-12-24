<?php
defined('IN_TS') or die('Access Denied.');
switch($ts){
	case "flash":
		
		$userid = intval($_GET['userid']);
		if($userid=='0'){
			echo '00000';
			exit;
		}
		
		$albumid = intval($_GET['albumid']);
		
		$arrData = array(
			'userid'	=> $userid,
			'albumid'	=> $albumid,
			'addtime'	=> time(),
		);
		
		$photoid = $db->insertArr($arrData,dbprefix.'photo');
		
		if (!empty($_FILES)) {
		
			//存储方式
			//1000个文件一个目录 
			$menu2=intval($photoid/1000);
			$menu1=intval($menu2/1000);
			$menu = $menu1.'/'.$menu2;
			$newdir = $menu;
			
			$dest_dir='uploadfile/photo/'.$newdir;
			createFolders($dest_dir);
			
			$photoname = $_FILES["Filedata"]['name'];
			$phototype = $_FILES['Filedata']['type'];
			$photosize = $_FILES['Filedata']['size'];
			
			$fileInfo=pathinfo($photoname);
			$extension=$fileInfo['extension'];

			$newphotoname = $photoid.'.'.$extension;
				
			$dest=$dest_dir.'/'.$newphotoname;
			move_uploaded_file($_FILES['Filedata']['tmp_name'], mb_convert_encoding($dest,"gb2312","UTF-8"));
			chmod($dest, 0755);
			
			$photourl = $newdir.'/'.$newphotoname;
			
			//继续更新
			$db->query("update ".dbprefix."photo set `photoname`='$photoname',`phototype`='$phototype',`path`='$menu',`photourl`='$photourl',`photosize`='$photosize' where `photoid`='$photoid'");
			
			echo $photoid;
		
		}
		break;
		
	//删除照片
	case "photo_del":
	
		//用户是否登录
		$userid = intval($TS_USER['user']['userid']);
		if($userid == 0){
			header("Location: ".SITE_URL.tsurl('user','login'));
			exit;
		}
		
		aac('user')->isUser($userid);
		
		$photoid = $_GET['photoid'];
		$strPhoto = $db->once_fetch_assoc("select * from ".dbprefix."photo where photoid='$photoid'");
		if($strPhoto['userid']!=$userid) qiMsg("非法操作！");
		
		$albumid = $strPhoto['albumid'];
		
		unlink('uploadfile/photo/'.$strPhoto['photourl']);
		
		$db->query("delete from ".dbprefix."photo where photoid='$photoid'");
		
		$count_photo = $db->once_num_rows("select * from ".dbprefix."photo where albumid='$albumid'");
		
		$db->query("update ".dbprefix."photo_album set `count_photo`='$count_photo' where albumid='$albumid'");
		
		qiMsg("照片删除成功！",'点击返回','index.php?app=photo&ac=album&ts=photo&albumid='.$albumid);
		
		break;
	
	//升级 
	case "up":
		
		/*
		$arrUserId = $db->fetch_all_assoc("select userid,photourl from ".dbprefix."photo group by userid");
		foreach($arrUserId as $item){
			$db->query("insert ".dbprefix."photo_album (`userid`,`albumface`,`albumname`) values ('".$item['userid']."','".$item['photourl']."','默认相册')");
		}
		*/
		$arrAlbumId = $db->fetch_all_assoc("select albumid,userid from ".dbprefix."photo_album");
		foreach($arrAlbumId as $item){
			$db->query("update ".dbprefix."photo set `albumid`='".$item['albumid']."' where userid='".$item['userid']."'");
		}
		
		break;
	
	//添加评论
	case "comment_do":
		$photoid	= intval($_POST['photoid']);
		$content	= h($_POST['content']);
		
		//用户是否登录
		$userid = intval($TS_USER['user']['userid']);
		if($userid == 0){
			header("Location: ".SITE_URL.tsurl('user','login'));
			exit;
		}
		
		aac('user')->isUser($userid);
		
		//标签
		doAction('add_comment','',$content,'');
		
		$arrData	= array(
			'photoid'			=> $photoid,
			'userid'			=> $userid,
			'content'	=> $content,
			'addtime'		=> time(),
		);
		
		$commentid = $db->insertArr($arrData,dbprefix.'photo_comment');
		
		
		//发送系统消息(通知楼主有人回复他的帖子啦)
		$strPhoto = $db->once_fetch_assoc("select * from ".dbprefix."photo where photoid='$photoid'");
		if($strPhoto['userid'] != $userid){
		
			$msg_userid = '0';
			$msg_touserid = $strPhoto['userid'];
			$msg_content = '你有照片新增一条评论，快去看看给个回复吧^_^ <br />'
										.$TS_SITE['base']['site_url'].'index.php?app=photo&ac=show&photoid='.$photoid;
			aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
			
		}
		
		header("Location: ".SITE_URL."index.php?app=photo&ac=show&photoid=".$photoid);

		break;
		
	//删除评论
	case "delcomment":
	
		//用户是否登录
		$userid = intval($TS_USER['user']['userid']);
		if($userid == 0){
			header("Location: ".SITE_URL.tsurl('user','login'));
			exit;
		}
	
		$commentid = $_GET['commentid'];
		
		$strComment = $db->once_fetch_assoc("select photoid,userid from ".dbprefix."photo_comment where `commentid`='$commentid'");
		
		$strPhoto = $db->once_fetch_assoc("select userid from ".dbprefix."photo where `photoid`='".$strComment['photoid']."'");
		
		if($userid == $strPhoto['userid'] || $TS_USER['user']['isadmin']=='1'){
		
			$db->query("delete from ".dbprefix."photo_comment where `commentid`='$commentid'");
			
			qiMsg("删除评论成功！");
			
		}else{
			qiMsg("非法操作！");
		}
		
		
	
		break;
}
