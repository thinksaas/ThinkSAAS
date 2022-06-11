<?php
/**
 * ThinkSAAS开源社区
 * copyright (c) 2010-3000 ThinkSAAS All Rights Reserved
 * 官网：https://www.thinksaas.cn
 * 作者：邱君
 * Email:thinksaas@qq.com
 * QQ:1078700473
 * 微信：thinksaas
 */
define('IN_TS', true);
header('Content-Type: text/html; charset=UTF-8');
#php版本限制(vendor\composer\platform_check.php)
if (!(PHP_VERSION_ID >= 70000)) {
    exit("ThinkSAAS运行环境要求PHP7或者更高PHP版本！请先升级PHP版本再运行ThinkSAAS程序!");
}

#定义一些路径
define('THINKROOT', dirname(__FILE__));
define('THINKAPP', THINKROOT . '/app');
define('THINKDATA', THINKROOT . '/data');
define('THINKSAAS', THINKROOT . '/thinksaas');
define('THINKINSTALL', THINKROOT . '/install');
define('THINKPLUGIN', THINKROOT . '/plugins');
#核心配置文件 $TS_CF 系统配置变量
$TS_CF = include THINKROOT . '/thinksaas/config.php';
$TS_CF['info']['version'] = include 'upgrade/version.php';#版本信息
#如果是调试模式，打开警告输出
if ($TS_CF['debug']) {
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
} else {
    error_reporting(0);
}
#php环境的某一些配置
//ini_set("memory_limit","120M");
ini_set('display_errors', 'on');   //正式环境关闭错误输出
set_time_limit(0);
ini_set('session.cookie_path', '/');

//自定义本地session存储目录路径
if ($TS_CF['sessionpath']) {
    ini_set('session.save_path', THINKROOT . '\\cache\\sessions');
}

if($TS_CF['session']=='redis'){
    ini_set("session.save_handler","redis");
    ini_set("session.save_path",$TS_CF['redis']['tcp']);
}

session_start();

#自动加载所需功能，支持composer
require_once THINKROOT . '/vendor/autoload.php';
#装载ThinkSAAS核心
include THINKSAAS.'/thinksaas.php';
unset($GLOBALS['TS_CF']);