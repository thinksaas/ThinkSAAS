<?php 
defined('IN_TS') or die('Access Denied.');
/**
 * 手机验证码登录
 */
if(tsIntval($TS_USER['userid']) > 0) {
	header('Location: '.SITE_URL);exit;
}

switch($ts){
    case "":

        $title = '手机验证码登录';
        include template("plogin");
    break;

    case "do":

        $js = tsIntval($_GET['js']);
        $phone = trim($_POST['email']);
        $authcode = strtolower($_POST['authcode']);
        $phonecode = trim($_POST['phonecode']);

        if($phone=='' || $phonecode==''){
            getJson('所有输入项都不能为空！',$js);
        }

        if(isPhone($phone)==false) getJson('手机号输入有误！',$js);

        #验证手机验证码
        if(aac('pubs')->verifyPhoneCode($phone,$phonecode)==false){
            getJson('手机验证码输入有误！',$js);
        }

        #手机号是否存在
        $strUser = $new['user']->find('user',array(
            'phone'=>$phone,
        ));

        if($strUser){
            
            $new['user']->login($strUser['userid'],$phone);

            getJson('登录成功！',$js,2,SITE_URL);


        }else{

            $new['user']->register($phone);

        }

        //跳转
        getJson('登录成功！',$js,2,SITE_URL);

    break;

}