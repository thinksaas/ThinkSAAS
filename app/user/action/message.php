<?php
defined('IN_TS') or die('Access Denied.');
 
//用户是否登录
$userid = aac('user')->isLogin();

switch($ts){
	//发送消息页面
	case "add":
		
		$touserid = tsIntval($_GET['touserid']);
		
        if($userid == $touserid || !$touserid) {
            tsNotice("Sorry！自己不能给自己发送消息的！& 对方为空!");
        }
        
        #互为粉丝的2个人才可以发送消息
        $isFollow = $new['user']->findCount('user_follow',array(
            'userid'=>$userid,
            'userid_follow'=>$touserid,
        ));

        $isFollow2 = $new['user']->findCount('user_follow',array(
            'userid'=>$touserid,
            'userid_follow'=>$userid,
        ));

        if($isFollow && $isFollow2){

            $strUser = $new['user']->getSimpleUser($userid);
		
            $strTouser = $new['user']->getSimpleUser($touserid);
    
            if(!$strTouser) tsNotice("Sorry！对方不存在!");
            $title = "发送短消息";
            include template("message_add");

        }else{

            tsNotice("互相关注的2个人才可以互相发送私信!");

        }
		
		break;
	
    case "do":
        
        $js = tsIntval($_GET['js']);
	
		$msg_userid = $userid;
        $msg_touserid = tsIntval($_POST['touserid']);
        
        if($msg_userid == $msg_touserid || !$msg_touserid) {
            getJson("Sorry！自己不能给自己发送消息的！& 对方为空!",$js,0);
        }
        
        #互为粉丝的2个人才可以发送消息
        $isFollow = $new['user']->findCount('user_follow',array(
            'userid'=>$msg_userid,
            'userid_follow'=>$msg_touserid,
        ));

        $isFollow2 = $new['user']->findCount('user_follow',array(
            'userid'=>$msg_touserid,
            'userid_follow'=>$msg_userid,
        ));

        if($isFollow && $isFollow2){

            $msg_content = trim($_POST['content']);

            if($msg_content==''){
                getJson('消息内容不能为空！',$js,0);
            }
		
            $msg_content = antiWord($msg_content);
            
            aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
    
            getJson('消息发送成功',$js,1);

        }else{

            getJson("互相关注的2个人才可以互相发送私信!",$js,0);

        }

		break;
}