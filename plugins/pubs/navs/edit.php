<?php 
defined('IN_TS') or die('Access Denied.');

//插件编辑
switch($ts){
	case "set":

		$strAbout = fileRead('plugins/pubs/navs/about.php');

		$arrNav = fileRead('data/plugins_pubs_navs.php');
		
		if($arrNav==''){
			$arrNav = $tsMySqlCache->get('plugins_pubs_navs');
		}

        include template('edit_set','navs');
		break;
		
	case "do":
		$arrNavName = $_POST['navname'];
		$arrNavUrl = $_POST['navurl'];
		$arrNewPage = $_POST['newpage'];

		foreach($arrNavName as $key=>$item){
			$navname = tsTrim($item);
			$navurl = tsTrim($arrNavUrl[$key]);
			$newpage = tsTrim($arrNewPage[$key]);
			if($navname && $navurl){
				$arrNav[] = array(
					'navname'	=> $navname,
					'navurl'	=> $navurl,
					'newpage'	=> $newpage,
				);
			}
			
		}
		
		fileWrite('plugins_pubs_navs.php','data',$arrNav);
		$tsMySqlCache->set('plugins_pubs_navs',$arrNav);
		
		header('Location: '.SITE_URL.'index.php?app=pubs&ac=plugin&plugin=navs&in=edit&ts=set');
		break;
}