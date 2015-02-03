<?php 
defined('IN_TS') or die('Access Denied.');
/*
 *豆瓣登陆
 *By QiuJun 2011-07-28
 */

//登录
function douban_login_html(){
	echo '<p><a href="'.SITE_URL.'index.php?app=pubs&ac=plugin&plugin=douban&in=login"><img  src="'.SITE_URL.'plugins/pubs/douban/login_with_douban_24.png"></a></p>';
}

function home_login_douban(){
	echo '<a href="'.SITE_URL.'index.php?app=pubs&ac=plugin&plugin=douban&in=login"><img align="absmiddle" src="'.SITE_URL.'plugins/pubs/douban/login_with_douban_18.png" alt="豆瓣登陆" /></a> ';
}

addAction('home_login', 'home_login_douban');


addAction('user_login_footer', 'douban_login_html');
addAction('user_register_footer', 'douban_login_html');