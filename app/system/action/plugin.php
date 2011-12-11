<?php 
switch($ts){
	//插件列表
	case "list":
		$arrPlugins = dirList('plugins/pubs');
	
		foreach($arrPlugins as $key=>$item){
			if(is_file('plugins/pubs/'.$item.'/about.php')){
				$arrPlugin[$key]['name'] = $item;
				$arrPlugin[$key]['about'] = require_once 'plugins/pubs/'.$item.'/about.php';
			}
		}
		
		include template("plugin_list");
		break;
	
	//插件停启用
	case "do":
		$isused =  intval($_GET['isused']);
		$pname = $_GET['pname'];
		//0停用1启用
		if($isused == '0'){
		
			$pkey = array_search($pname,$public_plugins);
			unset($public_plugins[$pkey]);

			fileWrite('pubs_plugins.php','data',$public_plugins);
			
			qiMsg("插件停用成功！");
			
		}elseif($isused == '1'){
		
			array_push($public_plugins,$pname);

			fileWrite('pubs_plugins.php','data',$public_plugins);
			qiMsg("插件启用成功！");
		
		}
		break;
		
	//删除插件
	case "del":
		$pname = $_GET['pname'];
		delDir('plugins/pubs/'.$pname);
		qiMsg("插件删除成功！");
		break;
}