<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 配置选项
 */	

$arrOptions = $db->findAll("select * from ".dbprefix."mail_options");

foreach($arrOptions as $item){
	$strOption[$item['optionname']] = $item['optionvalue'];
}

include template("admin/options");