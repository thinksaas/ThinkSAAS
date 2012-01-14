<?php
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = intval($TS_USER['user']['userid']);
if($userid == 0){
	header("Location: ".SITE_URL.tsurl('user','login'));
	exit;
}

switch($ts){
	case "":
		
		if($TS_APP['options']['iscreate'] == '1'){
			qiMsg("暂停申请创建小组，稍后开放");
		}
		
		$title = L::create_creategroup;
		include template("create");
		break;
	
	//创建执行
	case "do":
		
		if($userid=='0' || $_POST['groupname']=='' || $_POST['groupdesc']=='') qiMsg("必填项不能为空！");
		
		//配置文件是否需要审核
		$isaudit = intval($TS_APP['options']['isaudit']);
		
		$groupname = h($_POST['groupname']);
		
		$isGroup = $db->once_fetch_assoc("select count(groupid) from ".dbprefix."group where groupname='$groupname'");
		
		if($isGroup['count(groupid)'] > 0) qiMsg("小组名称已经存在，请更换其他小组名称！");
		
		$arrData = array(
			'userid'			=> $userid,
			'groupname'	=> $groupname,
			'groupdesc'		=> $_POST['groupdesc'],
			'isaudit'	=> $isaudit,
			'addtime'		=> time(),
		);
		
		$groupid = $db->insertArr($arrData,dbprefix.'group');
		
		//上传头像
		if (!empty($_FILES)) {
			$uptypes = array('jpg','gif','png');
			$menu2=intval($groupid/1000);
			$menu1=intval($menu2/1000);
			$menu = $menu1.'/'.$menu2;
			$dest_dir='uploadfile/group/'.$menu;
			createFolders($dest_dir);
			$arrType = explode('.',$_FILES['photo']['name']);
			$phototype = array_pop($arrType);
			if (in_array($phototype,$uptypes)) {
				$fileInfo=pathinfo($_FILES['photo']['name']);
				$extension=$fileInfo['extension'];
				$photoname = $groupid.'.'.$extension;
				$dest=$dest_dir.'/'.$photoname;
				move_uploaded_file($_FILES['photo']['tmp_name'],mb_convert_encoding($dest,"gb2312","UTF-8"));
				chmod($dest, 0755);
				$photo = $menu.'/'.$photoname;
				$db->query("update ".dbprefix."group set `path`='$menu',`groupicon`='$photo' where groupid='$groupid'");
			}
		}
		
		//绑定成员
		$db->query("insert into ".dbprefix."group_users (`userid`,`groupid`,`addtime`) values ('".$userid."','".$groupid."','".time()."')");
		
		//更新
		$db->query("update ".dbprefix."group set `count_user` = '1' where groupid='".$groupid."'");

		header("Location: ".SITE_URL."index.php?app=group&ac=group&groupid=".$groupid."");
		
	
		break;
}