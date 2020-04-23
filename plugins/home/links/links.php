<?php
defined('IN_TS') or die('Access Denied.'); 
//友情连接插件

function links(){
	global $tsMySqlCache;
	$arrLink = fileRead('data/plugins_home_links.php');
	if($arrLink==''){
		$arrLink = $tsMySqlCache->get('plugins_home_links');
	}

	include template('links','links');

}

addAction('home_index_footer','links');