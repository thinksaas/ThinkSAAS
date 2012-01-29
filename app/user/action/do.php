<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	
	case "setface":
		$userid = intval($TS_USER['user']['userid']);
		if($userid == '0') tsNotice("非法操作！");

		$uptypes = array( 'jpg','jpeg','png','gif');

		if(isset($_FILES['picfile'])){

			$f=$_FILES['picfile'];

			if($f['name']==''){
				tsNotice("上传图片不能为空！");
			}elseif ($f['name']){
				$arrAttach = explode('.',$f['name']);
				$attachtype = array_pop($arrAttach);
				
				if (!in_array($attachtype,$uptypes)) {
					tsNotice("仅支持 jpg,gif,png 格式的图片！");
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

			tsNotice("头像修改成功！请返回ctrl+f5强制刷新下！");

		}

		break;
		
	//验证Email是否唯一
	case "inemail":
	
		$email = $_GET['email'];
		$emailNum = $db->once_num_rows("select * from ".dbprefix."user where `email`='".$email."'");
		
		if($emailNum > '0'):
			echo 'false';
		else:
			echo 'true';
		endif;
		
		break;
	
	//验证用户名是否唯一
	case "isusername":
		$username = $_GET['username'];
		$usernameNum = $db->once_num_rows("select * from ".dbprefix."user_info where `username`='".$username."'");
		
		if($usernameNum > '0'):
			echo 'false';
		else:
			echo 'true';
		endif;
		break;
		
	//验证邀请码是否使用
	case "isinvitecode":
		
		$invitecode = trim($_GET['invitecode']);
		
		$codeNum = $db->once_num_rows("select * from ".dbprefix."user_invites where invitecode='$invitecode' and isused='0'");
		
		if($codeNum > 0){
			echo 'true';
		}else{
			echo 'false';
		}
		
		break;
		
	//修改用户密码 
	case "setpwd":
		
		$oldpwd = trim($_POST['oldpwd']);
		$newpwd = trim($_POST['newpwd']);
		$renewpwd = trim($_POST['renewpwd']);
		
		if($oldpwd == '' || $newpwd=='' || $renewpwd=='') tsNotice("所有项都不能为空！");
		
		$userid = $TS_USER['user']['userid'];
		$strUser = $db->once_fetch_assoc("select pwd from ".dbprefix."user where userid='$userid'");
		
		if(md5($oldpwd) != $strUser['pwd']) tsNotice("旧密码输入有误！");
		
		if($newpwd != $renewpwd) tsNotice("两次输入新密码密码不一样！");
		
		$db->query("update ".dbprefix."user set `pwd`='".md5($newpwd)."' where userid='$userid'");
		
		tsNotice("密码修改成功！");
		
		break;
}
