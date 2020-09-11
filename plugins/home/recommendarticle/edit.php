<?php
defined('IN_TS') or die('Access Denied.');

//插件编辑
switch($ts){
	case "set":

		$strAbout = fileRead('plugins/home/recommendarticle/about.php');
		
		$strData = fileRead('data/plugins_home_recommendarticle.php');
		if($strData==''){
			$strData = $GLOBALS['tsMySqlCache']->get('plugins_home_recommendarticle');
		}
		
		include template('edit_set','recommendarticle');
		break;
		
	case "do":
        $isrecommend = intval($_POST['isrecommend']);
        
        $strData = array(
            'isrecommend'=>$isrecommend,
        );

		fileWrite('plugins_home_recommendarticle.php','data',$strData);
		$tsMySqlCache->set('plugins_home_recommendarticle',$strData);
		
		header('Location: '.SITE_URL.'index.php?app=home&ac=plugin&plugin=recommendarticle&in=edit&ts=set');
		break;
}