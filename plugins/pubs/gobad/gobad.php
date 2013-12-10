<?php
defined('IN_TS') or die('Access Denied.');
//统计代码
function gobad($w){
	global $tsMySqlCache;
	$code = fileRead('data/plugins_pubs_gobad.php');
	
	if($code==''){
		$code = $tsMySqlCache->get('plugins_pubs_gobad');
	}
	
	echo stripslashes($code[$w]);

}

addAction('gobad','gobad');