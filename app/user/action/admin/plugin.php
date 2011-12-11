<?php 
switch($ts){
	//插件列表
	case "list":
		$arrPlugins = dirList('plugins/'.$app);
	
		foreach($arrPlugins as $key=>$item){
			$arrPlugin[$key]['name'] = $item;
			$arrPlugin[$key]['about'] = require_once 'plugins/'.$app.'/'.$item.'/about.php';
		}
		
		include template("admin/plugin_list");
		break;
	
	//插件停启用
	case "do":
		$isused =  intval($_GET['isused']);
		$pname = $_GET['pname'];
		//0停用1启用
		if($isused == '0'){
		
			$pkey = array_search($pname,$active_plugins);
			unset($active_plugins[$pkey]);
			
			fileWrite('user_plugins.php','data',$active_plugins);
			
			qiMsg("插件停用成功！");
			
		}elseif($isused == '1'){
		
			array_push($active_plugins,$pname);
			fileWrite('user_plugins.php','data',$active_plugins);
			qiMsg("插件启用成功！");
		
		}
		
		break;
}