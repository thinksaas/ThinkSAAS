<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 配置选项
 */	

switch($ts){
	//基本配置
	case "":
		$arrOptions = $db->findAll('apple_options');
		foreach($arrOptions as $item){
			$strOption[$item['optionname']] = $item['optionvalue'];
		}
		
		//列出模型
		$arrModel = $db->findAll('apple_model');
		
		include template("admin/options");
		
		break;
		
	case "do":
	
		$arrData = array(
			'appname' => trim($_POST['appname']),
			'appdesc' => trim($_POST['appdesc']),
			'isenable' => intval($_POST['isenable']),
			'modelid'	=> intval($_POST['modelid']),
		);

		foreach ($arrData as $key => $val){
			$db->query("UPDATE ".dbprefix."apple_options SET optionvalue='$val' where optionname='$key'");
		}
		
		//更新缓存
		$arrOptions = $db->findAll('apple_options','optionname,optionvalue');
		foreach($arrOptions as $item){
			$arrOption[$item['optionname']] = $item['optionvalue'];
		}
		
		fileWrite('apple_options.php','data',$arrOption);
		
		qiMsg("邮件配置更新成功，并重置了缓存文件^_^");
	
		break;
}