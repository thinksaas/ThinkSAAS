<?php
defined('IN_TS') or die('Access Denied.');

use EasyWeChat\Factory;

if(tsIntval($TS_USER['userid']) > 0) {
	header('Location: '.SITE_URL);exit;
}

#微信公众号授权
if(isWeixin()==true && $TS_SITE['is_weixin']==1){
	$config = [
		'app_id' => $TS_SITE['weixin_appid'],
		'oauth' => [
			'scopes'   => ['snsapi_userinfo'],
			'callback' => SITE_URL.'index.php?app=user&ac=wxlogin',
		],	
	];
	$app = Factory::officialAccount($config);
	$oauth = $app->oauth;
	$oauth->redirect()->send();
	exit();
}

//程序主体
switch($ts){
	case "":
		
		
		//记录上次访问地址
		$jump = $_SERVER['HTTP_REFERER'];

		$title = '登录';
		include template("login");
	break;
	
	//执行登录
	case "do":
		
		//用于JS提交验证
		$js = tsIntval($_GET['js']);

        $ad = tsIntval($_POST['ad']);
		
		/*禁止以下IP用户登陆或注册*/
        /*
		$arrIp = aac('system')->antiIp();
		if(in_array(getIp(),$arrIp)){
			getJson('你的IP已被锁定，暂无法登录！',$js);
		}
        */
		
		$jump = trim($_POST['jump']);
		
		$email = trim($_POST['email']);
		
		$pwd = trim($_POST['pwd']);
		
		$cktime = $_POST['cktime'];


		#人机验证
		$vaptcha_token = trim($_POST ['vaptcha_token']);
		if ($TS_SITE['is_vaptcha'] && $ad==0) {
			$strVt = vaptcha($vaptcha_token);
			if($strVt['success']==0) {
				getJson('人机验证未通过！',$js);
			}
		}

		
		if($email=='' || $pwd=='') getJson('账号和密码都不能为空！',$js);

		#先判断是否是Email
		if(valid_email($email)==true){

            $strUser = $new['user']->find('user',array(
                'email'=>$email,
            ));

            //if($strUser == '') getJson('Email不存在，你可能还没有注册！',$js);
            if($strUser == '') getJson('账号/密码输入有误！',$js);

        }else{

		    #判断是否是手机号
            if(isPhone($email)==true){

                $strUser = $new['user']->find('user',array(
                    'phone'=>$email,
                ));

                #if($strUser == '') getJson('手机号不存在，你可能还没有注册！',$js);
                if($strUser == '') getJson('账号/密码输入有误！',$js);

            }else{
                #getJson('账号不存在，你可能还没有注册！',$js);
                getJson('账号/密码输入有误！',$js);
            }

        }
			
		if(md5($strUser['salt'].$pwd)!==$strUser['pwd']) {
			#getJson('密码错误！',$js);
			getJson('账号/密码输入有误！',$js);
		}
		
		$new['user']->login($strUser['userid']);

		//对积分进行处理
		aac('user')->doScore($GLOBALS['TS_URL']['app'], $GLOBALS['TS_URL']['ac'], $GLOBALS['TS_URL']['ts'],0,'',1);

        if($ad==1){
            getJson('登录成功！',$js,2,SITE_URL.'index.php?app=system');
        }

		//跳转
		if($jump != ''){
			getJson('登录成功！',$js,2,$jump);
		}else{
			
			//登陆是否跳转到我的社区
			if($TS_SITE['istomy']){
				getJson('登录成功！',$js,2,tsUrl('my'));
			}else{
				getJson('登录成功！',$js,2,SITE_URL);
			}
			
		}
		
	break;
	
}