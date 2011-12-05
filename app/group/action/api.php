<?php
	/*
	 * API单入口
	 */
	 defined('IN_TS') or die('Access Denied.');

	if(is_file(TS_APP_API.'/'.$api.'.php')){
		require_once(TS_APP_API.'/'.$api.'.php');
	}else{
		qiMsg('sorry:no index!');
	}