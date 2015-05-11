<?php
defined('IN_TS') or die('Access Denied.');
	/*
	 *包含数据库配置文件
	 */
	$skin = 'default';
	$TS_APP['appname']	= '唠叨';
	require_once THINKDATA."/config.inc.php";
	
	/*
	$TS_APP['issql'] = 1;//是否有独立的数据库1有0无
	
	$TS_DB['sql']='mysql';
	$TS_DB['host']='localhost';
	$TS_DB['port']='3306';
	$TS_DB['user']='root';
	$TS_DB['pwd']='123456';
	$TS_DB['name']='weibo';
	$TS_DB['pre']='ts_';
	define('dbprefix','ts_');
	*/