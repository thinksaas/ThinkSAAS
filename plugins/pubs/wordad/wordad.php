<?php
defined('IN_TS') or die('Access Denied.');
//统计代码
function wordad(){
	global $tsMySqlCache;
	$arrData = fileRead('data/plugins_pubs_wordad.php');
	if($arrData==''){
		$arrData = $tsMySqlCache->get('plugins_pubs_wordad');
	}
	
	echo '<div class="panel panel-default"><div class="panel-body"><div class="row">';
	foreach($arrData as $key=>$item){
		echo '<div class="col-md-3"><a target="_blank" href="'.$item['url'].'">'.$item['title'].'</a></div>';
	}
	echo '</div></div></div>';
}
addAction('wordad','wordad');