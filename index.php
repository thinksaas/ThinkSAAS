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

if (substr(PHP_VERSION, 0, 3)<5.4) {
    exit("ThinkSAAS运行环境要求PHP5.4或者更高！");
}

define('THINKROOT', dirname(__FILE__));
define('THINKAPP', THINKROOT . '/app');
define('THINKDATA', THINKROOT . '/data');
define('THINKSAAS', THINKROOT . '/thinksaas');
define('THINKINSTALL', THINKROOT . '/install');
define('THINKPLUGIN', THINKROOT . '/plugins');

//自动加载所需功能，支持composer
require_once THINKROOT . '/vendor/autoload.php';
// 装载ThinkSAAS核心
include THINKSAAS.'/thinksaas.php';

unset($GLOBALS);