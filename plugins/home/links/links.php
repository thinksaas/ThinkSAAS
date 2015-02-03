<?php
defined('IN_TS') or die('Access Denied.'); 
//友情连接插件

function links_html(){
	global $tsMySqlCache;
	$arrLink = fileRead('data/plugins_home_links.php');
	if($arrLink==''){
		$arrLink = $tsMySqlCache->get('plugins_home_links');
	}
	
	echo '<div class="clear"></div>';
	echo '<div class="bbox">';
	echo '<div class="btitle">友情链接</div>';
	echo '<div class="bc links">';
	foreach($arrLink as $item){
		echo '<a target="_blank" href="'.$item['linkurl'].'">'.$item['linkname'].'</a> '; 
	}
	echo '</div></div>';
}

addAction('home_index_footer','links_html');