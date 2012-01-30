<?php
defined('IN_TS') or die('Access Denied.');

//设置用户信息

$userid = intval($TS_USER['user']['userid']);

if($userid == '0') header("Location: ".SITE_URL."index.php");

$strUser = $new['user']->getOneUserByUserid($userid);

if($userid != $strUser['userid']) header("Location: ".SITE_URL."index.php");


switch($ts){
	//修改基本信息
	case "base":
		
		$title = L::set_editinfo;
		
		include template("set_base");
		break;
	
	case "base_do":
	
		$username = t($_POST['username']);
		
		$arrData = array(
			'username' => $username,
			'blog'		=> t($_POST['blog']),
			'about'	=> h($_POST['about']),
		);

		if($username == '') tsNotice(L::set_usernameempty);
		
		if(strlen($username) < 4 || strlen($username) > 20) tsNotice(L::set_usernamelength);
		
		if($username != $strUser['username']){
		
			$isUserName = $db->once_fetch_assoc("select count(*) from ".dbprefix."user_info where username='$username'");
			
			if($isUserName['count(*)'] > 0) tsNotice(L::set_usernameregistered);
			
		}

		$db->updateArr($arrData,dbprefix."user_info","where userid='$userid'");

		tsNotice(L::set_success);
		
		break;
	
	//修改头像
	case "face":

		$title = L::set_editface;
		
		include template("set_face");

		break;
		
	case "face_do":

		$uptypes = array( 'jpg','jpeg','png','gif');

		if(isset($_FILES['picfile'])){

			$f=$_FILES['picfile'];

			if($f['name']==''){
			
				tsNotice(L::set_uploadpicempty);
				
			}elseif ($f['name']){
				$arrAttach = explode('.',$f['name']);
				$attachtype = array_pop($arrAttach);
				
				if (!in_array($attachtype,$uptypes)) {
				
					tsNotice(L::set_onlyjpeg);
					
				}
			}
			
			//存储方式
			//1000个文件一个目录 
			$menu2=intval($userid/1000);
			$menu1=intval($menu2/1000);
			$menu = $menu1.'/'.$menu2;
			$newdir = $menu;
			
			$dest_dir='uploadfile/user/'.$newdir;
			createFolders($dest_dir);
			

			//原图
			$fileInfo=pathinfo($f['name']);
			$extension=$fileInfo['extension'];
			
			$newphotoname = $userid.'.'.$extension;
				
			$dest=$dest_dir.'/'.$newphotoname;

			move_uploaded_file($f['tmp_name'],mb_convert_encoding($dest,"gb2312","UTF-8"));
			mkdir($dest, 0777);

			$face = $newdir.'/'.$newphotoname;

			//更新小组头像
			$db->query("update ".dbprefix."user_info set `path`='$menu',`face`='$face' where userid='$userid'");
			
			//清除缓存头像 
			$cache_24 = md10($face).'_24_24.'.$extension;
			$cache_32 = md10($face).'_32_32.'.$extension;
			$cache_48 = md10($face).'_48_48.'.$extension;
			
			unlink('cache/user/'.$menu.'/24/'.$cache_24);
			unlink('cache/user/'.$menu.'/32/'.$cache_32);
			unlink('cache/user/'.$menu.'/48/'.$cache_48);

			tsNotice(L::set_success);

		}
	
		break;
	
	//修改密码
	case "pwd":
	
		$title = L::set_editpwd;
		
		include template("set_pwd");

		break;
		
	case "pwd_do":
	
		$oldpwd = trim($_POST['oldpwd']);
		
		$newpwd = trim($_POST['newpwd']);
		
		if($oldpwd == '' || $newpwd=='') tsNotice(L::set_entryempty);
		
		$userid = $TS_USER['user']['userid'];
		
		$strUser = $db->once_fetch_assoc("select pwd from ".dbprefix."user where userid='$userid'");
		
		if(md5($oldpwd) != $strUser['pwd']) tsNotice(L::set_oldpwderror);
		
		$db->query("update ".dbprefix."user set `pwd`='".md5($newpwd)."' where userid='$userid'");
		
		tsNotice(L::set_success);
	
		break;
	
}