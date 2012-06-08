<?php
/*
 * ThinkSAAS APP入口
 * @copyright (c) 2010-3000 ThinkSAAS All Rights Reserved
 * @code by QiuJun
 * @Email:thinksaas@qq.com
 */
defined('IN_TS') or die('Access Denied.');

//判断升级
if(is_file('update/up.php')) $app = 'upgrade';

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
	$TS_SITE['base'] = fileRead('data/system_options.php');
	//定义网站URL
	define('SITE_URL', $TS_SITE['base']['site_url']);	
	//设置时区
	date_default_timezone_set($TS_SITE['base']['timezone']);
	
	//反垃圾过滤，过滤用户ID，过滤用户IP
	$ts_user_isenable = fileRead('data/user_isenable.php');
	$ts_user_ip = fileRead('data/user_ip.php');
	if($ts_user_isenable && $TS_USER['user']['userid']){
		preg_match("/$ts_user_isenable/i",$TS_USER['user']['userid'], $matches);
		if(!empty($matches[0])){
			header('Location: '.SITE_URL.tsUrl('user','login',array('ts'=>'out')));
		}
	}
	if($ts_user_ip && $TS_USER['user']['userid']){
		preg_match("/$ts_user_ip/i",getIp(), $matches);
		if(!empty($matches[0])){
			header('Location: '.SITE_URL.tsUrl('user','login',array('ts'=>'out')));
		}
	}
	
	//加载APP导航
	$TS_SITE['appnav'] = fileRead('data/system_appnav.php');
	
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
		
		$TS_APP['options'] = fileRead('data/'.$app.'_options.php');
		
		if($TS_APP['options']['isenable']=='1' && $ac != 'admin') qiMsg($app."应用关闭，请开启后访问！");
		
	}
	
	//加载APP配置文件
	include_once 'app/'.$app.'/config.php';
	
	include_once 'app/'.$app.'/class.'.$app.'.php';
	
	$new[$app] = new $app($db);

	//控制前台ADMIN访问权限
	if($ac == 'admin' && $TS_USER['admin']['isadmin']!=1 && $app != 'system'){
		header("Location: ".SITE_URL);
		exit;
	}
	
	//控制后台访问权限
	if($TS_USER['admin']['isadmin'] != 1 && $app=='system' && $ac != 'login'){
		header("Location: ".SITE_URL.tsUrl('system','login'));
		exit;
	}
	
	//控制插件设置权限
	if($TS_USER['admin']['isadmin'] != 1 && $in == 'edit'){
		header("Location: ".SITE_URL.tsUrl('system','login'));
		exit;
	}
	
	//判断用户是否需要验证Email,管理员除外
	if($TS_SITE['base']['isverify']==1 && intval($TS_USER['user']['userid']) > 0 && $app != 'system' && $ac != 'admin'){
		
		$verifyUser = $new[$app]->find('user_info',array(
			'userid'=>intval($TS_USER['user']['userid']),
		));
		
		if(intval($verifyUser['isverify'])==0 && $app != 'user' && $TS_USER['user']['isadmin'] != 1){
			header("Location: ".SITE_URL.tsUrl('user','verify'));
			exit;
		}
		
	}
	
	//判断用户是否上传头像,管理员除外
	if($TS_SITE['base']['isface']==1 && intval($TS_USER['user']['userid']) > 0 && $app != 'system' && $ac != 'admin'){
		
		$faceUser = $new[$app]->find('user_info',array(
			'userid'=>intval($TS_USER['user']['userid']),
		));
		
		if($faceUser['face']=='' && $app != 'user' && $TS_USER['user']['isadmin'] != 1){
			header("Location: ".SITE_URL.tsUrl('user','set',array('ts'=>'face')));
			exit;
		}
	}
	
	//运行统计结束
	$time_end = getmicrotime();
	
	$runTime = $time_end - $time_start;
	$runTime = number_format($runTime,6);
	
	//用户自动登录
	if($TS_USER['user']['userid']=='' && $_COOKIE['ts_email'] && $_COOKIE['ts_uptime']){
		
		$loginUserNum = $new[$app]->findCount('user_info',array(
			'email'=>$_COOKIE['ts_email'],
			'uptime'=>$_COOKIE['ts_uptime'],
		));
	
		if($loginUserNum > 0){
			
			$loginUserData = $new[$app]->find('user_info',array(
				'email'=>$_COOKIE['ts_email'],
			),'userid,username,areaid,path,face,count_score,isadmin,uptime');
			
			//用户session信息
			$_SESSION['tsuser']	= $loginUserData;
			$TS_USER = array(
				'user'		=> $_SESSION['tsuser'],
			);

		}
	}
	
	$tsHooks = array();
	
	if($app != 'system' && $app !='pubs'){

		//加载公用插件 
		if(is_file('data/pubs_plugins.php')){
			$public_plugins = fileRead('data/pubs_plugins.php');
		
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
			$active_plugins = fileRead('data/'.$app.'_plugins.php');
			if ($active_plugins && is_array($active_plugins)) {
				foreach($active_plugins as $item) {
					if(is_file('plugins/'.$app.'/'.$item.'/'.$item.'.php')) {
						include 'plugins/'.$app.'/'.$item.'/'.$item.'.php';
					}
				}
			}
		}
	}
	
	//在执行action之前加载
	doAction('beforeAction');
	
	//开始执行APP action
	include $app.'/action/'.$ac.'.php';
	
	
}else{
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	echo 'No APP 404 Page！';
	exit;
}