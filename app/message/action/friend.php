<?php
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();

$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
$url = tsUrl('message','friend',array('page'=>''));
$lstart = $page*40-40;

$arrToUsers = $db->fetch_all_assoc("select `userid` from ".dbprefix."message where `userid` > '0' and `touserid`='$userid' group by userid order by addtime desc limit $lstart,40");

$userNum = $db->once_num_rows("select `userid` from ".dbprefix."message where `userid` > '0' and `touserid`='$userid' group by userid");

$pageUrl = pagination($userNum, 40, $page, $url);

if(is_array($arrToUsers)){
	foreach($arrToUsers as $key=>$item){
		$arrToUser[] = $item;
		$arrToUser[$key]['user'] = aac('user')->getOneUser($item['userid']);
		$arrToUser[$key]['count'] = $new['message']->findCount('message',array(
			'touserid'=>$userid,
			'userid'=>$item['userid'],
			'isread'=>0,
		));
		
	}
}

$title = '好友消息';
include template('friend');