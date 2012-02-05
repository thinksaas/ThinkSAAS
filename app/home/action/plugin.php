<?php
//插件条件入口
defined('IN_TS') or die('Access Denied.');

if(is_file('plugins/'.$app.'/'.$plugin.'/'.$in.'.php')){
	require_once('plugins/'.$app.'/'.$plugin.'/'.$in.'.php');
}else{
	qiMsg('sorry:no plugin!');
}