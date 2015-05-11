<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

// 管理入口
if (is_file ( 'app/' . $TS_URL['app'] . '/action/my/' . $TS_URL['my'] . '.php' )) {
	include_once 'app/' . $TS_URL['app'] . '/action/my/common.php';
	include_once 'app/' . $TS_URL['app'] . '/action/my/' . $TS_URL['my'] . '.php';
} else {
	qiMsg ( 'sorry:no index!' );
}