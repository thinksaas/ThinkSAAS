<?php
defined('IN_TS') or die('Access Denied.');

//插件编辑
switch($ts){
	case "set":
		$code = fileRead('data/plugins_pubs_counter.php');
		if($code==''){
			$code = $tsMySqlCache->get('plugins_pubs_counter');
		}
		$code = stripslashes($code);
		
		include 'edit_set.html';
		break;
		
	case "do":
		$code = trim($_POST['code']);
		
		fileWrite('plugins_pubs_counter.php','data',$code);
		$tsMySqlCache->set('plugins_pubs_counter',$code);
		
		header('Location: '.SITE_URL.'index.php?app=pubs&ac=plugin&plugin=counter&in=edit&ts=set');
		break;
}