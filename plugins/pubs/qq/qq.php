<?php 
defined('IN_TS') or die('Access Denied.');
/*
 *QQ登录插件
 *By QiuJun 2011-07-28
 */

//登录
function qq_login_html(){
	echo '<p><a href="'.SITE_URL.'index.php?app=pubs&ac=plugin&plugin=qq&in=login"><img  src="'.SITE_URL.'plugins/pubs/qq/images/login.png"></a></p>';
}

function home_login_qq(){
	echo '<a href="'.SITE_URL.'index.php?app=pubs&ac=plugin&plugin=qq&in=login"><img align="absmiddle" src="'.SITE_URL.'plugins/pubs/qq/images/Connect_logo_6.png" alt="QQ登陆" /></a> ';
}

//同步QQ微博
function qq_add_t($arr){
	/*
	global $TS_USER;
	
	$userid = intval($TS_USER['userid']);
	

	$strOpen = '';
	if($userid){
		$strOpen = aac('user')->find('user_open',array(
			'userid'=>$userid,
			'sitename'=>'qq',
		));
	}
	
	if($strOpen){
		require_once(THINKROOT."/plugins/pubs/qq/API/qqConnectAPI.php");
		$qc = new QC($strOpen['access_token'],$strOpen['openid']);
		$ret = $qc->add_t($arr);
	}
	*/
}

addAction('home_login', 'home_login_qq');

addAction('user_login_footer', 'qq_login_html');
addAction('user_register_footer', 'qq_login_html');
addAction('qq_share', 'qq_add_t');