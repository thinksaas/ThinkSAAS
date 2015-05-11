<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "set":
		$arrData = fileRead('data/plugins_pubs_weibo.php');
		if($arrData==''){
			$arrData = $tsMySqlCache->get('plugins_pubs_weibo');
		}
		
		include 'html/edit.html';
		break;
		
	case "do":
		$wb_akey = trim($_POST['wb_akey']);
		$wb_skey = trim($_POST['wb_skey']);
		$siteurl = $_POST['siteurl'];
		
		$arrData = array(
			'wb_akey' => $wb_akey,
			'wb_skey'	=>$wb_skey,
			'siteurl'	=> $siteurl,
		);
		
		fileWrite('plugins_pubs_weibo.php','data',$arrData);
		$tsMySqlCache->set('plugins_pubs_weibo');
		
		header('Location: '.SITE_URL.'index.php?app=pubs&ac=plugin&plugin=weibo&in=edit&ts=set');
		break;
}
