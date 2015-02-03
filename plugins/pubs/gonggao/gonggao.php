<?php
defined('IN_TS') or die('Access Denied.');
//头部导航插件 
function gonggao(){
	global $tsMySqlCache;
	$strGonggao = fileRead('data/plugins_pubs_gogngao.php');
	
	if($strGonggao==''){
		$strGonggao = $tsMySqlCache->get('plugins_pubs_gonggao');
	}
	
	echo '<div class="gonggao">公告：<a target="_blank" href="'.$strGonggao['url'].'">'.$strGonggao['title'].'</a></div>';
	
}

addAction('my_right_top','gonggao');