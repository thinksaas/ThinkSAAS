<?php
defined('IN_TS') or die('Access Denied.');

//插件编辑
switch($ts){
	case "set":
		$tag = fileRead('data/plugins_home_tag.php');
		if($tag==''){
            $tag = $tsMySqlCache->get('plugins_home_tag');
		}

        include template('edit_set','tag');
		break;
		
	case "do":
		$tag = trim($_POST['tag']);
		fileWrite('plugins_home_tag.php','data',$tag);
		$tsMySqlCache->set('plugins_home_tag',$tag);
		
		header('Location: '.SITE_URL.'index.php?app=home&ac=plugin&plugin=tag&in=edit&ts=set');
		break;
}