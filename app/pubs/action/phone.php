<?php
defined('IN_TS') or die('Access Denied.');

/**
 * 发送手机验证码
 */

$phone = trim($_POST['phone']);

$authcode = strtolower($_POST['authcode']);

$typeid = tsIntval($_POST['typeid']); //判断手机号是否存在0不判断1判断存在2判断不存在


#人机验证
$vaptcha_token = trim ( $_POST ['vaptcha_token'] );
if ($TS_SITE['is_vaptcha']) {
    $strVt = vaptcha($vaptcha_token);
    if($strVt['success']==0) {
        getJson('人机验证未通过！',1,0);
    }
}


if(isPhone($phone)==false){
    //echo 0;exit;//手机号码输入有误
    getJson('手机号码输入有误',1,0);
}

if ($authcode != $_SESSION['verify']) {
    //echo 5;exit;//图片验证码输入有误
    getJson('图片验证码输入有误！',1,0);
}

#过滤手机号
$is_anti_phone = $new['pubs']->find('anti_phone',array(
    'phone'=>$phone,
));
if($is_anti_phone>0){
    getJson('非法操作！',1,0);
}

if($typeid==1){
    $strUserPhone = $new['pubs']->find('user',array(
        'phone'=>$phone,
    ));

    if($strUserPhone){
        //echo 3;exit;//手机号已经存在
        getJson('手机号已经存在！',1,0);
    }
}elseif($typeid==2){

    $strUserPhone = $new['pubs']->find('user',array(
        'phone'=>$phone,
    ));

    if($strUserPhone==''){
        //echo 4;exit;//手机号不存在
        getJson('手机号不存在！',1,0);
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

    #短信发送间隔时间
    $phone_code_send_time = tsIntval($TS_APP['phone_code_send_time']);
    if($phone_code_send_time==0) $phone_code_send_time = 30;

    $time30 = 60*$phone_code_send_time;

    if($ntime<$time30){
        //echo 1;exit;//30分钟内只能发送一次短信验证码
        getJson('30分钟内只能发送一次短信验证码！',1,0);
    }else{

        $new['pubs']->update('phone_code',array(
            'phone'=>$phone,
        ),array(
            'code'=>$code,
            'nums'=>0,
            'addtime'=>date('Y-m-d H:i:s'),
        ));

        $response = aac('mail')->sendSms($phone,$code);
        #var_dump($response);

        //echo 2;exit;//发送成功
        getJson('发送成功！',1,1);

    }

}else{

    $new['pubs']->create('phone_code',array(
        'phone'=>$phone,
        'code'=>$code,
        'nums'=>0,
        'addtime'=>date('Y-m-d H:i:s'),
    ));

    $response = aac('mail')->sendSms($phone,$code);
    #var_dump($response);

    //echo 2;exit;//发送成功
    getJson('发送成功！',1,1);

}