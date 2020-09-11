<?php 
defined('IN_TS') or die('Access Denied.');

//插件编辑
switch($ts){
	case "set":

		$strAbout = fileRead('plugins/pubs/morenav/about.php');

		$arrNav = fileRead('data/plugins_pubs_morenav.php');
		
		if($arrNav==''){
			$arrNav = $GLOBALS['tsMySqlCache']->get('plugins_pubs_morenav');
		}

        include template('edit_set','morenav');
		break;
		
	case "do":
		$arrNavName = $_POST['navname'];
		$arrNavUrl = $_POST['navurl'];
		$arrNewPage = $_POST['newpage'];

		foreach($arrNavName as $key=>$item){
			$navname = trim($item);
			$navurl = trim($arrNavUrl[$key]);
			$newpage = trim($arrNewPage[$key]);
			if($navname && $navurl){
				$arrNav[] = array(
					'navname'	=> $navname,
					'navurl'	=> $navurl,
					'newpage'	=> $newpage,
				);
			}
			
		}
		
		fileWrite('plugins_pubs_morenav.php','data',$arrNav);
		$tsMySqlCache->set('plugins_pubs_morenav',$arrNav);
		
		header('Location: '.SITE_URL.'index.php?app=pubs&ac=plugin&plugin=morenav&in=edit&ts=set');
		break;
}