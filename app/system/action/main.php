<?php
defined('IN_TS') or die('Access Denied.');


$os = explode(" ", php_uname());
if(!function_exists("gd_info")){$gd = '不支持,无法处理图像';}
if(function_exists("gd_info")) {  $gd = gd_info();  $gd = $gd["GD Version"];  $gd ? '&nbsp; 版本：'.$gd : '';}

				  
$systemInfo = array(
	'server'	=> $_SERVER['SERVER_SOFTWARE'],
	'phpos'	=> PHP_OS,
	'phpversion'	=> PHP_VERSION,
	'mysql'	=> $db->getMysqlVersion(),
	'os' =>$os[0] .''.$os[1].' '.$os[3],
	'gd'=>$gd,
	'upload' =>'表单允许 (post_max_size) '.ini_get('post_max_size').',上传总大小 (upload_max_filesize) '.ini_get('upload_max_filesize'),
	'memory'=> 'memory_limit '.ini_get('memory_limit'),
);

//获取域名
#$theAuthUrl = GetUrlToDomain($_SERVER['HTTP_HOST']);

include template("main");