<?php
defined('IN_TS') or die('Access Denied.');
/**
 * @copyright (c) ThinkSAAS All Rights Reserved
 * @code by QiuJun
 * @Email:thinksaas@qq.com
 * @site:www.thinksaas.cn
 */
if (substr(PHP_VERSION, 0, 1) != '5')exit("ThinkSAAS运行环境要求PHP5！");

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

@set_magic_quotes_runtime(0);

//核心配置文件 $TS_CF 系统配置变量
$TS_CF = require_once 'config.php';

//启动Memcache
if($TS_CF['memcache'] && extension_loaded('memcache')){
	Memcache::connect($TS_CF['memcache']['host'], $TS_CF['memcache']['port']);  
}

//加载基础函数
require_once 'tsFunction.php';

//数据库存储SESSION  还有点问题，暂时不要开启
if($TS_CF['session']){
	require_once 'tsSession.php';
	ini_set('session.save_handler', 'user'); 
	session_set_save_handler(
		array('tsSession', 'open'), 
		array('tsSession', 'close'), 
		array('tsSession', 'read'), 
		array('tsSession', 'write'), 
		array('tsSession', 'destroy'), 
		array('tsSession', 'gc') 
	); 
}

session_start();

//前台用户基本数据,$TS_USER数组
$TS_USER = array(
	'user'		=> isset($_SESSION['tsuser']) ? $_SESSION['tsuser'] : '',
	'admin'		=> isset($_SESSION['tsadmin']) ? $_SESSION['tsadmin'] : '',
); 

//开始处理url路由
reurl();

//处理过滤
if (!get_magic_quotes_gpc()) {     
	Add_S($_POST);
	Add_S($_GET);
}

//系统Url参数变量
//APP专用
$app = isset($_GET['app']) ? $_GET['app'] : 'home';
//Action专用
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

//处理html编码
header('Content-Type: text/html; charset=UTF-8');

//安装配置文件，数据库配置判断
if(!is_file('data/config.inc.php')){
	require_once 'install/index.php';exit;
}

//APP语言包
require_once 'i18n/i18n.class.php';
$i18n = new i18n('app/'.$app.'/lang/{LANGUAGE}.ini', 'cache/langcache', 'en'); 
$i18n->init();

//数据库配置文件
require_once 'data/config.inc.php';
//连接数据库
require_once 'sql/'.$TS_DB['sql'].'.php';
$db = new MySql($TS_DB);

//加载APP数据库操作类并建立对象
require_once 'tsApp.php';

//除去加载内核运行时间统计开始
$time_start = getmicrotime();