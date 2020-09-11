<?php
defined('IN_TS') or die('Access Denied.');

//插件编辑
switch($ts){
	case "set":

		$strAbout = fileRead('plugins/home/recommendtopic/about.php');
		
		$strData = fileRead('data/plugins_home_recommendtopic.php');
		if($strData==''){
			$strData = $GLOBALS['tsMySqlCache']->get('plugins_home_recommendtopic');
		}
		
		include template('edit_set','recommendtopic');
		break;
		
	case "do":
        $isrecommend = intval($_POST['isrecommend']);
        
        $strData = array(
            'isrecommend'=>$isrecommend,
        );

		fileWrite('plugins_home_recommendtopic.php','data',$strData);
		$tsMySqlCache->set('plugins_home_recommendtopic',$strData);
		
		header('Location: '.SITE_URL.'index.php?app=home&ac=plugin&plugin=recommendtopic&in=edit&ts=set');
		break;
}