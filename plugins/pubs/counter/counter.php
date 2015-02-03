<?php
defined('IN_TS') or die('Access Denied.');
//统计代码
function counter_html(){
	global $tsMySqlCache;
	$code = fileRead('data/plugins_pubs_counter.php');
	
	if($code==''){
		$code = $tsMySqlCache->get('plugins_pubs_counter');
	}
	
	$code = stripslashes($code);
	
	echo '<div style="display:none;">';
	echo $code;
	echo '</div>';
}

addAction('pub_footer','counter_html');