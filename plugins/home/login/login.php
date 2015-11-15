<?php 
defined('IN_TS') or die('Access Denied.'); 
//首页登录框
function login(){
	global $TS_USER;
	
	if($TS_USER['userid']){
		$strUser = aac('user')->getOneUser($TS_USER['userid']);
		$strUser['rolename'] = aac('user')->getRole($strUser['count_score']);
	}
	
	include template('login','login');
}

function login_css(){

	echo '<link href="'.SITE_URL.'plugins/home/login/style.css" rel="stylesheet" type="text/css" />';

}

addAction('home_index_right','login');
addAction('pub_header_top','login_css');