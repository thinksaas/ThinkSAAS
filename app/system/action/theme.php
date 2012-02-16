<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "":
		$arrTheme	= dirList('theme');
		$title = '系统主题';
		include template("theme");
		break;
		
	case "do":
	
		$site_theme = $_POST['site_theme'];
		
		$db->update('system_options',array(
			'optionvalue'=>$site_theme,
		),array(
			'optionname'=>'site_theme',
		));
		
		
		$arrOptions = $db->findAll("select optionname,optionvalue from ".dbprefix."system_options");
		foreach($arrOptions as $item){
				$arrOption[$item['optionname']] = $item['optionvalue'];
		}
		
		fileWrite('system_options.php','data',$arrOption);
		
		qiMsg("系统主题更换成功！");
	
		break;
}