<?php

defined('IN_TS') or die('Access Denied.');
/**
 * @copyright (c) ThinkSAAS All Rights Reserved
 * @code by QiuJun
 * @Email:thinksaas@qq.com
 * @site:www.thinksaas.cn
 */
if (version_compare('PHP_VERSION','5.0.0','>=')) {
    exit("ThinkSAAS运行环境要求PHP5！");
}

//核心配置文件 $TS_CF 系统配置变量
$TS_CF = include THINKROOT . '/thinksaas/config.php';

// 如果是调试模式，打开警告输出
if ($TS_CF['debug']) {
    if (substr(PHP_VERSION, 0, 3) == "5.3") {
        error_reporting(~E_WARNING & ~E_NOTICE & E_ALL & ~E_DEPRECATED);
    } else {
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }
} else {
    error_reporting(0);
}

ini_set('magic_quotes_runtime',0);

//ini_set("memory_limit","120M");

ini_set('display_errors', 'on');   //正式环境关闭错误输出

set_time_limit(0);

ini_set('session.cookie_path', '/');

//杜绝非本站域名的使用
if($TS_CF['urllock'] && $_SERVER['SERVER_NAME']!=$TS_CF['urllock']){
    echo '404 page';exit;
}

//自定义session存储目录路径
if ($TS_CF['sessionpath']) {
    ini_set('session.save_path', THINKROOT . '/cache/sessions');
}

//加载基础函数
include 'tsFunction.php';

//开始计算程序执行时间
$time_start = getmicrotime();

//处理fileurl
if ($TS_CF['fileurl']['url']) {
    if ($_SERVER['HTTP_HOST'] === $TS_CF['fileurl']['url']) {
        echo '404 page';
        exit;
    }
}

//数据库存储SESSION  还有点问题，暂时不要开启
if ($TS_CF['session']) {
    include 'tsSession.php';
    ini_set('session.save_handler', 'user');
    session_set_save_handler(
            array('tsSession', 'open'), array('tsSession', 'close'), array('tsSession', 'read'), array('tsSession', 'write'), array('tsSession', 'destroy'), array('tsSession', 'gc')
    );
}

session_start();

//启动Memcache
if ($TS_CF['memcache'] && extension_loaded('memcache')) {
    $TS_MC = Memcache::connect($TS_CF['memcache']['host'], $TS_CF['memcache']['port']);
}

//加密用户操作
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = sha1(uniqid(mt_rand(), TRUE));
}

if ($_REQUEST['token']) {
    if (tsFilter($_REQUEST['token']) != $_SESSION['token']) {
        tsNotice('非法操作！');
    }
}

//处理html编码
header('Content-Type: text/html; charset=UTF-8');
//安装专用变量
$install = isset($_GET['install']) ? $_GET['install'] : 'index';


//安装配置文件，数据库配置判断
if (!is_file('data/config.inc.php')) {
    include 'install/index.php';
    exit;
}

//开始处理url路由，支持APP二级域名
if ($TS_CF['subdomain']) {
    ini_set("session.cookie_domain", '.' . $TS_CF['subdomain']['domain']);

    //APP独立域名支持
    if (array_search($_SERVER['HTTP_HOST'], $TS_CF['appdomain'])) {
        reurlsubdomain();
    } else {
        $arrHost = explode('.', $_SERVER['HTTP_HOST']);
        if ($arrHost[0] == 'www') {
            reurl();
        } else {
            reurlsubdomain();
        }
    }
} else {
    reurl();
}

//判断magic_quotes_gpc状态
if (get_magic_quotes_gpc() == 0) {
    $_GET = tsgpc($_GET);
    $_POST = tsgpc($_POST);
    $_COOKIE = tsgpc($_COOKIE);
    //$_FILES = tsgpc ( $_FILES );
}

//系统Url参数变量
$TS_URL = array(
    'app'=>isset($_GET['app']) ? tsUrlCheck($_GET['app']) : 'home',//APP专用
    'ac'=>isset($_GET['ac']) ? tsUrlCheck($_GET['ac']) : 'index',//Action专用
    'ts'=>isset($_GET['ts']) ? tsUrlCheck($_GET['ts']) : '',//ThinkSAAS专用
    'mg'=>isset($_GET['mg']) ? tsUrlCheck($_GET['mg']) : 'index',//Admin管理专用
    'my'=>isset($_GET['my']) ? tsUrlCheck($_GET['my']) : 'index',//我的社区专用
    'api'=>isset($_GET['api']) ? tsUrlCheck($_GET['api']) : 'index',//Api专用
    'plugin'=>isset($_GET['plugin']) ? tsUrlCheck($_GET['plugin']) : '',//plugin专用
    'in'=>isset($_GET['in']) ? tsUrlCheck($_GET['in']) : '',//plugin专用
    'tp'=>isset($_GET['tp']) ? tsUrlCheck($_GET['tp']) : '1',//tp 内容分页
    'page'=>isset($_GET['page']) ? tsUrlCheck($_GET['page']) : '1',//page 列表分页
);

//下面是过渡，直到把所有的参数都改完
$app = $TS_URL['app'];
$ac = $TS_URL['ac'];
$ts = $TS_URL['ts'];
$mg = $TS_URL['mg'];
$my = $TS_URL['my'];
$api = $TS_URL['api'];
$plugin = $TS_URL['plugin'];
$in = $TS_URL['in'];
$tp = $TS_URL['tp'];
$page = $TS_URL['page'];


//APP二级域名支持，同时继续支持url原生写法
if ($TS_CF['subdomain'] && $TS_URL['app'] == 'home') {
    //APP独立域名支持
    $TS_URL['app'] = array_search($_SERVER['HTTP_HOST'], $TS_CF['appdomain']);
    if ($TS_URL['app'] == '') {
        //二级域名支持
        $arrHost = explode('.', $_SERVER['HTTP_HOST']);
        $TS_URL['app'] = $arrHost['0'];
        if ($TS_URL['app'] == 'www') {
            $TS_URL['app'] = 'home';
        }
    }
}



//数据库配置文件
include 'data/config.inc.php';

//加载APP配置文件
include 'app/' . $TS_URL['app'] . '/config.php';

//连接数据库
include 'sql/' . $TS_DB['sql'] . '.php';
$db = new MySql($TS_DB);

//加载APP数据库操作类并建立对象
include 'thinksaas/tsApp.php';
//MySQL数据库缓存
include 'thinksaas/tsMySqlCache.php';
$tsMySqlCache = new tsMySqlCache($db);


//加载网站配置文件
$TS_SITE = fileRead('data/system_options.php');
if ($TS_SITE == '') {
    $TS_SITE = $tsMySqlCache -> get('system_options');
}

//加载皮肤
$tstheme = isset($_COOKIE['tsTheme']) ? tsUrlCheck($_COOKIE['tsTheme']) : $TS_SITE['site_theme'];

//加载APP导航
$TS_SITE['appnav'] = fileRead('data/system_appnav.php');
if ($TS_SITE['appnav'] == '') {
    $TS_SITE['appnav'] = $tsMySqlCache -> get('system_appnav');
}

if (is_file('data/' . $TS_URL['app'] . '_options.php')) {
    $TS_APP = fileRead('data/' . $TS_URL['app'] . '_options.php');
    if ($TS_APP == '') {
        $TS_APP = $tsMySqlCache -> get($TS_URL['app'] . '_options');
    }

    if ($TS_APP['isenable'] == '1' && $TS_URL['ac'] != 'admin') {
        tsNotice($TS_URL['app'] . "应用关闭，请开启后访问！");
    }
}

//定义网站URL
define('SITE_URL', $TS_SITE['site_url']);

//设置时区
date_default_timezone_set($TS_SITE['timezone']);


//接管SESSION，前台用户基本数据,$TS_USER数组
$TS_USER = isset($_SESSION['tsuser']) ? $_SESSION['tsuser'] : '';

//记录日志
if ($TS_CF['logs']) {
    //打印用户日志记录
    userlog($_POST, intval($TS_USER['userid']));
    userlog($_GET, intval($TS_USER['userid']));
}

//控制访客权限
if($TS_USER=='' && $TS_SITE['visitor'] == 1){
    if($ac!='home' && $ac!='register' && $ac!='login' && $ac!='forgetpwd' && $ac!='resetpwd' && $app!='api'){
        tsHeaderUrl(tsUrl('pubs','home'));
    }
}

//控制前台ADMIN访问权限
if ($TS_URL['ac'] == 'admin' && $TS_USER['isadmin'] != 1 && $TS_URL['app'] != 'system') {
    tsHeaderUrl(SITE_URL);
}

//控制后台访问权限
if ($TS_USER['isadmin'] != 1 && $TS_URL['app'] == 'system' && $TS_URL['ac'] != 'login') {
    tsHeaderUrl(SITE_URL);
}

//控制插件设置权限
if ($TS_USER['isadmin'] != 1 && $TS_URL['in'] == 'edit') {
    tsHeaderUrl(SITE_URL);
}

//判断用户是否需要验证Email,管理员除外
if ($TS_SITE['isverify'] == 1 && intval($TS_USER['userid']) > 0 && $TS_URL['app'] != 'system' && $TS_URL['ac'] != 'admin') {

    $verifyUser = aac('user') -> find('user_info', array('userid' => intval($TS_USER['userid']), ));

    if (intval($verifyUser['isverify']) == 0 && $TS_URL['app'] != 'user' && $TS_USER['isadmin'] != 1) {
        tsHeaderUrl(tsUrl('user', 'verify'));
    }

}

//判断用户是否上传头像,管理员除外
if ($TS_SITE['isface'] == 1 && intval($TS_USER['userid']) > 0 && $TS_URL['app'] != 'system' && $TS_URL['ac'] != 'admin') {

    $faceUser = aac('user') -> find('user_info', array('userid' => intval($TS_USER['userid']), ));

    if ($faceUser['face'] == '' && $TS_URL['app'] != 'user' && $TS_USER['isadmin'] != 1) {
        tsHeaderUrl(tsUrl('user', 'verify', array('ts' => 'face')));
    }
}

//用户自动登录
if (intval($TS_USER['userid']) == 0 && $_COOKIE['ts_email'] && $_COOKIE['ts_autologin']) {

    $loginUserNum = aac('user') -> findCount('user_info', array('email' => $_COOKIE['ts_email'], 'autologin' => $_COOKIE['ts_autologin'], ));

    if ($loginUserNum > 0) {

        $loginUserData = aac('user') -> find('user_info', array('email' => $_COOKIE['ts_email'], ), 'userid,username,path,face,ip,isadmin,signin,uptime');

        if ($loginUserData['ip'] != getIp() && $TS_URL['app'] != 'user' && $TS_URL['ac'] != 'login') {
            tsHeaderUrl(tsUrl('user', 'login', array('ts' => 'out')));
        }

        //用户session信息
        $_SESSION['tsuser'] = array(
			'userid' => $loginUserData['userid'], 
			'username' => $loginUserData['username'], 
			'path' => $loginUserData['path'], 
			'face' => $loginUserData['face'], 
			'isadmin' => $loginUserData['isadmin'], 
			'signin' => $loginUserData['signin'], 
			'uptime' => $loginUserData['uptime'], 
		);
        $TS_USER = $_SESSION['tsuser'];
    }
}

$tsHooks = array();


if ($TS_URL['app'] != 'system' && $TS_URL['app'] != 'pubs') {
    //加载公用插件
    $public_plugins = fileRead('data/pubs_plugins.php');
    if ($public_plugins == '') {
        $public_plugins = $tsMySqlCache -> get('pubs_plugins');
    }

    if ($public_plugins && is_array($public_plugins)) {
        foreach ($public_plugins as $item) {
            if (is_file('plugins/pubs/' . $item . '/' . $item . '.php')) {
                include 'plugins/pubs/' . $item . '/' . $item . '.php';
            }
        }
    }

    //加载APP插件
    $active_plugins = fileRead('data/' . $TS_URL['app'] . '_plugins.php');
    if ($active_plugins == '') {
        $active_plugins = $tsMySqlCache -> get($TS_URL['app'] . '_plugins');
    }

    if ($active_plugins && is_array($active_plugins)) {
        foreach ($active_plugins as $item) {
            if (is_file('plugins/' . $TS_URL['app'] . '/' . $item . '/' . $item . '.php')) {
                include 'plugins/' . $TS_URL['app'] . '/' . $item . '/' . $item . '.php';
            }
        }
    }
}

//运行统计结束
$time_end = getmicrotime();

$runTime = $time_end - $time_start;
$TS_CF['runTime'] = number_format($runTime, 6);


//定义全局变量
global $TS_CF,$TS_SITE,$TS_APP,$TS_USER,$TS_URL,$TS_MC,$db,$tsMySqlCache,$tstheme;

//装载APP应用
if (is_file('app/' . $TS_URL['app'] . '/class.' . $TS_URL['app'] . '.php')) {


    include_once 'app/' . $TS_URL['app'] . '/class.' . $TS_URL['app'] . '.php';
    $new[$TS_URL['app']] = new $TS_URL['app']($db);


    //在执行action之前加载
    doAction('beforeAction');

    //全站通用数据加载
    include 'thinksaas/common.php';


    if(is_file('app/'.$TS_URL['app'].'/action.'.$TS_URL['app'].'.php')){
        //面向对象的写法
        include_once 'app/'.$TS_URL['app'].'/action.'.$TS_URL['app'].'.php';
        $appAction = $TS_URL['app'].'Action';
        $newAction = new $appAction($db);
        $newAction->$TS_URL['ac']();
    }else{
        //面向目录和文件的逻辑加载写法
        include 'app.php';
    }
    
} else {
    ts404();
}
