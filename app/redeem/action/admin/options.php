<?php
defined('IN_TS') or die('Access Denied.');


switch($ts){
	//基本配置
	case "":
		
		$arrOptions = $new['redeem']->findAll('redeem_options');

		foreach($arrOptions as $item){
			$strOption[$item['optionname']] = stripslashes($item['optionvalue']);
		}
		
		include template("admin/options");
		
		break;
		
	case "do":
	
		//先清空数据 
		$db->query("TRUNCATE TABLE `".dbprefix."redeem_options`");
	
		foreach($_POST['option'] as $key=>$item){
			
			$optionname = $key;
			$optionvalue = trim($item);
			
			$new['redeem']->create('redeem_options',array(
			
				'optionname'=>$optionname,
				'optionvalue'=>$optionvalue,
			
			));
		
		}
		
		$arrOptions = $new['redeem']->findAll('redeem_options',null,null,'optionname,optionvalue');
		foreach($arrOptions as $item){
			$arrOption[$item['optionname']] = $item['optionvalue'];
		}
		
		fileWrite('redeem_options.php','data',$arrOption);
		$tsMySqlCache->set('redeem_options',$arrOption);
		
		qiMsg('修改成功！');
	
		break;
}