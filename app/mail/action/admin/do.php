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
			'ssl' => intval($_POST['ssl']),
			'mailport' => trim($_POST['mailport']),
			'mailuser' => trim($_POST['mailuser']),
			'mailpwd' => trim($_POST['mailpwd']),
		);

		//先清空数据 
		$db->query("TRUNCATE TABLE `".dbprefix."mail_options`");
		
		foreach($arrData as $key=>$item){
			
			$optionname = $key;
			$optionvalue = $item;
			
			$new['mail']->create('mail_options',array(
			
				'optionname'=>$optionname,
				'optionvalue'=>$optionvalue,
			
			));
		
		}
		
		//更新缓存
		$arrOptions = $new['mail']->findAll('mail_options',null,null,'optionname,optionvalue');
		foreach($arrOptions as $item){
			$arrOption[$item['optionname']] = $item['optionvalue'];
		}
		
		fileWrite('mail_options.php','data',$arrOption);
		$tsMySqlCache->set('mail_options',$arrOption);
		
		qiMsg("邮件配置更新成功，并重置了缓存文件^_^");
		
		break;
		 
}