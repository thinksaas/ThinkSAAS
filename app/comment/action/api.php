<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

// api入口
if (is_file ( 'app/' . $TS_URL['app'] . '/action/api/' . $TS_URL['api'] . '.php' )) {
	include_once 'app/' . $TS_URL['app'] . '/action/api/' . $TS_URL['api'] . '.php';
} else {
	qiMsg ( 'sorry:no api!' );
}