<?php
	/* 
	 * 用户管理
	 */
	
	switch($ts){
	
		//用户列表
		case "list":
			$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
			$url = 'index.php?app=user&ac=admin&mg=user&ts=list&page=';
			$arrAllUser	= $new['user']->getAllUser($page,10);
			$userNum = $new['user']->getUserNum('userid','userid');
			$pageUrl = pagination($userNum, 10, $page, $url);

			include template("admin/user_list");
			break;
		
		//用户编辑
		case "edit":
			$userid = $_GET['userid'];
			$strUser = $new['user']->getOneUserByUserid($userid);
			
			include template("admin/user_edit");
			break;
		
		//用户查看 
		case "view":
			$userid = $_GET['userid'];
			
			$strUser = $new['user']->getOneUserByUserid($userid);
			
			include template("admin/user_view");
			break;
		
		//用户停用启用
		case "isenable":
			$userid = $_GET['userid'];
			$isenable = $_GET['isenable'];
			$db->query("update ".dbprefix."user_info set `isenable`='$isenable' where userid='$userid'");
			header("Location: ".SITE_URL."index.php?app=user&ac=admin&mg=user&ts=list");
			break;
	}