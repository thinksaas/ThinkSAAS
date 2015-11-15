<?php
defined('IN_TS') or die('Access Denied.');

//插件编辑
switch($ts){
	case "set":
		$arrData = fileRead('data/plugins_pubs_qq.php');
		
		if($arrData==''){
			$arrData = $tsMySqlCache->get('plugins_pubs_qq');
		}
		
		include 'html/edit.html';
		break;
		
	case "do":
		$appid = trim($_POST['appid']);
		$appkey = trim($_POST['appkey']);
		$siteurl = $_POST['siteurl'];
		
		$arrData = array(
			'appid' => $appid,
			'appkey'	=>$appkey,
			'siteurl'	=> $siteurl,
		);
		
		fileWrite('plugins_pubs_qq.php','data',$arrData);
		$tsMySqlCache->set('plugins_pubs_qq',$arrData);
		
		header('Location: '.SITE_URL.'index.php?app=pubs&ac=plugin&plugin=qq&in=edit&ts=set');
		break;
}