<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 系统信息
 */

$os = explode(" ", php_uname());
if(!function_exists("gd_info")){$gd = '不支持,无法处理图像';}
if(function_exists(gd_info)) {  $gd = @gd_info();  $gd = $gd["GD Version"];  $gd ? '&nbsp; 版本：'.$gd : '';}

				  
$systemInfo = array(
	'server'	=> $_SERVER['SERVER_SOFTWARE'],
	'phpos'	=> PHP_OS,
	'phpversion'	=> PHP_VERSION,
	'mysql'	=> $db->getMysqlVersion(),
	'os' =>$os[0] .''.$os[1].' '.$os[3],
	'gd'=>$gd,
	'upload' =>'表单允许'.ini_get('post_max_size').',上传总大小'.ini_get('upload_max_filesize')
);

include template("main");