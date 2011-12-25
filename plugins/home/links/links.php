<?php
defined('IN_TS') or die('Access Denied.'); 
//友情连接插件

function links_html(){
	$arrLink = fileRead('data.php','plugins','home','links');
	
	echo '<div class="clear"></div>';
	echo '<h2>友情链接</h2>';
	echo '<div>';
	foreach($arrLink as $item){
		echo '<a target="_blank" href="'.$item['linkurl'].'">'.$item['linkname'].'</a> '; 
	}
	echo '</div>';
}

addAction('index_footer','links_html');