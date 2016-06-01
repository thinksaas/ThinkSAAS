<?php
/*
 * 其他用户系统整合ThinkSAAS
 * 1、到ThinkSAAS后台关闭用户注册和登录（或者隐藏掉注册和登录）
 * 2、访问ThinkSAAS的接口(post方式，带参数)
 *      http://你的域名/index.php?app=user&ac=api
 *      请求方式：post
 *      参数：
 *      @sitekey        双方网站通信密钥，必须一样
 *      @sitename     用户来源用户网站名称。必须，例如qq,weibo
 *      @openid         唯一用户ID，必须
 *      @username    用户名/姓名，必须
 *      @email          唯一Email，必须(如果没有email，把唯一openid的值传入)
 *      @phone         手机号/电话号
 *      @face           用户头像，绝对路径图片。例如：http://sfault-avatar.b0.upaiyun.com/213/644/2136446251-1030000000092523_huge256
 */
$sitekey = trim($_POST['sitekey']);
$sitename = trim($_POST['sitename']);
$openid = trim($_POST['openid']);
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$face = trim($_POST['face']);

if($sitekey!='thinksaas'){//正式使用的时候请修改此处thinksaas为其他字符串
    getJson('sitekey密钥不正确');
}

if($sitename && $openid && $username && $email){


    $strOpen = $new['user']->find('user_open',array(
        'sitename'=>$sitename,
        'openid'=>$openid,
    ));

    if($strOpen){

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

        //header("Location: ".SITE_URL);exit;

        getJson('登录成功！',1,1);


    }else{


        $salt = md5(rand());

        $pwd = random(5,0);

        $userid = $new['user']->create('user',array(
            'pwd'=>md5($salt.$pwd),
            'salt'=>$salt,
            'email'=>$email,
        ));

        //插入ts_user_info
        $new['user']->create('user_info',array(
            'userid'			=> $userid,
            'username' 	=> $username,
            'email'		=> $email,
            'ip'			=> getIp(),
            'addtime'	=> time(),
            'uptime'	=> time(),
        ));

        //插入ts_user_open
        $new['user']->create('user_open',array(
            'userid'=>$userid,
            'sitename'=>$sitename,
            'openid' => $openid,
            'uptime'=>time(),
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
        $msg_content = '亲爱的'.$sitename.'用户 '.$username.' ：您成功加入了 '
            .$TS_SITE['site_title'].'在遵守本站的规定的同时，享受您的愉快之旅吧!';
        aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);

        $_SESSION['tsuser']	= $userData;

        //header("Location: ".SITE_URL);exit;

        getJson('登录成功！',1,1);


    }



}else{
    getJson('sitename,openid,username,email都不能为空');
}