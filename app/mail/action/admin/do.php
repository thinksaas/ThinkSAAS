<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	//APP配置选项
	case "options":
		$arrData = array(
			'appname' => trim($_POST['appname']),
			'appdesc' => trim($_POST['appdesc']),
			'isenable' => trim($_POST['isenable']),
			'mailhost' => trim($_POST['mailhost']),
			'mailport' => trim($_POST['mailport']),
			'mailuser' => trim($_POST['mailuser']),
			'mailpwd' => trim($_POST['mailpwd']),
		);

		foreach ($arrData as $key => $val){
			$db->query("UPDATE ".dbprefix."mail_options SET optionvalue='$val' where optionname='$key'");
		}
		
		//更新缓存
		$arrOptions = $db->fetch_all_assoc("select optionname,optionvalue from ".dbprefix."mail_options");
		foreach($arrOptions as $item){
			$arrOption[$item['optionname']] = $item['optionvalue'];
		}
		
		fileWrite('mail_options.php','data',$arrOption);
		$tsMySqlCache->set('mail_options',$arrOption);
		
		qiMsg("邮件配置更新成功，并重置了缓存文件^_^");
		
		break;
		 
}