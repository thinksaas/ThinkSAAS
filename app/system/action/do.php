<?php
defined('IN_TS') or die('Access Denied.');
switch($ts){
	case "options":
		$arrData = array(
			'site_title' => trim($_POST['site_title']),
			'site_subtitle' => trim($_POST['site_subtitle']),
			'site_key' => trim($_POST['site_key']),
			'site_desc' => trim($_POST['site_desc']),
			'site_url' => trim($_POST['site_url']),
			'site_email' => trim($_POST['site_email']),
			'site_icp' => trim($_POST['site_icp']),
			'isface'	=> $_POST['isface'],
			'isgzip'	=> $_POST['isgzip'],
			'timezone'	=> $_POST['timezone'],
			'lang'	=> $_POST['lang'],
		);
	
		foreach ($arrData as $key => $val){
			$db->query("UPDATE ".dbprefix."system_options SET optionvalue='$val' where optionname='$key'");
		}
		
		$arrOptions = $db->fetch_all_assoc("select optionname,optionvalue from ".dbprefix."system_options");
		foreach($arrOptions as $item){
				$arrOption[$item['optionname']] = $item['optionvalue'];
		}
		
		fileWrite('system_options.php','data',$arrOption);
		
		qiMsg("系统选项更新成功，并重置了缓存文件^_^");
		
		break;
}