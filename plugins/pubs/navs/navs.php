<?php
defined('IN_TS') or die('Access Denied.');
//头部导航插件 
function navs_html(){
	global $tsMySqlCache;
	$arrNav = fileRead('data/plugins_pubs_navs.php');
	
	if($arrNav==''){
		$arrNav = $tsMySqlCache->get('plugins_pubs_navs');
	}
	
	foreach($arrNav as $item){
		echo '<li class="mainlevel"><a href="'.$item['navurl'].'">'.$item['navname'].'</a></li>'; 
	}
}

addAction('pub_header_nav','navs_html');