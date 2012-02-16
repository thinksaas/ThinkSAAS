<?php 
//执行登录
$username = t($_POST['username']);
$email = trim($_POST['email']);
$face = $_POST['face'];

if($username=='' || $email==''){
	qiMsg("用户名和Email都不能为空！");
}elseif(valid_email($email) == false){
	qiMsg("Email书写不正确！");
}else{
	$isemail = $db->findCount("select * from ".dbprefix."user where email='$email'");
	$isusername = $db->findCount("select * from ".dbprefix."user_info where username='$username'");
	if($isemail > 0){
		
		//如果Email存在就跳转到验证密码页面
		header("Location: ".SITE_URL."index.php?app=pubs&ac=plugin&plugin=qq&in=email&email=".$email);
		
	}elseif($isusername > 0){
		qiMsg("用户名已经存在，请换个用户名！");
	}else{
	
		$pwd = random(5,0);
		$salt = md5(rand());
		
		$userid = $db->create('user',array(
			'pwd'=>md5($salt.$pwd),
			'salt'=>$salt,
			'email'=>$email,
		));
		
		//插入用户信息
		$db->create('user_info',array(
			'userid'			=> $userid,
			'username' 	=> $username,
			'email'		=> $email,
			'ip'			=> getIp(),
			'qq_openid' => $_SESSION['openid'],
			'qq_token'	=> $_SESSION['token'],
			'qq_secret'	=> $_SESSION['secret'],
			'addtime'	=> time(),
			'uptime'	=> time(),
		));
		
		//更新用户头像
		if($face){
			//1000个图片一个目录
			$menu2=intval($userid/1000);
			$menu1=intval($menu2/1000);
			$menu = $menu1.'/'.$menu2;
			$photo = $userid.'.jpg';
			
			$photos = $menu.'/'.$photo;
			
			$dir = 'uploadfile/user/'.$menu;
			
			$dfile = $dir.'/'.$photo;
			
			createFolders($dir);
			
			if(!is_file($dfile)){
				$img = file_get_contents($face); 
				file_put_contents($dfile,$img); 
			};
			
			$db->query("update ".dbprefix."user_info set `path`='$menu',`face`='$photos' where `userid`='$userid'");
			
		}
		
		
		//默认加入小组
		$isgroup = $db->find("select optionvalue from ".dbprefix."user_options where optionname='isgroup'");
		
		if($isgroup['optionvalue'] != ''){
			$arrGroup = explode(',',$isgroup['optionvalue']);
			foreach($arrGroup as $item){
				$groupusernum = $db->findCount("select * from ".dbprefix."group_users where `userid`='".$userid."' and `groupid`='".$item."'");
				if($groupusernum == '0'){
					
					$db->create('group_users',array(
						'userid'=>$userid,
						'groupid'=>$item,
						'addtime'=>time(),
					));
					
					//统计更新
					$count_user = $db->findCount("select * from ".dbprefix."group_users where groupid='".$item."'");
					$db->query("update ".dbprefix."group set `count_user`='".$count_user."' where groupid='".$item."'");
				}
			}
		}
		
		//获取用户信息
		$userData = $db->find("select * from ".dbprefix."user_info where userid='$userid'");
		
		//发送系统消息(恭喜注册成功)
		$msg_userid = '0';
		$msg_touserid = $userid;
		$msg_content = '亲爱的 '.$username.' ：<br />您成功加入了 '
									.$TS_SITE['base']['site_title'].'<br />在遵守本站的规定的同时，享受您的愉快之旅吧!<br />你除了能用QQ登录外，还可以用Email登录本站<br />登录Email：'.$email.'<br />登录密码：'.$pwd;
		aac('message',$db)->sendmsg($msg_userid,$msg_touserid,$msg_content);
		
		$_SESSION['tsuser']	= $userData;
	
		header("Location: ".SITE_URL."index.php");
	}
	
}