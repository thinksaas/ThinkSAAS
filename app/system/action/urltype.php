<?php 
defined('IN_TS') or die('Access Denied.');



switch($ts){
	case "":
		$title = '链接形式';
		include template("urltype");
		break;
		
	case "do":
	
		$site_urltype = $_POST['site_urltype'];
		
		$db->query("update ".dbprefix."system_options set `optionvalue`='$site_urltype' where optionname='site_urltype'");
		
		$arrOptions = $db->fetch_all_assoc("select optionname,optionvalue from ".dbprefix."system_options");
		foreach($arrOptions as $item){
				$arrOption[$item['optionname']] = $item['optionvalue'];
		}
		
		fileWrite('system_options.php','cache',$arrOption);
		
		if($site_urltype == 4){
		
			$scriptName = explode('index.php',$_SERVER['SCRIPT_NAME']);
			
			//生成.htaccess文件
			$fp =  fopen(THINKROOT.'/.htaccess','w');
			
			if(!is_writable(THINKROOT.'/.htaccess')) qiMsg("文件(.htaccess)不可写。如果您使用的是Unix/Linux主机，请修改该文件的权限为777。如果您使用的是Windows主机，请联系管理员，将此文件设为everyone可写");
			$htaccess = "RewriteEngine On\n"
					."RewriteBase ".$scriptName[0]."\n"
					."RewriteRule ^index\.php$ - [L]\n"
					."RewriteCond %{REQUEST_FILENAME} !-f\n"
					."RewriteCond %{REQUEST_FILENAME} !-d\n"
					."RewriteRule . ".$scriptName[0]."index.php [L]\n";
			
			$fw =  fwrite($fp,$htaccess);
			
		}
		
		qiMsg("链接形式更换成功！");
		
		break;
}