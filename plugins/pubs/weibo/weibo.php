<?php 
defined('IN_TS') or die('Access Denied.');
/*
 * 微博登入插件
 *
 * @ silenceper@gmail.com
 *   2012_09_26
 * */
function weibo_login_html(){
	echo '<p><a href="'.SITE_URL.'index.php?app=pubs&ac=plugin&plugin=weibo&in=login"><img src="'.SITE_URL.'plugins/pubs/weibo/images/weibo_login.png"></a></p>';
}

function home_login_weibo(){
	echo '<a href="'.SITE_URL.'index.php?app=pubs&ac=plugin&plugin=weibo&in=login"><img align="absmiddle" src="'.SITE_URL.'plugins/pubs/weibo/images/16x16.png" alt="微博登陆" /></a> ';
}

function weibo_update($content){
	/*
	global $TS_USER;
	
	$userid = intval($TS_USER['userid']);
	

	$strOpen = '';
	if($userid){
		$strOpen = aac('user')->find('user_open',array(
			'userid'=>$userid,
			'sitename'=>'weibo',
		));
	}
	
	
	if($_COOKIE['weibo_access_token'] && $_COOKIE['weibo_openid']){

	
		include_once( 'config.php' );
		include_once( 'saetv2.ex.class.php' );

		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_COOKIE['weibo_access_token'] );
		
		$c->update( $content );
	
	
	}else{
		
		if($strOpen){

			include_once( 'config.php' );
			include_once( 'saetv2.ex.class.php' );

			$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $strOpen['access_token'] );
			
			$c->update( $content );
		
		}
		
	}
	*/
}

addAction('home_login', 'home_login_weibo');

addAction('user_login_footer', 'weibo_login_html');
addAction('user_register_footer', 'weibo_login_html');
addAction('weibo_share', 'weibo_update');