<?php 
defined('IN_TS') or die('Access Denied.');

// 清空SESSION
unset ( $_SESSION ['tsuser'] );
session_destroy ();
setcookie ( "ts_email", '', time () + 3600, '/' );
setcookie ( "ts_autologin", '', time () + 3600, '/' );

//判断目录可写
$f_cache =  iswriteable('cache');
$f_data = iswriteable('data');
$f_plugins =  iswriteable('plugins');
$f_tslogs = iswriteable('tslogs');
$f_upgrade = iswriteable('upgrade');
$f_uploadfile =  iswriteable('uploadfile');


//扫描SQL文件
$arrSqls = tsScanDir('upgrade',1);
foreach($arrSqls as $key=>$item){
	if($item!='up.php'){
		$arrSql[] = str_replace('.sql','',$item);
	}
}

$title = '升级程序';
include template('index');