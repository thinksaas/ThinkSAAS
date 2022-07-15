<?php
defined('IN_TS') or die('Access Denied.');

//插件编辑
switch($ts){
	case "set":

		$strAbout = fileRead('plugins/pubs/counter/about.php');

		$code = fileRead('data/plugins_pubs_counter.php');
		if($code==''){
			$code = $tsMySqlCache->get('plugins_pubs_counter');
		}
		$code = stripslashes($code);

        include template('edit_set','counter');
		break;
		
	case "do":
		$code = tsTrim($_POST['code']);
		
		fileWrite('plugins_pubs_counter.php','data',$code);
		$tsMySqlCache->set('plugins_pubs_counter',$code);
		
		header('Location: '.SITE_URL.'index.php?app=pubs&ac=plugin&plugin=counter&in=edit&ts=set');
		break;
}