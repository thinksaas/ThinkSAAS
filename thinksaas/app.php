<?php
/*
 * ThinkSAAS APP入口
 * @copyright (c) 2010-3000 ThinkSAAS All Rights Reserved
 * @code by QiuJun
 * @Email:thinksaas@qq.com
 */
defined('IN_TS') or die('Access Denied.');

//加载APP应用首页和配置文件
if(is_file('app/'.$app.'/action/'.$ac.'.php')){

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
		
		if(is_file('data/'.$app.'_options.php')){
			$TS_APP['options'] = fileRead('data/'.$app.'_options.php');
			if($TS_APP['options']==''){
				$TS_APP['options'] = $tsMySqlCache->get($app.'_options');
			}
			
			if($TS_APP['options']['isenable']=='1' && $ac != 'admin') {
				tsNotice($app."应用关闭，请开启后访问！");
			}
		}
	}

	//控制前台ADMIN访问权限
	if($ac == 'admin' && $TS_USER['admin']['isadmin']!=1 && $app != 'system'){
		header("Location: ".SITE_URL);
		exit;
	}
	
	//控制后台访问权限
	if($TS_USER['admin']['isadmin'] != 1 && $app=='system' && $ac != 'login'){
		header("Location: ".tsUrl('system','login'));
		exit;
	}
	
	//控制插件设置权限
	if($TS_USER['admin']['isadmin'] != 1 && $in == 'edit'){
		header("Location: ".tsUrl('system','login'));
		exit;
	}
	
	//判断用户是否需要验证Email,管理员除外
	if($TS_SITE['base']['isverify']==1 && intval($TS_USER['user']['userid']) > 0 && $app != 'system' && $ac != 'admin'){
		
		$verifyUser = $new[$app]->find('user_info',array(
			'userid'=>intval($TS_USER['user']['userid']),
		));
		
		if(intval($verifyUser['isverify'])==0 && $app != 'user' && $TS_USER['user']['isadmin'] != 1){
			header("Location: ".tsUrl('user','verify'));
			exit;
		}
		
	}
	
	//判断用户是否上传头像,管理员除外
	if($TS_SITE['base']['isface']==1 && intval($TS_USER['user']['userid']) > 0 && $app != 'system' && $ac != 'admin'){
		
		$faceUser = $new[$app]->find('user_info',array(
			'userid'=>intval($TS_USER['user']['userid']),
		));
		
		if($faceUser['face']=='' && $app != 'user' && $TS_USER['user']['isadmin'] != 1){
			header("Location: ".tsUrl('user','set',array('ts'=>'face')));
			exit;
		}
	}
	
	//用户自动登录
	if($TS_USER['user']['userid']=='' && $_COOKIE['ts_email'] && $_COOKIE['ts_uptime']){
		
		$loginUserNum = $new[$app]->findCount('user_info',array(
			'email'=>$_COOKIE['ts_email'],
			'uptime'=>$_COOKIE['ts_uptime'],
		));
	
		if($loginUserNum > 0){
			
			$loginUserData = $new[$app]->find('user_info',array(
				'email'=>$_COOKIE['ts_email'],
			),'userid,username,path,face,isadmin,uptime');
			
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
		$public_plugins = fileRead('data/pubs_plugins.php');
		if($public_plugins==''){
			$public_plugins = $tsMySqlCache->get('pubs_plugins');
		}
	
		if ($public_plugins && is_array($public_plugins)) {
			foreach($public_plugins as $item) {
				if(is_file('plugins/pubs/'.$item.'/'.$item.'.php')) {
					include 'plugins/pubs/'.$item.'/'.$item.'.php';
				}
			}
		}
		
	
		//加载APP插件
		$active_plugins = fileRead('data/'.$app.'_plugins.php');
		if($active_plugins==''){
			$active_plugins = $tsMySqlCache->get($app.'_plugins');
		}
		
		if ($active_plugins && is_array($active_plugins)) {
			foreach($active_plugins as $item) {
				if(is_file('plugins/'.$app.'/'.$item.'/'.$item.'.php')) {
					include 'plugins/'.$app.'/'.$item.'/'.$item.'.php';
				}
			}
		}
	}
	
	//在执行action之前加载
	doAction('beforeAction');
	
	//全站通用数据加载
	if(is_file('thinksaas/common.php'))
		include 'thinksaas/common.php';
	
	//开始执行APP action
	if(is_file('app/'.$app.'/action/common.php'))
		include 'app/'.$app.'/action/common.php';
		
		
	//运行统计结束
	$time_end = getmicrotime();
	
	$runTime = $time_end - $time_start;
	$runTime = number_format($runTime,6);
	
	include 'app/'.$app.'/action/'.$ac.'.php';
	
}else{
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	echo 'No APP 404 Page！';
	exit;
}