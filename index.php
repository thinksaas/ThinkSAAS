<?php
/**
 * ThinkSAAS单入口
 * copyright (c) 2010-3000 ThinkSAAS All Rights Reserved
 * author QiuJun
 * Email:thinksaas@qq.com
 */
// 定义网站根目录,APP目录,DATA目录，ThinkSAAS核心目录
define('IN_TS', true);

define('THINKROOT', dirname(__FILE__));
define('THINKAPP', THINKROOT . '/app');
define('THINKDATA', THINKROOT . '/data');
define('THINKSAAS', THINKROOT . '/thinksaas');
define('THINKINSTALL', THINKROOT . '/install');
define('THINKPLUGIN', THINKROOT . '/plugins');

// 装载ThinkSAAS核心

include THINKSAAS.'/thinksaas.php';

unset($GLOBALS);
