<?php 
defined('IN_TS') or die('Access Denied.');
switch($ts){
	//插件列表
	case "list":
	
		$arrApps = tsScanDir('plugins');
	
		$apps = $_GET['apps'];
	
		$arrPlugins = tsScanDir('plugins/'.$apps);
	
		foreach($arrPlugins as $key=>$item){
			if(is_file('plugins/'.$apps.'/'.$item.'/about.php')){
				$arrPlugin[$key]['name'] = $item;
				$arrPlugin[$key]['about'] = require_once 'plugins/'.$apps.'/'.$item.'/about.php';
			}
		}
		
		$app_plugins = fileRead('data/'.$apps.'_plugins.php');
		
		include template("plugin_list");
		break;
	
	//插件停启用
	case "do":
	
		$apps = $_GET['apps'];
		
		$isused =  intval($_GET['isused']);
		$pname = $_GET['pname'];
		
		$app_plugins = fileRead('data/'.$apps.'_plugins.php');

		
		//0停用1启用
		if($isused == '0'){
		
			$pkey = array_search($pname,$app_plugins);
			unset($app_plugins[$pkey]);

			fileWrite($apps.'_plugins.php','data',$app_plugins);
			
			qiMsg("插件停用成功！");
			
		}elseif($isused == '1'){
		
			array_push($app_plugins,$pname);
			if(file_exists('plugins/'.$apps.'/'.$pname.'/install.sql')){
				$sql=file_get_contents('plugins/'.$apps.'/'.$pname.'/install.sql');
				$sql=str_replace('ts_',''.dbprefix.'',$sql);
				$ret=$db->query($sql);
				 if($ret=='1')
				 {
					fileWrite($apps.'_plugins.php','data',$app_plugins);
					$msg='插件启用成功！';
				 }else{
					 $msg=$ret;
					 }
				}else{
					fileWrite($apps.'_plugins.php','data',$app_plugins);
					$msg='插件启用成功！';
				}
			
			qiMsg($msg);
		
		}
		break;
		
	//删除插件
	case "del":
		$pname = $_GET['pname'];
		if(file_exists('plugins/'.$apps.'/'.$pname.'/uninstall.sql')){
				$sql=file_get_contents('plugins/'.$apps.'/'.$pname.'/uninstall.sql');
				$sql=str_replace('ts_',''.dbprefix.'',$sql);
				$ret=$db->query($sql);
				 if(mysql_fetch_array($ret))
				 {
					delDir('plugins/'.$apps.'/'.$pname);
					$msg='插件卸载成功！';
				 }else{
					 $msg=$ret;
					 }
				}else{
					delDir('plugins/'.$apps.'/'.$pname);
					$msg='插件删除成功！';
				}
		
		qiMsg($msg);
		break;
		
		
	//插件编辑
	case "edit":
		
		break;
}