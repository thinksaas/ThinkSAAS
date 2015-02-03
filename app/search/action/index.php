<?php 
defined('IN_TS') or die('Access Denied.');
switch($ts){
	case "":
		$title = '搜索';
		break;
	
	//搜索小组
	case "group":
		$title = '搜索小组';
		break;
		
	//搜索帖子
	case "topic":
		$title = '搜索帖子';
		break;
		
	//搜索用户
	case "user":
		$title = '搜索用户';
		break;
		
	//搜索文章
	case "article":
		$title = '搜索文章';
		break;
		
}

include template("index");