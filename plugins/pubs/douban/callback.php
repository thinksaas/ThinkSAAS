<?php
defined('IN_TS') or die('Access Denied.');
require('DoubanOAuth.php');
require('config.php');

$douban = new DoubanOAuth(array(
'key' => KEY,
'secret' => SECRET,
'redirect_url' => REDIRECT,
));

$result = $douban->getAccessToken($_GET['code']);

//print_r($result);exit;

$access_token = $result['access_token'];
$openid = $result['douban_user_id'];


//cookie10天
setcookie("douban_access_token",$access_token, time()+3600*24*10);
setcookie("douban_openid",$openid, time()+3600*24*10);

/*ThinkSAAS开始*/
if($openid){
	$strOpen = $new['pubs']->find('user_open',array(
		'sitename'=>'douban',
		'openid'=>$openid,
	));
	
	//10天更换一次access_token
	if(time()-10*86400>$strOpen['uptime']){
		$new['pubs']->update('user_open',array(
			'sitename'=>'douban',
			'openid'=>$openid,
		),array(
			'access_token'=>$access_token,
			'uptime'=>time(),
		));
	}

	if($strOpen['userid']){
		
		$userData = $new['pubs']->find('user_info',array(
			'userid'=>$strOpen['userid'],
		),'userid,username,path,face,isadmin,signin,uptime');
		
		//更新登录时间
		$new['pubs']->update('user_info',array(
			'userid'=>$strOpen['userid'],
		),array(
			'ip'=>getIp(),  //更新登录ip
			'uptime'=>time(),   //更新登录时间
		));
		
		$_SESSION['tsuser']	= $userData;
		header("Location: ".SITE_URL);
		exit;
		
	}else{
	
		//获取用户基本资料
		$arrUserInfo = $douban->get('user/~me');
		//print_r($arrUserInfo);exit;
		
		/*	
Array ( [loc_id] => 108288 [name] => 我就是我 [created] => 2012-01-07 13:02:07 [is_suicide] => [loc_name] => 北京 [avatar] => http://img3.douban.com/icon/u57581719-6.jpg [signature] => [uid] => zhongyaoquan [alt] => http://www.douban.com/people/zhongyaoquan/ [desc] => [type] => user [id] => 57581719 [large_avatar] => http://img3.douban.com/icon/up57581719-6.jpg )
		*/

		if($arrUserInfo['name']==''){
			tsNotice('登陆失败！请使用Email登陆');
		}
		
		$salt = md5(rand());
		
		$pwd = random(5,0);
		
		$userid = $new['pubs']->create('user',array(
			'pwd'=>md5($salt.$pwd),
			'salt'=>$salt,
			'email'=>$openid,
		));
		
		//插入ts_user_info
		$new['pubs']->create('user_info',array(
			'userid'			=> $userid,
			'username' 	=> $arrUserInfo['name'],
			'email'		=> $openid,
			'ip'			=> getIp(),
			'addtime'	=> time(),
			'uptime'	=> time(),
		));
		
		//插入ts_user_open
		$new['pubs']->create('user_open',array(
			'userid'=>$userid,
			'sitename'=>'douban',
			'openid' => $openid,
			'access_token'=>$access_token,
			'uptime'=>time(),
		));
		
		//更新用户头像
		if($arrUserInfo['large_avatar']){
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
				$img = file_get_contents($arrUserInfo['large_avatar']); 
				file_put_contents($dfile,$img); 
			};
			
			$new['pubs']->update('user_info',array(
				'userid'=>$userid,
			),array(
				'path'=>$menu,
				'face'=>$photos,
			));
			
		}
		
		//获取用户信息
		$userData = $new['pubs']->find('user_info',array(
			'userid'=>$userid,
		),'userid,username,path,face,isadmin,signin,uptime');
		
		
		//发送系统消息(恭喜注册成功)
		$msg_userid = '0';
		$msg_touserid = $userid;
		$msg_content = '亲爱的豆瓣用户 '.$username.' ：<br />您成功加入了 '
									.$TS_SITE['site_title'].'<br />在遵守本站的规定的同时，享受您的愉快之旅吧!';
		aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
		
		$_SESSION['tsuser']	= $userData;

		header("Location: ".SITE_URL);
		exit;

	}
}
