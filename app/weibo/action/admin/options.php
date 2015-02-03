<?php
defined('IN_TS') or die('Access Denied.');
aac('system')->isLogin();

switch($ts){
	//基本配置
	case "":
		
		$arrOptions = $new['weibo']->findAll('weibo_options');

		foreach($arrOptions as $item){
			$strOption[$item['optionname']] = stripslashes($item['optionvalue']);
		}
		
		include template("admin/options");
		
		break;
		
	case "do":
	
		//先清空数据 
		$db->query("TRUNCATE TABLE `".dbprefix."weibo_options`");
	
		foreach($_POST['option'] as $key=>$item){
			
			$optionname = $key;
			$optionvalue = trim($item);
			
			$new['weibo']->create('weibo_options',array(
			
				'optionname'=>$optionname,
				'optionvalue'=>$optionvalue,
			
			));
		
		}
		
		$arrOptions = $new['weibo']->findAll('weibo_options',null,null,'optionname,optionvalue');
		foreach($arrOptions as $item){
			$arrOption[$item['optionname']] = $item['optionvalue'];
		}
		
		fileWrite('weibo_options.php','data',$arrOption);
		$tsMySqlCache->set('weibo_options',$arrOption);
		
		qiMsg('修改成功！');
	
		break;
}