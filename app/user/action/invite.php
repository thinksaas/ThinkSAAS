<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 邀请
 */


$userid = $TS_USER['user']['userid'];

if($TS_USER['user']=='') header("Location: ".SITE_URL."index.php");

$strUser = $db->once_fetch_assoc("select * from ".dbprefix."user_info where userid='$userid'");

//邀请好友
switch($ts){
	case "":
		
		$codeNum = $db->once_num_rows("select * from ".dbprefix."user_invites where isused='0'");

		$title = '邀请码';
		include template("invite");
		
		break;
		
	//取邀请码
	case "invite_code":
		
		//计算是否还有邀请码
		$codeNum = $db->once_num_rows("select * from ".dbprefix."user_invites where isused='0'");
		
		if($codeNum > 0){
		
			//取一个码
			$strCode = $db->once_fetch_assoc("select * from ".dbprefix."user_invites where isused='0' limit 1");
		}else{
			//当数据库中没码的时间生成50个码
			for($i=1;$i<=50;$i++){
				$db->query("insert into ".dbprefix."user_invites (`invitecode`,`addtime`) values ('".random(18)."','".time()."')");

			}
			//再次取码
			$strCode = $db->once_fetch_assoc("select * from ".dbprefix."user_invites where isused='0' limit 1");
		}
		
		$title = '邀请码';
		include template("invite_code");
		
		break;
}