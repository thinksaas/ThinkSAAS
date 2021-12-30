<?php
defined('IN_TS') or die('Access Denied.');

/**
 * 发送Email验证码
 */

$email = trim($_POST['email']);

$typeid = tsIntval($_POST['typeid']); //判断Email是否存在：0不判断、1判断存在、2判断不存在

#人机验证
$vaptcha_token = trim ( $_POST ['vaptcha_token'] );
if ($TS_SITE['is_vaptcha']) {
    $strVt = vaptcha($vaptcha_token);
    if($strVt['success']==0) {
        getJson('人机验证未通过！',1,0);
    }
}

if(valid_email($email) == false){
    getJson('Email输入有误',1,0);
}

#过滤Email
$is_anti_email = $new['pubs']->find('anti_email',array(
    'email'=>$email,
));
if($is_anti_email>0){
    getJson('非法操作！',1,0);
}

if($typeid==1){
    $strUserEmail = $new['pubs']->find('user',array(
        'email'=>$email,
    ));
    if($strUserEmail){
        getJson('Email已经存在！',1,0);
    }
}elseif($typeid==2){
    $strUserEmail = $new['pubs']->find('user',array(
        'email'=>$email,
    ));

    if($strUserEmail==''){
        getJson('Email不存在！',1,0);
    }
}


$strEmail = $new['pubs']->find('email_code',array(
    'email'=>$email,
));

$code = random(4,1);

if($strEmail){

    $time = time();
    $ptime = strtotime($strEmail['addtime']);

    $ntime = $time-$ptime;

    #短信发送间隔时间
    $email_code_send_time = tsIntval($TS_APP['email_code_send_time']);
    if($email_code_send_time==0) $email_code_send_time = 30;

    $time30 = 60*$email_code_send_time;

    if($ntime<$time30){
        //echo 1;exit;//30分钟内只能发送一次短信验证码
        getJson('30分钟内只能发送一次Email验证码！',1,0);
    }else{

        $new['pubs']->update('email_code',array(
            'email'=>$email,
        ),array(
            'code'=>$code,
            'nums'=>0,
            'addtime'=>date('Y-m-d H:i:s'),
        ));

        $result = aac('mail')->postMail($email,$TS_SITE['site_title'].' Email验证码：'.$code,$TS_SITE['site_title'].' Email验证码：'.$code);
        
        getJson('发送成功！',1,1);

    }

}else{

    $new['pubs']->create('email_code',array(
        'email'=>$email,
        'code'=>$code,
        'nums'=>0,
        'addtime'=>date('Y-m-d H:i:s'),
    ));

    $result = aac('mail')->postMail($email,$TS_SITE['site_title'].' Email验证码：'.$code,$TS_SITE['site_title'].' Email验证码：'.$code);
    
    getJson('发送成功！',1,1);

}