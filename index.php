<?php
/*
 * ThinkSAAS单入口
 * @copyright (c) 2010-3000 ThinkSAAS All Rights Reserved
 * @author QiuJun
 * @Email:thinksaas@qq.com
 */
 
//定义网站根目录,APP目录,DATA目录，ThinkSAAS核心目录
define('IN_TS',true);

define('THINKROOT', dirname(__FILE__));
define('THINKAPP', THINKROOT.'/app');
define('THINKDATA',THINKROOT.'/data');
define('THINKSAAS', THINKROOT.'/ThinkSAAS');
define('THINKINSTALL',THINKROOT.'/install');
define('THINKPLUGIN',THINKROOT.'/plugins');

//装载ThinkSAAS核心

include 'ThinkSAAS/ThinkSAAS.php';

//除去加载内核运行时间统计开始
 
$time_start = getmicrotime();

if(is_file('data/config.inc.php')){
	//装载APP应用
	include 'app/index.php';
}else{
	//装载安装程序
	include 'install/index.php';
}

unset($GLOBALS);