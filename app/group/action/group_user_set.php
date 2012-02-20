<?php 
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();

switch($ts){
	//设置为管理员和取消为管理员
	case "isadmin":

		$userid = intval($_GET['userid']);
		$groupid = intval($_GET['groupid']);
		$isadmin = intval($_GET['isadmin']);
		
		if($userid == '' && $groupid=='' && $isadmin=='') tsNotice("请不要冒险进入危险境地！");
		
		$strGroup = $db->once_fetch_assoc("select * from ".dbprefix."group where groupid='".$groupid."'");
		
		
		if($TS_USER['user']['userid'] != $strGroup['userid']) tsNotice("机房重地，闲人免进！");
		
		$db->query("update ".dbprefix."group_users set `isadmin`='".$isadmin."' where userid='".$userid."' and groupid='".$groupid."'");

		header("Location: ".SITE_URL."index.php?app=group&ac=group_user&groupid=".$groupid."");
		
		break;
	
	//踢出小组成员
	case "isuser":
		$userid = intval($_GET['userid']);
		$groupid = intval($_GET['groupid']);
		$isuser = intval($_GET['isuser']);
		
		if($userid == '' && $groupid=='' && $isuser=='') tsNotice("请不要冒险进入危险境地！");
		$strGroup = $db->once_fetch_assoc("select * from ".dbprefix."group where groupid='".$groupid."'");
		if($TS_USER['user']['userid'] != $strGroup['userid']) tsNotice("机房重地，闲人免进！");
		
		$db->query("DELETE FROM ".dbprefix."group_users WHERE userid = '$userid' AND groupid = '$groupid'");
		
		//计算小组会员数
		$groupUserNum = $db->once_num_rows("select * from ".dbprefix."group_users where groupid='$groupid'");
		
		//更新小组成员统计
		$db->query("update ".dbprefix."group set `count_user`='$groupUserNum' where groupid='$groupid'");
		
		header("Location: ".SITE_URL."index.php?app=group&ac=group_user&groupid=".$groupid."");
		
		break;
}