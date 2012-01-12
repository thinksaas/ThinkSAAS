<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 系统设置
 */
	
$arrOptions = $db->fetch_all_assoc("select optionname,optionvalue from ".dbprefix."system_options");

foreach($arrOptions as $item){
	$strOption[$item['optionname']] = stripslashes($item['optionvalue']);
}

//时区和语言
$arrTime = fileRead('data/system_timezone.php');
$arrLang = fileRead('data/system_lang.php');

include template("options");