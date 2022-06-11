<?php 
defined('IN_TS') or die('Access Denied.');
//用户是否登录
$userid = aac('user')->isLogin($js,$userkey);

$commentid = tsIntval($_GET['commentid']);
		
$strComment = $new['comment']->find('comment',array(
    'commentid'=>$commentid,
));

$ptable = $strComment['ptable'];
$pkey = $strComment['pkey'];
$pid = $strComment['pid'];

if($TS_USER['isadmin']==1 || $strComment['userid']==$userid){
    
    $new['comment']->delComment($ptable,$pkey,$pid,$commentid);

    //处理积分
    aac('user')->doScore($TS_URL['app'], $TS_URL['ac'],$TS_URL['mg'],$TS_URL['api'], $TS_URL['ts'],$strComment['userid']);
    
}

//跳转回到详情页
header("Location: ".getProjectUrl($ptable,$pid));