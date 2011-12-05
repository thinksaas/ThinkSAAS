<?php
defined('IN_TS') or die('Access Denied.');

//管理入口

if(is_file('app/'.$app.'/action/admin/'.$mg.'.php')){
	include_once 'app/'.$app.'/action/admin/'.$mg.'.php';
}else{
	qiMsg('sorry:no index!');
}