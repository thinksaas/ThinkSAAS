<?php
defined('IN_TS') or die('Access Denied.');

use EasyWeChat\Factory;

$config = [
    'app_id' => $TS_SITE['weixin_appid'],
    'secret' => $TS_SITE['weixin_appsecret'],
  ];
  
$app = Factory::officialAccount($config);
$oauth = $app->oauth;

// 获取 OAuth 授权结果用户信息
$user = $oauth->user();

// $user 可以用的方法:
// $user->getId();  // 对应微信的 OPENID
// $user->getNickname(); // 对应微信的 nickname
// $user->getName(); // 对应微信的 nickname
// $user->getAvatar(); // 头像网址
// $user->getOriginal(); // 原始API返回的结果
// $user->getToken(); // access_token， 比如用于地址共享时使用

if($user->getToken() && $user->getId()){

    $openid = $user->getId();

    $access_token = $user->getToken();

    $strOpen = $new['user']->find('user_open',array(
        'sitename'=>'weixin',
        'openid'=>$openid,
    ));

    //10天更换一次access_token
    if(time()-10*86400>$strOpen['uptime']){
        $new['user']->update('user_open',array(
            'sitename'=>'weixin',
            'openid'=>$openid,
        ),array(
            'access_token'=>$access_token,
            'uptime'=>time(),
        ));
    }


    if($strOpen['userid']){

        $userData = $new['user']->find('user_info',array(
            'userid'=>$strOpen['userid'],
        ),'userid,username,path,face,isadmin,signin,uptime');

        //更新登录时间
        $new['user']->update('user_info',array(
            'userid'=>$strOpen['userid'],
        ),array(
            'ip'=>getIp(),  //更新登录ip
            'uptime'=>time(),   //更新登录时间
        ));

        $_SESSION['tsuser']	= $userData;

        if($_COOKIE['wx_jump']){
            header("Location: ".$_COOKIE['wx_jump']);
        }else{
            header("Location: ".SITE_URL);
        }

        exit;

    }else{

    
        $salt = md5(rand());

        $pwd = random(5,0);

        $userid = $new['user']->create('user',array(
            'pwd'=>md5($salt.$pwd),
            'salt'=>$salt,
            'email'=>$openid,
            'phone'=>$openid,
        ));

        $username = $user->getNickname();
        $userface = $user->getAvatar();

        //插入ts_user_info
        $new['user']->create('user_info',array(
            'userid'    => $userid,
            'username' 	=> $username,
            'email'		=> $openid,
            'phone'     => $openid,
            'ip'		=> getIp(),
            'addtime'	=> time(),
            'uptime'	=> time(),
        ));

        //插入ts_user_open
        $new['user']->create('user_open',array(
            'userid'=>$userid,
            'sitename'=>'weixin',
            'openid' => $openid,
            'access_token'=>$access_token,
            'uptime'=>time(),
        ));

        //更新用户头像
        if($userface!='' && $userface!='/0'){
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
                $img = file_get_contents($userface);
                file_put_contents($dfile,$img);
            };
            $new['user']->update('user_info',array(
                'userid'=>$userid,
            ),array(
                'path'=>$menu,
                'face'=>$photos,
            ));
        }

        //获取用户信息
        $userData = $new['user']->find('user_info',array(
            'userid'=>$userid,
        ),'userid,username,path,face,isadmin,signin,uptime');


        //发送系统消息(恭喜注册成功)
        $msg_userid = '0';
        $msg_touserid = $userid;
        $msg_content = '亲爱的微信用户 '.$username.' ：您成功加入了 '
            .$TS_SITE['site_title'].'在遵守本站的规定的同时，享受您的愉快之旅吧!';
        aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);

        $_SESSION['tsuser']	= $userData;

        if($_COOKIE['wx_jump']){
            header("Location: ".$_COOKIE['wx_jump']);
        }else{
            header("Location: ".SITE_URL);
        }
        exit;
    }
}