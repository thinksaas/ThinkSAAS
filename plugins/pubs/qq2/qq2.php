<?php 
defined('IN_TS') or die('Access Denied.');
/*
 *QQ登录插件
 *By QiuJun 2011-07-28
 */

//登录
function qq_login_html(){
	echo '<a href="'.SITE_URL.'index.php?app=pubs&ac=plugin&plugin=qq2&in=qq_login"><img align="absmiddle" src="'.SITE_URL.'plugins/pubs/qq2/qq_login.png"></a>';
}

addAction('user_login_footer', 'qq_login_html');
addAction('user_register_footer', 'qq_login_html');

addAction('pub_header_login', 'qq_login_html');