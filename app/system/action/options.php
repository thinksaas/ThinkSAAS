<?php
defined('IN_TS') or die('Access Denied.');


switch($ts){

	case "":
		
		$arrOptions = $new['system']->findAll('system_options');

		foreach($arrOptions as $item){
			$strOption[$item['optionname']] = stripslashes($item['optionvalue']);
		}

		//时区和语言
		$arrTime = fileRead('data/system_timezone.php');
		if($arrTime==''){
			$arrTime = $tsMySqlCache->get('system_timezone');
		}
		
		$arrTheme	= tsScanDir('theme');

		include template("options");
	
		break;
		
	//保存配置
	
	case "do":
	
		$strLogo = $new['system']->find('system_options',array(
			'optionname'=>'logo',
		));
	
		//先清空数据 
		$db->query("TRUNCATE TABLE `".dbprefix."system_options`");
	
		foreach($_POST['option'] as $key=>$item){
			
			$optionname = $key;
			$optionvalue = trim($item);
			
			$new['system']->create('system_options',array(
			
				'optionname'=>$optionname,
				'optionvalue'=>$optionvalue,
			
			));
		
		}
		
		$new['system']->create('system_options',array(
			'optionname'=>'logo',
			'optionvalue'=>$strLogo['optionvalue'],
		));
	
		
		$arrOptions = $new['system']->findAll('system_options',null,null,'optionname,optionvalue');
		foreach($arrOptions as $item){
			$arrOption[$item['optionname']] = $item['optionvalue'];
		}
		
		fileWrite('system_options.php','data',$arrOption);
		$tsMySqlCache->set('system_options',$arrOption);
		
		
		//生成伪静态文件
		if($_POST['option']['site_urltype'] == 3 || $_POST['option']['site_urltype'] == 4 || $_POST['option']['site_urltype'] == 5 || $_POST['option']['site_urltype'] == 6 || $_POST['option']['site_urltype'] == 7){
		
			$scriptName = explode('index.php',$_SERVER['SCRIPT_NAME']);
			
			//生成.htaccess文件
			$fp =  fopen(THINKROOT.'/.htaccess','w');
			
			if(!is_writable(THINKROOT.'/.htaccess')) qiMsg("文件(.htaccess)不可写。如果您使用的是Unix/Linux主机，请修改该文件的权限为777。如果您使用的是Windows主机，请联系管理员，将此文件设为everyone可写");
			$htaccess = "RewriteEngine On\n"
					."RewriteBase ".$scriptName[0]."\n"
					."RewriteRule ^index\.php$ - [L]\n"
					."RewriteCond %{REQUEST_FILENAME} !-f\n"
					."RewriteCond %{REQUEST_FILENAME} !-d\n"
					."RewriteRule . ".$scriptName[0]."index.php [L]\n"
					."RewriteCond %{REQUEST_METHOD} ^TRACE\n"
					."RewriteRule .* - [F]";
			
			$fw =  fwrite($fp,$htaccess);
			
		}
		
		//更新皮肤
		setcookie('tsTheme',$_POST['option']['site_theme']);
		
		qiMsg("系统选项更新成功，并重置了缓存文件^_^");
	
		break;

}