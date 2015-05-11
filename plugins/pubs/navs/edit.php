<?php 
defined('IN_TS') or die('Access Denied.');

//插件编辑
switch($ts){
	case "set":
		$arrNav = fileRead('data/plugins_pubs_navs.php');
		
		if($arrNav==''){
			$arrNav = $tsMySqlCache->get('plugins_pubs_navs');
		}
		
		include 'edit_set.html';
		break;
		
	case "do":
		$arrNavName = $_POST['navname'];
		$arrNavUrl = $_POST['navurl'];
		
		foreach($arrNavName as $key=>$item){
			$navname = trim($item);
			$navurl = trim($arrNavUrl[$key]);
			if($navname && $navurl){
				$arrNav[] = array(
					'navname'	=> $navname,
					'navurl'	=> $navurl,
				);
			}
			
		}
		
		fileWrite('plugins_pubs_navs.php','data',$arrNav);
		$tsMySqlCache->set('plugins_pubs_navs',$arrNav);
		
		header('Location: '.SITE_URL.'index.php?app=pubs&ac=plugin&plugin=navs&in=edit&ts=set');
		break;
}