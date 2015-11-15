<?php
defined('IN_TS') or die('Access Denied.');

//插件编辑
switch($ts){
	case "set":
		$arrData = fileRead('data/plugins_pubs_douban.php');
		
		if($arrData==''){
			$arrData = $tsMySqlCache->get('plugins_pubs_douban');
		}
		
		include 'html/edit.html';
		break;
		
	case "do":
		$key = trim($_POST['key']);
		$secret = trim($_POST['secret']);
		$siteurl = $_POST['siteurl'];
		
		$arrData = array(
			'key' => $key,
			'secret'	=>$secret,
			'siteurl'	=> $siteurl,
		);
		
		fileWrite('plugins_pubs_douban.php','data',$arrData);
		$tsMySqlCache->set('plugins_pubs_douban',$arrData);
		
		header('Location: '.SITE_URL.'index.php?app=pubs&ac=plugin&plugin=douban&in=edit&ts=set');
		break;
}