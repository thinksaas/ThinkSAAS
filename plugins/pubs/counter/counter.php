<?php
defined('IN_TS') or die('Access Denied.');
//统计代码
function counter_html(){
	$code = fileRead('plugins/pubs/counter/data.php');
	
	$code = stripcslashes($code);
	
	echo $code;
}

addAction('pub_footer','counter_html');