<?php 
defined('IN_TS') or die('Access Denied.');



switch($ts){
	case "":
		
		include template('cache');
		break;
	
	//删除全部
	case "delall":
		rmrf('cache/template');
		rmrf('cache/user');
		rmrf('cache/group');
		rmrf('cache/lang');
		qiMsg('缓存清除完毕！');
		break;
		
	//删除temp
	case "deltemp":
		rmrf('cache/template');
		qiMsg('缓存清除完毕！');
		break;
	
	//删除group
	case "delgroup":
		rmrf('cache/group');
		qiMsg('缓存清除完毕！');
		break;
		
	//删除user
	case "deluser":
		rmrf('cache/user');
		qiMsg('缓存清除完毕！');
		break;
		
	//删除语言包
	case "dellang":
		rmrf('cache/lang');
		qiMsg('缓存清除完毕！');
		break;
		
}