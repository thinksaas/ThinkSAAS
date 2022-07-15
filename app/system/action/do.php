<?php
defined('IN_TS') or die('Access Denied.');



switch($ts){
	case "options":
	
		$arrData = array(
			'site_title' => tsTrim($_POST['site_title']),
			'site_subtitle' => tsTrim($_POST['site_subtitle']),
			'site_key' => tsTrim($_POST['site_key']),
			'site_desc' => tsTrim($_POST['site_desc']),
			'site_url' => tsTrim($_POST['site_url']),
			'site_email' => tsTrim($_POST['site_email']),
			'site_icp' => tsTrim($_POST['site_icp']),
			'isface'	=> tsIntval($_POST['isface']),
			'isinvite'=> tsIntval($_POST['isinvite']),
			'isgzip'	=> tsIntval($_POST['isgzip']),
			'timezone'	=> $_POST['timezone'],
			'isverify'=>tsIntval($_POST['isverify']),
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