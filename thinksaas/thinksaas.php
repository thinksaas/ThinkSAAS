<?php
/**
 * @copyright (c) ThinkSAAS All Rights Reserved
 * @code by qiniao
 * @Email:thinksaas@qq.com
 * @site:www.thinksaas.cn
 */
defined('IN_TS') or die('Access Denied.');

//杜绝非本站域名的使用
if($TS_CF['urllock'] && $_SERVER['SERVER_NAME']!=$TS_CF['urllock']){
    echo '404 page';exit;
}

//加载基础函数
include 'tsFunction.php';

//安装专用变量
$install = isset($_GET['install']) ? $_GET['install'] : 'index';

//安装配置文件，数据库配置判断
if (!is_file('data/config.inc.php')) {
    include 'install/index.php';
    exit;
}

//开始计算程序执行时间
$time_start = microtime(true);

//处理fileurl
if ($TS_CF['fileurl']['url']) {
    if ($_SERVER['HTTP_HOST'] === $TS_CF['fileurl']['url']) {
        echo '404 page';
        exit;
    }
}

//启动Memcache
if ($TS_CF['memcache'] && extension_loaded('memcache')) {
    $TS_MC = Memcache::connect($TS_CF['memcache']['host'], $TS_CF['memcache']['port']);
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

$_GET = tsgpc($_GET);
$_POST = tsgpc($_POST);
$_COOKIE = tsgpc($_COOKIE);
//$_FILES = tsgpc ( $_FILES );

//系统Url参数变量
$TS_URL = array(
    'app'=>isset($_GET['app']) ? tsUrlCheck($_GET['app']) : 'home',//APP专用
    'ac'=>isset($_GET['ac']) ? tsUrlCheck($_GET['ac']) : 'index',//Action专用
    'mg'=>isset($_GET['mg']) ? tsUrlCheck($_GET['mg']) : '',//Admin管理专用
    'my'=>isset($_GET['my']) ? tsUrlCheck($_GET['my']) : 'index',//我的社区专用
    'api'=>isset($_GET['api']) ? tsUrlCheck($_GET['api']) : '',//Api专用
    'ts'=>isset($_GET['ts']) ? tsUrlCheck($_GET['ts']) : '',//ThinkSAAS专用
    'plugin'=>isset($_GET['plugin']) ? tsUrlCheck($_GET['plugin']) : '',//plugin专用
    'in'=>isset($_GET['in']) ? tsUrlCheck($_GET['in']) : '',//plugin专用
    'tp'=>isset($_GET['tp']) ? tsUrlCheck($_GET['tp']) : '1',//tp 内容分页
    'page'=>isset($_GET['page']) ? tsUrlCheck($_GET['page']) : '1',//page 列表分页
    'js'=>isset($_GET['js']) ? tsUrlCheck($_GET['js']) : '1',//输出json数据 接口专用
    'userkey'=>isset($_REQUEST['userkey']) ? tsUrlCheck($_REQUEST['userkey']) : '',//加密用户ID，专为客户端使用
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
$js = $TS_URL['js'];
$userkey = $TS_URL['userkey'];


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
include 'mysqli.php';
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

//加载我的社区导航
$TS_SITE['mynav'] = fileRead('data/system_mynav.php');
if ($TS_SITE['mynav'] == '') {
    $TS_SITE['mynav'] = $tsMySqlCache -> get('system_mynav');
}

//加载APP配置
if (is_file('data/' . $TS_URL['app'] . '_options.php')) {
    $TS_APP = fileRead('data/' . $TS_URL['app'] . '_options.php');
    if ($TS_APP == '') {
        $TS_APP = $tsMySqlCache -> get($TS_URL['app'] . '_options');
    }
    if ($TS_APP['isenable'] == '1' && $TS_URL['ac'] != 'admin') {
        tsNotice($TS_URL['app'] . "应用关闭，请开启后访问！");
    }
}

//加密用户操作
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = sha1(uniqid(mt_rand(), TRUE));
}

if ($_REQUEST['token'] && $TS_SITE['istoken']) {
    if (tsFilter($_REQUEST['token']) != $_SESSION['token']) {
        tsNotice('非法操作！');
    }
}

//定义网站URL
define('SITE_URL', $TS_SITE['site_url']);

//设置时区
date_default_timezone_set($TS_SITE['timezone']);


//接管SESSION，前台用户基本数据,$TS_USER数组
$TS_USER = isset($_SESSION['tsuser']) ? $_SESSION['tsuser'] : array();
//APP独立用户组权限控制
$route_key = $app.'_'.$ac;
if($mg && $mg!='index') $route_key .= '_'.$mg;
if($api && $api!='index') $route_key .= '_'.$api;
if($ts) $route_key .= '_'.$ts;
$TS_APP['permissions'] = fileRead('data/' . $TS_URL['app'] . '_permissions.php');
if ($TS_APP['permissions'] == '') $TS_APP['permissions'] = $tsMySqlCache -> get($TS_URL['app'] . '_permissions');
$common_ugid = tsIntval($TS_USER['ugid'],0,4);//默认4为游客
if($TS_APP['permissions']){
    if($TS_APP['permissions'][$common_ugid]){
        if($TS_APP['permissions'][$common_ugid][$route_key]!=null && $TS_APP['permissions'][$common_ugid][$route_key]==0){
            tsNotice('权限不够！不允许访问！');
        }
    }
}

//记录日志
if ($TS_CF['logs']) {
    //打印用户日志记录
    userlog($_POST, intval($TS_USER['userid']));
    userlog($_GET, intval($TS_USER['userid']));
}

//控制前台ADMIN访问权限
if ($TS_URL['ac'] == 'admin' && $TS_USER['isadmin'] != 1 && $TS_URL['app'] != 'system') {
    tsHeaderUrl(SITE_URL);
}

//API逻辑单独处理
if($app=='api' || $ac=='api'){

    //处理跨域
    $origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';
    $allow_origin = array(
        $TS_SITE['link_url'],
        'https://h5.thinksaas.cn',
        'https://www.thinksaas.cn',
        'http://localhost:8080',
    );
    if(in_array($origin, $allow_origin)){
        header('Access-Control-Allow-Origin:'.$origin);
    }
    header('Access-Control-Allow-Headers: X-Requested-With');

}else{

    //用户自动登录
    if (intval($TS_USER['userid']) == 0 && $_COOKIE['ts_email'] && $_COOKIE['ts_autologin']) {

        $loginUserData = aac('user') -> find('user_info', array(
            'email' => $_COOKIE['ts_email'],
            'autologin' => $_COOKIE['ts_autologin']
        ));

        if ($loginUserData) {
            
            if ($loginUserData['ip'] != getIp() && $TS_URL['app'] != 'user' && $TS_URL['ac'] != 'login') {
                tsHeaderUrl(tsUrl('user', 'login', array('ts' => 'out')));
            }
            //用户session信息
            $_SESSION['tsuser'] = array(
                'userid' => $loginUserData['userid'],
                'ugid' => $loginUserData['ugid'],
                'username' => $loginUserData['username'],
                'email' => $loginUserData['email'],
                'face'=>aac('user')->getUserFace($loginUserData),
                'isadmin' => $loginUserData['isadmin'],
                'signin' => $loginUserData['signin'],
                'isverify' => $loginUserData['isverify'],
                'isverifyphone' => $loginUserData['isverifyphone'],
                'uptime' => $loginUserData['uptime'],
            );
            $TS_USER = $_SESSION['tsuser'];
        }
    }

    //控制访客权限
    if($TS_USER==null && $TS_SITE['visitor'] == 1){
        if(!in_array($app,array('pubs','pay')) && !in_array($ac,array('info','home','register','phone','login','forgetpwd','resetpwd','wxlogin','plogin'))){
            tsHeaderUrl(tsUrl('pubs','home'));
        }
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
    if ($TS_SITE['isverify'] == 1 && tsIntval($TS_USER['userid']) > 0 && $TS_URL['app'] != 'system' && $TS_URL['ac'] != 'admin') {
        if (valid_email($TS_USER['email'])==true && tsIntval($TS_USER['isverify']) == 0 && $TS_URL['app'] != 'user' && $TS_USER['isadmin'] != 1) {
            tsHeaderUrl(tsUrl('user', 'verify'));
        }
    }

    //判断用户是否需要验证手机号,管理员除外
    if ($TS_SITE['isverifyphone'] == 1 && tsIntval($TS_USER['userid']) > 0 && $TS_URL['app'] != 'system' && $TS_URL['ac'] != 'admin') {
        if (tsIntval($TS_USER['isverifyphone']) == 0 && $TS_URL['app'] != 'user' && $TS_URL['app'] != 'pubs' && $TS_USER['isadmin'] != 1) {
            tsHeaderUrl(tsUrl('user', 'phone',array('ts'=>'verify')));
        }
    }

    //判断用户是否上传头像,管理员除外
    if ($TS_SITE['isface'] == 1 && tsIntval($TS_USER['userid']) > 0 && $TS_URL['app'] != 'system' && $TS_URL['ac'] != 'admin' && $TS_URL['app'] != 'pubs') {
        if ($TS_USER['face'] == SITE_URL.'public/images/user_large.jpg' && $TS_URL['app'] != 'user' && $TS_USER['isadmin'] != 1) {
            tsHeaderUrl(tsUrl('user', 'verify', array('ts' => 'face')));
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

}

//运行统计结束
$time_end = microtime(true);
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

    //面向目录和文件的逻辑加载写法
    if (is_file('app/' . $TS_URL['app'] . '/action/' . $TS_URL['ac'] . '.php')) {
        //开始执行APP action
        if (is_file('app/' . $TS_URL['app'] . '/action/common.php'))
            include 'app/' . $TS_URL['app'] . '/action/common.php';
    
        include 'app/' . $TS_URL['app'] . '/action/' . $TS_URL['ac'] . '.php';
    
    } else {
        ts404();
    }
    
} else {
    ts404();
}