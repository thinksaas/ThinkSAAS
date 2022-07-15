<?php
defined('IN_TS') or die('Access Denied.');
//插件编辑
switch($ts){
	//编辑
	case "set":

		$strAbout = fileRead('plugins/pubs/wordad/about.php');

		$arrData = fileRead('data/plugins_pubs_wordad.php');
		if($arrData==''){
			$arrData = $tsMySqlCache->get('plugins_pubs_wordad');
		}

        include template('edit','wordad');
		break;
	//执行编辑
	case "do":
		$arrTitle = $_POST['title'];
		$arrUrl = $_POST['url'];
		foreach($arrTitle as $key=>$item){
			$title = tsTrim($item);
			$url = tsTrim($arrUrl[$key]);
			if($title && $url){
				$arrData[] = array(
					'title'	=> $title,
					'url'	=> $url,
				);
			}
		}
		
		fileWrite('plugins_pubs_wordad.php','data',$arrData);
		$tsMySqlCache->set('plugins_pubs_wordad',$arrData);
		
		header('Location: '.SITE_URL.'index.php?app=pubs&ac=plugin&plugin=wordad&in=edit&ts=set');
		break;
}