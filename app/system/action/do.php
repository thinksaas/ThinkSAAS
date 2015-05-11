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
			'isface'	=> intval($_POST['isface']),
			'isinvite'=> intval($_POST['isinvite']),
			'isgzip'	=> intval($_POST['isgzip']),
			'timezone'	=> $_POST['timezone'],
			'isverify'=>intval($_POST['isverify']),
		);
	
		foreach ($arrData as $key => $val){

			$new['system']->update('system_options',array(
				'optionname'=>$key,
			),array(
				'optionvalue'=>$val,
			));
			
		}
		
		$arrOptions = $new['system']->findAll('system_options',null,null,'optionname,optionvalue');
		foreach($arrOptions as $item){
				$arrOption[$item['optionname']] = $item['optionvalue'];
		}
		
		fileWrite('system_options.php','data',$arrOption);
		$tsMySqlCache->set('system_options',$arrOption);
		
		qiMsg("系统选项更新成功，并重置了缓存文件^_^");
		
		break;
}