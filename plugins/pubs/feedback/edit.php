<?php
defined('IN_TS') or die('Access Denied.');

//插件编辑
switch($ts){
	case "set":
		$code = fileRead('data/plugins_pubs_feedback.php');
		
		if($code==''){
			$code = $tsMySqlCache->get('plugins_pubs_feedback');
		}
		
		$code = stripslashes($code);

        include template('edit_set','feedback');
		break;
		
	case "do":
		$code = trim($_POST['code']);
		
		fileWrite('plugins_pubs_feedback.php','data',$code);
		$tsMySqlCache->set('plugins_pubs_feedback',$code);
		
		header('Location: '.SITE_URL.'index.php?app=pubs&ac=plugin&plugin=feedback&in=edit&ts=set');
		break;
}