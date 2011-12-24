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
	
		if(intval($_POST['ismode'])=='1'){
			//判断默认小组是否存在
			$oneGroupNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."group where `groupid`='1'");
			
			//计算小组数 
			$groupNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."group");
			
			if($oneGroupNum['count(*)'] == 0 || $groupNum['count(*)'] > 1){
				qiMsg("默认小组不存在或者你的小组数已经多余1个，你不能使用单小组模式！");
			}
			
		}
	
		$arrData = array(
			'appname' => trim($_POST['appname']),
			'appdesc' => trim($_POST['appdesc']),
			'isenable' => trim($_POST['isenable']),
			'iscreate' => trim($_POST['iscreate']),
			'isaudit' => trim($_POST['isaudit']),
			'iscate' => trim($_POST['iscate']),
			'ismode'=> trim($_POST['ismode']),
			
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