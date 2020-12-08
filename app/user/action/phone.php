<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){

    //手机号注册
    case "":

        if(tsIntval($TS_USER['userid']) > 0) {
            header('Location: '.SITE_URL);exit;
        }

        #如果网站只采用Email注册，就跳转到Email注册
        if($TS_SITE['regtype']==0){
            header('Location: '.tsUrl('user','register'));exit;
        }

        //邀请用户ID
        $fuserid = tsIntval($_GET['fuserid']);

        $title = '手机号注册';

        include template("phone");

        break;


    case "do":

        //用于JS提交验证
        $js = tsIntval($_GET['js']);

        $phone		= trim($_POST['email']);
        $pwd			= trim($_POST['pwd']);
        $repwd		= trim($_POST['repwd']);
        $username		= t($_POST['username']);

        $fuserid = tsIntval($_POST['fuserid']);

        $authcode = strtolower($_POST['authcode']);

        $phonecode = trim($_POST['phonecode']);


        /*禁止以下IP用户登陆或注册*/
        $arrIp = aac('system')->antiIp();
        if(in_array(getIp(),$arrIp)){
            getJson('你的IP已被锁定，暂无法登录！',$js);
        }


        //是否开启邀请注册
        if($TS_SITE['isinvite']=='1'){

            $invitecode = trim($_POST['invitecode']);
            if($invitecode == '') getJson('邀请码不能为空！',$js);

            $codeNum = $new['user']->findCount('user_invites',array(
                'invitecode'=>$invitecode,
                'isused'=>0,
            ));

            if($codeNum == 0) getJson('邀请码已经被使用，请更换其他邀请码！',$js);

        }

        if($phone=='' || $pwd=='' || $repwd=='' || $username=='' || $phonecode==''){

            getJson('所有必选项都不能为空！',$js);

        }

        if(isPhone($phone)==false) getJson('手机号输入有误！',$js);

        #验证手机验证码
        if(aac('pubs')->verifyPhoneCode($phone,$phonecode)==false){
            getJson('手机验证码输入有误',$js);
        }

        #手机号是否存在
        $isPhone = $new['user']->findCount('user',array(
            'phone'=>$phone,
        ));

        if($isPhone > 0){
            getJson('手机号已经存在',$js);
        }

        if($pwd != $repwd){
            getJson('两次输入密码不正确！',$js);
        }


        if(count_string_len($username) < 4 || count_string_len($username) > 20){
            getJson('姓名长度必须在4和20之间',$js);
        }

        #用户名是否存在
        $isUserName = $new['user']->findCount('user_info',array(
            'username'=>$username,
        ),'userid');

        if($isUserName > 0){
            getJson('用户名已经存在，请换个用户名！',$js);
        }


        #验证码
        if ($authcode != $_SESSION['verify']) {
            getJson('验证码输入有误，请重新输入！', $js);
        }

        $salt = md5(rand());

        $userid = $new['user']->create('user',array(
            'pwd'=>md5($salt.$pwd),
            'salt'=>$salt,
            'email'=>$phone,
            'phone'=>$phone,
        ));

        //插入用户信息
        $new['user']->create('user_info',array(
            'userid'			=> $userid,
            'fuserid'	=> $fuserid,
            'username' 	=> $username,
            'email'		=> $phone,
            'phone'		=> $phone,
            'ip'			=> getIp(),
            'comefrom'=>'9',
            'isverifyphone'=>1,
            'addtime'	=> time(),
            'uptime'	=> time(),
        ));

        //默认加入小组
        $isGroup = $new['user']->find('user_options',array(
            'optionname'=>'isgroup',
        ));

        if($isGroup['optionvalue']){
            $arrGroup = explode(',',$isGroup['optionvalue']);

            if($arrGroup){
                foreach($arrGroup as $key=>$item){
                    $groupUserNum = $new['user']->findCount('group_user',array(
                        'userid'=>$userid,
                        'groupid'=>$item,
                    ));

                    if($groupUserNum == 0){
                        $new['user']->create('group_user',array(
                            'userid'=>$userid,
                            'groupid'=>$item,
                            'addtime'=>time(),
                        ));

                        //统计更新
                        $count_user = $new['user']->findCount('group_user',array(
                            'groupid'=>$item,
                        ));

                        $new['user']->update('group',array(
                            'groupid'=>$item,
                        ),array(
                            'count_user'=>$count_user,
                        ));

                    }
                }
            }
        }

        //用户信息
        $userData = $new['user']->find('user_info',array(
            'userid'=>$userid,
        ),'userid,username,path,face,isadmin,signin,uptime');

        //用户session信息
        $_SESSION['tsuser']	= $userData;

        //发送消息
        aac('message')->sendmsg(0,$userid,'亲爱的 '.$username.' ：您成功加入了 '.$TS_SITE['site_title'].'。在遵守本站的规定的同时，享受您的愉快之旅吧!');

        //注销邀请码并将关注邀请用户
        if($TS_SITE['isinvite']=='1'){

            //邀请码信息
            $strInviteCode = $new['user']->find('user_invites',array(
                'invitecode'=>$invitecode,
            ));

            $new['user']->create('user_follow',array(
                'userid'=>$userid,
                'userid_follow'=>$strInviteCode['userid'],
            ));

            //注销
            $new['user']->update('user_invites',array(
                'invitecode'=>$invitecode,
            ),array(
                'isused'=>'1',
            ));
        }

        //对积分进行处理
        aac('user')->doScore($GLOBALS['TS_URL']['app'], $GLOBALS['TS_URL']['ac'], $GLOBALS['TS_URL']['ts']);

        //跳转
        getJson('登录成功！',$js,2,SITE_URL);

        break;


    //验证手机号
    case "verify":

        $userid = aac('user')->isLogin();

        $strUser = $new['user']->getOneUser($userid);

        $title = '验证手机号';
        include template("phone_verify");

        break;


    case "verifydo":

        $js = tsIntval($_GET['js']);

        $userid = aac('user')->isLogin();

        $phone = trim($_POST['phone']);

        $authcode = strtolower($_POST['authcode']);
        $phonecode = trim($_POST['phonecode']);

        if($phone == '' || $authcode=='' || $phonecode==''){
            getJson('所有输入项都不能为空',$js);
        }

        if(isPhone($phone)==false){
            getJson('手机号输入有误！',$js);
        }

        if ($authcode != $_SESSION['verify']) {
            getJson('图片验证码输入有误，请重新输入！', $js);
        }

        #验证手机验证码
        if(aac('pubs')->verifyPhoneCode($phone,$phonecode)==false){
            getJson('手机验证码输入有误',$js);
        }


        $strUserInfo = $new['user']->find('user_info',array(
            'userid'=>$userid,
        ),'phone');

        if($strUserInfo['phone']!=$phone){

            #判断手机号是否存在
            $isPhone = $new['user']->findCount('user',array(
                'phone'=>$phone,
            ));

            if($isPhone){
                getJson('手机号已存在！请更换其他手机号！',$js);
            }

            //getJson('手机号有误！',$js);
        }

        #更新手机号
        $new['user']->update('user',array(
            'userid'=>$userid,
        ),array(
            'phone'=>$phone,
        ));
        #更新手机号和手机验证状态
        $new['user']->update('user_info',array(
            'userid'=>$userid,
        ),array(
            'phone'=>$phone,
            'isverifyphone'=>'1',
        ));

        getJson('手机号验证成功！',$js,2,SITE_URL);

        break;


    //如果手机号不对，可以修改手机号
    case "setphone":

        $userid = aac('user')->isLogin();

        $phone = trim($_POST['phone']);

        if($phone==''){
            tsNotice('手机号不能为空！');
        }

        if(isPhone($phone)==false){
            tsNotice('手机号输入有误！');
        }

        $isPhone = $new['user']->findCount('user',array(
            'phone'=>$phone,
        ));

        if($isPhone>0){
            tsNotice('手机号已经存在，请更换其他手机号！');
        }

        $new['user']->update('user',array(
            'userid'=>$userid,
        ),array(
            'phone'=>$phone,
        ));

        $new['user']->update('user_info',array(
            'userid'=>$userid,
        ),array(
            'phone'=>$phone,
        ));


        tsNotice('手机号修改成功！');

        break;


    //修改成新手机号
    case "editphone":

        $js = tsIntval($_GET['js']);

        $userid = aac('user')->isLogin();

        $phone = trim($_POST['phone']);

        $authcode = strtolower($_POST['authcode']);
        $phonecode = trim($_POST['phonecode']);

        if($phone == '' || $authcode=='' || $phonecode==''){
            getJson('所有输入项都不能为空',$js);
        }

        if(isPhone($phone)==false){
            getJson('手机号输入有误！',$js);
        }

        if ($authcode != $_SESSION['verify']) {
            getJson('图片验证码输入有误，请重新输入！', $js);
        }

        #验证手机验证码
        if(aac('pubs')->verifyPhoneCode($phone,$phonecode)==false){
            getJson('手机验证码输入有误',$js);
        }


        $isPhone = $new['user']->findCount('user',array(
            'phone'=>$phone,
        ));

        if($isPhone>0){
            getJson('手机号已经存在，请更换其他手机号！',$js);
        }


        $new['user']->update('user',array(
            'userid'=>$userid,
        ),array(
            'phone'=>$phone,
        ));


        $new['user']->update('user_info',array(
            'userid'=>$userid,
        ),array(
            'phone'=>$phone,
            'isverifyphone'=>'1',
        ));

        getJson('手机号修改成功！',$js,2,tsUrl('my','setting',array('ts'=>'email')));

        break;


    /**
     * 通过手机号重置密码
     */
    case "resetpwd":

        $js = tsIntval($_GET['js']);


        $phone = trim($_POST['phone']);
        $pwd	= trim($_POST['pwd']);
        $authcode = strtolower($_POST['authcode']);
        $phonecode = trim($_POST['phonecode']);

        if($phone == '' || $pwd=='' || $authcode=='' || $phonecode==''){
            getJson('所有输入项都不能为空',$js);
        }

        if(isPhone($phone)==false){
            getJson('手机号输入不正确',$js);
        }

        $strUser = $new['user']->find('user',array(
            'phone'=>$phone,
        ));

        if($strUser==''){
            getJson("手机号不存在，你可能还没有注册^_^",$js);
        }


        if ($authcode != $_SESSION['verify']) {
            getJson('图片验证码输入有误，请重新输入！', $js);
        }


        #验证手机验证码
        if(aac('pubs')->verifyPhoneCode($phone,$phonecode)==false){
            getJson('手机验证码输入有误',$js);
        }

        $salt = md5(rand());

        $new['user']->update('user',array(
            'userid'=>$strUser['userid'],
        ),array(
            'pwd'=>md5($salt.$pwd),
            'salt'=>$salt,
        ));


        $new['user']->update('user_info',array(
            'userid'=>$strUser['userid'],
        ),array(
            'phone'=>$strUser['phone'],
            'isverifyphone'=>'1',
        ));


        getJson('密码修改成功！',$js,2,tsUrl('user','login'));

        break;


}