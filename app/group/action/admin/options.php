<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	//基本配置
	case "":
		
		$arrOptions = $new['group']->findAll('group_options');

		foreach($arrOptions as $item){
			$strOption[$item['optionname']] = stripslashes($item['optionvalue']);
		}
		
		include template("admin/options");
		
		break;
		
	case "do":
	
		//先清空数据 
		$db->query("TRUNCATE TABLE `".dbprefix."group_options`");
	
		foreach($_POST['option'] as $key=>$item){
			
			$optionname = $key;
			$optionvalue = trim($item);
			
			$new['group']->create('group_options',array(
			
				'optionname'=>$optionname,
				'optionvalue'=>$optionvalue,
			
			));
		
		}
		
		$arrOptions = $new['group']->findAll('group_options',null,null,'optionname,optionvalue');
		foreach($arrOptions as $item){
			$arrOption[$item['optionname']] = $item['optionvalue'];
		}
		
		fileWrite('group_options.php','data',$arrOption);
		$tsMySqlCache->set('group_options',$arrOption);
		
		qiMsg('修改成功！');
	
		break;
}