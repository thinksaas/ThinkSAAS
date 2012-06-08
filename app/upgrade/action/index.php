<?php 
defined('IN_TS') or die('Access Denied.');
//判断目录可写
$f_cache =  iswriteable('cache');
$f_data = iswriteable('data');
$f_plugins =  iswriteable('plugins');
$f_uploadfile =  iswriteable('uploadfile');
$f_update = iswriteable('update');

//扫描SQL文件
$arrSqls = tsScanDir('update',1);
foreach($arrSqls as $key=>$item){
	$arrSql[] = str_replace('.sql','',$item);
}

$title = '升级程序';
include template('index');