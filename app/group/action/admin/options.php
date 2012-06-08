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
	
		$joinnum = intval($_POST['joinnum']);
	
		if($joinnum == 0) qiMsg('加入小组数不能为0！');
		
		if($joinnum > 100) qiMsg('加入小组数不能大于100个！');
	
		$arrData = array(
			'appname' => trim($_POST['appname']),
			'appdesc' => trim($_POST['appdesc']),
			'iscreate' => trim($_POST['iscreate']),
			'isaudit' => trim($_POST['isaudit']),
			'needcredit' => trim($_POST['needcredit']),
			'joinnum' => $joinnum,
			
			
		);
	
		foreach ($arrData as $key => $val){
			if($val!=''){
				$db->query("UPDATE ".dbprefix."group_options SET optionvalue='$val' where optionname='$key'");
			}
			
		}
		
		//更新缓存
		$arrOptions = $db->fetch_all_assoc("select optionname,optionvalue from ".dbprefix."group_options");
		foreach($arrOptions as $item){
			$arrOption[$item['optionname']] = $item['optionvalue'];
		}
		
		fileWrite('group_options.php','data',$arrOption);
		
		qiMsg("小组配置修改成功！");
		
		break;
}