<?php
defined('IN_TS') or die('Access Denied.');

//插件编辑
switch($ts){
	case "set":

		$strAbout = fileRead('plugins/home/links/about.php');
		
		$arrLink = fileRead('data/plugins_home_links.php');
		if($arrLink==''){
			$arrLink = $tsMySqlCache->get('plugins_home_links');
		}
		
		include template('edit_set','links');
		break;
		
	case "do":
		$arrLinkName = $_POST['linkname'];
		$arrLinkUrl = $_POST['linkurl'];
		
		foreach($arrLinkName as $key=>$item){
			$linkname = tsTrim($item);
			$linkurl = tsTrim($arrLinkUrl[$key]);
			if($linkname && $linkurl){
				$arrLink[] = array(
					'linkname'	=> $linkname,
					'linkurl'	=> $linkurl,
				);
			}
			
		}
		
		fileWrite('plugins_home_links.php','data',$arrLink);
		$tsMySqlCache->set('plugins_home_links',$arrLink);
		
		header('Location: '.SITE_URL.'index.php?app=home&ac=plugin&plugin=links&in=edit&ts=set');
		break;
}