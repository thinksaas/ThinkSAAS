<?php 
/*
 *QQ登录插件
 *By QiuJun 2011-07-28
 */

function qq_login_html(){
	echo '<div><a href="'.SITE_URL.'index.php?app=pubs&ac=plugin&plugin=qq&in=redirect_to_login"><img align="absmiddle" src="'.SITE_URL.'plugins/pubs/qq/qq_login.png"></a></div>';
}


function qq_login_pub_header_html(){
	echo '<a href="'.SITE_URL.'index.php?app=pubs&ac=plugin&plugin=qq&in=redirect_to_login"><img align="absmiddle" src="'.SITE_URL.'plugins/pubs/qq/qq_login.png"></a>';
}

addAction('user_login_footer', 'qq_login_html');
addAction('user_register_footer', 'qq_login_html');

addAction('pub_header_login', 'qq_login_pub_header_html');