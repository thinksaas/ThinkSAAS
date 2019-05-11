<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/9
 * Time: 15:25
 */
defined('IN_TS') or die('Access Denied.');

$phone = trim($_POST['phone']);

$authcode = strtolower($_POST['authcode']);

$typeid = intval($_POST['typeid']);

if(isPhone($phone)==false){
    echo 0;exit;//手机号码输入有误
}

if ($authcode != $_SESSION['verify']) {
    echo 5;exit;//图片验证码输入有误
}

if($typeid==0){
    //判断手机号是否已经存在
    $strUserPhone = $new['pubs']->find('user_info',array(
        'email'=>$phone,
    ));

    if($strUserPhone){
        echo 3;exit;//手机号已经存在
    }
}

$strPhone = $new['pubs']->find('phone_code',array(
    'phone'=>$phone,
));

$code = random(4,1);

if($strPhone){

    $time = time();
    $ptime = strtotime($strPhone['addtime']);

    $ntime = $time-$ptime;
    $time30 = 60*30;

    if($ntime<$time30){
        echo 1;exit;//30分钟内只能发送一次短信验证码
    }else{


        $new['pubs']->update('phone_code',array(
            'phone'=>$phone,
        ),array(
            'code'=>$code,
            'addtime'=>date('Y-m-d H:i:s'),
        ));

        $response = aac('mail')->sendSms($phone,$code);
        #var_dump($response);

        echo 2;exit;//发送成功

    }

}else{

    $new['pubs']->create('phone_code',array(
        'phone'=>$phone,
        'code'=>$code,
        'addtime'=>date('Y-m-d H:i:s'),
    ));

    $response = aac('mail')->sendSms($phone,$code);

    #var_dump($response);

    echo 2;exit;//发送成功

}