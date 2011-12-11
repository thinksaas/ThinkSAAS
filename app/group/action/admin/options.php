<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 配置选项
 */	

switch($ts){
	//基本配置
	case "":
		$arrOptions = $db->fetch_all_assoc("select * from ".dbprefix."group_options");
		foreach($arrOptions as $item){
			$strOption[$item['optionname']] = $item['optionvalue'];
		}
		
		include template("admin/options");
		
		break;
		
	case "do":
		$arrData = array(
			'appname' => trim($_POST['appname']),
			'appdesc' => trim($_POST['appdesc']),
			'isenable' => trim($_POST['isenable']),
			'iscreate' => trim($_POST['iscreate']),
			'isaudit' => trim($_POST['isaudit']),
			'isrewrite' => trim($_POST['isrewrite']),
			'iscate' => trim($_POST['iscate']),
		);
	
		foreach ($arrData as $key => $val){
			$db->query("UPDATE ".dbprefix."group_options SET optionvalue='$val' where optionname='$key'");
		}
		
		//更新缓存
		$arrOptions = $db->fetch_all_assoc("select optionname,optionvalue from ".dbprefix."group_options");
		foreach($arrOptions as $item){
			$arrOption[$item['optionname']] = $item['optionvalue'];
		}
		
		fileWrite('group_options.php','data',$arrOption);
		
		qiMsg("小组配置修改成功！");
		
		//header("Location: ".SITE_URL."index.php?app=group&ac=admin&mg=options");
		break;
}