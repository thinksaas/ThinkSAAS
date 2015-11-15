<?php
defined('IN_TS') or die('Access Denied.');
//统计代码
function wordad(){
	global $tsMySqlCache;
echo '<style>
	.wordad{ overflow:hidden;background: none repeat scroll 0 0 #EAEAEA;border: 1px solid #DDDDDD;margin-bottom: 10px;overflow: hidden;padding:5px;}
	.wordad ul{}
	.wordad ul li{width:225px;float:left;margin:2px 5px;}
	</style>';
	$arrData = fileRead('data/plugins_pubs_wordad.php');
	if($arrData==''){
		$arrData = $tsMySqlCache->get('plugins_pubs_wordad');
	}
	
	echo '<div class="wordad"><ul>';
	foreach($arrData as $key=>$item){
		echo '<li><a rel="nofollow" target="_blank" href="'.$item['url'].'">'.$item['title'].'</a></li>';
	}
	echo '</ul><div class="clear"></div></div>';
}
addAction('wordad','wordad');