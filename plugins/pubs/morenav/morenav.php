<?php
defined('IN_TS') or die('Access Denied.');
//头部更多导航插件 
function morenav(){
	$arrNav = fileRead('data/plugins_pubs_morenav.php');
	if($arrNav==''){
		$arrNav = $GLOBALS['tsMySqlCache']->get('plugins_pubs_morenav');
	}
    include template('morenav','morenav');
}
addAction('pub_header_nav','morenav');