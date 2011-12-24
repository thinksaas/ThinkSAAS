<?php
defined('IN_TS') or die('Access Denied.');
/**
 * @copyright (c) ThinkSAAS All Rights Reserved
 * @code by QiuJun
 * @Email:thinksaas@qq.com
 * @site:www.thinksaas.cn
 */
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
@set_magic_quotes_runtime(0);

session_start();

//前台用户基本数据,$TS_USER数组
$TS_USER = array(
	'user'		=> isset($_SESSION['tsuser']) ? $_SESSION['tsuser'] : '',
	'admin'		=> isset($_SESSION['tsadmin']) ? $_SESSION['tsadmin'] : '',
);

//加载基础函数
require_once 'fun.base.php';

//开始处理url路由
reurl();

//处理过滤
if (!get_magic_quotes_gpc()) {     
	Add_S($_POST);
	Add_S($_GET);
}

function Add_S(&$array){
	if (is_array($array)) {        
		foreach ($array as $key => $value) {             
			if (!is_array($value)) {                 
				$array[$key] = addslashes($value);             
			} else {                 
				Add_S($array[$key]);             
			}        
		}
	} 
}


$app = isset($_GET['app']) ? $_GET['app'] : 'home';
$ac = isset($_GET['ac']) ? $_GET['ac'] : 'index';

//安装
$install = isset($_GET['install']) ? $_GET['install'] : 'index';

//ThinkSAAS专用
$ts	= isset($_GET['ts']) ? $_GET['ts'] : '';
//Admin管理专用
$mg	= isset($_GET['mg']) ? $_GET['mg'] : 'index';
//Api专用
$api	= isset($_GET['api']) ? $_GET['api'] : 'index';

//plugin专用
$plugin = isset($_GET['plugin']) ? $_GET['plugin'] : '';
//plugin专用
$in = isset($_GET['in']) ? $_GET['in'] : '';

//语言包专用
$hl = isset($_GET['hl']) ? $_GET['hl'] : 'zh_cn';

header('Content-Type: text/html; charset=UTF-8');

//加载软件信息
$TS_SOFT['info'] = array(
	'name'	=>	'ThinkSAAS',
	'version'	=>	'1.6',
	'url'		=>	'http://www.thinksaas.cn/',
	'email'	=>	'thinksaas@qq.com',
	'copyright' =>	'ThinkSAAS',
	'year'		=>	'2009 - 2011',
	'author'	=>	'QiuJun',
);