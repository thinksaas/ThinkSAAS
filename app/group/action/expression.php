<?php
defined('IN_TS') or die('Access Denied.');
//表情
for($i==1;$i<=24;$i++){
	if($i!=''){
	$arrExpress[$i] = 'data/expression/'.$i.'.gif';
	}
}

include template("expression");