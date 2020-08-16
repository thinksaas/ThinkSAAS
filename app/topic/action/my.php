<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

// 我的入口
if (is_file ( 'app/' . $TS_URL['app'] . '/action/my/' . $TS_URL['my'] . '.php' )) {
	$userid = aac('user')->isLogin();
	$strUser = aac('user')->getOneUser($userid);
	include_once 'app/' . $TS_URL['app'] . '/action/my/' . $TS_URL['my'] . '.php';
} else {
	qiMsg ( 'sorry:no index!' );
}