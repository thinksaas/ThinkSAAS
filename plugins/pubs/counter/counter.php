<?php
defined('IN_TS') or die('Access Denied.');
//统计代码
function counter_html(){
	$code = fileRead('data.php','plugins','pubs','counter');
	
	$code = stripcslashes($code);
	
	echo $code;
}

addAction('pub_footer','counter_html');