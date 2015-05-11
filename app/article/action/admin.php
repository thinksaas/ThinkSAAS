<?php
defined('IN_TS') or die('Access Denied.');

// 管理入口
if (is_file('app/' . $TS_URL['app'] . '/action/admin/' . $mg . '.php')) {
	include_once 'app/' . $TS_URL['app'] . '/action/admin/' . $mg . '.php';
} else {
	qiMsg('sorry:no index!');
}
