<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	
	case "setface":
		$userid = intval($TS_USER['user']['userid']);
		if($userid == '0') qiMsg("非法操作！");

		$uptypes = array( 
			'image/jpg',
			'image/jpeg',
			'image/png',
			'image/pjpeg',
			'image/gif',
			'image/x-png',
		);

		if(isset($_FILES['picfile'])){

			$f=$_FILES['picfile'];

			if($f['name']==''){
				qiMsg("上传图片不能为空！");
			}elseif ($f['name']){
				if (!in_array($_FILES['picfile']['type'],$uptypes)) {
					qiMsg("仅支持 jpg,gif,png 格式的图片！");
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

			qiMsg("头像修改成功！请返回ctrl+f5强制刷新下！");

		}

		break;

	case "setbase":
	
		$userid = $TS_USER['user']['userid'];
		$strUser = $db->once_fetch_assoc("select username from ".dbprefix."user_info where userid='$userid'");
		$username = t($_POST['username']);
		
		$arrData = array(
			'username' => $username,
			'sex'	=> $_POST['sex'],
			'signed'		=> h($_POST['signed']),
			'phone'	=> t($_POST['phone']),
			'blog'			=> t($_POST['blog']),
			'about'			=> h($_POST['about']),
		);

		if($TS_USER['user'] == '') qiMsg("机房重地，闲人免进！");
		if($username == '') qiMsg("不管做什么都需要有一个名号吧^_^");
		if(strlen($username) < 4 || strlen($username) > 20) qiMsg("用户名长度必须在4到20字符之间!");
		
		if($username != $strUser['username']){
			$isusername = $db->once_num_rows("select * from ".dbprefix."user_info where username='$username'");
			if($isusername > 0) qiMsg("用户名已经存在，请换个用户名！");
		}

		$db->updateArr($arrData,dbprefix."user_info","where userid='$userid'");

		qiMsg("基本资料更新成功！");

		break;
	//修改常居地
	case "setcity":

		$userid = intval($TS_USER['user']['userid']);
	
		if($userid==0) header("Location: ".SITE_URL."index.php");
		
		$oneid = intval($_POST['oneid']);
		$twoid = intval($_POST['twoid']);
		$threeid = intval($_POST['threeid']);
		
		if($oneid != 0 && $twoid==0 && $threeid==0){
			$areaid = $oneid;
		}elseif($oneid!=0 && $twoid !=0 && $threeid==0){
			$areaid = $twoid;
		}elseif($oneid!=0 && $twoid !=0 && $threeid!=0){
			$areaid = $threeid;
		}else{
			$areaid = 0;
		}

		$db->query("update ".dbprefix."user_info set `areaid`='$areaid' where userid='$userid'");
		
		$_SESSION['tsuser']['areaid'] = $areaid;

		qiMsg("常居地更新成功！");
		
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
		
		if($oldpwd == '' || $newpwd=='' || $renewpwd=='') qiMsg("所有项都不能为空！");
		
		$userid = $TS_USER['user']['userid'];
		$strUser = $db->once_fetch_assoc("select pwd from ".dbprefix."user where userid='$userid'");
		
		if(md5($oldpwd) != $strUser['pwd']) qiMsg("旧密码输入有误！");
		
		if($newpwd != $renewpwd) qiMsg("两次输入新密码密码不一样！");
		
		$db->query("update ".dbprefix."user set `pwd`='".md5($newpwd)."' where userid='$userid'");
		
		qiMsg("密码修改成功！");
		
		break;
	
	//选择常住城市
	case "city":
		$provinceid = $_GET['provinceid'];
		//$arrCity = $db->fetch_all_assoc("select * from ".dbprefix."app_location_city where provinceid = '$provinceid' and isshow='0'");
		$arrCitys = AppCacheRead('location','city.php');
		
		$arrCity = $arrCitys[$provinceid];
		
		include template("city");
		
		break;
	//选择区县
	case "area":
		$cityid = $_GET['cityid'];
		//$arrArea = $db->fetch_all_assoc("select * from ".dbprefix."app_location_area where cityid = '$cityid' and isshow='0'");
		$arrAreas = AppCacheRead('location','area.php');
		$arrArea = $arrAreas[$cityid];
	
		include template("area");
	
		break;

	
	//用户跟随
	case "user_follow":
		
		//用户是否登录
		$userid = intval($TS_USER['user']['userid']);
		if($userid == 0){
			header("Location: ".SITE_URL.tsurl('user','login'));
			exit;
		}
		
		
		$userid_follow = intval($_GET['userid_follow']);

		if($userid_follow==0){
			header("Location: ".SITE_URL);
			exit;
		}
		
		$new['user']->isUser($userid_follow);
		
		$followNum = $db->once_num_rows("select * from ".dbprefix."user_follow where userid='$userid' and userid_follow='$userid_follow'");
		
		if($followNum > '0'){
			
			qiMsg("请不要重复关注同一用户！");
			
		}else{
			
			$db->query("insert into ".dbprefix."user_follow (`userid`,`userid_follow`,`addtime`) values ('$userid','$userid_follow','".time()."')");
			
			//统计更新跟随和被跟随数
			//统计自己的
			$count_follow = $db->once_num_rows("select * from ".dbprefix."user_follow where userid='$userid'");
			$count_followed = $db->once_num_rows("select * from ".dbprefix."user_follow where userid_follow='$userid'");
			
			$db->query("update ".dbprefix."user_info set `count_follow`='$count_follow',`count_followed`='$count_followed' where userid='$userid'");
			
			//统计别人的
			$count_follow_userid = $db->once_num_rows("select * from ".dbprefix."user_follow where userid='$userid_follow'");
			$count_followed_userid = $db->once_num_rows("select * from ".dbprefix."user_follow where userid_follow='$userid_follow'");
			
			$db->query("update ".dbprefix."user_info set `count_follow`='$count_follow_userid',`count_followed`='$count_followed_userid' where userid='$userid_follow'");
			
			//发送系统消息
			$msg_userid = '0';
			$msg_touserid = $userid_follow;
			$msg_content = '恭喜，您被人跟随啦！看看他是谁吧<br />'.SITE_URL.'index.php?app=user&ac=space&userid='.$userid;
			aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
			
			$strUser = $db->once_fetch_assoc("select userid,username,path,face from ".dbprefix."user_info where `userid`='$userid_follow'");
			
			//feed开始
			$feed_template = '<a href="{link}"><img title="{username}" alt="{username}" src="{face}" class="broadimg"></a>
			<span class="pl">关注<a href="{link}">{username}</a></span>';
			
			$feed_data = array(
				'link'	=> SITE_URL.tsurl('user','space',array('userid'=>$userid_follow)),
				'username'	=> $strUser['username'],
			);
			
			if($strUser['face']!=''){
				$feed_data['face'] = SITE_URL.miniimg($strUser['face'],'user',48,48,$strUser['path']);
			}else{
				$feed_data['face'] = SITE_URL.'public/images/noavatar.gif';
			}
			
			aac('feed')->addFeed($userid,$feed_template,serialize($feed_data));
			//feed结束
			
			
			
			header("Location: ".SITE_URL.tsurl('user','space',array('userid'=>$userid_follow)));
			
		}
		
		break;
	
	//用户取消跟随 
	case "user_nofollow":
		
		//用户是否登录
		$userid = intval($TS_USER['user']['userid']);
		if($userid == 0){
			header("Location: ".SITE_URL.tsurl('user','login'));
			exit;
		}
		
		$userid_follow = $_GET['userid_follow'];
		
		$db->query("DELETE FROM ".dbprefix."user_follow WHERE userid = '$userid' AND userid_follow = '$userid_follow'");
		
		//统计更新跟随和被跟随数
		//统计自己的
		$count_follow = $db->once_num_rows("select * from ".dbprefix."user_follow where userid='$userid'");
		$count_followed = $db->once_num_rows("select * from ".dbprefix."user_follow where userid_follow='$userid'");
		
		$db->query("update ".dbprefix."user_info set `count_follow`='$count_follow',`count_followed`='$count_followed' where userid='$userid'");
		
		//统计别人的
		$count_follow_userid = $db->once_num_rows("select * from ".dbprefix."user_follow where userid='$userid_follow'");
		$count_followed_userid = $db->once_num_rows("select * from ".dbprefix."user_follow where userid_follow='$userid_follow'");
		
		$db->query("update ".dbprefix."user_info set `count_follow`='$count_follow_userid',`count_followed`='$count_followed_userid' where userid='$userid_follow'");
		
		header("Location: ".SITE_URL.tsurl('user','space',array('userid'=>$userid_follow)));
		
		break;
}
