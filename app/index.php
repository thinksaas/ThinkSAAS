<?php
/*
 * ThinkSAAS APP入口
 * @copyright (c) 2010-3000 ThinkSAAS All Rights Reserved
 * @code by QiuJun
 * @Email:thinksaas@qq.com
 */
defined('IN_TS') or die('Access Denied.');

define('IS_POST', (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST'));

//判断升级
if(is_file('data/up.php')) $app = 'upgrade';

if($app=='upgrade' && !is_file('data/up.php')) $app='group';

//APP模板CSS,IMG,INC
$TS_APP['tpl']	= array(
	'skin'	=> 'app/'.$app.'/skins/',
	'js'	=> 'app/'.$app.'/js/',
);
 
//system系统管理模板CSS,IMG
$TS_APP['system']	= array(
	'skin'	=> 'app/system/skins/',
	'js'	=> 'app/system/js/',
);

//加载APP应用首页和配置文件
if(is_file('app/'.$app.'/action/'.$ac.'.php')){

	//加载系统缓存文件
	$TS_SITE['base'] = fileRead('options.php','data','system');
	
	//语言
	$hl_c = isset($_COOKIE['ts_lang']) ? $_COOKIE['ts_lang'] : '';
	if($hl_c){
		$hl = $hl_c;
	}else{
		$hl = $TS_SITE['base']['lang'];
	}
	
	//设置时区
	date_default_timezone_set($TS_SITE['base']['timezone']);
	
	//加载APP导航
	$TS_SITE['appnav'] = fileRead('appnav.php','data','system');

	
	define('SITE_URL', $TS_SITE['base']['site_url']);
	
	//主题
	$ts_theme = isset($_COOKIE['ts_theme']) ? $_COOKIE['ts_theme'] : '';
	if($ts_theme){
		if(is_file('theme/'.$ts_theme.'/preview.gif')){
			$site_theme = $ts_theme;
		}else{
			$site_theme = $TS_SITE['base']['site_theme'];
		}
	}else{
		$site_theme = $TS_SITE['base']['site_theme'];
	}
	
	//加载APP配置缓存文件
	if($app != 'system'){
		
		
			$TS_APP['options'] = fileRead('options.php','data',$app);
			
			if($TS_APP['options']['isenable']=='1' && $ac != 'admin') qiMsg($app."应用关闭，请开启后访问！");
		
	}
	

	//加载APP配置文件
	include_once 'app/'.$app.'/config.php';

	//连接数据库
	if($TS_DB['sql']=='0'){
		include_once 'thinksaas/mysql.php';
	}elseif($TS_DB['sql']=='1'){
		include_once 'thinksaas/pdo_mysql.php';
	}
	
	$db = new MySql($TS_DB);
	
	//加载APP数据库操作类并建立对象
	include_once 'app/'.$app.'/class.'.$app.'.php';
	$new[$app] = new $app($db);

	//控制前台ADMIN访问权限
	if($ac == 'admin' && $TS_USER['admin']['isadmin']!=1 && $app != 'system'){
		header("Location: ".SITE_URL."index.php");
		exit;
	}
	
	//控制后台访问权限
	if($TS_USER['admin']['isadmin'] != 1 && $app=='system' && $ac != 'login'){
		header("Location: ".SITE_URL."index.php?app=system&ac=login");
		exit;
	}
	
	//控制插件设置权限
	if($TS_USER['admin']['isadmin'] != 1 && $in == 'edit'){
		header("Location: ".SITE_URL."index.php?app=system&ac=login");
		exit;
	}
	
	//判断用户是否上传头像
	if($TS_SITE['base']['isface']==1 && $TS_USER['user'] != '' && $app != 'system' && $ac != 'admin'){
		$faceUser = $db->once_fetch_assoc("select face from ".dbprefix."user_info where userid='".intval($TS_USER['user']['userid'])."'");
		if($faceUser['face']=='' && $ts != 'face'){
			header("Location: ".SITE_URL."index.php?app=user&ac=set&ts=face");
		}
	}
	
	//运行统计结束
	$time_end = getmicrotime();
	
	$runTime = $time_end - $time_start;
	$runTime = number_format($runTime,6);
	
	//用户自动登录
	if($TS_USER['user']=='' && $_COOKIE['ts_email']!='' && $_COOKIE['ts_pwd'] !='' ){
		
		$loginUserNum = $db->once_num_rows("select * from ".dbprefix."user where email='".$_COOKIE['ts_email']."' and pwd='".$_COOKIE['ts_pwd']."'");
	
		if($loginUserNum > 0){
			$loginUserData = $db->once_fetch_assoc("select  userid,username,areaid,path,face,count_score,isadmin,uptime from ".dbprefix."user_info where email='".$_COOKIE['ts_email']."'");
			//用户session信息
			$_SESSION['tsuser']	= $loginUserData;
			$TS_USER = array(
				'user'		=> $_SESSION['tsuser'],
			);
			//更新登录时间
			$db->query("update ".dbprefix."user_info set `uptime`='".time()."' where userid='".$loginUserData['userid']."'");	
		}
	}
	
	
	$tsHooks = array();
	
	if($app != 'system' && $app !='pubs'){

		//加载公用插件 
		if(is_file('data/pubs_plugins.php')){
			$public_plugins = fileRead('plugins.php','data','pubs');
		
			if ($public_plugins && is_array($public_plugins)) {
				foreach($public_plugins as $item) {
					if(is_file('plugins/pubs/'.$item.'/'.$item.'.php')) {
						include 'plugins/pubs/'.$item.'/'.$item.'.php';
					}
				}
			}
		}
	
		//加载APP插件
		if(is_file('data/'.$app.'_plugins.php')){
			$active_plugins = fileRead('plugins.php','data',$app);
			if ($active_plugins && is_array($active_plugins)) {
				foreach($active_plugins as $item) {
					if(is_file('plugins/'.$app.'/'.$item.'/'.$item.'.php')) {
						include 'plugins/'.$app.'/'.$item.'/'.$item.'.php';
					}
				}
			}
		}
	}
	
	//加载语言包，公共语言包和APP语言包
	if(is_file('public/lang/'.$hl.'.php')){
		$TS_HL['pub'] = include 'public/lang/'.$hl.'.php';
	}else{
		if(is_file('public/lang/zh_cn.php')){
			$TS_HL['pub'] = include 'public/lang/zh_cn.php';
		}
	}
	
	if(is_file('app/'.$app.'/lang/'.$hl.'.php')){
		
		$TS_HL['app'] = include 'app/'.$app.'/lang/'.$hl.'.php';
	}else{
		if(is_file('app/'.$app.'/lang/zh_cn.php')){
			$TS_HL['app'] = include 'app/'.$app.'/lang/zh_cn.php';
		}
	}
	
	//开始执行APP action
	include $app.'/action/'.$ac.'.php';
	
	
}else{
	header("HTTP/1.1 404 Not Found");
	exit;
}