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

//开始处理url路由
$scriptName = explode('index.php',$_SERVER['SCRIPT_NAME']);

$rurl = substr($_SERVER['REQUEST_URI'], strlen($scriptName[0]));

if(strpos($rurl,'?')===false){
	if(preg_match('/index.php/i',$rurl)){
		$rurl = str_replace('index.php','',$rurl);
		$rurl = substr($rurl, 1);
		$params = $rurl;
	}else{
		$params = $rurl;
	}

	if($rurl){
		
		$params = explode('/', $params);
		
		foreach( $params as $p => $v )
		{
			switch($p)
			{
				case 0:$_GET['app']=$v;break;
				case 1:$_GET['ac']=$v;break;
				default:
					$kv = explode('-', $v);
					if(count($kv)>1)
					{
						$_GET[$kv[0]] = $kv[1];
					}
					else
					{
						$_GET['params'.$p] = $kv[0];
					}
					break;
			}
		}
	}
}

//处理过滤
if (!get_magic_quotes_gpc()) {     
	Add_S($_POST);
	Add_S($_GET);
}

//给出所有ThinkSAAS自身的变量
$app = isset($_GET['app']) ? $_GET['app'] : 'discuss';
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


//i18n语言
require_once  'class.i18n.php';
$i18n= new i18n($app,'app/'.$app.'/lang/lang_{LANGUAGE}.ini', 'cache/lang/', 'en'); 
$i18n->init();

//加载基础函数
require_once 'fun.base.php';

header('Content-Type: text/html; charset=UTF-8');

//加载软件信息
$TS_SOFT['info'] = array(
	'name'	=>	'ThinkSAAS',
	'version'	=>	'1.7',
	'url'		=>	'http://www.thinksaas.cn/',
	'email'	=>	'thinksaas@qq.com',
	'copyright' =>	'ThinkSAAS',
	'year'		=>	'2011-2012',
	'author'	=>	'QiuJun',
);

//
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