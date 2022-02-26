<?php 
defined('IN_TS') or die('Access Denied.');
//消息盒子
$userid = aac('user')->isLogin();

$touserid= tsIntval($_GET['touserid']);


$strTouser = aac('user')->getSimpleUser($touserid);

$where = "(userid='$userid' and touserid='$touserid' and `tourl`='') or (userid='$touserid' and touserid='$userid' and `tourl`='')";

$msgCount = $new['message']->findCount('message',$where);

$arrMessage = $new['message']->findAll('message',$where,'addtime desc',null,10);


foreach($arrMessage as $key=>$item){
    $arrMessage[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
    $arrMessage[$key]['content'] = tsTitle($item['content']);
}

$arrMessage = array_reverse($arrMessage);


//isread设为已读
$new['message']->update('message',array(
	'userid'=>$touserid,
	'touserid'=>$userid,
	'isread'=>0,
),array(
	'isread'=>1,
));

$title = '消息盒子';

include template("user");