<?php
defined('IN_TS') or die('Access Denied.');
//插件条件入口
if(is_file('plugins/'.$app.'/'.$plugin.'/'.$in.'.php')){
	require_once('plugins/'.$app.'/'.$plugin.'/'.$in.'.php');
}else{
	qiMsg('sorry:no plugin!');
}

//形如这样
//index.php?app=group&ac=plugin&plugin=qq&in=do