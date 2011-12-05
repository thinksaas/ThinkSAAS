<?php 
defined('IN_TS') or die('Access Denied.');
//判断目录可写
$f_cache =  iswriteable('cache');
$f_data = iswriteable('data');
$f_plugins =  iswriteable('plugins');
$f_uploadfile =  iswriteable('uploadfile');

$title = '升级程序';
include template('index');