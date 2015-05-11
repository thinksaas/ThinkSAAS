<?php

defined('IN_TS') or die('Access Denied.'); 
require_once("config.php");
include_once( 'saetv2.ex.class.php' );
$o = new SaeTOAuthV2(WB_AKEY , WB_SKEY );


if (isset($_REQUEST['code'])) {

	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = WB_CALLBACK_URL;
	try {
		$token = $o->getAccessToken( 'code', $keys ) ;
	} catch (OAuthException $e) {
	}
}

$access_token = $token['access_token'];
$openid = $token['uid'];

//cookie10天
setcookie("weibo_access_token",$access_token, time()+3600*24*10);
setcookie("weibo_openid",$openid, time()+3600*24*10);

if ($openid) {
	
	$strOpen = $new['pubs']->find('user_open',array(
		'sitename'=>'weibo',
		'openid'=>$openid,
	));
	
	//10天更换一次access_token
	if(time()-10*86400>$strOpen['uptime']){
		$new['pubs']->update('user_open',array(
			'sitename'=>'weibo',
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
		
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $access_token );
		$uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$arrUserInfo = $c->show_user_by_id( $uid);
		
		/*	
Array ( [id] => 2741015883 [idstr] => 2741015883 [class] => 1 [screen_name] => 哥哥很伤心啦 [name] => 哥哥很伤心啦 [province] => 11 [city] => 5 [location] => 北京 朝阳区 [description] => [url] => [profile_image_url] => http://tp4.sinaimg.cn/2741015883/50/5633423902/1 [profile_url] => u/2741015883 [domain] => [weihao] => [gender] => m [followers_count] => 3 [friends_count] => 30 [statuses_count] => 0 [favourites_count] => 0 [created_at] => Thu May 31 17:22:49 +0800 2012 [following] => [allow_all_act_msg] => [geo_enabled] => 1 [verified] => [verified_type] => -1 [remark] => [ptype] => 0 [allow_all_comment] => 1 [avatar_large] => http://tp4.sinaimg.cn/2741015883/180/5633423902/1 [avatar_hd] => http://tp4.sinaimg.cn/2741015883/180/5633423902/1 [verified_reason] => [follow_me] => [online_status] => 0 [bi_followers_count] => 0 [lang] => zh-cn [star] => 0 [mbtype] => 0 [mbrank] => 0 [block_word] => 0 )
		*/

		if($arrUserInfo['screen_name']==''){
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
			'username' 	=> $arrUserInfo['screen_name'],
			'email'		=> $openid,
			'ip'			=> getIp(),
			'addtime'	=> time(),
			'uptime'	=> time(),
		));
		
		//插入ts_user_open
		$new['pubs']->create('user_open',array(
			'userid'=>$userid,
			'sitename'=>'weibo',
			'openid' => $openid,
			'access_token'=>$access_token,
			'uptime'=>time(),
		));
		
		//更新用户头像
		if($arrUserInfo['avatar_large']){
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
				$img = file_get_contents($arrUserInfo['avatar_large']); 
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
		$msg_content = '亲爱的微博用户 '.$username.' ：<br />您成功加入了 '
									.$TS_SITE['site_title'].'<br />在遵守本站的规定的同时，享受您的愉快之旅吧!';
		aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
		
		$_SESSION['tsuser']	= $userData;

		header("Location: ".SITE_URL);
		exit;

	}
	
	
} 
