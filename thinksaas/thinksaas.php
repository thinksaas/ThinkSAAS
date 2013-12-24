<?php
defined('IN_TS') or die('Access Denied.');
/**
 * @copyright (c) ThinkSAAS All Rights Reserved
 * @code by QiuJun
 * @Email:thinksaas@qq.com
 * @site:www.thinksaas.cn
 */
if (substr(PHP_VERSION, 0, 1) != '5')exit("ThinkSAAS运行环境要求PHP5！");

//核心配置文件 $TS_CF 系统配置变量
$TS_CF = include THINKROOT.'/thinksaas/config.php';

// 如果是调试模式，打开警告输出
if ($TS_CF['debug']) {
	if( substr(PHP_VERSION, 0, 3) == "5.3" ){
		error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
	}else{
		error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
	}
} else {
	error_reporting(0);
}

@set_magic_quotes_runtime(0);

//ini_set("memory_limit","120M");

ini_set('display_errors', 'on');   //正式环境关闭错误输出

set_time_limit (0);

ini_set('session.cookie_path', '/');

//ini_set('session.save_path', THINKROOT.'/cache/sessions');

//加载基础函数
include 'tsFunction.php';

//开始计算程序执行时间
$time_start = getmicrotime();

//处理fileurl
if($TS_CF['fileurl']['url']){
	if($_SERVER['HTTP_HOST']==$TS_CF['fileurl']['url']){
		echo '404 page';exit;
	}
}

//数据库存储SESSION  还有点问题，暂时不要开启
if($TS_CF['session']){
	include 'tsSession.php';
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

//启动Memcache
if($TS_CF['memcache'] && extension_loaded('memcache')){
	Memcache::connect($TS_CF['memcache']['host'], $TS_CF['memcache']['port']);  
}

//加密用户操作
if(!isset($_SESSION['token'])){
	$_SESSION['token'] = sha1(uniqid(mt_rand(),TRUE));
}

if($_REQUEST['token']){
	if(tsFilter($_REQUEST['token']) != $_SESSION['token']) {
		tsNotice('非法操作！');
	}
}

//前台用户基本数据,$TS_USER数组
$TS_USER = array(
	'user'		=> isset($_SESSION['tsuser']) ? $_SESSION['tsuser'] : '',
	'admin'		=> isset($_SESSION['tsadmin']) ? $_SESSION['tsadmin'] : '',
); 

//记录日志
if($TS_CF['logs']){
	//打印用户日志记录
	userlog($_POST,intval($TS_USER['user']['userid']));
	userlog($_GET,intval($TS_USER['user']['userid']));
}

//处理html编码
header('Content-Type: text/html; charset=UTF-8');

//安装专用变量
$install = isset($_GET['install']) ? $_GET['install'] : 'index';


//安装配置文件，数据库配置判断
if(!is_file('data/config.inc.php')){
	include 'install/index.php';
	exit;
}

//数据库配置文件
include 'data/config.inc.php';

//连接数据库
include 'sql/'.$TS_DB['sql'].'.php';
$db = new MySql($TS_DB);

//加载APP数据库操作类并建立对象
include 'thinksaas/tsApp.php';

//MySQL数据库缓存
include 'thinksaas/tsMySqlCache.php';
$tsMySqlCache = new tsMySqlCache($db);

//开始处理url路由，支持APP二级域名
if($TS_CF['subdomain']){
	ini_set("session.cookie_domain",'.'.$TS_CF['subdomain']['domain']);
	
	//APP独立域名支持
	if(array_search($_SERVER['HTTP_HOST'], $TS_CF['appdomain'])){
		reurlsubdomain();
	}else{
		$arrHost	= explode('.',$_SERVER['HTTP_HOST']);
		if($arrHost[0]=='www'){
			reurl();
		}else{
			reurlsubdomain();
		}
	}
}else{
	reurl();
}

//系统Url参数变量
//APP专用
$app = isset($_GET['app']) ? tsFilter($_GET['app']) : 'home';

//APP二级域名支持，同时继续支持url原生写法
if($TS_CF['subdomain'] && $app=='home'){
	//APP独立域名支持
	$app = array_search($_SERVER['HTTP_HOST'], $TS_CF['appdomain']);
	if($app==''){
		//二级域名支持
		$arrHost	= explode('.',$_SERVER['HTTP_HOST']);
		$app			= $arrHost['0'];
		if($app == 'www'){
			$app='home';
		}
	}
}

//判断magic_quotes_gpc状态 
if(!get_magic_quotes_gpc()) { 
	$_GET = tsgpc ( $_GET ); 
	$_POST = tsgpc ( $_POST ); 
	$_COOKIE = tsgpc ( $_COOKIE ); 
	//$_FILES = tsgpc ( $_FILES ); 
} 
//$_SERVER = tsgpc ( $_SERVER ); 

//Action专用
$ac = isset($_GET['ac']) ? tsFilter($_GET['ac']) : 'index';
//ThinkSAAS专用
$ts	= isset($_GET['ts']) ? tsFilter($_GET['ts']) : '';
//Admin管理专用
$mg	= isset($_GET['mg']) ? tsFilter($_GET['mg']) : 'index';
//Api专用
$api	= isset($_GET['api']) ? tsFilter($_GET['api']) : 'index';
//plugin专用
$plugin = isset($_GET['plugin']) ? tsFilter($_GET['plugin']) : '';
//plugin专用
$in = isset($_GET['in']) ? tsFilter($_GET['in']) : '';
//皮肤
$tstheme = isset($_COOKIE['tsTheme']) ? tsFilter($_COOKIE['tsTheme']) : 'sample';

//360安全过滤
if(is_file($_SERVER['DOCUMENT_ROOT'].'/360safe/360webscan.php')){
    require_once($_SERVER['DOCUMENT_ROOT'].'/360safe/360webscan.php');
} 

//加载APP配置文件
include 'app/'.$app.'/config.php';
if(is_file('app/'.$app.'/action.php')){

	//加载网站配置文件
	$TS_SITE['base'] = fileRead('data/system_options.php');
	if($TS_SITE['base']==''){
		$TS_SITE['base'] = $tsMySqlCache->get('system_options');
	}
	
	//定义网站URL
	define('SITE_URL', $TS_SITE['base']['site_url']);	
	//设置时区
	date_default_timezone_set($TS_SITE['base']['timezone']);
	
	//加载APP导航
	$TS_SITE['appnav'] = fileRead('data/system_appnav.php');
	if($TS_SITE['appnav']==''){
		$TS_SITE['appnav'] = $tsMySqlCache->get('system_appnav');
	}
	
	include 'app/'.$app.'/model.php';
	include 'app/'.$app.'/action.php';
	$new[$app] = new $app($db);
	$new[$app]->$ac($mg);
	
}elseif(is_file('app/'.$app.'/class.'.$app.'.php')){

	include 'app/'.$app.'/class.'.$app.'.php';
	$new[$app] = new $app($db);
	//装载APP应用
	include 'app.php';
	
}else{
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	echo 'No APP 404 Page！';
	exit;
}