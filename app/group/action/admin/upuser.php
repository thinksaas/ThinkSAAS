<?php
defined('IN_TS') or die('Access Denied.'); 

//将用户全部绑定到群组
$groupid = intval($_GET['groupid']);

$arrUser = $db->fetch_all_assoc("select userid from ".dbprefix."user order by userid desc");

foreach($arrUser as $item){
	$groupusernum = $db->once_num_rows("select * from ".dbprefix."group_user where  userid='".$item['userid']."' and groupid='".$groupid."'");
	
	if($groupusernum == '0'){
		$db->query("insert into ".dbprefix."group_user (`userid`,`groupid`,`addtime`) values ('".$item['userid']."','".$groupid."','".time()."')");
	}
	
}

$userNum = $db->once_num_rows("select * from ".dbprefix."group_user where groupid='".$groupid."'");

$db->query("update ".dbprefix."group set `count_user`='".$userNum."' where groupid='".$groupid."'");

qiMsg("会员投送成功！");