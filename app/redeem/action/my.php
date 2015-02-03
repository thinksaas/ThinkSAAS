<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

// 管理入口
if (is_file ( 'app/' . $app . '/action/my/' . $my . '.php' )) {
	include_once 'app/' . $app . '/action/my/common.php';
	include_once 'app/' . $app . '/action/my/' . $my . '.php';
} else {
	qiMsg ( 'sorry:no index!' );
}