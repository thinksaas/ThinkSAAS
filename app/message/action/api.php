<?php
defined('IN_TS') or die('Access Denied.');
	/*
	 * API单入口
	 */
	 

	if(is_file(TS_APP_API.'/'.$api.'.php')){
		require_once(TS_APP_API.'/'.$api.'.php');
	}else{
		qiMsg('sorry:no index!');
	}