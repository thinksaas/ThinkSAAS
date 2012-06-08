<?php  
defined('IN_TS') or die('Access Denied.');
//更多应用

$applists = tsScanDir('app');
foreach($applists as $key=>$item){
	if(is_file('app/'.$item.'/about.php')){
		$arrApps[$key]['name'] = $item;
		$arrApps[$key]['about'] = require_once 'app/'.$item.'/about.php';
	}
}

foreach($arrApps as $item){
	$arrApp[] = $item;
}

$title = 'APP应用';
include template('apps');